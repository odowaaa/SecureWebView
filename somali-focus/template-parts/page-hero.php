<?php
/**
 * Compact inner-page hero used by Training/Advisory/Research/About/etc.
 * Expected $args: eyebrow, title, description.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'     => '',
		'title'       => get_the_title(),
		'description' => '',
	)
);
?>
<section class="page-hero sf-reveal">
	<div class="container">
		<?php sf_breadcrumbs(); ?>
		<?php if ( $args['eyebrow'] ) : ?><p class="eyebrow"><?php echo esc_html( $args['eyebrow'] ); ?></p><?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $args['title'] ); ?></h1>
		<?php if ( $args['description'] ) : ?><p class="page-hero__description"><?php echo esc_html( $args['description'] ); ?></p><?php endif; ?>
	</div>
</section>
