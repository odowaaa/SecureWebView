<?php
/**
 * Realistic, clearly fictional demo content so the site looks complete
 * immediately after activation. Each seed function is idempotent — it
 * only runs for a post type that currently has zero posts, so it never
 * duplicates content on repeat activations or manual re-runs.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Seed every content type that is currently empty.
 */
function somali_focus_install_demo_content() {
	somali_focus_seed_courses();
	somali_focus_seed_advisory();
	somali_focus_seed_research();
	somali_focus_seed_experts();
	somali_focus_seed_partners();
	somali_focus_seed_testimonials();
}
add_action( 'somali_focus_install_demo_content', 'somali_focus_install_demo_content' );

/**
 * Whether a post type has zero posts (any status) — safe-to-seed check.
 *
 * @param string $post_type Post type slug.
 * @return bool
 */
function somali_focus_type_is_empty( $post_type ) {
	$counts = (array) wp_count_posts( $post_type );
	return 0 === array_sum( $counts );
}

/**
 * Insert one demo post with meta in a single call.
 *
 * @param string $post_type Post type.
 * @param string $title     Post title.
 * @param string $content   Post content (may contain basic HTML).
 * @param array  $meta      key => value, saved with the _sf_ prefix.
 * @return int Post ID.
 */
function somali_focus_insert_demo_post( $post_type, $title, $content, $meta = array() ) {
	$post_id = wp_insert_post(
		array(
			'post_type'    => $post_type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $content,
		)
	);
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}
	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, '_sf_' . $key, $value );
	}
	return $post_id;
}

/**
 * Courses.
 */
function somali_focus_seed_courses() {
	if ( ! somali_focus_type_is_empty( 'sf_course' ) ) {
		return;
	}

	$courses = array(
		array(
			'title'   => __( 'Leadership & Management for Development Professionals', 'somali-focus' ),
			'content' => __( 'A practical five-day programme designed to strengthen leadership and management capabilities for professionals working across NGOs, government institutions and the private sector.', 'somali-focus' ),
			'meta'    => array(
				'short_description'  => __( 'Build the leadership and management skills needed to run high-performing teams and programs.', 'somali-focus' ),
				'trainer'            => __( 'Amina Yusuf, Senior Facilitator', 'somali-focus' ),
				'duration'           => __( '5 Days', 'somali-focus' ),
				'location'           => __( 'Garowe, Puntland', 'somali-focus' ),
				'delivery_mode'      => 'in-person',
				'start_date'         => gmdate( 'Y-m-d', strtotime( '+3 weeks' ) ),
				'end_date'           => gmdate( 'Y-m-d', strtotime( '+3 weeks +4 days' ) ),
				'fee'                => '$350',
				'max_participants'   => 25,
				'learning_objectives' => __( "Apply core leadership frameworks\nManage teams through change\nDelegate and communicate effectively", 'somali-focus' ),
				'target_audience'    => __( 'Mid-to-senior managers, program coordinators, department heads', 'somali-focus' ),
				'course_outline'     => __( "Foundations of Leadership\nManaging People & Performance\nDecision-Making Under Pressure\nOrganizational Communication\nLeading Change", 'somali-focus' ),
				'registration_status' => 'open',
			),
		),
		array(
			'title'   => __( 'Monitoring, Evaluation, Accountability & Learning (MEAL)', 'somali-focus' ),
			'content' => __( 'An applied course covering the full MEAL cycle — from indicator design to data quality assurance and adaptive learning.', 'somali-focus' ),
			'meta'    => array(
				'short_description' => __( 'Design and run effective MEAL systems that improve program quality and accountability.', 'somali-focus' ),
				'trainer'           => __( 'Dr. Hassan Warsame', 'somali-focus' ),
				'duration'          => __( '4 Days', 'somali-focus' ),
				'location'          => __( 'Online', 'somali-focus' ),
				'delivery_mode'     => 'online',
				'start_date'        => gmdate( 'Y-m-d', strtotime( '+5 weeks' ) ),
				'end_date'          => gmdate( 'Y-m-d', strtotime( '+5 weeks +3 days' ) ),
				'fee'               => '$280',
				'max_participants'  => 30,
				'learning_objectives' => __( "Design SMART indicators\nBuild data collection tools\nApply data quality checks", 'somali-focus' ),
				'target_audience'   => __( 'MEAL officers, program managers, research assistants', 'somali-focus' ),
				'course_outline'    => __( "MEAL Fundamentals\nIndicator & Logframe Design\nData Collection Tools\nData Quality & Analysis\nAdaptive Management", 'somali-focus' ),
				'registration_status' => 'open',
			),
		),
		array(
			'title'   => __( 'Applied Data Analysis for Program Teams', 'somali-focus' ),
			'content' => __( 'A hands-on introduction to cleaning, analyzing and visualizing program data for evidence-based decision making.', 'somali-focus' ),
			'meta'    => array(
				'short_description' => __( 'Turn raw program data into clear, decision-ready insights.', 'somali-focus' ),
				'trainer'           => __( 'Fartun Ali, Data Analyst', 'somali-focus' ),
				'duration'          => __( '3 Days', 'somali-focus' ),
				'location'          => __( 'Hargeisa, Somaliland', 'somali-focus' ),
				'delivery_mode'     => 'hybrid',
				'start_date'        => gmdate( 'Y-m-d', strtotime( '+7 weeks' ) ),
				'end_date'          => gmdate( 'Y-m-d', strtotime( '+7 weeks +2 days' ) ),
				'fee'               => __( 'Free (donor-sponsored)', 'somali-focus' ),
				'max_participants'  => 20,
				'learning_objectives' => __( "Clean and structure survey data\nBuild basic dashboards\nCommunicate findings clearly", 'somali-focus' ),
				'target_audience'   => __( 'M&E officers, researchers, program staff', 'somali-focus' ),
				'course_outline'    => __( "Data Cleaning Basics\nDescriptive Analysis\nVisualization Principles\nReporting Findings", 'somali-focus' ),
				'registration_status' => 'open',
			),
		),
	);

	foreach ( $courses as $course ) {
		$id = somali_focus_insert_demo_post( 'sf_course', $course['title'], $course['content'], $course['meta'] );
		if ( $id ) {
			wp_set_object_terms( $id, array( __( 'Leadership', 'somali-focus' ) ), 'course_category', false );
		}
	}
}

