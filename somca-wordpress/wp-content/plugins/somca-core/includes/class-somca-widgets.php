<?php
/**
 * Sidebar/footer widgets: Active Alerts, Latest Reports.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SomCA_Widgets {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'widgets_init', array( $this, 'register' ) );
	}

	public function register() {
		register_widget( 'SomCA_Widget_Alerts' );
		register_widget( 'SomCA_Widget_Latest_Reports' );
	}
}

class SomCA_Widget_Alerts extends WP_Widget {

	public function __construct() {
		parent::__construct( 'somca_widget_alerts', __( 'SomCA: Active Alerts', 'somca-core' ), array(
			'description' => __( 'Shows currently active climate alerts.', 'somca-core' ),
		) );
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
		}
		echo do_shortcode( '[somca_alerts limit="4"]' );
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Active Alerts', 'somca-core' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'somca-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}
}

class SomCA_Widget_Latest_Reports extends WP_Widget {

	public function __construct() {
		parent::__construct( 'somca_widget_reports', __( 'SomCA: Latest Reports', 'somca-core' ), array(
			'description' => __( 'Shows the most recent climate reports.', 'somca-core' ),
		) );
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
		}

		$posts = get_posts( array(
			'post_type'      => 'somca_report',
			'post_status'    => 'publish',
			'posts_per_page' => ! empty( $instance['count'] ) ? (int) $instance['count'] : 5,
		) );

		if ( $posts ) {
			echo '<ul class="somca-widget-reports">';
			foreach ( $posts as $p ) {
				echo '<li><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a></li>';
			}
			echo '</ul>';
		}

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : __( 'Latest Reports', 'somca-core' );
		$count = isset( $instance['count'] ) ? $instance['count'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'somca-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number to show:', 'somca-core' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['count'] = (int) $new_instance['count'];
		return $instance;
	}
}
