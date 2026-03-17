<?php
/**
 * Enqueue scripts and styles.
 *
 * NOTE: We do NOT deregister WP default jQuery on admin pages.
 * jQuery is only replaced on the FRONTEND (non-admin) only.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Frontend styles.
 */
function york_enqueue_styles(): void {
	// Local fonts.
	wp_enqueue_style(
		'york-fonts',
		YORK_URI . '/assets/css/york-fonts.css',
		array(),
		YORK_VERSION
	);

	// Bootstrap.
	wp_enqueue_style(
		'york-bootstrap',
		YORK_URI . '/assets/css/bootstrap.min.css',
		array(),
		'5.3.0'
	);

	// Font Awesome.
	wp_enqueue_style(
		'york-fontawesome',
		YORK_URI . '/assets/css/all.min.css',
		array(),
		'6.0.0'
	);

	// Owl Carousel.
	wp_enqueue_style(
		'york-owl-carousel',
		YORK_URI . '/assets/css/owl.carousel.min.css',
		array(),
		'2.3.4'
	);

	// Slick slider.
	wp_enqueue_style(
		'york-slick',
		'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css',
		array(),
		'1.8.1'
	);
	wp_enqueue_style(
		'york-slick-theme',
		'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css',
		array( 'york-slick' ),
		'1.8.1'
	);

	// Main theme stylesheet.
	wp_enqueue_style(
		'york-main',
		get_stylesheet_uri(),
		array( 'york-bootstrap', 'york-fontawesome' ),
		YORK_VERSION
	);

	// Events section styles.
	wp_enqueue_style(
		'york-events',
		YORK_URI . '/assets/css/york-events.css',
		array( 'york-main' ),
		YORK_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'york_enqueue_styles' );

/**
 * Frontend scripts.
 * Replace WP jQuery with local copy on FRONTEND ONLY.
 */
function york_enqueue_scripts(): void {
	// Replace WP jQuery with local copy — frontend only.
	wp_deregister_script( 'jquery' );
	wp_register_script(
		'jquery',
		YORK_URI . '/assets/js/jquery.min.js',
		array(),
		'3.7.1',
		true
	);
	wp_enqueue_script( 'jquery' );

	// Bootstrap JS.
	wp_enqueue_script(
		'york-bootstrap',
		YORK_URI . '/assets/js/bootstrap.bundle.min.js',
		array( 'jquery' ),
		'5.3.0',
		true
	);

	// Owl Carousel.
	wp_enqueue_script(
		'york-owl-carousel',
		YORK_URI . '/assets/js/owl.carousel.js',
		array( 'jquery' ),
		'2.3.4',
		true
	);

	// Slick Carousel.
	wp_enqueue_script(
		'york-slick',
		'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js',
		array( 'jquery' ),
		'1.8.1',
		true
	);

	// Main theme JS.
	wp_enqueue_script(
		'york-main',
		YORK_URI . '/assets/js/york-main.js',
		array( 'jquery', 'york-slick' ),
		YORK_VERSION,
		true
	);

	wp_localize_script(
		'york-main',
		'yorkTheme',
		array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'themeUrl' => YORK_URI,
			'isHome'   => is_front_page() ? 'true' : 'false',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'york_enqueue_scripts' );

/**
 * Defer non-critical frontend scripts.
 *
 * @param string $tag    Script HTML tag.
 * @param string $handle Script handle.
 * @return string
 */
function york_defer_scripts( string $tag, string $handle ): string {
	// Only defer on frontend, not admin.
	if ( is_admin() ) {
		return $tag;
	}

	$defer_handles = array( 'york-slick', 'york-owl-carousel', 'york-main' );

	if ( in_array( $handle, $defer_handles, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'york_defer_scripts', 10, 2 );
