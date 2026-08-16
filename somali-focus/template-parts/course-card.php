<?php
/**
 * A single course card. Expected $args: post (WP_Post).
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_post = isset( $args['post'] ) ? $args['post'] : null;
if ( ! $sf_post instanceof WP_Post ) {
	return;
}

$sf_permalink = get_permalink( $sf_post );
$sf_terms     = get_the_terms( $sf_post, 'course_category' );
$sf_category  = ( $sf_terms && ! is_wp_error( $sf_terms ) ) ? $sf_terms[0]->name : '';
$sf_duration  = sf_theme_meta( $sf_post, 'duration' );
$sf_mode      = sf_theme_meta( $sf_post, 'delivery_mode' );
$sf_start     = sf_theme_meta( $sf_post, 'start_date' );
$sf_excerpt   = sf_theme_meta( $sf_post, 'short_description' );
if ( ! $sf_excerpt ) {
	$sf_excerpt = sf_trim_words( $sf_post->post_content, 18 );
}
?>
<article class="card course-card sf-reveal">
	<a class="card__media" href="<?php echo esc_url( $sf_permalink ); ?>">
		<?php if ( has_post_thumbnail( $sf_post ) ) : ?>
			<?php echo get_the_post_thumbnail( $sf_post, 'sf-card', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title( $sf_post ) ) ); ?>
		<?php else : ?>
			<span class="card__media-fallback"><?php sf_icon( 'training' ); ?></span>
		<?php endif; ?>
		<?php if ( $sf_category ) : ?><span class="card__badge"><?php echo esc_html( $sf_category ); ?></span><?php endif; ?>
	</a>
	<div class="card__body">
		<ul class="card__meta">
			<?php if ( $sf_duration ) : ?><li><?php sf_icon( 'clock' ); ?><?php echo esc_html( $sf_duration ); ?></li><?php endif; ?>
			<?php if ( $sf_mode ) : ?><li><?php sf_icon( 'compass' ); ?><?php echo esc_html( ucfirst( $sf_mode ) ); ?></li><?php endif; ?>
			<?php if ( $sf_start ) : ?><li><?php sf_icon( 'calendar' ); ?><?php echo esc_html( sf_format_date( $sf_start ) ); ?></li><?php endif; ?>
		</ul>
		<h3 class="card__title"><a href="<?php echo esc_url( $sf_permalink ); ?>"><?php echo esc_html( get_the_title( $sf_post ) ); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html( $sf_excerpt ); ?></p>
		<a class="card__link" href="<?php echo esc_url( $sf_permalink ); ?>"><?php esc_html_e( 'View Course', 'somali-focus' ); ?> <?php sf_icon( 'arrow-right' ); ?></a>
	</div>
</article>
