<?php
/**
 * A single research/publication card. Expected $args: post (WP_Post).
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_post = isset( $args['post'] ) ? $args['post'] : null;
if ( ! $sf_post instanceof WP_Post ) {
	return;
}

$sf_permalink = get_permalink( $sf_post );
$sf_terms     = get_the_terms( $sf_post, 'research_category' );
$sf_category  = ( $sf_terms && ! is_wp_error( $sf_terms ) ) ? $sf_terms[0]->name : '';
$sf_date      = sf_theme_meta( $sf_post, 'publication_date' );
$sf_abstract  = sf_theme_meta( $sf_post, 'abstract' );
$sf_pdf_id    = (int) sf_theme_meta( $sf_post, 'report_pdf', 0 );
?>
<article class="card research-card sf-reveal">
	<a class="card__media" href="<?php echo esc_url( $sf_permalink ); ?>">
		<?php if ( has_post_thumbnail( $sf_post ) ) : ?>
			<?php echo get_the_post_thumbnail( $sf_post, 'sf-card', array( 'loading' => 'lazy', 'alt' => get_the_title( $sf_post ) ) ); ?>
		<?php else : ?>
			<span class="card__media-fallback"><?php sf_icon( 'research' ); ?></span>
		<?php endif; ?>
		<?php if ( $sf_category ) : ?><span class="card__badge"><?php echo esc_html( $sf_category ); ?></span><?php endif; ?>
	</a>
	<div class="card__body">
		<?php if ( $sf_date ) : ?><p class="card__meta card__meta--single"><?php sf_icon( 'calendar' ); ?><?php echo esc_html( sf_format_date( $sf_date ) ); ?></p><?php endif; ?>
		<h3 class="card__title"><a href="<?php echo esc_url( $sf_permalink ); ?>"><?php echo esc_html( get_the_title( $sf_post ) ); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html( sf_trim_words( $sf_abstract, 20 ) ); ?></p>
		<a class="card__link" href="<?php echo esc_url( $sf_permalink ); ?>">
			<?php echo $sf_pdf_id ? esc_html__( 'Download Report', 'somali-focus' ) : esc_html__( 'View Publication', 'somali-focus' ); ?>
			<?php sf_icon( $sf_pdf_id ? 'download' : 'arrow-right' ); ?>
		</a>
	</div>
</article>
