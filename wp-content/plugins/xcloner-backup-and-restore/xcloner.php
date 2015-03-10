<?php
/*
Plugin Name:  XCloner
Plugin URI: http://www.xcloner.com
Description: XCloner is a tool that will help you manage your website backups, generate/restore/move so your website will be always secured! With XCloner you will be able to clone your site to any other location with just a few clicks. Don't forget to create the 'administrator/backups' directory in your Wordpress root and make it fully writeable. <a href="plugins.php?page=xcloner_show">Open XCloner</a> | <a href="http://www.xcloner.com/support/premium-support/">Get Premium Support</a> | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=info%40xcloner%2ecom&lc=US&item_name=XCloner%20Support&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest">Donate</a>
Version: 3.1.1
Author: Liuta Ovidiu
Author URI: http://www.xcloner.com
Plugin URI: http://www.xcloner.com
*/

define("_VALID_MOS", 1);

(@__XCLONERDIR__ == '__XCLONERDIR__') && define('__XCLONERDIR__', realpath(dirname(__FILE__)));

global $xcloner_db_version;
$xcloner_db_version = "1.0";

if (version_compare(PHP_VERSION, '5.2.3') < 0) 
{
	add_action('admin_init', 'xclonerphpError');
}

/**
 * Show PHP Error message if PHP is lower the required
 */
function xclonerphpError() {
    add_action('admin_notices', 'xclonerShowError');
}

/**
 * Called in Notice Hook
 */
function xclonerShowError() {
    echo '<div class="update-nag"><span style="color:red; font-weight:bold;">' . __('For XCloner to work properly, the PHP version has to be equal or greater than 5.2.3', _PLUGIN_NAME_) . '</span></div>';
}


function xcloner_show()
{

	include "admin.cloner.php";

}

function xcloner_install()
{
	
}

function xcloner_page()
{

	if ( function_exists('add_submenu_page') )
		add_submenu_page('plugins.php', 'XCloner', 'XCloner', 'manage_options', 'xcloner_show', 'xcloner_show');

}

#add_action('admin_head', 'xcloner');
add_action('admin_menu', 'xcloner_page');

add_action( 'wp_ajax_add_foobar', 'prefix_ajax_add_foobar' );

function prefix_ajax_add_foobar() 
{
    // Handle request then generate response using WP_Ajax_Response
}


if (isset($_GET['activate']) && $_GET['activate'] == 'true')
{
	add_action('init', 'xcloner_install');
}
 
 
 
add_action( 'wp_ajax_json_return', 'json_return' );

function json_return(){

	$_REQUEST['nohtml'] = 1;
	
	include "admin.cloner.php";

	die();

} 

add_action( 'wp_ajax_files_xml', 'files_xml' );

function files_xml(){

	$_REQUEST['nohtml'] = 1;
	
	set_include_path(__XCLONERDIR__."/browser/");
	include __XCLONERDIR__."/browser/files_xml.php";

	die();

} 

// now load the scripts we need
function starter_plugin_admin_scripts ($hook) {
	
	wp_enqueue_script ('jquery-ui');
	wp_enqueue_script ('jquery-ui-dialog');
	wp_enqueue_script ('jquery-ui-button');
	wp_enqueue_script ('jquery-ui-tabs');
	wp_enqueue_script ('jquery-ui-sortable');
	wp_enqueue_script ('jquery-ui-progressbar');
	wp_enqueue_script ('jquery-ui-slider');
	
	wp_enqueue_script ('dtree.js', plugins_url()."/xcloner-backup-and-restore/javascript/dtree.js", "", "3.1.0");
	wp_enqueue_script ('main.js', plugins_url()."/xcloner-backup-and-restore/javascript/main.js", "", "3.1.0");
	
	wp_enqueue_style('dtree.css', plugins_url()."/xcloner-backup-and-restore/css/dtree.css", "", "3.1.0");
	wp_enqueue_style ('main.css', plugins_url()."/xcloner-backup-and-restore/css/main.css", "", "3.1.0");
	
	wp_enqueue_style ('jquery-start-ui-1.8.9.custom.css', plugins_url()."/xcloner-backup-and-restore/css/start/jquery-ui-1.8.9.custom.css", "", "3.1.0");
	
}
if($_REQUEST["page"] == "xcloner_show")
	add_action('admin_enqueue_scripts', 'starter_plugin_admin_scripts');

