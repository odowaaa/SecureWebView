<?php
/**
 * Optional, off-by-default ad-slot architecture. No ads are placed by
 * default anywhere on the site — this only gives the theme fixed,
 * clearly-labeled locations to render an ad network's code IF an admin
 * later opts in from Somali Focus → Settings → Advertising.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The seven supported ad-slot locations.
 *
 * @return array slot_id => label
 */
function somali_focus_ad_slots() {
	return array(
		'header_top'     => __( 'Header / Top of Page', 'somali-focus' ),
		'after_hero'      => __( 'After Hero (homepage)', 'somali-focus' ),
		'before_content'  => __( 'Before Content (pages & posts)', 'somali-focus' ),
		'in_content'      => __( 'In-Content (single posts/research)', 'somali-focus' ),
		'after_content'   => __( 'After Content (pages & posts)', 'somali-focus' ),
		'sidebar'         => __( 'Sidebar (Insights)', 'somali-focus' ),
		'footer'          => __( 'Footer', 'somali-focus' ),
	);
}

/**
 * Whether a given slot is enabled and has code to show.
 *
 * @param string $slot_id One of somali_focus_ad_slots() keys.
 * @return bool
 */
function somali_focus_ad_slot_active( $slot_id ) {
	if ( ! array_key_exists( $slot_id, somali_focus_ad_slots() ) ) {
		return false;
	}
	$enabled = somali_focus_get_option( "ad_{$slot_id}_enabled" );
	$code    = somali_focus_get_option( "ad_{$slot_id}_code" );
	return ( $enabled && trim( (string) $code ) );
}

/**
 * Render an ad slot. Outputs nothing at all unless that specific slot is
 * both enabled AND has code configured — the safe, silent default.
 *
 * The ad code is echoed unescaped by design: it is a literal ad-network
 * script/markup snippet (e.g. Google AdSense's <script>/<ins> tags),
 * entered only by a user who already holds manage_options (the same
 * capability required for every other Settings field on this page).
 * This is the same trusted-admin-field pattern used by mainstream
 * "header/footer code" WordPress plugins — sanitize_text_field() or
 * wp_kses_post() would strip the very script tags this field exists to
 * hold, so it deliberately skips them (see includes/settings.php,
 * the 'raw_html' schema type).
 *
 * @param string $slot_id One of somali_focus_ad_slots() keys.
 */
function somali_focus_ad_slot( $slot_id ) {
	if ( ! somali_focus_ad_slot_active( $slot_id ) ) {
		return;
	}

	$code = somali_focus_get_option( "ad_{$slot_id}_code" );

	echo '<div class="sf-ad-slot sf-ad-slot--' . esc_attr( $slot_id ) . '" data-ad-slot="' . esc_attr( $slot_id ) . '">';
	echo '<span class="sf-ad-slot__label">' . esc_html__( 'Advertisement', 'somali-focus' ) . '</span>';
	echo $code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted manage_options-only ad-network snippet, see docblock above.
	echo '</div>';
}
