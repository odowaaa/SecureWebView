<?php
/**
 * Public REST endpoints consumed by the theme's map & chart JS.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_REST {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route( 'somca/v1', '/hotspots', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_hotspots' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'somca/v1', '/reports', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_reports' ),
			'permission_callback' => '__return_true',
			'args'                => array(
				'hotspot' => array( 'type' => 'integer' ),
				'period'  => array( 'type' => 'string' ),
				'region'  => array( 'type' => 'string' ),
				'limit'   => array( 'type' => 'integer', 'default' => 24 ),
			),
		) );

		register_rest_route( 'somca/v1', '/alerts', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_alerts' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'somca/v1', '/stats', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_stats' ),
			'permission_callback' => '__return_true',
		) );
	}

	/**
	 * Site-wide snapshot: counts + last automated fetch per cadence. Added in 2.0.0.
	 */
	public function get_stats( $request ) {
		$active_alerts = get_posts( array(
			'post_type'      => 'somca_alert',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				'relation' => 'OR',
				array( 'key' => 'somca_expires_date', 'value' => gmdate( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ),
				array( 'key' => 'somca_expires_date', 'compare' => 'NOT EXISTS' ),
			),
		) );

		return rest_ensure_response( array(
			'hotspots'      => (int) wp_count_posts( 'somca_hotspot' )->publish,
			'reports'       => (int) wp_count_posts( 'somca_report' )->publish,
			'alerts_total'  => (int) wp_count_posts( 'somca_alert' )->publish,
			'alerts_active' => count( $active_alerts ),
			'last_fetch'    => array(
				'weekly'   => get_option( 'somca_last_fetch_weekly', '' ),
				'monthly'  => get_option( 'somca_last_fetch_monthly', '' ),
				'seasonal' => get_option( 'somca_last_fetch_seasonal', '' ),
				'yearly'   => get_option( 'somca_last_fetch_yearly', '' ),
				'biyearly' => get_option( 'somca_last_fetch_biyearly', '' ),
			),
		) );
	}

	public function get_hotspots( $request ) {
		$posts = get_posts( array(
			'post_type'      => 'somca_hotspot',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		) );

		$out = array();
		foreach ( $posts as $p ) {
			$lat = get_post_meta( $p->ID, 'somca_lat', true );
			$lng = get_post_meta( $p->ID, 'somca_lng', true );
			if ( '' === $lat || '' === $lng ) {
				continue;
			}
			$out[] = array(
				'id'        => $p->ID,
				'title'     => get_the_title( $p ),
				'url'       => get_permalink( $p ),
				'excerpt'   => wp_strip_all_tags( get_the_excerpt( $p ) ),
				'lat'       => (float) $lat,
				'lng'       => (float) $lng,
				'risk'      => get_post_meta( $p->ID, 'somca_risk_level', true ) ?: 'moderate',
				'status'    => get_post_meta( $p->ID, 'somca_status', true ) ?: 'monitoring',
				'regions'   => wp_get_post_terms( $p->ID, 'somca_region', array( 'fields' => 'names' ) ),
				'hazards'   => wp_get_post_terms( $p->ID, 'somca_hazard', array( 'fields' => 'names' ) ),
				'thumbnail' => get_the_post_thumbnail_url( $p, 'medium' ),
			);
		}

		return rest_ensure_response( $out );
	}

	public function get_reports( $request ) {
		$args = array(
			'post_type'      => 'somca_report',
			'post_status'    => 'publish',
			'posts_per_page' => $request->get_param( 'limit' ) ? (int) $request->get_param( 'limit' ) : 24,
			'orderby'        => 'meta_value',
			'meta_key'       => 'somca_period_end',
			'order'          => 'ASC',
		);

		$tax_query = array();

		if ( $request->get_param( 'period' ) ) {
			$tax_query[] = array( 'taxonomy' => 'somca_period', 'field' => 'slug', 'terms' => sanitize_key( $request->get_param( 'period' ) ) );
		}
		if ( $request->get_param( 'region' ) ) {
			$tax_query[] = array( 'taxonomy' => 'somca_region', 'field' => 'slug', 'terms' => sanitize_key( $request->get_param( 'region' ) ) );
		}
		if ( $tax_query ) {
			$args['tax_query'] = $tax_query;
		}
		if ( $request->get_param( 'hotspot' ) ) {
			$args['meta_query'] = array( array( 'key' => 'somca_related_hotspot', 'value' => (int) $request->get_param( 'hotspot' ) ) );
		}

		$posts = get_posts( $args );
		$out   = array();

		foreach ( $posts as $p ) {
			$out[] = array(
				'id'                  => $p->ID,
				'title'               => get_the_title( $p ),
				'url'                 => get_permalink( $p ),
				'period_start'        => get_post_meta( $p->ID, 'somca_period_start', true ),
				'period_end'          => get_post_meta( $p->ID, 'somca_period_end', true ),
				'avg_temp_c'          => get_post_meta( $p->ID, 'somca_avg_temp_c', true ),
				'temp_anomaly_c'      => get_post_meta( $p->ID, 'somca_temp_anomaly_c', true ),
				'rainfall_mm'         => get_post_meta( $p->ID, 'somca_rainfall_mm', true ),
				'rainfall_anomaly_pct'=> get_post_meta( $p->ID, 'somca_rainfall_anomaly_pct', true ),
				'periods'             => wp_get_post_terms( $p->ID, 'somca_period', array( 'fields' => 'slugs' ) ),
			);
		}

		return rest_ensure_response( $out );
	}

	public function get_alerts( $request ) {
		$posts = get_posts( array(
			'post_type'      => 'somca_alert',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'OR',
				array( 'key' => 'somca_expires_date', 'value' => gmdate( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ),
				array( 'key' => 'somca_expires_date', 'compare' => 'NOT EXISTS' ),
			),
		) );

		$out = array();
		foreach ( $posts as $p ) {
			$out[] = array(
				'id'        => $p->ID,
				'title'     => get_the_title( $p ),
				'url'       => get_permalink( $p ),
				'excerpt'   => wp_strip_all_tags( get_the_excerpt( $p ) ),
				'severity'  => get_post_meta( $p->ID, 'somca_severity', true ) ?: 'advisory',
				'issued'    => get_post_meta( $p->ID, 'somca_issued_date', true ),
				'expires'   => get_post_meta( $p->ID, 'somca_expires_date', true ),
				'regions'   => wp_get_post_terms( $p->ID, 'somca_region', array( 'fields' => 'names' ) ),
			);
		}

		return rest_ensure_response( $out );
	}
}
