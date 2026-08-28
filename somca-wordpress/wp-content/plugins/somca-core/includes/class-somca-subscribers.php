<?php
/**
 * Weekly briefing subscribers: custom table, public AJAX signup, admin list + CSV export.
 *
 * New in 2.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Subscribers {

	private static $instance = null;

	const TABLE_OPTION_VERSION = 'somca_subscribers_db_version';
	const DB_VERSION = '1.0';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_ajax_somca_subscribe', array( $this, 'ajax_subscribe' ) );
		add_action( 'wp_ajax_nopriv_somca_subscribe', array( $this, 'ajax_subscribe' ) );

		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_post_somca_export_subscribers', array( $this, 'export_csv' ) );

		// Catches sites upgraded from 1.x by re-uploading files without deactivating.
		add_action( 'plugins_loaded', array( $this, 'maybe_upgrade_table' ) );
	}

	public function maybe_upgrade_table() {
		if ( get_option( self::TABLE_OPTION_VERSION ) !== self::DB_VERSION ) {
			self::install_table();
		}
	}

	public static function table_name() {
		global $wpdb;
		return $wpdb->prefix . 'somca_subscribers';
	}

	/**
	 * Create/upgrade the subscribers table. Called on plugin activation.
	 */
	public static function install_table() {
		global $wpdb;

		$table_name      = self::table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			email VARCHAR(190) NOT NULL,
			region VARCHAR(100) DEFAULT '' NOT NULL,
			subscribed_at DATETIME NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY email (email)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( self::TABLE_OPTION_VERSION, self::DB_VERSION );
	}

	/**
	 * Public AJAX handler for the footer "Weekly Climate Briefing" form.
	 * No login required (nopriv); protected by nonce + honeypot + basic rate awareness.
	 */
	public function ajax_subscribe() {
		check_ajax_referer( 'somca_subscribe_nonce', 'nonce' );

		// Honeypot: a real visitor never fills this hidden field.
		if ( ! empty( $_POST['somca_website'] ) ) {
			wp_send_json_success( array( 'message' => __( 'Thanks for subscribing!', 'somca-core' ) ) );
		}

		$email  = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$region = isset( $_POST['region'] ) ? sanitize_text_field( wp_unslash( $_POST['region'] ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a valid email address.', 'somca-core' ) ) );
		}

		global $wpdb;
		$table = self::table_name();

		$existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE email = %s", $email ) );

		if ( $existing ) {
			wp_send_json_success( array( 'message' => __( 'You’re already subscribed — thank you!', 'somca-core' ) ) );
		}

		$inserted = $wpdb->insert(
			$table,
			array(
				'email'          => $email,
				'region'         => $region,
				'subscribed_at'  => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%s' )
		);

		if ( ! $inserted ) {
			wp_send_json_error( array( 'message' => __( 'Something went wrong. Please try again.', 'somca-core' ) ) );
		}

		do_action( 'somca_new_subscriber', $email, $region );

		wp_send_json_success( array( 'message' => __( 'Subscribed! Watch your inbox for weekly climate updates.', 'somca-core' ) ) );
	}

	public function add_menu() {
		add_submenu_page(
			'somca-core',
			__( 'Subscribers', 'somca-core' ),
			__( 'Subscribers', 'somca-core' ),
			'manage_options',
			'somca-subscribers',
			array( $this, 'render_list_page' )
		);
	}

	public function render_list_page() {
		global $wpdb;
		$table = self::table_name();
		$rows  = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY subscribed_at DESC LIMIT 500" );
		$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'SomCA Subscribers', 'somca-core' ); ?> <span class="count">(<?php echo esc_html( $total ); ?>)</span></h1>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=somca_export_subscribers' ), 'somca_export_subscribers' ) ); ?>">
					<?php esc_html_e( 'Export CSV', 'somca-core' ); ?>
				</a>
			</p>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Email', 'somca-core' ); ?></th>
						<th><?php esc_html_e( 'Region', 'somca-core' ); ?></th>
						<th><?php esc_html_e( 'Subscribed', 'somca-core' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( $rows ) : ?>
						<?php foreach ( $rows as $row ) : ?>
							<tr>
								<td><?php echo esc_html( $row->email ); ?></td>
								<td><?php echo esc_html( $row->region ?: '—' ); ?></td>
								<td><?php echo esc_html( $row->subscribed_at ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><td colspan="3"><?php esc_html_e( 'No subscribers yet.', 'somca-core' ); ?></td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public function export_csv() {
		if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'somca_export_subscribers' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'somca-core' ) );
		}

		global $wpdb;
		$table = self::table_name();
		$rows  = $wpdb->get_results( "SELECT email, region, subscribed_at FROM {$table} ORDER BY subscribed_at DESC" );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=somca-subscribers-' . gmdate( 'Y-m-d' ) . '.csv' );

		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array( 'Email', 'Region', 'Subscribed At' ) );
		foreach ( $rows as $row ) {
			fputcsv( $out, array( $row->email, $row->region, $row->subscribed_at ) );
		}
		fclose( $out );
		exit;
	}
}
