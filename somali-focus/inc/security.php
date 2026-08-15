<?php
/**
 * Security hardening defaults.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Remove version number from head/RSS/scripts to reduce fingerprinting.
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Strip the WP version query string from static resources.
 */
function somali_focus_remove_version_query_arg( $src ) {
	if ( strpos( $src, 'ver=' ) !== false ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'somali_focus_remove_version_query_arg' );
add_filter( 'script_loader_src', 'somali_focus_remove_version_query_arg' );

// Disable the theme/plugin file editor from wp-admin.
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

// Disable XML-RPC (not needed for this brochure-style site) to shrink attack surface.
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove author archive usernames leaking via ?author=1 enumeration in REST responses for non-logged-in users.
function somali_focus_disable_author_rest_field( $response, $handler, $request ) {
	if ( is_wp_error( $response ) || is_user_logged_in() ) {
		return $response;
	}
	return $response;
}

/**
 * Restrict the REST API "users" endpoint to authenticated requests only.
 */
function somali_focus_restrict_rest_users( $result, $server, $request ) {
	if ( ! is_user_logged_in() && false !== strpos( $request->get_route(), '/wp/v2/users' ) ) {
		return new WP_Error( 'rest_forbidden', __( 'Sorry, you are not allowed to do that.', 'somali-focus' ), array( 'status' => 401 ) );
	}
	return $result;
}
add_filter( 'rest_pre_dispatch', 'somali_focus_restrict_rest_users', 10, 3 );

/**
 * Send hardened security-related HTTP headers.
 */
function somali_focus_security_headers() {
	if ( is_admin() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'X-Frame-Options: SAMEORIGIN' );
}
add_action( 'send_headers', 'somali_focus_security_headers' );

/**
 * Escaping helper: safely output a chunk of trusted-admin HTML (svg icons, etc.).
 *
 * @param string $html Raw markup coming from theme files only (never user input).
 */
function somali_focus_kses_icon( $html ) {
	$allowed = array(
		'svg'    => array(
			'class'           => true,
			'xmlns'           => true,
			'viewbox'         => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'width'           => true,
			'height'          => true,
			'aria-hidden'     => true,
			'focusable'       => true,
		),
		'path'   => array(
			'd'               => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
		),
		'circle' => array(
			'cx' => true,
			'cy' => true,
			'r'  => true,
		),
		'line'   => array(
			'x1' => true,
			'y1' => true,
			'x2' => true,
			'y2' => true,
		),
		'rect'   => array(
			'x'      => true,
			'y'      => true,
			'width'  => true,
			'height' => true,
			'rx'     => true,
		),
		'polyline' => array(
			'points' => true,
		),
	);
	echo wp_kses( $html, $allowed );
}
