<?php
/**
 * A single advisory project card. Expected $args: post (WP_Post).
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
$sf_terms     = get_the_terms( $sf_post, 'advisory_category' );
$sf_category  = ( $sf_terms && ! is_wp_error( $sf_terms ) ) ? $sf_terms[0]->name : '';
$sf_sector    = sf_theme_meta( $sf_post, 'client_sector' );
$sf_challenge = sf_theme_meta( $sf_post, 'challenge' );
?>
<article class="card advisory-card sf-reveal">
	<a class="card__media" href="<?php echo esc_url( $sf_permalink ); ?>">
		<?php if ( has_post_thumbnail( $sf_post ) ) : ?>
			<?php echo get_the_post_thumbnail( $sf_post, 'sf-card', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title( $sf_post ) ) ); ?>
		<?php else : ?>
			<span class="card__media-fallback"><?php sf_icon( 'advisory' ); ?></span>
		<?php endif; ?>
		<?php if ( $sf_category ) : ?><span class="card__badge"><?php echo esc_html( $sf_category ); ?></span><?php endif; ?>
	</a>
	<div class="card__body">
		<?php if ( $sf_sector ) : ?><p class="card__meta card__meta--single"><?php sf_icon( 'briefcase' ); ?><?php echo esc_html( $sf_sector ); ?></p><?php endif; ?>
		<h3 class="card__title"><a href="<?php echo esc_url( $sf_permalink ); ?>"><?php echo esc_html( get_the_title( $sf_post ) ); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html( sf_trim_words( $sf_challenge, 20 ) ); ?></p>
		<a class="card__link" href="<?php echo esc_url( $sf_permalink ); ?>"><?php esc_html_e( 'Read More', 'somali-focus' ); ?> <?php sf_icon( 'arrow-right' ); ?></a>
	</div>
</article>
