<?php
/**
 * Database Handler Class
 *
 * Handles all database operations for the plugin.
 *
 * @package ShopOS_WooCommerce_Manager
 */

/**
 * Class ShopOS_Database
 *
 * Handles database operations with proper sanitization and security measures.
 */
class ShopOS_Database {

	/**
	 * The table name for analytics.
	 *
	 * @var string
	 */
	private $analytics_table;

	/**
	 * WordPress database object.
	 *
	 * @var wpdb
	 */
	private $wpdb;

	/**
	 * Initialize the database handler.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->analytics_table = $wpdb->prefix . 'shopos_analytics';
	}

	/**
	 * Insert analytics data.
	 *
	 * @param array $data Analytics data to insert.
	 * @return int|false The number of rows inserted, or false on error.
	 * @since 1.0.0
	 */
	public function insert_analytics( $data ) {
		// Validate required fields.
		if ( empty( $data['order_id'] ) || empty( $data['order_status'] ) ) {
			return false;
		}

		// Sanitize data.
		$sanitized_data = array(
			'order_id'       => absint( $data['order_id'] ),
			'customer_id'    => ! empty( $data['customer_id'] ) ? absint( $data['customer_id'] ) : null,
			'order_status'   => sanitize_text_field( $data['order_status'] ),
			'order_total'    => floatval( $data['order_total'] ),
			'order_date'     => sanitize_text_field( $data['order_date'] ),
			'processed_date' => ! empty( $data['processed_date'] ) ? sanitize_text_field( $data['processed_date'] ) : null,
			'metadata'       => ! empty( $data['metadata'] ) ? wp_json_encode( $data['metadata'] ) : null,
		);

		// Insert data with prepared statement.
		$result = $this->wpdb->insert(
			$this->analytics_table,
			$sanitized_data,
			array( '%d', '%d', '%s', '%f', '%s', '%s', '%s' )
		);

		return $result;
	}

	/**
	 * Update analytics data.
	 *
	 * @param int   $id   The analytics record ID.
	 * @param array $data Data to update.
	 * @return int|false The number of rows updated, or false on error.
	 * @since 1.0.0
	 */
	public function update_analytics( $id, $data ) {
		if ( empty( $id ) || empty( $data ) ) {
			return false;
		}

		$sanitized_data = array();
		$format = array();

		// Sanitize only provided fields.
		if ( isset( $data['order_status'] ) ) {
			$sanitized_data['order_status'] = sanitize_text_field( $data['order_status'] );
			$format[] = '%s';
		}

		if ( isset( $data['order_total'] ) ) {
			$sanitized_data['order_total'] = floatval( $data['order_total'] );
			$format[] = '%f';
		}

		if ( isset( $data['processed_date'] ) ) {
			$sanitized_data['processed_date'] = sanitize_text_field( $data['processed_date'] );
			$format[] = '%s';
		}

		if ( isset( $data['metadata'] ) ) {
			$sanitized_data['metadata'] = wp_json_encode( $data['metadata'] );
			$format[] = '%s';
		}

		if ( empty( $sanitized_data ) ) {
			return false;
		}

		// Update data with prepared statement.
		$result = $this->wpdb->update(
			$this->analytics_table,
			$sanitized_data,
			array( 'id' => absint( $id ) ),
			$format,
			array( '%d' )
		);

		return $result;
	}

	/**
	 * Get analytics data by order ID.
	 *
	 * @param int $order_id The WooCommerce order ID.
	 * @return object|null The analytics record or null if not found.
	 * @since 1.0.0
	 */
	public function get_analytics_by_order( $order_id ) {
		$order_id = absint( $order_id );

		// Use prepared statement for security.
		$query = $this->wpdb->prepare(
			"SELECT * FROM {$this->analytics_table} WHERE order_id = %d ORDER BY created_at DESC LIMIT 1",
			$order_id
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $this->wpdb->get_row( $query );
	}

	/**
	 * Get analytics data within a date range.
	 *
	 * @param string $start_date Start date (Y-m-d H:i:s format).
	 * @param string $end_date   End date (Y-m-d H:i:s format).
	 * @return array Array of analytics records.
	 * @since 1.0.0
	 */
	public function get_analytics_by_date_range( $start_date, $end_date ) {
		// Use prepared statement for security.
		$query = $this->wpdb->prepare(
			"SELECT * FROM {$this->analytics_table} WHERE order_date BETWEEN %s AND %s ORDER BY order_date DESC",
			sanitize_text_field( $start_date ),
			sanitize_text_field( $end_date )
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $this->wpdb->get_results( $query );
	}

	/**
	 * Get total sales within a date range.
	 *
	 * @param string $start_date Start date (Y-m-d H:i:s format).
	 * @param string $end_date   End date (Y-m-d H:i:s format).
	 * @return float Total sales amount.
	 * @since 1.0.0
	 */
	public function get_total_sales( $start_date, $end_date ) {
		// Use prepared statement for security.
		$query = $this->wpdb->prepare(
			"SELECT SUM(order_total) as total FROM {$this->analytics_table} 
			WHERE order_date BETWEEN %s AND %s 
			AND order_status NOT IN ('cancelled', 'refunded', 'failed')",
			sanitize_text_field( $start_date ),
			sanitize_text_field( $end_date )
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$result = $this->wpdb->get_var( $query );

		return $result ? floatval( $result ) : 0.0;
	}

	/**
	 * Delete analytics records older than specified days.
	 *
	 * @param int $days Number of days to keep.
	 * @return int|false Number of rows deleted, or false on error.
	 * @since 1.0.0
	 */
	public function cleanup_old_records( $days = 90 ) {
		$days = absint( $days );
		$cutoff_date = gmdate( 'Y-m-d H:i:s', strtotime( "-{$days} days" ) );

		// Use prepared statement for security.
		$query = $this->wpdb->prepare(
			"DELETE FROM {$this->analytics_table} WHERE created_at < %s",
			$cutoff_date
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $this->wpdb->query( $query );
	}

	/**
	 * Get analytics summary.
	 *
	 * @param string $start_date Start date (Y-m-d H:i:s format).
	 * @param string $end_date   End date (Y-m-d H:i:s format).
	 * @return object Summary data.
	 * @since 1.0.0
	 */
	public function get_summary( $start_date, $end_date ) {
		// Use prepared statement for security.
		$query = $this->wpdb->prepare(
			"SELECT 
				COUNT(*) as total_orders,
				SUM(order_total) as total_sales,
				AVG(order_total) as average_order_value,
				COUNT(DISTINCT customer_id) as unique_customers
			FROM {$this->analytics_table} 
			WHERE order_date BETWEEN %s AND %s 
			AND order_status NOT IN ('cancelled', 'refunded', 'failed')",
			sanitize_text_field( $start_date ),
			sanitize_text_field( $end_date )
		);

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $this->wpdb->get_row( $query );
	}
}
