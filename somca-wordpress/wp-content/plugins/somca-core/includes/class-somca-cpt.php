<?php
/**
 * Custom Post Types & Taxonomies for SomCA Core.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_CPT {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Post types:
	 * - somca_hotspot : a monitored climate hotspot / location
	 * - somca_report  : a periodic (weekly/monthly/seasonal/yearly/biyearly) data update
	 * - somca_alert   : an active warning/advisory
	 */
	public function register_post_types() {

		register_post_type( 'somca_hotspot', array(
			'labels'        => array(
				'name'               => __( 'Hotspots', 'somca-core' ),
				'singular_name'      => __( 'Hotspot', 'somca-core' ),
				'add_new_item'       => __( 'Add New Hotspot', 'somca-core' ),
				'edit_item'          => __( 'Edit Hotspot', 'somca-core' ),
				'all_items'          => __( 'Hotspots', 'somca-core' ),
				'menu_name'          => __( 'Hotspots', 'somca-core' ),
			),
			'public'        => true,
			'menu_icon'     => 'dashicons-location-alt',
			'menu_position' => 21,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'hotspots' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'show_in_rest'  => true,
			'rest_base'     => 'somca-hotspots',
		) );

		register_post_type( 'somca_report', array(
			'labels'        => array(
				'name'               => __( 'Climate Reports', 'somca-core' ),
				'singular_name'      => __( 'Climate Report', 'somca-core' ),
				'add_new_item'       => __( 'Add New Report', 'somca-core' ),
				'edit_item'          => __( 'Edit Report', 'somca-core' ),
				'all_items'          => __( 'Reports', 'somca-core' ),
				'menu_name'          => __( 'Reports', 'somca-core' ),
			),
			'public'        => true,
			'menu_icon'     => 'dashicons-chart-line',
			'menu_position' => 22,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'reports' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'show_in_rest'  => true,
			'rest_base'     => 'somca-reports',
		) );

		register_post_type( 'somca_alert', array(
			'labels'        => array(
				'name'               => __( 'Alerts', 'somca-core' ),
				'singular_name'      => __( 'Alert', 'somca-core' ),
				'add_new_item'       => __( 'Add New Alert', 'somca-core' ),
				'edit_item'          => __( 'Edit Alert', 'somca-core' ),
				'all_items'          => __( 'Alerts', 'somca-core' ),
				'menu_name'          => __( 'Alerts', 'somca-core' ),
			),
			'public'        => true,
			'menu_icon'     => 'dashicons-warning',
			'menu_position' => 23,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'alerts' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'show_in_rest'  => true,
			'rest_base'     => 'somca-alerts',
		) );
	}

	/**
	 * Taxonomies:
	 * - somca_region  : hierarchical (Somaliland, Puntland, Banaadir, Jubaland, Galmudug, Hirshabelle, South West, Global)
	 * - somca_hazard  : flat (drought, flood, cyclone, heatwave, sea-level rise, desertification, locust, deforestation)
	 * - somca_period  : flat (weekly, monthly, seasonal, yearly, biyearly)
	 */
	public function register_taxonomies() {

		register_taxonomy( 'somca_region', array( 'somca_hotspot', 'somca_report', 'somca_alert' ), array(
			'labels'            => array(
				'name'          => __( 'Regions', 'somca-core' ),
				'singular_name' => __( 'Region', 'somca-core' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'region' ),
		) );

		register_taxonomy( 'somca_hazard', array( 'somca_hotspot', 'somca_report', 'somca_alert' ), array(
			'labels'            => array(
				'name'          => __( 'Hazard Types', 'somca-core' ),
				'singular_name' => __( 'Hazard Type', 'somca-core' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'hazard' ),
		) );

		register_taxonomy( 'somca_period', array( 'somca_report' ), array(
			'labels'            => array(
				'name'          => __( 'Report Periods', 'somca-core' ),
				'singular_name' => __( 'Report Period', 'somca-core' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'period' ),
		) );
	}

	/**
	 * Default terms created on activation-adjacent first load, kept idempotent.
	 */
	public static function default_regions() {
		return array( 'Somaliland', 'Puntland', 'Banaadir (Mogadishu)', 'Jubaland', 'Galmudug', 'Hirshabelle', 'South West State', 'Global / Regional' );
	}

	public static function default_hazards() {
		return array( 'Drought', 'Flood', 'Cyclone', 'Heatwave', 'Sea-Level Rise', 'Desertification', 'Locust Outbreak', 'Deforestation' );
	}

	public static function default_periods() {
		return array(
			'weekly'   => 'Weekly',
			'monthly'  => 'Monthly',
			'seasonal' => 'Seasonal',
			'yearly'   => 'Yearly',
			'biyearly' => 'Bi-Yearly',
		);
	}
}
