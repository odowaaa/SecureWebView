<?php
/**
 * One-time (idempotent) site-structure setup: creates the six landing
 * Pages the theme's page templates expect, wires up the Insights blog
 * location, and builds a Primary navigation menu — so the site is
 * actually navigable immediately after activation rather than only
 * after manual admin setup.
 *
 * Every step here checks for existing content/config first and skips it
 * if found, so this is always safe to re-run (activation, plugin
 * upgrade, or the manual "Set Up Site Structure" dashboard button all
 * call the same function).
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The template-backed Pages, keyed by slug. 'content' is an optional
 * callable that generates starter post_content (used for the legal
 * pages, which need real placeholder text rather than an empty body).
 *
 * @return array
 */
function somali_focus_page_blueprint() {
	return array(
		'home'            => array( 'title' => __( 'Home', 'somali-focus' ), 'template' => '' ),
		'about'           => array( 'title' => __( 'About', 'somali-focus' ), 'template' => 'templates/page-about.php' ),
		'training'        => array( 'title' => __( 'Training', 'somali-focus' ), 'template' => 'templates/page-training.php' ),
		'advisory'        => array( 'title' => __( 'Advisory', 'somali-focus' ), 'template' => 'templates/page-advisory.php' ),
		'research'        => array( 'title' => __( 'Research', 'somali-focus' ), 'template' => 'templates/page-research.php' ),
		'services'        => array( 'title' => __( 'Services', 'somali-focus' ), 'template' => 'templates/page-services.php' ),
		'contact'         => array( 'title' => __( 'Contact', 'somali-focus' ), 'template' => 'templates/page-contact.php' ),
		'insights'        => array( 'title' => __( 'Insights', 'somali-focus' ), 'template' => '' ),
		'privacy-policy'  => array( 'title' => __( 'Privacy Policy', 'somali-focus' ), 'template' => '', 'content' => 'somali_focus_privacy_policy_placeholder' ),
		'terms'           => array( 'title' => __( 'Terms & Conditions', 'somali-focus' ), 'template' => '', 'content' => 'somali_focus_terms_placeholder' ),
	);
}

/**
 * Create every blueprint Page that doesn't already exist at its slug,
 * assigning the matching theme template and any starter content.
 *
 * @return array slug => page ID (existing or newly created).
 */
function somali_focus_ensure_pages() {
	$ids = array();

	foreach ( somali_focus_page_blueprint() as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}

		$content = '';
		if ( ! empty( $page['content'] ) && is_callable( $page['content'] ) ) {
			$content = call_user_func( $page['content'] );
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => $content,
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		if ( $page['template'] ) {
			update_post_meta( $post_id, '_wp_page_template', $page['template'] );
		}

		$ids[ $slug ] = $post_id;
	}

	if ( ! empty( $ids['privacy-policy'] ) && ! get_option( 'wp_page_for_privacy_policy' ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['privacy-policy'] );
	}

	return $ids;
}

/**
 * Starter Privacy Policy content. Deliberately structural, not a real
 * legal document — every claim-bearing line is marked for the site
 * owner to complete or confirm with their own legal counsel. Organization
 * name/contact fields pull from Somali Focus → Settings automatically.
 *
 * @return string
 */
