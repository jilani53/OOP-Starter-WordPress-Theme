<?php
/**
 * starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package starter
 */

if ( ! defined( 'STARTER_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'STARTER_VERSION', '1.0.0' );
}

if( ! defined( 'STARTER_DIR_PATH' ) ) {
	define( 'STARTER_DIR_PATH', untrailingslashit( get_template_directory() ) );
}

if( ! defined( 'STARTER_DIR_URI' ) ) {
	define( 'STARTER_DIR_URI', untrailingslashit( get_template_directory_uri() ) );
}

// Autoloader function
require_once STARTER_DIR_PATH . '/inc/helpers/autoloader.php';

// Custom template tags for this theme.
require_once STARTER_DIR_PATH . '/inc/helpers/template-tags.php';

// Custom template functions for this theme.
require_once STARTER_DIR_PATH . '/inc/helpers/template-functions.php';

// Customizer options for this theme.
require_once STARTER_DIR_PATH . '/inc/helpers/customizer.php';

// Customizer options for this theme.
require_once STARTER_DIR_PATH . '/inc/plugins/recommended-plugins.php';

function starter_get_theme_instance() {
	\STARTER_THEME\Inc\STARTER_THEME::get_instance();
}

// Call instance method
starter_get_theme_instance();
