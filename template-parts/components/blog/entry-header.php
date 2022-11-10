<?php
/**
* Template for post entry header
 *
 * @package Starter
 */
?>

<header class="entry-header">
    <?php
    if ( is_singular() ) :
        the_title( '<h1 class="entry-title">', '</h1>' );
    else :
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
    endif;

    if ( 'post' === get_post_type() ) :
        ?>
        <div class="entry-meta">
            <?php
            starter_posted_on();
            starter_posted_by();
            ?>
        </div><!-- .entry-meta -->
    <?php endif; ?>
</header><!-- .entry-header -->