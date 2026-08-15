<?php
/**
 * Custom taxonomies for the Somali Focus content types.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom taxonomies.
 */
function somali_focus_register_taxonomies() {

	register_taxonomy(
		'course_category',
		array( 'sf_course' ),
		array(
			'labels'            => array(
				'name'          => __( 'Course Categories', 'somali-focus' ),
				'singular_name' => __( 'Course Category', 'somali-focus' ),
				'search_items'  => __( 'Search Course Categories', 'somali-focus' ),
				'all_items'     => __( 'All Course Categories', 'somali-focus' ),
				'edit_item'     => __( 'Edit Course Category', 'somali-focus' ),
				'add_new_item'  => __( 'Add New Course Category', 'somali-focus' ),
				'menu_name'     => __( 'Course Categories', 'somali-focus' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'training-category' ),
		)
	);

	register_taxonomy(
		'advisory_category',
		array( 'sf_advisory' ),
		array(
			'labels'            => array(
				'name'          => __( 'Advisory Categories', 'somali-focus' ),
				'singular_name' => __( 'Advisory Category', 'somali-focus' ),
				'search_items'  => __( 'Search Advisory Categories', 'somali-focus' ),
				'all_items'     => __( 'All Advisory Categories', 'somali-focus' ),
				'edit_item'     => __( 'Edit Advisory Category', 'somali-focus' ),
				'add_new_item'  => __( 'Add New Advisory Category', 'somali-focus' ),
				'menu_name'     => __( 'Advisory Categories', 'somali-focus' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'advisory-category' ),
		)
	);

	register_taxonomy(
		'research_category',
		array( 'sf_research' ),
		array(
			'labels'            => array(
				'name'          => __( 'Research Categories', 'somali-focus' ),
				'singular_name' => __( 'Research Category', 'somali-focus' ),
				'search_items'  => __( 'Search Research Categories', 'somali-focus' ),
				'all_items'     => __( 'All Research Categories', 'somali-focus' ),
				'edit_item'     => __( 'Edit Research Category', 'somali-focus' ),
				'add_new_item'  => __( 'Add New Research Category', 'somali-focus' ),
				'menu_name'     => __( 'Research Categories', 'somali-focus' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'research-category' ),
		)
	);

	register_taxonomy(
		'expertise',
		array( 'sf_expert' ),
		array(
			'labels'            => array(
				'name'          => __( 'Areas of Expertise', 'somali-focus' ),
				'singular_name' => __( 'Area of Expertise', 'somali-focus' ),
				'search_items'  => __( 'Search Areas of Expertise', 'somali-focus' ),
				'all_items'     => __( 'All Areas of Expertise', 'somali-focus' ),
				'edit_item'     => __( 'Edit Area of Expertise', 'somali-focus' ),
				'add_new_item'  => __( 'Add New Area of Expertise', 'somali-focus' ),
				'menu_name'     => __( 'Areas of Expertise', 'somali-focus' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'expertise' ),
		)
	);

	register_taxonomy(
		'sf_sector',
		array( 'sf_advisory', 'sf_partner' ),
		array(
			'labels'            => array(
				'name'          => __( 'Sectors', 'somali-focus' ),
				'singular_name' => __( 'Sector', 'somali-focus' ),
				'search_items'  => __( 'Search Sectors', 'somali-focus' ),
				'all_items'     => __( 'All Sectors', 'somali-focus' ),
				'edit_item'     => __( 'Edit Sector', 'somali-focus' ),
				'add_new_item'  => __( 'Add New Sector', 'somali-focus' ),
				'menu_name'     => __( 'Sectors', 'somali-focus' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'sector' ),
		)
	);
}
add_action( 'init', 'somali_focus_register_taxonomies', 5 );
