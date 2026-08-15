<?php
/**
 * Training course archive.
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
		'eyebrow'     => __( 'Training', 'somali-focus' ),
		'title'       => __( 'Training Courses', 'somali-focus' ),
		'description' => __( 'Browse upcoming and ongoing training programs delivered by Somali Focus.', 'somali-focus' ),
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
					get_template_part( 'template-parts/course-card', null, array( 'post' => get_post() ) );
				endwhile;
				?>
			</div>
			<div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'No courses are currently listed. Please check back soon or contact us to arrange a customized session.', 'somali-focus' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
