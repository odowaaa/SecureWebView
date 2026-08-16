<?php
/**
 * Custom Post Types owned by the Somali Focus Core plugin.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register every Somali Focus custom post type.
 */
function somali_focus_register_post_types() {

	// ---------------------------------------------------------------
	// Courses / Training.
	// ---------------------------------------------------------------
	register_post_type(
		'sf_course',
		array(
			'labels'             => array(
				'name'                  => __( 'Courses', 'somali-focus' ),
				'singular_name'         => __( 'Course', 'somali-focus' ),
				'add_new_item'          => __( 'Add New Course', 'somali-focus' ),
				'edit_item'             => __( 'Edit Course', 'somali-focus' ),
				'new_item'              => __( 'New Course', 'somali-focus' ),
				'view_item'             => __( 'View Course', 'somali-focus' ),
				'search_items'          => __( 'Search Courses', 'somali-focus' ),
				'not_found'             => __( 'No courses found.', 'somali-focus' ),
				'all_items'             => __( 'All Courses', 'somali-focus' ),
				'menu_name'             => __( 'Training', 'somali-focus' ),
				'featured_image'        => __( 'Course Image', 'somali-focus' ),
			),
			'public'             => true,
			// Archive slug intentionally NOT 'training' — that URL is reserved
			// for the Training landing Page (templates/page-training.php).
			// A CPT archive and a Page can't share a slug; the archive loses.
			'has_archive'        => 'courses',
			'rewrite'            => array( 'slug' => 'courses', 'with_front' => false ),
			'show_in_menu'       => 'somali-focus',
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-welcome-learn-more',
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'taxonomies'         => array( 'course_category' ),
			'capability_type'    => 'post',
		)
	);

	// ---------------------------------------------------------------
	// Advisory Projects.
	// ---------------------------------------------------------------
	register_post_type(
		'sf_advisory',
		array(
			'labels'          => array(
				'name'           => __( 'Advisory Projects', 'somali-focus' ),
				'singular_name'  => __( 'Advisory Project', 'somali-focus' ),
				'add_new_item'   => __( 'Add New Advisory Project', 'somali-focus' ),
				'edit_item'      => __( 'Edit Advisory Project', 'somali-focus' ),
				'new_item'       => __( 'New Advisory Project', 'somali-focus' ),
				'view_item'      => __( 'View Advisory Project', 'somali-focus' ),
				'search_items'   => __( 'Search Advisory Projects', 'somali-focus' ),
				'not_found'      => __( 'No advisory projects found.', 'somali-focus' ),
				'all_items'      => __( 'Projects', 'somali-focus' ),
				'menu_name'      => __( 'Advisory', 'somali-focus' ),
				'featured_image' => __( 'Project Image', 'somali-focus' ),
			),
			'public'          => true,
			// Archive slug intentionally NOT 'advisory' — reserved for the
			// Advisory landing Page (templates/page-advisory.php).
			'has_archive'     => 'advisory-projects',
			'rewrite'         => array( 'slug' => 'advisory-projects', 'with_front' => false ),
			'show_in_menu'    => 'somali-focus',
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-businessman',
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'taxonomies'      => array( 'advisory_category', 'sf_sector' ),
			'capability_type' => 'post',
		)
	);

	// ---------------------------------------------------------------
	// Research & Publications.
	// ---------------------------------------------------------------
	register_post_type(
		'sf_research',
		array(
			'labels'          => array(
				'name'           => __( 'Research', 'somali-focus' ),
				'singular_name'  => __( 'Research Item', 'somali-focus' ),
				'add_new_item'   => __( 'Add New Research Item', 'somali-focus' ),
				'edit_item'      => __( 'Edit Research Item', 'somali-focus' ),
				'new_item'       => __( 'New Research Item', 'somali-focus' ),
				'view_item'      => __( 'View Research Item', 'somali-focus' ),
				'search_items'   => __( 'Search Research', 'somali-focus' ),
				'not_found'      => __( 'No research items found.', 'somali-focus' ),
				'all_items'      => __( 'Publications', 'somali-focus' ),
				'menu_name'      => __( 'Research', 'somali-focus' ),
				'featured_image' => __( 'Cover Image', 'somali-focus' ),
			),
			'public'          => true,
			// Archive slug intentionally NOT 'research' — reserved for the
			// Research landing Page (templates/page-research.php).
			'has_archive'     => 'publications',
			'rewrite'         => array( 'slug' => 'publications', 'with_front' => false ),
			'show_in_menu'    => 'somali-focus',
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-media-document',
			'supports'        => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'taxonomies'      => array( 'research_category' ),
			'capability_type' => 'post',
		)
	);

	// ---------------------------------------------------------------
	// Experts / Team.
	// ---------------------------------------------------------------
	register_post_type(
		'sf_expert',
		array(
			'labels'          => array(
				'name'           => __( 'Experts', 'somali-focus' ),
				'singular_name'  => __( 'Expert', 'somali-focus' ),
				'add_new_item'   => __( 'Add New Expert', 'somali-focus' ),
				'edit_item'      => __( 'Edit Expert', 'somali-focus' ),
				'new_item'       => __( 'New Expert', 'somali-focus' ),
				'view_item'      => __( 'View Expert', 'somali-focus' ),
				'search_items'   => __( 'Search Experts', 'somali-focus' ),
				'not_found'      => __( 'No experts found.', 'somali-focus' ),
				'all_items'      => __( 'All Experts', 'somali-focus' ),
				'menu_name'      => __( 'Experts', 'somali-focus' ),
				'featured_image' => __( 'Profile Photo', 'somali-focus' ),
			),
			'public'          => true,
			'has_archive'     => 'experts',
			'rewrite'         => array( 'slug' => 'experts', 'with_front' => false ),
			'show_in_menu'    => 'somali-focus',
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-groups',
			'supports'        => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ),
			'taxonomies'      => array( 'expertise' ),
			'capability_type' => 'post',
		)
	);

	// ---------------------------------------------------------------
	// Partners.
	// ---------------------------------------------------------------
	register_post_type(
		'sf_partner',
		array(
			'labels'          => array(
				'name'           => __( 'Partners', 'somali-focus' ),
				'singular_name'  => __( 'Partner', 'somali-focus' ),
				'add_new_item'   => __( 'Add New Partner', 'somali-focus' ),
				'edit_item'      => __( 'Edit Partner', 'somali-focus' ),
				'new_item'       => __( 'New Partner', 'somali-focus' ),
				'search_items'   => __( 'Search Partners', 'somali-focus' ),
				'not_found'      => __( 'No partners found.', 'somali-focus' ),
				'all_items'      => __( 'All Partners', 'somali-focus' ),
				'menu_name'      => __( 'Partners', 'somali-focus' ),
				'featured_image' => __( 'Logo', 'somali-focus' ),
			),
			// Partners are logo/link cards embedded in other pages, not
			// standalone content — same public/queryable shape as
			// Testimonials below, so they never get an orphaned,
			// undesigned single URL.
			'public'              => false,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'    => 'somali-focus',
			'show_in_rest'    => true,
			'menu_icon'       => 'dashicons-groups',
			'supports'        => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'taxonomies'      => array( 'sf_sector' ),
			'capability_type' => 'post',
		)
	);

	// ---------------------------------------------------------------
	// Testimonials.
	// ---------------------------------------------------------------
	register_post_type(
		'sf_testimonial',
		array(
			'labels'          => array(
				'name'          => __( 'Testimonials', 'somali-focus' ),
				'singular_name' => __( 'Testimonial', 'somali-focus' ),
				'add_new_item'  => __( 'Add New Testimonial', 'somali-focus' ),
				'edit_item'     => __( 'Edit Testimonial', 'somali-focus' ),
				'new_item'      => __( 'New Testimonial', 'somali-focus' ),
				'search_items'  => __( 'Search Testimonials', 'somali-focus' ),
				'not_found'     => __( 'No testimonials found.', 'somali-focus' ),
				'all_items'     => __( 'All Testimonials', 'somali-focus' ),
				'menu_name'     => __( 'Testimonials', 'somali-focus' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'has_archive'         => false,
			'show_in_menu'        => 'somali-focus',
			'show_in_rest'        => true,
			'menu_icon'           => 'dashicons-format-quote',
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'capability_type'     => 'post',
		)
	);

	// ---------------------------------------------------------------
	// Training Registrations (private submissions, no public/REST access).
	// ---------------------------------------------------------------
	register_post_type(
		'sf_registration',
		array(
			'labels'              => array(
				'name'          => __( 'Registrations', 'somali-focus' ),
				'singular_name' => __( 'Registration', 'somali-focus' ),
				'search_items'  => __( 'Search Registrations', 'somali-focus' ),
				'not_found'     => __( 'No registrations found.', 'somali-focus' ),
				'all_items'     => __( 'Registrations', 'somali-focus' ),
				'menu_name'     => __( 'Registrations', 'somali-focus' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_in_rest'        => false,
			'show_ui'             => true,
			'show_in_menu'        => 'somali-focus',
			'has_archive'         => false,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);

	// ---------------------------------------------------------------
	// General Service Requests (private submissions).
	// ---------------------------------------------------------------
	register_post_type(
		'sf_service_request',
		array(
			'labels'              => array(
				'name'          => __( 'Service Requests', 'somali-focus' ),
				'singular_name' => __( 'Service Request', 'somali-focus' ),
				'search_items'  => __( 'Search Service Requests', 'somali-focus' ),
				'not_found'     => __( 'No service requests found.', 'somali-focus' ),
				'all_items'     => __( 'Service Requests', 'somali-focus' ),
				'menu_name'     => __( 'Service Requests', 'somali-focus' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_in_rest'        => false,
			'show_ui'             => true,
			'show_in_menu'        => 'somali-focus',
			'has_archive'         => false,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		)
	);
}
add_action( 'init', 'somali_focus_register_post_types', 6 );

/**
 * Custom columns: Course list shows category, trainer, dates, delivery mode.
 */
function somali_focus_course_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['sf_trainer']  = __( 'Trainer', 'somali-focus' );
			$new['sf_dates']    = __( 'Dates', 'somali-focus' );
			$new['sf_delivery'] = __( 'Delivery', 'somali-focus' );
			$new['sf_status']   = __( 'Registration', 'somali-focus' );
		}
	}
	return $new;
}
add_filter( 'manage_sf_course_posts_columns', 'somali_focus_course_columns' );

function somali_focus_course_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'sf_trainer':
			echo esc_html( somali_focus_meta( $post_id, 'trainer', '—' ) );
			break;
		case 'sf_dates':
			$start = somali_focus_meta( $post_id, 'start_date' );
			$end   = somali_focus_meta( $post_id, 'end_date' );
			echo esc_html( $start ? $start . ( $end ? ' – ' . $end : '' ) : '—' );
			break;
		case 'sf_delivery':
			echo esc_html( somali_focus_meta( $post_id, 'delivery_mode', '—' ) );
			break;
		case 'sf_status':
			echo esc_html( somali_focus_meta( $post_id, 'registration_status', 'open' ) );
			break;
	}
}
add_action( 'manage_sf_course_posts_custom_column', 'somali_focus_course_column_content', 10, 2 );

/**
 * Registrations list: custom columns for quick admin scanning.
 */
function somali_focus_registration_columns( $columns ) {
	return array(
		'cb'          => $columns['cb'],
		'title'       => __( 'Reference', 'somali-focus' ),
		'sf_name'     => __( 'Name', 'somali-focus' ),
		'sf_org'      => __( 'Organization', 'somali-focus' ),
		'sf_course'   => __( 'Course', 'somali-focus' ),
		'sf_email'    => __( 'Email', 'somali-focus' ),
		'sf_status'   => __( 'Status', 'somali-focus' ),
		'date'        => __( 'Submitted', 'somali-focus' ),
	);
}
add_filter( 'manage_sf_registration_posts_columns', 'somali_focus_registration_columns' );

function somali_focus_registration_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'sf_name':
			echo esc_html( somali_focus_meta( $post_id, 'full_name' ) );
			break;
		case 'sf_org':
			echo esc_html( somali_focus_meta( $post_id, 'organization', '—' ) );
			break;
		case 'sf_course':
			$course_id = (int) somali_focus_meta( $post_id, 'course_id' );
			echo $course_id ? esc_html( get_the_title( $course_id ) ) : esc_html__( 'General', 'somali-focus' );
			break;
		case 'sf_email':
			$email = somali_focus_meta( $post_id, 'email' );
			echo $email ? '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>' : '—';
			break;
		case 'sf_status':
			echo esc_html( ucfirst( somali_focus_meta( $post_id, 'status', 'new' ) ) );
			break;
	}
}
add_action( 'manage_sf_registration_posts_custom_column', 'somali_focus_registration_column_content', 10, 2 );

