<?php
/**
 * Asset enqueueing: styles, scripts, fonts.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end assets.
 */
function somali_focus_scripts() {
	// Self-hosted Plus Jakarta Sans + Merriweather — no external font request.
	wp_enqueue_style(
		'somali-focus-fonts',
		SOMALI_FOCUS_URI . '/assets/css/fonts.css',
		array(),
		SOMALI_FOCUS_VERSION
	);

	wp_enqueue_style( 'somali-focus-style', get_stylesheet_uri(), array(), SOMALI_FOCUS_VERSION );

	wp_enqueue_style(
		'somali-focus-main',
		SOMALI_FOCUS_URI . '/assets/css/main.css',
		array( 'somali-focus-style' ),
		SOMALI_FOCUS_VERSION
	);

	wp_enqueue_script(
		'somali-focus-main',
		SOMALI_FOCUS_URI . '/assets/js/main.js',
		array(),
		SOMALI_FOCUS_VERSION,
		true
	);
	wp_script_add_data( 'somali-focus-main', 'defer', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'somali_focus_scripts' );

/**
 * Enqueue block-editor styling so Gutenberg content matches the front end.
 */
function somali_focus_editor_assets() {
	wp_enqueue_style(
		'somali-focus-editor-style',
		SOMALI_FOCUS_URI . '/assets/css/editor-style.css',
		array(),
		SOMALI_FOCUS_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'somali_focus_editor_assets' );
add_editor_style( 'assets/css/editor-style.css' );

/**
 * Preload the two self-hosted Latin font files most visitors need
 * immediately, avoiding a flash of unstyled/fallback text.
 */
function somali_focus_resource_hints() {
	$fonts = array(
		'/assets/fonts/plus-jakarta-sans-latin.woff2',
		'/assets/fonts/merriweather-normal-latin.woff2',
	);
	foreach ( $fonts as $font ) {
		echo '<link rel="preload" href="' . esc_url( SOMALI_FOCUS_URI . $font ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'somali_focus_resource_hints', 1 );

/**
 * Favicon fallback: the bundled Somali Focus mark, used only until the
 * administrator sets a proper Site Icon under Customize → Site Identity
 * (WordPress core then handles favicon output itself).
 */
function somali_focus_favicon_fallback() {
	if ( has_site_icon() || is_admin() ) {
		return;
	}
	echo '<link rel="icon" href="' . esc_url( SOMALI_FOCUS_URI . '/assets/images/site-icon.png' ) . '" sizes="512x512">' . "\n";
}
add_action( 'wp_head', 'somali_focus_favicon_fallback', 2 );
