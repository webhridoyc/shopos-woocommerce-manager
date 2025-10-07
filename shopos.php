<?php
/**
 * Plugin Name: ShopOS - WooCommerce Manager
 * Plugin URI: https://github.com/webhridoyc/shopos-woocommerce-manager
 * Description: All-in-One AI Manager for WooCommerce - Manage products, orders, inventory, and get AI-powered insights for your WooCommerce store.
 * Version: 1.0.0
 * Author: webhridoyc
 * Author URI: https://github.com/webhridoyc
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: shopos
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SHOPOS_VERSION', '1.0.0');
define('SHOPOS_PLUGIN_FILE', __FILE__);
define('SHOPOS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SHOPOS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SHOPOS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Check if WooCommerce is active
 */
function shopos_is_woocommerce_active() {
    return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
}

/**
 * Initialize the plugin
 */
function shopos_init() {
    // Load plugin text domain for translations
    load_plugin_textdomain('shopos', false, dirname(SHOPOS_PLUGIN_BASENAME) . '/languages');
    
    // Check if WooCommerce is active
    if (!shopos_is_woocommerce_active()) {
        add_action('admin_notices', 'shopos_woocommerce_missing_notice');
        return;
    }
    
    // Include required files
    require_once SHOPOS_PLUGIN_DIR . 'includes/class-shopos-admin.php';
    require_once SHOPOS_PLUGIN_DIR . 'includes/class-shopos-core.php';
    
    // Initialize core functionality
    ShopOS_Core::init();
}
add_action('plugins_loaded', 'shopos_init');

/**
 * Display admin notice if WooCommerce is not active
 */
function shopos_woocommerce_missing_notice() {
    ?>
    <div class="error">
        <p><?php echo esc_html__('ShopOS requires WooCommerce to be installed and active. Please install and activate WooCommerce.', 'shopos'); ?></p>
    </div>
    <?php
}

/**
 * Plugin activation hook
 */
function shopos_activate() {
    // Check if WooCommerce is active
    if (!shopos_is_woocommerce_active()) {
        deactivate_plugins(SHOPOS_PLUGIN_BASENAME);
        wp_die(
            esc_html__('ShopOS requires WooCommerce to be installed and active.', 'shopos'),
            esc_html__('Plugin Activation Error', 'shopos'),
            array('back_link' => true)
        );
    }
    
    // Set default options
    add_option('shopos_version', SHOPOS_VERSION);
    add_option('shopos_activation_time', current_time('timestamp'));
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'shopos_activate');

/**
 * Plugin deactivation hook
 */
function shopos_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'shopos_deactivate');
