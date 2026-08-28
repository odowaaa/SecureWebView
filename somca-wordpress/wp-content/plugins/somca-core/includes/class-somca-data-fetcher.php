<?php
/**
 * Automated climate data collection.
 *
 * Pulls live + historical-average weather data per Hotspot from the free,
 * key-less Open-Meteo API (https://open-meteo.com), computes anomalies
 * against a 1991-2020 climatology, and publishes a "somca_report" post
 * (tagged weekly/monthly/seasonal/yearly/biyearly). When a threshold is
 * crossed it also drafts a "somca_alert" for editorial review.
 *
 * No API key required, so the site works out of the box. Site owners can
 * later swap SOMCA_DATA_PROVIDER for NOAA/NASA POWER/etc. via the filter
 * `somca_climate_data_provider`.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Data_Fetcher {

	private static $instance = null;

	const HOOK_WEEKLY   = 'somca_fetch_weekly';
	const HOOK_MONTHLY  = 'somca_fetch_monthly';
	const HOOK_SEASONAL = 'somca_fetch_seasonal';
	const HOOK_YEARLY   = 'somca_fetch_yearly';
	const HOOK_BIYEARLY = 'somca_fetch_biyearly';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );

		add_action( self::HOOK_WEEKLY, array( $this, 'run_weekly' ) );
		add_action( self::HOOK_MONTHLY, array( $this, 'run_monthly' ) );
		add_action( self::HOOK_SEASONAL, array( $this, 'run_seasonal' ) );
		add_action( self::HOOK_YEARLY, array( $this, 'run_yearly' ) );
		add_action( self::HOOK_BIYEARLY, array( $this, 'run_biyearly' ) );

		add_action( 'wp_ajax_somca_run_fetch_now', array( $this, 'ajax_run_now' ) );
	}

	public static function activate() {
		if ( ! wp_next_scheduled( self::HOOK_WEEKLY ) ) {
			wp_schedule_event( time() + 300, 'weekly', self::HOOK_WEEKLY );
		}
		if ( ! wp_next_scheduled( self::HOOK_MONTHLY ) ) {
			wp_schedule_event( time() + 600, 'monthly', self::HOOK_MONTHLY );
		}
		if ( ! wp_next_scheduled( self::HOOK_SEASONAL ) ) {
			wp_schedule_event( time() + 900, 'quarterly', self::HOOK_SEASONAL );
		}
		if ( ! wp_next_scheduled( self::HOOK_YEARLY ) ) {
			wp_schedule_event( time() + 1200, 'yearly', self::HOOK_YEARLY );
		}
		if ( ! wp_next_scheduled( self::HOOK_BIYEARLY ) ) {
			wp_schedule_event( time() + 1500, 'biyearly', self::HOOK_BIYEARLY );
		}
	}

	public static function deactivate() {
		foreach ( array( self::HOOK_WEEKLY, self::HOOK_MONTHLY, self::HOOK_SEASONAL, self::HOOK_YEARLY, self::HOOK_BIYEARLY ) as $hook ) {
			$timestamp = wp_next_scheduled( $hook );
			if ( $timestamp ) {
				wp_unschedule_event( $timestamp, $hook );
			}
		}
	}

	public function add_schedules( $schedules ) {
		$schedules['weekly']    = array( 'interval' => WEEK_IN_SECONDS, 'display' => __( 'Once Weekly', 'somca-core' ) );
		$schedules['monthly']   = array( 'interval' => 30 * DAY_IN_SECONDS, 'display' => __( 'Once Monthly', 'somca-core' ) );
		$schedules['quarterly'] = array( 'interval' => 91 * DAY_IN_SECONDS, 'display' => __( 'Once Seasonally (Quarterly)', 'somca-core' ) );
		$schedules['yearly']    = array( 'interval' => 365 * DAY_IN_SECONDS, 'display' => __( 'Once Yearly', 'somca-core' ) );
		$schedules['biyearly']  = array( 'interval' => 2 * 365 * DAY_IN_SECONDS, 'display' => __( 'Once Every 2 Years', 'somca-core' ) );
		return $schedules;
	}

	public function run_weekly() {
		$this->run_for_period( 'weekly', 7 );
	}
	public function run_monthly() {
		$this->run_for_period( 'monthly', 30 );
	}
	public function run_seasonal() {
		$this->run_for_period( 'seasonal', 91 );
	}
	public function run_yearly() {
		$this->run_for_period( 'yearly', 365 );
	}
	public function run_biyearly() {
		$this->run_for_period( 'biyearly', 730 );
	}

	public function ajax_run_now() {
		check_ajax_referer( 'somca_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Not allowed', 'somca-core' ) ) );
		}
		$period = isset( $_POST['period'] ) ? sanitize_key( wp_unslash( $_POST['period'] ) ) : 'weekly';
		$days   = array( 'weekly' => 7, 'monthly' => 30, 'seasonal' => 91, 'yearly' => 365, 'biyearly' => 730 );
		$count  = $this->run_for_period( $period, isset( $days[ $period ] ) ? $days[ $period ] : 7 );
		wp_send_json_success( array( 'message' => sprintf( __( 'Fetched data for %d hotspot(s).', 'somca-core' ), $count ) ) );
	}

	/**
	 * Core routine: for every published hotspot with coordinates, pull data
	 * and generate a report (+ alert draft if thresholds are crossed).
	 */
	private function run_for_period( $period_slug, $days ) {
		$hotspots = get_posts( array(
			'post_type'      => 'somca_hotspot',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		) );

		$processed = 0;

		foreach ( $hotspots as $hotspot_id ) {
			$lat = get_post_meta( $hotspot_id, 'somca_lat', true );
			$lng = get_post_meta( $hotspot_id, 'somca_lng', true );

			if ( '' === $lat || '' === $lng ) {
				continue;
			}

			$data = $this->fetch_climate_data( (float) $lat, (float) $lng, $days );

			if ( is_wp_error( $data ) || empty( $data ) ) {
				continue;
			}

			$report_id = $this->create_report( $hotspot_id, $period_slug, $data );
			$this->maybe_create_alert( $hotspot_id, $data );

			if ( $report_id ) {
				$processed++;
			}
		}

		update_option( 'somca_last_fetch_' . $period_slug, current_time( 'mysql' ) );

		return $processed;
	}

	/**
	 * Fetch recent daily data + long-term climatology from Open-Meteo and
	 * compute simple anomalies. No API key required.
	 */
	private function fetch_climate_data( $lat, $lng, $days ) {
		$provider = apply_filters( 'somca_climate_data_provider', 'open-meteo' );

		if ( 'open-meteo' !== $provider ) {
			return apply_filters( 'somca_fetch_climate_data_custom', new WP_Error( 'no_provider', 'No custom provider hooked.' ), $lat, $lng, $days );
		}

		$end   = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
		$start = ( clone $end )->modify( '-' . max( 1, min( $days, 92 ) ) . ' days' );

		$recent_url = add_query_arg( array(
			'latitude'   => $lat,
			'longitude'  => $lng,
			'start_date' => $start->format( 'Y-m-d' ),
			'end_date'   => $end->format( 'Y-m-d' ),
			'daily'      => 'temperature_2m_mean,precipitation_sum',
			'timezone'   => 'UTC',
		), 'https://archive-api.open-meteo.com/v1/archive' );

		$response = wp_remote_get( $recent_url, array( 'timeout' => 20 ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $body['daily']['temperature_2m_mean'] ) ) {
			return new WP_Error( 'somca_no_data', __( 'No climate data returned.', 'somca-core' ) );
		}

		$temps = array_filter( $body['daily']['temperature_2m_mean'], function( $v ) { return null !== $v; } );
		$rain  = array_filter( $body['daily']['precipitation_sum'], function( $v ) { return null !== $v; } );

		$avg_temp  = $temps ? array_sum( $temps ) / count( $temps ) : null;
		$total_rain = $rain ? array_sum( $rain ) : null;

		// Long-term climatology baseline (30-year normal) for the same window a year-independent way: reuse Open-Meteo climate API.
		$clim_url = add_query_arg( array(
			'latitude'   => $lat,
			'longitude'  => $lng,
			'start_date' => '1991-01-01',
			'end_date'   => '2020-12-31',
			'models'     => 'MRI_AGCM3_2_S',
			'daily'      => 'temperature_2m_mean,precipitation_sum',
		), 'https://climate-api.open-meteo.com/v1/climate' );

		$clim_response = wp_remote_get( $clim_url, array( 'timeout' => 20 ) );
		$avg_temp_norm  = null;
		$avg_rain_norm  = null;

		if ( ! is_wp_error( $clim_response ) ) {
			$clim_body = json_decode( wp_remote_retrieve_body( $clim_response ), true );
			if ( ! empty( $clim_body['daily']['temperature_2m_mean'] ) ) {
				$clim_temps = array_filter( $clim_body['daily']['temperature_2m_mean'], function( $v ) { return null !== $v; } );
				$clim_rain  = array_filter( $clim_body['daily']['precipitation_sum'], function( $v ) { return null !== $v; } );
				if ( $clim_temps ) {
					$avg_temp_norm = array_sum( $clim_temps ) / count( $clim_temps );
				}
				if ( $clim_rain ) {
					// Scale the 30-year daily average rainfall to the same window length as the recent sample.
					$avg_rain_norm = ( array_sum( $clim_rain ) / count( $clim_rain ) ) * count( $rain );
				}
			}
		}

		$temp_anomaly = ( null !== $avg_temp && null !== $avg_temp_norm ) ? round( $avg_temp - $avg_temp_norm, 2 ) : null;
		$rain_anomaly_pct = ( $total_rain !== null && $avg_rain_norm ) ? round( ( ( $total_rain - $avg_rain_norm ) / max( $avg_rain_norm, 0.01 ) ) * 100, 1 ) : null;

		return array(
			'start'             => $start->format( 'Y-m-d' ),
			'end'               => $end->format( 'Y-m-d' ),
			'avg_temp_c'        => null !== $avg_temp ? round( $avg_temp, 2 ) : '',
			'temp_anomaly_c'    => null !== $temp_anomaly ? $temp_anomaly : '',
			'rainfall_mm'       => null !== $total_rain ? round( $total_rain, 1 ) : '',
			'rainfall_anomaly_pct' => null !== $rain_anomaly_pct ? $rain_anomaly_pct : '',
			'source'            => 'Open-Meteo (ERA5 reanalysis + 1991-2020 climatology)',
		);
	}

	private function create_report( $hotspot_id, $period_slug, $data ) {
		$hotspot_title = get_the_title( $hotspot_id );
		$period_labels = SomCA_CPT::default_periods();
		$period_label  = isset( $period_labels[ $period_slug ] ) ? $period_labels[ $period_slug ] : ucfirst( $period_slug );

		$title = sprintf( '%s — %s Climate Update (%s)', $hotspot_title, $period_label, $data['end'] );

		$content  = '<p>' . sprintf( esc_html__( 'Automated %1$s climate summary for %2$s covering %3$s to %4$s.', 'somca-core' ), strtolower( $period_label ), esc_html( $hotspot_title ), esc_html( $data['start'] ), esc_html( $data['end'] ) ) . '</p>';
		$content .= '<ul>';
		$content .= '<li>' . sprintf( esc_html__( 'Average temperature: %s°C', 'somca-core' ), esc_html( $data['avg_temp_c'] ) ) . '</li>';
		if ( '' !== $data['temp_anomaly_c'] ) {
			$content .= '<li>' . sprintf( esc_html__( 'Temperature anomaly vs. 1991-2020 average: %s°C', 'somca-core' ), esc_html( $data['temp_anomaly_c'] ) ) . '</li>';
		}
		$content .= '<li>' . sprintf( esc_html__( 'Total rainfall: %s mm', 'somca-core' ), esc_html( $data['rainfall_mm'] ) ) . '</li>';
		if ( '' !== $data['rainfall_anomaly_pct'] ) {
			$content .= '<li>' . sprintf( esc_html__( 'Rainfall anomaly vs. normal: %s%%', 'somca-core' ), esc_html( $data['rainfall_anomaly_pct'] ) ) . '</li>';
		}
		$content .= '</ul>';
		$content .= '<p><em>' . sprintf( esc_html__( 'Source: %s', 'somca-core' ), esc_html( $data['source'] ) ) . '</em></p>';

		$post_id = wp_insert_post( array(
			'post_type'    => 'somca_report',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
		) );

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 0;
		}

		update_post_meta( $post_id, 'somca_period_start', $data['start'] );
		update_post_meta( $post_id, 'somca_period_end', $data['end'] );
		update_post_meta( $post_id, 'somca_avg_temp_c', $data['avg_temp_c'] );
		update_post_meta( $post_id, 'somca_temp_anomaly_c', $data['temp_anomaly_c'] );
		update_post_meta( $post_id, 'somca_rainfall_mm', $data['rainfall_mm'] );
		update_post_meta( $post_id, 'somca_rainfall_anomaly_pct', $data['rainfall_anomaly_pct'] );
		update_post_meta( $post_id, 'somca_data_source', $data['source'] );
		update_post_meta( $post_id, 'somca_related_hotspot', $hotspot_id );
		update_post_meta( $post_id, 'somca_auto_generated', '1' );

		wp_set_object_terms( $post_id, $period_slug, 'somca_period' );

		$regions = wp_get_post_terms( $hotspot_id, 'somca_region', array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $regions ) && $regions ) {
			wp_set_object_terms( $post_id, $regions, 'somca_region' );
		}
		$hazards = wp_get_post_terms( $hotspot_id, 'somca_hazard', array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $hazards ) && $hazards ) {
			wp_set_object_terms( $post_id, $hazards, 'somca_hazard' );
		}

		if ( has_post_thumbnail( $hotspot_id ) ) {
			set_post_thumbnail( $post_id, get_post_thumbnail_id( $hotspot_id ) );
		}

		return $post_id;
	}

	/**
	 * Threshold-based auto alerting. Thresholds are filterable so an admin
	 * can tune sensitivity from the settings page without touching code.
	 */
	private function maybe_create_alert( $hotspot_id, $data ) {
		$temp_threshold = (float) get_option( 'somca_alert_temp_anomaly_threshold', 1.5 );
		$rain_drought_threshold = (float) get_option( 'somca_alert_rain_drought_threshold', -40 );
		$rain_flood_threshold   = (float) get_option( 'somca_alert_rain_flood_threshold', 60 );

		$reasons  = array();
		$severity = 'advisory';

		if ( '' !== $data['temp_anomaly_c'] && (float) $data['temp_anomaly_c'] >= $temp_threshold ) {
			$reasons[] = sprintf( __( 'Temperature anomaly of +%s°C above the historical average.', 'somca-core' ), $data['temp_anomaly_c'] );
			$severity  = ( (float) $data['temp_anomaly_c'] >= $temp_threshold * 2 ) ? 'warning' : 'watch';
		}

		if ( '' !== $data['rainfall_anomaly_pct'] ) {
			$rain_anom = (float) $data['rainfall_anomaly_pct'];
			if ( $rain_anom <= $rain_drought_threshold ) {
				$reasons[] = sprintf( __( 'Rainfall %s%% below normal — drought risk.', 'somca-core' ), abs( $rain_anom ) );
				$severity  = 'warning';
			} elseif ( $rain_anom >= $rain_flood_threshold ) {
				$reasons[] = sprintf( __( 'Rainfall %s%% above normal — flood risk.', 'somca-core' ), $rain_anom );
				$severity  = 'warning';
			}
		}

		if ( empty( $reasons ) ) {
			return;
		}

		// Avoid duplicate alerts: skip if an active (non-expired) auto alert already exists for this hotspot in the last 7 days.
		$existing = get_posts( array(
			'post_type'      => 'somca_alert',
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => 1,
			'meta_query'     => array(
				array( 'key' => 'somca_related_hotspot', 'value' => $hotspot_id ),
			),
			'date_query'     => array( array( 'after' => '7 days ago' ) ),
		) );

		if ( ! empty( $existing ) ) {
			return;
		}

		$hotspot_title = get_the_title( $hotspot_id );
		$title = sprintf( __( 'Climate Alert: %s', 'somca-core' ), $hotspot_title );
		$content = '<p>' . esc_html__( 'Automated climate monitoring has flagged the following:', 'somca-core' ) . '</p><ul>';
		foreach ( $reasons as $reason ) {
			$content .= '<li>' . esc_html( $reason ) . '</li>';
		}
		$content .= '</ul><p>' . esc_html__( 'This alert was generated automatically and is pending review before publication.', 'somca-core' ) . '</p>';

		$alert_id = wp_insert_post( array(
			'post_type'    => 'somca_alert',
			'post_status'  => apply_filters( 'somca_auto_alert_status', 'draft' ),
			'post_title'   => $title,
			'post_content' => $content,
		) );

		if ( is_wp_error( $alert_id ) || ! $alert_id ) {
			return;
		}

		update_post_meta( $alert_id, 'somca_severity', $severity );
		update_post_meta( $alert_id, 'somca_issued_date', gmdate( 'Y-m-d' ) );
		update_post_meta( $alert_id, 'somca_expires_date', gmdate( 'Y-m-d', strtotime( '+14 days' ) ) );
		update_post_meta( $alert_id, 'somca_related_hotspot', $hotspot_id );

		$regions = wp_get_post_terms( $hotspot_id, 'somca_region', array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $regions ) && $regions ) {
			wp_set_object_terms( $alert_id, $regions, 'somca_region' );
		}

		do_action( 'somca_alert_created', $alert_id, $hotspot_id, $severity );
	}
}