function somali_focus_privacy_policy_placeholder() {
	$org   = somali_focus_get_option( 'org_name', get_bloginfo( 'name' ) );
	$email = somali_focus_get_option( 'email', get_option( 'admin_email' ) );

	/* translators: %s: organization name */
	$intro = sprintf( __( '%s ("we", "us", "our") respects your privacy. This page explains what information we collect through this website and how we use it.', 'somali-focus' ), $org );

	return
		'<p><em>' . esc_html__( '[This is a starter template, not a finished legal document. Replace the bracketed sections below with your organization\'s actual policy, ideally reviewed by qualified legal counsel before publishing.]', 'somali-focus' ) . '</em></p>' .
		'<p>' . esc_html( $intro ) . '</p>' .
		'<h2>' . esc_html__( 'Information We Collect', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[Describe what this site actually collects — typically: name, organization, email, phone and message content submitted through the Training Registration and Service Request forms, plus standard server/analytics logs if applicable.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'How We Use Information', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[Describe why — e.g. to process registrations, respond to inquiries, and improve our services. State whether data is shared with any third party and, if so, which one.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'Cookies', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[State which cookies this site sets, if any. If you enable advertising or analytics later, update this section before doing so.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'Data Retention & Your Rights', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[State how long submitted data is kept, and how a visitor can request access to or deletion of their data.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'Contact', 'somali-focus' ) . '</h2>' .
		'<p>' . sprintf( /* translators: %s: contact email */ esc_html__( 'Questions about this policy can be sent to %s.', 'somali-focus' ), esc_html( $email ) ) . '</p>';
}

/**
 * Starter Terms & Conditions content — same placeholder approach as the
 * Privacy Policy above.
 *
 * @return string
 */
function somali_focus_terms_placeholder() {
	$org = somali_focus_get_option( 'org_name', get_bloginfo( 'name' ) );

	return
		'<p><em>' . esc_html__( '[This is a starter template, not a finished legal document. Replace the bracketed sections below with your organization\'s actual terms, ideally reviewed by qualified legal counsel before publishing.]', 'somali-focus' ) . '</em></p>' .
		/* translators: %s: organization name */
		'<p>' . sprintf( esc_html__( 'These terms govern your use of the %s website.', 'somali-focus' ), esc_html( $org ) ) . '</p>' .
		'<h2>' . esc_html__( 'Use of This Website', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[Describe acceptable use of the site and its content.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'Training, Advisory & Research Services', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[Describe registration, cancellation, fee and delivery terms for courses, advisory engagements and research services.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'Intellectual Property', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[State ownership of published research, course materials and site content, and any permitted reuse.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'Limitation of Liability', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[Add your organization\'s liability position, reviewed by legal counsel.]', 'somali-focus' ) . '</p>' .
		'<h2>' . esc_html__( 'Contact', 'somali-focus' ) . '</h2>' .
		'<p>' . esc_html__( '[Restate your contact details or reference the Contact page.]', 'somali-focus' ) . '</p>';
}

/**
 * Suggest the data our own forms collect in WordPress's native "Privacy
 * Policy Guide" panel (Settings → Privacy → Policy Guide), the standard
 * extension point plugins use to help site owners write an accurate
 * policy — this only ever populates an editorial suggestion panel, never
 * live page content.
 */
function somali_focus_privacy_policy_suggestion() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}
	$content = '<p class="wp-policy-help">' . esc_html__( 'Somali Focus Core collects the following through its front-end forms:', 'somali-focus' ) . '</p>' .
		'<ul>' .
		'<li>' . esc_html__( 'Training Registration form: full name, organization, position, email, phone, selected course, and an optional message.', 'somali-focus' ) . '</li>' .
		'<li>' . esc_html__( 'Service Request form: full name, organization, email, phone, service requested, budget range, and message.', 'somali-focus' ) . '</li>' .
		'</ul>' .
		'<p>' . esc_html__( 'Both are stored privately in WordPress (never exposed via the REST API or public queries) and are used only to process the registration/request and to email the site administrator a notification.', 'somali-focus' ) . '</p>';

	wp_add_privacy_policy_content( __( 'Somali Focus Core', 'somali-focus' ), wp_kses_post( $content ) );
}
add_action( 'admin_init', 'somali_focus_privacy_policy_suggestion' );

/**
 * Point Reading settings at the Home/Insights pages so /insights/ becomes
 * a real blog index, without touching a site that has already been
 * configured (manually or by a previous run of this function).
 *
 * @param array $ids slug => page ID, from somali_focus_ensure_pages().
 */
function somali_focus_configure_reading_settings( $ids ) {
	if ( 'page' === get_option( 'show_on_front' ) ) {
		return; // Already configured — never override a deliberate choice.
	}
	if ( empty( $ids['home'] ) || empty( $ids['insights'] ) ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );
	update_option( 'page_for_posts', $ids['insights'] );
}

/**
 * Build the Primary navigation menu if nothing is already assigned to
 * that theme location.
 *
 * @param array $ids slug => page ID, from somali_focus_ensure_pages().
 */
function somali_focus_ensure_primary_menu( $ids ) {
	if ( has_nav_menu( 'primary' ) ) {
		return; // Admin (or a previous run) already set this up.
	}

	$menu_name = __( 'Primary Menu', 'somali-focus' );
	$menu_id   = wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$position = 1;

	if ( ! empty( $ids['home'] ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Home', 'somali-focus' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $ids['home'],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position++,
		) );
	}

	$page_items = array(
		'about'    => __( 'About', 'somali-focus' ),
		'training' => __( 'Training', 'somali-focus' ),
		'advisory' => __( 'Advisory', 'somali-focus' ),
		'research' => __( 'Research', 'somali-focus' ),
		'insights' => __( 'Insights', 'somali-focus' ),
	);

	foreach ( $page_items as $slug => $label ) {
		if ( empty( $ids[ $slug ] ) ) {
			continue;
		}
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $label,
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $ids[ $slug ],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position++,
		) );
	}

	if ( post_type_exists( 'sf_expert' ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'    => __( 'Experts', 'somali-focus' ),
			'menu-item-url'      => get_post_type_archive_link( 'sf_expert' ),
			'menu-item-type'     => 'custom',
			'menu-item-status'   => 'publish',
			'menu-item-position' => $position++,
		) );
	}

	if ( ! empty( $ids['contact'] ) ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => __( 'Contact', 'somali-focus' ),
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $ids['contact'],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position++,
		) );
	}

	$locations             = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Run the full site-structure setup. Safe to call repeatedly.
 */
function somali_focus_setup_site_structure() {
	$ids = somali_focus_ensure_pages();
	somali_focus_configure_reading_settings( $ids );
	somali_focus_ensure_primary_menu( $ids );
}

/**
 * Manual "Set Up Site Structure" action, reachable from the Dashboard —
 * for sites that skipped this on activation or want to re-check it after
 * deleting a page/menu.
 */
function somali_focus_handle_manual_site_setup() {
	if ( ! current_user_can( somali_focus_manage_cap() ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'somali-focus' ), 403 );
	}
	somali_focus_verify_nonce_or_die( 'somali_focus_site_setup' );
	somali_focus_setup_site_structure();
	wp_safe_redirect( add_query_arg( 'sf_site_setup', '1', admin_url( 'admin.php?page=somali-focus' ) ) );
	exit;
}
add_action( 'admin_post_somali_focus_site_setup', 'somali_focus_handle_manual_site_setup' );
