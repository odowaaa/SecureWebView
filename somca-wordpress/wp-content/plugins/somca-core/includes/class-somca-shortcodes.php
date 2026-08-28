<?php
/**
 * Shortcodes: [somca_map] [somca_alerts] [somca_chart] [somca_latest_reports] [somca_hotspots_grid]
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Shortcodes {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

		add_shortcode( 'somca_map', array( $this, 'render_map' ) );
		add_shortcode( 'somca_alerts', array( $this, 'render_alerts' ) );
		add_shortcode( 'somca_chart', array( $this, 'render_chart' ) );
		add_shortcode( 'somca_latest_reports', array( $this, 'render_latest_reports' ) );
		add_shortcode( 'somca_hotspots_grid', array( $this, 'render_hotspots_grid' ) );
	}

	/**
	 * Register (but do not enqueue) the map/chart libraries so shortcodes
	 * work even when the active theme is not the bundled SomCA theme.
	 */
	public function register_assets() {
		wp_register_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4' );
		wp_register_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true );
		wp_register_script( 'chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js', array(), '4.4.4', true );

		wp_register_script( 'somca-map', SOMCA_CORE_URL . 'assets/js/somca-map.js', array( 'leaflet' ), SOMCA_CORE_VERSION, true );
		wp_register_script( 'somca-charts', SOMCA_CORE_URL . 'assets/js/somca-charts.js', array( 'chartjs' ), SOMCA_CORE_VERSION, true );
		wp_register_style( 'somca-core', SOMCA_CORE_URL . 'assets/css/somca-core.css', array( 'leaflet' ), SOMCA_CORE_VERSION );

		wp_enqueue_style( 'somca-core' );
	}

	public function render_map( $atts ) {
		$atts = shortcode_atts( array( 'height' => '480px' ), $atts );
		wp_enqueue_style( 'leaflet' );
		wp_enqueue_script( 'leaflet' );
		wp_enqueue_script( 'somca-map' );
		ob_start();
		?>
		<div class="somca-map-wrap">
			<div id="somca-hotspot-map" class="somca-hotspot-map" style="height: <?php echo esc_attr( $atts['height'] ); ?>;" data-endpoint="<?php echo esc_url( rest_url( 'somca/v1/hotspots' ) ); ?>"></div>
			<div class="somca-map-legend">
				<span class="somca-legend-item"><i class="somca-dot somca-risk-low"></i><?php esc_html_e( 'Low', 'somca-core' ); ?></span>
				<span class="somca-legend-item"><i class="somca-dot somca-risk-moderate"></i><?php esc_html_e( 'Moderate', 'somca-core' ); ?></span>
				<span class="somca-legend-item"><i class="somca-dot somca-risk-high"></i><?php esc_html_e( 'High', 'somca-core' ); ?></span>
				<span class="somca-legend-item"><i class="somca-dot somca-risk-critical"></i><?php esc_html_e( 'Critical', 'somca-core' ); ?></span>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function render_alerts( $atts ) {
		$atts = shortcode_atts( array( 'limit' => 5 ), $atts );

		$posts = get_posts( array(
			'post_type'      => 'somca_alert',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $atts['limit'],
			'meta_query'     => array(
				'relation' => 'OR',
				array( 'key' => 'somca_expires_date', 'value' => gmdate( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ),
				array( 'key' => 'somca_expires_date', 'compare' => 'NOT EXISTS' ),
			),
		) );

		if ( empty( $posts ) ) {
			return '';
		}

		ob_start();
		?>
		<div class="somca-alerts-banner">
			<?php foreach ( $posts as $p ) :
				$severity = get_post_meta( $p->ID, 'somca_severity', true ) ?: 'advisory';
				?>
				<a class="somca-alert somca-severity-<?php echo esc_attr( $severity ); ?>" href="<?php echo esc_url( get_permalink( $p ) ); ?>">
					<span class="somca-alert-badge"><?php echo esc_html( ucfirst( $severity ) ); ?></span>
					<span class="somca-alert-title"><?php echo esc_html( get_the_title( $p ) ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	public function render_chart( $atts ) {
		$atts = shortcode_atts( array(
			'region'  => '',
			'period'  => 'weekly',
			'metric'  => 'temp', // temp | rainfall
			'hotspot' => '',
			'limit'   => 24,
			'height'  => '360px',
		), $atts );

		wp_enqueue_script( 'chartjs' );
		wp_enqueue_script( 'somca-charts' );

		$endpoint = add_query_arg( array_filter( array(
			'period'  => sanitize_key( $atts['period'] ),
			'region'  => sanitize_key( $atts['region'] ),
			'hotspot' => (int) $atts['hotspot'],
			'limit'   => (int) $atts['limit'],
		) ), rest_url( 'somca/v1/reports' ) );

		$id = 'somca-chart-' . wp_unique_id();

		ob_start();
		?>
		<div class="somca-chart-wrap">
			<canvas id="<?php echo esc_attr( $id ); ?>" class="somca-chart" height="<?php echo esc_attr( str_replace( 'px', '', $atts['height'] ) ); ?>"
				data-endpoint="<?php echo esc_url( $endpoint ); ?>"
				data-metric="<?php echo esc_attr( $atts['metric'] ); ?>"></canvas>
		</div>
		<?php
		return ob_get_clean();
	}

	public function render_latest_reports( $atts ) {
		$atts = shortcode_atts( array( 'count' => 6, 'period' => '' ), $atts );

		$args = array(
			'post_type'      => 'somca_report',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $atts['count'],
		);
		if ( $atts['period'] ) {
			$args['tax_query'] = array( array( 'taxonomy' => 'somca_period', 'field' => 'slug', 'terms' => sanitize_key( $atts['period'] ) ) );
		}

		$q = new WP_Query( $args );
		if ( ! $q->have_posts() ) {
			return '';
		}

		ob_start();
		?>
		<div class="somca-card-grid">
			<?php while ( $q->have_posts() ) : $q->the_post(); ?>
				<article class="somca-card somca-report-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="somca-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
					<?php endif; ?>
					<div class="somca-card-body">
						<div class="somca-card-meta">
							<?php echo esc_html( implode( ', ', wp_get_post_terms( get_the_ID(), 'somca_period', array( 'fields' => 'names' ) ) ) ); ?>
						</div>
						<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="somca-card-stats">
							<span><?php echo esc_html( get_post_meta( get_the_ID(), 'somca_avg_temp_c', true ) ); ?>&deg;C</span>
							<span><?php echo esc_html( get_post_meta( get_the_ID(), 'somca_rainfall_mm', true ) ); ?>mm</span>
						</div>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	public function render_hotspots_grid( $atts ) {
		$atts = shortcode_atts( array( 'count' => 6 ), $atts );

		$q = new WP_Query( array(
			'post_type'      => 'somca_hotspot',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $atts['count'],
		) );

		if ( ! $q->have_posts() ) {
			return '';
		}

		ob_start();
		?>
		<div class="somca-card-grid">
			<?php while ( $q->have_posts() ) : $q->the_post();
				$risk = get_post_meta( get_the_ID(), 'somca_risk_level', true ) ?: 'moderate';
				?>
				<article class="somca-card somca-hotspot-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="somca-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
					<?php endif; ?>
					<div class="somca-card-body">
						<span class="somca-risk-badge somca-risk-<?php echo esc_attr( $risk ); ?>"><?php echo esc_html( ucfirst( $risk ) ); ?></span>
						<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
