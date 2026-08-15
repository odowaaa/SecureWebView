<?php
/**
 * Fallback single template for an Advisory Project.
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
			<li><strong><?php esc_html_e( 'Client / Sector', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'client_sector', '—' ) ); ?></li>
			<li><strong><?php esc_html_e( 'Project Date', 'somali-focus' ); ?></strong><?php echo esc_html( somali_focus_meta( $post_id, 'project_date', '—' ) ); ?></li>
		</ul>

		<?php
		$sections = array(
			'challenge' => __( 'The Challenge', 'somali-focus' ),
			'approach'  => __( 'Our Approach', 'somali-focus' ),
			'results'   => __( 'Results', 'somali-focus' ),
		);
		foreach ( $sections as $key => $label ) :
			$value = somali_focus_meta( $post_id, $key );
			if ( ! $value ) {
				continue;
			}
			?>
			<h2><?php echo esc_html( $label ); ?></h2>
			<div class="sf-fallback__content"><?php echo wp_kses_post( wpautop( $value ) ); ?></div>
			<?php
		endforeach;
		?>

		<div class="sf-fallback__content"><?php the_content(); ?></div>
	</main>
	<?php
endwhile;
get_footer();
