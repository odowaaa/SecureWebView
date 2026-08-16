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
?>

<section class="core-services sf-reveal" aria-label="<?php esc_attr_e( 'What We Do', 'somali-focus' ); ?>">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'What We Do', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Three Ways We Help', 'somali-focus' ); ?></h2>
		</header>
		<div class="core-services__grid">
			<?php
			get_template_part(
				'template-parts/service-card',
				null,
				array(
					'number'      => '01',
					'icon'        => 'training',
					'title'       => __( 'Training', 'somali-focus' ),
					'description' => __( 'Building knowledge, skills and professional capacity.', 'somali-focus' ),
					'url'         => home_url( '/training/' ),
					'cta_text'    => __( 'Explore Training', 'somali-focus' ),
					'accent'      => 'blue',
				)
			);
			get_template_part(
				'template-parts/service-card',
				null,
				array(
					'number'      => '02',
					'icon'        => 'advisory',
					'title'       => __( 'Advisory', 'somali-focus' ),
					'description' => __( 'Practical expertise to help organizations solve complex challenges.', 'somali-focus' ),
					'url'         => home_url( '/advisory/' ),
					'cta_text'    => __( 'Explore Advisory', 'somali-focus' ),
					'accent'      => 'red',
				)
			);
			get_template_part(
				'template-parts/service-card',
				null,
				array(
					'number'      => '03',
					'icon'        => 'research',
					'title'       => __( 'Research', 'somali-focus' ),
					'description' => __( 'Generating reliable evidence for better decisions.', 'somali-focus' ),
					'url'         => home_url( '/research/' ),
					'cta_text'    => __( 'Explore Research', 'somali-focus' ),
					'accent'      => 'blue',
				)
			);
			?>
		</div>
	</div>
</section>

<?php
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
get_template_part( 'template-parts/sectors' );
get_template_part( 'template-parts/experts-section', null, array( 'count' => 4 ) );
get_template_part( 'template-parts/cta' );

get_footer();