/**
 * Advisory projects.
 */
function somali_focus_seed_advisory() {
	if ( ! somali_focus_type_is_empty( 'sf_advisory' ) ) {
		return;
	}

	$projects = array(
		array(
			'title' => __( 'Strategic Plan Development for a Regional NGO Network', 'somali-focus' ),
			'meta'  => array(
				'client_sector' => __( 'International NGO Network (fictional example)', 'somali-focus' ),
				'challenge'     => __( 'The network lacked a shared five-year strategy and struggled to align member organizations around common priorities.', 'somali-focus' ),
				'approach'      => __( 'We facilitated a participatory strategic planning process combining stakeholder consultations, situational analysis and a theory-of-change workshop.', 'somali-focus' ),
				'results'       => __( 'A costed five-year strategic plan was adopted by all member organizations, with a shared monitoring framework.', 'somali-focus' ),
				'project_date'  => gmdate( 'Y-m-d', strtotime( '-4 months' ) ),
			),
		),
		array(
			'title' => __( 'Institutional Strengthening for a Government Department', 'somali-focus' ),
			'meta'  => array(
				'client_sector' => __( 'Public Sector Institution (fictional example)', 'somali-focus' ),
				'challenge'     => __( 'Limited internal systems for planning, HR and performance management were slowing service delivery.', 'somali-focus' ),
				'approach'      => __( 'We conducted an institutional capacity assessment and co-designed streamlined operating procedures with department staff.', 'somali-focus' ),
				'results'       => __( 'New standard operating procedures and a performance management framework were rolled out across three units.', 'somali-focus' ),
				'project_date'  => gmdate( 'Y-m-d', strtotime( '-9 months' ) ),
			),
		),
		array(
			'title' => __( 'Business Development Advisory for a Growing SME', 'somali-focus' ),
			'meta'  => array(
				'client_sector' => __( 'Private Sector SME (fictional example)', 'somali-focus' ),
				'challenge'     => __( 'A fast-growing SME needed a clearer growth strategy and stronger financial management practices.', 'somali-focus' ),
				'approach'      => __( 'We provided hands-on advisory on business planning, pricing strategy and basic financial controls over a three-month engagement.', 'somali-focus' ),
				'results'       => __( 'The business adopted a formal growth plan and improved monthly financial reporting.', 'somali-focus' ),
				'project_date'  => gmdate( 'Y-m-d', strtotime( '-2 months' ) ),
			),
		),
	);

	foreach ( $projects as $project ) {
		somali_focus_insert_demo_post( 'sf_advisory', $project['title'], '', $project['meta'] );
	}
}

