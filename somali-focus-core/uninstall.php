<?php
/**
 * Uninstall handler. Only removes the plugin's own settings — Courses,
 * Advisory Projects, Research, Experts, Partners, Testimonials,
 * Registrations and Service Requests are left untouched so real
 * organizational data is never lost by uninstalling the plugin.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'somali_focus_settings' );

if ( is_multisite() ) {
	delete_site_option( 'somali_focus_settings' );
}
