<?php
/**
 * A single testimonial card. Expected $args: post (WP_Post).
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

$sf_org      = sf_theme_meta( $sf_post, 'organization' );
$sf_position = sf_theme_meta( $sf_post, 'position' );
?>
<figure class="testimonial-card sf-reveal">
	<span class="testimonial-card__quote-mark" aria-hidden="true">&ldquo;</span>
	<blockquote class="testimonial-card__quote"><?php echo esc_html( wp_strip_all_tags( $sf_post->post_content ) ); ?></blockquote>
	<figcaption class="testimonial-card__author">
		<?php if ( has_post_thumbnail( $sf_post ) ) : ?>
			<?php echo get_the_post_thumbnail( $sf_post, 'thumbnail', array( 'loading' => 'lazy', 'class' => 'testimonial-card__photo', 'alt' => get_the_title( $sf_post ) ) ); ?>
		<?php endif; ?>
		<span>
			<span class="testimonial-card__name"><?php echo esc_html( get_the_title( $sf_post ) ); ?></span>
			<?php if ( $sf_position || $sf_org ) : ?>
				<span class="testimonial-card__role"><?php echo esc_html( trim( $sf_position . ( $sf_position && $sf_org ? ', ' : '' ) . $sf_org ) ); ?></span>
			<?php endif; ?>
		</span>
	</figcaption>
</figure>
