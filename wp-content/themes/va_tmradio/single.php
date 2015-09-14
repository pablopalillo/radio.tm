<?php
/**
 * The template for displaying single post
 */

get_header(); ?>

<div class="col-sm-9 col-xs-12 inner-category">
	<div id="main-content" class="main-content">

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();

						// Include the page content template.
						get_template_part( 'content', get_post_format()  );

					endwhile;
				?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div><!-- #main-content -->
</div>

<?php
get_footer();
