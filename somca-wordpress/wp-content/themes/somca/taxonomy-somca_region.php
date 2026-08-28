<?php
/**
 * Region hub page: hotspots, reports & alerts for a single region.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term = get_queried_object();
?>
<div class="somca-container somca-section">
	<header class="somca-section-header">
		<div>
			<span class="somca-eyebrow"><?php esc_html_e( 'Region', 'somca' ); ?></span>
			<h1><?php echo esc_html( $term->name ); ?></h1>
			<?php if ( $term->description ) : ?><p><?php echo esc_html( $term->description ); ?></p><?php endif; ?>
		</div>
	</header>

	<?php
	$hotspots = new WP_Query( array(
		'post_type'      => 'somca_hotspot',
		'posts_per_page' => 12,
		'tax_query'      => array( array( 'taxonomy' => 'somca_region', 'field' => 'term_id', 'terms' => $term->term_id ) ),
	) );
	if ( $hotspots->have_posts() ) :
		?>
		<h2><?php esc_html_e( 'Hotspots in this Region', 'somca' ); ?></h2>
		<div class="somca-card-grid">
			<?php while ( $hotspots->have_posts() ) : $hotspots->the_post();
				$risk = get_post_meta( get_the_ID(), 'somca_risk_level', true ) ?: 'moderate';
				?>
				<article class="somca-card">
					<div class="somca-card-body">
						<span class="somca-risk-badge somca-risk-<?php echo esc_attr( $risk ); ?>"><?php echo esc_html( ucfirst( $risk ) ); ?></span>
						<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php endif; ?>

	<?php
	$reports = new WP_Query( array(
		'post_type'      => 'somca_report',
		'posts_per_page' => 6,
		'tax_query'      => array( array( 'taxonomy' => 'somca_region', 'field' => 'term_id', 'terms' => $term->term_id ) ),
	) );
	if ( $reports->have_posts() ) :
		?>
		<h2><?php esc_html_e( 'Recent Reports', 'somca' ); ?></h2>
		<div class="somca-card-grid">
			<?php while ( $reports->have_posts() ) : $reports->the_post(); ?>
				<article class="somca-card">
					<div class="somca-card-body">
						<div class="somca-card-meta"><?php echo esc_html( implode( ', ', wp_get_post_terms( get_the_ID(), 'somca_period', array( 'fields' => 'names' ) ) ) ); ?></div>
						<h3 class="somca-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					</div>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
