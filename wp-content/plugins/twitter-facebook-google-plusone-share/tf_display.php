<?php 
/*
Core logic to display social share icons at the required positions. 
*/
require_once('tf_admin_page.php');

function twitter_facebook_share_init() {
	// DISABLED IN THE ADMIN PAGES
	if (is_admin()) {
		return;
	}

	//GET ARRAY OF STORED VALUES
	$option = twitter_facebook_share_get_options_stored();
	
	if (is_mobile_device() && ($option['mobdev']==true)){
	// disable for mobile device
		return;
	} 

	if ($option['active_buttons']['twitter']==true) {
		wp_enqueue_script('twitter_facebook_share_twitter', 'http'.(is_ssl()?'s':'').'://platform.twitter.com/widgets.js','','',$option['jsload']);
	}
	
	if ($option['active_buttons']['Google_plusone']==true) {
		wp_enqueue_script('twitter_facebook_share_google', 'http'.(is_ssl()?'s':'').'://apis.google.com/js/plusone.js','','',$option['jsload']);
	}
	if ($option['active_buttons']['linkedin']==true) {
		wp_enqueue_script('twitter_facebook_share_linkedin', 'http'.(is_ssl()?'s':'').'://platform.linkedin.com/in.js','','',$option['jsload']);
	}
/*
	if ($option['active_buttons']['pinterest']==true) {
		wp_enqueue_script('twitter_facebook_share_pinterest', 'http'.(is_ssl()?'s':'').'://assets.pinterest.com/js/pinit.js','','',$option['jsload']);
	}
*/
	wp_enqueue_style('tfg_style', '/wp-content/plugins/twitter-facebook-google-plusone-share/tfg_style.css');
	
	
}    

function kc_twitter_facebook_contents($content)
{
	return kc_twitter_facebook($content,'content');
}

function kc_twitter_facebook_excerpt($content)
{
	return kc_twitter_facebook($content,'excerpt');
}

function kc_twitter_facebook($content, $filter)
{
  global $single;
  static $last_execution = '';

  if ($filter=='the_excerpt' and $last_execution=='the_content') {
		remove_filter('the_content', 'kc_twitter_facebook_contents');
		$last_execution = 'the_excerpt';
		return the_excerpt();
	}
	if ($filter=='the_excerpt' and $last_execution=='the_excerpt') {
		add_filter('the_content', 'kc_twitter_facebook_contents');
	}
  
  $option = twitter_facebook_share_get_options_stored();
  $custom_disable = get_post_custom_values('disable_social_share');
  
  if (is_mobile_device() && ($option['mobdev']==true)){
	// disable for mobile device
		return $content;
	} 
  if (is_single() && ($option['show_in']['posts']) && ($custom_disable[0] != 'yes')) {
	    $output = kc_social_share('auto');
		$last_execution = $filter;
  		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
    } 
	if (is_home() && ($option['show_in']['home_page'])){
        $output = kc_social_share('auto');
		$last_execution = $filter;
		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
	}
	if (is_page() && ($option['show_in']['pages']) && ($custom_disable[0] != 'yes')) {
		  $output = kc_social_share('auto');
		  $last_execution = $filter;
  		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
    }  
	if (is_category() && ($option['show_in']['categories'])) {
		  $output = kc_social_share('auto');
		  $last_execution = $filter;
  		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
    } 
	if (is_tag() && ($option['show_in']['tags'])) {
		  $output = kc_social_share('auto');
		  $last_execution = $filter;
  		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
    } 
	if (is_author() && ($option['show_in']['authors'])) {
		  $output = kc_social_share('auto');
		  $last_execution = $filter;
  		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
    } 
	if (is_search() && ($option['show_in']['search'])) {
		  $output = kc_social_share('auto');
		  $last_execution = $filter;
  		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
    } 
	if (is_date() && ($option['show_in']['date_arch'])) {
		  $output = kc_social_share('auto');
		  $last_execution = $filter;
  		if ($option['position'] == 'above')
        	return  $output . $content;
		if ($option['position'] == 'below')
			return  $content . $output;
		if ($option['position'] == 'left')
			return  $output . $content;
		if ($option['position'] == 'both')
			return  $output . $content . $output;
    }
	
	return $content;
}

