# ShopOS - WooCommerce Manager

[![WordPress Plugin Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/webhridoyc/shopos-woocommerce-manager)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![WordPress](https://img.shields.io/badge/wordpress-5.8%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/woocommerce-5.0%2B-purple.svg)](https://woocommerce.com/)

All-in-One AI Manager for WooCommerce - A comprehensive solution for managing orders, inventory, and analytics with AI-powered insights.

## Description

ShopOS WooCommerce Manager is a powerful WordPress plugin designed to enhance your WooCommerce store management experience. It provides an intuitive dashboard for tracking orders, analyzing sales data, and managing your store operations efficiently.

### Key Features

- **📊 Advanced Dashboard**: Comprehensive overview of your store's performance with real-time statistics
- **📦 Order Management**: Streamlined order processing and status tracking
- **📈 Analytics Engine**: Deep insights into sales trends and customer behavior
- **🔒 Security First**: Built with WordPress security best practices and data sanitization
- **⚡ Performance Optimized**: Efficient database queries and caching mechanisms
- **🎨 Modern UI**: Clean and responsive admin interface
- **🔔 Email Notifications**: Stay informed about important store events
- **🗄️ Custom Database Tables**: Optimized data storage for analytics

## Requirements

- WordPress 5.8 or higher
- WooCommerce 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## Installation

### Automatic Installation

1. Log in to your WordPress admin panel
2. Navigate to **Plugins > Add New**
3. Search for "ShopOS WooCommerce Manager"
4. Click **Install Now** and then **Activate**

### Manual Installation

1. Download the plugin ZIP file
2. Log in to your WordPress admin panel
3. Navigate to **Plugins > Add New > Upload Plugin**
4. Choose the downloaded ZIP file and click **Install Now**
5. After installation, click **Activate Plugin**

### Installation from GitHub

```bash
cd wp-content/plugins/
git clone https://github.com/webhridoyc/shopos-woocommerce-manager.git
```

Then activate the plugin from the WordPress admin panel.

## Configuration

### Initial Setup

1. After activation, navigate to **ShopOS > Dashboard** in your WordPress admin
2. Configure the plugin settings under **ShopOS > Settings**
3. Enable analytics tracking to start collecting data
4. Set your data retention period (default: 90 days)
5. Configure email notifications as needed

### Settings Overview

#### Analytics Settings
- **Enable Analytics**: Toggle analytics tracking for orders and sales
- **Data Retention**: Set how long to keep historical data (30-365 days)

#### Notification Settings
- **Email Notifications**: Enable/disable email alerts for order status changes

## Usage

### Dashboard

The ShopOS Dashboard provides an at-a-glance view of your store's performance:

- **Total Orders**: View order count for the last 30 days
- **Total Sales**: See your sales revenue
- **Average Order Value**: Track your AOV metrics
- **Unique Customers**: Monitor customer engagement

Access the dashboard at **ShopOS > Dashboard**.

### Order Management

Manage your WooCommerce orders efficiently:

1. Navigate to **ShopOS > Order Manager**
2. View all orders with detailed information
3. Use bulk actions to update multiple orders at once
4. Track order status changes in real-time

### Analytics

Gain insights into your store's performance:

1. Go to **ShopOS > Analytics**
2. View sales trends and customer behavior
3. Export data for further analysis
4. Generate custom reports

## Database Structure

The plugin creates the following custom table:

### `wp_shopos_analytics`

```sql
CREATE TABLE wp_shopos_analytics (
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
);
```

## Security

ShopOS follows WordPress coding standards and security best practices:

- ✅ Data sanitization and validation
- ✅ Prepared SQL statements (SQL injection prevention)
- ✅ Nonce verification for AJAX requests
- ✅ Capability checks for admin actions
- ✅ Secure data escaping for output
- ✅ WPCS (WordPress Coding Standards) compliant

## Hooks and Filters

### Actions

```php
// Triggered when an order is processed
do_action( 'shopos_order_processed', $order_id, $order );

// Triggered before analytics insert
do_action( 'shopos_before_analytics_insert', $data );

// Triggered after analytics insert
do_action( 'shopos_after_analytics_insert', $result, $data );
```

### Filters

```php
// Filter analytics data before insertion
apply_filters( 'shopos_analytics_data', $data, $order );

// Filter dashboard stats
apply_filters( 'shopos_dashboard_stats', $stats );

// Filter data retention period
apply_filters( 'shopos_data_retention_days', $days );
```

## Developer Documentation

### Class Structure

```
includes/
├── class-shopos-activator.php      # Plugin activation logic
├── class-shopos-deactivator.php    # Plugin deactivation logic
├── class-shopos-database.php       # Database operations
├── class-shopos-admin.php          # Admin interface
└── class-shopos-order-manager.php  # Order management
```

### Using the Database Class

```php
// Get database instance
$database = new ShopOS_Database();

// Insert analytics data
$data = array(
    'order_id'     => 123,
    'customer_id'  => 456,
    'order_status' => 'completed',
    'order_total'  => 99.99,
    'order_date'   => current_time('mysql'),
);
$database->insert_analytics( $data );

// Get analytics by order ID
$analytics = $database->get_analytics_by_order( 123 );

// Get sales summary
$summary = $database->get_summary( '2024-01-01 00:00:00', '2024-12-31 23:59:59' );
```

### Using the Order Manager

```php
// Get order manager instance
$order_manager = new ShopOS_Order_Manager();

// Get order summary
$summary = $order_manager->get_order_summary( array(
    'start_date' => '2024-01-01 00:00:00',
    'end_date'   => '2024-12-31 23:59:59',
) );

// Get orders by status
$pending_orders = $order_manager->get_orders_by_status( 'pending', 10 );

// Bulk update order status
$result = $order_manager->bulk_update_status( array( 1, 2, 3 ), 'processing' );
```

## Changelog

### Version 1.0.0 (Phase 1)
- Initial release
- Dashboard with key metrics
- Order tracking integration
- Analytics database structure
- Admin interface with modern UI
- Settings page for configuration
- Email notification system
- Security best practices implementation
- WordPress coding standards compliance

## Roadmap

### Phase 2 (Coming Soon)
- AI-powered insights and recommendations
- Advanced reporting with charts
- Inventory management features
- Automated order processing
- Customer segmentation
- Export functionality

### Phase 3 (Future)
- Multi-store support
- REST API endpoints
- Mobile app integration
- Advanced AI features
- Custom workflows
- Integration with popular services

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Support

For support, feature requests, or bug reports:

- **GitHub Issues**: [Create an issue](https://github.com/webhridoyc/shopos-woocommerce-manager/issues)
- **Documentation**: [View docs](https://github.com/webhridoyc/shopos-woocommerce-manager/wiki)

## License

This plugin is licensed under the GPL v2 or later.

```
ShopOS WooCommerce Manager
Copyright (C) 2024 WebHridoy

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Credits

Developed by [WebHridoy](https://github.com/webhridoyc)

## Acknowledgments

- WordPress.org for the amazing platform
- WooCommerce team for the e-commerce framework
- All contributors and supporters

---

**Made with ❤️ for the WordPress and WooCommerce community**
