<?php
/**
 * Fallback single template for a Course.
 *
 * @package SomaliFocusCore
 */

get_header();
while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();
	?>
	<main class="sf-fallback">
		<header class="sf-fallback__header">
			<h1 class="sf-fallback__title"><?php the_title(); ?></h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large' ); ?>
		<?php endif; ?>

		<ul class="sf-fallback-single__meta">
			<li><strong><?php esc_html_e( 'Trainer', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'trainer', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Duration', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'duration', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Location', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'location', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Delivery Mode', 'somali-focus' ); ?></strong><?php echo esc_html( ucfirst( somali_focus_meta( $post_id, 'delivery_mode', '—' ) ) ); ?></li>
			<li><strong><?php esc_html_e( 'Start Date', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'start_date', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Fee', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'fee', '—' ) ); ?></li>
		</ul>

		<div class="sf-fallback__content"><?php the_content(); ?></div>

		<?php echo do_shortcode( '[sf_course_registration_form course_id="' . $post_id . '"]' ); ?>
	</main>
	<?php
endwhile;
get_footer();
