<?php
/**
 * Core theme setup: supports, menus, image sizes, widget areas.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function somali_focus_setup() {
	load_theme_textdomain( 'somali-focus', SOMALI_FOCUS_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 72,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Editor colour palette pulled from the Somali Focus brand.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Somali Focus Blue', 'somali-focus' ),
				'slug'  => 'sf-blue',
				'color' => '#0B4A8F',
			),
			array(
				'name'  => __( 'Somali Focus Red', 'somali-focus' ),
				'slug'  => 'sf-red',
				'color' => '#C8102E',
			),
			array(
				'name'  => __( 'Navy', 'somali-focus' ),
				'slug'  => 'sf-navy',
				'color' => '#0A1F33',
			),
			array(
				'name'  => __( 'Charcoal', 'somali-focus' ),
				'slug'  => 'sf-charcoal',
				'color' => '#22282F',
			),
			array(
				'name'  => __( 'Light Gray', 'somali-focus' ),
				'slug'  => 'sf-light-gray',
				'color' => '#F5F7FA',
			),
			array(
				'name'  => __( 'White', 'somali-focus' ),
				'slug'  => 'sf-white',
				'color' => '#FFFFFF',
			),
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'somali-focus' ),
			'footer'  => __( 'Footer Menu', 'somali-focus' ),
			'legal'   => __( 'Legal Menu', 'somali-focus' ),
		)
	);

	add_image_size( 'sf-card', 640, 420, true );
	add_image_size( 'sf-hero', 1600, 1000, true );
	add_image_size( 'sf-portrait', 480, 560, true );
	add_image_size( 'sf-logo-wide', 480, 160, false );

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1200;
	}
}
add_action( 'after_setup_theme', 'somali_focus_setup' );

/**
 * Register widget areas (footer + optional sidebar for Insights).
 */
function somali_focus_widgets_init() {
	$columns = array(
		'sf-footer-1' => __( 'Footer Column 1 — About', 'somali-focus' ),
		'sf-footer-2' => __( 'Footer Column 2 — Quick Links', 'somali-focus' ),
		'sf-footer-3' => __( 'Footer Column 3 — Services', 'somali-focus' ),
		'sf-footer-4' => __( 'Footer Column 4 — Contact', 'somali-focus' ),
	);

	foreach ( $columns as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => __( 'Insights Sidebar', 'somali-focus' ),
			'id'            => 'sf-blog-sidebar',
			'description'   => __( 'Displayed on Insights (blog) archive and single posts.', 'somali-focus' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'somali_focus_widgets_init' );

/**
 * Fallback menu when no primary menu is assigned yet.
 */
function somali_focus_fallback_menu() {
	echo '<ul class="primary-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'somali-focus' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Excerpt length & "read more" string.
 */
function somali_focus_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'somali_focus_excerpt_length' );

function somali_focus_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'somali_focus_excerpt_more' );
