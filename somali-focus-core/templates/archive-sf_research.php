<?php
/**
 * Fallback archive template for Research & Publications.
 *
 * @package SomaliFocusCore
 */

get_header();
?>
<main class="sf-fallback">
	<header class="sf-fallback__header">
		<h1 class="sf-fallback__title"><?php esc_html_e( 'Research & Publications', 'somali-focus' ); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="sf-fallback-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article class="sf-fallback-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
					<?php endif; ?>
					<div class="sf-fallback-card__body">
						<h2 class="sf-fallback-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="sf-fallback-card__meta"><?php echo esc_html( somali_focus_meta( get_the_ID(), 'publication_date', '' ) ); ?></p>
						<p><?php echo esc_html( wp_trim_words( somali_focus_meta( get_the_ID(), 'abstract', get_the_excerpt() ), 24 ) ); ?></p>
						<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Publication', 'somali-focus' ); ?> &rarr;</a>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No research items are currently listed.', 'somali-focus' ); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();