/**
 * Research & publications.
 */
function somali_focus_seed_research() {
	if ( ! somali_focus_type_is_empty( 'sf_research' ) ) {
		return;
	}

	$items = array(
		array(
			'title' => __( 'Baseline Study: Youth Employment Readiness in Urban Centers', 'somali-focus' ),
			'meta'  => array(
				'abstract'         => __( 'This baseline study assesses employment readiness among urban youth, examining skills gaps, labor market barriers and training needs across three regions.', 'somali-focus' ),
				'authors'          => __( 'Somali Focus Research Team', 'somali-focus' ),
				'publication_date' => gmdate( 'Y-m-d', strtotime( '-6 months' ) ),
				'methodology'      => __( 'Mixed methods: household survey (n=850), key informant interviews and focus group discussions.', 'somali-focus' ),
				'keywords'         => __( 'youth employment, skills, labor market, baseline', 'somali-focus' ),
			),
		),
		array(
			'title' => __( 'Program Evaluation: Community Health Worker Initiative', 'somali-focus' ),
			'meta'  => array(
				'abstract'         => __( 'An independent endline evaluation measuring the reach, effectiveness and sustainability of a community health worker program.', 'somali-focus' ),
				'authors'          => __( 'Dr. Hassan Warsame, Fartun Ali', 'somali-focus' ),
				'publication_date' => gmdate( 'Y-m-d', strtotime( '-3 months' ) ),
				'methodology'      => __( 'Quasi-experimental design with pre/post comparison and qualitative case studies.', 'somali-focus' ),
				'keywords'         => __( 'health, evaluation, community health workers', 'somali-focus' ),
			),
		),
		array(
			'title' => __( 'Needs Assessment: Small Business Recovery After Drought', 'somali-focus' ),
			'meta'  => array(
				'abstract'         => __( 'This rapid needs assessment identifies the most pressing recovery needs of small businesses affected by prolonged drought conditions.', 'somali-focus' ),
				'authors'          => __( 'Amina Yusuf', 'somali-focus' ),
				'publication_date' => gmdate( 'Y-m-d', strtotime( '-1 month' ) ),
				'methodology'      => __( 'Rapid assessment combining trader surveys and market observation.', 'somali-focus' ),
				'keywords'         => __( 'private sector, resilience, needs assessment', 'somali-focus' ),
			),
		),
	);

	foreach ( $items as $item ) {
		$id = somali_focus_insert_demo_post( 'sf_research', $item['title'], '', $item['meta'] );
		if ( $id ) {
			wp_set_object_terms( $id, array( __( 'Program Evaluation', 'somali-focus' ) ), 'research_category', false );
		}
	}
}

/**
 * Experts.
 */
