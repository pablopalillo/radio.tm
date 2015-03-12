<?php get_header(); ?>

<div class="col-sm-9 col-xs-12 inner-category">
	<div id="main-content" class="main-content">

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php
					// Start the Loop.
				if(have_posts()):

					while ( have_posts() ) : the_post();

						// Include the page content template.
						get_template_part( 'content', 'page' );

					endwhile;
				?>

				<div class="pag">
					<?php
					/* paginacion marca telemedellin */
					if ( function_exists( 'bt_pagination' ) ) 
					{
						bt_pagination();
					}
					?>

				</div>


				<?php 
				else:
					?>
						<div class="no-found">
							<h3>No existe contenido relacionado</h3>
						</div>
					<?php
				endif;	
				?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div><!-- #main-content -->
</div>

<?php
get_footer();