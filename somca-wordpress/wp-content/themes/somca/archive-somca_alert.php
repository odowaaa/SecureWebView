<?php
/**
 * Alert archive: all active + past alerts, most severe first.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="somca-container somca-section">
	<header class="somca-section-header">
		<div>
			<span class="somca-eyebrow"><?php esc_html_e( 'Early Warning', 'somca' ); ?></span>
			<h1><?php esc_html_e( 'Climate Alerts', 'somca' ); ?></h1>
			<p><?php esc_html_e( 'Advisories, watches, warnings and emergencies issued from automated climate monitoring and editorial review.', 'somca' ); ?></p>
		</div>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="somca-alerts-banner" style="margin-bottom:2em;">
			<?php while ( have_posts() ) : the_post();
				$severity = get_post_meta( get_the_ID(), 'somca_severity', true ) ?: 'advisory';
				?>
				<a class="somca-alert somca-severity-<?php echo esc_attr( $severity ); ?>" href="<?php the_permalink(); ?>">
					<span class="somca-alert-badge somca-severity-<?php echo esc_attr( $severity ); ?>"><?php echo esc_html( ucfirst( $severity ) ); ?></span>
					<span class="somca-alert-title"><?php the_title(); ?></span>
				</a>
			<?php endwhile; ?>
		</div>
		<nav class="somca-pagination"><?php the_posts_pagination(); ?></nav>
	<?php else : ?>
		<p><?php esc_html_e( 'No alerts issued yet.', 'somca' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
