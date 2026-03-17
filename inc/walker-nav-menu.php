<?php
/**
 * Custom Navigation Walker.
 *
 * Extends Walker_Nav_Menu to produce semantic HTML with
 * proper ARIA attributes for accessibility and dropdown support.
 * Mobile hamburger toggle is handled via vanilla JS in york-main.js.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class York_Walker_Nav_Menu
 *
 * Produces accessible nav markup with aria-expanded,
 * aria-haspopup, and role attributes on dropdown items.
 */
class York_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Start the element output.
	 *
	 * @param string    $output Used to append additional content.
	 * @param \WP_Post  $data_object Menu item data object.
	 * @param int       $depth  Depth of menu item. 0 = top level.
	 * @param \stdClass $args   Object of wp_nav_menu() arguments.
	 * @param int       $id     Current item ID.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $id = 0 ): void {
		$item = $data_object;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// CSS classes for the <li>.
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = implode( ' ', array_filter( apply_filters( 'nav_menu_css_class', $classes, $item, $args, $depth ) ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		// Build anchor attributes.
		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '#';

		// ARIA: mark items with children as haspopup + expanded.
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** @var object $args */
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a' . $attributes . '>';
		$item_output .= ( isset( $args->link_before ) ? $args->link_before : '' ) . $title . ( isset( $args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Start level — open the <ul> for a dropdown submenu.
	 *
	 * @param string    $output  Used to append content.
	 * @param int       $depth   Depth of current submenu.
	 * @param \stdClass $args    wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ): void {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<ul class=\"sub-menu\" role=\"menu\">\n";
	}
}
