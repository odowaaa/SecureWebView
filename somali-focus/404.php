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
		<nav class="sf-404-links" aria-label="<?php esc_attr_e( 'Popular pages', 'somali-focus' ); ?>" style="margin-top:2.5rem;">
			<p style="font-weight:600;color:var(--sf-navy);"><?php esc_html_e( 'Or try one of these popular pages:', 'somali-focus' ); ?></p>
			<ul style="list-style:none;display:flex;flex-wrap:wrap;justify-content:center;gap:.75rem 1.5rem;padding:0;margin:.75rem 0 0;">
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'somali-focus' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/training/' ) ); ?>"><?php esc_html_e( 'Training', 'somali-focus' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/advisory/' ) ); ?>"><?php esc_html_e( 'Advisory', 'somali-focus' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/research/' ) ); ?>"><?php esc_html_e( 'Research', 'somali-focus' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>"><?php esc_html_e( 'Insights', 'somali-focus' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'somali-focus' ); ?></a></li>
			</ul>
		</nav>
	</div>
</section>
<?php
get_footer();
