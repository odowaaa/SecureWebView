<?php
/**
 * SomCA theme setup, enqueues, and helper includes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SOMCA_THEME_VERSION', '2.0.0' );

require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Theme setup: title tag, thumbnails, HTML5, nav menus, RSS feed image.
 */
function somca_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 80,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	set_post_thumbnail_size( 800, 500, true );
	add_image_size( 'somca-hero', 1600, 900, true );
	add_image_size( 'somca-card', 480, 320, true );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'somca' ),
		'footer'  => __( 'Footer Menu', 'somca' ),
	) );
}
add_action( 'after_setup_theme', 'somca_setup' );

/**
 * Register widget areas.
 */
function somca_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'somca' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="somca-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="somca-widget-title">',
		'after_title'   => '</h3>',
	) );

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar( array(
			'name'          => sprintf( __( 'Footer Column %d', 'somca' ), $i ),
			'id'            => 'footer-' . $i,
			'before_widget' => '<div id="%1$s" class="somca-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="somca-footer-title">',
			'after_title'   => '</h4>',
		) );
	}
}
add_action( 'widgets_init', 'somca_widgets_init' );

/**
 * Enqueue theme styles/scripts.
 */
function somca_scripts() {
	wp_enqueue_style( 'somca-style', get_stylesheet_uri(), array(), SOMCA_THEME_VERSION );
	wp_enqueue_style( 'somca-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap', array(), null );

	wp_enqueue_script( 'somca-main', get_template_directory_uri() . '/assets/js/main.js', array(), SOMCA_THEME_VERSION, true );

	wp_enqueue_script( 'somca-subscribe', get_template_directory_uri() . '/assets/js/subscribe.js', array(), SOMCA_THEME_VERSION, true );
	wp_localize_script( 'somca-subscribe', 'SomcaSubscribe', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'somca_subscribe_nonce' ),
	) );

	if ( is_front_page() ) {
		wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
		wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
		wp_enqueue_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js', array(), '4.4.4', true );

		wp_enqueue_script( 'somca-map', get_template_directory_uri() . '/assets/js/map.js', array( 'leaflet' ), SOMCA_THEME_VERSION, true );
		wp_enqueue_script( 'somca-charts', get_template_directory_uri() . '/assets/js/charts.js', array( 'chartjs' ), SOMCA_THEME_VERSION, true );

		wp_localize_script( 'somca-map', 'SomcaData', array(
			'hotspotsEndpoint' => esc_url_raw( rest_url( 'somca/v1/hotspots' ) ),
		) );
		wp_localize_script( 'somca-charts', 'SomcaChartData', array(
			'reportsEndpoint' => esc_url_raw( rest_url( 'somca/v1/reports' ) ),
		) );
	}

	if ( is_singular( 'somca_hotspot' ) ) {
		wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
		wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
		wp_enqueue_script( 'somca-map', get_template_directory_uri() . '/assets/js/map.js', array( 'leaflet' ), SOMCA_THEME_VERSION, true );
		wp_localize_script( 'somca-map', 'SomcaData', array(
			'hotspotsEndpoint' => esc_url_raw( rest_url( 'somca/v1/hotspots?include=' . get_the_ID() ) ),
			'single'           => true,
			'lat'              => get_post_meta( get_the_ID(), 'somca_lat', true ),
			'lng'              => get_post_meta( get_the_ID(), 'somca_lng', true ),
			'title'            => get_the_title(),
		) );
	}

	if ( is_singular( 'somca_report' ) || is_post_type_archive( 'somca_report' ) ) {
		wp_enqueue_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js', array(), '4.4.4', true );
		wp_enqueue_script( 'somca-charts', get_template_directory_uri() . '/assets/js/charts.js', array( 'chartjs' ), SOMCA_THEME_VERSION, true );
		$hotspot_id = get_post_meta( get_the_ID(), 'somca_related_hotspot', true );
		wp_localize_script( 'somca-charts', 'SomcaChartData', array(
			'reportsEndpoint' => esc_url_raw( add_query_arg( array_filter( array( 'hotspot' => $hotspot_id ) ), rest_url( 'somca/v1/reports' ) ) ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'somca_scripts' );

/**
 * Nudge Core Web Vitals / SEO basics without an extra plugin dependency.
 */
add_filter( 'excerpt_length', function ( $length ) { return 28; } );
add_filter( 'excerpt_more', function () { return '&hellip;'; } );

/**
 * Make sure the SomCA post types show up cleanly in the theme's nav & search.
 */
function somca_search_include_cpts( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'page', 'somca_hotspot', 'somca_report', 'somca_alert' ) );
	}
}
add_action( 'pre_get_posts', 'somca_search_include_cpts' );

/**
 * Applies the ?hazard=<slug> filter on the Hotspot archive (added in 2.0.0).
 */
function somca_filter_hotspot_archive_by_hazard( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'somca_hotspot' ) ) {
		return;
	}
	if ( empty( $_GET['hazard'] ) ) {
		return;
	}
	$hazard = sanitize_key( wp_unslash( $_GET['hazard'] ) );
	$query->set( 'tax_query', array(
		array( 'taxonomy' => 'somca_hazard', 'field' => 'slug', 'terms' => $hazard ),
	) );
}
add_action( 'pre_get_posts', 'somca_filter_hotspot_archive_by_hazard' );

/**
 * Fallback menu when no nav menu is assigned yet.
 */
function somca_fallback_menu() {
	echo '<ul id="primary-menu" class="somca-nav-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'somca' ) . '</a></li>';
	echo '<li><a href="' . esc_url( get_post_type_archive_link( 'somca_hotspot' ) ) . '">' . esc_html__( 'Hotspots', 'somca' ) . '</a></li>';
	echo '<li><a href="' . esc_url( get_post_type_archive_link( 'somca_report' ) ) . '">' . esc_html__( 'Reports', 'somca' ) . '</a></li>';
	echo '<li><a href="' . esc_url( get_post_type_archive_link( 'somca_alert' ) ) . '">' . esc_html__( 'Alerts', 'somca' ) . '</a></li>';
	echo '</ul>';
}
