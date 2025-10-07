<?php
/**
 * Fired during plugin deactivation.
 *
 * @package ShopOS_WooCommerce_Manager
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 */
class ShopOS_Deactivator {

	/**
	 * Deactivate the plugin.
	 *
	 * Cleans up temporary data and flushes rewrite rules.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {
		// Flush rewrite rules.
		flush_rewrite_rules();

		// Clear any scheduled cron jobs.
		wp_clear_scheduled_hook( 'shopos_daily_cleanup' );
		wp_clear_scheduled_hook( 'shopos_analytics_sync' );

		// Note: We do not delete database tables or options on deactivation
		// to preserve data. This should only be done on uninstall.
	}
}
