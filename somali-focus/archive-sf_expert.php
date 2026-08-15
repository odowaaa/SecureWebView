<?php
/**
 * Experts archive.
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
		'eyebrow'     => __( 'Our Team', 'somali-focus' ),
		'title'       => __( 'Our Experts', 'somali-focus' ),
		'description' => __( 'The trainers, advisors and researchers behind Somali Focus.', 'somali-focus' ),
	)
);
?>
<section class="page-content-section sf-reveal">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="experts-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/expert-card', null, array( 'post' => get_post() ) );
				endwhile;
				?>
			</div>
			<div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'No experts are currently listed.', 'somali-focus' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
