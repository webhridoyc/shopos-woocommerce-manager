<?php
/**
 * ShopOS Core Class
 * 
 * Main class that initializes the plugin functionality
 * 
 * @package ShopOS
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ShopOS_Core {
    
    /**
     * Single instance of the class
     * 
     * @var ShopOS_Core
     */
    protected static $instance = null;
    
    /**
     * Get main instance
     * 
     * @return ShopOS_Core
     */
    public static function init() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Initialize admin functionality
        if (is_admin()) {
            add_action('admin_init', array($this, 'admin_init'));
            new ShopOS_Admin();
        }
        
        // Add custom WooCommerce hooks
        add_filter('woocommerce_product_tabs', array($this, 'add_custom_product_tab'));
        
        // Register REST API endpoints
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }
    
    /**
     * Admin initialization
     */
    public function admin_init() {
        // Register settings
        register_setting('shopos_settings', 'shopos_enable_ai_insights');
        register_setting('shopos_settings', 'shopos_enable_inventory_manager');
        register_setting('shopos_settings', 'shopos_enable_order_analytics');
    }
    
    /**
     * Add custom product tab
     * 
     * @param array $tabs
     * @return array
     */
    public function add_custom_product_tab($tabs) {
        $tabs['shopos_insights'] = array(
            'title'    => __('AI Insights', 'shopos'),
            'priority' => 50,
            'callback' => array($this, 'product_tab_content')
        );
        return $tabs;
    }
    
    /**
     * Product tab content
     */
    public function product_tab_content() {
        echo '<h2>' . esc_html__('AI-Powered Product Insights', 'shopos') . '</h2>';
        echo '<p>' . esc_html__('View AI-generated insights for this product.', 'shopos') . '</p>';
    }
    
    /**
     * Register REST API routes
     */
    public function register_rest_routes() {
        register_rest_route('shopos/v1', '/stats', array(
            'methods'  => 'GET',
            'callback' => array($this, 'get_stats'),
            'permission_callback' => array($this, 'check_permission')
        ));
    }
    
    /**
     * Get statistics endpoint
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get_stats($request) {
        $stats = array(
            'total_products' => wp_count_posts('product')->publish,
            'total_orders'   => wc_orders_count('completed'),
            'version'        => SHOPOS_VERSION
        );
        
        return new WP_REST_Response($stats, 200);
    }
    
    /**
     * Check permissions for REST API
     * 
     * @return bool
     */
    public function check_permission() {
        return current_user_can('manage_woocommerce');
    }
}
