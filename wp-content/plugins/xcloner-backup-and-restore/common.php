<?php
/*
 *      common.php
 *
 *      Copyright 2011 Ovidiu Liuta <info@thinkovi.com>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

(@__XCLONERDIR__ == '__XCLONERDIR__') && define('__XCLONERDIR__', realpath(dirname(__FILE__)));

if ((!extension_loaded('zlib')) &&(function_exists('ob_start'))) {
                ob_end_clean();
                ob_start('ob_gzhandler');
        }

####################################

$_CONFIG['multiple_config_dir'] = __XCLONERDIR__."/configs";

$_CONFIG['backup_path'] = ($_CONFIG['backup_path']);
$_CONFIG['backups_dir'] = str_replace("//administrator","/administrator",($_CONFIG['backup_path'])."/administrator/backups");

$_CONFIG['backup_path'] = str_replace("\\","/", $_CONFIG['backup_path']);
$_CONFIG['backups_dir'] = str_replace("\\","/", $_CONFIG['backups_dir']);

$_CONFIG['exfile'] = $_CONFIG['backups_dir']."/.excl";
$_CONFIG['exfile_tar'] = $_CONFIG['backups_dir']."/.excl_tar";

$_CONFIG['logfile'] = $_CONFIG['backups_dir']."/xcloner.log";
$_CONFIG['commentsfile'] = $_CONFIG['backups_dir']."/.comments"; #$_REQUEST['backupComments']

$_CONFIG['script_path'] = str_replace("\\","/",dirname(__FILE__));

$lang_dir =  __XCLONERDIR__."/language";

$task = $_REQUEST['task'];
####################################

if(!class_exists("mysqli")){
		E_print("mysqli php module is not supported, mysql backup will be disabled. Please reinstall or upgrade php to support the mysqli extension.");
		$_CONFIG['enable_db_backup'] = 0;
	}
	
if($_CONFIG['enable_db_backup'] and !$_REQUEST['nohtml']){

	### Connecting to the mysql server
	$_CONFIG['mysqli'] = new mysqli($_CONFIG['mysql_host'], $_CONFIG['mysql_user'], $_CONFIG['mysql_pass'], $_CONFIG['mysql_database']) ;
	
	if (mysqli_connect_errno()) {
			E_print("Connect failed: %s\n", mysqli_connect_error());
			//exit();
			$_CONFIG['disable_mysql'] = 1;
			$_CONFIG['enable_db_backup'] = 0;
		}
	else{
		$_CONFIG['mysqli']->query("SET NAMES 'utf8'");
	}	

}


### loading language
if($_CONFIG['select_lang']!="")

    $mosConfig_lang = $_CONFIG['select_lang'];

if (file_exists( __XCLONERDIR__ ."/language/".$mosConfig_lang.".php" )) {

    include_once( __XCLONERDIR__ ."/language/".$mosConfig_lang.".php" );

    @include_once( __XCLONERDIR__ ."/language/english.php" );

}

else{

	include_once( __XCLONERDIR__ ."language/english.php" );

}

$version = str_replace(".", "", phpversion());
if (version_compare(PHP_VERSION, '5.2.3') < 0) {
	$_CONFIG['refresh_mode']="0";
}

if (!$_CONFIG['backup_refresh']) {
	$_CONFIG['refresh_mode']="0";
}

$_CONFIG['backup_start_path'] 	= $_CONFIG['backup_path'];
$_CONFIG['backup_store_path'] 	= $_CONFIG['clonerPath'];
$_CONFIG['temp_dir'] 		= $_CONFIG['backups_dir'];

date_default_timezone_set('America/Los_Angeles'); 

?>
