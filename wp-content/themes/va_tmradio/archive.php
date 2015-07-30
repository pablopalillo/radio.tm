<?php get_header(); ?>

<?php  if( get_category_by_slug(single_cat_title("", false))->category_parent == 5 ): ?>

<?php $catid = get_category_by_slug('programas')->term_id;  ?>
<div class="col-sm-9 col-xs-12 inner-category">
	<div id="main-content-home">	
		<div class="category-info">
			<h1><?php single_cat_title('', true)?>
			</h1>
			<?php if (function_exists('z_taxonomy_image_url')):?>
			<p><img src="<?php echo z_taxonomy_image_url($category->cat_ID, 'programa-interna'); ?>" alt="<?php single_cat_title('', true)?>" /></p>
			<?php endif; ?>
			<?php echo category_description(); ?> 
		</div>
		<h3>Programas</h3>
		<form method="GET">
			<label for="mes">Selecciona el mes: </label>
			<div class="select-mask">
				<select name="mes" id="mes" data-nonce="<?php echo $nonce ?>" data-catid="<?php echo $catid ?>">
					<option value="" SELECTED>Seleccione el mes</option>
					<?php 
					foreach($dates as $date): 
						setlocale(LC_ALL, 'es_ES.UTF-8');
					$t = strtotime($date->post_date);
					$selected = (isset($_GET['mes']) && date('Ym', $t)==$_GET['mes'])?'selected':'';
					?>
					<option value="<?php echo date('Ym', $t) ?>"><?php echo strftime('%B de %Y', $t) ?></option>
				<?php endforeach; ?>
			</select>
			</div>
			<div class="select-mask">
				<select name="programa" id="programa" disabled>
					<option value="">Selecciona el podcast</option>
				</select>
			</div>
		</form>

		<div id="emision"></div>
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
<?php endif; ?>
<?php
get_footer();