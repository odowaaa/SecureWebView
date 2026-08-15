<?php
/**
 * One of the three core-service cards (Training / Advisory / Research).
 *
 * Expected $args: number, icon, title, description, url, cta_text, accent.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'number'      => '01',
		'icon'        => 'training',
		'title'       => '',
		'description' => '',
		'url'         => '#',
		'cta_text'    => __( 'Learn More', 'somali-focus' ),
		'accent'      => 'blue',
	)
);
?>
<a class="service-card sf-reveal service-card--<?php echo esc_attr( $args['accent'] ); ?>" href="<?php echo esc_url( $args['url'] ); ?>">
	<span class="service-card__number"><?php echo esc_html( $args['number'] ); ?></span>
	<span class="service-card__icon"><?php sf_icon( $args['icon'] ); ?></span>
	<h3 class="service-card__title"><?php echo esc_html( $args['title'] ); ?></h3>
	<p class="service-card__description"><?php echo esc_html( $args['description'] ); ?></p>
	<span class="service-card__cta"><?php echo esc_html( $args['cta_text'] ); ?> <?php sf_icon( 'arrow-right' ); ?></span>
</a>
