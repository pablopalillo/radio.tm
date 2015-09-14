<?php
add_theme_support( 'post-thumbnails' ); 
//add_image_size( 'programa-thumb', 220, 170, array('top', 'center') );
//add_image_size( 'programa-interna', 655, 320 );*/
//add_theme_support( 'menus' );
//add_theme_support( 'post-formats', array('audio') );

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

add_action("wp_ajax_nopriv_get_posts_by_mes", "get_posts_by_mes");
add_action("wp_ajax_get_posts_by_mes", "get_posts_by_mes");

add_action("wp_ajax_nopriv_get_podcast", "get_podcast");
add_action("wp_ajax_get_podcast", "get_podcast");

add_action("wp_ajax_nopriv_get_images_background", "get_images_background");
add_action("wp_ajax_get_images_background", "get_images_background");

function get_posts_by_mes() 
{

	$posts = false;

	if(isset($_REQUEST['mes']) && isset($_REQUEST['cat_id']))
	{
		$args = array('cat' => $_REQUEST['cat_id'], 'monthnum' =>  ( (int) $_REQUEST['mes'] ) + 1 ) ;
		//$args = array('cat' => $_REQUEST['cat_id']);
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

function get_images_background()
{
	$pathArchivos = array();

	try 
	{
		$directorio   = opendir( get_template_directory().'/images/background');
		
	} catch (Exception $e) 
	{
		echo 'Error al leer directorio'.$e->getMessage();
		die;
	}

	if($directorio)
	{
		while ( false !== ($archivo = readdir($directorio)) ) 
		{
			if( !is_dir($archivo))
			{
				$pathArchivos[]  =  $archivo;
			}
		}

		 closedir($directorio);

		if( !empty($pathArchivos) )
		{
			echo json_encode($pathArchivos, JSON_FORCE_OBJECT);
		}	
	}
	die();

}

/**
 * MenorPorCategoria
 *
 * funcion que permite segun la categoria mostrar
 * la fecha mas antigua de publicacion de un post.
 * de esa categoria 
 * @param cat_id = categoria de wordpress
 * @return date d/m/Y
 * @author Pablo Martinez
 **/

function menorPorCategoria($cat_id)
{
	  $dateMenor = null; 
	  $args 	 = array('cat'=>$cat_id, 'posts_per_page' => '-1' );
	  $the_query = new WP_Query($args); 

	  if ($the_query->have_posts())
	  {
	  	while ($the_query->have_posts()) : $the_query->the_post(); 
	  		
	  		if($dateMenor == null )
	  		{
	  			$dateMenor = get_the_date('d/m/Y');
	  		}
	  		else
	  		{
	  			if( strtotime(get_the_date('d/m/Y')) < strtotime($dateMenor)  )
	  			{
	  				$dateMenor = get_the_date('d/m/Y');
	  			}
	  		}

	  	endwhile;
	  }

	  return $dateMenor;

}


/**
 * SeletMeses
 * Retorna un select de html 
 * con los que hay antes del mes pasado por parametro.
 *
 * @return string (html)
 * @param date d/m/Y
 * @author Pablo Martinez
 **/

function selectMeses($fecha)
{
	if ( $fecha != null )
	{
		$contenido 	= '<select id="select-meses">';
		$meses 		= array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio',
							'Agosto','Septiembre','Octubre','Noviembre','Diciembre');

		try {

			$mesFecha  = DateTime::createFromFormat('d/m/Y',$fecha);
			$mesFecha  = $mesFecha->format('n');

			if( $mesFecha >= 0 && $mesFecha <= 12)
			{
				$contenido .= '<option selected >Seleccione</option>';

				if( $mesFecha > 1 )
				{
					for ($i = ( (int)$mesFecha - 1) ; $i < 12 ; $i ++ )
					{
			
						$contenido .= '<option value="'.$i.'">'.$meses[$i].'</option>';
					}
				}

				$contenido .= '</select>';
				return $contenido;
			}
			else
			{
 				throw new Exception('Mes no valido, posiblemente tenga el formato de fecha invalido.');
			}

		} catch (Exception $e) {

			 echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}

	}

	return null;

}



/**
* bt_pagination
* Funcion para crear paginado adaptado al estilo de boostrap
*
* @version  1.0
* @author Pablo Martínez
*/
function bt_pagination() 
{
	$prev_arrow = is_rtl() ? '&laquo;' : '&laquo;';
	$next_arrow = is_rtl() ? '&raquo;' : '&raquo;';

	global $wp_query;
	$curr 	= get_query_var('paged');
	settype($curr, "int"); 

	$total 	= $wp_query->max_num_pages;
	$big = 999999999;
	if( $total > 1 )  
	{
		if( !$current_page = $curr )
		{
			$current_page = 1;
		}
		if( get_option('permalink_structure') ) 
		{
			$format = 'page/%#%/';
		} else 
		{
			$format = '&paged=%#%';
		}

		$pag = paginate_links(array(
				'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'		=> $format,
				'current'		=> max( 1, $curr ),
				'total' 		=> $total,
				'mid_size'		=> 3,
				'type' 			=> 'list',
				'prev_text'		=> $prev_arrow,
				'next_text'		=> $next_arrow,
				) );

		$replace = str_replace("<li><span class='page-numbers current'>","<li class='active'><span class='page-numbers current'>", $pag );
		$replace = str_replace( "<ul class='page-numbers'>", '<ul class="pagination">', $replace );

		echo $replace;
	}
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