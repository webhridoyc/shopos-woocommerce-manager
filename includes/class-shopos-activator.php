<?php
/**
 * Fired during plugin activation.
 *
 * @package ShopOS_WooCommerce_Manager
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class ShopOS_Activator {

	/**
	 * Activate the plugin.
	 *
	 * Creates necessary database tables and sets default options.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		// Set default options.
		add_option( 'shopos_version', SHOPOS_VERSION );
		add_option( 'shopos_activation_date', current_time( 'mysql' ) );

		// Create database tables.
		self::create_tables();

		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Create custom database tables.
	 *
	 * @since 1.0.0
	 */
	private static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'shopos_analytics';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			order_id bigint(20) UNSIGNED NOT NULL,
			customer_id bigint(20) UNSIGNED DEFAULT NULL,
			order_status varchar(50) NOT NULL,
			order_total decimal(10,2) NOT NULL,
			order_date datetime NOT NULL,
			processed_date datetime DEFAULT NULL,
			metadata longtext DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY order_id (order_id),
			KEY customer_id (customer_id),
			KEY order_date (order_date)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}
