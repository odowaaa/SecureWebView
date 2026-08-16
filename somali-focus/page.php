<?php
/**
 * Generic page template.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>
		<section class="page-hero sf-reveal">
			<div class="container">
				<?php sf_breadcrumbs(); ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
			</div>
		</section>

		<section class="page-content-section sf-reveal">
			<div class="container container--narrow entry-content">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-post__image"><?php the_post_thumbnail( 'sf-hero', array( 'alt' => get_the_title() ) ); ?></div>
				<?php endif; ?>
				<?php the_content(); ?>
				<?php
				wp_link_pages(
					array(
						'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'somali-focus' ),
						'after'  => '</nav>',
					)
				);
				?>
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
