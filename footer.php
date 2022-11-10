<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package starter
 */

if( get_theme_mod('copy_text') ):
?>

	<footer class="starter-footer">
		<div class="container">

			<?php if( is_active_sidebar( 'footer-about' ) || is_active_sidebar( 'footer-link' ) ): ?>
			<div class="row footer-top-section">
				<div class="col-md-4">						
					<?php

					if( is_active_sidebar( 'footer-about' ) ):
						dynamic_sidebar('footer-about');
					endif;

					?>
				</div>

				<div class="col-md-8">
					<div class="footer-essential-links">
						<div class="row">
							<?php
							if( is_active_sidebar( 'footer-link' ) ):
								dynamic_sidebar('footer-link');
							endif;
							?>

						</div>
					</div>  
				</div>
			</div>
			<?php endif; ?>

			<div class="col-md-12">
				<div class="footer-bottom">
					<div class="row">
						<div class="col-md-5">
							<?php echo wp_kses( get_theme_mod('copy_text'), 'allowed_html' ); ?>
						</div>
						<div class="col-md-7">
							<?php /* Footer navigation */
							wp_nav_menu(
								[
									'theme_location' => 'starter-footer-menu',
									'depth'          => 1,
									'container'      => false,
									'menu_class'     => 'footer-nav',
									'fallback_cb'    => \STARTER_THEME\Inc\Menus::get_instance()->primary_menu_fallback(),
									//Process nav menu using our custom nav walker
									'walker'         => \STARTER_THEME\Inc\Menus::get_instance(),
								]
							);
							?>
						</div>
					</div>  
					
				</div>  
			</div>

		</div>		
	</footer><!-- End Footer -->
	
</div><!-- End Body -->

<?php endif; /* end copyright */ wp_footer(); ?>

</body>
</html>
