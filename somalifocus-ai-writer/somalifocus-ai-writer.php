<?php
/**
 * Plugin Name:       SomaliFocus AI Article Writer
 * Plugin URI:        https://somalifocus.org
 * Description:       Generates complete, publication-ready draft articles for SomaliFocus from a topic, using the WordPress AI Client and whichever AI Connector plugin (Anthropic, OpenAI, Google, etc.) is already configured under Settings &rarr; Connectors. Never publishes automatically &mdash; every result is a reviewable draft.
 * Version:           1.0.0
 * Requires at least: 6.9
 * Requires PHP:      7.4
 * Author:            Somali Focus
 * Author URI:        https://somalifocus.org
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       somalifocus-ai-writer
 * Domain Path:       /languages
 *
 * @package SomaliFocusAiWriter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SF_AI_WRITER_VERSION', '1.0.0' );
define( 'SF_AI_WRITER_FILE', __FILE__ );
define( 'SF_AI_WRITER_DIR', plugin_dir_path( __FILE__ ) );
define( 'SF_AI_WRITER_URL', plugin_dir_url( __FILE__ ) );

/**
 * Checks whether the platform this plugin builds on is actually present:
 * the official "AI" plugin (which ships the Abstract_Feature/Abstract_Ability
 * base classes and the wp_ai_client_prompt() helper) and at least the ability
 * to register WordPress Abilities.
 *
 * This plugin deliberately does not bundle its own copy of that framework and
 * does not implement its own AI-provider credential handling — it is built
 * entirely on top of the Connectors and AI Client already provided by the
 * "AI" plugin, so that the same connector configuration (e.g. the Anthropic
 * API key under Settings -> Connectors) is reused rather than duplicated.
 *
 * @since 1.0.0
 *
 * @return bool True if the required platform classes/functions are present.
 */
function sf_ai_writer_platform_available(): bool {
	return class_exists( '\WordPress\AI\Abstracts\Abstract_Feature' )
		&& class_exists( '\WordPress\AI\Abstracts\Abstract_Ability' )
		&& function_exists( 'wp_ai_client_prompt' )
		&& function_exists( 'wp_register_ability' );
}

/**
 * Shows an admin notice explaining what is missing, without ever fataling.
 *
 * Mirrors the graceful-degradation pattern already used by the Somali Focus
 * theme when its Core plugin is inactive: tell the admin what to do, do not
 * break the dashboard.
 *
 * @since 1.0.0
 */
function sf_ai_writer_missing_platform_notice(): void {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$ai_plugin_active = class_exists( '\WordPress\AI\Abstracts\Abstract_Feature' );
	?>
	<div class="notice notice-warning">
		<p>
			<strong><?php esc_html_e( 'SomaliFocus AI Article Writer', 'somalifocus-ai-writer' ); ?></strong>
			&mdash;
			<?php if ( ! $ai_plugin_active ) : ?>
				<?php esc_html_e( 'this plugin requires the official "AI" plugin (AI features, experiments and capabilities for WordPress) to be installed and active. Install it from the Plugins screen, then activate at least one AI Connector plugin (e.g. AI Provider for Anthropic) and configure it under Settings → Connectors.', 'somalifocus-ai-writer' ); ?>
			<?php else : ?>
				<?php esc_html_e( 'the required AI Client functions were not found. This usually means the "AI" plugin needs to be updated to a version that supports WordPress 7.0\'s built-in AI Client, or (on WordPress 6.9) that the wordpress/php-ai-client package is missing.', 'somalifocus-ai-writer' ); ?>
			<?php endif; ?>
		</p>
	</div>
	<?php
}

/**
 * Boots the plugin once all other plugins have loaded, so the "AI" plugin's
 * classes are guaranteed to be available if it is active.
 *
 * @since 1.0.0
 */
function sf_ai_writer_bootstrap(): void {
	load_plugin_textdomain( 'somalifocus-ai-writer', false, dirname( plugin_basename( SF_AI_WRITER_FILE ) ) . '/languages' );

	if ( ! sf_ai_writer_platform_available() ) {
		add_action( 'admin_notices', 'sf_ai_writer_missing_platform_notice' );
		return;
	}

	require_once SF_AI_WRITER_DIR . 'includes/class-seo-integration.php';
	require_once SF_AI_WRITER_DIR . 'includes/class-ability.php';
	require_once SF_AI_WRITER_DIR . 'includes/class-feature.php';
	require_once SF_AI_WRITER_DIR . 'admin/class-admin-page.php';

	add_action(
		'wpai_register_features',
		static function ( $registry ): void {
			$registry->register_feature( new SomaliFocus\AiWriter\Article_Generation_Feature() );
		}
	);

	add_action( 'init', 'sf_ai_writer_register_fallback_meta' );
}
add_action( 'plugins_loaded', 'sf_ai_writer_bootstrap', 20 );

/**
 * Registers the fallback SEO meta keys (used only when no supported SEO
 * plugin is detected) with `show_in_rest`, so they can actually be set
 * through the standard `POST /wp/v2/posts` `meta` payload when the admin
 * clicks "Create Draft". Third-party SEO plugins' own meta keys are
 * intentionally left alone here — registering them ourselves would be
 * presumptuous about a schema this plugin does not own; the admin page
 * sends those best-effort and always shows the raw values for manual
 * entry as a fallback.
 *
 * @since 1.0.0
 */
function sf_ai_writer_register_fallback_meta(): void {
	$keys = array(
		\SomaliFocus\AiWriter\SEO_Integration::FALLBACK_TITLE_KEY,
		\SomaliFocus\AiWriter\SEO_Integration::FALLBACK_DESCRIPTION_KEY,
		\SomaliFocus\AiWriter\SEO_Integration::FALLBACK_FOCUS_KEY,
	);

	foreach ( $keys as $key ) {
		register_post_meta(
			'post',
			$key,
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => static function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
