<?php
/**
 * Reusable call-to-action band. Expected $args: title, description,
 * primary_text, primary_url, secondary_text, secondary_url.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'title'          => sf_theme_option( 'cta_title', __( 'Have a Training, Advisory or Research Need?', 'somali-focus' ) ),
		'description'    => sf_theme_option( 'cta_description', __( "Let's discuss how Somali Focus can support your organization.", 'somali-focus' ) ),
		'primary_text'   => sf_theme_option( 'cta_button_primary_text', __( 'Request a Service', 'somali-focus' ) ),
		'primary_url'    => sf_theme_option( 'cta_button_primary_url' ) ? sf_theme_option( 'cta_button_primary_url' ) : home_url( '/contact/' ),
		'secondary_text' => sf_theme_option( 'cta_button_secondary_text', __( 'Contact Us', 'somali-focus' ) ),
		'secondary_url'  => sf_theme_option( 'cta_button_secondary_url' ) ? sf_theme_option( 'cta_button_secondary_url' ) : home_url( '/contact/' ),
	)
);
?>
<section class="cta-band sf-reveal">
	<div class="container cta-band__inner">
		<div class="cta-band__text">
			<h2 class="cta-band__title"><?php echo esc_html( $args['title'] ); ?></h2>
			<?php if ( $args['description'] ) : ?><p class="cta-band__description"><?php echo esc_html( $args['description'] ); ?></p><?php endif; ?>
		</div>
		<div class="cta-band__actions">
			<a class="btn btn--white btn--lg" href="<?php echo esc_url( $args['primary_url'] ); ?>"><?php echo esc_html( $args['primary_text'] ); ?></a>
			<a class="btn btn--outline-white btn--lg" href="<?php echo esc_url( $args['secondary_url'] ); ?>"><?php echo esc_html( $args['secondary_text'] ); ?></a>
		</div>
	</div>
</section>
