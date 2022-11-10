<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'starter' ), '<span>' . get_search_query() . '</span>' );
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

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

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
