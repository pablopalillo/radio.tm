<?php get_header(); ?>
<div id="primary" class="post col-sm-9 col-xs-12" role="main"> 
 	<?php
		if (have_posts()) :
				while (have_posts() ) : the_post();	?>
					<?php get_template_part( 'content-blog', get_post_format() ); ?>
			<?php endwhile; ?>	
		<?php endif; ?>
</div>

<?php get_footer(); ?>