<?php
/**
 * Theme Customizer — presentation-only controls (colors, header CTA,
 * breadcrumb visibility). Business content (hero copy, stats, contact
 * info) lives in Somali Focus Core → Settings, not here, so it survives
 * a theme switch.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer sections/settings/controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function somali_focus_customize_register( $wp_customize ) {

	// ---------------------------------------------------------------
	// Design — brand colors.
	// ---------------------------------------------------------------
	$wp_customize->add_section(
		'sf_design',
		array(
			'title'    => __( 'Somali Focus — Brand Colors', 'somali-focus' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'sf_color_primary',
		array(
			'default'           => '#0B4A8F',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'sf_color_primary',
			array(
				'label'   => __( 'Primary (Blue)', 'somali-focus' ),
				'section' => 'sf_design',
			)
		)
	);

	$wp_customize->add_setting(
		'sf_color_accent',
		array(
			'default'           => '#C8102E',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'sf_color_accent',
			array(
				'label'   => __( 'Accent (Red)', 'somali-focus' ),
				'section' => 'sf_design',
			)
		)
	);

	// ---------------------------------------------------------------
	// Header CTA.
	// ---------------------------------------------------------------
	$wp_customize->add_section(
		'sf_header',
		array(
			'title'    => __( 'Somali Focus — Header', 'somali-focus' ),
			'priority' => 31,
		)
	);

	$wp_customize->add_setting(
		'sf_header_cta_text',
		array(
			'default'           => __( 'Request a Service', 'somali-focus' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'sf_header_cta_text',
		array(
			'label'   => __( 'Header CTA Button Text', 'somali-focus' ),
			'section' => 'sf_header',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'sf_header_cta_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'sf_header_cta_url',
		array(
			'label'       => __( 'Header CTA URL', 'somali-focus' ),
			'description' => __( 'Leave blank to link to the Contact page.', 'somali-focus' ),
			'section'     => 'sf_header',
			'type'        => 'url',
		)
	);

	// ---------------------------------------------------------------
	// Layout toggles.
	// ---------------------------------------------------------------
	$wp_customize->add_section(
		'sf_layout',
		array(
			'title'    => __( 'Somali Focus — Layout', 'somali-focus' ),
			'priority' => 32,
		)
	);

	$wp_customize->add_setting(
		'sf_show_breadcrumbs',
		array(
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
		)
	);
	$wp_customize->add_control(
		'sf_show_breadcrumbs',
		array(
			'label'   => __( 'Show breadcrumbs on inner pages', 'somali-focus' ),
			'section' => 'sf_layout',
			'type'    => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'somali_focus_customize_register' );

/**
 * Output brand color overrides as CSS custom properties only when they
 * differ from the design-system defaults, keeping the base stylesheet
 * cacheable.
 */
function somali_focus_customizer_css() {
	$primary = get_theme_mod( 'sf_color_primary', '#0B4A8F' );
	$accent  = get_theme_mod( 'sf_color_accent', '#C8102E' );

	if ( '#0B4A8F' === $primary && '#C8102E' === $accent ) {
		return;
	}

	$css = ':root{';
	if ( $primary ) {
		$css .= '--sf-blue:' . esc_html( $primary ) . ';';
	}
	if ( $accent ) {
		$css .= '--sf-red:' . esc_html( $accent ) . ';';
	}
	$css .= '}';

	wp_add_inline_style( 'somali-focus-main', $css );
}
add_action( 'wp_enqueue_scripts', 'somali_focus_customizer_css', 20 );

/**
 * Whether breadcrumbs should render, per Customizer toggle.
 *
 * @return bool
 */
function sf_breadcrumbs_enabled() {
	return (bool) get_theme_mod( 'sf_show_breadcrumbs', true );
}
