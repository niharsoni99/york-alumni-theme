<?php
/**
 * York Alumni Theme — functions.php
 *
 * Keep this file clean. All features are split into
 * separate include files under inc/.
 *
 * @package YorkAlumni
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Theme version constant for cache-busting.
define( 'YORK_VERSION', '1.0.0' );
define( 'YORK_DIR', get_template_directory() );
define( 'YORK_URI', get_template_directory_uri() );

/**
 * Load feature files from inc/.
 * Each file handles one responsibility only.
 */
$york_includes = array(
	'/inc/theme-setup.php',      // Theme supports, image sizes, nav menus
	'/inc/enqueue.php',          // Scripts and styles
	'/inc/walker-nav-menu.php',  // Custom Walker for dropdown nav
	'/inc/meta-boxes.php',       // Home page + Event meta boxes
	'/inc/template-functions.php', // Helper functions used in templates
	'/inc/performance.php',      // Remove unused WP scripts/styles
	'/inc/post-type.php',
	'/inc/usp-meta-box.php',

	
);

foreach ( $york_includes as $file ) {
	$filepath = YORK_DIR . $file;
	if ( file_exists( $filepath ) ) {
		require_once $filepath;
	}
}
