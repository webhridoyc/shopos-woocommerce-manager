<?php
/**
 * Order Manager Class
 *
 * Handles WooCommerce order operations and management.
 *
 * @package ShopOS_WooCommerce_Manager
 */

/**
 * Class ShopOS_Order_Manager
 *
 * Manages order processing, status updates, and analytics tracking.
 */
class ShopOS_Order_Manager {

	/**
	 * Database handler instance.
	 *
	 * @var ShopOS_Database
	 */
	private $database;

	/**
	 * Initialize the order manager.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->database = new ShopOS_Database();

		// Hook into WooCommerce order events.
		add_action( 'woocommerce_new_order', array( $this, 'track_new_order' ), 10, 1 );
		add_action( 'woocommerce_order_status_changed', array( $this, 'track_order_status_change' ), 10, 4 );
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'process_order_analytics' ), 10, 3 );
	}

	/**
	 * Track new order creation.
	 *
	 * @param int $order_id The order ID.
	 * @since 1.0.0
	 */
	public function track_new_order( $order_id ) {
		if ( ! $order_id ) {
			return;
		}

		// Check if analytics is enabled.
		if ( ! get_option( 'shopos_enable_analytics', true ) ) {
			return;
		}

		// Get the order object.
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		// Prepare analytics data.
		$data = array(
			'order_id'     => $order_id,
			'customer_id'  => $order->get_customer_id(),
			'order_status' => $order->get_status(),
			'order_total'  => $order->get_total(),
			'order_date'   => $order->get_date_created()->date( 'Y-m-d H:i:s' ),
			'metadata'     => array(
				'payment_method' => $order->get_payment_method(),
				'items_count'    => $order->get_item_count(),
				'currency'       => $order->get_currency(),
			),
		);

		// Insert analytics record.
		$this->database->insert_analytics( $data );
	}

	/**
	 * Track order status changes.
	 *
	 * @param int    $order_id   The order ID.
	 * @param string $old_status Old order status.
	 * @param string $new_status New order status.
	 * @param object $order      The order object.
	 * @since 1.0.0
	 */
	public function track_order_status_change( $order_id, $old_status, $new_status, $order ) {
		if ( ! $order_id || ! $order ) {
			return;
		}

		// Check if analytics is enabled.
		if ( ! get_option( 'shopos_enable_analytics', true ) ) {
			return;
		}

		// Get existing analytics record.
		$existing = $this->database->get_analytics_by_order( $order_id );

		if ( $existing ) {
			// Update existing record.
			$update_data = array(
				'order_status'   => $new_status,
				'processed_date' => current_time( 'mysql' ),
			);

			$this->database->update_analytics( $existing->id, $update_data );
		} else {
			// Create new record if it doesn't exist.
			$this->track_new_order( $order_id );
		}

		// Send email notification if enabled.
		if ( get_option( 'shopos_email_notifications', false ) ) {
			$this->send_status_change_notification( $order, $old_status, $new_status );
		}
	}

	/**
	 * Process order analytics after checkout.
	 *
	 * @param int    $order_id The order ID.
	 * @param array  $posted_data Posted checkout data.
	 * @param object $order The order object.
	 * @since 1.0.0
	 */
	public function process_order_analytics( $order_id, $posted_data, $order ) {
		if ( ! $order_id ) {
			return;
		}

		// Additional analytics processing can be added here.
		// For Phase 1, this is a placeholder for future enhancements.
		do_action( 'shopos_order_processed', $order_id, $order );
	}

	/**
	 * Get order summary data.
	 *
	 * @param array $args Query arguments.
	 * @return array Order summary data.
	 * @since 1.0.0
	 */
	public function get_order_summary( $args = array() ) {
		$defaults = array(
			'start_date' => gmdate( 'Y-m-d 00:00:00', strtotime( '-30 days' ) ),
			'end_date'   => gmdate( 'Y-m-d 23:59:59' ),
		);

		$args = wp_parse_args( $args, $defaults );

		return $this->database->get_summary(
			$args['start_date'],
			$args['end_date']
		);
	}

	/**
	 * Get orders by status.
	 *
	 * @param string $status Order status.
	 * @param int    $limit  Number of orders to retrieve.
	 * @return array Array of WC_Order objects.
	 * @since 1.0.0
	 */
	public function get_orders_by_status( $status, $limit = 10 ) {
		$args = array(
			'limit'  => absint( $limit ),
			'status' => sanitize_text_field( $status ),
			'return' => 'objects',
		);

		return wc_get_orders( $args );
	}

	/**
	 * Bulk update order status.
	 *
	 * @param array  $order_ids   Array of order IDs.
	 * @param string $new_status  New status to set.
	 * @return array Result with success and failed orders.
	 * @since 1.0.0
	 */
	public function bulk_update_status( $order_ids, $new_status ) {
		$result = array(
			'success' => array(),
			'failed'  => array(),
		);

		if ( empty( $order_ids ) || empty( $new_status ) ) {
			return $result;
		}

		// Sanitize new status.
		$new_status = sanitize_text_field( $new_status );

		foreach ( $order_ids as $order_id ) {
			$order_id = absint( $order_id );
			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				$result['failed'][] = $order_id;
				continue;
			}

			// Update order status.
			$updated = $order->update_status( $new_status );

			if ( $updated ) {
				$result['success'][] = $order_id;
			} else {
				$result['failed'][] = $order_id;
			}
		}

		return $result;
	}

	/**
	 * Send email notification for status change.
	 *
	 * @param object $order      The order object.
	 * @param string $old_status Old order status.
	 * @param string $new_status New order status.
	 * @since 1.0.0
	 */
	private function send_status_change_notification( $order, $old_status, $new_status ) {
		// Get admin email.
		$admin_email = get_option( 'admin_email' );

		// Prepare email content.
		$subject = sprintf(
			/* translators: %d: order ID */
			__( 'Order #%d Status Changed', 'shopos-woocommerce-manager' ),
			$order->get_id()
		);

		$message = sprintf(
			/* translators: 1: order ID, 2: old status, 3: new status */
			__( 'Order #%1$d status has been changed from %2$s to %3$s.', 'shopos-woocommerce-manager' ),
			$order->get_id(),
			$old_status,
			$new_status
		);

		// Send email.
		wp_mail( $admin_email, $subject, $message );
	}

	/**
	 * Get pending orders count.
	 *
	 * @return int Number of pending orders.
	 * @since 1.0.0
	 */
	public function get_pending_orders_count() {
		$args = array(
			'status' => 'pending',
			'return' => 'ids',
		);

		$orders = wc_get_orders( $args );
		return count( $orders );
	}

	/**
	 * Get processing orders count.
	 *
	 * @return int Number of processing orders.
	 * @since 1.0.0
	 */
	public function get_processing_orders_count() {
		$args = array(
			'status' => 'processing',
			'return' => 'ids',
		);

		$orders = wc_get_orders( $args );
		return count( $orders );
	}
}
