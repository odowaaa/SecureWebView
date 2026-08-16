<?php
/**
 * The footer: multi-column info, legal bar, wp_footer.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_org_name = sf_theme_option( 'org_name', get_bloginfo( 'name' ) );
$sf_email    = sf_theme_option( 'email' );
$sf_phone    = sf_theme_option( 'phone' );
$sf_address  = sf_theme_option( 'address' );
$sf_hours    = sf_theme_option( 'office_hours' );

$sf_socials = array(
	'facebook' => array( sf_theme_option( 'social_facebook' ), __( 'Facebook', 'somali-focus' ) ),
	'linkedin' => array( sf_theme_option( 'social_linkedin' ), __( 'LinkedIn', 'somali-focus' ) ),
	'twitter'  => array( sf_theme_option( 'social_twitter' ), __( 'X (Twitter)', 'somali-focus' ) ),
	'youtube'  => array( sf_theme_option( 'social_youtube' ), __( 'YouTube', 'somali-focus' ) ),
);
?>
	</main><!-- #primary -->

	<footer id="colophon" class="site-footer">
		<div class="container site-footer__grid">

			<div class="footer-col footer-col--about">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-footer__logo">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<img
							class="custom-logo sf-default-logo sf-default-logo--footer"
							src="<?php echo esc_url( SOMALI_FOCUS_URI . '/assets/images/logo-horizontal.png' ); ?>"
							alt="<?php echo esc_attr( $sf_org_name ); ?>"
							width="781" height="120"
						>
					<?php endif; ?>
				</a>
				<p class="footer-col__text"><?php echo esc_html( sf_theme_option( 'tagline', __( 'Training, Advisory & Research', 'somali-focus' ) ) ); ?></p>
				<?php if ( array_filter( array_column( $sf_socials, 0 ) ) ) : ?>
					<ul class="social-icons">
						<?php foreach ( $sf_socials as $key => $social ) :
							if ( empty( $social[0] ) ) {
								continue;
							}
							?>
							<li>
								<a href="<?php echo esc_url( $social[0] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $social[1] ); ?>">
									<?php sf_social_icon( $key ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<nav class="footer-col footer-col--links" aria-label="<?php esc_attr_e( 'Quick Links', 'somali-focus' ); ?>">
				<h3 class="footer-col__title"><?php esc_html_e( 'Quick Links', 'somali-focus' ); ?></h3>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
						)
					);
				} else {
					echo '<ul class="footer-menu">';
					$links = array(
						'/'          => __( 'Home', 'somali-focus' ),
						'/about/'    => __( 'About', 'somali-focus' ),
						'/training/' => __( 'Training', 'somali-focus' ),
						'/advisory/' => __( 'Advisory', 'somali-focus' ),
						'/research/' => __( 'Research', 'somali-focus' ),
						'/insights/' => __( 'Insights', 'somali-focus' ),
						'/contact/'  => __( 'Contact', 'somali-focus' ),
					);
					foreach ( $links as $path => $label ) {
						echo '<li><a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a></li>';
					}
					echo '</ul>';
				}
				?>
			</nav>

			<div class="footer-col footer-col--services">
				<h3 class="footer-col__title"><?php esc_html_e( 'Our Services', 'somali-focus' ); ?></h3>
				<ul class="footer-menu">
					<li><a href="<?php echo esc_url( home_url( '/training/' ) ); ?>"><?php esc_html_e( 'Training', 'somali-focus' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/advisory/' ) ); ?>"><?php esc_html_e( 'Advisory', 'somali-focus' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/research/' ) ); ?>"><?php esc_html_e( 'Research', 'somali-focus' ); ?></a></li>
				</ul>
			</div>

			<div class="footer-col footer-col--contact">
				<h3 class="footer-col__title"><?php esc_html_e( 'Contact', 'somali-focus' ); ?></h3>
				<ul class="footer-contact">
					<?php if ( $sf_address ) : ?>
						<li><?php sf_icon( 'location' ); ?><span><?php echo esc_html( $sf_address ); ?></span></li>
					<?php endif; ?>
					<?php if ( $sf_phone ) : ?>
						<li><?php sf_icon( 'clock' ); ?><span><a href="<?php echo esc_url( 'tel:' . preg_replace( '/\s+/', '', $sf_phone ) ); ?>"><?php echo esc_html( $sf_phone ); ?></a></span></li>
					<?php endif; ?>
					<?php if ( $sf_email ) : ?>
						<li><a href="<?php echo esc_url( 'mailto:' . $sf_email ); ?>"><?php echo esc_html( $sf_email ); ?></a></li>
					<?php endif; ?>
					<?php if ( $sf_hours ) : ?>
						<li class="footer-contact__hours"><?php echo esc_html( $sf_hours ); ?></li>
					<?php endif; ?>
				</ul>
			</div>

		</div>

		<div class="site-footer__bottom">
			<div class="container site-footer__bottom-inner">
				<p class="site-footer__copyright">
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $sf_org_name ); ?>. <?php esc_html_e( 'All rights reserved.', 'somali-focus' ); ?>
				</p>
				<nav class="footer-legal" aria-label="<?php esc_attr_e( 'Legal', 'somali-focus' ); ?>">
					<?php
					if ( has_nav_menu( 'legal' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'legal',
								'container'      => false,
								'menu_class'     => 'footer-legal__menu',
								'depth'          => 1,
							)
						);
					} else {
						$privacy = get_privacy_policy_url();
						$terms   = get_page_by_path( 'terms' );
						echo '<ul class="footer-legal__menu">';
						if ( $privacy ) {
							echo '<li><a href="' . esc_url( $privacy ) . '">' . esc_html__( 'Privacy Policy', 'somali-focus' ) . '</a></li>';
						}
						if ( $terms ) {
							echo '<li><a href="' . esc_url( get_permalink( $terms ) ) . '">' . esc_html__( 'Terms & Conditions', 'somali-focus' ) . '</a></li>';
						}
						echo '</ul>';
					}
					?>
				</nav>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
