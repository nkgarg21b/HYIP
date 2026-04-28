<?php
/**
 * Plugin Name: HYIP Investment Plugin
 * Description: Investment & ROI management system.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) exit;

define('HYIP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('HYIP_PLUGIN_URL', plugin_dir_url(__FILE__));

// ✅ Autoloader (removes manual includes)
require_once HYIP_PLUGIN_PATH . 'includes/class-autoloader.php';
HYIP_Autoloader::init();
HYIP_Error_Logger::init();

// ✅ Upgrader (auto DB + version migrations)
require_once HYIP_PLUGIN_PATH . 'includes/class-upgrader.php';
HYIP_Upgrader::init();

// Core system loader
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

register_activation_hook(__FILE__, function() {
    add_option('hyip_setup_required', true);
});

add_action('admin_init', function() {

    // ✅ Only allow admins
    if (!current_user_can('manage_options')) {
        return;
    }

    // ✅ Only run after activation (not during sandbox)
    if (get_option('hyip_setup_required')) {

        delete_option('hyip_setup_required');

        // Prevent redirect during bulk activation
        if (!isset($_GET['activate-multi'])) {

            wp_safe_redirect(admin_url('admin.php?page=hyip-setup'));
            exit;
        }
    }
});
