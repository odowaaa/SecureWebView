<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="somca-container somca-section" style="text-align:center; max-width:640px;">
	<span class="somca-eyebrow"><?php esc_html_e( '404', 'somca' ); ?></span>
	<h1><?php esc_html_e( 'This location isn’t on the map.', 'somca' ); ?></h1>
	<p><?php esc_html_e( 'The page you’re looking for has moved or no longer exists. Try searching, or explore our climate hotspots and reports below.', 'somca' ); ?></p>
	<?php get_search_form(); ?>
	<div class="somca-hero-actions" style="justify-content:center; margin-top:1.5em;">
		<a class="somca-btn somca-btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'somca' ); ?></a>
		<a class="somca-btn somca-btn-outline" href="<?php echo esc_url( get_post_type_archive_link( 'somca_hotspot' ) ); ?>"><?php esc_html_e( 'View Hotspots', 'somca' ); ?></a>
	</div>
</div>
<?php get_footer(); ?>
