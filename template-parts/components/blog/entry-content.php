<?php
/**
* Template for post entry content
 *
 * @package Starter
 */
?>

<div class="entry-content">
    <?php
    the_content(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'starter' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post( get_the_title() )
        )
    );

    wp_link_pages(
        array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'starter' ),
            'after'  => '</div>',
        )
    );
    ?>
</div><!-- .entry-content -->