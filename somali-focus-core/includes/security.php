<?php
/**
 * Shared security helpers: capability checks, sanitization, safe uploads.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Capability required to manage Somali Focus submissions (registrations,
 * service requests) and settings. Kept as a filterable single source of
 * truth rather than sprinkling 'manage_options' across the codebase.
 *
 * @return string
 */
function somali_focus_manage_cap() {
	return apply_filters( 'somali_focus_manage_capability', 'manage_options' );
}

/**
 * Die with a generic error if the current request's nonce is invalid.
 *
 * @param string $action Nonce action name.
 * @param string $field  $_POST/$_REQUEST field holding the nonce.
 */
function somali_focus_verify_nonce_or_die( $action, $field = '_wpnonce' ) {
	$nonce = isset( $_REQUEST[ $field ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $field ] ) ) : '';
	if ( ! $nonce || ! wp_verify_nonce( $nonce, $action ) ) {
		wp_die( esc_html__( 'Security check failed. Please go back and try again.', 'somali-focus' ), 403 );
	}
}

/**
 * Sanitize a multi-line textarea while preserving line breaks, stripping tags.
 *
 * @param string $value Raw value.
 * @return string
 */
function somali_focus_sanitize_textarea( $value ) {
	return sanitize_textarea_field( wp_unslash( $value ) );
}

/**
 * Sanitize a restricted set of safe HTML tags for longer rich-text fields
 * (challenge/approach/results, biography, etc.).
 *
 * @param string $value Raw HTML.
 * @return string
 */
function somali_focus_sanitize_richtext( $value ) {
	return wp_kses_post( wp_unslash( $value ) );
}

/**
 * Sanitize a phone number field (digits, spaces, +, -, parentheses only).
 *
 * @param string $value Raw phone value.
 * @return string
 */
function somali_focus_sanitize_phone( $value ) {
	$value = wp_unslash( $value );
	return preg_replace( '/[^0-9+\-\s\(\)]/', '', $value );
}

/**
 * Validate an uploaded research report is a PDF and within size limits
 * before it is attached to a post via the media library.
 *
 * @param array $file $_FILES-style array.
 * @return true|WP_Error
 */
function somali_focus_validate_pdf_upload( $file ) {
	if ( empty( $file['tmp_name'] ) ) {
		return new WP_Error( 'sf_no_file', __( 'No file was uploaded.', 'somali-focus' ) );
	}

	$filetype = wp_check_filetype( $file['name'], array( 'pdf' => 'application/pdf' ) );
	if ( 'pdf' !== $filetype['ext'] ) {
		return new WP_Error( 'sf_invalid_type', __( 'Only PDF files are allowed for research reports.', 'somali-focus' ) );
	}

	$max_bytes = 20 * MB_IN_BYTES;
	if ( ! empty( $file['size'] ) && $file['size'] > $max_bytes ) {
		return new WP_Error( 'sf_file_too_large', __( 'The PDF must be smaller than 20MB.', 'somali-focus' ) );
	}

	return true;
}

/**
 * Simple honeypot spam check shared by both front-end forms.
 * The field is hidden from real visitors with CSS and must stay empty.
 *
 * @param string $field_name Honeypot field name.
 * @return bool True if the submission looks human.
 */
function somali_focus_passes_honeypot( $field_name = 'sf_website' ) {
	return empty( $_POST[ $field_name ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- honeypot only, nonce checked separately.
}
