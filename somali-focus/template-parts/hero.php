<?php
/**
 * Homepage hero — headline, supporting text, two CTAs, abstract visual.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_hero_title       = sf_theme_option( 'hero_title', __( 'Empowering People. Strengthening Organizations. Generating Evidence.', 'somali-focus' ) );
$sf_hero_description = sf_theme_option( 'hero_description', __( 'Somali Focus provides professional Training, Advisory and Research services designed to strengthen individual and organizational capacity and support evidence-based decision making.', 'somali-focus' ) );
$sf_btn_primary_text  = sf_theme_option( 'hero_button_primary_text', __( 'Explore Our Services', 'somali-focus' ) );
$sf_btn_primary_url   = sf_theme_option( 'hero_button_primary_url' ) ? sf_theme_option( 'hero_button_primary_url' ) : home_url( '/services/' );
$sf_btn_secondary_text = sf_theme_option( 'hero_button_secondary_text', __( 'Start a Conversation', 'somali-focus' ) );
$sf_btn_secondary_url  = sf_theme_option( 'hero_button_secondary_url' ) ? sf_theme_option( 'hero_button_secondary_url' ) : home_url( '/contact/' );
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Introduction', 'somali-focus' ); ?>">
	<div class="hero__pattern" aria-hidden="true">
		<svg viewBox="0 0 800 600" preserveAspectRatio="xMidYMid slice">
			<circle cx="700" cy="80" r="140" fill="var(--sf-blue)" opacity="0.06"/>
			<circle cx="620" cy="420" r="200" fill="var(--sf-red)" opacity="0.05"/>
			<path d="M0 480 L800 380" stroke="var(--sf-blue)" stroke-width="1" opacity="0.08"/>
			<path d="M0 520 L800 440" stroke="var(--sf-blue)" stroke-width="1" opacity="0.08"/>
			<g opacity="0.12">
				<rect x="640" y="180" width="10" height="10" fill="var(--sf-red)"/>
				<rect x="680" y="220" width="10" height="10" fill="var(--sf-blue)"/>
				<rect x="600" y="240" width="10" height="10" fill="var(--sf-red)"/>
			</g>
		</svg>
	</div>

	<div class="container hero__inner">
		<div class="hero__content sf-reveal">
			<p class="eyebrow"><?php esc_html_e( 'Training · Advisory · Research', 'somali-focus' ); ?></p>
			<h1 class="hero__title"><?php echo esc_html( $sf_hero_title ); ?></h1>
			<p class="hero__description"><?php echo esc_html( $sf_hero_description ); ?></p>
			<div class="hero__actions">
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $sf_btn_primary_url ); ?>"><?php echo esc_html( $sf_btn_primary_text ); ?></a>
				<a class="btn btn--outline btn--lg" href="<?php echo esc_url( $sf_btn_secondary_url ); ?>"><?php echo esc_html( $sf_btn_secondary_text ); ?></a>
			</div>
		</div>

		<div class="hero__visual sf-reveal" aria-hidden="true">
			<div class="hero-panel">
				<div class="hero-panel__card hero-panel__card--1">
					<?php sf_icon( 'training' ); ?>
					<span><?php esc_html_e( 'Training', 'somali-focus' ); ?></span>
				</div>
				<div class="hero-panel__card hero-panel__card--2">
					<?php sf_icon( 'advisory' ); ?>
					<span><?php esc_html_e( 'Advisory', 'somali-focus' ); ?></span>
				</div>
				<div class="hero-panel__card hero-panel__card--3">
					<?php sf_icon( 'research' ); ?>
					<span><?php esc_html_e( 'Research', 'somali-focus' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>
