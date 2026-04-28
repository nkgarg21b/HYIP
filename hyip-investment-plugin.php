<?php
/**
 * Plugin Name: HYIP Investment Plugin
 * Description: Investment & ROI management system.
 * Version: 0.1.0
 */

if (!defined('ABSPATH')) exit;

define('HYIP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('HYIP_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once HYIP_PLUGIN_PATH . 'core/class-loader.php';
require_once HYIP_PLUGIN_PATH . 'core/class-activator.php';
require_once HYIP_PLUGIN_PATH . 'core/class-deactivator.php';

register_activation_hook(__FILE__, ['HYIP_Activator', 'activate']);
register_deactivation_hook(__FILE__, ['HYIP_Deactivator', 'deactivate']);

function run_hyip_plugin() {
    $loader = new HYIP_Loader();
    $loader->run();
}
run_hyip_plugin();