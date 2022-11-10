<?php
/**
 * Bootstraps the Theme.
 *
 * @package Starter
 */

namespace STARTER_THEME\Inc;

use STARTER_THEME\Inc\Traits\Singleton;

class Assets {
    use Singleton;

    protected function __construct() {
        // Load classes
        $this->setup_hooks(); 
    }

    protected function setup_hooks() {
        /**
         * Actions
         */
        add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );

        add_action( 'login_enqueue_scripts', [ $this, 'register_wp_default_login_styles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_wp_dashboard_scripts' ] );

    }

    /**
	 * Register Google fonts.
	 *
	 * @return string Google fonts URL for the theme.
	 */
	
	public function starter_fonts_url() {
		$font_url = '';
		/*
		 * Translators: If there are characters in your language that are not supported
		 * by Open Sans, translate this to 'off'. Do not translate into your own language.
		 */
		
		$primary_font = get_theme_mod( 'primary_font' ) ? get_theme_mod( 'primary_font' ) : 'Source+Sans+Pro:ital,wght@0,400;0,600;0,700;0,900;1,400;1,600&display=swap';
		$secondary_font = get_theme_mod( 'secondary_font' ) ? get_theme_mod( 'secondary_font' ) : 'Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap';
	
		if ( 'off' !== _x( 'on', 'Fonts: on or off', 'starter' ) ) {
			$query_args = array(
				'family' => $primary_font,
				//'family' => $primary_font.'&family='. $secondary_font
			);
			$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css2' );
		}
		return $font_url;
	}

    /**
     * Register front-end styles
     */
    public function register_styles() {      

        // Bootstrap 
        wp_register_style( 'bootstrap', STARTER_DIR_URI . '/assets/src/library/bootstrap/css/bootstrap.min.css', [], false, 'all' );

        // Icon Fonts
        wp_register_style( 'line-awesome', STARTER_DIR_URI . '/assets/src/fonts/line-awesome/css/line-awesome.min.css', [], false, 'all' );

        // Theme Google fonts
        wp_register_style( 'startert-fonts', $this->starter_fonts_url(), [], null );
        
        // Main & RTL Stylesheet
        wp_register_style( 'starter-style', get_stylesheet_uri(), [], filemtime( STARTER_DIR_PATH . '/style.css' ), 'all' );
        wp_style_add_data( 'starter-style', 'rtl', 'replace' );

        // Responsive file
        wp_register_style( 'starter-responsive', STARTER_DIR_URI . '/assets/css/responsive.css', [], filemtime( STARTER_DIR_PATH . '/assets/css/responsive.css' ) );

        // Enqueing all styles
        wp_enqueue_style( 'bootstrap' );
        wp_enqueue_style( 'line-awesome' );
        wp_enqueue_style( 'starter-style' );
        wp_enqueue_style( 'starter-responsive' );

        if( get_theme_mod( 'display_font' ) == 2 ):
            wp_enqueue_style( 'startert-fonts' );
        endif;

    }

    /**
     * Register front-end scripts
     */
    public function register_scripts() {        

        // Bootstrap Script
        wp_register_script( 'bootstrap', STARTER_DIR_URI . '/assets/src/library/bootstrap/js/bootstrap.bundle.min.js', [ 'jquery' ], false, true );

        // Smooth Scrool Script
        wp_register_script( 'smoothscroll', STARTER_DIR_URI . '/assets/src/js/smoothscroll.js', [ 'jquery' ], false, true );

        // Starter Custom js
        wp_register_script( 'starter-script', STARTER_DIR_URI . '/assets/js/custom.js', [ 'jquery' ], filemtime( STARTER_DIR_PATH . '/assets/js/main.js' ), true );

        // Enqueing all scripts
        wp_enqueue_script( 'bootstrap' );
        wp_enqueue_script( 'smoothscroll' );
        wp_enqueue_script( 'starter-script' );

        // Adding Dashicons in WordPress Front-end
        wp_enqueue_style( 'dashicons' );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

    }

    /**
     * Register WP default login styles
     */
    public function register_wp_default_login_styles() {

        // WP default login form style
        wp_register_style( 'starter-wp-login', STARTER_DIR_URI . '/assets/css/admin/login.css', [], filemtime( STARTER_DIR_PATH . '/assets/css/admin/login.css' ), 'all' );

        // Enqueing WP dashboard styles
        wp_enqueue_style( 'starter-wp-login' );
    }

    /**
     * Register WP dashboard scripts
     */
    public function register_wp_dashboard_scripts( $screen ) {

        // Widget media uploader js
        wp_register_script( 'starter-dashboard-script', STARTER_DIR_URI . '/assets/js/admin/widget.js', [ 'jquery' ], filemtime( STARTER_DIR_PATH . '/assets/js/admin/widget.js' ), true );
        
        // Enqueing all scripts
        if( 'widgets.php' == $screen ){
            wp_enqueue_script( 'starter-dashboard-script' );
        }

    }
}