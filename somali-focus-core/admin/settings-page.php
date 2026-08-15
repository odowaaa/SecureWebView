<?php
/**
 * Renders the "Somali Focus → Settings" admin page.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Small helper to print a labeled text/textarea/url/email input bound to
 * the consolidated settings array.
 *
 * @param array  $settings Current settings values.
 * @param string $key      Settings key.
 * @param string $label    Field label.
 * @param string $type     Input type: text, textarea, url, email.
 */
function somali_focus_settings_field( $settings, $key, $label, $type = 'text' ) {
	$value = isset( $settings[ $key ] ) ? $settings[ $key ] : '';
	$id    = 'sfset_' . $key;
	$name  = 'somali_focus_settings[' . $key . ']';

	echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label></th><td>';
	if ( 'textarea' === $type ) {
		echo '<textarea class="large-text" rows="3" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>';
	} else {
		echo '<input type="' . esc_attr( $type ) . '" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '">';
	}
	echo '</td></tr>';
}

/**
 * Render the settings page.
 */
function somali_focus_render_settings_page() {
	if ( ! current_user_can( somali_focus_manage_cap() ) ) {
		return;
	}

	$settings = wp_parse_args( get_option( 'somali_focus_settings', array() ), somali_focus_default_settings() );
	?>
	<div class="wrap somali-focus-settings">
		<h1><?php esc_html_e( 'Somali Focus Settings', 'somali-focus' ); ?></h1>
		<p><?php esc_html_e( 'These values power the organization info, homepage copy and statistics used across the site. No code editing required.', 'somali-focus' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'somali_focus_settings_group' ); ?>

			<h2 class="title"><?php esc_html_e( 'Organization Information', 'somali-focus' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php
				somali_focus_settings_field( $settings, 'org_name', __( 'Organization Name', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'tagline', __( 'Tagline', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'email', __( 'Email', 'somali-focus' ), 'email' );
				somali_focus_settings_field( $settings, 'phone', __( 'Phone', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'address', __( 'Office Address', 'somali-focus' ), 'textarea' );
				somali_focus_settings_field( $settings, 'website', __( 'Website URL', 'somali-focus' ), 'url' );
				somali_focus_settings_field( $settings, 'office_hours', __( 'Office Hours', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'notification_email', __( 'New Submission Notification Email', 'somali-focus' ), 'email' );
				?>
			</table>

			<h2 class="title"><?php esc_html_e( 'Social Media', 'somali-focus' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php
				somali_focus_settings_field( $settings, 'social_facebook', __( 'Facebook URL', 'somali-focus' ), 'url' );
				somali_focus_settings_field( $settings, 'social_linkedin', __( 'LinkedIn URL', 'somali-focus' ), 'url' );
				somali_focus_settings_field( $settings, 'social_twitter', __( 'X / Twitter URL', 'somali-focus' ), 'url' );
				somali_focus_settings_field( $settings, 'social_youtube', __( 'YouTube URL', 'somali-focus' ), 'url' );
				somali_focus_settings_field( $settings, 'social_whatsapp', __( 'WhatsApp Number', 'somali-focus' ) );
				?>
			</table>

			<h2 class="title"><?php esc_html_e( 'Homepage — Hero', 'somali-focus' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php
				somali_focus_settings_field( $settings, 'hero_title', __( 'Hero Title', 'somali-focus' ), 'textarea' );
				somali_focus_settings_field( $settings, 'hero_description', __( 'Hero Description', 'somali-focus' ), 'textarea' );
				somali_focus_settings_field( $settings, 'hero_button_primary_text', __( 'Primary Button Text', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'hero_button_primary_url', __( 'Primary Button URL', 'somali-focus' ), 'url' );
				somali_focus_settings_field( $settings, 'hero_button_secondary_text', __( 'Secondary Button Text', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'hero_button_secondary_url', __( 'Secondary Button URL', 'somali-focus' ), 'url' );
				?>
			</table>

			<h2 class="title"><?php esc_html_e( 'Homepage — About', 'somali-focus' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php
				somali_focus_settings_field( $settings, 'about_text', __( 'About Text', 'somali-focus' ), 'textarea' );
				somali_focus_settings_field( $settings, 'mission_text', __( 'Mission', 'somali-focus' ), 'textarea' );
				somali_focus_settings_field( $settings, 'vision_text', __( 'Vision', 'somali-focus' ), 'textarea' );
				?>
			</table>

			<h2 class="title"><?php esc_html_e( 'Homepage — Impact Statistics', 'somali-focus' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<?php
					somali_focus_settings_field( $settings, "stat_{$i}_number", sprintf( /* translators: %d stat position */ __( 'Statistic %d — Number', 'somali-focus' ), $i ) );
					somali_focus_settings_field( $settings, "stat_{$i}_label", sprintf( /* translators: %d stat position */ __( 'Statistic %d — Label', 'somali-focus' ), $i ) );
					?>
				<?php endfor; ?>
			</table>

			<h2 class="title"><?php esc_html_e( 'Homepage — Final Call To Action', 'somali-focus' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php
				somali_focus_settings_field( $settings, 'cta_title', __( 'CTA Title', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'cta_description', __( 'CTA Description', 'somali-focus' ), 'textarea' );
				somali_focus_settings_field( $settings, 'cta_button_primary_text', __( 'Primary Button Text', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'cta_button_primary_url', __( 'Primary Button URL', 'somali-focus' ), 'url' );
				somali_focus_settings_field( $settings, 'cta_button_secondary_text', __( 'Secondary Button Text', 'somali-focus' ) );
				somali_focus_settings_field( $settings, 'cta_button_secondary_url', __( 'Secondary Button URL', 'somali-focus' ), 'url' );
				?>
			</table>

			<?php submit_button( __( 'Save Settings', 'somali-focus' ) ); ?>
		</form>
	</div>
	<?php
}
