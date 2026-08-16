<?php
/**
 * Plugin Name:       Somali Focus Core
 * Plugin URI:        https://somalifocus.org
 * Description:       Core business logic and data structures for the Somali Focus website — Courses, Advisory Projects, Research, Experts, Partners, Testimonials, registrations, service requests and site settings. Required by the Somali Focus theme; works independently of it.
 * Version:           3.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Somali Focus
 * Author URI:        https://somalifocus.org
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       somali-focus
 * Domain Path:       /languages
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SOMALI_FOCUS_CORE_VERSION', '3.0.0' );
define( 'SOMALI_FOCUS_CORE_FILE', __FILE__ );
define( 'SOMALI_FOCUS_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SOMALI_FOCUS_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'SOMALI_FOCUS_CORE_BASENAME', plugin_basename( __FILE__ ) );

require_once SOMALI_FOCUS_CORE_DIR . 'includes/class-plugin.php';

/**
 * Boot the plugin.
 */
function somali_focus_core() {
	return Somali_Focus_Core_Plugin::instance();
}
somali_focus_core();

register_activation_hook( __FILE__, array( 'Somali_Focus_Core_Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Somali_Focus_Core_Plugin', 'deactivate' ) );
