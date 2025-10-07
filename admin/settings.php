<?php
/**
 * Settings Page Template
 * 
 * @package ShopOS
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Handle form submission
if (isset($_POST['shopos_settings_submit']) && check_admin_referer('shopos_settings_save', 'shopos_settings_nonce')) {
    update_option('shopos_enable_ai_insights', isset($_POST['shopos_enable_ai_insights']) ? '1' : '0');
    update_option('shopos_enable_inventory_manager', isset($_POST['shopos_enable_inventory_manager']) ? '1' : '0');
    update_option('shopos_enable_order_analytics', isset($_POST['shopos_enable_order_analytics']) ? '1' : '0');
    
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved successfully!', 'shopos') . '</p></div>';
}

// Get current settings
$enable_ai_insights = get_option('shopos_enable_ai_insights', '1');
$enable_inventory_manager = get_option('shopos_enable_inventory_manager', '1');
$enable_order_analytics = get_option('shopos_enable_order_analytics', '1');
?>

<div class="wrap shopos-settings">
    <h1><?php echo esc_html__('ShopOS Settings', 'shopos'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('shopos_settings_save', 'shopos_settings_nonce'); ?>
        
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="shopos_enable_ai_insights">
                            <?php echo esc_html__('AI Insights', 'shopos'); ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   name="shopos_enable_ai_insights" 
                                   id="shopos_enable_ai_insights" 
                                   value="1" 
                                   <?php checked($enable_ai_insights, '1'); ?>>
                            <?php echo esc_html__('Enable AI-powered insights for products and orders', 'shopos'); ?>
                        </label>
                        <p class="description">
                            <?php echo esc_html__('Get AI-generated recommendations and insights for your store.', 'shopos'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="shopos_enable_inventory_manager">
                            <?php echo esc_html__('Inventory Manager', 'shopos'); ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   name="shopos_enable_inventory_manager" 
                                   id="shopos_enable_inventory_manager" 
                                   value="1" 
                                   <?php checked($enable_inventory_manager, '1'); ?>>
                            <?php echo esc_html__('Enable advanced inventory management features', 'shopos'); ?>
                        </label>
                        <p class="description">
                            <?php echo esc_html__('Track stock levels, get low stock alerts, and manage inventory efficiently.', 'shopos'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="shopos_enable_order_analytics">
                            <?php echo esc_html__('Order Analytics', 'shopos'); ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   name="shopos_enable_order_analytics" 
                                   id="shopos_enable_order_analytics" 
                                   value="1" 
                                   <?php checked($enable_order_analytics, '1'); ?>>
                            <?php echo esc_html__('Enable detailed order analytics and reporting', 'shopos'); ?>
                        </label>
                        <p class="description">
                            <?php echo esc_html__('Get detailed insights into your order patterns, customer behavior, and sales trends.', 'shopos'); ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="submit">
            <input type="submit" 
                   name="shopos_settings_submit" 
                   id="submit" 
                   class="button button-primary" 
                   value="<?php echo esc_attr__('Save Settings', 'shopos'); ?>">
        </p>
    </form>
    
    <hr>
    
    <div class="shopos-info-section">
        <h2><?php echo esc_html__('Plugin Information', 'shopos'); ?></h2>
        <table class="widefat">
            <tbody>
                <tr>
                    <td><strong><?php echo esc_html__('Version', 'shopos'); ?>:</strong></td>
                    <td><?php echo esc_html(SHOPOS_VERSION); ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo esc_html__('Plugin Directory', 'shopos'); ?>:</strong></td>
                    <td><?php echo esc_html(SHOPOS_PLUGIN_DIR); ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo esc_html__('WooCommerce Version', 'shopos'); ?>:</strong></td>
                    <td><?php echo esc_html(defined('WC_VERSION') ? WC_VERSION : 'Not installed'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
