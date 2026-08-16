<?php
/**
 * An Insights (blog) post card. Uses the current loop post unless
 * $args['post'] is explicitly provided.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_post = isset( $args['post'] ) && $args['post'] instanceof WP_Post ? $args['post'] : get_post();
if ( ! $sf_post ) {
	return;
}

$sf_categories = get_the_category( $sf_post );
$sf_category   = ! empty( $sf_categories ) ? $sf_categories[0]->name : '';
?>
<article class="card blog-card sf-reveal">
	<a class="card__media" href="<?php echo esc_url( get_permalink( $sf_post ) ); ?>">
		<?php if ( has_post_thumbnail( $sf_post ) ) : ?>
			<?php echo get_the_post_thumbnail( $sf_post, 'sf-card', array( 'loading' => 'lazy', 'alt' => get_the_title( $sf_post ) ) ); ?>
		<?php else : ?>
			<span class="card__media-fallback"><?php sf_icon( 'book' ); ?></span>
		<?php endif; ?>
		<?php if ( $sf_category ) : ?><span class="card__badge"><?php echo esc_html( $sf_category ); ?></span><?php endif; ?>
	</a>
	<div class="card__body">
		<p class="card__meta card__meta--single">
			<?php sf_icon( 'calendar' ); ?><?php echo esc_html( get_the_date( '', $sf_post ) ); ?>
			<span class="card__meta-dot">&middot;</span>
			<?php
			/* translators: %d: reading time in minutes */
			printf( esc_html__( '%d min read', 'somali-focus' ), (int) sf_reading_time( $sf_post ) );
			?>
		</p>
		<h3 class="card__title"><a href="<?php echo esc_url( get_permalink( $sf_post ) ); ?>"><?php echo esc_html( get_the_title( $sf_post ) ); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html( sf_trim_words( get_the_excerpt( $sf_post ), 18 ) ); ?></p>
		<a class="card__link" href="<?php echo esc_url( get_permalink( $sf_post ) ); ?>"><?php esc_html_e( 'Read More', 'somali-focus' ); ?> <?php sf_icon( 'arrow-right' ); ?></a>
	</div>
</article>
