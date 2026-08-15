<?php
/**
 * 404 error page.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<section class="page-hero sf-reveal">
	<div class="container container--narrow" style="text-align:center;">
		<p class="eyebrow"><?php esc_html_e( '404', 'somali-focus' ); ?></p>
		<h1 class="page-hero__title"><?php esc_html_e( 'Page Not Found', 'somali-focus' ); ?></h1>
		<p class="page-hero__description"><?php esc_html_e( "The page you're looking for doesn't exist or may have moved.", 'somali-focus' ); ?></p>
		<p style="margin-top:2rem;">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Homepage', 'somali-focus' ); ?></a>
		</p>
		<div style="max-width:480px;margin:2rem auto 0;">
			<?php get_search_form(); ?>
		</div>
	</div>
</section>
<?php
get_footer();
