<!doctype html>  
<html lang="es">
	<head>
		<meta charset="utf-8">
		<title><?php bloginfo('name') . wp_title( ' -', true, 'left' ); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!--[if lt IE 9]>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>			
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
  		<link rel="shortcut icon" href="/favicon.ico" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<header class="container">
			<div class="row">
				<h1 class="col-sm-3 col-xs-6">
					<a href="<?php echo home_url(); ?>">
						<img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-tmradio.jpg" alt="<?php bloginfo('name') ?>" />
					</a>
				</h1>
				<div class="col-sm-6 hidden-xs">
					<?php get_template_part('menu'); ?>
				</div>
				<div class="col-sm-3 col-xs-6">
					<ul class="redes">
						<li><a href="https://www.facebook.com/Telemedradio" class="facebook" rel="nofollow" target="_blank">Facebook</a></li>
						<li><a href="https://twitter.com/TelemedRadio" class="twitter" rel="nofollow" target="_blank">Twitter</a></li>
					</ul>
					<a href="<?php echo home_url(); ?>/senal-en-vivo.php" class="senal-en-vivo">Señal en vivo</a>
				</div>
			</div>
	    </header>
	    <div class="row">
			<div class="col-xs-12 visible-xs menu-movil">
				<?php get_template_part('menu'); ?>
			</div>
		</div>
		<div id="content" class="container">
			<div class="row">