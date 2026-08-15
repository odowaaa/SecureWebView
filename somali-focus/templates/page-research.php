<?php
/**
 * Template Name: Research Page
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
		'eyebrow'     => __( 'Research', 'somali-focus' ),
		'title'       => __( 'Evidence That Drives Better Decisions.', 'somali-focus' ),
		'description' => __( 'Applied research, assessments, studies, data collection, analysis, evaluations and evidence-based recommendations.', 'somali-focus' ),
	)
);
?>

<section class="page-content-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Services', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Research Services', 'somali-focus' ); ?></h2>
		</header>
		<div class="sectors-grid">
			<?php
			$sf_areas = array(
				__( 'Baseline Studies', 'somali-focus' ),
				__( 'Endline Studies', 'somali-focus' ),
				__( 'Needs Assessments', 'somali-focus' ),
				__( 'Feasibility Studies', 'somali-focus' ),
				__( 'Market Research', 'somali-focus' ),
				__( 'Impact Assessments', 'somali-focus' ),
				__( 'Program Evaluations', 'somali-focus' ),
				__( 'Data Collection', 'somali-focus' ),
				__( 'Qualitative & Quantitative Research', 'somali-focus' ),
				__( 'Surveys & Data Analysis', 'somali-focus' ),
			);
			foreach ( $sf_areas as $sf_area ) :
				?>
				<div class="sector-card">
					<span class="sector-card__icon"><?php sf_icon( 'research' ); ?></span>
					<span class="sector-card__title"><?php echo esc_html( $sf_area ); ?></span>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<section class="page-content-section content-highlight--tinted sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Our Process', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Research Methodology', 'somali-focus' ); ?></h2>
		</header>
		<div class="feature-grid">
			<?php
			$sf_methods = array(
				array( 'icon' => 'compass', 'title' => __( 'Design', 'somali-focus' ), 'text' => __( 'Clear research questions, methodology and sampling frameworks.', 'somali-focus' ) ),
				array( 'icon' => 'users', 'title' => __( 'Data Collection', 'somali-focus' ), 'text' => __( 'Rigorous field data collection using trained enumerators.', 'somali-focus' ) ),
				array( 'icon' => 'chart', 'title' => __( 'Analysis', 'somali-focus' ), 'text' => __( 'Statistical and qualitative analysis producing reliable findings.', 'somali-focus' ) ),
				array( 'icon' => 'book', 'title' => __( 'Reporting', 'somali-focus' ), 'text' => __( 'Clear, actionable reports and evidence-based recommendations.', 'somali-focus' ) ),
			);
			foreach ( $sf_methods as $sf_method ) :
				?>
				<div class="feature-card">
					<span class="feature-card__icon"><?php sf_icon( $sf_method['icon'] ); ?></span>
					<h3 class="feature-card__title"><?php echo esc_html( $sf_method['title'] ); ?></h3>
					<p class="feature-card__text"><?php echo esc_html( $sf_method['text'] ); ?></p>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<?php
$sf_research = sf_theme_get_research( 9 );
if ( ! empty( $sf_research ) ) :
	?>
	<section class="content-highlight sf-reveal">
		<div class="container">
			<header class="section-header">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Publications', 'somali-focus' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Research & Publications', 'somali-focus' ); ?></h2>
				</div>
			</header>
			<div class="card-grid card-grid--3">
				<?php foreach ( $sf_research as $sf_item ) : ?>
					<?php get_template_part( 'template-parts/research-card', null, array( 'post' => $sf_item ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;
?>

<section id="sf-request" class="page-content-section sf-reveal">
	<div class="container container--narrow">
		<header class="section-header section-header--center">
			<h2 class="section-title"><?php esc_html_e( 'Discuss a Research Project', 'somali-focus' ); ?></h2>
			<p class="section-description"><?php esc_html_e( 'Tell us about the study or evaluation you need and our research team will follow up.', 'somali-focus' ); ?></p>
		</header>
		<div data-sf-preselect-service="research">
			<?php sf_theme_service_request_form(); ?>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php get_footer(); ?>
