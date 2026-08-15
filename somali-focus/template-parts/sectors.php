<?php
/**
 * "Sectors We Serve" grid.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_sectors = array(
	array( 'icon' => 'globe', 'title' => __( 'NGOs & Development Organizations', 'somali-focus' ) ),
	array( 'icon' => 'building', 'title' => __( 'Government Institutions', 'somali-focus' ) ),
	array( 'icon' => 'briefcase', 'title' => __( 'Private Sector', 'somali-focus' ) ),
	array( 'icon' => 'chart', 'title' => __( 'SMEs & Entrepreneurs', 'somali-focus' ) ),
	array( 'icon' => 'book', 'title' => __( 'Educational Institutions', 'somali-focus' ) ),
	array( 'icon' => 'globe', 'title' => __( 'International Organizations', 'somali-focus' ) ),
	array( 'icon' => 'users', 'title' => __( 'Community Organizations', 'somali-focus' ) ),
	array( 'icon' => 'research', 'title' => __( 'Research Institutions', 'somali-focus' ) ),
);
?>
<section class="sectors-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Who We Work With', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Sectors We Serve', 'somali-focus' ); ?></h2>
		</header>

		<div class="sectors-grid">
			<?php foreach ( $sf_sectors as $sf_sector ) : ?>
				<div class="sector-card">
					<span class="sector-card__icon"><?php sf_icon( $sf_sector['icon'] ); ?></span>
					<span class="sector-card__title"><?php echo esc_html( $sf_sector['title'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
