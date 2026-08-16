<?php
/**
 * Taxonomy term archive — course_category, advisory_category,
 * research_category, expertise, sf_sector. Renders each item with its
 * own post-type card rather than a generic blog-post treatment, since a
 * term can span more than one Somali Focus content type (sf_sector is
 * shared by Advisory Projects and Partners, for example).
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$sf_term = get_queried_object();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'     => $sf_term instanceof WP_Term ? get_taxonomy( $sf_term->taxonomy )->labels->singular_name : '',
		'title'       => get_the_archive_title(),
		'description' => wp_strip_all_tags( get_the_archive_description() ),
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
			<p><?php esc_html_e( 'Nothing found in this category yet.', 'somali-focus' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
