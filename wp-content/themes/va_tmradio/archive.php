<?php 
/**
* Contenido de cada uno de los programas.
*
**/
get_header(); 

?>



<?php  if( get_category_by_slug(single_cat_title("", false))->category_parent == 5 ): ?>

<?php $catid 	 		= get_category_by_slug('programas')->term_id;  ?>
<?php $menorDate 		= menorPorCategoria( get_cat_ID( single_cat_title("", false)) )  ?>


<div class="col-sm-9 col-xs-12 inner-category">
	<div id="main-content-home" class="programas-inner">	
		<div class="category-info">
			<h1><?php single_cat_title('', true)?>
			</h1>
			<?php if (function_exists('z_taxonomy_image_url')):?>
			<p><img src="<?php echo z_taxonomy_image_url($category->cat_ID, 'programa-interna'); ?>" alt="<?php single_cat_title('', true)?>" /></p>
			<?php endif; ?>
			<?php echo category_description(); ?> 
		</div>

		<div id="programas-seccion">
			<h3 class="titulo-programa-h3">Programas</h3>

			<div class="programas-select">
				<label for="mes">Selecciona el mes: </label>
				<div class="select-mask">
					<?php
					 /** Funcion de mostrar el select con los meses 
						 desde programa mas antiguo , para mas info 
						 consulte el archivo functions.php **/ 
						 echo selectMeses($menorDate);
					?>
				</div>
			</div>

			<div id="podcast-area" cat="<?php echo get_cat_ID( single_cat_title("", false)) ?>"></div>

		</div>

	</div>
</div>
<?php else: ?>

<div class="col-sm-9 col-xs-12 inner-category">
	<div id="main-content" class="main-content">

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php
					// Start the Loop.
				if(have_posts()):

					while ( have_posts() ) : the_post();

						// Include the page content template.
						//get_template_part( 'content', 'page' );

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
<?php endif; ?>
<?php
get_footer();
