<?php
/**
 * "Our Approach" five-step process — horizontal on desktop, vertical
 * timeline on mobile (handled purely in CSS).
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_steps = array(
	array( 'number' => '01', 'icon' => 'compass', 'title' => __( 'Understand', 'somali-focus' ), 'text' => __( 'We understand the context, challenge and objectives.', 'somali-focus' ) ),
	array( 'number' => '02', 'icon' => 'chart', 'title' => __( 'Analyze', 'somali-focus' ), 'text' => __( 'We examine available information, evidence and needs.', 'somali-focus' ) ),
	array( 'number' => '03', 'icon' => 'design', 'title' => __( 'Design', 'somali-focus' ), 'text' => __( 'We develop practical and context-specific solutions.', 'somali-focus' ) ),
	array( 'number' => '04', 'icon' => 'deliver', 'title' => __( 'Deliver', 'somali-focus' ), 'text' => __( 'We implement quality training, advisory or research services.', 'somali-focus' ) ),
	array( 'number' => '05', 'icon' => 'measure', 'title' => __( 'Measure', 'somali-focus' ), 'text' => __( 'We assess results and identify opportunities for improvement.', 'somali-focus' ) ),
);
?>
<section class="approach-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'How We Work', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Our Approach', 'somali-focus' ); ?></h2>
		</header>

		<ol class="approach-steps">
			<?php foreach ( $sf_steps as $sf_step ) : ?>
				<li class="approach-step">
					<span class="approach-step__number"><?php echo esc_html( $sf_step['number'] ); ?></span>
					<span class="approach-step__icon"><?php sf_icon( $sf_step['icon'] ); ?></span>
					<h3 class="approach-step__title"><?php echo esc_html( $sf_step['title'] ); ?></h3>
					<p class="approach-step__text"><?php echo esc_html( $sf_step['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
