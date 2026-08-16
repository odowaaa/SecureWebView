<?php
/**
 * Custom "Somali Focus" admin menu: dashboard, settings, CSV exports and
 * list-table filters for the two private submission post types.
 *
 * @package SomaliFocusCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the top-level admin menu. Must run before the CPTs' own submenu
 * registration fires so 'somali-focus' exists as a valid parent slug.
 */
function somali_focus_admin_menu() {
	$cap = somali_focus_manage_cap();

	add_menu_page(
		__( 'Somali Focus', 'somali-focus' ),
		__( 'Somali Focus', 'somali-focus' ),
		$cap,
		'somali-focus',
		'somali_focus_dashboard_page',
		'dashicons-analytics',
		25
	);

	add_submenu_page(
		'somali-focus',
		__( 'Dashboard', 'somali-focus' ),
		__( 'Dashboard', 'somali-focus' ),
		$cap,
		'somali-focus',
		'somali_focus_dashboard_page'
	);

	add_submenu_page(
		'somali-focus',
		__( 'Settings', 'somali-focus' ),
		__( 'Settings', 'somali-focus' ),
		$cap,
		'somali-focus-settings',
		'somali_focus_render_settings_page'
	);
}
add_action( 'admin_menu', 'somali_focus_admin_menu' );

/**
 * Simple dashboard landing page with quick counts and links.
 */
