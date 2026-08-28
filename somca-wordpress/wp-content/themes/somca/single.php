<?php
/**
 * Default single template for standard blog posts / news updates.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="somca-container somca-section">
	<div class="somca-layout">
		<main id="primary" class="somca-main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php somca_breadcrumb( array( array( 'label' => __( 'Home', 'somca' ), 'url' => home_url( '/' ) ), array( 'label' => __( 'News', 'somca' ), 'url' => get_permalink( get_option( 'page_for_posts' ) ) ), array( 'label' => get_the_title() ) ) ); ?>
				<header class="somca-entry-header">
					<h1><?php the_title(); ?></h1>
					<div class="somca-card-meta"><?php echo esc_html( get_the_date() ); ?> &middot; <?php the_author(); ?></div>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="somca-entry-thumb"><?php the_post_thumbnail( 'somca-hero' ); ?></div>
				<?php endif; ?>
				<div class="somca-entry-content">
					<?php the_content(); ?>
				</div>
				<?php if ( comments_open() || get_comments_number() ) : comments_template(); endif; ?>
			<?php endwhile; ?>
		</main>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>
