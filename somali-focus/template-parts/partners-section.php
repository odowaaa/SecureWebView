<?php
/**
 * "Partners & Clients" section — reused on the homepage and the About
 * page. Renders nothing if no partners exist yet.
 *
 * Expected $args: count (default 12).
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args     = wp_parse_args( $args ?? array(), array( 'count' => 12 ) );
$sf_partners = sf_theme_get_partners( (int) $args['count'] );
if ( empty( $sf_partners ) ) {
	return;
}
?>
<section class="partners-section sf-reveal" aria-label="<?php esc_attr_e( 'Our Partners', 'somali-focus' ); ?>">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Trusted By', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Partners & Clients', 'somali-focus' ); ?></h2>
		</header>
		<div class="partners-grid">
			<?php foreach ( $sf_partners as $sf_partner ) : ?>
				<?php get_template_part( 'template-parts/partner-card', null, array( 'post' => $sf_partner ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
