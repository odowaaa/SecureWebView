<?php
/**
 * Front page: hero, live alerts, hotspot map, latest reports, period overview.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$hotspot_count = wp_count_posts( 'somca_hotspot' )->publish;
$report_count  = wp_count_posts( 'somca_report' )->publish;
$active_alerts = somca_get_active_alerts( -1 );
$alert_count   = count( $active_alerts );
?>

<section class="somca-hero">
	<div class="somca-container somca-hero-inner">
		<div class="somca-hero-copy">
			<span class="somca-hero-eyebrow">● <?php esc_html_e( 'Live Climate Monitoring — Somalia & Worldwide', 'somca' ); ?></span>
			<h1><?php esc_html_e( 'Tracking every climate', 'somca' ); ?> <span class="somca-accent"><?php esc_html_e( 'hotspot', 'somca' ); ?></span> <?php esc_html_e( 'before it becomes a crisis.', 'somca' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'SomCA collects, visualises, and shares weekly, monthly, seasonal, yearly and bi-yearly climate data across Somalia and worldwide — flagging areas that need attention, precaution, and early warning.', 'somca' ); ?></p>
			<div class="somca-hero-actions">
				<a class="somca-btn somca-btn-primary" href="#somca-live-map"><?php esc_html_e( 'View Live Hotspot Map', 'somca' ); ?></a>
				<a class="somca-btn somca-btn-outline" style="color:#fff;" href="<?php echo esc_url( get_post_type_archive_link( 'somca_alert' ) ); ?>"><?php esc_html_e( 'See Active Alerts', 'somca' ); ?></a>
			</div>
			<div class="somca-hero-stats">
				<div class="somca-hero-stat"><strong><?php echo esc_html( $hotspot_count ); ?></strong><span><?php esc_html_e( 'Monitored Hotspots', 'somca' ); ?></span></div>
				<div class="somca-hero-stat"><strong><?php echo esc_html( $report_count ); ?></strong><span><?php esc_html_e( 'Climate Reports', 'somca' ); ?></span></div>
				<div class="somca-hero-stat"><strong><?php echo esc_html( $alert_count ); ?></strong><span><?php esc_html_e( 'Active Alerts', 'somca' ); ?></span></div>
			</div>
		</div>
		<div class="somca-hero-visual">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.jpg' ); ?>" alt="<?php esc_attr_e( 'SomCA — Somali Climate Action', 'somca' ); ?>" />
		</div>
	</div>
</section>

<?php if ( ! empty( $active_alerts ) ) : ?>
<section class="somca-section" aria-label="<?php esc_attr_e( 'Active climate alerts', 'somca' ); ?>">
	<div class="somca-container">
		<div class="somca-section-header">
			<div><span class="somca-eyebrow"><?php esc_html_e( 'Early Warning', 'somca' ); ?></span><h2><?php esc_html_e( 'Active Alerts', 'somca' ); ?></h2></div>
			<a class="somca-btn somca-btn-outline" href="<?php echo esc_url( get_post_type_archive_link( 'somca_alert' ) ); ?>"><?php esc_html_e( 'All Alerts', 'somca' ); ?></a>
		</div>
		<?php echo do_shortcode( '[somca_alerts limit="6"]' ); ?>
	</div>
</section>
<?php endif; ?>

<section class="somca-section somca-section-alt somca-map-section" id="somca-live-map">
	<div class="somca-container">
		<div class="somca-section-header">
			<div><span class="somca-eyebrow"><?php esc_html_e( 'Interactive', 'somca' ); ?></span><h2><?php esc_html_e( 'Live Climate Hotspot Map', 'somca' ); ?></h2>
			<p><?php esc_html_e( 'Every monitored location, colour-coded by risk level — drought, flood, cyclone, heatwave, sea-level rise, desertification and more.', 'somca' ); ?></p></div>
			<a class="somca-btn somca-btn-outline" href="<?php echo esc_url( get_post_type_archive_link( 'somca_hotspot' ) ); ?>"><?php esc_html_e( 'Browse All Hotspots', 'somca' ); ?></a>
		</div>
		<div id="somca-front-map" class="somca-hotspot-map" data-endpoint="<?php echo esc_url( rest_url( 'somca/v1/hotspots' ) ); ?>"></div>
		<div class="somca-map-legend">
			<span class="somca-legend-item"><i class="somca-dot somca-risk-low"></i><?php esc_html_e( 'Low', 'somca' ); ?></span>
			<span class="somca-legend-item"><i class="somca-dot somca-risk-moderate"></i><?php esc_html_e( 'Moderate', 'somca' ); ?></span>
			<span class="somca-legend-item"><i class="somca-dot somca-risk-high"></i><?php esc_html_e( 'High', 'somca' ); ?></span>
			<span class="somca-legend-item"><i class="somca-dot somca-risk-critical"></i><?php esc_html_e( 'Critical', 'somca' ); ?></span>
		</div>
	</div>
</section>

<section class="somca-section">
	<div class="somca-container">
		<div class="somca-section-header">
			<div><span class="somca-eyebrow"><?php esc_html_e( 'Data & Trends', 'somca' ); ?></span><h2><?php esc_html_e( 'Regional Temperature & Rainfall Trend', 'somca' ); ?></h2>
			<p><?php esc_html_e( 'Automatically updated from weekly climate data collection across all monitored hotspots.', 'somca' ); ?></p></div>
		</div>
		<div id="somca-front-chart-wrap" class="somca-chart-wrap">
			<canvas id="somca-front-chart" class="somca-chart" data-endpoint="<?php echo esc_url( add_query_arg( array( 'period' => 'weekly', 'limit' => 20 ), rest_url( 'somca/v1/reports' ) ) ); ?>" data-metric="temp"></canvas>
		</div>
	</div>
</section>

<section class="somca-section somca-section-alt">
	<div class="somca-container">
		<div class="somca-section-header">
			<div><span class="somca-eyebrow"><?php esc_html_e( 'Reporting Cadence', 'somca' ); ?></span><h2><?php esc_html_e( 'Weekly, Monthly, Seasonal, Yearly & Bi-Yearly Coverage', 'somca' ); ?></h2>
			<p><?php esc_html_e( 'SomCA automatically pulls fresh climate data on every cadence, so you always know what changed and when.', 'somca' ); ?></p></div>
		</div>
		<div class="somca-period-tabs">
			<?php
			$periods = array( 'weekly' => __( 'Weekly', 'somca' ), 'monthly' => __( 'Monthly', 'somca' ), 'seasonal' => __( 'Seasonal', 'somca' ), 'yearly' => __( 'Yearly', 'somca' ), 'biyearly' => __( 'Bi-Yearly', 'somca' ) );
			foreach ( $periods as $slug => $label ) :
				$term_link = get_term_link( $slug, 'somca_period' );
				?>
				<a href="<?php echo esc_url( ! is_wp_error( $term_link ) ? $term_link : '#' ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</div>
		<?php echo do_shortcode( '[somca_latest_reports count="6"]' ); ?>
	</div>
</section>

<section class="somca-section">
	<div class="somca-container">
		<div class="somca-section-header">
			<div><span class="somca-eyebrow"><?php esc_html_e( 'Monitored Locations', 'somca' ); ?></span><h2><?php esc_html_e( 'Featured Hotspots', 'somca' ); ?></h2></div>
			<a class="somca-btn somca-btn-outline" href="<?php echo esc_url( get_post_type_archive_link( 'somca_hotspot' ) ); ?>"><?php esc_html_e( 'View All', 'somca' ); ?></a>
		</div>
		<?php echo do_shortcode( '[somca_hotspots_grid count="6"]' ); ?>
	</div>
</section>

<section class="somca-section somca-section-alt">
	<div class="somca-container" style="max-width: 860px; text-align:center;">
		<span class="somca-eyebrow"><?php esc_html_e( 'Our Mission', 'somca' ); ?></span>
		<h2><?php esc_html_e( 'Advocating for climate action in Somalia and beyond', 'somca' ); ?></h2>
		<p style="color:var(--somca-text-muted); font-size:1.05rem;"><?php esc_html_e( 'Somali Climate Action (SomCA) exists to make climate risk visible — pairing continuous data collection with community advocacy so at-risk regions get the attention, precaution, and support they need before disaster strikes.', 'somca' ); ?></p>
		<a class="somca-btn somca-btn-primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ?: home_url( '/about/' ) ); ?>"><?php esc_html_e( 'Learn About Our Work', 'somca' ); ?></a>
	</div>
</section>

<?php get_footer(); ?>
