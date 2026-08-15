<?php
/**
 * Single Insights (blog) post.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$sf_categories = get_the_category();
	$sf_category   = ! empty( $sf_categories ) ? $sf_categories[0]->name : '';
	?>
	<article <?php post_class( 'single-post' ); ?>>

		<section class="page-hero sf-reveal">
			<div class="container container--narrow">
				<?php sf_breadcrumbs(); ?>
				<?php if ( $sf_category ) : ?><p class="eyebrow"><?php echo esc_html( $sf_category ); ?></p><?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<p class="page-hero__meta">
					<?php echo esc_html( get_the_date() ); ?>
					<span class="card__meta-dot">&middot;</span>
					<?php
					/* translators: %d: reading time in minutes */
					printf( esc_html__( '%d min read', 'somali-focus' ), (int) sf_reading_time() );
					?>
				</p>
			</div>
		</section>

		<section class="page-content-section sf-reveal">
			<div class="container container--narrow">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-post__image"><?php the_post_thumbnail( 'sf-hero' ); ?></div>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'somali-focus' ),
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<footer class="entry-footer">
					<?php
					$sf_tags = get_the_tags();
					if ( $sf_tags ) :
						?>
						<div class="entry-tags">
							<?php foreach ( $sf_tags as $sf_tag ) : ?>
								<a class="tag-pill" href="<?php echo esc_url( get_tag_link( $sf_tag ) ); ?>"><?php echo esc_html( $sf_tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</footer>
			</div>
		</section>

		<?php if ( comments_open() || get_comments_number() ) : ?>
			<section class="page-content-section sf-reveal">
				<div class="container container--narrow">
					<?php comments_template(); ?>
				</div>
			</section>
		<?php endif; ?>

	</article>
	<?php
endwhile;

get_footer();
