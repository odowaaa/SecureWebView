<?php
/**
 * Search results — spans posts, courses, advisory, research and experts.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$sf_result_count = $wp_query->found_posts;
get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'eyebrow'     => __( 'Search', 'somali-focus' ),
		/* translators: %s: search query */
		'title'       => sprintf( __( 'Search results for "%s"', 'somali-focus' ), get_search_query() ),
		/* translators: %s: number of results found */
		'description' => sprintf( _n( '%s result found', '%s results found', $sf_result_count, 'somali-focus' ), number_format_i18n( $sf_result_count ) ),
	)
);
?>
<section class="page-content-section sf-reveal">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="card-grid card-grid--3">
				<?php
				while ( have_posts() ) :
					the_post();
					switch ( get_post_type() ) {
						case 'sf_course':
							get_template_part( 'template-parts/course-card', null, array( 'post' => get_post() ) );
							break;
						case 'sf_advisory':
							get_template_part( 'template-parts/advisory-card', null, array( 'post' => get_post() ) );
							break;
						case 'sf_research':
							get_template_part( 'template-parts/research-card', null, array( 'post' => get_post() ) );
							break;
						case 'sf_expert':
							get_template_part( 'template-parts/expert-card', null, array( 'post' => get_post() ) );
							break;
						default:
							get_template_part( 'template-parts/blog-card' );
					}
				endwhile;
				?>
			</div>
			<div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( "No results found. Try a different search term, or check your spelling — the terms below can also help you find what you're looking for.", 'somali-focus' ); ?></p>
			<div style="max-width:480px;margin:0 0 2rem;">
				<?php get_search_form(); ?>
			</div>
			<nav class="sf-404-links" aria-label="<?php esc_attr_e( 'Popular pages', 'somali-focus' ); ?>">
				<p style="font-weight:600;color:var(--sf-navy);"><?php esc_html_e( 'Popular pages:', 'somali-focus' ); ?></p>
				<ul style="list-style:none;display:flex;flex-wrap:wrap;gap:.75rem 1.5rem;padding:0;margin:.75rem 0 0;">
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'somali-focus' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/training/' ) ); ?>"><?php esc_html_e( 'Training', 'somali-focus' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/advisory/' ) ); ?>"><?php esc_html_e( 'Advisory', 'somali-focus' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/research/' ) ); ?>"><?php esc_html_e( 'Research', 'somali-focus' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/insights/' ) ); ?>"><?php esc_html_e( 'Insights', 'somali-focus' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'somali-focus' ); ?></a></li>
				</ul>
			</nav>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
