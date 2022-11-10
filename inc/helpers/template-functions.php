<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package starter
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function starter_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'starter_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function starter_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'starter_pingback_header' );

/**
 * HTML tag compatible data validation
 */
function starter_kses_allowed_html( $tags, $context ) {
	switch( $context ) {
	  case 'allowed_html': 
		$tags = array(
		  'a'      => [
			  'href'  => [],
			  'title' => [],
		  ],
		  'br'     => [],
		  'em'     => [],
		  'strong' => [],
		  'b' => [],
		  'span' => [],
		  'del' => [],
		);
		return $tags;
	  default: 
		return $tags;
	}
}
add_filter( 'wp_kses_allowed_html', 'starter_kses_allowed_html', 10, 2 );
  
// Remove all script and style type as w3valitator
function starter_buffer_start() { 
	ob_start( 'starter_callback' ); 
}
add_action('wp_loaded', 'starter_buffer_start');
  
function starter_callback( $buffer ) {
	return preg_replace( "%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", ' ', $buffer );
}

/**
 * Change WP Default Logo and url
 */
function starter_login_logo() { ?>
    <style type="text/css">
        #login h1 a, 
		.login h1 a {
            background-image: url( <?php echo esc_url( get_theme_mod( 'light_logo_upload',''.get_template_directory_uri().'/images/light-logo.png' ) ); ?> );
			max-width: 170px;
			margin: 0 auto 0 auto;
			width: auto;
			background-size: 100%;
            box-shadow: none
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'starter_login_logo' );

// Change logo url
function starter_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'starter_login_logo_url' );

// Change url title
function starter_login_logo_url_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'starter_login_logo_url_title' );

/**
 * Change Form label
 */
add_action( 'login_head', function() {
	add_filter( 'gettext', function( $original_text, $translated_text, $text_domain ) {
		if( 'Username or Email Address' == $translated_text ) {
			$original_text = esc_html__( 'Username', 'starter' );
		}
		return $original_text;
	}, 10, 3 );
});

/**
 * WordPress register username validation
 */
function starter_validate_username($valid, $username ) {
		if (preg_match("/\\s/", $username)) {
   			// there are spaces
			return $valid = false;
		}        
	return $valid;
}
add_filter('validate_username' , 'starter_validate_username', 10, 2);
