<?php
/**
 * Fallback single template for an Expert profile.
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
			<p><?php echo esc_html( somali_focus_meta( $post_id, 'position', '' ) ); ?></p>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sf-portrait' ); ?>
		<?php endif; ?>

		<div class="sf-fallback__content"><?php the_content(); ?></div>

		<ul class="sf-fallback-single__meta">
			<li><strong><?php esc_html_e( 'Education', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'education', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Experience', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'experience', '—' ) ); ?></li>
		</ul>

		<p>
			<?php
			$linkedin = somali_focus_meta( $post_id, 'linkedin' );
			$email    = somali_focus_meta( $post_id, 'email' );
			$website  = somali_focus_meta( $post_id, 'website' );
			if ( $linkedin ) {
				echo '<a href="' . esc_url( $linkedin ) . '" target="_blank" rel="noopener noreferrer">LinkedIn</a> ';
			}
			if ( $email ) {
				echo '<a href="' . esc_url( 'mailto:' . $email ) . '">Email</a> ';
			}
			if ( $website ) {
				echo '<a href="' . esc_url( $website ) . '" target="_blank" rel="noopener noreferrer">Website</a>';
			}
			?>
		</p>
	</main>
	<?php
endwhile;
get_footer();
