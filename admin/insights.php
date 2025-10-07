<?php
/**
 * AI Insights Page Template
 * 
 * @package ShopOS
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get products with low stock
$low_stock_products = wc_get_products(array(
    'stock_status' => 'onbackorder',
    'limit' => 5
));

// Get best selling products
$best_sellers = wc_get_products(array(
    'orderby' => 'popularity',
    'order' => 'DESC',
    'limit' => 5
));
?>

<div class="wrap shopos-insights">
    <h1><?php echo esc_html__('AI Insights', 'shopos'); ?></h1>
    
    <div class="shopos-insights-notice">
        <div class="notice notice-info">
            <p>
                <strong><?php echo esc_html__('AI-Powered Analytics', 'shopos'); ?></strong><br>
                <?php echo esc_html__('Get intelligent insights about your store performance and inventory.', 'shopos'); ?>
            </p>
        </div>
    </div>
    
    <div class="shopos-insights-grid">
        <div class="insight-card">
            <h2><?php echo esc_html__('Inventory Alerts', 'shopos'); ?></h2>
            
            <?php if ($low_stock_products) : ?>
            <div class="insight-content">
                <p class="insight-label">
                    <span class="dashicons dashicons-warning"></span>
                    <?php echo esc_html__('Products needing attention:', 'shopos'); ?>
                </p>
                <ul class="insight-list">
                    <?php foreach ($low_stock_products as $product) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                        <span class="badge badge-warning"><?php echo esc_html__('Low Stock', 'shopos'); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php else : ?>
            <p class="insight-content">
                <span class="dashicons dashicons-yes-alt"></span>
                <?php echo esc_html__('All products are well stocked!', 'shopos'); ?>
            </p>
            <?php endif; ?>
        </div>
        
        <div class="insight-card">
            <h2><?php echo esc_html__('Top Performers', 'shopos'); ?></h2>
            
            <?php if ($best_sellers) : ?>
            <div class="insight-content">
                <p class="insight-label">
                    <span class="dashicons dashicons-star-filled"></span>
                    <?php echo esc_html__('Best selling products:', 'shopos'); ?>
                </p>
                <ul class="insight-list">
                    <?php foreach ($best_sellers as $product) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                        <span class="badge badge-success"><?php echo esc_html($product->get_total_sales()); ?> <?php echo esc_html__('sales', 'shopos'); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php else : ?>
            <p class="insight-content"><?php echo esc_html__('No sales data available yet.', 'shopos'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="shopos-recommendations">
        <h2><?php echo esc_html__('AI Recommendations', 'shopos'); ?></h2>
        
        <div class="recommendation-card">
            <span class="dashicons dashicons-lightbulb"></span>
            <div class="recommendation-content">
                <h3><?php echo esc_html__('Optimize Your Product Descriptions', 'shopos'); ?></h3>
                <p><?php echo esc_html__('Products with detailed descriptions tend to convert 30% better. Consider adding more details to your product pages.', 'shopos'); ?></p>
            </div>
        </div>
        
        <div class="recommendation-card">
            <span class="dashicons dashicons-chart-line"></span>
            <div class="recommendation-content">
                <h3><?php echo esc_html__('Monitor Your Inventory', 'shopos'); ?></h3>
                <p><?php echo esc_html__('Set up low stock alerts to prevent stockouts and maintain optimal inventory levels.', 'shopos'); ?></p>
            </div>
        </div>
        
        <div class="recommendation-card">
            <span class="dashicons dashicons-groups"></span>
            <div class="recommendation-content">
                <h3><?php echo esc_html__('Engage Your Customers', 'shopos'); ?></h3>
                <p><?php echo esc_html__('Regular customer engagement through email campaigns can increase repeat purchases by up to 40%.', 'shopos'); ?></p>
            </div>
        </div>
    </div>
</div>
