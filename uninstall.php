<?php
/**
 * ShopOS Uninstall Script
 * 
 * Fired when the plugin is uninstalled.
 * 
 * @package ShopOS
 * @since 1.0.0
 */

// Exit if accessed directly or not from WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Delete plugin options
 */
function shopos_delete_plugin_options() {
    delete_option('shopos_version');
    delete_option('shopos_activation_time');
    delete_option('shopos_enable_ai_insights');
    delete_option('shopos_enable_inventory_manager');
    delete_option('shopos_enable_order_analytics');
}

/**
 * Clean up plugin data
 */
function shopos_cleanup_plugin_data() {
    global $wpdb;
    
    // Delete any custom tables if they exist
    // Example: $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}shopos_data");
    
    // Delete transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_shopos_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_shopos_%'");
}

// Execute cleanup
shopos_delete_plugin_options();
shopos_cleanup_plugin_data();
