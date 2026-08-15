<?php
/**
 * Advisory projects archive.
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
		'eyebrow'     => __( 'Advisory', 'somali-focus' ),
		'title'       => __( 'Advisory Projects', 'somali-focus' ),
		'description' => __( 'A selection of advisory and consulting engagements delivered by Somali Focus.', 'somali-focus' ),
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
					get_template_part( 'template-parts/advisory-card', null, array( 'post' => get_post() ) );
				endwhile;
				?>
			</div>
			<div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'No advisory projects are currently listed.', 'somali-focus' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/cta' );
get_footer();
