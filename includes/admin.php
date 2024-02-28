<?php
if(!defined('WPINC'))
{
	die;
}

add_action('admin_menu', 'pagesCountEstimateAdminAddPage');

function pagesCountEstimateAdminAddPage()
{
	add_menu_page( 
		__('Pages Count', 'wp-pages-count-estimate'), 
		__('Pages Count', 'wp-pages-count-estimate'), 
		'manage_options',
		'pages-count', 
		'pagesCountEstimateAdminPage', 
		'dashicons-editor-paste-word',
		89);

	/*
	add_submenu_page( 
		'words-count', 
		'Settings', 
		'Settings',
		'manage_options', 
		'words-count',
		array($this,'showAdminPageSetting')
	);
	add_submenu_page( 
		'words-count', 
		'Pricing', 
		'Pricing',
		'manage_options', 
		'words-count-pricing',
		array($this,'showAdminPagePricing')
	);
	*/
}

function pagesCountEstimateAdminPage()
{
	pagesCountEstimateEnqueueAdmin();
	// esc_html_e('Admin Page Test', 'wp-pages-count-estimate');
	$adminSettings = pagesCountEstimateAdminGetSettings();
	include( WP_PAGECOUNT_ESTIMATE_BASE . 'templates/admin-main-page.php' );
}

function pagesCountEstimateAdminSaveSettings($settingsArr)
{
	global $wpdb;
	$result = false;
	foreach($settingsArr as $key => $value)
	{
		if(in_array($key, ['pce_shipping_price_from_languages_list', 'pce_shipping_price_to_languages_list']))
		{
			$value = json_encode($value);
		}
		$tableName = $wpdb->prefix . "pce_data";
		$sql = $wpdb->prepare("select * from " . $tableName . " where `option_name` = %s", $key);
		if($dbRow = $wpdb->get_row($sql, ARRAY_A))
		{
			if($wpdb->update(
							$tableName,
							['value' => $value],
							['id' => $dbRow['id']],
							['%s'],
							['%d']
						)
			)
			{
				$result = true;
			}
			else if($value == $dbRow['value'])
			{
				$result = true;
			}
		}
		else
		{
			if($wpdb->insert( 
							$tableName, 
							['option_name' => $key, 'value' => $value],
							['%s', '%s']
						)
			)
			{
				$result = true;
			}
		}
	}
	wp_send_json(['result' => ($result) ? 'Saved' : 'Not saved or nothing changed'], 200);
}

function pagesCountEstimateAdminGetSettings()
{
	global $wpdb;
	$tableName = $wpdb->prefix . "pce_data";
	$adminGetSettings = [];
	$sql = $wpdb->prepare("select * from `" . $tableName . "`");
	$dbAdminGetSettings = $wpdb->get_results($sql, ARRAY_A);
	foreach($dbAdminGetSettings as $dbASetting)
	{
		if(in_array($dbASetting['option_name'], ['pce_shipping_price_from_languages_list', 'pce_shipping_price_to_languages_list']))
		{
			$dbASetting['value'] = json_decode($dbASetting['value']);
		}
		$adminGetSettings[$dbASetting['option_name']] = $dbASetting['value'];
	}
	return $adminGetSettings;
}
