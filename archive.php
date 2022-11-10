<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package starter
 */

get_header();
?>

<header class="page-header starter-page-header">
	<div class="container">
		<div class="row">			
			<div class="col-md-12">
				<h1 class="page-title">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</h1>
			</div>
		</div>
	</div>
</header><!-- .page-header -->

<div class="starter-main-body">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<?php 
				if ( have_posts() ) : 
				
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						* Include the Post-Type-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Type name) and that will be used instead.
						*/
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;

					if( paginate_links() ): 
						starter_simple_pagination();
					endif; // End pagination checking

				else :

					/**
					 * Nothing found
					 */
					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>
			
			<div class="col-md-4">
				<div class="starter-sidebar">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();