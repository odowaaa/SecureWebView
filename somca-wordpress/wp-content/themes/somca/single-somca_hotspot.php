<?php
/**
 * Single Hotspot: detail page with focused map, risk status, related reports & alerts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$id       = get_the_ID();
	$lat      = get_post_meta( $id, 'somca_lat', true );
	$lng      = get_post_meta( $id, 'somca_lng', true );
	$risk     = get_post_meta( $id, 'somca_risk_level', true ) ?: 'moderate';
	$status   = get_post_meta( $id, 'somca_status', true ) ?: 'monitoring';
	$pop      = get_post_meta( $id, 'somca_population_affected', true );
	$regions  = get_the_terms( $id, 'somca_region' );
	$hazards  = get_the_terms( $id, 'somca_hazard' );
	?>
	<div class="somca-container somca-section">
		<div class="somca-layout">
			<main id="primary" class="somca-main">
				<?php somca_breadcrumb( array(
					array( 'label' => __( 'Home', 'somca' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Hotspots', 'somca' ), 'url' => get_post_type_archive_link( 'somca_hotspot' ) ),
					array( 'label' => get_the_title() ),
				) ); ?>

				<header class="somca-entry-header">
					<?php somca_risk_badge( $risk ); ?>
					<h1><?php the_title(); ?></h1>
					<div class="somca-card-meta">
						<?php esc_html_e( 'Status:', 'somca' ); ?> <?php echo esc_html( somca_status_label( $status ) ); ?>
						<?php if ( $regions && ! is_wp_error( $regions ) ) : ?>
							&middot; <?php echo esc_html( implode( ', ', wp_list_pluck( $regions, 'name' ) ) ); ?>
						<?php endif; ?>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="somca-entry-thumb"><?php the_post_thumbnail( 'somca-hero' ); ?></div>
				<?php endif; ?>

				<?php if ( $lat && $lng ) : ?>
					<div id="somca-hotspot-single-map" class="somca-hotspot-map" style="margin: 1.5em 0;" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lng ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>"></div>
				<?php endif; ?>

				<div class="somca-metrics-row">
					<?php somca_metric_tile( __( 'Risk Level', 'somca' ), ucfirst( $risk ) ); ?>
					<?php somca_metric_tile( __( 'Status', 'somca' ), somca_status_label( $status ) ); ?>
					<?php if ( $pop ) : somca_metric_tile( __( 'Est. Population Affected', 'somca' ), number_format_i18n( $pop ) ); endif; ?>
					<?php if ( $hazards && ! is_wp_error( $hazards ) ) : somca_metric_tile( __( 'Hazard Types', 'somca' ), implode( ', ', wp_list_pluck( $hazards, 'name' ) ) ); endif; ?>
				</div>

				<div class="somca-entry-content">
					<?php the_content(); ?>
				</div>

				<h2><?php esc_html_e( 'Recent Climate Reports', 'somca' ); ?></h2>
				<?php echo do_shortcode( '[somca_chart hotspot="' . absint( $id ) . '" period="weekly"]' ); ?>
				<?php
				$reports = get_posts( array(
					'post_type'      => 'somca_report',
					'post_status'    => 'publish',
					'posts_per_page' => 6,
					'meta_query'     => array( array( 'key' => 'somca_related_hotspot', 'value' => $id ) ),
				) );
				if ( $reports ) :
					?>
					<div class="somca-card-grid">
						<?php foreach ( $reports as $r ) : ?>
							<article class="somca-card">
								<div class="somca-card-body">
									<div class="somca-card-meta"><?php echo esc_html( implode( ', ', wp_get_post_terms( $r->ID, 'somca_period', array( 'fields' => 'names' ) ) ) ); ?></div>
									<h3 class="somca-card-title"><a href="<?php echo esc_url( get_permalink( $r ) ); ?>"><?php echo esc_html( get_the_title( $r ) ); ?></a></h3>
									<div class="somca-card-stats">
										<span><?php echo esc_html( get_post_meta( $r->ID, 'somca_avg_temp_c', true ) ); ?>&deg;C</span>
										<span><?php echo esc_html( get_post_meta( $r->ID, 'somca_rainfall_mm', true ) ); ?>mm</span>
									</div>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p><?php esc_html_e( 'No reports yet — the first automated weekly report will appear here once collected.', 'somca' ); ?></p>
				<?php endif; ?>
			</main>
			<?php get_sidebar(); ?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
