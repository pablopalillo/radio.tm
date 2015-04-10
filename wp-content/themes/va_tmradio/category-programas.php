<?php get_header(); ?>
<div class="col-sm-9 col-xs-12 top-category">
	<div class="row">
	<?php 
	$catid = get_category_by_slug('programas')->term_id; 
	$categories = get_categories( array('hide_empty'=>0,'child_of' => $catid) );
	foreach($categories as $category):
	?>
		<div class="col-sm-4">
			<a href="<?php echo get_category_link( $category->cat_ID )?>">
				<h2><?php echo $category->name ?></h2>
				<?php if (function_exists('z_taxonomy_image_url')):?>
				<img src="<?php echo z_taxonomy_image_url($category->cat_ID, 'programa-thumb') ?>" alt="<?php echo $category->name ?> " /> -->
				<?php endif; ?>
			</a>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<?php get_footer(); ?>