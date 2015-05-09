<article id="post-<?php the_ID(); ?>" class="single" >
	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
		?>
	</header><!-- .entry-header -->

	<p class="date"><?php if( ! is_page() ) the_date('j \d\e F \d\e Y') ?></p>

	<?php if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php
		if( is_home() ):
			the_excerpt();	
		else:

			the_content('');
		endif;	
		?>

	</div><!-- .entry-content -->
	<?php endif; ?>

</article><!-- #post-## -->
