<?php
/**
 * Admin Dashboard Class
 *
 * Handles all admin interface functionality.
 *
 * @package ShopOS_WooCommerce_Manager
 */

/**
 * Class ShopOS_Admin
 *
 * Manages the admin dashboard, menus, and settings.
 */
class ShopOS_Admin {

	/**
	 * Initialize the admin functionality.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add admin menu items.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		// Add main menu page.
		add_menu_page(
			__( 'ShopOS Manager', 'shopos-woocommerce-manager' ),
			__( 'ShopOS', 'shopos-woocommerce-manager' ),
			'manage_woocommerce',
			'shopos-dashboard',
			array( $this, 'render_dashboard_page' ),
			'dashicons-store',
			56
		);

		// Add dashboard submenu.
		add_submenu_page(
			'shopos-dashboard',
			__( 'Dashboard', 'shopos-woocommerce-manager' ),
			__( 'Dashboard', 'shopos-woocommerce-manager' ),
			'manage_woocommerce',
			'shopos-dashboard',
			array( $this, 'render_dashboard_page' )
		);

		// Add orders submenu.
		add_submenu_page(
			'shopos-dashboard',
			__( 'Order Manager', 'shopos-woocommerce-manager' ),
			__( 'Order Manager', 'shopos-woocommerce-manager' ),
			'manage_woocommerce',
			'shopos-orders',
			array( $this, 'render_orders_page' )
		);

		// Add analytics submenu.
		add_submenu_page(
			'shopos-dashboard',
			__( 'Analytics', 'shopos-woocommerce-manager' ),
			__( 'Analytics', 'shopos-woocommerce-manager' ),
			'manage_woocommerce',
			'shopos-analytics',
			array( $this, 'render_analytics_page' )
		);

		// Add settings submenu.
		add_submenu_page(
			'shopos-dashboard',
			__( 'Settings', 'shopos-woocommerce-manager' ),
			__( 'Settings', 'shopos-woocommerce-manager' ),
			'manage_options',
			'shopos-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Enqueue admin CSS and JavaScript.
	 *
	 * @param string $hook The current admin page hook.
	 * @since 1.0.0
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on ShopOS admin pages.
		if ( strpos( $hook, 'shopos' ) === false ) {
			return;
		}

		// Enqueue CSS.
		wp_enqueue_style(
			'shopos-admin-css',
			SHOPOS_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			SHOPOS_VERSION,
			'all'
		);

		// Enqueue JavaScript.
		wp_enqueue_script(
			'shopos-admin-js',
			SHOPOS_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			SHOPOS_VERSION,
			true
		);

		// Localize script with AJAX URL and nonce.
		wp_localize_script(
			'shopos-admin-js',
			'shoposAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'shopos-admin-nonce' ),
				'strings' => array(
					'error'   => __( 'An error occurred. Please try again.', 'shopos-woocommerce-manager' ),
					'success' => __( 'Operation completed successfully.', 'shopos-woocommerce-manager' ),
				),
			)
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		// Register settings.
		register_setting(
			'shopos_settings',
			'shopos_enable_analytics',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'rest_sanitize_boolean',
				'default'           => true,
			)
		);

		register_setting(
			'shopos_settings',
			'shopos_data_retention_days',
			array(
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'default'           => 90,
			)
		);

		register_setting(
			'shopos_settings',
			'shopos_email_notifications',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'rest_sanitize_boolean',
				'default'           => false,
			)
		);
	}

	/**
	 * Render dashboard page.
	 *
	 * @since 1.0.0
	 */
	public function render_dashboard_page() {
		// Verify user capabilities.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopos-woocommerce-manager' ) );
		}

		// Get summary data.
		$database = new ShopOS_Database();
		$start_date = gmdate( 'Y-m-d 00:00:00', strtotime( '-30 days' ) );
		$end_date = gmdate( 'Y-m-d 23:59:59' );
		$summary = $database->get_summary( $start_date, $end_date );

		?>
		<div class="wrap shopos-dashboard">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="shopos-welcome-panel">
				<h2><?php esc_html_e( 'Welcome to ShopOS WooCommerce Manager', 'shopos-woocommerce-manager' ); ?></h2>
				<p><?php esc_html_e( 'Your all-in-one solution for managing WooCommerce orders, inventory, and analytics.', 'shopos-woocommerce-manager' ); ?></p>
			</div>

			<div class="shopos-stats-grid">
				<div class="shopos-stat-card">
					<h3><?php esc_html_e( 'Total Orders (Last 30 Days)', 'shopos-woocommerce-manager' ); ?></h3>
					<p class="shopos-stat-value"><?php echo esc_html( $summary->total_orders ?? 0 ); ?></p>
				</div>

