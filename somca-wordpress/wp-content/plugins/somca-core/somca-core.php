<?php
/**
 * Plugin Name: SomCA Core
 * Plugin URI: https://somca.org
 * Description: Core engine for Somali Climate Action (SomCA) — climate hotspots, reports, alerts, automated climate-data collection and REST/map/chart tools for the SomCA theme.
 * Version: 1.0.0
 * Author: Somali Climate Action
 * Author URI: https://somca.org
 * License: GPL v2 or later
 * Text Domain: somca-core
 * Requires PHP: 7.4
 * Requires at least: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SOMCA_CORE_VERSION', '1.0.0' );
define( 'SOMCA_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SOMCA_CORE_URL', plugin_dir_url( __FILE__ ) );

final class SomCA_Core {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->includes();
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		register_activation_hook( __FILE__, array( 'SomCA_Data_Fetcher', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'SomCA_Data_Fetcher', 'deactivate' ) );
		register_activation_hook( __FILE__, array( $this, 'on_activate' ) );
	}

	private function includes() {
		require_once SOMCA_CORE_PATH . 'includes/class-somca-cpt.php';
		require_once SOMCA_CORE_PATH . 'includes/class-somca-meta.php';
		require_once SOMCA_CORE_PATH . 'includes/class-somca-data-fetcher.php';
		require_once SOMCA_CORE_PATH . 'includes/class-somca-rest.php';
		require_once SOMCA_CORE_PATH . 'includes/class-somca-shortcodes.php';
		require_once SOMCA_CORE_PATH . 'includes/class-somca-admin.php';
		require_once SOMCA_CORE_PATH . 'includes/class-somca-widgets.php';

		SomCA_CPT::instance();
		SomCA_Meta::instance();
		SomCA_Data_Fetcher::instance();
		SomCA_REST::instance();
		SomCA_Shortcodes::instance();
		SomCA_Admin::instance();
		SomCA_Widgets::instance();
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'somca-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function on_activate() {
		SomCA_CPT::instance()->register_post_types();
		SomCA_CPT::instance()->register_taxonomies();
		flush_rewrite_rules();
	}
}

SomCA_Core::instance();
