<?php
/**
 * Hotspot archive: full map + filterable grid of all monitored locations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="somca-container somca-section">
	<header class="somca-section-header">
		<div>
			<span class="somca-eyebrow"><?php esc_html_e( 'Monitored Locations', 'somca' ); ?></span>
			<h1><?php esc_html_e( 'Climate Hotspots', 'somca' ); ?></h1>
			<p><?php esc_html_e( 'Areas across Somalia and worldwide flagged for climate risk — drought, flood, cyclone, heatwave, desertification and more.', 'somca' ); ?></p>
		</div>
	</header>

	<?php echo do_shortcode( '[somca_map height="480px"]' ); ?>

	<?php
	$regions = get_terms( array( 'taxonomy' => 'somca_region', 'hide_empty' => true ) );
	if ( ! is_wp_error( $regions ) && $regions ) :
		?>
		<div class="somca-period-tabs">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'somca_hotspot' ) ); ?>" class="is-active"><?php esc_html_e( 'All Regions', 'somca' ); ?></a>
			<?php foreach ( $regions as $region ) : ?>
				<a href="<?php echo esc_url( get_term_link( $region ) ); ?>"><?php echo esc_html( $region->name ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php
	$hazards        = get_terms( array( 'taxonomy' => 'somca_hazard', 'hide_empty' => true ) );
	$selected_hazard = isset( $_GET['hazard'] ) ? sanitize_key( wp_unslash( $_GET['hazard'] ) ) : '';
	if ( ! is_wp_error( $hazards ) && $hazards ) :
		?>
		<form method="get" class="somca-hazard-filter" style="margin-bottom:1.5em;">
			<label for="somca-hazard-select"><strong><?php esc_html_e( 'Filter by hazard type:', 'somca' ); ?></strong></label>
			<select id="somca-hazard-select" name="hazard" onchange="this.form.submit()">
				<option value=""><?php esc_html_e( 'All Hazard Types', 'somca' ); ?></option>
				<?php foreach ( $hazards as $hazard ) : ?>
					<option value="<?php echo esc_attr( $hazard->slug ); ?>" <?php selected( $selected_hazard, $hazard->slug ); ?>><?php echo esc_html( $hazard->name ); ?></option>
				<?php endforeach; ?>
			</select>
			<noscript><button type="submit" class="somca-btn somca-btn-outline"><?php esc_html_e( 'Apply', 'somca' ); ?></button></noscript>
		</form>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="somca-card-grid">
			<?php while ( have_posts() ) : the_post();
				$risk = get_post_meta( get_the_ID(), 'somca_risk_level', true ) ?: 'moderate';
				?>
				<article <?php post_class( 'somca-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="somca-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'somca-card' ); ?></a>
					<?php endif; ?>
					<div class="somca-card-body">
						<span class="somca-risk-badge somca-risk-<?php echo esc_attr( $risk ); ?>"><?php echo esc_html( ucfirst( $risk ) ); ?></span>
						<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<nav class="somca-pagination"><?php the_posts_pagination(); ?></nav>
	<?php else : ?>
		<p><?php esc_html_e( 'No hotspots published yet.', 'somca' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
