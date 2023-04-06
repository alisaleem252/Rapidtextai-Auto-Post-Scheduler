<?php

/**
 * 
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://gigsix.com
 * @since             1.0.0
 * @package           Gigsix_gigsix_chatgpt_scheduler
 *
 * @wordpress-plugin
 * Plugin Name:       Gigsix ChatGPT Post Scheduler
 * Plugin URI:        https://gigsix.com/clients
 * Description:       Generate AI Content for your WordPress Posts.
 * Version:           1.2
 * Author:            Alisaleem252 || Gigsix
 * Author URI:        https://alisaleem252.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gigsix_chatgpt_scheduler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}



define( 'gigsix_PLUGIN_VERSION', '1.2' );
define('gigsix_chatgpt_scheduler_URL', plugin_dir_url(__FILE__));
define('gigsix_chatgpt_scheduler_PATH', dirname(__FILE__));
define('gigsix_chatgpt_scheduler_network', 'https://gigsix.com/openai/');

require_once(gigsix_chatgpt_scheduler_PATH.'/inc/helper.php');
require_once(gigsix_chatgpt_scheduler_PATH.'/inc/settings.php');
require_once(gigsix_chatgpt_scheduler_PATH.'/inc/ajax.php');
require_once(gigsix_chatgpt_scheduler_PATH.'/inc/metabox.php');
require_once(gigsix_chatgpt_scheduler_PATH.'/inc/cron_schedules.php');


require_once(gigsix_chatgpt_scheduler_PATH.'/wp_autoupdate.php');

add_action('init', 'chatgpt_update_plugin_version');
function chatgpt_update_plugin_version(){
	$plugin_current_version = gigsix_PLUGIN_VERSION;
	$plugin_remote_path     = 'https://gigsix.com/clients/plugin/update.php';
	$plugin_slug            = plugin_basename(__FILE__);
	$test = new WP_AutoUpdate ($plugin_current_version, $plugin_remote_path, $plugin_slug);
}
