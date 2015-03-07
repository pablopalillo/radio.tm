<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

$option_name = 'xcloner_';

// For regular options.
global $wpdb;
$options = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name like '".$option_name."%'" );

foreach ( $options as $option ) 
{
	delete_option( $option );  
}

