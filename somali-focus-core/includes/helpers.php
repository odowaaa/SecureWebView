<?php
/**
 * Public data API — the ONLY way the active theme should read plugin data.
 * Every function here degrades to a safe empty value; nothing fatals if
 * called before the plugin has finished loading.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a single value from the consolidated Somali Focus settings option.
 *
 * @param string $key     Dot-free settings key, e.g. 'org_name', 'stat_1_number'.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function somali_focus_get_option( $key, $default = '' ) {
	$settings = get_option( 'somali_focus_settings', array() );
	if ( isset( $settings[ $key ] ) && '' !== $settings[ $key ] ) {
		return $settings[ $key ];
	}
	return $default;
}

/**
 * Retrieve the four homepage impact statistics as a simple array.
 *
 * @return array[] Each item: number, label.
 */
function somali_focus_get_stat_blocks() {
	$defaults = array(
		1 => array( 'number' => '500+', 'label' => __( 'Professionals Trained', 'somali-focus' ) ),
		2 => array( 'number' => '50+', 'label' => __( 'Organizations Supported', 'somali-focus' ) ),
		3 => array( 'number' => '100+', 'label' => __( 'Assignments Completed', 'somali-focus' ) ),
		4 => array( 'number' => '10+', 'label' => __( 'Areas of Expertise', 'somali-focus' ) ),
	);

	$blocks = array();
	foreach ( $defaults as $i => $default ) {
		$blocks[] = array(
			'number' => somali_focus_get_option( "stat_{$i}_number", $default['number'] ),
			'label'  => somali_focus_get_option( "stat_{$i}_label", $default['label'] ),
		);
	}
	return $blocks;
}

/**
 * Generic CPT query wrapper.
 *
 * @param string $post_type Registered post type slug.
 * @param array  $args      Extra WP_Query args (merged over sane defaults).
 * @return WP_Post[]
 */
function somali_focus_query( $post_type, $args = array() ) {
	if ( ! post_type_exists( $post_type ) ) {
		return array();
	}

	$defaults = array(
		'post_type'              => $post_type,
		'posts_per_page'         => 6,
		'post_status'            => 'publish',
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_term_cache' => true,
		'update_post_meta_cache' => true,
	);

	$query = new WP_Query( wp_parse_args( $args, $defaults ) );
	return $query->posts;
}

/**
 * Featured (or most recent) courses.
 *
 * @param int $count Number of courses to return.
 * @return WP_Post[]
 */
function somali_focus_get_courses( $count = 3 ) {
	return somali_focus_query( 'sf_course', array( 'posts_per_page' => $count ) );
}

/**
 * Advisory projects/services.
 *
 * @param int $count Number of items to return.
 * @return WP_Post[]
 */
function somali_focus_get_advisory_projects( $count = 3 ) {
	return somali_focus_query( 'sf_advisory', array( 'posts_per_page' => $count ) );
}

/**
 * Research & publications.
 *
 * @param int $count Number of items to return.
 * @return WP_Post[]
 */
function somali_focus_get_research( $count = 3 ) {
	return somali_focus_query( 'sf_research', array( 'posts_per_page' => $count ) );
}

/**
 * Experts/team.
 *
 * @param int $count Number of experts to return.
 * @return WP_Post[]
 */
function somali_focus_get_experts( $count = 4 ) {
	return somali_focus_query( 'sf_expert', array( 'posts_per_page' => $count, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
}

/**
 * Partner organizations.
 *
 * @param int $count Number of partners to return.
 * @return WP_Post[]
 */
function somali_focus_get_partners( $count = 12 ) {
	return somali_focus_query( 'sf_partner', array( 'posts_per_page' => $count, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
}

/**
 * Client testimonials.
 *
 * @param int $count Number of testimonials to return.
 * @return WP_Post[]
 */
function somali_focus_get_testimonials( $count = 6 ) {
	return somali_focus_query( 'sf_testimonial', array( 'posts_per_page' => $count ) );
}

/**
 * Shorthand meta getter.
 *
 * @param int|WP_Post $post Post ID or object.
 * @param string      $key  Meta key without leading underscore.
 * @param mixed       $default Fallback.
 * @return mixed
 */
function somali_focus_meta( $post, $key, $default = '' ) {
	$post_id = is_object( $post ) ? $post->ID : (int) $post;
	$value   = get_post_meta( $post_id, '_sf_' . $key, true );
	return ( '' === $value || false === $value ) ? $default : $value;
}

/**
 * Whether the Somali Focus Core plugin is running — theme should not rely
 * on class checks, this is the single source of truth other code can call
 * (also used by the theme's own `function_exists()` guard).
 *
 * @return bool
 */
function somali_focus_core_is_active() {
	return defined( 'SOMALI_FOCUS_CORE_VERSION' );
}
