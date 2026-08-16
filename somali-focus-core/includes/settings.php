<?php
/**
 * Site-wide Somali Focus settings: organization info, socials, homepage
 * copy and statistics. Single consolidated option so the theme only ever
 * needs somali_focus_get_option().
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Full default settings, used on activation and as a fallback schema.
 *
 * @return array
 */
function somali_focus_default_settings() {
	$defaults = array(
		// Organization.
		'org_name'                    => 'Somali Focus',
		'tagline'                     => __( 'Training, Advisory & Research', 'somali-focus' ),
		'email'                       => 'info@somalifocus.org',
		'phone'                       => '+252 61 000 0000',
		'address'                     => __( 'Garowe, Puntland, Somalia', 'somali-focus' ),
		'website'                     => 'https://somalifocus.org',
		'office_hours'                => __( 'Sunday – Thursday, 8:00 AM – 5:00 PM', 'somali-focus' ),

		// Socials.
		'social_facebook'             => '',
		'social_linkedin'             => '',
		'social_twitter'              => '',
		'social_youtube'              => '',
		'social_whatsapp'             => '',

		// Homepage — hero.
		'hero_title'                  => __( 'Empowering People. Strengthening Organizations. Generating Evidence.', 'somali-focus' ),
		'hero_description'            => __( 'Somali Focus provides professional Training, Advisory and Research services designed to strengthen individual and organizational capacity and support evidence-based decision making.', 'somali-focus' ),
		'hero_button_primary_text'    => __( 'Explore Our Services', 'somali-focus' ),
		'hero_button_primary_url'     => '',
		'hero_button_secondary_text'  => __( 'Start a Conversation', 'somali-focus' ),
		'hero_button_secondary_url'   => '',

		// Homepage — welcome video.
		'welcome_video_url'           => '',
		'welcome_video_poster_id'     => '',

		// Homepage — about.
		'about_text'                  => __( 'Somali Focus is a professional Training, Advisory and Research organization committed to strengthening people, organizations and decision-making through practical knowledge, professional expertise and evidence.', 'somali-focus' ),
		'mission_text'                => __( 'To strengthen people and organizations through quality training, practical advisory services and credible research.', 'somali-focus' ),
		'vision_text'                 => __( 'A more capable, informed and evidence-driven Somalia.', 'somali-focus' ),

		// Homepage — stats.
		'stat_1_number'                => '500+',
		'stat_1_label'                 => __( 'Professionals Trained', 'somali-focus' ),
		'stat_2_number'                => '50+',
		'stat_2_label'                 => __( 'Organizations Supported', 'somali-focus' ),
		'stat_3_number'                => '100+',
		'stat_3_label'                 => __( 'Assignments Completed', 'somali-focus' ),
		'stat_4_number'                => '10+',
		'stat_4_label'                 => __( 'Areas of Expertise', 'somali-focus' ),

		// Homepage — final CTA.
		'cta_title'                   => __( 'Have a Training, Advisory or Research Need?', 'somali-focus' ),
		'cta_description'             => __( "Let's discuss how Somali Focus can support your organization.", 'somali-focus' ),
		'cta_button_primary_text'     => __( 'Request a Service', 'somali-focus' ),
		'cta_button_primary_url'      => '',
		'cta_button_secondary_text'   => __( 'Contact Us', 'somali-focus' ),
		'cta_button_secondary_url'    => '',

		// Notifications.
		'notification_email'          => '',
	);

	// Advertising — every slot ships disabled with empty code.
	if ( function_exists( 'somali_focus_ad_slots' ) ) {
		foreach ( array_keys( somali_focus_ad_slots() ) as $slot_id ) {
			$defaults[ "ad_{$slot_id}_enabled" ] = '';
			$defaults[ "ad_{$slot_id}_code" ]    = '';
		}
	}

	return $defaults;
}

/**
 * Which sanitizer to run per settings key, grouped by field type.
 *
 * @return array key => type
 */
function somali_focus_settings_schema() {
	$textarea_fields = array( 'hero_description', 'about_text', 'mission_text', 'vision_text', 'cta_description', 'address' );
	$url_fields      = array( 'website', 'social_facebook', 'social_linkedin', 'social_twitter', 'social_youtube', 'hero_button_primary_url', 'hero_button_secondary_url', 'cta_button_primary_url', 'cta_button_secondary_url', 'welcome_video_url' );
	$email_fields    = array( 'email', 'notification_email' );
	$phone_fields    = array( 'phone', 'social_whatsapp' );
	$int_fields      = array( 'welcome_video_poster_id' );

	$schema = array();
	foreach ( array_keys( somali_focus_default_settings() ) as $key ) {
		if ( in_array( $key, $textarea_fields, true ) ) {
			$schema[ $key ] = 'textarea';
		} elseif ( in_array( $key, $url_fields, true ) ) {
			$schema[ $key ] = 'url';
		} elseif ( in_array( $key, $email_fields, true ) ) {
			$schema[ $key ] = 'email';
		} elseif ( in_array( $key, $phone_fields, true ) ) {
			$schema[ $key ] = 'phone';
		} elseif ( in_array( $key, $int_fields, true ) ) {
			$schema[ $key ] = 'int';
		} elseif ( 1 === preg_match( '/^ad_.+_enabled$/', $key ) ) {
			$schema[ $key ] = 'checkbox';
		} elseif ( 1 === preg_match( '/^ad_.+_code$/', $key ) ) {
			$schema[ $key ] = 'raw_html';
		} else {
			$schema[ $key ] = 'text';
		}
	}
	return $schema;
}

/**
 * Sanitize the whole settings array before it is saved.
 *
 * @param array $input Raw posted settings.
 * @return array
 */
function somali_focus_sanitize_settings( $input ) {
	$schema   = somali_focus_settings_schema();
	$defaults = somali_focus_default_settings();
	$clean    = array();

	foreach ( $defaults as $key => $default ) {
		$raw = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';

		switch ( $schema[ $key ] ) {
			case 'textarea':
				$clean[ $key ] = somali_focus_sanitize_textarea( $raw );
				break;
			case 'url':
				$clean[ $key ] = $raw ? esc_url_raw( trim( $raw ) ) : '';
				break;
			case 'email':
				$clean[ $key ] = sanitize_email( $raw );
				break;
			case 'phone':
				$clean[ $key ] = somali_focus_sanitize_phone( $raw );
				break;
			case 'int':
				$clean[ $key ] = $raw ? absint( $raw ) : '';
				break;
			case 'checkbox':
				$clean[ $key ] = $raw ? '1' : '';
				break;
			case 'raw_html':
				// Deliberately NOT run through sanitize_textarea_field()/
				// wp_kses_post() — both would strip the <script>/<ins> tags
				// this field exists to hold. See includes/ad-slots.php for
				// the trusted-admin-field rationale.
				$clean[ $key ] = $raw;
				break;
			default:
				$clean[ $key ] = sanitize_text_field( $raw );
		}
	}

	return $clean;
}

/**
 * Register the setting with WordPress' Settings API.
 */
function somali_focus_register_settings() {
	register_setting(
		'somali_focus_settings_group',
		'somali_focus_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'somali_focus_sanitize_settings',
			'default'           => somali_focus_default_settings(),
		)
	);
}
add_action( 'admin_init', 'somali_focus_register_settings' );
