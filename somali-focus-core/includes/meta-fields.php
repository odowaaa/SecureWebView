<?php
/**
 * Meta boxes for every Somali Focus custom post type.
 *
 * A single config-driven engine renders, saves and sanitizes fields so the
 * same reliable nonce/capability/sanitize path is used everywhere instead
 * of repeating it eight times.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central field configuration for every post type that has a meta box.
 * Field types: text, textarea, richtext, date, number, url, email, select, pdf.
 *
 * @return array
 */
function somali_focus_meta_box_config() {
	return array(
		'sf_course'      => array(
			'title'  => __( 'Course Details', 'somali-focus' ),
			'fields' => array(
				'short_description'     => array( 'label' => __( 'Short Description', 'somali-focus' ), 'type' => 'textarea', 'help' => __( 'Used on course cards and listings.', 'somali-focus' ) ),
				'trainer'                => array( 'label' => __( 'Trainer', 'somali-focus' ), 'type' => 'text' ),
				'duration'               => array( 'label' => __( 'Duration', 'somali-focus' ), 'type' => 'text', 'help' => __( 'e.g. 3 Days / 20 Hours', 'somali-focus' ) ),
				'location'               => array( 'label' => __( 'Location', 'somali-focus' ), 'type' => 'text' ),
				'delivery_mode'          => array( 'label' => __( 'Delivery Mode', 'somali-focus' ), 'type' => 'select', 'options' => array( 'in-person' => __( 'In-Person', 'somali-focus' ), 'online' => __( 'Online', 'somali-focus' ), 'hybrid' => __( 'Hybrid', 'somali-focus' ) ) ),
				'start_date'             => array( 'label' => __( 'Start Date', 'somali-focus' ), 'type' => 'date' ),
				'end_date'               => array( 'label' => __( 'End Date', 'somali-focus' ), 'type' => 'date' ),
				'fee'                    => array( 'label' => __( 'Course Fee', 'somali-focus' ), 'type' => 'text', 'help' => __( 'e.g. $250 or "Free"', 'somali-focus' ) ),
				'max_participants'       => array( 'label' => __( 'Maximum Participants', 'somali-focus' ), 'type' => 'number' ),
				'learning_objectives'    => array( 'label' => __( 'Learning Objectives', 'somali-focus' ), 'type' => 'textarea', 'help' => __( 'One objective per line.', 'somali-focus' ) ),
				'target_audience'        => array( 'label' => __( 'Target Audience', 'somali-focus' ), 'type' => 'textarea' ),
				'course_outline'         => array( 'label' => __( 'Course Outline', 'somali-focus' ), 'type' => 'richtext', 'help' => __( 'One module per line.', 'somali-focus' ) ),
				'requirements'           => array( 'label' => __( 'Requirements', 'somali-focus' ), 'type' => 'textarea' ),
				'registration_status'    => array( 'label' => __( 'Registration Status', 'somali-focus' ), 'type' => 'select', 'options' => array( 'open' => __( 'Open', 'somali-focus' ), 'closed' => __( 'Closed', 'somali-focus' ), 'full' => __( 'Full', 'somali-focus' ) ) ),
				'registration_deadline'  => array( 'label' => __( 'Registration Deadline', 'somali-focus' ), 'type' => 'date' ),
				'contact_info'           => array( 'label' => __( 'Contact Information', 'somali-focus' ), 'type' => 'text', 'help' => __( 'Overrides global contact info for this course only.', 'somali-focus' ) ),
			),
		),
		'sf_advisory'    => array(
			'title'  => __( 'Advisory Project Details', 'somali-focus' ),
			'fields' => array(
				'client_sector'  => array( 'label' => __( 'Client / Sector (anonymized)', 'somali-focus' ), 'type' => 'text', 'help' => __( 'e.g. "International NGO" — never disclose confidential client names.', 'somali-focus' ) ),
				'challenge'      => array( 'label' => __( 'The Challenge', 'somali-focus' ), 'type' => 'richtext' ),
				'approach'       => array( 'label' => __( 'Our Approach', 'somali-focus' ), 'type' => 'richtext' ),
				'deliverables'   => array( 'label' => __( 'Deliverables', 'somali-focus' ), 'type' => 'textarea', 'help' => __( 'One deliverable per line.', 'somali-focus' ) ),
				'results'        => array( 'label' => __( 'Results', 'somali-focus' ), 'type' => 'richtext' ),
				'project_date'   => array( 'label' => __( 'Project Date', 'somali-focus' ), 'type' => 'date' ),
			),
		),
		'sf_research'    => array(
			'title'  => __( 'Research Details', 'somali-focus' ),
			'fields' => array(
				'abstract'          => array( 'label' => __( 'Abstract', 'somali-focus' ), 'type' => 'textarea' ),
				'authors'           => array( 'label' => __( 'Authors', 'somali-focus' ), 'type' => 'text' ),
				'publication_date'  => array( 'label' => __( 'Publication Date', 'somali-focus' ), 'type' => 'date' ),
				'methodology'       => array( 'label' => __( 'Methodology', 'somali-focus' ), 'type' => 'textarea' ),
				'keywords'          => array( 'label' => __( 'Keywords', 'somali-focus' ), 'type' => 'text', 'help' => __( 'Comma separated.', 'somali-focus' ) ),
				'report_pdf'        => array( 'label' => __( 'Report PDF', 'somali-focus' ), 'type' => 'pdf' ),
				'external_url'      => array( 'label' => __( 'External URL', 'somali-focus' ), 'type' => 'url', 'help' => __( 'Optional link to an external publication.', 'somali-focus' ) ),
			),
		),
		'sf_expert'      => array(
			'title'  => __( 'Expert Profile', 'somali-focus' ),
			'fields' => array(
				'position'   => array( 'label' => __( 'Position / Title', 'somali-focus' ), 'type' => 'text' ),
				'education'  => array( 'label' => __( 'Education', 'somali-focus' ), 'type' => 'textarea' ),
				'experience' => array( 'label' => __( 'Professional Experience', 'somali-focus' ), 'type' => 'textarea' ),
				'linkedin'   => array( 'label' => __( 'LinkedIn URL', 'somali-focus' ), 'type' => 'url' ),
				'email'      => array( 'label' => __( 'Email', 'somali-focus' ), 'type' => 'email' ),
				'website'    => array( 'label' => __( 'Website', 'somali-focus' ), 'type' => 'url' ),
			),
		),
		'sf_partner'     => array(
			'title'  => __( 'Partner Details', 'somali-focus' ),
			'fields' => array(
				'website' => array( 'label' => __( 'Website URL', 'somali-focus' ), 'type' => 'url' ),
			),
		),
		'sf_testimonial' => array(
			'title'  => __( 'Testimonial Details', 'somali-focus' ),
			'fields' => array(
				'organization' => array( 'label' => __( 'Organization', 'somali-focus' ), 'type' => 'text' ),
				'position'     => array( 'label' => __( 'Position', 'somali-focus' ), 'type' => 'text' ),
			),
		),
		'sf_registration' => array(
			'title'  => __( 'Registration Details', 'somali-focus' ),
			'fields' => array(
				'full_name'    => array( 'label' => __( 'Full Name', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'organization' => array( 'label' => __( 'Organization', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'position'     => array( 'label' => __( 'Position', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'email'        => array( 'label' => __( 'Email', 'somali-focus' ), 'type' => 'email', 'readonly' => true ),
				'phone'        => array( 'label' => __( 'Phone', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'message'      => array( 'label' => __( 'Message', 'somali-focus' ), 'type' => 'textarea', 'readonly' => true ),
				'status'       => array( 'label' => __( 'Status', 'somali-focus' ), 'type' => 'select', 'options' => array( 'new' => __( 'New', 'somali-focus' ), 'contacted' => __( 'Contacted', 'somali-focus' ), 'confirmed' => __( 'Confirmed', 'somali-focus' ), 'completed' => __( 'Completed', 'somali-focus' ), 'cancelled' => __( 'Cancelled', 'somali-focus' ) ) ),
			),
		),
		'sf_service_request' => array(
			'title'  => __( 'Service Request Details', 'somali-focus' ),
			'fields' => array(
				'full_name'    => array( 'label' => __( 'Full Name', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'organization' => array( 'label' => __( 'Organization', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'email'        => array( 'label' => __( 'Email', 'somali-focus' ), 'type' => 'email', 'readonly' => true ),
				'phone'        => array( 'label' => __( 'Phone', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'service'      => array( 'label' => __( 'Service Required', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'budget_range' => array( 'label' => __( 'Budget Range', 'somali-focus' ), 'type' => 'text', 'readonly' => true ),
				'message'      => array( 'label' => __( 'Message', 'somali-focus' ), 'type' => 'textarea', 'readonly' => true ),
				'status'       => array( 'label' => __( 'Status', 'somali-focus' ), 'type' => 'select', 'options' => array( 'new' => __( 'New', 'somali-focus' ), 'in-progress' => __( 'In Progress', 'somali-focus' ), 'replied' => __( 'Replied', 'somali-focus' ), 'closed' => __( 'Closed', 'somali-focus' ) ) ),
			),
		),
	);
}

/**
 * Register meta boxes for every configured post type.
 */
function somali_focus_add_meta_boxes() {
	foreach ( somali_focus_meta_box_config() as $post_type => $box ) {
		add_meta_box(
			'somali_focus_' . $post_type,
			$box['title'],
			'somali_focus_render_meta_box',
			$post_type,
			'normal',
			'high',
			array( 'post_type' => $post_type )
		);
	}
}
add_action( 'add_meta_boxes', 'somali_focus_add_meta_boxes' );

/**
 * Render a meta box for the given post type using its field config.
 *
 * @param WP_Post $post Current post.
 * @param array   $box  add_meta_box() callback args.
 */
function somali_focus_render_meta_box( $post, $box ) {
	$post_type = $box['args']['post_type'];
	$config    = somali_focus_meta_box_config();
	$fields    = $config[ $post_type ]['fields'];

	wp_nonce_field( 'somali_focus_save_meta_' . $post_type, 'somali_focus_meta_nonce' );

	echo '<table class="form-table somali-focus-meta-table"><tbody>';
	foreach ( $fields as $key => $field ) {
		$value    = somali_focus_meta( $post->ID, $key, '' );
		$input_id = 'sf_' . $key;
		echo '<tr><th><label for="' . esc_attr( $input_id ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
		somali_focus_render_field( $input_id, $key, $field, $value );
		if ( ! empty( $field['help'] ) ) {
			echo '<p class="description">' . esc_html( $field['help'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

/**
 * Render a single form field by type.
 *
 * @param string $id    HTML id/name.
 * @param string $key   Raw meta key (without prefix).
 * @param array  $field Field config.
 * @param mixed  $value Current stored value.
 */
function somali_focus_render_field( $id, $key, $field, $value ) {
	$name     = 'somali_focus_meta[' . $key . ']';
	$readonly = ! empty( $field['readonly'] ) ? ' readonly' : '';

	switch ( $field['type'] ) {
		case 'textarea':
			echo '<textarea class="large-text" rows="4" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '"' . $readonly . '>' . esc_textarea( $value ) . '</textarea>';
			break;

		case 'richtext':
			echo '<textarea class="large-text" rows="6" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '"' . $readonly . '>' . esc_textarea( $value ) . '</textarea>';
			break;

		case 'select':
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( $field['options'] as $opt_value => $opt_label ) {
				echo '<option value="' . esc_attr( $opt_value ) . '" ' . selected( $value, $opt_value, false ) . '>' . esc_html( $opt_label ) . '</option>';
			}
			echo '</select>';
			break;

		case 'date':
			echo '<input type="date" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . $readonly . '>';
			break;

		case 'number':
			echo '<input type="number" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . $readonly . '>';
			break;

		case 'url':
			echo '<input type="url" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_url( $value ) . '"' . $readonly . '>';
			break;

		case 'email':
			echo '<input type="email" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . $readonly . '>';
			break;

		case 'pdf':
			$attachment_id  = (int) $value;
			$attachment_url = $attachment_id ? wp_get_attachment_url( $attachment_id ) : '';
			echo '<input type="hidden" class="somali-focus-pdf-id" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $attachment_id ) . '">';
			echo '<button type="button" class="button somali-focus-pdf-select">' . esc_html__( 'Select PDF', 'somali-focus' ) . '</button> ';
			echo '<button type="button" class="button somali-focus-pdf-clear"' . ( $attachment_id ? '' : ' style="display:none"' ) . '>' . esc_html__( 'Remove', 'somali-focus' ) . '</button>';
			echo '<p class="somali-focus-pdf-filename description">' . ( $attachment_url ? esc_html( basename( $attachment_url ) ) : esc_html__( 'No file selected.', 'somali-focus' ) ) . '</p>';
			break;

		default:
			echo '<input type="text" class="regular-text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"' . $readonly . '>';
	}
}

/**
 * Save meta fields for whichever configured post type is being saved.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function somali_focus_save_meta_fields( $post_id, $post ) {
	$config = somali_focus_meta_box_config();
	if ( ! isset( $config[ $post->post_type ] ) ) {
		return;
	}

	if ( ! isset( $_POST['somali_focus_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['somali_focus_meta_nonce'] ) ), 'somali_focus_save_meta_' . $post->post_type ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( empty( $_POST['somali_focus_meta'] ) || ! is_array( $_POST['somali_focus_meta'] ) ) {
		return;
	}

	$posted = wp_unslash( $_POST['somali_focus_meta'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash

	foreach ( $config[ $post->post_type ]['fields'] as $key => $field ) {
		if ( ! array_key_exists( $key, $posted ) ) {
			continue;
		}
		$raw       = $posted[ $key ];
		$sanitized = somali_focus_sanitize_by_type( $raw, $field['type'] );
		update_post_meta( $post_id, '_sf_' . $key, $sanitized );
	}
}
add_action( 'save_post', 'somali_focus_save_meta_fields', 10, 2 );

/**
 * Sanitize a raw value according to its declared field type.
 *
 * @param mixed  $raw  Raw posted value.
 * @param string $type Field type key.
 * @return mixed
 */
function somali_focus_sanitize_by_type( $raw, $type ) {
	switch ( $type ) {
		case 'textarea':
			return somali_focus_sanitize_textarea( $raw );
		case 'richtext':
			return somali_focus_sanitize_richtext( $raw );
		case 'url':
			return esc_url_raw( trim( $raw ) );
		case 'email':
			return sanitize_email( $raw );
		case 'number':
			return is_numeric( $raw ) ? (int) $raw : '';
		case 'pdf':
			return absint( $raw );
		case 'date':
			return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $raw ) ? $raw : '';
		case 'select':
			return sanitize_key( $raw );
		default:
			return sanitize_text_field( $raw );
	}
}

/**
 * Enqueue the media uploader script on edit screens that need a PDF picker.
 *
 * @param string $hook Current admin page hook.
 */
function somali_focus_admin_enqueue_meta_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	global $post_type;
	if ( 'sf_research' !== $post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'somali-focus-admin-pdf',
		SOMALI_FOCUS_CORE_URL . 'admin/assets/js/pdf-picker.js',
		array( 'jquery' ),
		SOMALI_FOCUS_CORE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'somali_focus_admin_enqueue_meta_assets' );
