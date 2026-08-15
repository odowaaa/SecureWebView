<?php
/**
 * "Who We Are" / Mission / Vision / Values. Expected $args: compact (bool).
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args( $args ?? array(), array( 'compact' => false ) );

$sf_about   = sf_theme_option( 'about_text', __( 'Somali Focus is a professional Training, Advisory and Research organization committed to strengthening people, organizations and decision-making through practical knowledge, professional expertise and evidence.', 'somali-focus' ) );
$sf_mission = sf_theme_option( 'mission_text', __( 'To strengthen people and organizations through quality training, practical advisory services and credible research.', 'somali-focus' ) );
$sf_vision  = sf_theme_option( 'vision_text', __( 'A more capable, informed and evidence-driven Somalia.', 'somali-focus' ) );

$sf_values = array(
	array( 'icon' => 'check', 'title' => __( 'Integrity', 'somali-focus' ), 'text' => __( 'We act honestly and hold ourselves accountable in every engagement.', 'somali-focus' ) ),
	array( 'icon' => 'compass', 'title' => __( 'Excellence', 'somali-focus' ), 'text' => __( 'We hold our training, advisory and research to a high professional standard.', 'somali-focus' ) ),
	array( 'icon' => 'research', 'title' => __( 'Evidence', 'somali-focus' ), 'text' => __( 'We ground our recommendations in credible data and rigorous analysis.', 'somali-focus' ) ),
	array( 'icon' => 'design', 'title' => __( 'Innovation', 'somali-focus' ), 'text' => __( 'We adapt practical, context-specific approaches to real challenges.', 'somali-focus' ) ),
	array( 'icon' => 'users', 'title' => __( 'Partnership', 'somali-focus' ), 'text' => __( 'We work alongside clients and communities, not apart from them.', 'somali-focus' ) ),
	array( 'icon' => 'chart', 'title' => __( 'Impact', 'somali-focus' ), 'text' => __( 'We focus on outcomes that measurably strengthen people and organizations.', 'somali-focus' ) ),
);
?>
<section class="about-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Who We Are', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'About Somali Focus', 'somali-focus' ); ?></h2>
			<p class="section-description"><?php echo esc_html( $sf_about ); ?></p>
		</header>

		<div class="mission-vision">
			<div class="mission-vision__item">
				<h3><?php esc_html_e( 'Our Mission', 'somali-focus' ); ?></h3>
				<p><?php echo esc_html( $sf_mission ); ?></p>
			</div>
			<div class="mission-vision__item">
				<h3><?php esc_html_e( 'Our Vision', 'somali-focus' ); ?></h3>
				<p><?php echo esc_html( $sf_vision ); ?></p>
			</div>
		</div>

		<?php if ( ! $args['compact'] ) : ?>
			<h3 class="values-heading"><?php esc_html_e( 'Our Values', 'somali-focus' ); ?></h3>
			<div class="values-grid">
				<?php foreach ( $sf_values as $sf_value ) : ?>
					<div class="value-card">
						<span class="value-card__icon"><?php sf_icon( $sf_value['icon'] ); ?></span>
						<h4 class="value-card__title"><?php echo esc_html( $sf_value['title'] ); ?></h4>
						<p class="value-card__text"><?php echo esc_html( $sf_value['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
