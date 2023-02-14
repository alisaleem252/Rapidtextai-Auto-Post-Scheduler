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
 * Version:           1.6
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



define( 'PLUGIN_VERSION', '1.5.0' );
define('chatgpt_scheduler_URL', plugin_dir_url(__FILE__));
define('chatgpt_scheduler_PATH', dirname(__FILE__));
define('chatgpt_scheduler_network', 'https://gigsix/com/openai/');

	add_action('admin_enqueue_scripts', 'chatgpt_admin_scriptsCBF');
    function chatgpt_admin_scriptsCBF(){
        wp_enqueue_script('chatgpt_admin_JS-JS', chatgpt_scheduler_URL.'/js/admin.js',array('jquery'));
            
    } // function wpwlc_ex_enqueue_scriptsCBF() 


require_once(chatgpt_scheduler_PATH.'/inc/helper.php');
require_once(chatgpt_scheduler_PATH.'/inc/settings.php');
require_once(chatgpt_scheduler_PATH.'/inc/ajax.php');