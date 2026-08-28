<?php
/**
 * Period archive (weekly/monthly/seasonal/yearly/biyearly) — reused report layout.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();
?>
<div class="somca-container somca-section">
	<header class="somca-section-header">
		<div>
			<span class="somca-eyebrow"><?php esc_html_e( 'Data & Trends', 'somca' ); ?></span>
			<h1><?php echo esc_html( $term->name ); ?> <?php esc_html_e( 'Reports', 'somca' ); ?></h1>
		</div>
	</header>

	<div class="somca-period-tabs">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'somca_report' ) ); ?>"><?php esc_html_e( 'All', 'somca' ); ?></a>
		<?php
		foreach ( array( 'weekly' => __( 'Weekly', 'somca' ), 'monthly' => __( 'Monthly', 'somca' ), 'seasonal' => __( 'Seasonal', 'somca' ), 'yearly' => __( 'Yearly', 'somca' ), 'biyearly' => __( 'Bi-Yearly', 'somca' ) ) as $slug => $label ) :
			$term_link = get_term_link( $slug, 'somca_period' );
			if ( is_wp_error( $term_link ) ) {
				continue;
			}
			?>
			<a href="<?php echo esc_url( $term_link ); ?>" class="<?php echo ( $term->slug === $slug ) ? 'is-active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
	</div>

	<?php if ( have_posts() ) : ?>
		<div class="somca-card-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'somca-card' ); ?>>
					<div class="somca-card-body">
						<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="somca-card-stats">
							<span><?php echo esc_html( get_post_meta( get_the_ID(), 'somca_avg_temp_c', true ) ); ?>&deg;C</span>
							<span><?php echo esc_html( get_post_meta( get_the_ID(), 'somca_rainfall_mm', true ) ); ?>mm</span>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<nav class="somca-pagination"><?php the_posts_pagination(); ?></nav>
	<?php else : ?>
		<p><?php esc_html_e( 'No reports for this period yet.', 'somca' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
