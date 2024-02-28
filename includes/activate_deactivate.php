<?php
if(!defined('WPINC'))
{
	die;
}

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function pagesCountEstimateActivatePlugin()
{
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$tableName = $wpdb->prefix . "pce_data"; 
	$sql = "CREATE TABLE $tableName (
		id mediumint(11) NOT NULL AUTO_INCREMENT,
		option_name varchar(150) NOT NULL,
		value text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	maybe_create_table($tableName, $sql);
}

function pagesCountEstimateDeactivatePlugin()
{
	pagesCountEstimateCleanUploadedFiles();
}

function pagesCountEstimateUninstallPlugin()
{
	pagesCountEstimateCleanUploadedFiles();
	global $wpdb;
	$tableName = $wpdb->prefix . "pce_data";
	$sql = "DROP TABLE IF EXISTS $tableName;";
	$wpdb->query($sql); 
}