<?php
/**
 * Single advisory project page.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$sf_id      = get_the_ID();
	$sf_terms   = get_the_terms( $sf_id, 'advisory_category' );
	$sf_category = ( $sf_terms && ! is_wp_error( $sf_terms ) ) ? $sf_terms[0]->name : '';
	?>
	<article <?php post_class( 'single-advisory' ); ?>>

		<section class="page-hero sf-reveal">
			<div class="container">
				<?php sf_breadcrumbs(); ?>
				<?php if ( $sf_category ) : ?><p class="eyebrow"><?php echo esc_html( $sf_category ); ?></p><?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<?php $sf_sector = sf_theme_meta( $sf_id, 'client_sector' ); ?>
				<?php if ( $sf_sector ) : ?><p class="page-hero__description"><?php echo esc_html( $sf_sector ); ?></p><?php endif; ?>
			</div>
		</section>

		<section class="page-content-section sf-reveal">
			<div class="container container--narrow entry-content">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-course__image"><?php the_post_thumbnail( 'sf-hero', array( 'alt' => get_the_title() ) ); ?></div>
				<?php endif; ?>

				<?php
				$sf_sections = array(
					'challenge'    => __( 'The Challenge', 'somali-focus' ),
					'approach'     => __( 'Our Approach', 'somali-focus' ),
					'results'      => __( 'Results', 'somali-focus' ),
				);
				foreach ( $sf_sections as $sf_key => $sf_label ) :
					$sf_value = sf_theme_meta( $sf_id, $sf_key );
					if ( ! $sf_value ) {
						continue;
					}
					?>
					<h2><?php echo esc_html( $sf_label ); ?></h2>
					<div><?php echo wp_kses_post( wpautop( $sf_value ) ); ?></div>
					<?php
				endforeach;

				$sf_deliverables = sf_theme_meta( $sf_id, 'deliverables' );
				if ( $sf_deliverables ) :
					?>
					<h2><?php esc_html_e( 'Deliverables', 'somali-focus' ); ?></h2>
					<ul class="checklist">
						<?php foreach ( array_filter( array_map( 'trim', explode( "\n", $sf_deliverables ) ) ) as $sf_line ) : ?>
							<li><?php sf_icon( 'check' ); ?><?php echo esc_html( $sf_line ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php the_content(); ?>
			</div>
		</section>

		<?php get_template_part( 'template-parts/cta' ); ?>

	</article>
	<?php
endwhile;

get_footer();
