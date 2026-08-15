<?php
/**
 * Fallback front-end templates. If the active theme does not provide its
 * own archive-{post_type}.php / single-{post_type}.php, the plugin serves
 * a plain but fully functional version so content is never inaccessible —
 * this is what keeps data alive across a theme switch.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post types this plugin can supply a fallback front-end template for.
 *
 * @return string[]
 */
function somali_focus_fallback_post_types() {
	return array( 'sf_course', 'sf_advisory', 'sf_research', 'sf_expert' );
}

/**
 * Supply an archive fallback only when the theme did not resolve one.
 *
 * @param string $template Template path resolved so far.
 * @return string
 */
function somali_focus_archive_template_fallback( $template ) {
	if ( $template ) {
		return $template;
	}
	foreach ( somali_focus_fallback_post_types() as $post_type ) {
		if ( is_post_type_archive( $post_type ) ) {
			$file = SOMALI_FOCUS_CORE_DIR . 'templates/archive-' . $post_type . '.php';
			if ( file_exists( $file ) ) {
				wp_enqueue_style( 'somali-focus-core-fallback', SOMALI_FOCUS_CORE_URL . 'public/css/fallback-templates.css', array(), SOMALI_FOCUS_CORE_VERSION );
				return $file;
			}
		}
	}
	return $template;
}
add_filter( 'archive_template', 'somali_focus_archive_template_fallback', 20 );

/**
 * Supply a single fallback only when the theme did not resolve one.
 *
 * @param string $template Template path resolved so far.
 * @return string
 */
function somali_focus_single_template_fallback( $template ) {
	if ( $template ) {
		return $template;
	}
	$post_type = get_post_type();
	if ( in_array( $post_type, somali_focus_fallback_post_types(), true ) ) {
		$file = SOMALI_FOCUS_CORE_DIR . 'templates/single-' . $post_type . '.php';
		if ( file_exists( $file ) ) {
			wp_enqueue_style( 'somali-focus-core-fallback', SOMALI_FOCUS_CORE_URL . 'public/css/fallback-templates.css', array(), SOMALI_FOCUS_CORE_VERSION );
			return $file;
		}
	}
	return $template;
}
add_filter( 'single_template', 'somali_focus_single_template_fallback', 20 );

/**
 * Admin notice when the theme in use does not appear to be Somali Focus
 * aware and the plugin is silently supplying fallback templates.
 */
function somali_focus_maybe_theme_notice() {
	if ( ! current_user_can( somali_focus_manage_cap() ) ) {
		return;
	}
	if ( function_exists( 'sf_theme_get_courses' ) ) {
		return; // The active theme declares itself Somali Focus aware.
	}
	echo '<div class="notice notice-info"><p>' . wp_kses_post( __( '<strong>Somali Focus Core</strong> is active and serving basic fallback templates for Courses, Advisory, Research and Experts. Install the Somali Focus theme for the full designed experience.', 'somali-focus' ) ) . '</p></div>';
}
add_action( 'admin_notices', 'somali_focus_maybe_theme_notice' );
