<?php
/**
 * Template Name: Advisory Page
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'     => __( 'Advisory', 'somali-focus' ),
		'title'       => __( 'Practical Expertise. Better Decisions. Stronger Organizations.', 'somali-focus' ),
		'description' => __( 'Professional consulting and advisory services for organizations, businesses, NGOs, government institutions and development partners.', 'somali-focus' ),
	)
);
?>

<section class="page-content-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Service Areas', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Advisory Services', 'somali-focus' ); ?></h2>
		</header>
		<div class="sectors-grid">
			<?php
			$sf_areas = array(
				__( 'Strategic Planning', 'somali-focus' ),
				__( 'Organizational Development', 'somali-focus' ),
				__( 'Project Management', 'somali-focus' ),
				__( 'Monitoring & Evaluation', 'somali-focus' ),
				__( 'Human Resources', 'somali-focus' ),
				__( 'Business Development', 'somali-focus' ),
				__( 'Institutional Strengthening', 'somali-focus' ),
				__( 'Policy & Program Advisory', 'somali-focus' ),
				__( 'Data & Information Management', 'somali-focus' ),
				__( 'Digital Transformation', 'somali-focus' ),
			);
			foreach ( $sf_areas as $sf_area ) :
				?>
				<div class="sector-card">
					<span class="sector-card__icon"><?php sf_icon( 'advisory' ); ?></span>
					<span class="sector-card__title"><?php echo esc_html( $sf_area ); ?></span>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<?php
$sf_projects = sf_theme_get_advisory_projects( 9 );
if ( ! empty( $sf_projects ) ) :
	?>
	<section class="content-highlight content-highlight--tinted sf-reveal">
		<div class="container">
			<header class="section-header">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Recent Work', 'somali-focus' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Advisory Projects', 'somali-focus' ); ?></h2>
				</div>
			</header>
			<div class="card-grid card-grid--3">
				<?php foreach ( $sf_projects as $sf_project ) : ?>
					<?php get_template_part( 'template-parts/advisory-card', null, array( 'post' => $sf_project ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;
?>

<?php get_template_part( 'template-parts/approach' ); ?>

<?php get_template_part( 'template-parts/sectors' ); ?>

<section id="sf-request" class="page-content-section sf-reveal">
	<div class="container container--narrow">
		<header class="section-header section-header--center">
			<h2 class="section-title"><?php esc_html_e( 'Request Advisory Support', 'somali-focus' ); ?></h2>
			<p class="section-description"><?php esc_html_e( 'Tell us about your organization\'s challenge and our advisory team will follow up.', 'somali-focus' ); ?></p>
		</header>
		<div data-sf-preselect-service="advisory">
			<?php sf_theme_service_request_form(); ?>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php get_footer(); ?>
