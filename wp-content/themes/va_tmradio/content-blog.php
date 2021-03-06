<article id="post-<?php the_ID(); ?>" role="article" class="post col-md-12">
	<header class="col-md-8">
		<figure class="image-article">
			<a href="<?php esc_url(the_permalink()) ?>" >
				<?php the_post_thumbnail('medium') ?>
			</a>
		</figure>
	</header>
		<section class="col-md-4">
			<h2 class="title-article"> <?php echo get_the_title() ?> </h2>
			<div class="post-content">
				<?php
					/**
					* Si tiene entradilla ya sea por cualquiera de los metodos,
					* que pongan el texto en el campo de la entradilla o poniento la etiqueta 'more'
					**/
					if( get_the_excerpt())
					{
						echo get_the_excerpt();
					}
					else
					{
						echo get_the_content('');
					}
				?>
			</div>
			<footer class="footer-content">
				<a href="<?php esc_url(the_permalink()) ?>" class="leer-mas" >
						Leer más
				</a>
			</footer>
		</section>

</article><!-- end article -->