function somali_focus_dashboard_page() {
	if ( ! current_user_can( somali_focus_manage_cap() ) ) {
		return;
	}

	$counts = array(
		'sf_course'           => __( 'Courses', 'somali-focus' ),
		'sf_advisory'         => __( 'Advisory Projects', 'somali-focus' ),
		'sf_research'         => __( 'Research Items', 'somali-focus' ),
		'sf_expert'           => __( 'Experts', 'somali-focus' ),
		'sf_partner'          => __( 'Partners', 'somali-focus' ),
		'sf_testimonial'      => __( 'Testimonials', 'somali-focus' ),
		'sf_registration'     => __( 'Registrations', 'somali-focus' ),
		'sf_service_request'  => __( 'Service Requests', 'somali-focus' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Somali Focus — Dashboard', 'somali-focus' ); ?></h1>
		<p><?php esc_html_e( 'Manage all Training, Advisory, Research and site content from this menu. The active theme reads everything below automatically.', 'somali-focus' ); ?></p>

		<?php if ( isset( $_GET['sf_seeded'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Demo content check complete — any empty sections have been filled with sample content.', 'somali-focus' ); ?></p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['sf_site_setup'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Site structure check complete — any missing pages or the primary menu have been created.', 'somali-focus' ); ?></p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['sf_demo_removed'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p>
				<?php
				printf(
					/* translators: %d: number of removed demo posts */
					esc_html( _n( '%d demo item removed. Any real content you added is untouched.', '%d demo items removed. Any real content you added is untouched.', (int) $_GET['sf_demo_removed'], 'somali-focus' ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					(int) $_GET['sf_demo_removed'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				);
				?>
			</p></div>
		<?php endif; ?>

		<p>
			<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=somali_focus_site_setup' ), 'somali_focus_site_setup' ) ); ?>">
				<?php esc_html_e( 'Set Up Site Structure (Pages & Menu)', 'somali-focus' ); ?>
			</a>
			<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=somali_focus_seed_demo' ), 'somali_focus_seed_demo' ) ); ?>">
				<?php esc_html_e( 'Install / Refill Demo Content', 'somali-focus' ); ?>
			</a>
			<?php if ( function_exists( 'somali_focus_has_demo_content' ) && somali_focus_has_demo_content() ) : ?>
				<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=somali_focus_remove_demo_content' ), 'somali_focus_remove_demo_content' ) ); ?>" onclick="return confirm('<?php echo esc_js( __( 'Permanently delete all demo content? This only removes items tagged as demo — anything you\'ve added or edited yourself is not affected.', 'somali-focus' ) ); ?>');">
					<?php esc_html_e( 'Remove All Demo Content', 'somali-focus' ); ?>
				</a>
			<?php endif; ?>
			<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=somali-focus-settings' ) ); ?>">
				<?php esc_html_e( 'Go to Settings', 'somali-focus' ); ?>
			</a>
		</p>

		<div class="somali-focus-dashboard-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-top:20px;">
			<?php foreach ( $counts as $post_type => $label ) :
				if ( ! post_type_exists( $post_type ) ) {
					continue;
				}
				$count_obj = wp_count_posts( $post_type );
				$published = isset( $count_obj->publish ) ? (int) $count_obj->publish : 0;
				?>
				<div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:16px;">
					<div style="font-size:28px;font-weight:600;"><?php echo esc_html( $published ); ?></div>
					<div style="color:#555;"><?php echo esc_html( $label ); ?></div>
					<p style="margin-top:8px;"><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $post_type ) ); ?>"><?php esc_html_e( 'Manage', 'somali-focus' ); ?> &rarr;</a></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Add a "Course" filter dropdown to the Registrations list table.
 */
function somali_focus_registration_filters() {
	global $typenow;
	if ( 'sf_registration' !== $typenow ) {
		return;
	}

	$courses  = get_posts( array( 'post_type' => 'sf_course', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
	$selected = isset( $_GET['sf_course_filter'] ) ? absint( $_GET['sf_course_filter'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	echo '<select name="sf_course_filter"><option value="0">' . esc_html__( 'All Courses', 'somali-focus' ) . '</option>';
	foreach ( $courses as $course ) {
		echo '<option value="' . esc_attr( $course->ID ) . '" ' . selected( $selected, $course->ID, false ) . '>' . esc_html( $course->post_title ) . '</option>';
	}
	echo '</select>';

	somali_focus_status_filter_dropdown( array( 'new' => __( 'New', 'somali-focus' ), 'contacted' => __( 'Contacted', 'somali-focus' ), 'confirmed' => __( 'Confirmed', 'somali-focus' ), 'completed' => __( 'Completed', 'somali-focus' ), 'cancelled' => __( 'Cancelled', 'somali-focus' ) ) );
}
add_action( 'restrict_manage_posts', 'somali_focus_registration_filters' );

/**
 * Add a status filter dropdown to the Service Requests list table.
 */
function somali_focus_service_request_filters() {
	global $typenow;
	if ( 'sf_service_request' !== $typenow ) {
		return;
	}
	somali_focus_status_filter_dropdown( array( 'new' => __( 'New', 'somali-focus' ), 'in-progress' => __( 'In Progress', 'somali-focus' ), 'replied' => __( 'Replied', 'somali-focus' ), 'closed' => __( 'Closed', 'somali-focus' ) ) );
}
add_action( 'restrict_manage_posts', 'somali_focus_service_request_filters' );

/**
 * Shared status <select> renderer.
 *
 * @param array $statuses status_key => label.
 */
function somali_focus_status_filter_dropdown( $statuses ) {
	$selected = isset( $_GET['sf_status_filter'] ) ? sanitize_key( $_GET['sf_status_filter'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	echo '<select name="sf_status_filter"><option value="">' . esc_html__( 'All Statuses', 'somali-focus' ) . '</option>';
	foreach ( $statuses as $key => $label ) {
		echo '<option value="' . esc_attr( $key ) . '" ' . selected( $selected, $key, false ) . '>' . esc_html( $label ) . '</option>';
	}
	echo '</select>';
}

/**
 * Apply the course/status filters to the underlying query.
 *
 * @param WP_Query $query Current admin query.
 */
function somali_focus_apply_admin_filters( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	$meta_query = array();

	if ( 'sf_registration' === $post_type && ! empty( $_GET['sf_course_filter'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$meta_query[] = array(
			'key'   => '_sf_course_id',
			'value' => absint( $_GET['sf_course_filter'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		);
	}

	if ( in_array( $post_type, array( 'sf_registration', 'sf_service_request' ), true ) && ! empty( $_GET['sf_status_filter'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$meta_query[] = array(
			'key'   => '_sf_status',
			'value' => sanitize_key( $_GET['sf_status_filter'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		);
	}

	if ( ! empty( $meta_query ) ) {
		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'somali_focus_apply_admin_filters' );

/**
 * Add an "Export CSV" link into the list table's native views row
 * (alongside "All | Published | Trash") for the two submission CPTs.
 *
 * @param array $views Existing view links.
 * @return array
 */
function somali_focus_add_export_view( $views ) {
	global $typenow;
	$map = array(
		'sf_registration'    => 'somali_focus_export_registrations',
		'sf_service_request' => 'somali_focus_export_service_requests',
	);
	if ( ! isset( $map[ $typenow ] ) ) {
		return $views;
	}
	$url = wp_nonce_url( admin_url( 'admin-post.php?action=' . $map[ $typenow ] ), $map[ $typenow ] );
	$views['sf_export'] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Export CSV', 'somali-focus' ) . '</a>';
	return $views;
}
add_filter( 'views_edit-sf_registration', 'somali_focus_add_export_view' );
add_filter( 'views_edit-sf_service_request', 'somali_focus_add_export_view' );

/**
 * Stream a CSV export for the given post type + field map.
 *
 * @param string $post_type Post type slug.
 * @param array  $fields    meta_key => column header.
 */
function somali_focus_stream_csv_export( $post_type, $fields ) {
	if ( ! current_user_can( somali_focus_manage_cap() ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'somali-focus' ), 403 );
	}

	$action = 'sf_registration' === $post_type ? 'somali_focus_export_registrations' : 'somali_focus_export_service_requests';
	somali_focus_verify_nonce_or_die( $action );

	$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => -1, 'post_status' => 'any' ) );

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . $post_type . '-export-' . gmdate( 'Y-m-d' ) . '.csv' );

	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array_merge( array( 'ID', 'Submitted' ), array_values( $fields ) ) );

	foreach ( $posts as $post ) {
		$row = array( $post->ID, get_the_date( 'Y-m-d H:i', $post ) );
		foreach ( array_keys( $fields ) as $key ) {
			$row[] = somali_focus_meta( $post->ID, $key );
		}
		fputcsv( $out, $row );
	}
	fclose( $out );
	exit;
}

/**
 * Export handler: Registrations.
 */
function somali_focus_handle_export_registrations() {
	somali_focus_stream_csv_export(
		'sf_registration',
		array(
			'full_name'    => 'Full Name',
			'organization' => 'Organization',
			'position'     => 'Position',
			'email'        => 'Email',
			'phone'        => 'Phone',
			'course_id'    => 'Course ID',
			'message'      => 'Message',
			'status'       => 'Status',
		)
	);
}
add_action( 'admin_post_somali_focus_export_registrations', 'somali_focus_handle_export_registrations' );

/**
 * Export handler: Service Requests.
 */
function somali_focus_handle_export_service_requests() {
	somali_focus_stream_csv_export(
		'sf_service_request',
		array(
			'full_name'    => 'Full Name',
			'organization' => 'Organization',
			'email'        => 'Email',
			'phone'        => 'Phone',
			'service'      => 'Service',
			'budget_range' => 'Budget Range',
			'message'      => 'Message',
			'status'       => 'Status',
		)
	);
}
add_action( 'admin_post_somali_focus_export_service_requests', 'somali_focus_handle_export_service_requests' );
