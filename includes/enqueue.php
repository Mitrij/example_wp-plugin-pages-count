<?php
if(!defined('WPINC'))
{
	die;
}

if(!function_exists('pagesCountEstimateRegisterFrontend'))
{
	function pagesCountEstimateRegisterFrontend()
	{
		wp_register_script('pages-count-estimate-dropzone-js', WP_PAGECOUNT_ESTIMATE_URI . '/assets/js/dropzone.min.js', ['jquery'], WP_PAGECOUNT_ESTIMATE_VERSION);
		wp_register_script('pages-count-estimate-selectize-js', WP_PAGECOUNT_ESTIMATE_URI . '/assets/js/selectize.min.js', ['jquery'], WP_PAGECOUNT_ESTIMATE_VERSION);
		wp_register_script('pages-count-estimate-frontend-js', WP_PAGECOUNT_ESTIMATE_URI . '/assets/js/main-front.js', ['jquery', 'pages-count-estimate-dropzone-js'], WP_PAGECOUNT_ESTIMATE_VERSION);
		wp_register_style('pages-count-estimate-dropzone-css', WP_PAGECOUNT_ESTIMATE_URI . '/assets/css/dropzone.min.css', [], WP_PAGECOUNT_ESTIMATE_VERSION);
		wp_register_style('pages-count-estimate-selectize-css', WP_PAGECOUNT_ESTIMATE_URI . '/assets/css/selectize.css', [], WP_PAGECOUNT_ESTIMATE_VERSION);
		wp_register_style('pages-count-estimate-frontend-css', WP_PAGECOUNT_ESTIMATE_URI . '/assets/css/main-front.css', [], WP_PAGECOUNT_ESTIMATE_VERSION);
	}
}

pagesCountEstimateRegisterFrontend();

if(!function_exists('pagesCountEstimateEnqueueFrontend'))
{
	function pagesCountEstimateEnqueueFrontend()
	{
		wp_enqueue_script('pages-count-estimate-dropzone-js');
		wp_enqueue_script('pages-count-estimate-selectize-js');
		wp_enqueue_script('pages-count-estimate-frontend-js');
		$pceParams = array(
				'ajax' => admin_url('admin-ajax.php'),
				'currency_unit' => 'CHF',
				'translate' => 
				[
					'Name' => __('Name', 'wp-pages-count-estimate'),
					'Email' => __('Email', 'wp-pages-count-estimate'),
					'Comments' => __('Comments', 'wp-pages-count-estimate'),
					'Send' => __('Comments', 'wp-pages-count-estimate'),
					'Please_contact_us' => __('Please contact us', 'wp-pages-count-estimate'),
				],
			);
		wp_localize_script('pages-count-estimate-frontend-js','pceParams', $pceParams);
		$dropZoneParams = array(
				'upload' => admin_url('admin-ajax.php?action=pce_upload_file'),
				'delete' => admin_url('admin-ajax.php?action=pce_delete_file'),
			);
		wp_localize_script('pages-count-estimate-frontend-js','dropZoneParams', $dropZoneParams);
		wp_enqueue_style('pages-count-estimate-dropzone-css');
		wp_enqueue_style('pages-count-estimate-selectize-css');
		wp_enqueue_style('pages-count-estimate-frontend-css');
	}
}


if(!function_exists('pagesCountEstimateRegisterAdmin'))
{
	function pagesCountEstimateRegisterAdmin()
	{
		wp_register_script('pages-count-estimate-admin-js', WP_PAGECOUNT_ESTIMATE_URI . '/assets/js/main-admin.js', ['jquery'], WP_PAGECOUNT_ESTIMATE_VERSION);
		wp_register_style('pages-count-estimate-admin-css', WP_PAGECOUNT_ESTIMATE_URI . '/assets/css/main-admin.css', [], WP_PAGECOUNT_ESTIMATE_VERSION);
	}
}

pagesCountEstimateRegisterAdmin();

if(!function_exists('pagesCountEstimateEnqueueAdmin'))
{
	function pagesCountEstimateEnqueueAdmin()
	{
		wp_enqueue_script('pages-count-estimate-admin-js');
		wp_enqueue_style('pages-count-estimate-admin-css');
	}
}