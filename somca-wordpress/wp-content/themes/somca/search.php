<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="somca-container somca-section">
	<div class="somca-layout">
		<main id="primary" class="somca-main">
			<h1 class="somca-page-title"><?php printf( esc_html__( 'Search results for: %s', 'somca' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
			<?php if ( have_posts() ) : ?>
				<div class="somca-card-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<article <?php post_class( 'somca-card' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="somca-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'somca-card' ); ?></a>
							<?php endif; ?>
							<div class="somca-card-body">
								<div class="somca-card-meta"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></div>
								<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<nav class="somca-pagination"><?php the_posts_pagination(); ?></nav>
			<?php else : ?>
				<p><?php esc_html_e( 'No results found. Try a different search term.', 'somca' ); ?></p>
				<?php get_search_form(); ?>
			<?php endif; ?>
		</main>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>
