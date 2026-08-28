<?php
/**
 * The header for the SomCA theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="theme-color" content="#1a1a1a" />
	<?php if ( is_front_page() ) : ?>
		<meta name="description" content="<?php esc_attr_e( 'Somali Climate Action (SomCA) monitors and advocates on climate change hotspots across Somalia and worldwide — live hotspot maps, weekly climate data, and early-warning alerts.', 'somca' ); ?>" />
	<?php endif; ?>
	<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.jpg' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#somca-content"><?php esc_html_e( 'Skip to content', 'somca' ); ?></a>

<?php
$active_alerts = function_exists( 'somca_get_active_alerts' ) ? somca_get_active_alerts( 3 ) : array();
if ( ! empty( $active_alerts ) ) :
	?>
	<div class="somca-topbar">
		<div class="somca-container somca-topbar-inner">
			<span class="somca-topbar-label"><?php esc_html_e( 'Active Alerts:', 'somca' ); ?></span>
			<div class="somca-topbar-alerts">
				<?php foreach ( $active_alerts as $alert ) :
					$severity = get_post_meta( $alert->ID, 'somca_severity', true ) ?: 'advisory';
					?>
					<a class="somca-topbar-alert somca-severity-<?php echo esc_attr( $severity ); ?>" href="<?php echo esc_url( get_permalink( $alert ) ); ?>"><?php echo esc_html( get_the_title( $alert ) ); ?></a>
				<?php endforeach; ?>
			</div>
			<a class="somca-topbar-all" href="<?php echo esc_url( get_post_type_archive_link( 'somca_alert' ) ); ?>"><?php esc_html_e( 'View all alerts →', 'somca' ); ?></a>
		</div>
	</div>
<?php endif; ?>

<header id="masthead" class="somca-header">
	<div class="somca-container somca-header-inner">
		<div class="somca-branding">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="somca-logo-link" rel="home">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					<img class="somca-logo-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.jpg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
				<?php endif; ?>
				<span class="somca-logo-text">
					<span class="somca-logo-title"><span class="somca-black">Som</span><span class="somca-red">CA</span></span>
					<span class="somca-logo-sub"><?php esc_html_e( 'Somali Climate Action', 'somca' ); ?></span>
				</span>
			</a>
		</div>

		<nav id="site-navigation" class="somca-nav" aria-label="<?php esc_attr_e( 'Primary', 'somca' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'somca-nav-menu',
				) );
			} else {
				somca_fallback_menu();
			}
			?>
		</nav>

		<div class="somca-header-actions">
			<a class="somca-btn somca-btn-primary" href="#somca-subscribe"><?php esc_html_e( 'Get Weekly Updates', 'somca' ); ?></a>
			<button class="somca-menu-toggle" id="somca-menu-toggle" aria-controls="site-navigation" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>
</header>

<div id="somca-content" class="somca-site-content">
