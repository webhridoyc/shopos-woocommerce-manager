<?php
/**
 * Dashboard Page Template
 * 
 * @package ShopOS
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get WooCommerce stats
$total_products = wp_count_posts('product')->publish;
$total_orders = wc_orders_count('completed');
$total_customers = count(get_users(array('role' => 'customer')));
$currency_symbol = get_woocommerce_currency_symbol();

// Get recent orders
$recent_orders = wc_get_orders(array(
    'limit' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
));
?>

<div class="wrap shopos-dashboard">
    <h1><?php echo esc_html__('ShopOS Dashboard', 'shopos'); ?></h1>
    
    <div class="shopos-welcome-panel">
        <div class="welcome-panel-content">
            <h2><?php echo esc_html__('Welcome to ShopOS', 'shopos'); ?></h2>
            <p class="about-description">
                <?php echo esc_html__('Your All-in-One AI Manager for WooCommerce. Get powerful insights and manage your store efficiently.', 'shopos'); ?>
            </p>
        </div>
    </div>
    
    <div class="shopos-stats-grid">
        <div class="shopos-stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <h3><?php echo esc_html($total_products); ?></h3>
                <p><?php echo esc_html__('Total Products', 'shopos'); ?></p>
            </div>
        </div>
        
        <div class="shopos-stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-content">
                <h3><?php echo esc_html($total_orders); ?></h3>
                <p><?php echo esc_html__('Completed Orders', 'shopos'); ?></p>
            </div>
        </div>
        
        <div class="shopos-stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3><?php echo esc_html($total_customers); ?></h3>
                <p><?php echo esc_html__('Total Customers', 'shopos'); ?></p>
            </div>
        </div>
        
        <div class="shopos-stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3><?php echo esc_html__('Active', 'shopos'); ?></h3>
                <p><?php echo esc_html__('Plugin Status', 'shopos'); ?></p>
            </div>
        </div>
    </div>
    
    <div class="shopos-recent-section">
        <h2><?php echo esc_html__('Recent Orders', 'shopos'); ?></h2>
        
        <?php if ($recent_orders) : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php echo esc_html__('Order', 'shopos'); ?></th>
                    <th><?php echo esc_html__('Date', 'shopos'); ?></th>
                    <th><?php echo esc_html__('Status', 'shopos'); ?></th>
                    <th><?php echo esc_html__('Total', 'shopos'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_orders as $order) : ?>
                <tr>
                    <td>
                        <a href="<?php echo esc_url(admin_url('post.php?post=' . $order->get_id() . '&action=edit')); ?>">
                            #<?php echo esc_html($order->get_order_number()); ?>
                        </a>
                    </td>
                    <td><?php echo esc_html($order->get_date_created()->date_i18n(get_option('date_format'))); ?></td>
                    <td><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></td>
                    <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else : ?>
        <p><?php echo esc_html__('No orders found.', 'shopos'); ?></p>
        <?php endif; ?>
    </div>
    
    <div class="shopos-quick-links">
        <h2><?php echo esc_html__('Quick Links', 'shopos'); ?></h2>
        <div class="quick-links-grid">
            <a href="<?php echo esc_url(admin_url('admin.php?page=shopos-settings')); ?>" class="quick-link-card">
                <span class="dashicons dashicons-admin-generic"></span>
                <?php echo esc_html__('Settings', 'shopos'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=shopos-insights')); ?>" class="quick-link-card">
                <span class="dashicons dashicons-chart-bar"></span>
                <?php echo esc_html__('AI Insights', 'shopos'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>" class="quick-link-card">
                <span class="dashicons dashicons-products"></span>
                <?php echo esc_html__('Products', 'shopos'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=shop_order')); ?>" class="quick-link-card">
                <span class="dashicons dashicons-cart"></span>
                <?php echo esc_html__('Orders', 'shopos'); ?>
            </a>
        </div>
    </div>
</div>
