<?php
/**
* Template for post entry thumbnail
 *
 * @package Starter
 */

if ( has_post_thumbnail( get_the_ID() ) ) {
    ?>
    <div class="entry-image">
        <a class="d-block" href="<?php echo esc_url( get_permalink() ); ?>">
            <figure class="img-container">
                <?php
                starter_the_post_thumbnail(
                    get_the_ID(),
                    'starter-blog-image',
                    [
                        'sizes' => '(max-width: 1200px) 1200px, 630px',
                        'class' => 'attachment-blog-image'
                    ]
                )
                ?>
            </figure>
        </a>
    </div>
    <?php
}