<?php
/**
 * Shortcodes that render fully functional forms/markup independent of any
 * particular theme. A theme may instead call the same query helpers
 * directly for full design control — these shortcodes are the safety net
 * that keeps forms working even on an unstyled or third-party theme.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the minimal public assets whenever a Somali Focus shortcode runs.
 */
function somali_focus_maybe_enqueue_public_assets() {
	wp_enqueue_style( 'somali-focus-core-forms', SOMALI_FOCUS_CORE_URL . 'public/css/forms.css', array(), SOMALI_FOCUS_CORE_VERSION );
	wp_enqueue_script( 'somali-focus-core-forms', SOMALI_FOCUS_CORE_URL . 'public/js/forms.js', array(), SOMALI_FOCUS_CORE_VERSION, true );
}

/**
 * Render the success/error notice for a just-submitted form, if present.
 *
 * @param string $context 'registration' or 'request'.
 * @return string
 */
function somali_focus_form_status_notice( $context ) {
	if ( empty( $_GET['sf_form_status'] ) || empty( $_GET['sf_form_context'] ) || $context !== $_GET['sf_form_context'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return '';
	}

	$status  = sanitize_key( $_GET['sf_form_status'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$message = isset( $_GET['sf_form_message'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['sf_form_message'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( 'success' === $status ) {
		$text = 'registration' === $context
			? __( 'Thank you — your registration has been received. We will be in touch shortly.', 'somali-focus' )
			: __( 'Thank you — your request has been received. We will be in touch shortly.', 'somali-focus' );
		return '<div class="sf-form-notice sf-form-notice--success">' . esc_html( $text ) . '</div>';
	}

	$text = $message ? $message : __( 'Something went wrong. Please check the form and try again.', 'somali-focus' );
	return '<div class="sf-form-notice sf-form-notice--error">' . esc_html( $text ) . '</div>';
}

/**
 * [sf_course_registration_form course_id="123"]
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function somali_focus_course_registration_form_shortcode( $atts ) {
	somali_focus_maybe_enqueue_public_assets();
	$atts      = shortcode_atts( array( 'course_id' => 0 ), $atts );
	$course_id = absint( $atts['course_id'] );

	ob_start();
	?>
	<div id="sf-form" class="sf-form-wrapper">
		<?php echo somali_focus_form_status_notice( 'registration' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<form class="sf-form sf-registration-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="somali_focus_course_registration">
			<input type="hidden" name="course_id" value="<?php echo esc_attr( $course_id ); ?>">
			<?php wp_nonce_field( 'somali_focus_registration', 'somali_focus_registration_nonce' ); ?>
			<p class="sf-field sf-field--honeypot"><label>Website<input type="text" name="sf_website" tabindex="-1" autocomplete="off"></label></p>

			<p class="sf-field"><label for="sf-reg-name"><?php esc_html_e( 'Full Name', 'somali-focus' ); ?> *</label><input type="text" id="sf-reg-name" name="full_name" required></p>
			<p class="sf-field"><label for="sf-reg-org"><?php esc_html_e( 'Organization', 'somali-focus' ); ?></label><input type="text" id="sf-reg-org" name="organization"></p>
			<p class="sf-field"><label for="sf-reg-position"><?php esc_html_e( 'Position', 'somali-focus' ); ?></label><input type="text" id="sf-reg-position" name="position"></p>
			<p class="sf-field"><label for="sf-reg-email"><?php esc_html_e( 'Email', 'somali-focus' ); ?> *</label><input type="email" id="sf-reg-email" name="email" required></p>
			<p class="sf-field"><label for="sf-reg-phone"><?php esc_html_e( 'Phone', 'somali-focus' ); ?></label><input type="tel" id="sf-reg-phone" name="phone"></p>
			<p class="sf-field sf-field--wide"><label for="sf-reg-message"><?php esc_html_e( 'Message', 'somali-focus' ); ?></label><textarea id="sf-reg-message" name="message" rows="4"></textarea></p>

			<p class="sf-field sf-field--submit"><button type="submit" class="sf-btn sf-btn--primary"><?php esc_html_e( 'Submit Registration', 'somali-focus' ); ?></button></p>
		</form>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'sf_course_registration_form', 'somali_focus_course_registration_form_shortcode' );

/**
 * [sf_service_request_form]
 *
 * @return string
 */
function somali_focus_service_request_form_shortcode() {
	somali_focus_maybe_enqueue_public_assets();

	ob_start();
	?>
	<div id="sf-form" class="sf-form-wrapper">
		<?php echo somali_focus_form_status_notice( 'request' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<form class="sf-form sf-service-request-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="somali_focus_service_request">
			<?php wp_nonce_field( 'somali_focus_service_request', 'somali_focus_service_request_nonce' ); ?>
			<p class="sf-field sf-field--honeypot"><label>Website<input type="text" name="sf_website" tabindex="-1" autocomplete="off"></label></p>

			<p class="sf-field"><label for="sf-req-name"><?php esc_html_e( 'Full Name', 'somali-focus' ); ?> *</label><input type="text" id="sf-req-name" name="full_name" required></p>
			<p class="sf-field"><label for="sf-req-org"><?php esc_html_e( 'Organization', 'somali-focus' ); ?></label><input type="text" id="sf-req-org" name="organization"></p>
			<p class="sf-field"><label for="sf-req-email"><?php esc_html_e( 'Email', 'somali-focus' ); ?> *</label><input type="email" id="sf-req-email" name="email" required></p>
			<p class="sf-field"><label for="sf-req-phone"><?php esc_html_e( 'Phone', 'somali-focus' ); ?></label><input type="tel" id="sf-req-phone" name="phone"></p>
			<p class="sf-field">
				<label for="sf-req-service"><?php esc_html_e( 'Service Required', 'somali-focus' ); ?> *</label>
				<select id="sf-req-service" name="service" required>
					<option value="training"><?php esc_html_e( 'Training', 'somali-focus' ); ?></option>
					<option value="advisory"><?php esc_html_e( 'Advisory', 'somali-focus' ); ?></option>
					<option value="research"><?php esc_html_e( 'Research', 'somali-focus' ); ?></option>
					<option value="other"><?php esc_html_e( 'Other', 'somali-focus' ); ?></option>
				</select>
			</p>
			<p class="sf-field"><label for="sf-req-budget"><?php esc_html_e( 'Budget Range', 'somali-focus' ); ?></label><input type="text" id="sf-req-budget" name="budget_range"></p>
			<p class="sf-field sf-field--wide"><label for="sf-req-message"><?php esc_html_e( 'Message', 'somali-focus' ); ?> *</label><textarea id="sf-req-message" name="message" rows="5" required></textarea></p>

			<p class="sf-field sf-field--submit"><button type="submit" class="sf-btn sf-btn--primary"><?php esc_html_e( 'Send Request', 'somali-focus' ); ?></button></p>
		</form>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'sf_service_request_form', 'somali_focus_service_request_form_shortcode' );

/**
 * [sf_stats] — plain fallback markup for the impact statistics.
 *
 * @return string
 */
function somali_focus_stats_shortcode() {
	$blocks = somali_focus_get_stat_blocks();
	ob_start();
	?>
	<div class="sf-stats-fallback">
		<?php foreach ( $blocks as $block ) : ?>
			<div class="sf-stat-fallback">
				<span class="sf-stat-fallback__number"><?php echo esc_html( $block['number'] ); ?></span>
				<span class="sf-stat-fallback__label"><?php echo esc_html( $block['label'] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'sf_stats', 'somali_focus_stats_shortcode' );
