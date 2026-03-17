<?php
/**
 * Performance optimisations.
 *
 * - Remove unused WordPress default scripts and styles.
 * - Remove emoji scripts (saves ~15 KB).
 * - Remove block library CSS on non-Gutenberg pages.
 * - Remove classic theme styles.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove WordPress emoji scripts and styles.
 * Emoji are rendered natively by browsers — no JS needed.
 */
function york_remove_emoji(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'york_remove_emoji' );

/**
 * Remove unused <link> tags from <head>.
 */
function york_remove_head_junk(): void {
	// Remove Windows Live Writer manifest link.
	remove_action( 'wp_head', 'wlwmanifest_link' );

	// Remove Really Simple Discovery link.
	remove_action( 'wp_head', 'rsd_link' );

	// Remove WordPress generator meta tag (security).
	remove_action( 'wp_head', 'wp_generator' );

	// Remove shortlink.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Remove REST API link.
	remove_action( 'wp_head', 'rest_output_link_wp_head' );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'york_remove_head_junk' );

/**
 * Remove block editor (Gutenberg) CSS on non-block pages.
 * This saves the browser from loading ~80 KB of unused CSS.
 */
function york_remove_block_css(): void {
	// Remove core block styles.
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );

	// Remove classic theme styles added in WP 6.1+.
	wp_dequeue_style( 'classic-theme-styles' );

	// Remove global styles added in WP 5.9+.
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'york_remove_block_css', 100 );
