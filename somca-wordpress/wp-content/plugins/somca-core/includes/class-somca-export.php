<?php
/**
 * Public CSV export of a Hotspot's climate report history.
 *
 * New in 2.0.0. No login required — the underlying data is already public
 * (published reports), this just offers it as a downloadable CSV.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Export {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_post_somca_export_reports', array( $this, 'export_reports_csv' ) );
		add_action( 'admin_post_nopriv_somca_export_reports', array( $this, 'export_reports_csv' ) );
	}

	public function export_reports_csv() {
		$hotspot_id = isset( $_GET['hotspot_id'] ) ? absint( $_GET['hotspot_id'] ) : 0;

		if ( ! $hotspot_id || 'somca_hotspot' !== get_post_type( $hotspot_id ) || 'publish' !== get_post_status( $hotspot_id ) ) {
			wp_die( esc_html__( 'Unknown hotspot.', 'somca-core' ) );
		}

		$reports = get_posts( array(
			'post_type'      => 'somca_report',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'meta_value',
			'meta_key'       => 'somca_period_end',
			'order'          => 'ASC',
			'meta_query'     => array( array( 'key' => 'somca_related_hotspot', 'value' => $hotspot_id ) ),
		) );

		$filename = sanitize_title( get_the_title( $hotspot_id ) ) . '-climate-reports-' . gmdate( 'Y-m-d' ) . '.csv';

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );

		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array( 'Report', 'Period', 'Period Start', 'Period End', 'Avg Temp (C)', 'Temp Anomaly (C)', 'Rainfall (mm)', 'Rainfall Anomaly (%)', 'Source' ) );

		foreach ( $reports as $r ) {
			fputcsv( $out, array(
				get_the_title( $r ),
				implode( '/', wp_get_post_terms( $r->ID, 'somca_period', array( 'fields' => 'names' ) ) ),
				get_post_meta( $r->ID, 'somca_period_start', true ),
				get_post_meta( $r->ID, 'somca_period_end', true ),
				get_post_meta( $r->ID, 'somca_avg_temp_c', true ),
				get_post_meta( $r->ID, 'somca_temp_anomaly_c', true ),
				get_post_meta( $r->ID, 'somca_rainfall_mm', true ),
				get_post_meta( $r->ID, 'somca_rainfall_anomaly_pct', true ),
				get_post_meta( $r->ID, 'somca_data_source', true ),
			) );
		}

		fclose( $out );
		exit;
	}
}
