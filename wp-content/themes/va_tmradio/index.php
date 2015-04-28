<?php get_header(); ?>
<div id="primary" class="post col-sm-9 col-xs-12" role="main"> 
 	<?php
		$args = array('cat'=>20);
		$query = new WP_Query( $args );

		if ($query->have_posts()) :
				while ($query->have_posts() ) : $query->the_post();	?>
					<?php get_template_part( 'content-blog', get_post_format() ); ?>
			<?php endwhile; ?>	
		<?php endif; ?>
</div>

<?php get_footer(); ?>