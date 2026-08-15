<?php
/**
 * Template Name: Contact Page
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'     => __( 'Contact', 'somali-focus' ),
		'title'       => __( 'Contact Somali Focus', 'somali-focus' ),
		'description' => __( "We'd love to hear about your Training, Advisory or Research need.", 'somali-focus' ),
	)
);

$sf_email   = sf_theme_option( 'email' );
$sf_phone   = sf_theme_option( 'phone' );
$sf_address = sf_theme_option( 'address' );
$sf_hours   = sf_theme_option( 'office_hours' );
?>

<section class="page-content-section sf-reveal">
	<div class="container contact-layout">
		<div class="contact-layout__form">
			<h2 class="section-title"><?php esc_html_e( 'Send Us a Message', 'somali-focus' ); ?></h2>
			<?php sf_theme_service_request_form(); ?>
		</div>

		<aside class="contact-layout__info">
			<h2 class="section-title"><?php esc_html_e( 'Get In Touch', 'somali-focus' ); ?></h2>
			<ul class="contact-info-list">
				<?php if ( $sf_address ) : ?>
					<li><?php sf_icon( 'location' ); ?><span><strong><?php esc_html_e( 'Office', 'somali-focus' ); ?></strong><br><?php echo esc_html( $sf_address ); ?></span></li>
				<?php endif; ?>
				<?php if ( $sf_phone ) : ?>
					<li><?php sf_icon( 'clock' ); ?><span><strong><?php esc_html_e( 'Phone', 'somali-focus' ); ?></strong><br><a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $sf_phone ) ); ?>"><?php echo esc_html( $sf_phone ); ?></a></span></li>
				<?php endif; ?>
				<?php if ( $sf_email ) : ?>
					<li><?php sf_icon( 'compass' ); ?><span><strong><?php esc_html_e( 'Email', 'somali-focus' ); ?></strong><br><a href="<?php echo esc_url( 'mailto:' . $sf_email ); ?>"><?php echo esc_html( $sf_email ); ?></a></span></li>
				<?php endif; ?>
				<?php if ( $sf_hours ) : ?>
					<li><?php sf_icon( 'calendar' ); ?><span><strong><?php esc_html_e( 'Office Hours', 'somali-focus' ); ?></strong><br><?php echo esc_html( $sf_hours ); ?></span></li>
				<?php endif; ?>
			</ul>

			<?php if ( $sf_address ) : ?>
				<div class="contact-map">
					<iframe
						src="https://maps.google.com/maps?q=<?php echo rawurlencode( $sf_address ); ?>&output=embed"
						width="100%" height="260" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
						title="<?php echo esc_attr( $sf_address ); ?>"
					></iframe>
				</div>
			<?php endif; ?>
		</aside>
	</div>
</section>

<?php get_footer(); ?>
