<?php
/**
 * Somali Focus theme bootstrap.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SOMALI_FOCUS_VERSION', '1.0.0' );
define( 'SOMALI_FOCUS_DIR', get_template_directory() );
define( 'SOMALI_FOCUS_URI', get_template_directory_uri() );

$somali_focus_includes = array(
	'/inc/plugin-integration.php',
	'/inc/setup.php',
	'/inc/enqueue.php',
	'/inc/security.php',
	'/inc/seo.php',
	'/inc/customizer.php',
	'/inc/navigation.php',
	'/inc/template-functions.php',
);

foreach ( $somali_focus_includes as $somali_focus_file ) {
	$somali_focus_path = SOMALI_FOCUS_DIR . $somali_focus_file;
	if ( is_readable( $somali_focus_path ) ) {
		require_once $somali_focus_path;
	}
}
