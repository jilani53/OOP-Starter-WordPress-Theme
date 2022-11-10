<?php
/**
 * Theme sidebars.
 *
 * @package Starter
 */

namespace STARTER_THEME\Inc;

use STARTER_THEME\Inc\Traits\Singleton;

class Sidebars {
    use Singleton;

    protected function __construct() {
        // Load classes
        $this->setup_hooks(); 
    }

    protected function setup_hooks() {
        /**
         * Actions
         */
        add_action( 'widgets_init', [ $this, 'starter_register_sidebar' ] );

    }

    /**
	 * Register Widgets.
	 */
	
	public function starter_register_sidebar() {

        register_sidebar(
            [
                'name'          => esc_html__('Sidebar', 'starter'),
                'id'            => 'sidebar',
                'description'   => esc_html__('Sidebar position.', 'starter'),
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
            ]
        );
    
        register_sidebar(
            [
                'name'          => esc_html__('Footer Link Widget', 'starter'),
                'id'            => 'footer-link',
                'description'   => esc_html__('Footer widget for link position.', 'starter'),
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
            ]
        );
    
        register_sidebar(
            [
                'name'          => esc_html__('Footer About Widget', 'starter'),
                'id'            => 'footer-about',
                'description'   => esc_html__('Footer widget for about position.', 'starter'),
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
            ]
        );
    
        register_sidebar(
            [
                'name'          => esc_html__('Footer Subscribe Widget', 'starter'),
                'id'            => 'footer-subscribe',
                'description'   => esc_html__('Footer widget for subscribe email form position.', 'starter'),
                'before_widget' => '<div class="widget">',
                'after_widget'  => '</div>',
            ]
        );
    
        register_sidebar(
            [
                'name'        => esc_html__('Footer Bottom Link Widget', 'starter'),
                'id'          => 'footer-bottom-link',
                'description' => esc_html__('Footer widget for bottom link position.', 'starter'),
            ]
        );
    
        register_sidebar(
            [
                'name'        => esc_html__('User Profile Link Wdiget Area', 'starter'),
                'id'          => 'user-profile-link',
                'description' => esc_html__('This is your widget profile link widget area to show usefull link.', 'starter'),
            ]
        );
    
        /**
         * Register MegaMenu widget if the Mega Menu is set as the menu location
         */
        $location = 'starter-primary';
        $css_class = 'has-mega-menu';
        $locations = get_nav_menu_locations();
        if ( isset( $locations[$location] ) ) {
            $menu = get_term( $locations[$location], 'nav_menu' );
            if ( $items = wp_get_nav_menu_items( $menu->name ) ) {
                foreach ( $items as $item ) {
                    if ( in_array( $css_class, $item->classes ) ) {
                        register_sidebar(
                            array(
                                'id'          => 'mega-menu-item-' . $item->ID,
                                'description' => 'Mega Menu items',
                                'name'        => $item->title . ' - Mega Menu',
                            )
                        );
                    }
                }
            }
        } // End Mega Menu
    }
}