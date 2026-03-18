<?php
/**
 * Register Custom Post Types.
 *
 * Registers the 'statistics' CPT used for USP/Key Stats blocks.
 * Admin creates posts with title + excerpt — no meta needed.
 * Icon is handled at the theme display level (USP template).
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the 'statistics' Custom Post Type.
 *
 * Hooked to 'init'.
 */
function york_register_statistics_cpt(): void {

	$labels = array(
		'name'               => _x( 'Statistics', 'Post type general name', 'york-alumni' ),
		'singular_name'      => _x( 'Statistic', 'Post type singular name', 'york-alumni' ),
		'menu_name'          => _x( 'Statistics', 'Admin Menu text', 'york-alumni' ),
		'add_new'            => __( 'Add New', 'york-alumni' ),
		'add_new_item'       => __( 'Add New Statistic', 'york-alumni' ),
		'edit_item'          => __( 'Edit Statistic', 'york-alumni' ),
		'view_item'          => __( 'View Statistic', 'york-alumni' ),
		'all_items'          => __( 'All Statistics', 'york-alumni' ),
		'search_items'       => __( 'Search Statistics', 'york-alumni' ),
		'not_found'          => __( 'No statistics found.', 'york-alumni' ),
		'not_found_in_trash' => __( 'No statistics found in Trash.', 'york-alumni' ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Key statistics and USP blocks displayed on the home page.', 'york-alumni' ),
		'public'             => false,   // Not publicly queryable — admin only.
		'show_ui'            => true,    // Show in WP Admin.
		'show_in_menu'       => true,    // Show in admin sidebar.
		'show_in_rest'       => false,   // No Gutenberg needed.
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-chart-bar',
		'capability_type'    => 'post',
		'map_meta_cap'       => true,
		'hierarchical'       => false,
		'supports'           => array(
			'title',    // Heading — e.g. "93.5% of York students"
			'excerpt',  // Description — e.g. "Lorem ipsum dolor sit amet"
		),
		'has_archive'        => false,
		'rewrite'            => false,
	);

	register_post_type( 'statistics', $args );
}
add_action( 'init', 'york_register_statistics_cpt' );