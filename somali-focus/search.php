<?php
/**
 * Search results — spans posts, courses, advisory, research and experts.
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
		'eyebrow'     => __( 'Search', 'somali-focus' ),
		/* translators: %s: search query */
		'title'       => sprintf( __( 'Search results for "%s"', 'somali-focus' ), get_search_query() ),
		'description' => '',
	)
);
?>
<section class="page-content-section sf-reveal">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="card-grid card-grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					switch ( get_post_type() ) {
						case 'sf_course':
							get_template_part( 'template-parts/course-card', null, array( 'post' => get_post() ) );
							break;
						case 'sf_advisory':
							get_template_part( 'template-parts/advisory-card', null, array( 'post' => get_post() ) );
							break;
						case 'sf_research':
							get_template_part( 'template-parts/research-card', null, array( 'post' => get_post() ) );
							break;
						case 'sf_expert':
							get_template_part( 'template-parts/expert-card', null, array( 'post' => get_post() ) );
							break;
						default:
							get_template_part( 'template-parts/blog-card' );
					}
				endwhile;
				?>
			</div>
			<div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'No results found. Try a different search term.', 'somali-focus' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
