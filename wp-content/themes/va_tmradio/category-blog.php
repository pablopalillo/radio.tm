<?php get_header(); ?>
<div id="primary" class="post col-sm-9 col-xs-12" role="main"> 
 	<?php
 		$first = true;

		if (have_posts()) :
				while (have_posts() ) : the_post();	?>
				<?php if($first): ?>
					<?php get_template_part( 'content-blog', get_post_format() ); ?>
					<?php $first = false; ?>
				<?php else: ?>
				 	<?php get_template_part( 'content-blog','2' ,get_post_format() ); ?>
				<?php endif;?>				 
			<?php endwhile; ?>	
			
			<div class="pag">
				<?php
				/* paginacion marca telemedellin */
				if ( function_exists( 'bt_pagination' ) ) 
				{
					bt_pagination();
				}
				?>
			</div>
		<?php endif; ?>
</div>

<?php get_footer(); ?>