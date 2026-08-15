<?php
/**
 * Single expert profile page.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$sf_id       = get_the_ID();
	$sf_position = sf_theme_meta( $sf_id, 'position' );
	$sf_linkedin = sf_theme_meta( $sf_id, 'linkedin' );
	$sf_email    = sf_theme_meta( $sf_id, 'email' );
	$sf_website  = sf_theme_meta( $sf_id, 'website' );
	?>
	<article <?php post_class( 'single-expert' ); ?>>
		<section class="page-content-section sf-reveal">
			<div class="container expert-profile">
				<div class="expert-profile__media">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'sf-portrait' ); ?>
					<?php else : ?>
						<span class="expert-card__initial expert-profile__initial"><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span>
					<?php endif; ?>
					<?php if ( $sf_linkedin || $sf_email || $sf_website ) : ?>
						<ul class="expert-profile__social">
							<?php if ( $sf_linkedin ) : ?><li><a href="<?php echo esc_url( $sf_linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><?php sf_social_icon( 'linkedin' ); ?></a></li><?php endif; ?>
							<?php if ( $sf_email ) : ?><li><a href="<?php echo esc_url( 'mailto:' . $sf_email ); ?>" aria-label="Email"><?php sf_icon( 'compass' ); ?></a></li><?php endif; ?>
							<?php if ( $sf_website ) : ?><li><a href="<?php echo esc_url( $sf_website ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Website"><?php sf_icon( 'globe' ); ?></a></li><?php endif; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="expert-profile__body entry-content">
					<?php sf_breadcrumbs(); ?>
					<h1><?php the_title(); ?></h1>
					<?php if ( $sf_position ) : ?><p class="expert-profile__position"><?php echo esc_html( $sf_position ); ?></p><?php endif; ?>

					<?php the_content(); ?>

					<?php
					$sf_rows = array(
						'education'  => __( 'Education', 'somali-focus' ),
						'experience' => __( 'Professional Experience', 'somali-focus' ),
					);
					foreach ( $sf_rows as $sf_key => $sf_label ) :
						$sf_value = sf_theme_meta( $sf_id, $sf_key );
						if ( ! $sf_value ) {
							continue;
						}
						?>
						<h2><?php echo esc_html( $sf_label ); ?></h2>
						<p><?php echo esc_html( $sf_value ); ?></p>
						<?php
					endforeach;
					?>
				</div>
			</div>
		</section>
	</article>
	<?php
endwhile;

get_footer();