function somali_focus_seed_experts() {
	if ( ! somali_focus_type_is_empty( 'sf_expert' ) ) {
		return;
	}

	$experts = array(
		array(
			'title'   => 'Amina Yusuf',
			'content' => __( 'Amina has over twelve years of experience designing and delivering leadership and organizational development programs across the Horn of Africa.', 'somali-focus' ),
			'meta'    => array(
				'position'   => __( 'Lead Trainer, Leadership & Organizational Development', 'somali-focus' ),
				'education'  => __( 'MA Organizational Development, University of Nairobi', 'somali-focus' ),
				'experience' => __( '12+ years in training and institutional strengthening', 'somali-focus' ),
			),
		),
		array(
			'title'   => 'Dr. Hassan Warsame',
			'content' => __( 'Hassan leads Somali Focus\' research and evaluation practice, with a background in public health and applied social research.', 'somali-focus' ),
			'meta'    => array(
				'position'   => __( 'Director of Research & Evaluation', 'somali-focus' ),
				'education'  => __( 'PhD Public Health, University of London', 'somali-focus' ),
				'experience' => __( '15+ years in research, MEAL and program evaluation', 'somali-focus' ),
			),
		),
		array(
			'title'   => 'Fartun Ali',
			'content' => __( 'Fartun specializes in data analysis, survey design and building practical data systems for program teams.', 'somali-focus' ),
			'meta'    => array(
				'position'   => __( 'Senior Data Analyst', 'somali-focus' ),
				'education'  => __( 'BSc Statistics, Somali National University', 'somali-focus' ),
				'experience' => __( '8+ years in data analysis and MEAL systems', 'somali-focus' ),
			),
		),
		array(
			'title'   => 'Mohamed Abdullahi',
			'content' => __( 'Mohamed advises organizations on strategy, governance and institutional strengthening across the public and non-profit sectors.', 'somali-focus' ),
			'meta'    => array(
				'position'   => __( 'Senior Advisory Consultant', 'somali-focus' ),
				'education'  => __( 'MBA Strategic Management, Kampala International University', 'somali-focus' ),
				'experience' => __( '10+ years in strategy and institutional advisory', 'somali-focus' ),
			),
		),
	);

	foreach ( $experts as $expert ) {
		somali_focus_insert_demo_post( 'sf_expert', $expert['title'], $expert['content'], $expert['meta'] );
	}
}

/**
 * Partners (clearly fictional placeholders).
 */
function somali_focus_seed_partners() {
	if ( ! somali_focus_type_is_empty( 'sf_partner' ) ) {
		return;
	}

	$partners = array(
		__( 'Horn Development Alliance (placeholder)', 'somali-focus' ),
		__( 'Regional Health Partnership (placeholder)', 'somali-focus' ),
		__( 'East Africa Youth Trust (placeholder)', 'somali-focus' ),
		__( 'National Institute for Public Policy (placeholder)', 'somali-focus' ),
		__( 'Coastal Livelihoods Foundation (placeholder)', 'somali-focus' ),
		__( 'Somali Chamber of Commerce (placeholder)', 'somali-focus' ),
	);

	foreach ( $partners as $name ) {
		somali_focus_insert_demo_post( 'sf_partner', $name, '', array( 'website' => '' ) );
	}
}

/**
 * Testimonials (clearly fictional placeholders).
 */
function somali_focus_seed_testimonials() {
	if ( ! somali_focus_type_is_empty( 'sf_testimonial' ) ) {
		return;
	}

	$testimonials = array(
		array(
			'title'   => __( 'Fictional Program Director', 'somali-focus' ),
			'content' => __( 'The training was practical and directly applicable to our team\'s day-to-day challenges. (Placeholder testimonial — replace with a real quote.)', 'somali-focus' ),
			'meta'    => array( 'organization' => __( 'Placeholder Organization', 'somali-focus' ), 'position' => __( 'Program Director', 'somali-focus' ) ),
		),
		array(
			'title'   => __( 'Fictional M&E Manager', 'somali-focus' ),
			'content' => __( 'Their research team delivered a rigorous, well-communicated evaluation on time and on budget. (Placeholder testimonial — replace with a real quote.)', 'somali-focus' ),
			'meta'    => array( 'organization' => __( 'Placeholder Organization', 'somali-focus' ), 'position' => __( 'M&E Manager', 'somali-focus' ) ),
		),
	);

	foreach ( $testimonials as $t ) {
		somali_focus_insert_demo_post( 'sf_testimonial', $t['title'], $t['content'], $t['meta'] );
	}
}

/**
 * "(Re)Install Demo Content" action, reachable from the Dashboard page.
 * Safe to run repeatedly — each seeder only fills genuinely empty types.
 */
function somali_focus_handle_manual_seed() {
	if ( ! current_user_can( somali_focus_manage_cap() ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'somali-focus' ), 403 );
	}
	somali_focus_verify_nonce_or_die( 'somali_focus_seed_demo' );
	somali_focus_install_demo_content();
	wp_safe_redirect( add_query_arg( 'sf_seeded', '1', admin_url( 'admin.php?page=somali-focus' ) ) );
	exit;
}
add_action( 'admin_post_somali_focus_seed_demo', 'somali_focus_handle_manual_seed' );
