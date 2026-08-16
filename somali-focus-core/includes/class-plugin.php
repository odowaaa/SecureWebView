<?php
/**
 * Main plugin bootstrap class.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Somali_Focus_Core_Plugin
 */
final class Somali_Focus_Core_Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Somali_Focus_Core_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Somali_Focus_Core_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor: load dependencies and hook init.
	 */
	private function __construct() {
		$this->includes();
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'maybe_upgrade' ), 20 );
	}

	/**
	 * Detect a version change on an already-active install (as opposed to
	 * a fresh activation, which is handled by self::activate()) and re-run
	 * the same rewrite-flush + site-structure steps. Without this, updating
	 * the plugin's files while it stays active would leave old rewrite
	 * rules and a stale site structure in place indefinitely.
	 */
	public function maybe_upgrade() {
		$installed = get_option( 'somali_focus_core_db_version' );
		if ( SOMALI_FOCUS_CORE_VERSION === $installed ) {
			return;
		}

		flush_rewrite_rules();

		if ( is_readable( SOMALI_FOCUS_CORE_DIR . 'includes/site-setup.php' ) ) {
			require_once SOMALI_FOCUS_CORE_DIR . 'includes/site-setup.php';
			somali_focus_setup_site_structure();
		}

		update_option( 'somali_focus_core_db_version', SOMALI_FOCUS_CORE_VERSION );
	}

	/**
	 * Load translation files.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'somali-focus', false, dirname( SOMALI_FOCUS_CORE_BASENAME ) . '/languages' );
	}

	/**
	 * Require all plugin files.
	 */
	private function includes() {
		$files = array(
			'includes/helpers.php',
			'includes/security.php',
			'includes/post-types.php',
			'includes/taxonomies.php',
			'includes/meta-fields.php',
			'includes/settings.php',
			'includes/ad-slots.php',
			'includes/forms.php',
			'includes/notifications.php',
			'includes/shortcodes.php',
			'includes/template-loader.php',
			'includes/demo-content.php',
			'includes/site-setup.php',
		);

		foreach ( $files as $file ) {
			$path = SOMALI_FOCUS_CORE_DIR . $file;
			if ( is_readable( $path ) ) {
				require_once $path;
			}
		}

		if ( is_admin() ) {
			require_once SOMALI_FOCUS_CORE_DIR . 'admin/admin-menu.php';
			require_once SOMALI_FOCUS_CORE_DIR . 'admin/settings-page.php';
		}
	}

	/**
	 * Activation: register CPTs so rewrite rules exist, then flush.
	 */
	public static function activate() {
		require_once SOMALI_FOCUS_CORE_DIR . 'includes/post-types.php';
		require_once SOMALI_FOCUS_CORE_DIR . 'includes/taxonomies.php';
		somali_focus_register_taxonomies();
		somali_focus_register_post_types();
		flush_rewrite_rules();

		if ( false === get_option( 'somali_focus_settings' ) ) {
			require_once SOMALI_FOCUS_CORE_DIR . 'includes/settings.php';
			update_option( 'somali_focus_settings', somali_focus_default_settings() );
		}

		require_once SOMALI_FOCUS_CORE_DIR . 'includes/security.php';
		require_once SOMALI_FOCUS_CORE_DIR . 'includes/helpers.php';
		require_once SOMALI_FOCUS_CORE_DIR . 'includes/demo-content.php';
		somali_focus_install_demo_content();

		require_once SOMALI_FOCUS_CORE_DIR . 'includes/site-setup.php';
		somali_focus_setup_site_structure();

		update_option( 'somali_focus_core_db_version', SOMALI_FOCUS_CORE_VERSION );
	}

	/**
	 * Deactivation: flush rewrites only, keep all content and settings.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
