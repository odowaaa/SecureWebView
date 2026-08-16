<?php
/**
 * Lightweight technical SEO defaults. Everything here backs off
 * automatically when Yoast SEO or Rank Math is active, since those
 * plugins already own meta description, OG/Twitter tags and canonicals.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether a dedicated SEO plugin is already handling meta output.
 *
 * @return bool
 */
function sf_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' );
}

/**
 * Build a plain-text meta description for the current view.
 *
 * @return string
 */
function sf_meta_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			if ( $post->post_excerpt ) {
				return wp_strip_all_tags( $post->post_excerpt );
			}
			return sf_trim_words( $post->post_content, 30 );
		}
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
		if ( $description ) {
			return wp_strip_all_tags( $description );
		}
	}

	if ( is_post_type_archive() ) {
		$obj = get_queried_object();
		if ( $obj instanceof WP_Post_Type && ! empty( $obj->description ) ) {
			return wp_strip_all_tags( $obj->description );
		}
	}

	return sf_theme_option( 'tagline', get_bloginfo( 'description' ) );
}

/**
 * Whether the current view is an archive/search results page with zero
 * items — a thin/empty page that shouldn't be offered to Google for
 * indexing, even though visitors can still browse to it directly.
 *
 * @return bool
 */
function sf_is_thin_archive() {
	if ( is_search() ) {
		return true;
	}
	if ( ! is_archive() ) {
		return false;
	}
	global $wp_query;
	return $wp_query instanceof WP_Query && 0 === (int) $wp_query->post_count;
}

/**
 * Print meta description, canonical, Open Graph, Twitter Card and robots
 * tags.
 */
function sf_seo_head_tags() {
	if ( sf_seo_plugin_active() ) {
		return;
	}

	if ( sf_is_thin_archive() ) {
		echo '<meta name="robots" content="noindex,follow">' . "\n";
	}

	$description = sf_meta_description();
	$title       = wp_get_document_title();
	$url         = is_front_page() ? home_url( '/' ) : ( is_singular() ? get_permalink() : '' );
	$image       = '';

	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( get_queried_object_id(), 'sf-hero' );
	}

	echo "\n" . '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	if ( $url ) {
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	}

	echo '<meta property="og:site_name" content="' . esc_attr( sf_theme_option( 'org_name', get_bloginfo( 'name' ) ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( is_singular() ? 'article' : 'website' ) . '">' . "\n";
	if ( $url ) {
		echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	}
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	echo '<meta name="twitter:card" content="' . esc_attr( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'sf_seo_head_tags', 1 );
