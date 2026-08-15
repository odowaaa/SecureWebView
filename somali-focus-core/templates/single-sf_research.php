<?php
/**
 * Fallback single template for a Research item.
 *
 * @package SomaliFocusCore
 */

get_header();
while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();
	$pdf_id  = (int) somali_focus_meta( $post_id, 'report_pdf', 0 );
	$pdf_url = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';
	$ext_url = somali_focus_meta( $post_id, 'external_url', '' );
	?>
	<main class="sf-fallback">
		<header class="sf-fallback__header">
			<h1 class="sf-fallback__title"><?php the_title(); ?></h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large' ); ?>
		<?php endif; ?>

		<ul class="sf-fallback-single__meta">
			<li><strong><?php esc_html_e( 'Authors', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'authors', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Publication Date', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'publication_date', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Methodology', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'methodology', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Keywords', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'keywords', '—' ) ); ?></li>
		</ul>

		<?php $abstract = somali_focus_meta( $post_id, 'abstract' ); ?>
		<?php if ( $abstract ) : ?>
			<h2><?php esc_html_e( 'Abstract', 'somali-focus' ); ?></h2>
			<p><?php echo esc_html( $abstract ); ?></p>
		<?php endif; ?>

		<div class="sf-fallback__content"><?php the_content(); ?></div>

		<?php if ( $pdf_url ) : ?>
			<p><a class="sf-btn sf-btn--primary" href="<?php echo esc_url( $pdf_url ); ?>" download><?php esc_html_e( 'Download Full Report (PDF)', 'somali-focus' ); ?></a></p>
		<?php elseif ( $ext_url ) : ?>
			<p><a class="sf-btn sf-btn--primary" href="<?php echo esc_url( $ext_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View External Publication', 'somali-focus' ); ?></a></p>
		<?php endif; ?>
	</main>
	<?php
endwhile;
get_footer();
