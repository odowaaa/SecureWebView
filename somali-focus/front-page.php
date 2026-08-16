<?php
/**
 * The homepage: hero → welcome video (if configured) → who we are →
 * three core services → stats → training, advisory & research
 * highlights → why Somali Focus → approach → sectors → insights →
 * experts → partners → testimonials (if any) → final CTA.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/welcome-video' );
sf_ad_slot( 'after_hero' );

get_template_part( 'template-parts/about', null, array( 'compact' => true ) );
?>

<section class="core-services sf-reveal" aria-label="<?php esc_attr_e( 'Our Core Services', 'somali-focus' ); ?>">
	<div class="container core-services__grid">
		<?php
		get_template_part(
			'template-parts/service-card',
			null,
			array(
				'number'      => '01',
				'icon'        => 'training',
				'title'       => __( 'Training', 'somali-focus' ),
				'description' => __( 'Building knowledge, skills and professional capacity.', 'somali-focus' ),
				'url'         => home_url( '/training/' ),
				'cta_text'    => __( 'Explore Training', 'somali-focus' ),
				'accent'      => 'blue',
			)
		);
		get_template_part(
			'template-parts/service-card',
			null,
			array(
				'number'      => '02',
				'icon'        => 'advisory',
				'title'       => __( 'Advisory', 'somali-focus' ),
				'description' => __( 'Practical expertise to help organizations solve complex challenges.', 'somali-focus' ),
				'url'         => home_url( '/advisory/' ),
				'cta_text'    => __( 'Explore Advisory', 'somali-focus' ),
				'accent'      => 'red',
			)
		);
		get_template_part(
			'template-parts/service-card',
			null,
			array(
				'number'      => '03',
				'icon'        => 'research',
				'title'       => __( 'Research', 'somali-focus' ),
				'description' => __( 'Generating reliable evidence for better decisions.', 'somali-focus' ),
				'url'         => home_url( '/research/' ),
				'cta_text'    => __( 'Explore Research', 'somali-focus' ),
				'accent'      => 'blue',
			)
		);
		?>
	</div>
</section>

<?php get_template_part( 'template-parts/stats' ); ?>

<?php
$sf_courses = sf_theme_get_courses( 3 );
if ( ! empty( $sf_courses ) ) :
	?>
	<section class="content-highlight sf-reveal" aria-label="<?php esc_attr_e( 'Training Highlight', 'somali-focus' ); ?>">
		<div class="container">
			<header class="section-header">
				<div>
					<p class="eyebrow"><?php esc_html_e( '01 — Training', 'somali-focus' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Upcoming & Featured Courses', 'somali-focus' ); ?></h2>
				</div>
				<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/training/' ) ); ?>"><?php esc_html_e( 'View All Training', 'somali-focus' ); ?></a>
			</header>
			<div class="card-grid card-grid--3">
				<?php foreach ( $sf_courses as $sf_course ) : ?>
					<?php get_template_part( 'template-parts/course-card', null, array( 'post' => $sf_course ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

$sf_advisory = sf_theme_get_advisory_projects( 3 );
if ( ! empty( $sf_advisory ) ) :
	?>
	<section class="content-highlight content-highlight--tinted sf-reveal" aria-label="<?php esc_attr_e( 'Advisory Highlight', 'somali-focus' ); ?>">
		<div class="container">
			<header class="section-header">
				<div>
					<p class="eyebrow"><?php esc_html_e( '02 — Advisory', 'somali-focus' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Advisory Projects', 'somali-focus' ); ?></h2>
				</div>
				<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/advisory/' ) ); ?>"><?php esc_html_e( 'Explore Advisory', 'somali-focus' ); ?></a>
			</header>
			<div class="card-grid card-grid--3">
				<?php foreach ( $sf_advisory as $sf_project ) : ?>
					<?php get_template_part( 'template-parts/advisory-card', null, array( 'post' => $sf_project ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

$sf_research = sf_theme_get_research( 3 );
if ( ! empty( $sf_research ) ) :
	?>
	<section class="content-highlight sf-reveal" aria-label="<?php esc_attr_e( 'Research Highlight', 'somali-focus' ); ?>">
		<div class="container">
			<header class="section-header">
				<div>
					<p class="eyebrow"><?php esc_html_e( '03 — Research', 'somali-focus' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Featured Research & Publications', 'somali-focus' ); ?></h2>
				</div>
				<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/research/' ) ); ?>"><?php esc_html_e( 'Explore Research', 'somali-focus' ); ?></a>
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

<?php get_template_part( 'template-parts/why-somali-focus' ); ?>

<?php get_template_part( 'template-parts/approach' ); ?>

<?php get_template_part( 'template-parts/sectors' ); ?>

<?php
$sf_insights = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 3, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) );
if ( ! empty( $sf_insights ) ) :
	?>
	<section class="content-highlight content-highlight--tinted sf-reveal" aria-label="<?php esc_attr_e( 'Latest Insights', 'somali-focus' ); ?>">
		<div class="container">
			<header class="section-header">
				<div>
					<p class="eyebrow"><?php esc_html_e( 'Insights', 'somali-focus' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Latest Insights', 'somali-focus' ); ?></h2>
				</div>
				<a class="btn btn--outline" href="<?php echo esc_url( home_url( '/insights/' ) ); ?>"><?php esc_html_e( 'View All Insights', 'somali-focus' ); ?></a>
			</header>
			<div class="card-grid card-grid--3">
				<?php foreach ( $sf_insights as $sf_insight ) : ?>
					<?php get_template_part( 'template-parts/blog-card', null, array( 'post' => $sf_insight ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

get_template_part( 'template-parts/experts-section', null, array( 'count' => 4 ) );

get_template_part( 'template-parts/partners-section', null, array( 'count' => 12 ) );

$sf_testimonials = sf_theme_get_testimonials( 3 );
if ( ! empty( $sf_testimonials ) ) :
	?>
	<section class="testimonials-section sf-reveal" aria-label="<?php esc_attr_e( 'What People Say', 'somali-focus' ); ?>">
		<div class="container">
			<header class="section-header section-header--center">
				<p class="eyebrow"><?php esc_html_e( 'Feedback', 'somali-focus' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'What Our Clients Say', 'somali-focus' ); ?></h2>
			</header>
			<div class="testimonials-grid">
				<?php foreach ( $sf_testimonials as $sf_testimonial ) : ?>
					<?php get_template_part( 'template-parts/testimonial-card', null, array( 'post' => $sf_testimonial ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
endif;

get_template_part( 'template-parts/cta' );

get_footer();
