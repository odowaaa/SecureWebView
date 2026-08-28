<?php
/**
 * Reusable template helpers for the SomCA theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function somca_risk_badge( $risk ) {
	$risk = $risk ?: 'moderate';
	printf( '<span class="somca-risk-badge somca-risk-%1$s">%2$s</span>', esc_attr( $risk ), esc_html( ucfirst( $risk ) ) );
}

function somca_severity_badge( $severity ) {
	$severity = $severity ?: 'advisory';
	printf( '<span class="somca-alert-badge somca-severity-%1$s">%2$s</span>', esc_attr( $severity ), esc_html( ucfirst( $severity ) ) );
}

function somca_status_label( $status ) {
	$labels = array(
		'monitoring' => __( 'Monitoring', 'somca' ),
		'watch'      => __( 'Watch', 'somca' ),
		'warning'    => __( 'Warning', 'somca' ),
		'critical'   => __( 'Critical', 'somca' ),
	);
	return isset( $labels[ $status ] ) ? $labels[ $status ] : ucfirst( $status );
}

/**
 * Render a small metric tile used on report/hotspot templates.
 */
function somca_metric_tile( $label, $value, $suffix = '' ) {
	if ( '' === $value || null === $value ) {
		return;
	}
	printf(
		'<div class="somca-metric-tile"><span class="somca-metric-value">%1$s%2$s</span><span class="somca-metric-label">%3$s</span></div>',
		esc_html( $value ),
		esc_html( $suffix ),
		esc_html( $label )
	);
}

function somca_breadcrumb( $trail ) {
	echo '<nav class="somca-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'somca' ) . '">';
	$last = count( $trail ) - 1;
	foreach ( $trail as $i => $item ) {
		if ( $i > 0 ) {
			echo '<span class="sep">/</span>';
		}
		if ( $i === $last || empty( $item['url'] ) ) {
			echo '<span class="current">' . esc_html( $item['label'] ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		}
	}
	echo '</nav>';
}

function somca_get_active_alerts( $limit = -1 ) {
	return get_posts( array(
		'post_type'      => 'somca_alert',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'meta_query'     => array(
			'relation' => 'OR',
			array( 'key' => 'somca_expires_date', 'value' => gmdate( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ),
			array( 'key' => 'somca_expires_date', 'compare' => 'NOT EXISTS' ),
		),
	) );
}
