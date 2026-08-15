<?php
/**
 * A single partner logo. Expected $args: post (WP_Post).
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

$sf_website = sf_theme_meta( $sf_post, 'website' );
$sf_tag     = $sf_website ? 'a' : 'span';
?>
<<?php echo esc_html( $sf_tag ); ?>
	class="partner-logo"
	<?php if ( $sf_website ) : ?>href="<?php echo esc_url( $sf_website ); ?>" target="_blank" rel="noopener noreferrer"<?php endif; ?>
	title="<?php echo esc_attr( get_the_title( $sf_post ) ); ?>"
>
	<?php if ( has_post_thumbnail( $sf_post ) ) : ?>
		<?php echo get_the_post_thumbnail( $sf_post, 'sf-logo-wide', array( 'loading' => 'lazy', 'alt' => get_the_title( $sf_post ) ) ); ?>
	<?php else : ?>
		<span class="partner-logo__text"><?php echo esc_html( get_the_title( $sf_post ) ); ?></span>
	<?php endif; ?>
</<?php echo esc_html( $sf_tag ); ?>>
