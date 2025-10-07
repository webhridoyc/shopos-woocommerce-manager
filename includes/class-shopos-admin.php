<?php
/**
 * ShopOS Admin Class
 * 
 * Handles all admin-related functionality
 * 
 * @package ShopOS
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ShopOS_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_filter('plugin_action_links_' . SHOPOS_PLUGIN_BASENAME, array($this, 'add_action_links'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('ShopOS', 'shopos'),
            __('ShopOS', 'shopos'),
            'manage_woocommerce',
            'shopos',
            array($this, 'render_dashboard_page'),
            'dashicons-store',
            56
        );
        
        add_submenu_page(
            'shopos',
            __('Dashboard', 'shopos'),
            __('Dashboard', 'shopos'),
            'manage_woocommerce',
            'shopos',
            array($this, 'render_dashboard_page')
        );
        
        add_submenu_page(
            'shopos',
            __('Settings', 'shopos'),
            __('Settings', 'shopos'),
            'manage_woocommerce',
            'shopos-settings',
            array($this, 'render_settings_page')
        );
        
        add_submenu_page(
            'shopos',
            __('AI Insights', 'shopos'),
            __('AI Insights', 'shopos'),
            'manage_woocommerce',
            'shopos-insights',
            array($this, 'render_insights_page')
        );
    }
    
    /**
     * Enqueue admin assets
     * 
     * @param string $hook
     */
    public function enqueue_admin_assets($hook) {
        // Only load on ShopOS admin pages
        if (strpos($hook, 'shopos') === false) {
            return;
        }
        
        wp_enqueue_style(
            'shopos-admin',
            SHOPOS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            SHOPOS_VERSION
        );
        
        wp_enqueue_script(
            'shopos-admin',
            SHOPOS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            SHOPOS_VERSION,
            true
        );
        
        wp_localize_script('shopos-admin', 'shoposData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('shopos_nonce'),
            'strings' => array(
                'saving' => __('Saving...', 'shopos'),
                'saved'  => __('Saved!', 'shopos'),
                'error'  => __('Error saving settings', 'shopos')
            )
        ));
    }
    
    /**
     * Add plugin action links
     * 
     * @param array $links
     * @return array
     */
    public function add_action_links($links) {
        $plugin_links = array(
            '<a href="' . admin_url('admin.php?page=shopos-settings') . '">' . __('Settings', 'shopos') . '</a>',
        );
        return array_merge($plugin_links, $links);
    }
    
    /**
     * Render dashboard page
     */
    public function render_dashboard_page() {
        require_once SHOPOS_PLUGIN_DIR . 'admin/dashboard.php';
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        require_once SHOPOS_PLUGIN_DIR . 'admin/settings.php';
    }
    
    /**
     * Render insights page
     */
    public function render_insights_page() {
        require_once SHOPOS_PLUGIN_DIR . 'admin/insights.php';
    }
}
