<?php
/**
 * Template Name: About Page
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
		'eyebrow'     => __( 'About Somali Focus', 'somali-focus' ),
		'title'       => get_the_title(),
		'description' => __( 'Connecting knowledge, expertise and evidence to help people and organizations perform better.', 'somali-focus' ),
	)
);

get_template_part( 'template-parts/about', null, array( 'compact' => false ) );

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		if ( trim( get_the_content() ) ) :
			?>
			<section class="page-content-section sf-reveal">
				<div class="container container--narrow entry-content">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;
	endwhile;
endif;

get_template_part( 'template-parts/stats' );
get_template_part( 'template-parts/approach' );
get_template_part( 'template-parts/cta' );

get_footer();
