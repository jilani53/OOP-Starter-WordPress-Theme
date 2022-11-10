<?php
/**
 * Starter Theme Customizer
 *
 * @package Starter
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function starter_customize_register( $wp_customize ) {

    /**
     * Separator control in Customizer API
    */
    class Separator_Custom_control extends WP_Customize_Control {
        public $type = 'separator';
        public function render_content(){ ?>
            <h2><?php echo esc_html( $this->label ); ?></h2>
            <p><hr></p>
            <?php
        }
    }

    $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'starter_customize_partial_blogname',
        ) );
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'starter_customize_partial_blogdescription',
        ) );
    }

    /**
     * Site identity
     */

    // Logo
    $wp_customize->add_setting('light_logo_upload',

        array(
            'default' => '' . get_template_directory_uri() . '/images/light-logo.png',
            'sanitize_callback' => 'starter_sanitize_image',
        )

    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'light_logo_upload',
            array(
                'label'    => esc_html__('Light Logo', 'starter'),
                'section'  => 'title_tagline',
                'settings' => 'light_logo_upload',
                'priority' => '8',
            )
        )
    );

    // Sticky Logo
    $wp_customize->add_setting('sticky_logo_upload',

        array(
            'default' => '' . get_template_directory_uri() . '/images/logo.png',
            'sanitize_callback' => 'starter_sanitize_image',
        )

    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'sticky_logo_upload',
            array(
                'label'    => esc_html__('Sticky Logo', 'starter'),
                'section'  => 'title_tagline',
                'settings' => 'sticky_logo_upload',
                'priority' => '8',
            )
        )
    );

    // Sticky menu show/hide
    $wp_customize->add_setting(
        'display_font',
        array(
            'default'           => '1',
            'sanitize_callback' => 'starter_header_sanitize_radio',
        )
    );

    $wp_customize->add_control(
        'display_font',
        array(
            'type'     => 'radio',
            'label'    => esc_html__('Google font show/hide', 'starter'),
            'section'  => 'title_tagline',
            'priority' => '20',
            'choices'  => array(
                '1' => esc_html__('Default Font ( Recommended for better performance )', 'starter'),
                '2' => esc_html__('Google Font', 'starter'),
            ),
        )
    );

    
    /**
     * Footer section
     */

    // Footer Panel
    $wp_customize->add_panel('footer_panel', array(
        'priority'       => 30,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '',
        'title'          => esc_html__('Starter Footer', 'starter'),
        'description'    => esc_html__('Several settings pertaining starter theme', 'starter'),
    ));

        // Footer section
        $wp_customize->add_section('footer_section', array(

            'title'    => esc_html__('Footer information', 'starter'),
            'priority' => '20',
            'panel'    => 'footer_panel',

        ));

            /**
             * Footer fields
             */

            // Copyright text
            $wp_customize->add_setting('copy_text', array(

                'default'   => '',
                'transport' => 'refresh',
                'sanitize_callback' => 'wp_kses_post'

            ));
            $wp_customize->add_control('copy_text', array(

                'section' => 'footer_section',
                'label'   => esc_html__('Copyright text', 'starter'),
                'type'    => 'textarea',

            ));

            // Footer width
            $wp_customize->add_setting(
                'footer_width',
                array(
                    'default'           => '1',
                    'sanitize_callback' => 'starter_header_sanitize_radio',
                )
            );

            $wp_customize->add_control(
                'footer_width',
                array(
                    'type'     => 'radio',
                    'label'    => esc_html__('Footer Width ( Container or Container Fluid )', 'starter'),
                    'section'  => 'footer_section',
                    'priority' => '20',
                    'choices'  => array(
                        '1' => esc_html__('Container', 'starter'),
                        '2' => esc_html__('Container Fluid', 'starter'),
                    ),
                )
            );

            // Footer style
            $wp_customize->add_setting(
                'footer_type',
                array(
                    'default'           => '1',
                    'sanitize_callback' => 'starter_header_sanitize_radio',
                )
            );

            $wp_customize->add_control(
                'footer_type',
                array(
                    'type'     => 'radio',
                    'label'    => esc_html__('Footer General/Animate Background', 'starter'),
                    'section'  => 'footer_section',
                    'priority' => '20',
                    'choices'  => array(
                        '1' => esc_html__('General', 'starter'),
                        '2' => esc_html__('Background Animation', 'starter'),
                        '3' => esc_html__('Dark', 'starter'),
                    ),
                )
            );

    /**
     * Color Sanitization
     */
    function color_sanitize_hex_color($hex_color, $setting) {
        // Sanitize $input as a hex value.
        $hex_color = sanitize_hex_color($hex_color);
        // If $input is a valid hex value, return it; otherwise, return the default.
        return (!is_null($hex_color) ? $hex_color : $setting->default);
    }

    /**
     * Radio options sanitizations
     */
    function starter_header_sanitize_radio($input, $setting) {
        // Ensure input is a slug.
        $input = sanitize_key($input);
        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control($setting->id)->choices;
        // If the input is a valid key, return it; otherwise, return the default.
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }

    /**
     * File input sanitization function
     */
    function starter_sanitize_image( $input, $setting ) {

        $input = esc_url( $input );    
        $attrs = $setting->manager->get_control( $setting->id )->input_attrs;
        
        $extension = pathinfo( $input , PATHINFO_EXTENSION );
        
        if ( $input != $setting->default ) {
        
            if ( $extension == 'jpg' ) {
                return wp_get_attachment_image_src( attachment_url_to_postid( $input ) , $attrs['img_size'] )[0];
            } elseif ( $extension == 'jpeg' ) {
                return wp_get_attachment_image_src( attachment_url_to_postid( $input ) , $attrs['img_size'] )[0];
            } elseif ( $extension == 'png' ) {
                return wp_get_attachment_image_src( attachment_url_to_postid( $input ) , $attrs['img_size'] )[0];
            } elseif ( $extension == 'gif' ) {
                return $input;
            } elseif ( $extension == 'svg' && current_user_can('editor') || current_user_can('administrator') ) {
                return $input;
            }
            
        } else {            
            return esc_url( $setting->default );        
        }        
    }

    /**
     * Date sanitization function
     */
    function starter_sanitize_date( $input ) {
        $date = new DateTime( $input );
        return $date->format('Y-m-d');
    }

}
add_action('customize_register', 'starter_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function starter_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function starter_customize_partial_blogdescription() {
    bloginfo('description');
}
