<?php
/**
 * Bootstraps the Theme.
 *
 * @package Starter
 */

namespace STARTER_THEME\Inc;

use STARTER_THEME\Inc\Traits\Singleton;

class Menus extends \Walker_Nav_Menu {
    use Singleton;

    protected function __construct() {
        // Load classes
        $this->setup_hooks(); 
    }

    protected function setup_hooks() {
        /**
         * Actions
         */
        add_action( 'init', [ $this, 'register_menus' ] );
    }

    /**
     * Register menus
     */
    public function register_menus() {
        // This theme uses wp_nav_menu() in one location.
		register_nav_menus([
			'starter-primary' => esc_html__( 'Primary Menu', 'starter' ),
            'starter-footer-menu' => esc_html__( 'Footer Menu', 'starter' ),
		]);
    }

    /**
     * Menu fallback
     */
    public function primary_menu_fallback(){
        echo '<ul class="navbar-nav ml-auto main-menu-nav"><li><a href="'.esc_url( admin_url( 'nav-menus.php' ) ).'"></a></li></ul>';
    }

    /**
     * Start Level.
     *
     * @access public
     * @param mixed &$output Output.
     * @param int   $depth (default: 0) Depth.
     * @param array $args (default: array()) Arguments.
     * @return void
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent  = str_repeat( "\t", $depth );
        $submenu = ( $depth > 0 ) ? ' submenu' : '';
        $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
    }

    /**
     * Start El.
     *
     * @access public
     * @param mixed &$output Output.
     * @param mixed $item Item.
     * @param int   $depth (default: 0) Depth.
     * @param array $args (default: array()) Arguments.
     * @param int   $id (default: 0) ID.
     * @return void
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent        = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $li_attributes = '';
        $class_names   = $value = '';
        $has_mega_menu = is_active_sidebar( 'mega-menu-item-' . $item->ID );

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        // managing divider: add divider class to an element to get a divider before it.
        $divider_class_position = array_search( 'divider', $classes, true );

        if ( false !== $divider_class_position ) {
            $output .= "<li class=\"divider\"></li>\n";
            unset( $classes[ $divider_class_position ] );
        }

        $classes[] = ( $args->has_children || $has_mega_menu ) ? 'dropdown' : '';
        $classes[] = ( $item->current || $item->current_item_ancestor ) ? 'active' : '';
        $classes[] = 'nav-item-' . $item->ID;

        if ( $depth && $args->has_children ) {
            $classes[] = 'dropdown-submenu';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = ' class="nav-item ' . esc_attr( $class_names ) . '"';

        $id = apply_filters( 'nav_menu_item_id', 'nav-item-' . $item->ID, $item, $args );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
        $attributes .= ( $args->has_children || $has_mega_menu ) ? ' class="nav-link dropdown-toggle" id="navbarDropdownMenuLink'. $item->ID .'" role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '';

        $item_output  = $args->before;
        //$item_output .= '<a' . $attributes . ' class="nav-link">';
        $item_output .= ( ( 0 <= $depth ) && ( $args->has_children || $has_mega_menu ) ) ? '<a' . $attributes . ' class="dropdown-item">' : '<a' . $attributes . ' class="nav-link">';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= ( ( 0 === $depth || 1 ) && ( $args->has_children || $has_mega_menu ) ) ? ' <i class="las la-angle-down"></i></a>' : '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

        if ( $has_mega_menu ) {
            $output .= "<ul id=\"mega-menu-{$item->ID}\" class=\"mega-menu-wrapper dropdown-menu depth_" . $depth . '"><li><div class="container"><div class="row">';
            ob_start();
                dynamic_sidebar( 'mega-menu-item-' . $item->ID );
                $dynamic_sidebar = ob_get_contents();
            ob_end_clean();
            $output .= $dynamic_sidebar;
            $output .= '</div></div></li></ul>';
        }
    }

    /**
     * Display Element.
     *
     * @access public
     * @param mixed $element Element.
     * @param mixed &$children_elements Children Elements.
     * @param mixed $max_depth Max Depth.
     * @param int   $depth (default: 0) Depth.
     * @param mixed $args Arguments.
     * @param mixed &$output Output.
     */
    public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

        if ( ! $element ) {
            return;
        }

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_array( $args[0] ) ) {
            $args[0]['has_children'] = ! empty( $children_elements[ $element->$id_field ] );
        } elseif ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
        }
        $cb_args = array_merge( array( &$output, $element, $depth ), $args );
        call_user_func_array( array( &$this, 'start_el' ), $cb_args );

        $id = $element->$id_field;

        // Descend only when the depth is right and there are childrens for this element.
        if ( ( 0 === $max_depth || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {
            foreach ( $children_elements[ $id ] as $child ) {
                if ( ! isset( $newlevel ) ) {
                    $newlevel = true;
                    // Start the child delimiter.
                    $cb_args = array_merge( array( &$output, $depth ), $args );
                    call_user_func_array( array( &$this, 'start_lvl' ), $cb_args );
                }

                $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
            }

            unset( $children_elements[ $id ] );
        }

        if ( isset( $newlevel ) && $newlevel ) {
            // End the child delimiter.
            $cb_args = array_merge( array( &$output, $depth ), $args );
            call_user_func_array( array( &$this, 'end_lvl' ), $cb_args );
        }

        // End this element.
        $cb_args = array_merge( array( &$output, $element, $depth ), $args );
        call_user_func_array( array( &$this, 'end_el' ), $cb_args );
    }
    
}
