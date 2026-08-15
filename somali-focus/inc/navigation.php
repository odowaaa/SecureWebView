<?php
/**
 * Navigation menu walker + helpers for the sticky header and mobile nav.
 *
 * @package SomaliFocus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds a submenu-toggle button and accessible attributes to top-level
 * items that have children, so dropdowns are keyboard and screen-reader
 * friendly without extra markup duplication in header.php.
 */
class Somali_Focus_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Start the element output, injecting a dropdown toggle where needed.
	 *
	 * @param string   $output Passed by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   wp_nav_menu() args.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		$classes[] = 'menu-item';
		$class_names = 'class="' . esc_attr( implode( ' ', array_filter( $classes ) ) ) . '"';

		$output .= '<li ' . $class_names . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$output .= '<a class="menu-link"' . $attributes . '>' . esc_html( $title ) . '</a>';

		if ( $has_children && 0 === $depth ) {
			$output .= '<button type="button" class="submenu-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Open submenu', 'somali-focus' ) . '"><span aria-hidden="true">+</span></button>';
		}
	}
}

/**
 * Standard args for the primary nav wp_nav_menu() call, reused by both
 * the desktop header and the mobile off-canvas panel.
 *
 * @param string $container_class Wrapper class.
 * @return array
 */
function sf_primary_menu_args( $container_class = 'primary-menu' ) {
	return array(
		'theme_location' => 'primary',
		'container'      => false,
		'menu_class'     => $container_class,
		'fallback_cb'    => 'somali_focus_fallback_menu',
		'walker'         => new Somali_Focus_Nav_Walker(),
		'depth'          => 2,
	);
}
