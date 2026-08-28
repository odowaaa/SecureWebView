<?php
/**
 * The footer for the SomCA theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</div><!-- #somca-content -->

<footer id="colophon" class="somca-footer">
	<div class="somca-footer-cta" id="somca-subscribe">
		<div class="somca-container somca-footer-cta-inner">
			<div>
				<h3><?php esc_html_e( 'Weekly Climate Briefing', 'somca' ); ?></h3>
				<p><?php esc_html_e( 'Hotspot updates, new alerts, and regional data — straight to your inbox every week.', 'somca' ); ?></p>
			</div>
			<div>
				<form id="somca-subscribe-form" class="somca-subscribe-form">
					<input type="email" name="email" required placeholder="<?php esc_attr_e( 'you@example.com', 'somca' ); ?>" aria-label="<?php esc_attr_e( 'Email address', 'somca' ); ?>" />
					<input type="text" name="somca_website" class="somca-hp-field" tabindex="-1" autocomplete="off" aria-hidden="true" />
					<button type="submit" class="somca-btn somca-btn-secondary"><?php esc_html_e( 'Subscribe', 'somca' ); ?></button>
				</form>
				<p id="somca-subscribe-message" class="somca-subscribe-message" role="status" aria-live="polite"></p>
			</div>
		</div>
	</div>

	<div class="somca-container somca-footer-columns">
		<div class="somca-footer-brand">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.jpg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="somca-footer-logo" />
			<p><?php esc_html_e( 'Somali Climate Action (SomCA) monitors, advocates, and raises early warning on climate change hotspots across Somalia and worldwide.', 'somca' ); ?></p>
		</div>

		<?php for ( $i = 1; $i <= 4; $i++ ) : if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
			<div class="somca-footer-col">
				<?php dynamic_sidebar( 'footer-' . $i ); ?>
			</div>
		<?php endif; endfor; ?>

		<?php if ( ! is_active_sidebar( 'footer-2' ) ) : ?>
			<div class="somca-footer-col">
				<h4 class="somca-footer-title"><?php esc_html_e( 'Explore', 'somca' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'somca_hotspot' ) ); ?>"><?php esc_html_e( 'Climate Hotspots', 'somca' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'somca_report' ) ); ?>"><?php esc_html_e( 'Climate Reports', 'somca' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'somca_alert' ) ); ?>"><?php esc_html_e( 'Active Alerts', 'somca' ); ?></a></li>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<div class="somca-footer-col">
				<h4 class="somca-footer-title"><?php esc_html_e( 'About', 'somca' ); ?></h4>
				<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'somca-footer-menu' ) ); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="somca-container somca-footer-bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'somca' ); ?></p>
		<p class="somca-footer-tagline"><?php esc_html_e( 'Data: Open-Meteo (ERA5 reanalysis).', 'somca' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
