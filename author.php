<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package starter
 */

get_header();

// Collect author name
$curauth = ( isset( $_GET['author_name'] )) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ));

?>

<header class="page-header starter-page-header">
	<div class="container">
		<div class="row">			
			<div class="col-md-12">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'About: %s', 'starter' ), '<span>' . $curauth->nickname . '</span>' );
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

					if ( is_home() && ! is_front_page() ) :
						?>
						<header>
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
						<?php
					endif;

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