// Function to manually display related posts.
function kc_add_social_share()
{
 $option = twitter_facebook_share_get_options_stored();
 
 if ((is_mobile_device()) && ($option['mobdev']==true)){
	// disable for mobile device
		return;
	} 
 $output = kc_social_share('manual');
 echo $output;
}



function kc_social_share($source)
{
	global $posts;
	//GET ARRAY OF STORED VALUES
	$option = twitter_facebook_share_get_options_stored();
	if (empty($option['bkcolor_value']))
		$option['bkcolor_value'] = '#F0F4F9';
	$border ='';
 	if ($option['border'] == 'flat') 
		$border = 'border:1px solid #808080;';
	else if ($option['border'] == 'round')
	    $border = 'border:1px solid #808080; border-radius:5px 5px 5px 5px; box-shadow:2px 2px 5px rgba(0,0,0,0.3);';
		
	if ($option['bkcolor'] == true)
		$bkcolor = 'background-color:' . $option['bkcolor_value']. ';'; 
	else
		$bkcolor = '';

 	$post_link = get_permalink();
	$post_title = get_the_title();
	if ($option['position'] == 'left' && ( !is_single() && !is_page()))
		if (($source != 'manual') || ($source != 'shortcode')) 
			$option['position'] = 'above';

	if ($option['position'] == 'left'){
		$output = '<div id="leftcontainerBox" style="' .$border. $bkcolor. 'position:' .$option['float_position']. '; top:' .$option['bottom_space']. '; left:' .$option['left_space']. ';">';
		if ($option['active_buttons']['facebook_like']==true) {
		$output .= '
			<div class="buttons">
			<iframe src="http'.(is_ssl()?'s':'').'://www.facebook.com/plugins/like.php?href=' . urlencode($post_link) . '&amp;layout=box_count&amp;show_faces=false&amp;action=like&amp;font=verdana&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:60px; height:65px;"></iframe>
			</div>';
		}
		
		if ($option['active_buttons']['twitter']==true) {
		if ($option['twitter_id'] != ''){
		$output .= '
			<div class="buttons">
			<a href="http'.(is_ssl()?'s':'').'://twitter.com/share" class="twitter-share-button" data-url="'. $post_link .'"  data-text="'. $post_title . '" data-count="vertical" data-via="'. $option['twitter_id'] . '"></a>
			</div>';
		} else {
		$output .= '
			<div class="buttons">
			<a href="http'.(is_ssl()?'s':'').'://twitter.com/share" class="twitter-share-button" data-url="'. $post_link .'"  data-text="'. $post_title . '" data-count="vertical"></a>
			</div>';
		}
		}
		
		if ($option['active_buttons']['Google_plusone']==true) {
		$output .= '
			<div class="buttons">
			<g:plusone size="tall" href="'. $post_link .'"></g:plusone>
			</div>';
		}
		if ($option['active_buttons']['linkedin']==true) {
		$output .= '<div class="buttons" style="padding-left: 0px;"><script type="in/share" data-url="' . $post_link . '" data-counter="top"></script></div>';
		}
		if ($option['active_buttons']['stumbleupon']==true) {
		$output .= '
			<div class="buttons"><script src="http'.(is_ssl()?'s':'').'://www.stumbleupon.com/hostedbadge.php?s=5&amp;r='.$post_link.'"></script></div>';
		}
		if ($option['active_buttons']['pinterest']==true) {
		$post_image = tf_get_image(array('post_id' => $post->ID));
		$output .= '<div class="buttons" style="padding-top: 5px; padding-left: 5px; padding-top: 35px;">
		<a href="http'.(is_ssl()?'s':'').'://pinterest.com/pin/create/button/?url=' .  urlencode($post_link) . '&media= ' . urlencode($post_image) . '" data-pin-do="buttonPin" data-pin-config="above" data-pin-height="28"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_28.png" /></a>
<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script></div>';
		}
		$output .= '</div><div style="clear:both"></div>';
		return $output;
	}

		
	if (($option['position'] == 'below') || ($option['position'] == 'above') || ($option['position'] == 'both'))
	{
		$output = '<div class="bottomcontainerBox" style="' .$border. $bkcolor. '">';
		if ($option['active_buttons']['facebook_like']==true) {
		$output .= '
			<div style="float:left; width:' .$option['facebook_like_width']. 'px;padding-right:10px; margin:4px 4px 4px 4px;height:30px;">
			<iframe src="http'.(is_ssl()?'s':'').'://www.facebook.com/plugins/like.php?href=' . urlencode($post_link) . '&amp;layout=button_count&amp;show_faces=false&amp;width='.$option['facebook_like_width'].'&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:'.$option['facebook_like_width'].'px; height:21px;"></iframe></div>';
		}

		if ($option['active_buttons']['Google_plusone']==true) {
		$data_count = ($option['google_count']) ? '' : 'count="false"';
		$output .= '
			<div style="float:left; width:' .$option['google_width']. 'px;padding-right:10px; margin:4px 4px 4px 4px;height:30px;">
			<g:plusone size="medium" href="' . $post_link . '"'.$data_count.'></g:plusone>
			</div>';
		}
		
		if ($option['active_buttons']['twitter']==true) {
		$data_count = ($option['twitter_count']) ? 'horizontal' : 'none';
		if ($option['twitter_id'] != ''){
		$output .= '
			<div style="float:left; width:' .$option['twitter_width']. 'px;padding-right:10px; margin:4px 4px 4px 4px;height:30px;">
			<a href="http'.(is_ssl()?'s':'').'://twitter.com/share" class="twitter-share-button" data-url="'. $post_link .'"  data-text="'. $post_title . '" data-count="'.$data_count.'" data-via="'. $option['twitter_id'] . '"></a>
			</div>';
		} else {
		$output .= '
			<div style="float:left; width:' .$option['twitter_width']. 'px;padding-right:10px; margin:4px 4px 4px 4px;height:30px;">
			<a href="http'.(is_ssl()?'s':'').'://twitter.com/share" class="twitter-share-button" data-url="'. $post_link .'"  data-text="'. $post_title . '" data-count="'.$data_count.'"></a>
			</div>';
		}
		}
		if ($option['active_buttons']['linkedin']==true) {
		$counter = ($option['linkedin_count']) ? 'right' : '';
		$output .= '<div style="float:left; width:' .$option['linkedin_width']. 'px;padding-right:10px; margin:4px 4px 4px 4px;height:30px;"><script type="in/share" data-url="' . $post_link . '" data-counter="' .$counter. '"></script></div>';
		}
		if ($option['active_buttons']['pinterest']==true) {
		$post_image = tf_get_image();
		$counter = ($option['pinterest_count']) ? 'beside' : 'none';
		$output .= '<div style="float:left; width:' .$option['pinterest_width']. 'px;padding-right:10px; margin:4px 4px 4px 4px;height:30px;"><a href="http'.(is_ssl()?'s':'').'://pinterest.com/pin/create/button/?url=' . urlencode($post_link) . '&media=' . urlencode($post_image) . '" data-pin-do="buttonPin" data-pin-config="' .$counter . '"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script></div>';
		}
		if ($option['active_buttons']['stumbleupon']==true) {
		$output .= '			
			<div style="float:left; width:' .$option['stumbleupon_width']. 'px;padding-right:10px; margin:4px 4px 4px 4px;height:30px;"><script src="http'.(is_ssl()?'s':'').'://www.stumbleupon.com/hostedbadge.php?s=1&amp;r='.$post_link.'"></script></div>';
		}
		
		$output .= '			
			</div><div style="clear:both"></div><div style="padding-bottom:4px;"></div>';
			
		return $output;
	}
}

