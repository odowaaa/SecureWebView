<?php
/**
 * "Our Experts" section — reused on the homepage and the About page.
 * Renders nothing if no experts exist yet.
 *
 * Expected $args: count (default 4).
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args    = wp_parse_args( $args ?? array(), array( 'count' => 4 ) );
$sf_experts = sf_theme_get_experts( (int) $args['count'] );
if ( empty( $sf_experts ) ) {
	return;
}
?>
<section class="experts-section sf-reveal" aria-label="<?php esc_attr_e( 'Our Experts', 'somali-focus' ); ?>">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Meet The Team', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Our Experts', 'somali-focus' ); ?></h2>
		</header>
		<div class="experts-grid">
			<?php foreach ( $sf_experts as $sf_expert ) : ?>
				<?php get_template_part( 'template-parts/expert-card', null, array( 'post' => $sf_expert ) ); ?>
			<?php endforeach; ?>
		</div>
		<p class="section-cta"><a class="btn btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'sf_expert' ) ); ?>"><?php esc_html_e( 'Meet All Experts', 'somali-focus' ); ?></a></p>
	</div>
</section>
