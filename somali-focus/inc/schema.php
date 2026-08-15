<?php
/**
 * JSON-LD structured data: Organization + BreadcrumbList sitewide, plus
 * Course and Article schema on their respective single templates.
 *
 * Organization/BreadcrumbList/Article back off when a dedicated SEO
 * plugin is active (it already emits a full schema graph for those).
 * Course schema always emits — Yoast/Rank Math have no concept of the
 * sf_course post type, so there is nothing to duplicate or conflict with.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print a JSON-LD <script> block.
 *
 * @param array $data Schema.org data (must include @context/@type).
 */
function sf_print_schema( $data ) {
	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
}

/**
 * Resolve the brand logo URL for schema output: custom logo if set,
 * otherwise the bundled default.
 *
 * @return string
 */
function sf_schema_logo_url() {
	if ( has_custom_logo() ) {
		$logo_id  = get_theme_mod( 'custom_logo' );
		$logo_src = $logo_id ? wp_get_attachment_image_src( $logo_id, 'full' ) : false;
		if ( $logo_src ) {
			return $logo_src[0];
		}
	}
	return SOMALI_FOCUS_URI . '/assets/images/logo-horizontal.png';
}

/**
 * Sitewide Organization schema.
 */
function sf_schema_organization() {
	$same_as = array_values(
		array_filter(
			array(
				sf_theme_option( 'social_facebook' ),
				sf_theme_option( 'social_linkedin' ),
				sf_theme_option( 'social_twitter' ),
				sf_theme_option( 'social_youtube' ),
			)
		)
	);

	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => sf_theme_option( 'org_name', get_bloginfo( 'name' ) ),
		'url'      => home_url( '/' ),
		'logo'     => sf_schema_logo_url(),
	);

	if ( $same_as ) {
		$data['sameAs'] = $same_as;
	}

	$phone = sf_theme_option( 'phone' );
	$email = sf_theme_option( 'email' );
	if ( $phone || $email ) {
		$contact_point = array( '@type' => 'ContactPoint', 'contactType' => 'customer service' );
		if ( $phone ) {
			$contact_point['telephone'] = $phone;
		}
		if ( $email ) {
			$contact_point['email'] = $email;
		}
		$data['contactPoint'] = array( $contact_point );
	}

	sf_print_schema( $data );
}

/**
 * BreadcrumbList schema mirroring the visible sf_breadcrumbs() trail.
 */
function sf_schema_breadcrumbs() {
	$items = sf_get_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	$list_items = array();
	foreach ( $items as $index => $item ) {
		$url = $item['url'] ? $item['url'] : home_url( add_query_arg( null, null ) );
		$list_items[] = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'name'     => $item['label'],
			'item'     => esc_url_raw( $url ),
		);
	}

	sf_print_schema(
		array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $list_items,
		)
	);
}

/**
 * Course schema for single sf_course pages.
 */
function sf_schema_course() {
	$post_id = get_the_ID();
	$mode_map = array( 'in-person' => 'Onsite', 'online' => 'Online', 'hybrid' => 'Blended' );
	$delivery = sf_theme_meta( $post_id, 'delivery_mode' );

	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Course',
		'name'        => get_the_title(),
		'description' => wp_strip_all_tags( sf_theme_meta( $post_id, 'short_description', get_the_excerpt() ) ),
		'provider'    => array(
			'@type' => 'Organization',
			'name'  => sf_theme_option( 'org_name', get_bloginfo( 'name' ) ),
			'sameAs' => home_url( '/' ),
		),
	);

	$start = sf_theme_meta( $post_id, 'start_date' );
	$end   = sf_theme_meta( $post_id, 'end_date' );
	if ( $start || $end || $delivery ) {
		$instance = array( '@type' => 'CourseInstance' );
		if ( isset( $mode_map[ $delivery ] ) ) {
			$instance['courseMode'] = $mode_map[ $delivery ];
		}
		if ( $start ) {
			$instance['startDate'] = $start;
		}
		if ( $end ) {
			$instance['endDate'] = $end;
		}
		$location = sf_theme_meta( $post_id, 'location' );
		if ( $location ) {
			$instance['location'] = array( '@type' => 'Place', 'name' => $location );
		}
		$data['hasCourseInstance'] = array( $instance );
	}

	sf_print_schema( $data );
}

/**
 * Article schema for single blog posts.
 */
function sf_schema_article() {
	$data = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title(),
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'mainEntityOfPage' => get_permalink(),
		'author'           => array(
			'@type' => 'Organization',
			'name'  => sf_theme_option( 'org_name', get_bloginfo( 'name' ) ),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => sf_theme_option( 'org_name', get_bloginfo( 'name' ) ),
			'logo'  => array( '@type' => 'ImageObject', 'url' => sf_schema_logo_url() ),
		),
	);

	if ( has_post_thumbnail() ) {
		$data['image'] = get_the_post_thumbnail_url( get_the_ID(), 'sf-hero' );
	}

	sf_print_schema( $data );
}

/**
 * Dispatch the right schema blocks for the current request.
 */
function sf_output_schema() {
	if ( ! sf_seo_plugin_active() ) {
		sf_schema_organization();
		sf_schema_breadcrumbs();
		if ( is_singular( 'post' ) ) {
			sf_schema_article();
		}
	}

	if ( is_singular( 'sf_course' ) ) {
		sf_schema_course();
	}
}
add_action( 'wp_footer', 'sf_output_schema' );
