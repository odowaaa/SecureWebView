<?php
/**
 * Template Name: Training Page
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
		'eyebrow'     => __( 'Training', 'somali-focus' ),
		'title'       => __( 'Professional Training for a Changing World', 'somali-focus' ),
		'description' => __( 'Building knowledge, skills and professional capacity through practical, results-focused training programs.', 'somali-focus' ),
	)
);
?>

<section class="page-content-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Training Areas', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'What We Train On', 'somali-focus' ); ?></h2>
		</header>
		<div class="sectors-grid">
			<?php
			$sf_areas = array(
				__( 'Leadership & Management', 'somali-focus' ),
				__( 'Project Management', 'somali-focus' ),
				__( 'Monitoring & Evaluation', 'somali-focus' ),
				__( 'Research Methods', 'somali-focus' ),
				__( 'Data Analysis', 'somali-focus' ),
				__( 'Human Resources', 'somali-focus' ),
				__( 'Finance & Administration', 'somali-focus' ),
				__( 'Procurement & Supply Chain', 'somali-focus' ),
				__( 'Communication & Professional Skills', 'somali-focus' ),
				__( 'Organizational Development', 'somali-focus' ),
				__( 'Digital Skills', 'somali-focus' ),
				__( 'Customized Organizational Training', 'somali-focus' ),
			);
			foreach ( $sf_areas as $sf_area ) :
				?>
				<div class="sector-card">
					<span class="sector-card__icon"><?php sf_icon( 'training' ); ?></span>
					<span class="sector-card__title"><?php echo esc_html( $sf_area ); ?></span>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<?php
$sf_courses = sf_theme_get_courses( 9 );
if ( ! empty( $sf_courses ) ) :
	?>
	<section class="content-highlight content-highlight--tinted sf-reveal">
		<div class="container">
			<header class="section-header">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Schedule', 'somali-focus' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Upcoming Training', 'somali-focus' ); ?></h2>
				</div>
			</header>
			<div class="card-grid card-grid--3">
				<?php foreach ( $sf_courses as $sf_course ) : ?>
					<?php get_template_part( 'template-parts/course-card', null, array( 'post' => $sf_course ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
else :
	?>
	<section class="content-highlight sf-reveal">
		<div class="container">
			<p><?php esc_html_e( 'New training dates are announced regularly — contact us to be notified or to arrange a customized session for your organization.', 'somali-focus' ); ?></p>
		</div>
	</section>
	<?php
endif;
?>

<section class="page-content-section sf-reveal">
	<div class="container">
		<header class="section-header section-header--center">
			<p class="eyebrow"><?php esc_html_e( 'Formats', 'somali-focus' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Training Formats', 'somali-focus' ); ?></h2>
		</header>
		<div class="feature-grid">
			<?php
			$sf_formats = array(
				array( 'icon' => 'users', 'title' => __( 'Individual Training', 'somali-focus' ), 'text' => __( 'Open-enrollment courses for individual professionals building new skills.', 'somali-focus' ) ),
				array( 'icon' => 'building', 'title' => __( 'Corporate Training', 'somali-focus' ), 'text' => __( 'In-house programs designed around your team\'s specific goals.', 'somali-focus' ) ),
				array( 'icon' => 'design', 'title' => __( 'Customized Training', 'somali-focus' ), 'text' => __( 'Curricula tailored to your organization\'s sector, tools and context.', 'somali-focus' ) ),
				array( 'icon' => 'compass', 'title' => __( 'Workshops & Capacity Development', 'somali-focus' ), 'text' => __( 'Focused, short-form workshops for teams and networks.', 'somali-focus' ) ),
			);
			foreach ( $sf_formats as $sf_format ) :
				?>
				<div class="feature-card">
					<span class="feature-card__icon"><?php sf_icon( $sf_format['icon'] ); ?></span>
					<h3 class="feature-card__title"><?php echo esc_html( $sf_format['title'] ); ?></h3>
					<p class="feature-card__text"><?php echo esc_html( $sf_format['text'] ); ?></p>
				</div>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<section class="page-content-section content-highlight--tinted sf-reveal">
	<div class="container container--narrow">
		<h2 class="section-title"><?php esc_html_e( 'Why Train With Somali Focus?', 'somali-focus' ); ?></h2>
		<ul class="checklist">
			<?php
			$sf_reasons = array(
				__( 'Experienced trainers with real sector expertise', 'somali-focus' ),
				__( 'Practical, application-focused curricula', 'somali-focus' ),
				__( 'Flexible in-person, online and hybrid delivery', 'somali-focus' ),
				__( 'Training grounded in research and evidence', 'somali-focus' ),
				__( 'Customization for your organization\'s context', 'somali-focus' ),
			);
			foreach ( $sf_reasons as $sf_reason ) :
				?>
				<li><?php sf_icon( 'check' ); ?><?php echo esc_html( $sf_reason ); ?></li>
				<?php
			endforeach;
			?>
		</ul>
	</div>
</section>

<section id="sf-request" class="page-content-section sf-reveal">
	<div class="container container--narrow">
		<header class="section-header section-header--center">
			<h2 class="section-title"><?php esc_html_e( 'Request a Training', 'somali-focus' ); ?></h2>
			<p class="section-description"><?php esc_html_e( 'Tell us what you need and our team will follow up with available options.', 'somali-focus' ); ?></p>
		</header>
		<div data-sf-preselect-service="training">
			<?php sf_theme_service_request_form(); ?>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php get_footer(); ?>
