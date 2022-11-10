<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package starter
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php 
	get_template_part( 'template-parts/components/blog/entry-header' );
	get_template_part( 'template-parts/components/blog/entry-thumbnail' );
	get_template_part( 'template-parts/components/blog/entry-content' );
	get_template_part( 'template-parts/components/blog/entry-footer' ); 
	?>

</article><!-- #post-<?php the_ID(); ?> -->
