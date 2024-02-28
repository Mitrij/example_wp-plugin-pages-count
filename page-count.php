<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Pages Count & Estimate
 * Plugin URI:        
 * Description:       
 * Version:           1.0.0
 * Author:            
 * Author URI:        
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-pages-count-estimate
 * Domain Path:       /languages
 */
 
if(!defined('WPINC'))
{
	die;
}

define('WP_PAGECOUNT_ESTIMATE_VERSION', '1.0.0');
define('WP_PAGECOUNT_ESTIMATE_BASE', plugin_dir_path( __FILE__ ));
define('WP_PAGECOUNT_ESTIMATE_URI', trailingslashit(plugins_url('', __FILE__)));
define('WP_PAGECOUNT_ESTIMATE_FILES_STORAGE', WP_PAGECOUNT_ESTIMATE_BASE . 'files_storage');

require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/activate_deactivate.php';
require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/enqueue.php';
require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/shortcodes.php';
require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/ajax.php';
require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/functions.php';
require_once WP_PAGECOUNT_ESTIMATE_BASE . 'includes/admin.php';

register_activation_hook(__FILE__, 'pagesCountEstimateActivatePlugin');
register_deactivation_hook(__FILE__, 'pagesCountEstimateDeactivatePlugin');
register_uninstall_hook(__FILE__, 'pagesCountEstimateUninstallPlugin');


if(defined( 'DOING_AJAX' ) && DOING_AJAX)
{
	load_plugin_textdomain('wp-pages-count-estimate', false,  dirname(plugin_basename(__FILE__)) . '/languages/');
}
else
{
	function pagesCountEstimateSetLocale()
	{
		load_plugin_textdomain('wp-pages-count-estimate', false,  dirname(plugin_basename(__FILE__)) . '/languages/');
	}
	add_action( 'init', 'pagesCountEstimateSetLocale' );
}
