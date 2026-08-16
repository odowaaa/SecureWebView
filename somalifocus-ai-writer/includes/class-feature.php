<?php
/**
 * The "Article Generation" experiment/feature: registers the
 * sf/generate-article Ability and the admin screen used to run it.
 *
 * Registered with the official "AI" plugin via the documented
 * `wpai_register_features` extension point (see the plugin's own
 * docs/experiments/custom-experiment-reference.md), so it automatically
 * gets the same enable/disable toggle on Settings → AI as every built-in
 * feature, without needing its own separate settings screen for that.
 *
 * @package SomaliFocusAiWriter
 */

declare( strict_types=1 );

namespace SomaliFocus\AiWriter;

use WordPress\AI\Abstracts\Abstract_Feature;
use WordPress\AI\Experiments\Experiment_Category;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Article Generation feature.
 *
 * @since 1.0.0
 */
class Article_Generation_Feature extends Abstract_Feature {

	/**
	 * The admin page hook suffix returned by add_menu_page(), captured so
	 * enqueue_assets() can match against the real value WordPress assigned
	 * rather than assuming its format.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $admin_page_hook = '';

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	public static function get_id(): string {
		return 'somalifocus-article-generation';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	protected function load_metadata(): array {
		return array(
			'label'       => __( 'SomaliFocus Article Generation', 'somalifocus-ai-writer' ),
			'description' => __( 'Generates a complete, publication-ready draft article (title, full body, excerpt, SEO metadata) from a topic. Requires a connected AI provider that supports text generation. Never publishes automatically.', 'somalifocus-ai-writer' ),
			'category'    => Experiment_Category::ADMIN,
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 */
	public function register(): void {
		add_action( 'wp_abilities_api_init', array( $this, 'register_abilities' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Registers the sf/generate-article ability.
	 *
	 * @since 1.0.0
	 */
	public function register_abilities(): void {
		wp_register_ability(
			'sf/generate-article',
			array(
				'label'         => __( 'Generate Article', 'somalifocus-ai-writer' ),
				'description'   => $this->get_description(),
				'ability_class' => Article_Generation_Ability::class,
			)
		);
	}

	/**
	 * Registers the "Generate Article" admin page.
	 *
	 * Nested under Settings → AI's own top-level "AI" menu would be
	 * convenient, but that menu's structure is internal to the "AI"
	 * plugin and not a documented extension point; a dedicated top-level
	 * page keeps this plugin decoupled from that internal structure while
	 * still being easy to find.
	 *
	 * @since 1.0.0
	 */
	public function register_admin_page(): void {
		if ( ! $this->is_enabled() ) {
			return;
		}

		$hook = add_menu_page(
			__( 'SomaliFocus AI Writer', 'somalifocus-ai-writer' ),
			__( 'AI Writer', 'somalifocus-ai-writer' ),
			'edit_posts',
			'somalifocus-ai-writer',
			array( Admin_Page::class, 'render' ),
			'dashicons-edit-large',
			30
		);

		$this->admin_page_hook = is_string( $hook ) ? $hook : '';
	}

	/**
	 * Enqueues the admin page's vanilla JS/CSS assets, only on its own screen.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 */
	public function enqueue_assets( string $hook_suffix ): void {
		if ( '' === $this->admin_page_hook || $hook_suffix !== $this->admin_page_hook ) {
			return;
		}

		if ( ! $this->is_enabled() ) {
			return;
		}

		wp_enqueue_script( 'wp-api-fetch' );
		wp_enqueue_script( 'wp-i18n' );

		wp_enqueue_style(
			'sf-ai-writer-admin',
			SF_AI_WRITER_URL . 'admin/assets/admin.css',
			array(),
			SF_AI_WRITER_VERSION
		);

		wp_enqueue_script(
			'sf-ai-writer-admin',
			SF_AI_WRITER_URL . 'admin/assets/admin.js',
			array( 'wp-api-fetch', 'wp-i18n' ),
			SF_AI_WRITER_VERSION,
			true
		);

		$seo = SEO_Integration::get_meta_keys();

		wp_localize_script(
			'sf-ai-writer-admin',
			'SFAIWriterData',
			array(
				'abilityRestPath' => '/wp-abilities/v1/abilities/sf/generate-article/run',
				'postsRestPath'   => '/wp/v2/posts',
				'categoriesPath'  => '/wp/v2/categories',
				'tagsPath'        => '/wp/v2/tags',
				'editPostUrlBase' => admin_url( 'post.php?action=edit&post=' ),
				'seoMetaKeys'     => array(
					'title'         => $seo['title_key'],
					'description'   => $seo['description_key'],
					'focusKeyword'  => $seo['focus_keyword_key'],
				),
				'seoPluginActive' => $seo['plugin'],
			)
		);
	}
}
