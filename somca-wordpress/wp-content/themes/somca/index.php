<?php
/**
 * Fallback template: blog index / any post type without a dedicated template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="somca-container somca-section">
	<div class="somca-layout">
		<main id="primary" class="somca-main">
			<?php if ( have_posts() ) : ?>
				<?php if ( is_home() && ! is_front_page() ) : ?>
					<h1 class="somca-page-title"><?php single_post_title(); ?></h1>
				<?php endif; ?>

				<div class="somca-card-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<article <?php post_class( 'somca-card' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="somca-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'somca-card' ); ?></a>
							<?php endif; ?>
							<div class="somca-card-body">
								<div class="somca-card-meta"><?php echo esc_html( get_the_date() ); ?></div>
								<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<nav class="somca-pagination"><?php the_posts_pagination(); ?></nav>
			<?php else : ?>
				<p><?php esc_html_e( 'Nothing found.', 'somca' ); ?></p>
			<?php endif; ?>
		</main>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>
