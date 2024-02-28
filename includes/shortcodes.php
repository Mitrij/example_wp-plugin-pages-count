<?php
if(!defined('WPINC'))
{
	die;
}


if(!function_exists('pagesCountEstimateFormShortcode'))
{
	function pagesCountEstimateFormShortcode($atts)
	{
		$atts = shortcode_atts([], $atts, 'pagescountestimate');
		//
		pagesCountEstimateCleanUploadedFiles();
		pagesCountEstimateClearSession();
		pagesCountEstimateEnqueueFrontend();
		$adminSettings = pagesCountEstimateAdminGetSettings();
		include( WP_PAGECOUNT_ESTIMATE_BASE . 'templates/pages-count-estimate-form.php' );
	}
}

add_shortcode('pagescountestimate', 'pagesCountEstimateFormShortcode');