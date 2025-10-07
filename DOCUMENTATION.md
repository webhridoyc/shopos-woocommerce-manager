# ShopOS Plugin Documentation

## Installation Guide

### Step 1: Download & Install
1. Download or clone the plugin from GitHub
2. Upload to `/wp-content/plugins/shopos-woocommerce-manager/`
3. Go to WordPress Admin > Plugins
4. Find "ShopOS - WooCommerce Manager"
5. Click "Activate"

### Step 2: Prerequisites
- Make sure WooCommerce is installed and activated
- If WooCommerce is not active, the plugin will show an error message

### Step 3: Access the Plugin
After activation, you'll see a new "ShopOS" menu item in your WordPress admin sidebar with a store icon.

## Features Overview

### 1. Dashboard (`/wp-admin/admin.php?page=shopos`)

The dashboard provides:
- **Statistics Cards**: Quick view of total products, orders, customers, and plugin status
- **Recent Orders Table**: Last 5 orders with order number, date, status, and total
- **Quick Links**: Fast access to Settings, AI Insights, Products, and Orders

### 2. Settings Page (`/wp-admin/admin.php?page=shopos-settings`)

Configure plugin features:
- **AI Insights**: Toggle AI-powered insights and recommendations
- **Inventory Manager**: Enable/disable advanced inventory management
- **Order Analytics**: Turn on/off detailed order analytics and reporting
- **Plugin Information**: View version, directory path, and WooCommerce version

### 3. AI Insights Page (`/wp-admin/admin.php?page=shopos-insights`)

View intelligent insights:
- **Inventory Alerts**: Products that need attention (low stock, back-ordered)
- **Top Performers**: Best selling products with sales count
- **AI Recommendations**: Smart suggestions to improve store performance

### 4. REST API Endpoints

#### Get Statistics
```
GET /wp-json/shopos/v1/stats
```

**Authentication**: Requires `manage_woocommerce` capability

**Response**:
```json
{
  "total_products": 100,
  "total_orders": 250,
  "version": "1.0.0"
}
```

## Plugin Architecture

### File Structure

```
shopos-woocommerce-manager/
├── shopos.php                          # Main plugin file - Entry point
├── uninstall.php                       # Cleanup script when plugin is deleted
├── includes/
│   ├── class-shopos-core.php          # Core functionality class
│   └── class-shopos-admin.php         # Admin interface handler
├── admin/
│   ├── dashboard.php                  # Dashboard page template
│   ├── settings.php                   # Settings page template
│   └── insights.php                   # AI Insights page template
├── assets/
│   ├── css/
│   │   └── admin.css                  # Admin styles
│   └── js/
│       └── admin.js                   # Admin JavaScript
└── languages/                          # Translation files directory
```

### Class Overview

**ShopOS_Core** (`includes/class-shopos-core.php`)
- Singleton pattern for single instance
- Handles WooCommerce integration
- Registers REST API endpoints
- Manages product tabs and custom functionality

**ShopOS_Admin** (`includes/class-shopos-admin.php`)
- Creates admin menu structure
- Enqueues CSS and JavaScript assets
- Handles admin page routing
- Adds plugin action links

## Security Features

1. **Direct Access Prevention**: All files check for `ABSPATH` constant
2. **Nonce Verification**: Forms use WordPress nonces for CSRF protection
3. **Capability Checks**: Admin pages require `manage_woocommerce` capability
4. **Data Sanitization**: All user inputs are sanitized using WordPress functions
5. **Output Escaping**: All output uses `esc_html()`, `esc_url()`, etc.

## Hooks & Filters

### Actions
- `plugins_loaded`: Initialize the plugin
- `admin_menu`: Add admin menu items
- `admin_init`: Register settings
- `admin_enqueue_scripts`: Load admin assets
- `rest_api_init`: Register REST API routes

### Filters
- `plugin_action_links_`: Add Settings link to plugins page
- `woocommerce_product_tabs`: Add custom product tabs

## Customization

### Adding Custom Tabs
```php
add_filter('woocommerce_product_tabs', function($tabs) {
    $tabs['my_custom_tab'] = array(
        'title'    => 'My Tab',
        'priority' => 50,
        'callback' => 'my_tab_content'
    );
    return $tabs;
});
```

### Extending Statistics
```php
add_filter('shopos_dashboard_stats', function($stats) {
    $stats['custom_metric'] = get_custom_value();
    return $stats;
});
```

## Troubleshooting

### Plugin Won't Activate
- **Cause**: WooCommerce is not installed or not active
- **Solution**: Install and activate WooCommerce first

### Admin Pages Not Showing
- **Cause**: Insufficient permissions
- **Solution**: Make sure your user has `manage_woocommerce` capability

### Styles Not Loading
- **Cause**: File permissions or path issues
- **Solution**: Check that `assets/` folder is readable

### REST API Not Working
- **Cause**: Permalink structure not set up
- **Solution**: Go to Settings > Permalinks and save settings

## Performance Optimization

The plugin is designed to be lightweight:
- CSS/JS only loaded on ShopOS admin pages
- Minimal database queries
- Uses WordPress caching mechanisms
- No external dependencies

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## WordPress Compatibility

- Tested with WordPress 5.8+
- Compatible with WordPress 6.x
- Follows WordPress Coding Standards

## WooCommerce Compatibility

- Tested with WooCommerce 5.0+
- Compatible with WooCommerce 8.x
- Uses official WooCommerce functions and hooks

## Support

For issues or questions:
1. Check this documentation first
2. Review the [README.md](README.md)
3. Open an issue on [GitHub](https://github.com/webhridoyc/shopos-woocommerce-manager/issues)

## Development

### Local Development Setup
1. Set up a local WordPress installation
2. Install WooCommerce
3. Clone this repository to `wp-content/plugins/`
4. Activate the plugin
5. Make changes and test

### Code Standards
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Use proper escaping and sanitization
- Comment complex logic
- Keep functions focused and small

## Credits

Developed with ❤️ for the WordPress and WooCommerce community.
