<?php
/**
 * Single course page: details, outline, registration form.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$sf_id       = get_the_ID();
	$sf_terms    = get_the_terms( $sf_id, 'course_category' );
	$sf_category = ( $sf_terms && ! is_wp_error( $sf_terms ) ) ? $sf_terms[0]->name : '';
	$sf_status   = sf_theme_meta( $sf_id, 'registration_status', 'open' );
	?>
	<article <?php post_class( 'single-course' ); ?>>

		<section class="page-hero sf-reveal">
			<div class="container">
				<?php sf_breadcrumbs(); ?>
				<?php if ( $sf_category ) : ?><p class="eyebrow"><?php echo esc_html( $sf_category ); ?></p><?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
				<?php $sf_short = sf_theme_meta( $sf_id, 'short_description' ); ?>
				<?php if ( $sf_short ) : ?><p class="page-hero__description"><?php echo esc_html( $sf_short ); ?></p><?php endif; ?>
			</div>
		</section>

		<section class="page-content-section sf-reveal">
			<div class="container single-layout">
				<div class="single-layout__main entry-content">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="single-course__image"><?php the_post_thumbnail( 'sf-hero' ); ?></div>
					<?php endif; ?>

					<?php the_content(); ?>

					<?php
					$sf_objectives = sf_theme_meta( $sf_id, 'learning_objectives' );
					if ( $sf_objectives ) :
						?>
						<h2><?php esc_html_e( 'Learning Objectives', 'somali-focus' ); ?></h2>
						<ul class="checklist">
							<?php foreach ( array_filter( array_map( 'trim', explode( "\n", $sf_objectives ) ) ) as $sf_line ) : ?>
								<li><?php sf_icon( 'check' ); ?><?php echo esc_html( $sf_line ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php
					$sf_audience = sf_theme_meta( $sf_id, 'target_audience' );
					if ( $sf_audience ) :
						?>
						<h2><?php esc_html_e( 'Target Audience', 'somali-focus' ); ?></h2>
						<p><?php echo esc_html( $sf_audience ); ?></p>
					<?php endif; ?>

					<?php
					$sf_outline = sf_theme_meta( $sf_id, 'course_outline' );
					if ( $sf_outline ) :
						?>
						<h2><?php esc_html_e( 'Course Outline', 'somali-focus' ); ?></h2>
						<ol class="outline-list">
							<?php foreach ( array_filter( array_map( 'trim', explode( "\n", $sf_outline ) ) ) as $sf_module ) : ?>
								<li><?php echo esc_html( $sf_module ); ?></li>
							<?php endforeach; ?>
						</ol>
					<?php endif; ?>

					<?php
					$sf_requirements = sf_theme_meta( $sf_id, 'requirements' );
					if ( $sf_requirements ) :
						?>
						<h2><?php esc_html_e( 'Requirements', 'somali-focus' ); ?></h2>
						<p><?php echo esc_html( $sf_requirements ); ?></p>
					<?php endif; ?>
				</div>

				<aside class="single-layout__sidebar">
					<div class="course-summary-card">
						<h2 class="course-summary-card__title"><?php esc_html_e( 'Course Details', 'somali-focus' ); ?></h2>
						<ul class="course-summary-card__list">
							<?php
							$sf_rows = array(
								array( 'clock', __( 'Duration', 'somali-focus' ), sf_theme_meta( $sf_id, 'duration' ) ),
								array( 'location', __( 'Location', 'somali-focus' ), sf_theme_meta( $sf_id, 'location' ) ),
								array( 'compass', __( 'Delivery Mode', 'somali-focus' ), ucfirst( sf_theme_meta( $sf_id, 'delivery_mode' ) ) ),
								array( 'calendar', __( 'Start Date', 'somali-focus' ), sf_format_date( sf_theme_meta( $sf_id, 'start_date' ) ) ),
								array( 'calendar', __( 'End Date', 'somali-focus' ), sf_format_date( sf_theme_meta( $sf_id, 'end_date' ) ) ),
								array( 'users', __( 'Trainer', 'somali-focus' ), sf_theme_meta( $sf_id, 'trainer' ) ),
								array( 'briefcase', __( 'Fee', 'somali-focus' ), sf_theme_meta( $sf_id, 'fee' ) ),
								array( 'calendar', __( 'Registration Deadline', 'somali-focus' ), sf_format_date( sf_theme_meta( $sf_id, 'registration_deadline' ) ) ),
							);
							foreach ( $sf_rows as $sf_row ) :
								if ( empty( $sf_row[2] ) ) {
									continue;
								}
								?>
								<li><?php sf_icon( $sf_row[0] ); ?><span><strong><?php echo esc_html( $sf_row[1] ); ?></strong><br><?php echo esc_html( $sf_row[2] ); ?></span></li>
								<?php
							endforeach;
							?>
						</ul>

						<?php if ( 'open' === $sf_status ) : ?>
							<span class="badge badge--open"><?php esc_html_e( 'Registration Open', 'somali-focus' ); ?></span>
						<?php elseif ( 'full' === $sf_status ) : ?>
							<span class="badge badge--full"><?php esc_html_e( 'Course Full', 'somali-focus' ); ?></span>
						<?php else : ?>
							<span class="badge badge--closed"><?php esc_html_e( 'Registration Closed', 'somali-focus' ); ?></span>
						<?php endif; ?>
					</div>
				</aside>
			</div>
		</section>

		<?php if ( 'open' === $sf_status ) : ?>
			<section class="page-content-section content-highlight--tinted sf-reveal">
				<div class="container container--narrow">
					<h2 class="section-title"><?php esc_html_e( 'Register for This Course', 'somali-focus' ); ?></h2>
					<?php sf_theme_registration_form( $sf_id ); ?>
				</div>
			</section>
		<?php endif; ?>

	</article>
	<?php
endwhile;

get_footer();
