<?php
/**
 * SomCA Core admin settings page: alert thresholds, cron status, manual fetch.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Admin {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'admin_notices', array( $this, 'maybe_seed_notice' ) );
		add_action( 'admin_init', array( $this, 'maybe_seed_terms' ) );
	}

	public function add_menu() {
		add_menu_page(
			__( 'SomCA Settings', 'somca-core' ),
			__( 'SomCA Core', 'somca-core' ),
			'manage_options',
			'somca-core',
			array( $this, 'render_settings_page' ),
			'dashicons-admin-site-alt3',
			20
		);
	}

	public function register_settings() {
		register_setting( 'somca_core_settings', 'somca_alert_temp_anomaly_threshold', array( 'type' => 'number', 'default' => 1.5 ) );
		register_setting( 'somca_core_settings', 'somca_alert_rain_drought_threshold', array( 'type' => 'number', 'default' => -40 ) );
		register_setting( 'somca_core_settings', 'somca_alert_rain_flood_threshold', array( 'type' => 'number', 'default' => 60 ) );
	}

	public function enqueue( $hook ) {
		if ( 'toplevel_page_somca-core' !== $hook ) {
			return;
		}
		wp_enqueue_script( 'somca-admin', SOMCA_CORE_URL . 'assets/js/admin.js', array( 'jquery' ), SOMCA_CORE_VERSION, true );
		wp_localize_script( 'somca-admin', 'SomcaAdmin', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'somca_admin_nonce' ),
		) );
	}

	/**
	 * On first activation, seed the standard region/hazard/period taxonomy
	 * terms so the site is immediately usable without manual setup.
	 */
	public function maybe_seed_terms() {
		if ( get_option( 'somca_terms_seeded' ) ) {
			return;
		}
		foreach ( SomCA_CPT::default_regions() as $region ) {
			if ( ! term_exists( $region, 'somca_region' ) ) {
				wp_insert_term( $region, 'somca_region' );
			}
		}
		foreach ( SomCA_CPT::default_hazards() as $hazard ) {
			if ( ! term_exists( $hazard, 'somca_hazard' ) ) {
				wp_insert_term( $hazard, 'somca_hazard' );
			}
		}
		foreach ( SomCA_CPT::default_periods() as $slug => $label ) {
			if ( ! term_exists( $slug, 'somca_period' ) ) {
				wp_insert_term( $label, 'somca_period', array( 'slug' => $slug ) );
			}
		}
		update_option( 'somca_terms_seeded', 1 );
	}

	public function maybe_seed_notice() {
		$screen = get_current_screen();
		if ( ! $screen || 'toplevel_page_somca-core' !== $screen->id ) {
			return;
		}
		$hotspot_count = wp_count_posts( 'somca_hotspot' );
		if ( empty( $hotspot_count->publish ) ) {
			echo '<div class="notice notice-info"><p>' . sprintf(
				/* translators: %s: link to add a hotspot */
				esc_html__( 'Get started by adding your first Climate Hotspot with coordinates — %s. Weekly data will begin collecting automatically.', 'somca-core' ),
				'<a href="' . esc_url( admin_url( 'post-new.php?post_type=somca_hotspot' ) ) . '">' . esc_html__( 'Add Hotspot', 'somca-core' ) . '</a>'
			) . '</p></div>';
		}
	}

	public function render_settings_page() {
		$last_weekly = get_option( 'somca_last_fetch_weekly', __( 'Never', 'somca-core' ) );
		?>
		<div class="wrap somca-admin">
			<h1><?php esc_html_e( 'SomCA Core Settings', 'somca-core' ); ?></h1>
			<p><?php esc_html_e( 'Somali Climate Action — automated hotspot monitoring, alert thresholds, and data collection schedule.', 'somca-core' ); ?></p>

			<h2 class="title"><?php esc_html_e( 'Automated Alert Thresholds', 'somca-core' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'somca_core_settings' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="somca_alert_temp_anomaly_threshold"><?php esc_html_e( 'Temperature anomaly (°C)', 'somca-core' ); ?></label></th>
						<td><input type="number" step="0.1" id="somca_alert_temp_anomaly_threshold" name="somca_alert_temp_anomaly_threshold" value="<?php echo esc_attr( get_option( 'somca_alert_temp_anomaly_threshold', 1.5 ) ); ?>" class="small-text" />
						<p class="description"><?php esc_html_e( 'Draft a warning when average temperature is this many degrees above the 1991-2020 normal.', 'somca-core' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="somca_alert_rain_drought_threshold"><?php esc_html_e( 'Drought rainfall anomaly (%)', 'somca-core' ); ?></label></th>
						<td><input type="number" step="1" id="somca_alert_rain_drought_threshold" name="somca_alert_rain_drought_threshold" value="<?php echo esc_attr( get_option( 'somca_alert_rain_drought_threshold', -40 ) ); ?>" class="small-text" />
						<p class="description"><?php esc_html_e( 'Draft a drought warning when rainfall falls this % (or more) below normal, e.g. -40.', 'somca-core' ); ?></p></td>
					</tr>
					<tr>
						<th scope="row"><label for="somca_alert_rain_flood_threshold"><?php esc_html_e( 'Flood rainfall anomaly (%)', 'somca-core' ); ?></label></th>
						<td><input type="number" step="1" id="somca_alert_rain_flood_threshold" name="somca_alert_rain_flood_threshold" value="<?php echo esc_attr( get_option( 'somca_alert_rain_flood_threshold', 60 ) ); ?>" class="small-text" />
						<p class="description"><?php esc_html_e( 'Draft a flood warning when rainfall exceeds normal by this %, e.g. 60.', 'somca-core' ); ?></p></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>

			<hr/>

			<h2 class="title"><?php esc_html_e( 'Data Collection', 'somca-core' ); ?></h2>
			<p>
				<?php esc_html_e( 'Source: Open-Meteo (ERA5 reanalysis + climatology). No API key required.', 'somca-core' ); ?><br/>
				<?php printf( esc_html__( 'Last weekly run: %s', 'somca-core' ), esc_html( $last_weekly ) ); ?>
			</p>
			<p>
				<button type="button" class="button button-primary" id="somca-run-fetch" data-period="weekly"><?php esc_html_e( 'Run Weekly Fetch Now', 'somca-core' ); ?></button>
				<button type="button" class="button" id="somca-run-fetch-monthly" data-period="monthly"><?php esc_html_e( 'Run Monthly Fetch Now', 'somca-core' ); ?></button>
				<span id="somca-fetch-status" style="margin-left:10px;"></span>
			</p>
			<p class="description"><?php esc_html_e( 'Automatically runs weekly, monthly, seasonally, yearly, and bi-yearly via WP-Cron for every published Hotspot that has latitude/longitude set.', 'somca-core' ); ?></p>
		</div>
		<script>
		( function($){
			$('#somca-run-fetch, #somca-run-fetch-monthly').on('click', function(){
				var btn = $(this), period = btn.data('period');
				$('#somca-fetch-status').text('<?php echo esc_js( __( 'Running…', 'somca-core' ) ); ?>');
				btn.prop('disabled', true);
				$.post(SomcaAdmin.ajaxUrl, { action: 'somca_run_fetch_now', nonce: SomcaAdmin.nonce, period: period }, function(res){
					btn.prop('disabled', false);
					$('#somca-fetch-status').text(res && res.data ? res.data.message : 'Done');
				}).fail(function(){
					btn.prop('disabled', false);
					$('#somca-fetch-status').text('<?php echo esc_js( __( 'Error running fetch.', 'somca-core' ) ); ?>');
				});
			});
		})(jQuery);
		</script>
		<?php
	}
}
