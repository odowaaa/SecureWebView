<?php
/**
 * A single expert profile card. Expected $args: post (WP_Post).
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
$sf_position  = sf_theme_meta( $sf_post, 'position' );
$sf_linkedin  = sf_theme_meta( $sf_post, 'linkedin' );
?>
<article class="expert-card sf-reveal">
	<a class="expert-card__media" href="<?php echo esc_url( $sf_permalink ); ?>">
		<?php if ( has_post_thumbnail( $sf_post ) ) : ?>
			<?php echo get_the_post_thumbnail( $sf_post, 'sf-portrait', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span class="expert-card__initial"><?php echo esc_html( mb_substr( get_the_title( $sf_post ), 0, 1 ) ); ?></span>
		<?php endif; ?>
	</a>
	<div class="expert-card__body">
		<h3 class="expert-card__name"><a href="<?php echo esc_url( $sf_permalink ); ?>"><?php echo esc_html( get_the_title( $sf_post ) ); ?></a></h3>
		<?php if ( $sf_position ) : ?><p class="expert-card__position"><?php echo esc_html( $sf_position ); ?></p><?php endif; ?>
		<?php if ( $sf_linkedin ) : ?>
			<a class="expert-card__linkedin" href="<?php echo esc_url( $sf_linkedin ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn profile', 'somali-focus' ); ?>">
				<?php sf_social_icon( 'linkedin' ); ?>
			</a>
		<?php endif; ?>
	</div>
</article>
