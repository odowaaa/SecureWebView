<?php
/**
 * SEO plugin integration: maps generated SEO fields onto whichever SEO
 * plugin's own meta keys are active, following the exact same detection
 * pattern the official "AI" plugin already uses for Meta Description
 * Generation (WordPress\AI\Abilities\Meta_Description\SEO_Integration),
 * so this plugin never introduces a second, conflicting set of SEO meta
 * boxes.
 *
 * @package SomaliFocusAiWriter
 */

declare( strict_types=1 );

namespace SomaliFocus\AiWriter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detects the active SEO plugin and returns the meta keys it reads.
 *
 * @since 1.0.0
 */
class SEO_Integration {

	/**
	 * Fallback meta keys used when no supported SEO plugin is active. Still
	 * saved as ordinary postmeta so the values are not lost and remain
	 * visible to a developer via a custom field or REST, even though no
	 * SEO plugin will render them automatically.
	 *
	 * @since 1.0.0
	 */
	public const FALLBACK_TITLE_KEY       = 'sf_ai_seo_title';
	public const FALLBACK_DESCRIPTION_KEY = 'sf_ai_meta_description';
	public const FALLBACK_FOCUS_KEY       = 'sf_ai_focus_keyword';

	/**
	 * Supported SEO plugins and the meta keys they use for each field.
	 *
	 * AIOSEO's focus-keyphrase storage is a serialized structure rather
	 * than a single string; writing it directly here would risk
	 * corrupting other AIOSEO analysis data, so `focus_keyword_key` is
	 * intentionally null for it — the value is still returned to the
	 * admin in the review screen for manual entry into AIOSEO's own panel.
	 *
	 * @since 1.0.0
	 *
	 * @return array<string, array{file: string, title_key: string, description_key: string, focus_keyword_key: ?string}>
	 */
	public static function get_supported_plugins(): array {
		$plugins = array(
			'yoast-seo'      => array(
				'file'              => 'wordpress-seo/wp-seo.php',
				'title_key'         => '_yoast_wpseo_title',
				'description_key'   => '_yoast_wpseo_metadesc',
				'focus_keyword_key' => '_yoast_wpseo_focuskw',
			),
			'rank-math'      => array(
				'file'              => 'seo-by-rank-math/rank-math.php',
				'title_key'         => 'rank_math_title',
				'description_key'   => 'rank_math_description',
				'focus_keyword_key' => 'rank_math_focus_keyword',
			),
			'all-in-one-seo' => array(
				'file'              => 'all-in-one-seo-pack/all_in_one_seo_pack.php',
				'title_key'         => '_aioseo_title',
				'description_key'   => '_aioseo_description',
				'focus_keyword_key' => null,
			),
			'seopress'       => array(
				'file'              => 'wp-seopress/seopress.php',
				'title_key'         => '_seopress_titles_title',
				'description_key'   => '_seopress_titles_desc',
				'focus_keyword_key' => '_seopress_analysis_target_kw',
			),
		);

		/**
		 * Filters the list of supported SEO plugins for SomaliFocus AI Writer.
		 *
		 * @since 1.0.0
		 *
		 * @param array<string, array{file: string, title_key: string, description_key: string, focus_keyword_key: ?string}> $plugins
		 */
		return (array) apply_filters( 'sf_ai_writer_seo_plugins', $plugins );
	}

	/**
	 * Detects the currently active SEO plugin, matching the same
	 * transient-cached detection the "AI" plugin's Meta Description
	 * feature uses so both agree on which plugin is active.
	 *
	 * @since 1.0.0
	 *
	 * @return string|null The slug of the active SEO plugin, or null.
	 */
	public static function detect_active_plugin(): ?string {
		$active_plugin = get_transient( 'wpai_active_seo_plugin' );
		if ( ! empty( $active_plugin ) && is_string( $active_plugin ) ) {
			return $active_plugin;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		foreach ( self::get_supported_plugins() as $slug => $info ) {
			if ( is_plugin_active( $info['file'] ) ) {
				set_transient( 'wpai_active_seo_plugin', $slug );
				return $slug;
			}
		}

		return null;
	}

	/**
	 * Returns the meta key map to use for the active (or given) SEO plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $plugin_slug Optional. If null, auto-detects.
	 * @return array{title_key: string, description_key: string, focus_keyword_key: ?string, plugin: ?string}
	 */
	public static function get_meta_keys( ?string $plugin_slug = null ): array {
		if ( null === $plugin_slug ) {
			$plugin_slug = self::detect_active_plugin();
		}

		$plugins = self::get_supported_plugins();

		if ( $plugin_slug && isset( $plugins[ $plugin_slug ] ) ) {
			return array(
				'title_key'         => $plugins[ $plugin_slug ]['title_key'],
				'description_key'   => $plugins[ $plugin_slug ]['description_key'],
				'focus_keyword_key' => $plugins[ $plugin_slug ]['focus_keyword_key'],
				'plugin'            => $plugin_slug,
			);
		}

		return array(
			'title_key'         => self::FALLBACK_TITLE_KEY,
			'description_key'   => self::FALLBACK_DESCRIPTION_KEY,
			'focus_keyword_key' => self::FALLBACK_FOCUS_KEY,
			'plugin'            => null,
		);
	}
}
