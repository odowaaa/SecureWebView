<?php
/**
 * The header: skip link, site branding, sticky primary navigation.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'somali-focus' ); ?></a>

<header id="masthead" class="site-header" data-sf-header>
	<div class="site-header__inner container">
		<div class="site-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-branding__link" rel="home">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<img
						class="custom-logo sf-default-logo"
						src="<?php echo esc_url( SOMALI_FOCUS_URI . '/assets/images/logo-horizontal.png' ); ?>"
						alt="<?php echo esc_attr( sf_theme_option( 'org_name', get_bloginfo( 'name' ) ) ); ?>"
						width="781" height="120"
					>
				<?php endif; ?>
			</a>
		</div>

		<nav id="primary-navigation" class="primary-navigation" aria-label="<?php esc_attr_e( 'Primary', 'somali-focus' ); ?>">
			<?php wp_nav_menu( sf_primary_menu_args() ); ?>
		</nav>

		<div class="site-header__actions">
			<a class="btn btn--primary btn--sm site-header__cta" href="<?php echo esc_url( sf_header_cta_url() ); ?>">
				<?php echo esc_html( get_theme_mod( 'sf_header_cta_text', __( 'Request a Service', 'somali-focus' ) ) ); ?>
			</a>
			<button type="button" class="menu-toggle" aria-controls="mobile-navigation" aria-expanded="false" data-sf-menu-toggle>
				<span class="menu-toggle__box" aria-hidden="true"><span></span><span></span><span></span></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'somali-focus' ); ?></span>
			</button>
		</div>
	</div>
</header>

<?php get_template_part( 'template-parts/mobile-nav' ); ?>

<div id="page" class="site">
	<main id="primary" class="site-main">
