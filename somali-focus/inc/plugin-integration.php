<?php
/**
 * The ONLY file in this theme that talks to Somali Focus Core. Every
 * function here calls the plugin's public API when available and falls
 * back to safe, clearly-labelled placeholder data when it is not — so the
 * theme never fatals and never shows a broken page if the plugin is
 * missing or deactivated.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Somali Focus Core is active.
 *
 * @return bool
 */
function sf_theme_core_active() {
	return function_exists( 'somali_focus_core_is_active' ) && somali_focus_core_is_active();
}

/**
 * Admin notice when the required plugin is missing.
 */
function sf_theme_plugin_required_notice() {
	if ( sf_theme_core_active() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' .
		wp_kses_post( __( '<strong>Somali Focus theme</strong> works best with the <strong>Somali Focus Core</strong> plugin active — it provides Courses, Advisory, Research, Experts, Partners and site settings. The theme is displaying placeholder content until the plugin is installed and activated.', 'somali-focus' ) ) .
		'</p></div>';
}
add_action( 'admin_notices', 'sf_theme_plugin_required_notice' );

/**
 * Settings/options passthrough with theme-side fallback copy.
 *
 * @param string $key     Setting key.
 * @param string $default Fallback used only when the plugin is inactive
 *                        or has no value for this key.
 * @return string
 */
function sf_theme_option( $key, $default = '' ) {
	if ( function_exists( 'somali_focus_get_option' ) ) {
		return somali_focus_get_option( $key, $default );
	}
	return $default;
}

/**
 * Impact statistics block.
 *
 * @return array[]
 */
function sf_theme_get_stats() {
	if ( function_exists( 'somali_focus_get_stat_blocks' ) ) {
		return somali_focus_get_stat_blocks();
	}
	return array(
		array( 'number' => '500+', 'label' => __( 'Professionals Trained', 'somali-focus' ) ),
		array( 'number' => '50+', 'label' => __( 'Organizations Supported', 'somali-focus' ) ),
		array( 'number' => '100+', 'label' => __( 'Assignments Completed', 'somali-focus' ) ),
		array( 'number' => '10+', 'label' => __( 'Areas of Expertise', 'somali-focus' ) ),
	);
}

/**
 * Featured courses.
 *
 * @param int $count Number of courses.
 * @return WP_Post[]
 */
function sf_theme_get_courses( $count = 3 ) {
	if ( function_exists( 'somali_focus_get_courses' ) ) {
		return somali_focus_get_courses( $count );
	}
	return array();
}

/**
 * Advisory projects.
 *
 * @param int $count Number of items.
 * @return WP_Post[]
 */
function sf_theme_get_advisory_projects( $count = 3 ) {
	if ( function_exists( 'somali_focus_get_advisory_projects' ) ) {
		return somali_focus_get_advisory_projects( $count );
	}
	return array();
}

/**
 * Research & publications.
 *
 * @param int $count Number of items.
 * @return WP_Post[]
 */
function sf_theme_get_research( $count = 3 ) {
	if ( function_exists( 'somali_focus_get_research' ) ) {
		return somali_focus_get_research( $count );
	}
	return array();
}

/**
 * Experts.
 *
 * @param int $count Number of items.
 * @return WP_Post[]
 */
function sf_theme_get_experts( $count = 4 ) {
	if ( function_exists( 'somali_focus_get_experts' ) ) {
		return somali_focus_get_experts( $count );
	}
	return array();
}

/**
 * Partners.
 *
 * @param int $count Number of items.
 * @return WP_Post[]
 */
function sf_theme_get_partners( $count = 12 ) {
	if ( function_exists( 'somali_focus_get_partners' ) ) {
		return somali_focus_get_partners( $count );
	}
	return array();
}

/**
 * Testimonials.
 *
 * @param int $count Number of items.
 * @return WP_Post[]
 */
function sf_theme_get_testimonials( $count = 6 ) {
	if ( function_exists( 'somali_focus_get_testimonials' ) ) {
		return somali_focus_get_testimonials( $count );
	}
	return array();
}

/**
 * Meta passthrough.
 *
 * @param int|WP_Post $post    Post ID or object.
 * @param string      $key     Meta key without underscore/prefix.
 * @param mixed       $default Fallback.
 * @return mixed
 */
function sf_theme_meta( $post, $key, $default = '' ) {
	if ( function_exists( 'somali_focus_meta' ) ) {
		return somali_focus_meta( $post, $key, $default );
	}
	return $default;
}

/**
 * Render the plugin's course registration form for a given course, with a
 * graceful fallback message if the plugin is not active.
 *
 * @param int $course_id Course post ID.
 */
function sf_theme_registration_form( $course_id ) {
	if ( shortcode_exists( 'sf_course_registration_form' ) ) {
		echo do_shortcode( '[sf_course_registration_form course_id="' . absint( $course_id ) . '"]' );
		return;
	}
	echo '<p>' . esc_html__( 'Registration is temporarily unavailable.', 'somali-focus' ) . '</p>';
}

/**
 * Render the plugin's general service request form, with a graceful
 * fallback message if the plugin is not active.
 */
function sf_theme_service_request_form() {
	if ( shortcode_exists( 'sf_service_request_form' ) ) {
		echo do_shortcode( '[sf_service_request_form]' );
		return;
	}
	echo '<p>' . esc_html__( 'This form is temporarily unavailable. Please email us directly.', 'somali-focus' ) . '</p>';
}

/**
 * Render an optional ad slot. A silent no-op unless the plugin is active
 * AND that specific slot is enabled with code configured — safe to call
 * unconditionally from any template.
 *
 * @param string $slot_id One of: header_top, after_hero, before_content,
 *                        in_content, after_content, sidebar, footer.
 */
function sf_ad_slot( $slot_id ) {
	if ( function_exists( 'somali_focus_ad_slot' ) ) {
		somali_focus_ad_slot( $slot_id );
	}
}
