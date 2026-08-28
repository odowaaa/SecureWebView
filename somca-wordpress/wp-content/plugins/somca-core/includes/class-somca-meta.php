<?php
/**
 * Meta boxes & meta fields for SomCA post types.
 * Deliberately dependency-free (no ACF requirement) so the plugin works standalone.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Meta {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_somca_hotspot', array( $this, 'save_hotspot' ) );
		add_action( 'save_post_somca_report', array( $this, 'save_report' ) );
		add_action( 'save_post_somca_alert', array( $this, 'save_alert' ) );

		// Expose key meta fields to REST for map/chart consumption.
		add_action( 'init', array( $this, 'register_meta_fields' ) );
	}

	public function register_meta_fields() {
		$hotspot_fields = array(
			'somca_lat'         => 'string',
			'somca_lng'         => 'string',
			'somca_risk_level'  => 'string', // low | moderate | high | critical
			'somca_status'      => 'string', // monitoring | watch | warning | critical
			'somca_population_affected' => 'string',
		);
		foreach ( $hotspot_fields as $key => $type ) {
			register_post_meta( 'somca_hotspot', $key, array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => $type,
				'auth_callback'=> function() { return current_user_can( 'edit_posts' ); },
			) );
		}

		$report_fields = array(
			'somca_period_start'      => 'string',
			'somca_period_end'        => 'string',
			'somca_avg_temp_c'        => 'string',
			'somca_temp_anomaly_c'    => 'string',
			'somca_rainfall_mm'       => 'string',
			'somca_rainfall_anomaly_pct' => 'string',
			'somca_data_source'       => 'string',
			'somca_related_hotspot'   => 'string',
			'somca_auto_generated'    => 'string',
		);
		foreach ( $report_fields as $key => $type ) {
			register_post_meta( 'somca_report', $key, array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => $type,
				'auth_callback'=> function() { return current_user_can( 'edit_posts' ); },
			) );
		}

		$alert_fields = array(
			'somca_severity'      => 'string', // advisory | watch | warning | emergency
			'somca_issued_date'   => 'string',
			'somca_expires_date'  => 'string',
			'somca_recommended_actions' => 'string',
			'somca_related_hotspot'     => 'string',
		);
		foreach ( $alert_fields as $key => $type ) {
			register_post_meta( 'somca_alert', $key, array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => $type,
				'auth_callback'=> function() { return current_user_can( 'edit_posts' ); },
			) );
		}
	}

	public function add_meta_boxes() {
		add_meta_box( 'somca_hotspot_details', __( 'Hotspot Details', 'somca-core' ), array( $this, 'render_hotspot_box' ), 'somca_hotspot', 'normal', 'high' );
		add_meta_box( 'somca_report_details', __( 'Report Data', 'somca-core' ), array( $this, 'render_report_box' ), 'somca_report', 'normal', 'high' );
		add_meta_box( 'somca_alert_details', __( 'Alert Details', 'somca-core' ), array( $this, 'render_alert_box' ), 'somca_alert', 'normal', 'high' );
	}

	private function field_row( $label, $id, $value, $type = 'text', $options = array(), $desc = '' ) {
		echo '<p><label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $label ) . '</strong></label><br/>';
		if ( 'select' === $type ) {
			echo '<select name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" style="width:100%;max-width:320px;">';
			foreach ( $options as $opt_value => $opt_label ) {
				echo '<option value="' . esc_attr( $opt_value ) . '" ' . selected( $value, $opt_value, false ) . '>' . esc_html( $opt_label ) . '</option>';
			}
			echo '</select>';
		} elseif ( 'textarea' === $type ) {
			echo '<textarea name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" rows="3" style="width:100%;max-width:480px;">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input type="' . esc_attr( $type ) . '" step="any" name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '" style="width:100%;max-width:320px;" />';
		}
		if ( $desc ) {
			echo '<br/><span class="description">' . esc_html( $desc ) . '</span>';
		}
		echo '</p>';
	}

	public function render_hotspot_box( $post ) {
		wp_nonce_field( 'somca_save_hotspot', 'somca_hotspot_nonce' );
		$lat    = get_post_meta( $post->ID, 'somca_lat', true );
		$lng    = get_post_meta( $post->ID, 'somca_lng', true );
		$risk   = get_post_meta( $post->ID, 'somca_risk_level', true ) ?: 'moderate';
		$status = get_post_meta( $post->ID, 'somca_status', true ) ?: 'monitoring';
		$pop    = get_post_meta( $post->ID, 'somca_population_affected', true );

		$this->field_row( __( 'Latitude', 'somca-core' ), 'somca_lat', $lat, 'text', array(), __( 'e.g. 9.5624 (used to auto-pull climate data & plot the map marker)', 'somca-core' ) );
		$this->field_row( __( 'Longitude', 'somca-core' ), 'somca_lng', $lng, 'text', array(), __( 'e.g. 44.0770', 'somca-core' ) );
		$this->field_row( __( 'Risk Level', 'somca-core' ), 'somca_risk_level', $risk, 'select', array(
			'low' => __( 'Low', 'somca-core' ), 'moderate' => __( 'Moderate', 'somca-core' ), 'high' => __( 'High', 'somca-core' ), 'critical' => __( 'Critical', 'somca-core' ),
		) );
		$this->field_row( __( 'Status', 'somca-core' ), 'somca_status', $status, 'select', array(
			'monitoring' => __( 'Monitoring', 'somca-core' ), 'watch' => __( 'Watch', 'somca-core' ), 'warning' => __( 'Warning', 'somca-core' ), 'critical' => __( 'Critical', 'somca-core' ),
		) );
		$this->field_row( __( 'Est. Population Affected', 'somca-core' ), 'somca_population_affected', $pop, 'number' );
	}

	public function save_hotspot( $post_id ) {
		if ( ! isset( $_POST['somca_hotspot_nonce'] ) || ! wp_verify_nonce( $_POST['somca_hotspot_nonce'], 'somca_save_hotspot' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( array( 'somca_lat', 'somca_lng', 'somca_risk_level', 'somca_status', 'somca_population_affected' ) as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
	}

	public function render_report_box( $post ) {
		wp_nonce_field( 'somca_save_report', 'somca_report_nonce' );
		$start   = get_post_meta( $post->ID, 'somca_period_start', true );
		$end     = get_post_meta( $post->ID, 'somca_period_end', true );
		$temp    = get_post_meta( $post->ID, 'somca_avg_temp_c', true );
		$anomaly = get_post_meta( $post->ID, 'somca_temp_anomaly_c', true );
		$rain    = get_post_meta( $post->ID, 'somca_rainfall_mm', true );
		$rainA   = get_post_meta( $post->ID, 'somca_rainfall_anomaly_pct', true );
		$source  = get_post_meta( $post->ID, 'somca_data_source', true ) ?: 'Open-Meteo';
		$related = get_post_meta( $post->ID, 'somca_related_hotspot', true );

		$this->field_row( __( 'Period Start', 'somca-core' ), 'somca_period_start', $start, 'date' );
		$this->field_row( __( 'Period End', 'somca-core' ), 'somca_period_end', $end, 'date' );
		$this->field_row( __( 'Average Temperature (°C)', 'somca-core' ), 'somca_avg_temp_c', $temp, 'number' );
		$this->field_row( __( 'Temperature Anomaly (°C vs. historical avg)', 'somca-core' ), 'somca_temp_anomaly_c', $anomaly, 'number' );
		$this->field_row( __( 'Rainfall (mm)', 'somca-core' ), 'somca_rainfall_mm', $rain, 'number' );
		$this->field_row( __( 'Rainfall Anomaly (%)', 'somca-core' ), 'somca_rainfall_anomaly_pct', $rainA, 'number' );
		$this->field_row( __( 'Data Source', 'somca-core' ), 'somca_data_source', $source, 'text' );
		$this->field_row( __( 'Related Hotspot (Post ID)', 'somca-core' ), 'somca_related_hotspot', $related, 'number' );
	}

	public function save_report( $post_id ) {
		if ( ! isset( $_POST['somca_report_nonce'] ) || ! wp_verify_nonce( $_POST['somca_report_nonce'], 'somca_save_report' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( array( 'somca_period_start', 'somca_period_end', 'somca_avg_temp_c', 'somca_temp_anomaly_c', 'somca_rainfall_mm', 'somca_rainfall_anomaly_pct', 'somca_data_source', 'somca_related_hotspot' ) as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
	}

	public function render_alert_box( $post ) {
		wp_nonce_field( 'somca_save_alert', 'somca_alert_nonce' );
		$severity = get_post_meta( $post->ID, 'somca_severity', true ) ?: 'advisory';
		$issued   = get_post_meta( $post->ID, 'somca_issued_date', true ) ?: gmdate( 'Y-m-d' );
		$expires  = get_post_meta( $post->ID, 'somca_expires_date', true );
		$actions  = get_post_meta( $post->ID, 'somca_recommended_actions', true );
		$related  = get_post_meta( $post->ID, 'somca_related_hotspot', true );

		$this->field_row( __( 'Severity', 'somca-core' ), 'somca_severity', $severity, 'select', array(
			'advisory' => __( 'Advisory', 'somca-core' ), 'watch' => __( 'Watch', 'somca-core' ), 'warning' => __( 'Warning', 'somca-core' ), 'emergency' => __( 'Emergency', 'somca-core' ),
		) );
		$this->field_row( __( 'Issued Date', 'somca-core' ), 'somca_issued_date', $issued, 'date' );
		$this->field_row( __( 'Expires Date', 'somca-core' ), 'somca_expires_date', $expires, 'date' );
		$this->field_row( __( 'Recommended Actions', 'somca-core' ), 'somca_recommended_actions', $actions, 'textarea' );
		$this->field_row( __( 'Related Hotspot (Post ID)', 'somca-core' ), 'somca_related_hotspot', $related, 'number' );
	}

	public function save_alert( $post_id ) {
		if ( ! isset( $_POST['somca_alert_nonce'] ) || ! wp_verify_nonce( $_POST['somca_alert_nonce'], 'somca_save_alert' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( array( 'somca_severity', 'somca_issued_date', 'somca_expires_date', 'somca_recommended_actions', 'somca_related_hotspot' ) as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
	}
}
