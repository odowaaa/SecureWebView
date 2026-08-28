<?php
/**
 * Single Climate Report: key metrics + trend chart + narrative content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$id       = get_the_ID();
	$hotspot  = get_post_meta( $id, 'somca_related_hotspot', true );
	$periods  = get_the_terms( $id, 'somca_period' );
	?>
	<div class="somca-container somca-section">
		<div class="somca-layout">
			<main id="primary" class="somca-main">
				<?php somca_breadcrumb( array(
					array( 'label' => __( 'Home', 'somca' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Reports', 'somca' ), 'url' => get_post_type_archive_link( 'somca_report' ) ),
					array( 'label' => get_the_title() ),
				) ); ?>

				<header class="somca-entry-header">
					<?php if ( $periods && ! is_wp_error( $periods ) ) : ?>
						<span class="somca-risk-badge somca-risk-moderate"><?php echo esc_html( implode( ', ', wp_list_pluck( $periods, 'name' ) ) ); ?></span>
					<?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<div class="somca-card-meta">
						<?php echo esc_html( get_post_meta( $id, 'somca_period_start', true ) ); ?> &rarr; <?php echo esc_html( get_post_meta( $id, 'somca_period_end', true ) ); ?>
						<?php if ( $hotspot ) : ?>
							&middot; <a href="<?php echo esc_url( get_permalink( $hotspot ) ); ?>"><?php echo esc_html( get_the_title( $hotspot ) ); ?></a>
						<?php endif; ?>
					</div>
				</header>

				<div class="somca-metrics-row">
					<?php somca_metric_tile( __( 'Avg. Temperature', 'somca' ), get_post_meta( $id, 'somca_avg_temp_c', true ), '°C' ); ?>
					<?php somca_metric_tile( __( 'Temp. Anomaly', 'somca' ), get_post_meta( $id, 'somca_temp_anomaly_c', true ), '°C' ); ?>
					<?php somca_metric_tile( __( 'Rainfall', 'somca' ), get_post_meta( $id, 'somca_rainfall_mm', true ), 'mm' ); ?>
					<?php somca_metric_tile( __( 'Rainfall Anomaly', 'somca' ), get_post_meta( $id, 'somca_rainfall_anomaly_pct', true ), '%' ); ?>
				</div>

				<?php if ( $hotspot ) : ?>
					<?php echo do_shortcode( '[somca_chart hotspot="' . absint( $hotspot ) . '"]' ); ?>
				<?php endif; ?>

				<div class="somca-entry-content">
					<?php the_content(); ?>
				</div>

				<p class="somca-card-meta"><?php printf( esc_html__( 'Source: %s', 'somca' ), esc_html( get_post_meta( $id, 'somca_data_source', true ) ?: 'Open-Meteo' ) ); ?></p>
			</main>
			<?php get_sidebar(); ?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
