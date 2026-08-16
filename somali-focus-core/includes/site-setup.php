<?php
/**
 * One-time (idempotent) site-structure setup: creates the six landing
 * Pages the theme's page templates expect, wires up the Insights blog
 * location, and builds a Primary navigation menu — so the site is
 * actually navigable immediately after activation rather than only
 * after manual admin setup.
 *
 * Every step here checks for existing content/config first and skips it
 * if found, so this is always safe to re-run (activation, plugin
 * upgrade, or the manual "Set Up Site Structure" dashboard button all
 * call the same function).
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The six template-backed Pages, keyed by slug.
 *
 * @return array
 */
function somali_focus_page_blueprint() {
	return array(
		'home'     => array( 'title' => __( 'Home', 'somali-focus' ), 'template' => '' ),
		'about'    => array( 'title' => __( 'About', 'somali-focus' ), 'template' => 'templates/page-about.php' ),
		'training' => array( 'title' => __( 'Training', 'somali-focus' ), 'template' => 'templates/page-training.php' ),
		'advisory' => array( 'title' => __( 'Advisory', 'somali-focus' ), 'template' => 'templates/page-advisory.php' ),
		'research' => array( 'title' => __( 'Research', 'somali-focus' ), 'template' => 'templates/page-research.php' ),
		'services' => array( 'title' => __( 'Services', 'somali-focus' ), 'template' => 'templates/page-services.php' ),
		'contact'  => array( 'title' => __( 'Contact', 'somali-focus' ), 'template' => 'templates/page-contact.php' ),
		'insights' => array( 'title' => __( 'Insights', 'somali-focus' ), 'template' => '' ),
	);
}

/**
 * Create every blueprint Page that doesn't already exist at its slug,
 * assigning the matching theme template.
 *
 * @return array slug => page ID (existing or newly created).
 */
function somali_focus_ensure_pages() {
	$ids = array();

	foreach ( somali_focus_page_blueprint() as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => '',
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		if ( $page['template'] ) {
			update_post_meta( $post_id, '_wp_page_template', $page['template'] );
		}

		$ids[ $slug ] = $post_id;
	}

	return $ids;
}

/**
 * Point Reading settings at the Home/Insights pages so /insights/ becomes
 * a real blog index, without touching a site that has already been
 * configured (manually or by a previous run of this function).
 *
 * @param array $ids slug => page ID, from somali_focus_ensure_pages().
 */
function somali_focus_configure_reading_settings( $ids ) {
	if ( 'page' === get_option( 'show_on_front' ) ) {
		return; // Already configured — never override a deliberate choice.
	}
	if ( empty( $ids['home'] ) || empty( $ids['insights'] ) ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );
	update_option( 'page_for_posts', $ids['insights'] );
}

/**
 * Build the Primary navigation menu if nothing is already assigned to
 * that theme location.
 *
 * @param array $ids slug => page ID, from somali_focus_ensure_pages().
 */
function somali_focus_ensure_primary_menu( $ids ) {
	if ( has_nav_menu( 'primary' ) ) {
		return; // Admin (or a previous run) already set this up.
	}

	$menu_name = __( 'Primary Menu', 'somali-focus' );
	$menu_id   = wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$position = 1;

	if ( ! empty( $ids['home'] ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Home', 'somali-focus' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $ids['home'],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position++,
		) );
	}

	$page_items = array(
		'about'    => __( 'About', 'somali-focus' ),
		'training' => __( 'Training', 'somali-focus' ),
		'advisory' => __( 'Advisory', 'somali-focus' ),
		'research' => __( 'Research', 'somali-focus' ),
		'insights' => __( 'Insights', 'somali-focus' ),
	);

	foreach ( $page_items as $slug => $label ) {
		if ( empty( $ids[ $slug ] ) ) {
			continue;
		}
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $label,
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $ids[ $slug ],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position++,
		) );
	}

	if ( post_type_exists( 'sf_expert' ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'    => __( 'Experts', 'somali-focus' ),
			'menu-item-url'      => get_post_type_archive_link( 'sf_expert' ),
			'menu-item-type'     => 'custom',
			'menu-item-status'   => 'publish',
			'menu-item-position' => $position++,
		) );
	}

	if ( ! empty( $ids['contact'] ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Contact', 'somali-focus' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $ids['contact'],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position++,
		) );
	}

	$locations             = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Run the full site-structure setup. Safe to call repeatedly.
 */
function somali_focus_setup_site_structure() {
	$ids = somali_focus_ensure_pages();
	somali_focus_configure_reading_settings( $ids );
	somali_focus_ensure_primary_menu( $ids );
}

/**
 * Manual "Set Up Site Structure" action, reachable from the Dashboard —
 * for sites that skipped this on activation or want to re-check it after
 * deleting a page/menu.
 */
function somali_focus_handle_manual_site_setup() {
	if ( ! current_user_can( somali_focus_manage_cap() ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'somali-focus' ), 403 );
	}
	somali_focus_verify_nonce_or_die( 'somali_focus_site_setup' );
	somali_focus_setup_site_structure();
	wp_safe_redirect( add_query_arg( 'sf_site_setup', '1', admin_url( 'admin.php?page=somali-focus' ) ) );
	exit;
}
add_action( 'admin_post_somali_focus_site_setup', 'somali_focus_handle_manual_site_setup' );
