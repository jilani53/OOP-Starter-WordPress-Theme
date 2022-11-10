<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package starter
 */

get_header();
?>

<div class="starter-main-body">
	<div class="container">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<?php

				/**
				 * Breadcrumbs
				 */
				\STARTER_THEME\Inc\Breadcrumbs::get_instance()->starter_breadcrumbs();

				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

					the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'starter' ) . '</span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'starter' ) . '</span> <span class="nav-title">%title</span>',
						)
					);

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div>			
		</div>
	</div>
</div>

<?php
get_footer();
