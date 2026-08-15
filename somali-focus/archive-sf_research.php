<?php
/**
 * Research & Publications archive.
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
		'eyebrow'     => __( 'Research', 'somali-focus' ),
		'title'       => __( 'Research & Publications', 'somali-focus' ),
		'description' => __( 'Studies, assessments and evaluations produced by the Somali Focus research team.', 'somali-focus' ),
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
					get_template_part( 'template-parts/research-card', null, array( 'post' => get_post() ) );
				endwhile;
				?>
			</div>
			<div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'No research items are currently listed.', 'somali-focus' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
