<?php
/**
 * Full-screen mobile navigation panel, toggled by the header hamburger.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="mobile-navigation" class="mobile-nav" data-sf-mobile-nav hidden>
	<div class="mobile-nav__inner">
		<nav class="mobile-nav__menu" aria-label="<?php esc_attr_e( 'Mobile', 'somali-focus' ); ?>">
			<?php wp_nav_menu( sf_primary_menu_args( 'mobile-menu' ) ); ?>
		</nav>

		<a class="btn btn--primary btn--block" href="<?php echo esc_url( sf_header_cta_url() ); ?>">
			<?php echo esc_html( get_theme_mod( 'sf_header_cta_text', __( 'Request a Service', 'somali-focus' ) ) ); ?>
		</a>

		<div class="mobile-nav__contact">
			<?php
			$sf_phone = sf_theme_option( 'phone' );
			$sf_email = sf_theme_option( 'email' );
			if ( $sf_phone ) {
				echo '<a href="' . esc_url( 'tel:' . preg_replace( '/\s+/', '', $sf_phone ) ) . '">' . esc_html( $sf_phone ) . '</a>';
			}
			if ( $sf_email ) {
				echo '<a href="' . esc_url( 'mailto:' . $sf_email ) . '">' . esc_html( $sf_email ) . '</a>';
			}
			?>
		</div>
	</div>
</div>
