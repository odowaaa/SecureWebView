<?php
/**
 * Front-end form handling: course registrations and general service
 * requests. Both post to admin-post.php, are nonce + honeypot protected,
 * and store submissions as private CPT posts (never exposed via REST).
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirect back to where the visitor came from with a status flag,
 * following the Post/Redirect/Get pattern so refreshing never re-submits.
 *
 * @param string $status  'success' or 'error'.
 * @param string $context 'registration' or 'request'.
 * @param string $message Optional human-readable error detail.
 */
function somali_focus_form_redirect( $status, $context, $message = '' ) {
	$fallback = home_url( '/' );
	$referer  = wp_get_referer();
	$target   = $referer ? $referer : $fallback;

	$target = remove_query_arg( array( 'sf_form_status', 'sf_form_context', 'sf_form_message' ), $target );
	$target = add_query_arg(
		array(
			'sf_form_status'  => $status,
			'sf_form_context' => $context,
		),
		$target
	);
	if ( $message ) {
		$target = add_query_arg( 'sf_form_message', rawurlencode( $message ), $target );
	}

	wp_safe_redirect( $target . '#sf-form' );
	exit;
}

/**
 * Handle a course registration submission.
 */
function somali_focus_handle_course_registration() {
	somali_focus_verify_nonce_or_die( 'somali_focus_registration', 'somali_focus_registration_nonce' );

	if ( ! somali_focus_passes_honeypot() ) {
		somali_focus_form_redirect( 'success', 'registration' ); // Silently drop bots as if it succeeded.
	}

	$full_name = isset( $_POST['full_name'] ) ? sanitize_text_field( wp_unslash( $_POST['full_name'] ) ) : '';
	$email     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$course_id = isset( $_POST['course_id'] ) ? absint( $_POST['course_id'] ) : 0;

	if ( '' === $full_name || ! is_email( $email ) ) {
		somali_focus_form_redirect( 'error', 'registration', __( 'Please provide your name and a valid email address.', 'somali-focus' ) );
	}

	$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : '';
	$position     = isset( $_POST['position'] ) ? sanitize_text_field( wp_unslash( $_POST['position'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? somali_focus_sanitize_phone( $_POST['phone'] ) : '';
	$message      = isset( $_POST['message'] ) ? somali_focus_sanitize_textarea( $_POST['message'] ) : '';
	$course_title = $course_id && get_post( $course_id ) ? get_the_title( $course_id ) : __( 'General Training Inquiry', 'somali-focus' );

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'sf_registration',
			'post_status' => 'publish',
			/* translators: 1: applicant name, 2: course title, 3: date */
			'post_title'  => sprintf( __( '%1$s — %2$s (%3$s)', 'somali-focus' ), $full_name, $course_title, current_time( 'Y-m-d H:i' ) ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		somali_focus_form_redirect( 'error', 'registration', __( 'Something went wrong saving your registration. Please try again.', 'somali-focus' ) );
	}

	$fields = compact( 'full_name', 'organization', 'position', 'email', 'phone', 'course_id', 'message' );
	foreach ( $fields as $key => $value ) {
		update_post_meta( $post_id, '_sf_' . $key, $value );
	}
	update_post_meta( $post_id, '_sf_status', 'new' );

	somali_focus_notify_admin_new_registration( $post_id );

	somali_focus_form_redirect( 'success', 'registration' );
}
add_action( 'admin_post_somali_focus_course_registration', 'somali_focus_handle_course_registration' );
add_action( 'admin_post_nopriv_somali_focus_course_registration', 'somali_focus_handle_course_registration' );

/**
 * Handle a general Training / Advisory / Research service request.
 */
function somali_focus_handle_service_request() {
	somali_focus_verify_nonce_or_die( 'somali_focus_service_request', 'somali_focus_service_request_nonce' );

	if ( ! somali_focus_passes_honeypot() ) {
		somali_focus_form_redirect( 'success', 'request' );
	}

	$full_name = isset( $_POST['full_name'] ) ? sanitize_text_field( wp_unslash( $_POST['full_name'] ) ) : '';
	$email     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( '' === $full_name || ! is_email( $email ) ) {
		somali_focus_form_redirect( 'error', 'request', __( 'Please provide your name and a valid email address.', 'somali-focus' ) );
	}

	$allowed_services = array( 'training', 'advisory', 'research', 'other' );
	$service          = isset( $_POST['service'] ) ? sanitize_key( $_POST['service'] ) : 'other';
	if ( ! in_array( $service, $allowed_services, true ) ) {
		$service = 'other';
	}

	$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? somali_focus_sanitize_phone( $_POST['phone'] ) : '';
	$budget_range = isset( $_POST['budget_range'] ) ? sanitize_text_field( wp_unslash( $_POST['budget_range'] ) ) : '';
	$message      = isset( $_POST['message'] ) ? somali_focus_sanitize_textarea( $_POST['message'] ) : '';

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'sf_service_request',
			'post_status' => 'publish',
			/* translators: 1: applicant name, 2: service, 3: date */
			'post_title'  => sprintf( __( '%1$s — %2$s (%3$s)', 'somali-focus' ), $full_name, ucfirst( $service ), current_time( 'Y-m-d H:i' ) ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		somali_focus_form_redirect( 'error', 'request', __( 'Something went wrong saving your request. Please try again.', 'somali-focus' ) );
	}

	$fields = compact( 'full_name', 'organization', 'email', 'phone', 'service', 'budget_range', 'message' );
	foreach ( $fields as $key => $value ) {
		update_post_meta( $post_id, '_sf_' . $key, $value );
	}
	update_post_meta( $post_id, '_sf_status', 'new' );

	somali_focus_notify_admin_new_service_request( $post_id );

	somali_focus_form_redirect( 'success', 'request' );
}
add_action( 'admin_post_somali_focus_service_request', 'somali_focus_handle_service_request' );
add_action( 'admin_post_nopriv_somali_focus_service_request', 'somali_focus_handle_service_request' );
