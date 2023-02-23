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
 * @package           chatgpt_scheduler
 *
 * @wordpress-plugin
 * Plugin Name:       ChatGPT Scheduler
 * Plugin URI:        http://gigsix.com/
 * Description:       Build SEO Pages for geolocations.
 * Version:           1.6.1
 * Author:            Alisaleem252 || Gigsix
 * Author URI:        http://gigsix.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       chatgpt_scheduler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}



define( 'PLUGIN_VERSION', '1.6.1' );
define('chatgpt_scheduler_URL', plugin_dir_url(__FILE__));
define('chatgpt_scheduler_PATH', dirname(__FILE__));
define('chatgpt_scheduler_network', 'https://gigsix.com/openai/');

require_once(chatgpt_scheduler_PATH.'/inc/helper.php');
require_once(chatgpt_scheduler_PATH.'/inc/settings.php');
require_once(chatgpt_scheduler_PATH.'/inc/ajax.php');
require_once(chatgpt_scheduler_PATH.'/inc/metabox.php');
require_once(chatgpt_scheduler_PATH.'/inc/cron_schedules.php');