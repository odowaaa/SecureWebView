<?php
/**
 * Homepage welcome video — a click-to-play facade (poster image only
 * until the visitor presses play) so no video-platform JS or iframe
 * loads on page load. Renders nothing if no video URL is configured.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_video_url = sf_theme_option( 'welcome_video_url' );
if ( ! $sf_video_url ) {
	return;
}

$sf_embed = sf_video_embed_src( $sf_video_url );
if ( ! $sf_embed ) {
	return;
}

$sf_poster_id  = sf_theme_option( 'welcome_video_poster_id' );
$sf_poster_url = $sf_poster_id ? wp_get_attachment_image_url( $sf_poster_id, 'sf-hero' ) : '';
?>
<section class="video-section sf-reveal" aria-label="<?php esc_attr_e( 'Welcome video', 'somali-focus' ); ?>">
	<div class="container container--narrow">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( '60 Seconds', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'What Is Somali Focus?', 'somali-focus' ); ?></h2>
		</header>

		<div
			class="video-facade<?php echo $sf_poster_url ? '' : ' video-facade--no-poster'; ?>"
			data-sf-video-facade
			data-embed="<?php echo esc_attr( $sf_embed['html'] ); ?>"
			role="button"
			tabindex="0"
			aria-label="<?php esc_attr_e( 'Play welcome video', 'somali-focus' ); ?>"
			<?php if ( $sf_poster_url ) : ?>style="background-image:url('<?php echo esc_url( $sf_poster_url ); ?>');"<?php endif; ?>
		>
			<span class="video-facade__play" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
			</span>
		</div>
	</div>
</section>
