<?php
/**
 * Single Alert: severity, timing, recommended actions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$id       = get_the_ID();
	$severity = get_post_meta( $id, 'somca_severity', true ) ?: 'advisory';
	$issued   = get_post_meta( $id, 'somca_issued_date', true );
	$expires  = get_post_meta( $id, 'somca_expires_date', true );
	$actions  = get_post_meta( $id, 'somca_recommended_actions', true );
	$hotspot  = get_post_meta( $id, 'somca_related_hotspot', true );
	?>
	<div class="somca-container somca-section">
		<div class="somca-layout">
			<main id="primary" class="somca-main">
				<?php somca_breadcrumb( array(
					array( 'label' => __( 'Home', 'somca' ), 'url' => home_url( '/' ) ),
					array( 'label' => __( 'Alerts', 'somca' ), 'url' => get_post_type_archive_link( 'somca_alert' ) ),
					array( 'label' => get_the_title() ),
				) ); ?>

				<header class="somca-entry-header">
					<?php somca_severity_badge( $severity ); ?>
					<h1><?php the_title(); ?></h1>
					<div class="somca-card-meta">
						<?php if ( $issued ) : ?><?php esc_html_e( 'Issued:', 'somca' ); ?> <?php echo esc_html( $issued ); ?><?php endif; ?>
						<?php if ( $expires ) : ?> &middot; <?php esc_html_e( 'Expires:', 'somca' ); ?> <?php echo esc_html( $expires ); ?><?php endif; ?>
						<?php if ( $hotspot ) : ?> &middot; <a href="<?php echo esc_url( get_permalink( $hotspot ) ); ?>"><?php echo esc_html( get_the_title( $hotspot ) ); ?></a><?php endif; ?>
					</div>
				</header>

				<div class="somca-entry-content">
					<?php the_content(); ?>
				</div>

				<?php if ( $actions ) : ?>
					<div class="somca-metric-tile" style="text-align:left; margin-top:2em;">
						<h3><?php esc_html_e( 'Recommended Actions', 'somca' ); ?></h3>
						<p style="color:var(--somca-text-muted); font-weight:400;"><?php echo esc_html( $actions ); ?></p>
					</div>
				<?php endif; ?>
			</main>
			<?php get_sidebar(); ?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
