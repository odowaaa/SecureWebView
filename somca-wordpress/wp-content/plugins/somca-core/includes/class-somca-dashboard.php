<?php
/**
 * wp-admin "At a Glance"-style dashboard widget summarizing SomCA data.
 *
 * New in 2.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Dashboard {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'register_widget' ) );
	}

	public function register_widget() {
		wp_add_dashboard_widget( 'somca_snapshot', __( 'SomCA Climate Snapshot', 'somca-core' ), array( $this, 'render' ) );
	}

	public function render() {
		$hotspots      = (int) wp_count_posts( 'somca_hotspot' )->publish;
		$reports       = (int) wp_count_posts( 'somca_report' )->publish;
		$active_alerts = somca_get_active_alerts_count();
		$last_weekly   = get_option( 'somca_last_fetch_weekly', __( 'Never', 'somca-core' ) );

		global $wpdb;
		$subscriber_count = 0;
		if ( class_exists( 'SomCA_Subscribers' ) ) {
			$table = SomCA_Subscribers::table_name();
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- table name is fixed/internal, no user input.
			$subscriber_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
		}
		?>
		<ul class="somca-dashboard-stats" style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin:0 0 12px;">
			<li style="background:#f6f7f7;border-radius:6px;padding:10px;"><strong style="font-size:1.4em;display:block;"><?php echo esc_html( $hotspots ); ?></strong><?php esc_html_e( 'Hotspots', 'somca-core' ); ?></li>
			<li style="background:#f6f7f7;border-radius:6px;padding:10px;"><strong style="font-size:1.4em;display:block;"><?php echo esc_html( $reports ); ?></strong><?php esc_html_e( 'Reports', 'somca-core' ); ?></li>
			<li style="background:#fdecea;border-radius:6px;padding:10px;"><strong style="font-size:1.4em;display:block;color:#a92424;"><?php echo esc_html( $active_alerts ); ?></strong><?php esc_html_e( 'Active Alerts', 'somca-core' ); ?></li>
			<li style="background:#f6f7f7;border-radius:6px;padding:10px;"><strong style="font-size:1.4em;display:block;"><?php echo esc_html( $subscriber_count ); ?></strong><?php esc_html_e( 'Subscribers', 'somca-core' ); ?></li>
		</ul>
		<p><?php printf( esc_html__( 'Last weekly data run: %s', 'somca-core' ), esc_html( $last_weekly ?: __( 'Never', 'somca-core' ) ) ); ?></p>
		<p>
			<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=somca_hotspot' ) ); ?>"><?php esc_html_e( 'Add Hotspot', 'somca-core' ); ?></a> |
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=somca-core' ) ); ?>"><?php esc_html_e( 'SomCA Settings', 'somca-core' ); ?></a> |
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=somca-subscribers' ) ); ?>"><?php esc_html_e( 'View Subscribers', 'somca-core' ); ?></a>
		</p>
		<?php
	}
}

if ( ! function_exists( 'somca_get_active_alerts_count' ) ) {
	function somca_get_active_alerts_count() {
		$ids = get_posts( array(
			'post_type'      => 'somca_alert',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				'relation' => 'OR',
				array( 'key' => 'somca_expires_date', 'value' => gmdate( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ),
				array( 'key' => 'somca_expires_date', 'compare' => 'NOT EXISTS' ),
			),
		) );
		return count( $ids );
	}
}