function tfg_social_share_shortcode () {
	$option = twitter_facebook_share_get_options_stored();
	
	if (is_mobile_device() && ($option['mobdev']==true)){
	// disable for mobile device
		return;
	} 
	$output = kc_social_share('shortcode');
	echo $output;
}

function kc_fb_like_thumbnails()
{
//global $posts;
/*
$default = '';
$content = $posts[0]->post_content; // $posts is an array, fetch the first element
$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
if ( $output > 0 ) {
$thumb = $matches[1][0];
echo "\n\n<!-- Thumbnail for facebook like -->\n<link rel=\"image_src\" href=\"$thumb\" />\n\n";
}
else
$thumb = $default;
*/

$thumb = tf_get_image();
if(!empty($thumb))
{
 echo "\n\n<!-- Facebook Like Thumbnail -->\n<link rel=\"image_src\" href=\"$thumb\" />\n<!-- End Facebook Like Thumbnail -->\n\n";
}

}
/*
This script will go through different possible options to retrive the display image associated with each post.  
*/
function tf_get_image($args = array() ) 
{
 global $post;
 
 $defaults = array('post_id' => $post->ID);
 $args = wp_parse_args( $args, $defaults );
 
 /* Get the first image if it exists in post content.  */
// $final_img = get_image_in_post_content($args);
 $final_img = get_image_from_post_thumbnail($args);
 
 
 if(!$final_img)
 $final_img = get_image_from_attachments($args);
 
 if(!$final_img)
 $final_img = get_image_in_post_content($args);
 
 $final_img = str_replace($url, '', $final_img);
 return $final_img;
}

