<?php
/**
 * Default blog listing template — powers the Insights section.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'     => __( 'Insights', 'somali-focus' ),
		'title'       => __( 'Insights', 'somali-focus' ),
		'description' => __( 'Perspectives on training, advisory and research from the Somali Focus team.', 'somali-focus' ),
	)
);
?>
<section class="page-content-section sf-reveal">
	<div class="container blog-layout">
		<div class="blog-layout__main">
			<?php if ( have_posts() ) : ?>
				<div class="card-grid card-grid--3">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/blog-card' );
					endwhile;
					?>
				</div>
				<div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
			<?php else : ?>
				<p><?php esc_html_e( 'No posts published yet.', 'somali-focus' ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( is_active_sidebar( 'sf-blog-sidebar' ) ) : ?>
			<aside class="blog-layout__sidebar">
				<?php dynamic_sidebar( 'sf-blog-sidebar' ); ?>
			</aside>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
