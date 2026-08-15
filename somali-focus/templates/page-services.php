<?php
/**
 * Template Name: Services Overview Page
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
		'eyebrow'     => __( 'What We Do', 'somali-focus' ),
		'title'       => __( 'Our Services', 'somali-focus' ),
		'description' => __( 'Somali Focus provides professional Training, Advisory and Research services designed to strengthen individual and organizational capacity and support evidence-based decision making.', 'somali-focus' ),
	)
);

$sf_service_blocks = array(
	'training' => array(
		'number'   => '01',
		'icon'     => 'training',
		'title'    => __( 'Training', 'somali-focus' ),
		'text'     => __( 'Professional training, capacity development, workshops, courses and organizational learning.', 'somali-focus' ),
		'areas'    => array( __( 'Leadership & Management', 'somali-focus' ), __( 'Project Management', 'somali-focus' ), __( 'Monitoring & Evaluation', 'somali-focus' ), __( 'Research Methods', 'somali-focus' ), __( 'Data Analysis', 'somali-focus' ), __( 'Human Resources', 'somali-focus' ), __( 'Finance & Administration', 'somali-focus' ), __( 'Procurement & Supply Chain', 'somali-focus' ), __( 'Digital Skills', 'somali-focus' ) ),
		'url'      => home_url( '/training/' ),
		'cta'      => __( 'Request a Training', 'somali-focus' ),
	),
	'advisory' => array(
		'number'   => '02',
		'icon'     => 'advisory',
		'title'    => __( 'Advisory', 'somali-focus' ),
		'text'     => __( 'Professional consulting and advisory services for organizations, businesses, NGOs, government institutions and development partners.', 'somali-focus' ),
		'areas'    => array( __( 'Strategic Planning', 'somali-focus' ), __( 'Organizational Development', 'somali-focus' ), __( 'Project Management', 'somali-focus' ), __( 'Monitoring & Evaluation', 'somali-focus' ), __( 'Human Resources', 'somali-focus' ), __( 'Business Development', 'somali-focus' ), __( 'Institutional Strengthening', 'somali-focus' ), __( 'Policy & Program Advisory', 'somali-focus' ), __( 'Digital Transformation', 'somali-focus' ) ),
		'url'      => home_url( '/advisory/' ),
		'cta'      => __( 'Request Advisory Support', 'somali-focus' ),
	),
	'research' => array(
		'number'   => '03',
		'icon'     => 'research',
		'title'    => __( 'Research', 'somali-focus' ),
		'text'     => __( 'Applied research, assessments, studies, data collection, analysis, evaluations and evidence-based recommendations.', 'somali-focus' ),
		'areas'    => array( __( 'Baseline Studies', 'somali-focus' ), __( 'Endline Studies', 'somali-focus' ), __( 'Needs Assessments', 'somali-focus' ), __( 'Feasibility Studies', 'somali-focus' ), __( 'Market Research', 'somali-focus' ), __( 'Impact Assessments', 'somali-focus' ), __( 'Program Evaluations', 'somali-focus' ), __( 'Surveys', 'somali-focus' ), __( 'Data Analysis', 'somali-focus' ) ),
		'url'      => home_url( '/research/' ),
		'cta'      => __( 'Discuss a Research Project', 'somali-focus' ),
	),
);

$sf_i = 0;
foreach ( $sf_service_blocks as $sf_block ) :
	$sf_i++;
	$sf_tinted = ( 0 === $sf_i % 2 );
	?>
	<section class="service-detail sf-reveal <?php echo $sf_tinted ? 'content-highlight--tinted' : ''; ?>">
		<div class="container service-detail__grid">
			<div class="service-detail__intro">
				<span class="service-detail__number"><?php echo esc_html( $sf_block['number'] ); ?></span>
				<span class="service-detail__icon"><?php sf_icon( $sf_block['icon'] ); ?></span>
				<h2 class="section-title"><?php echo esc_html( $sf_block['title'] ); ?></h2>
				<p class="section-description"><?php echo esc_html( $sf_block['text'] ); ?></p>
				<a class="btn btn--primary" href="<?php echo esc_url( $sf_block['url'] . '#sf-request' ); ?>"><?php echo esc_html( $sf_block['cta'] ); ?></a>
			</div>
			<ul class="service-detail__areas">
				<?php foreach ( $sf_block['areas'] as $sf_area ) : ?>
					<li><?php sf_icon( 'check' ); ?><?php echo esc_html( $sf_area ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
endforeach;

get_template_part( 'template-parts/cta' );

get_footer();