/* Function to search through post contents and return the first available image in the content.*/

function get_image_in_post_content($args = array() )
{
 $display_img = '';
 $url = get_bloginfo('url');
 ob_start();
 ob_end_clean();
 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field( 'post_content', $args['post_id'] ), $matches);
 $display_img = $matches [1] [0];
 return $display_img;
}


/* 
Function to find image using WP available function get_the_post_thumbnail(). 
Note: This function will be available only if your theme supports the same.
Post Thumbnail is a theme feature introduced with Version 2.9. 

Themes have to declare their support for post images before the interface for assigning these images will appear on the Edit Post and Edit Page screens. They do this by putting the following in their functions.php file:

if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
}
 */

function get_image_from_post_thumbnail($args = array())
{
	if (function_exists('has_post_thumbnail')) {
		if (has_post_thumbnail( $args['post_id']))
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $args['post_id'] ), 'single-post-thumbnail' );
	}
 	return $image[0];

}


function get_image_from_attachments($args = array())
{
	if (function_exists('wp_get_attachment_image')) {
	$children = get_children(
	array(
	'post_parent'=> $args['post_id'],
	'post_type'=> 'attachment',
	'numberposts'=> 1,
	'post_status'=> 'inherit',
	'post_mime_type' => 'image',
	'order'=> 'ASC',
	'orderby'=> 'menu_order ASC'
	)
	);

	if ( empty( $children ))
		return false;

	$image = wp_get_attachment_image_src( $children[0], 'thumbnail');
	return $image;
	}

}

function is_mobile_device()
{
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'iPhone') ) 
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'iPad') )
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'iPod') )
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'Nokia') )
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'Opera Mini') )
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'Opera Mobi') )
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'SonyEricsson') )
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'BlackBerry') )
		return true;
if (strpos( $_SERVER['HTTP_USER_AGENT'] , 'Mobile Safari') )
		return true;
return false;
}
?>