				<div class="shopos-stat-card">
					<h3><?php esc_html_e( 'Total Sales', 'shopos-woocommerce-manager' ); ?></h3>
					<p class="shopos-stat-value">
						<?php echo esc_html( wc_price( $summary->total_sales ?? 0 ) ); ?>
					</p>
				</div>

				<div class="shopos-stat-card">
					<h3><?php esc_html_e( 'Average Order Value', 'shopos-woocommerce-manager' ); ?></h3>
					<p class="shopos-stat-value">
						<?php echo esc_html( wc_price( $summary->average_order_value ?? 0 ) ); ?>
					</p>
				</div>

				<div class="shopos-stat-card">
					<h3><?php esc_html_e( 'Unique Customers', 'shopos-woocommerce-manager' ); ?></h3>
					<p class="shopos-stat-value"><?php echo esc_html( $summary->unique_customers ?? 0 ); ?></p>
				</div>
			</div>

			<div class="shopos-quick-links">
				<h2><?php esc_html_e( 'Quick Links', 'shopos-woocommerce-manager' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopos-orders' ) ); ?>">
							<?php esc_html_e( 'Manage Orders', 'shopos-woocommerce-manager' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopos-analytics' ) ); ?>">
							<?php esc_html_e( 'View Analytics', 'shopos-woocommerce-manager' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=shopos-settings' ) ); ?>">
							<?php esc_html_e( 'Configure Settings', 'shopos-woocommerce-manager' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Render orders page.
	 *
	 * @since 1.0.0
	 */
	public function render_orders_page() {
		// Verify user capabilities.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopos-woocommerce-manager' ) );
		}

		?>
		<div class="wrap shopos-orders">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="shopos-orders-container">
				<p><?php esc_html_e( 'Order management interface will be displayed here.', 'shopos-woocommerce-manager' ); ?></p>
				
				<div class="shopos-orders-table">
					<?php
					// This will be enhanced in future phases with actual order management functionality.
					esc_html_e( 'Advanced order management features coming soon!', 'shopos-woocommerce-manager' );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render analytics page.
	 *
	 * @since 1.0.0
	 */
	public function render_analytics_page() {
		// Verify user capabilities.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopos-woocommerce-manager' ) );
		}

		?>
		<div class="wrap shopos-analytics">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="shopos-analytics-container">
				<p><?php esc_html_e( 'Analytics and reporting interface will be displayed here.', 'shopos-woocommerce-manager' ); ?></p>
				
				<div class="shopos-charts">
					<?php
					// This will be enhanced in future phases with charts and graphs.
					esc_html_e( 'Advanced analytics and AI-powered insights coming soon!', 'shopos-woocommerce-manager' );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render settings page.
	 *
	 * @since 1.0.0
	 */
	public function render_settings_page() {
		// Verify user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'shopos-woocommerce-manager' ) );
		}

		?>
		<div class="wrap shopos-settings">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<form method="post" action="options.php">
				<?php
				settings_fields( 'shopos_settings' );
				do_settings_sections( 'shopos_settings' );
				?>
				
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="shopos_enable_analytics">
								<?php esc_html_e( 'Enable Analytics', 'shopos-woocommerce-manager' ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="shopos_enable_analytics" 
								name="shopos_enable_analytics" 
								value="1" 
								<?php checked( get_option( 'shopos_enable_analytics', true ) ); ?>
							/>
							<p class="description">
								<?php esc_html_e( 'Enable analytics tracking for orders and sales.', 'shopos-woocommerce-manager' ); ?>
							</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label for="shopos_data_retention_days">
								<?php esc_html_e( 'Data Retention (Days)', 'shopos-woocommerce-manager' ); ?>
							</label>
						</th>
						<td>
							<input 
								type="number" 
								id="shopos_data_retention_days" 
								name="shopos_data_retention_days" 
								value="<?php echo esc_attr( get_option( 'shopos_data_retention_days', 90 ) ); ?>" 
								min="30" 
								max="365" 
								step="1"
							/>
							<p class="description">
								<?php esc_html_e( 'Number of days to retain analytics data (30-365 days).', 'shopos-woocommerce-manager' ); ?>
							</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">
							<label for="shopos_email_notifications">
								<?php esc_html_e( 'Email Notifications', 'shopos-woocommerce-manager' ); ?>
							</label>
						</th>
						<td>
							<input 
								type="checkbox" 
								id="shopos_email_notifications" 
								name="shopos_email_notifications" 
								value="1" 
								<?php checked( get_option( 'shopos_email_notifications', false ) ); ?>
							/>
							<p class="description">
								<?php esc_html_e( 'Send email notifications for important events.', 'shopos-woocommerce-manager' ); ?>
							</p>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
