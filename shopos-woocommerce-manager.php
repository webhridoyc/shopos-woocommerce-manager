<?php
/**
 * Plugin Name: ShopOS WooCommerce Manager
 * Plugin URI: https://github.com/webhridoyc/shopos-woocommerce-manager
 * Description: All-in-One AI Manager for WooCommerce - Comprehensive solution for managing orders, inventory, and analytics.
 * Version: 1.0.0
 * Author: WebHridoy
 * Author URI: https://github.com/webhridoyc
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: shopos-woocommerce-manager
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 *
 * @package ShopOS_WooCommerce_Manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'SHOPOS_VERSION', '1.0.0' );

/**
 * Plugin directory path.
 */
define( 'SHOPOS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'SHOPOS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'SHOPOS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_shopos_woocommerce_manager() {
	require_once SHOPOS_PLUGIN_DIR . 'includes/class-shopos-activator.php';
	ShopOS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_shopos_woocommerce_manager() {
	require_once SHOPOS_PLUGIN_DIR . 'includes/class-shopos-deactivator.php';
	ShopOS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_shopos_woocommerce_manager' );
register_deactivation_hook( __FILE__, 'deactivate_shopos_woocommerce_manager' );

/**
 * Check if WooCommerce is active.
 */
function shopos_check_woocommerce() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'shopos_woocommerce_missing_notice' );
		return false;
	}
	return true;
}

/**
 * Display admin notice if WooCommerce is not active.
 */
function shopos_woocommerce_missing_notice() {
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'ShopOS WooCommerce Manager requires WooCommerce to be installed and activated.', 'shopos-woocommerce-manager' ); ?></p>
	</div>
	<?php
}

/**
 * Begin execution of the plugin.
 */
function run_shopos_woocommerce_manager() {
	// Check if WooCommerce is active.
	if ( ! shopos_check_woocommerce() ) {
		return;
	}

	// Load core classes.
	require_once SHOPOS_PLUGIN_DIR . 'includes/class-shopos-database.php';
	require_once SHOPOS_PLUGIN_DIR . 'includes/class-shopos-admin.php';
	require_once SHOPOS_PLUGIN_DIR . 'includes/class-shopos-order-manager.php';

	// Initialize the plugin.
	$database = new ShopOS_Database();
	$admin = new ShopOS_Admin();
	$order_manager = new ShopOS_Order_Manager();
}

add_action( 'plugins_loaded', 'run_shopos_woocommerce_manager' );
