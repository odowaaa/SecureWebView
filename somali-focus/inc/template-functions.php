<?php
/**
 * Presentational helper functions used across templates.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Estimated reading time for the current post.
 *
 * @param int|WP_Post $post Post ID or object. Defaults to the loop post.
 * @return int Minutes, minimum 1.
 */
function sf_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 1;
	}
	$word_count = str_word_count( wp_strip_all_tags( $post->post_content ) );
	return max( 1, (int) ceil( $word_count / 200 ) );
}

/**
 * Build the breadcrumb trail items for the current request. Shared by the
 * visible breadcrumb nav and the BreadcrumbList structured data output.
 *
 * @return array[] Each item: label, url (empty string for the current page).
 */
function sf_get_breadcrumb_items() {
	if ( is_front_page() ) {
		return array();
	}

	$items = array(
		array( 'label' => __( 'Home', 'somali-focus' ), 'url' => home_url( '/' ) ),
	);

	if ( is_singular() ) {
		$post_type_obj = get_post_type_object( get_post_type() );
		if ( $post_type_obj && $post_type_obj->has_archive ) {
			$items[] = array( 'label' => $post_type_obj->labels->name, 'url' => get_post_type_archive_link( get_post_type() ) );
		}
		$items[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$items[] = array( 'label' => single_term_title( '', false ), 'url' => '' );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( 'label' => post_type_archive_title( '', false ), 'url' => '' );
	} elseif ( is_search() ) {
		/* translators: %s: search query */
		$items[] = array( 'label' => sprintf( __( 'Search results for "%s"', 'somali-focus' ), get_search_query() ), 'url' => '' );
	} elseif ( is_404() ) {
		$items[] = array( 'label' => __( 'Page Not Found', 'somali-focus' ), 'url' => '' );
	} elseif ( is_page() ) {
		$items[] = array( 'label' => get_the_title(), 'url' => '' );
	}

	return $items;
}

/**
 * Simple breadcrumb trail. Renders nothing on the front page.
 */
function sf_breadcrumbs() {
	if ( ! sf_breadcrumbs_enabled() ) {
		return;
	}

	$items = sf_get_breadcrumb_items();
	if ( empty( $items ) ) {
		return;
	}

	echo '<nav class="sf-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'somali-focus' ) . '"><ol class="sf-breadcrumbs__list">';
	foreach ( $items as $index => $item ) {
		echo '<li class="sf-breadcrumbs__item">';
		if ( $item['url'] ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item['label'] ) . '</span>';
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}

/**
 * Output <body> helper classes for header behaviour.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function sf_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'sf-home';
	}
	return $classes;
}
add_filter( 'body_class', 'sf_body_classes' );

/**
 * Consistent, translation-friendly date format for cards/listings.
 *
 * @param string|int $date Meta date string (Y-m-d) or timestamp.
 * @return string
 */
function sf_format_date( $date ) {
	if ( empty( $date ) ) {
		return '';
	}
	$timestamp = is_numeric( $date ) ? (int) $date : strtotime( $date );
	if ( ! $timestamp ) {
		return '';
	}
	return date_i18n( get_option( 'date_format' ), $timestamp );
}

/**
 * Truncate a plain string to a word count with an ellipsis.
 *
 * @param string $text  Source text.
 * @param int    $words Word limit.
 * @return string
 */
function sf_trim_words( $text, $words = 20 ) {
	return wp_trim_words( wp_strip_all_tags( $text ), $words, '&hellip;' );
}

/**
 * Resolve the header CTA button's destination: the Customizer URL if set,
 * otherwise the Contact page, otherwise the homepage.
 *
 * @return string
 */
function sf_header_cta_url() {
	$configured = get_theme_mod( 'sf_header_cta_url', '' );
	if ( $configured ) {
		return $configured;
	}
	$contact_page = get_page_by_path( 'contact' );
	if ( $contact_page ) {
		return get_permalink( $contact_page );
	}
	return home_url( '/' );
}

/**
 * Register a `sf-icon-*` sprite renderer so template-parts can request a
 * consistent inline SVG icon by name without duplicating markup.
 *
 * @param string $name  Icon name (see assets/images/icons.php map).
 * @param string $class Extra CSS class.
 */
function sf_icon( $name, $class = '' ) {
	$icons = sf_icon_map();
	if ( ! isset( $icons[ $name ] ) ) {
		return;
	}
	printf(
		'<svg class="sf-icon %s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
		esc_attr( $class ),
		$icons[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static trusted SVG path data only.
	);
}

/**
 * Print an inline social-brand icon by key (facebook, linkedin, twitter,
 * youtube, whatsapp).
 *
 * @param string $key Social network key.
 */
function sf_social_icon( $key ) {
	$icons = array(
		'facebook' => '<path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H8v3h3v7h3v-7h3l1-3h-4V9c0-.6.4-1 1-1Z"/>',
		'linkedin' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 11v5M8 8v.01M12 16v-5M16 16v-3a2 2 0 0 0-4 0"/>',
		'twitter'  => '<path d="M22 5.9c-.7.3-1.5.5-2.3.6.8-.5 1.5-1.3 1.7-2.3-.8.5-1.7.8-2.6 1a4 4 0 0 0-6.9 3.7A11.5 11.5 0 0 1 3.4 4.6a4 4 0 0 0 1.2 5.4c-.6 0-1.3-.2-1.8-.5v.1c0 2 1.4 3.6 3.2 4a4 4 0 0 1-1.8.1 4 4 0 0 0 3.8 2.8A8 8 0 0 1 2 18.5 11.4 11.4 0 0 0 8.3 20c7.5 0 11.7-6.4 11.7-11.9v-.5c.8-.6 1.5-1.3 2-2.1Z"/>',
		'youtube'  => '<rect x="2" y="5" width="20" height="14" rx="3"/><path d="M10 9.5v5l5-2.5-5-2.5Z" fill="currentColor" stroke="none"/>',
		'whatsapp' => '<path d="M21 11.5a8.5 8.5 0 0 1-12.4 7.6L3 21l1.9-5.7A8.5 8.5 0 1 1 21 11.5Z"/><path d="M8.5 9.5c0 3.5 2.5 6 6 6 .5-1 .3-1.5-.3-1.9l-1.3-.8c-.4-.3-.7-.2-1 0l-.4.4c-1-.5-1.8-1.3-2.3-2.3l.4-.4c.2-.3.3-.6 0-1L8.8 8.2c-.4-.6-.9-.8-1.9-.3-.4.3-.4.9-.4 1.6Z" fill="currentColor" stroke="none"/>',
	);
	if ( ! isset( $icons[ $key ] ) ) {
		return;
	}
	printf(
		'<svg class="sf-icon sf-icon--social" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
		$icons[ $key ] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static trusted SVG path data only.
	);
}

/**
 * Static inline-SVG path map for the small icon set used across cards,
 * sectors and the approach timeline. Kept as plain path data (no external
 * requests, no icon font) for performance.
 *
 * @return array
 */
function sf_icon_map() {
	return array(
		'training'     => '<path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"/>',
		'advisory'     => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5Z"/>',
		'research'     => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
		'check'        => '<path d="M20 6 9 17l-5-5"/>',
		'arrow-right'  => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
		'calendar'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
		'location'     => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
		'download'     => '<path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/>',
		'compass'      => '<circle cx="12" cy="12" r="9"/><path d="m16 8-2 6-6 2 2-6 6-2Z"/>',
		'chart'        => '<path d="M3 3v18h18"/><path d="M7 15v3M12 10v8M17 6v12"/>',
		'design'       => '<path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/>',
		'deliver'      => '<rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8Z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
		'measure'      => '<path d="M21 21H3"/><path d="M21 7v14"/><path d="m3 21 8-8 4 4 6-6"/>',
		'building'     => '<rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/>',
		'briefcase'    => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
		'globe'        => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 0 18 15 15 0 0 1 0-18Z"/>',
		'users'        => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'book'         => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>',
	);
}
