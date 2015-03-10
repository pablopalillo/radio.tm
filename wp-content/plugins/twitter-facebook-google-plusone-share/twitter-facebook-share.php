<?php
/*
Plugin Name: Twitter Facebook Social Share
Description: WordPress plugin for twitter, facebook, Google +1 (plus one) and other social share. Can add the share box before post contents, after and also floating on left hand side of the post.
Author: Kunal Chichkar
Author URI: http://www.searchtechword.com
Plugin URI: http://www.searchtechword.com/2011/06/wordpress-plugin-add-twitter-facebook-google-plus-one-share
Version: 2.4.2
License: GPL
*/
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

require_once('tf_admin_page.php');
require_once('tf_display.php');


if (!function_exists('is_admin')) 
{
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'kc_twitter_facebook_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'kc_twitter_facebook_remove' );

function kc_twitter_facebook_install() 
{
/* Do Nothing */
}

function kc_twitter_facebook_remove() {
/* Deletes the database field */
delete_option('twitter_facebook_share');
}
if(is_admin())
{
add_action('admin_menu', 'kc_twitter_facebook_admin_menu');
}
else
{
 add_action('init', 'twitter_facebook_share_init');
 add_shortcode('tfg_social_share', 'tfg_social_share_shortcode' );
 add_action('wp_head', 'kc_fb_like_thumbnails');
 $option = twitter_facebook_share_get_options_stored();
 if($option['auto'] == true)
 {
  add_filter('the_content', 'kc_twitter_facebook_contents');
  add_filter('the_excerpt', 'kc_twitter_facebook_excerpt');
 } 
}
?>