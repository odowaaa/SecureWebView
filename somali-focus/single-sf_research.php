<?php
/**
 * Single research/publication page.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$sf_id       = get_the_ID();
	$sf_terms    = get_the_terms( $sf_id, 'research_category' );
	$sf_category = ( $sf_terms && ! is_wp_error( $sf_terms ) ) ? $sf_terms[0]->name : '';
	$sf_pdf_id   = (int) sf_theme_meta( $sf_id, 'report_pdf', 0 );
	$sf_pdf_url  = $sf_pdf_id ? wp_get_attachment_url( $sf_pdf_id ) : '';
	$sf_ext_url  = sf_theme_meta( $sf_id, 'external_url' );
	?>
	<article <?php post_class( 'single-research' ); ?>>

		<section class="page-hero sf-reveal">
			<div class="container">
				<?php sf_breadcrumbs(); ?>
				<?php if ( $sf_category ) : ?><p class="eyebrow"><?php echo esc_html( $sf_category ); ?></p><?php endif; ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
			</div>
		</section>

		<section class="page-content-section sf-reveal">
			<div class="container single-layout">
				<div class="single-layout__main entry-content">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="single-course__image"><?php the_post_thumbnail( 'sf-hero', array( 'alt' => get_the_title() ) ); ?></div>
					<?php endif; ?>

					<?php
					$sf_abstract = sf_theme_meta( $sf_id, 'abstract' );
					if ( $sf_abstract ) :
						?>
						<h2><?php esc_html_e( 'Abstract', 'somali-focus' ); ?></h2>
						<p><?php echo esc_html( $sf_abstract ); ?></p>
					<?php endif; ?>

					<?php the_content(); ?>

					<?php if ( $sf_pdf_url ) : ?>
						<p><a class="btn btn--primary" href="<?php echo esc_url( $sf_pdf_url ); ?>" download><?php sf_icon( 'download' ); ?> <?php esc_html_e( 'Download Full Report (PDF)', 'somali-focus' ); ?></a></p>
					<?php elseif ( $sf_ext_url ) : ?>
						<p><a class="btn btn--primary" href="<?php echo esc_url( $sf_ext_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View External Publication', 'somali-focus' ); ?></a></p>
					<?php endif; ?>
				</div>

				<aside class="single-layout__sidebar">
					<div class="course-summary-card">
						<h2 class="course-summary-card__title"><?php esc_html_e( 'Publication Details', 'somali-focus' ); ?></h2>
						<ul class="course-summary-card__list">
							<?php
							$sf_rows = array(
								array( 'users', __( 'Authors', 'somali-focus' ), sf_theme_meta( $sf_id, 'authors' ) ),
								array( 'calendar', __( 'Publication Date', 'somali-focus' ), sf_format_date( sf_theme_meta( $sf_id, 'publication_date' ) ) ),
								array( 'compass', __( 'Methodology', 'somali-focus' ), sf_theme_meta( $sf_id, 'methodology' ) ),
								array( 'book', __( 'Keywords', 'somali-focus' ), sf_theme_meta( $sf_id, 'keywords' ) ),
							);
							foreach ( $sf_rows as $sf_row ) :
								if ( empty( $sf_row[2] ) ) {
									continue;
								}
								?>
								<li><?php sf_icon( $sf_row[0] ); ?><span><strong><?php echo esc_html( $sf_row[1] ); ?></strong><br><?php echo esc_html( $sf_row[2] ); ?></span></li>
								<?php
							endforeach;
							?>
						</ul>
					</div>
				</aside>
			</div>
		</section>

		<?php
		$sf_related = sf_theme_get_research( 3 );
		if ( ! empty( $sf_related ) ) :
			?>
			<section class="content-highlight content-highlight--tinted sf-reveal">
				<div class="container">
					<header class="section-header">
						<div><h2 class="section-title"><?php esc_html_e( 'Related Research', 'somali-focus' ); ?></h2></div>
					</header>
					<div class="card-grid card-grid--3">
						<?php foreach ( $sf_related as $sf_item ) :
							if ( $sf_item->ID === $sf_id ) {
								continue;
							}
							get_template_part( 'template-parts/research-card', null, array( 'post' => $sf_item ) );
						endforeach;
						?>
					</div>
				</div>
			</section>
			<?php
		endif;
		?>

	</article>
	<?php
endwhile;

get_footer();
