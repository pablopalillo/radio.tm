<?php 
if(is_category())
{
	global $wpdb;
	$category = get_category(get_query_var('cat'));
    $cat_id = $category->cat_ID;

    $gdates = $wpdb->prepare( 
    	"SELECT wposts.post_date 
    	FROM {$wpdb->posts} wposts 
    	LEFT JOIN {$wpdb->postmeta} wpostmeta ON wposts.ID = wpostmeta.post_id 
    	LEFT JOIN {$wpdb->term_relationships} ON (wposts.ID = $wpdb->term_relationships.object_id) 
    	LEFT JOIN {$wpdb->term_taxonomy} ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) 
    	WHERE $wpdb->term_taxonomy.taxonomy = 'category' 
    	AND $wpdb->term_taxonomy.term_id IN(%d) 
    	GROUP BY MONTH(wposts.post_date)", $cat_id
    );
    $dates = $wpdb->get_results( $gdates );

    $nonce = wp_create_nonce("posts_moy_nonce");
}
?>
<?php get_header(); ?>
<div class="col-sm-9 col-xs-12 inner-category">
	<div id="main-content-home">

	<?php if(is_category()): ?>
		<div class="category-info">
		<h1><?php single_cat_title('', true)?></h1>
		<?php if (function_exists('z_taxonomy_image_url')):?>
			<p><img src="<?php echo z_taxonomy_image_url($category->cat_ID, 'programa-interna'); ?>" alt="<?php single_cat_title('', true)?>" /></p>
		<?php endif; ?>
		<?php echo category_description(); ?> 
		</div>
		<h3>Programas</h3>
		<form method="GET">
			<label for="mes">Selecciona el mes: </label>
			<div class="select-mask">
			<select name="mes" id="mes" data-nonce="<?php echo $nonce ?>" data-catid="<?php echo $cat_id ?>">
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
	<?php else: ?>

	<?php 
		$args  = array('category_name' => 'programas', 'orderby' => 'date', 'order' => 'DESC');
		$query = new WP_Query( $args );
	?>  
	<?php
	if($query->have_posts()):?>
		<h1>Últimas emisiones</h1>

		<?php while($query->have_posts()): $query->the_post();
				get_template_part( 'content', get_post_format() );
		endwhile; ?>

	<?php else: ?>

		<?php 
			$catid = get_category_by_slug('programas')->term_id; 
			$categories = get_categories( array('hide_empty'=>0,'child_of' => $catid) );
		?>
		<h1>Programas</h1>
		<?php
		foreach($categories as $category):
		?>
			<div class="col-sm-4">
				<a href="<?php echo get_category_link( $category->cat_ID )?>">
					<h2><?php echo $category->name ?></h2>
					<?php if (function_exists('z_taxonomy_image_url')):?>
						<img src="<?php echo z_taxonomy_image_url($category->cat_ID, 'programa-thumb'); ?>" alt="" />
					<?php endif; ?>
				</a>
			</div>
	<?php endforeach; ?>
	<?php endif; ?>
	<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>