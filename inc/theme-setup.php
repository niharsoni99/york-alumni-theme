<?php
/**
 * Theme setup — supports, image sizes, navigation menus.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme features after WordPress is ready.
 */
function york_theme_setup(): void {
	// Allow WordPress to manage the <title> tag.
	add_theme_support( 'title-tag' );

	// Enable post thumbnail support.
	add_theme_support( 'post-thumbnails' );

	// Enable HTML5 markup for core elements.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' )
	);

	// Enable custom logo in Customizer.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Enable wide and full alignment blocks (Gutenberg).
	add_theme_support( 'align-wide' );

	// Enable responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Register custom image sizes.
	add_image_size( 'york-hero',    1920, 676, true );
	add_image_size( 'york-card',    640,  400, true );
	add_image_size( 'york-thumb',   400,  300, true );
	add_image_size( 'york-alumni',  560,  420, true );

	// Text domain for translations.
	load_theme_textdomain( 'york-alumni', YORK_DIR . '/languages' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary'       => __( 'Primary Navigation', 'york-alumni' ),
			'header-cta'    => __( 'Header CTA Buttons', 'york-alumni' ),
			'footer-col-1'  => __( 'Footer Column 1', 'york-alumni' ),
			'footer-col-2'  => __( 'Footer Column 2', 'york-alumni' ),
			'footer-col-3'  => __( 'Footer Column 3', 'york-alumni' ),
		)
	);
}
add_action( 'after_setup_theme', 'york_theme_setup' );

/**
 * Register sidebar widget areas.
 */
function york_register_sidebars(): void {
	register_sidebar(
		array(
			'name'          => __( 'Main Sidebar', 'york-alumni' ),
			'id'            => 'sidebar-main',
			'description'   => __( 'Widgets in this area appear in the page sidebar.', 'york-alumni' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'york_register_sidebars' );
