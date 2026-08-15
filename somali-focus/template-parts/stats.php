<?php
/**
 * Impact statistics with animated counters.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sf_stats = sf_theme_get_stats();
if ( empty( $sf_stats ) ) {
	return;
}
?>
<section class="stats-section sf-reveal" aria-label="<?php esc_attr_e( 'Our Impact', 'somali-focus' ); ?>">
	<div class="container stats-grid">
		<?php foreach ( $sf_stats as $sf_stat ) :
			$sf_number = $sf_stat['number'];
			preg_match( '/[\d,.]+/', $sf_number, $sf_digits_match );
			$sf_digits = isset( $sf_digits_match[0] ) ? str_replace( ',', '', $sf_digits_match[0] ) : '';
			$sf_suffix = $sf_digits ? str_replace( $sf_digits_match[0], '', $sf_number ) : '';
			?>
			<div class="stat-block">
				<span class="stat-block__number" <?php if ( $sf_digits ) : ?>data-sf-counter="<?php echo esc_attr( $sf_digits ); ?>" data-sf-suffix="<?php echo esc_attr( $sf_suffix ); ?>"<?php endif; ?>>
					<?php echo $sf_digits ? '0' . esc_html( $sf_suffix ) : esc_html( $sf_number ); ?>
				</span>
				<span class="stat-block__label"><?php echo esc_html( $sf_stat['label'] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
