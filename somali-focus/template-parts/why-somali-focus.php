<?php
/**
 * "Why Somali Focus" — the differentiators that separate the
 * organization from a generic training/consulting vendor.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_reasons = array(
	array( 'icon' => 'users', 'title' => __( 'Experienced Team', 'somali-focus' ), 'text' => __( 'Trainers, advisors and researchers with real sector experience, not generalists.', 'somali-focus' ) ),
	array( 'icon' => 'research', 'title' => __( 'Evidence-Based', 'somali-focus' ), 'text' => __( 'Every recommendation is grounded in data, not assumption.', 'somali-focus' ) ),
	array( 'icon' => 'compass', 'title' => __( 'Practical & Contextual', 'somali-focus' ), 'text' => __( 'Solutions built for the realities our clients actually operate in.', 'somali-focus' ) ),
	array( 'icon' => 'check', 'title' => __( 'Trusted Partner', 'somali-focus' ), 'text' => __( 'We work alongside our clients from understanding through measurement.', 'somali-focus' ) ),
);
?>
<section class="why-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'The Difference', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Why Somali Focus', 'somali-focus' ); ?></h2>
		</header>
		<div class="why-grid">
			<?php foreach ( $sf_reasons as $sf_reason ) : ?>
				<div class="why-card">
					<span class="why-card__icon"><?php sf_icon( $sf_reason['icon'] ); ?></span>
					<h3 class="why-card__title"><?php echo esc_html( $sf_reason['title'] ); ?></h3>
					<p class="why-card__text"><?php echo esc_html( $sf_reason['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
