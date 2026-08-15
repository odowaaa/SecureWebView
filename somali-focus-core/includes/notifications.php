<?php
/**
 * Admin email notifications for new registrations and service requests.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve who should receive submission notifications.
 *
 * @return string
 */
function somali_focus_notification_recipient() {
	$configured = somali_focus_get_option( 'notification_email', '' );
	return is_email( $configured ) ? $configured : get_option( 'admin_email' );
}

/**
 * Shared mail headers: Reply-To the submitter so the admin can respond
 * directly from their inbox.
 *
 * @param string $reply_to_email Submitter email.
 * @return array
 */
function somali_focus_mail_headers( $reply_to_email ) {
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $reply_to_email ) ) {
		$headers[] = 'Reply-To: ' . $reply_to_email;
	}
	return $headers;
}

/**
 * Notify the admin of a new training registration.
 *
 * @param int $post_id sf_registration post ID.
 */
function somali_focus_notify_admin_new_registration( $post_id ) {
	$full_name    = somali_focus_meta( $post_id, 'full_name' );
	$email        = somali_focus_meta( $post_id, 'email' );
	$organization = somali_focus_meta( $post_id, 'organization', '—' );
	$phone        = somali_focus_meta( $post_id, 'phone', '—' );
	$course_id    = (int) somali_focus_meta( $post_id, 'course_id' );
	$course_title = $course_id ? get_the_title( $course_id ) : __( 'General Training Inquiry', 'somali-focus' );
	$message      = somali_focus_meta( $post_id, 'message', '' );

	$org_name = somali_focus_get_option( 'org_name', get_bloginfo( 'name' ) );
	/* translators: %s: organization name */
	$subject = sprintf( __( '[%s] New Training Registration', 'somali-focus' ), $org_name );

	$body  = __( 'A new training registration has been submitted.', 'somali-focus' ) . "\n\n";
	$body .= __( 'Course:', 'somali-focus' ) . ' ' . $course_title . "\n";
	$body .= __( 'Name:', 'somali-focus' ) . ' ' . $full_name . "\n";
	$body .= __( 'Organization:', 'somali-focus' ) . ' ' . $organization . "\n";
	$body .= __( 'Email:', 'somali-focus' ) . ' ' . $email . "\n";
	$body .= __( 'Phone:', 'somali-focus' ) . ' ' . $phone . "\n";
	if ( $message ) {
		$body .= "\n" . __( 'Message:', 'somali-focus' ) . "\n" . $message . "\n";
	}
	$body .= "\n" . __( 'Review this registration:', 'somali-focus' ) . ' ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";

	wp_mail( somali_focus_notification_recipient(), $subject, $body, somali_focus_mail_headers( $email ) );
}

/**
 * Notify the admin of a new service request.
 *
 * @param int $post_id sf_service_request post ID.
 */
function somali_focus_notify_admin_new_service_request( $post_id ) {
	$full_name    = somali_focus_meta( $post_id, 'full_name' );
	$email        = somali_focus_meta( $post_id, 'email' );
	$organization = somali_focus_meta( $post_id, 'organization', '—' );
	$phone        = somali_focus_meta( $post_id, 'phone', '—' );
	$service      = ucfirst( somali_focus_meta( $post_id, 'service', 'other' ) );
	$budget       = somali_focus_meta( $post_id, 'budget_range', '—' );
	$message      = somali_focus_meta( $post_id, 'message', '' );

	$org_name = somali_focus_get_option( 'org_name', get_bloginfo( 'name' ) );
	/* translators: %s: organization name */
	$subject = sprintf( __( '[%s] New Service Request', 'somali-focus' ), $org_name );

	$body  = __( 'A new service request has been submitted.', 'somali-focus' ) . "\n\n";
	$body .= __( 'Service:', 'somali-focus' ) . ' ' . $service . "\n";
	$body .= __( 'Name:', 'somali-focus' ) . ' ' . $full_name . "\n";
	$body .= __( 'Organization:', 'somali-focus' ) . ' ' . $organization . "\n";
	$body .= __( 'Email:', 'somali-focus' ) . ' ' . $email . "\n";
	$body .= __( 'Phone:', 'somali-focus' ) . ' ' . $phone . "\n";
	$body .= __( 'Budget Range:', 'somali-focus' ) . ' ' . $budget . "\n";
	if ( $message ) {
		$body .= "\n" . __( 'Message:', 'somali-focus' ) . "\n" . $message . "\n";
	}
	$body .= "\n" . __( 'Review this request:', 'somali-focus' ) . ' ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";

	wp_mail( somali_focus_notification_recipient(), $subject, $body, somali_focus_mail_headers( $email ) );
}
