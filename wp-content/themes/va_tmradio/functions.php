<?php
add_theme_support( 'post-thumbnails' ); 
add_image_size( 'programa-thumb', 220, 170, array('top', 'center') );
add_image_size( 'programa-interna', 655, 320 );
add_theme_support( 'menus' );
add_theme_support( 'post-formats', array('audio') );

register_nav_menus(
	array( 
		'main_nav' => 'Menu principal',   // main nav in header
	)
);
register_sidebar(array(
  'id' => 'sidebar-home',
  'name' => 'Sidebar Home',
  'description' => '',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>',
  'before_title' => '<h4 class="widgettitle">',
  'after_title' => '</h4>',
));
register_sidebar(array(
  'id' => 'sidebar',
  'name' => 'Sidebar',
  'description' => '',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div>',
  'before_title' => '<h4 class="widgettitle">',
  'after_title' => '</h4>',
));

add_action("wp_ajax_nopriv_get_posts_by_moy", "get_posts_by_moy");
add_action("wp_ajax_get_posts_by_moy", "get_posts_by_moy");

add_action("wp_ajax_nopriv_get_podcast", "get_podcast");
add_action("wp_ajax_get_podcast", "get_podcast");

function get_posts_by_moy() 
{
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "posts_moy_nonce")) {
		exit("No naughty business please");
	}
	$posts = false;

	if(isset($_REQUEST['moy']) && isset($_REQUEST['cat_id']))
	{
		$args = array('cat' => $_REQUEST['cat_id'], 'm' => $_REQUEST['moy']);
	
		$posts = get_posts( $args );
	}
	if($posts === false) 
	{
		$result['type'] = "error";
	}
	else 
	{
		$result['type'] = "success";
		$result['posts'] = $posts;
	}
   
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		echo json_encode($result);
	}
	die();
}

function get_podcast()
{
	/*if ( !wp_verify_nonce( $_REQUEST['nonce'], "posts_moy_nonce")) {
		exit("No naughty business please");
	}/**/
	$post = false;

	if(isset($_REQUEST['post_id']) && $_REQUEST['post_id'] != NULL)
	{
		$args = array('p' => $_REQUEST['post_id']);
		$post = get_posts( $args );
	}
	if($post === false) 
	{
		$result['type'] = "error";
	}
	else 
	{
		$result['type'] = "success";
		$result['posts'] = $post;
	}

	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		echo json_encode($result);
	}
	die();
}

// enqueue styles
if( !function_exists("theme_styles") ) {  
    function theme_styles() { 
        wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '1.0', 'all' );
        wp_register_style( 'main', get_template_directory_uri() . '/style.css', array(), '1.0', 'all' );
        
        wp_enqueue_style( 'bootstrap' );
        wp_enqueue_style( 'main');
    }
}
add_action( 'wp_enqueue_scripts', 'theme_styles' );

if( !function_exists( "theme_js" ) ) {  
	function theme_js(){
		wp_register_script( 'bootstrap', 
		  get_template_directory_uri() . '/js/bootstrap.min.js', 
		  array('jquery'), 
		  '2.1.0' );

		wp_register_script( 'easyResponsiveTabs', 
		  get_template_directory_uri() . '/js/easyResponsiveTabs.js', 
		  array('jquery'), 
		  '2.1.0' );


		wp_register_script( 'backstretch', 
		  get_template_directory_uri() . '/js/jquery.backstretch.min.js', 
		  array('jquery'), 
		  '2.1.0' );

			wp_register_script( 'main', 
		  get_template_directory_uri() . '/js/main.js', 
		  array('jquery'), 
		  '2.1.0' );

		wp_localize_script( 'main', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));  

		wp_enqueue_script('bootstrap');
		wp_enqueue_script('easyResponsiveTabs');
		wp_enqueue_script('backstretch');
		wp_enqueue_script('main');

	}
}
add_action( 'wp_enqueue_scripts', 'theme_js' );
?>