/**
 * Service requests list: custom columns.
 */
function somali_focus_service_request_columns( $columns ) {
	return array(
		'cb'         => $columns['cb'],
		'title'      => __( 'Reference', 'somali-focus' ),
		'sf_name'    => __( 'Name', 'somali-focus' ),
		'sf_org'     => __( 'Organization', 'somali-focus' ),
		'sf_service' => __( 'Service', 'somali-focus' ),
		'sf_email'   => __( 'Email', 'somali-focus' ),
		'sf_status'  => __( 'Status', 'somali-focus' ),
		'date'       => __( 'Submitted', 'somali-focus' ),
	);
}
add_filter( 'manage_sf_service_request_posts_columns', 'somali_focus_service_request_columns' );

function somali_focus_service_request_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'sf_name':
			echo esc_html( somali_focus_meta( $post_id, 'full_name' ) );
			break;
		case 'sf_org':
			echo esc_html( somali_focus_meta( $post_id, 'organization', '—' ) );
			break;
		case 'sf_service':
			echo esc_html( somali_focus_meta( $post_id, 'service', '—' ) );
			break;
		case 'sf_email':
			$email = somali_focus_meta( $post_id, 'email' );
			echo $email ? '<a href="' . esc_url( 'mailto:' . $email ) . '">' . esc_html( $email ) . '</a>' : '—';
			break;
		case 'sf_status':
			echo esc_html( ucfirst( somali_focus_meta( $post_id, 'status', 'new' ) ) );
			break;
	}
}
add_action( 'manage_sf_service_request_posts_custom_column', 'somali_focus_service_request_column_content', 10, 2 );
