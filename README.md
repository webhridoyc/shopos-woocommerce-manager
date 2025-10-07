# ShopOS - WooCommerce Manager

**All-in-One AI Manager for WooCommerce**

ShopOS is a powerful WordPress plugin that provides AI-powered insights, advanced inventory management, and comprehensive analytics for your WooCommerce store.

## Features

- 🤖 **AI-Powered Insights** - Get intelligent recommendations for your products and store performance
- 📊 **Advanced Analytics** - Detailed order analytics and sales trend reporting
- 📦 **Inventory Management** - Track stock levels, low stock alerts, and manage inventory efficiently
- 🎯 **Dashboard** - Beautiful, intuitive dashboard with key metrics and quick access
- 🔧 **Easy Configuration** - Simple settings page to enable/disable features
- 🌐 **REST API** - Built-in REST API endpoints for integration with other tools
- 🎨 **Modern UI** - Clean, responsive admin interface that matches WordPress design

## Requirements

- WordPress 5.8 or higher
- WooCommerce 5.0 or higher
- PHP 7.4 or higher

## Installation

### From GitHub

1. Download the latest release or clone this repository
2. Upload the `shopos-woocommerce-manager` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to **ShopOS** in your WordPress admin menu
5. Configure your settings and start using the plugin!

### Manual Installation

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/webhridoyc/shopos-woocommerce-manager.git
```

Then activate via WordPress admin.

## Usage

### Dashboard

Access the main dashboard at **ShopOS > Dashboard** to view:
- Total products, orders, and customers
- Recent orders
- Quick links to important sections

### Settings

Configure plugin features at **ShopOS > Settings**:
- Enable/disable AI Insights
- Enable/disable Inventory Manager
- Enable/disable Order Analytics

### AI Insights

View intelligent insights at **ShopOS > AI Insights**:
- Inventory alerts for low stock products
- Top performing products
- AI-powered recommendations

## REST API

ShopOS provides REST API endpoints for external integrations:

### Get Statistics

```
GET /wp-json/shopos/v1/stats
```

Returns:
```json
{
  "total_products": 100,
  "total_orders": 250,
  "version": "1.0.0"
}
```

Requires `manage_woocommerce` capability.

## Plugin Structure

```
shopos-woocommerce-manager/
├── admin/                  # Admin page templates
│   ├── dashboard.php      # Dashboard page
│   ├── settings.php       # Settings page
│   └── insights.php       # AI Insights page
├── assets/                 # Plugin assets
│   ├── css/               # Stylesheets
│   │   └── admin.css      # Admin styles
│   └── js/                # JavaScript files
│       └── admin.js       # Admin scripts
├── includes/              # Core plugin files
│   ├── class-shopos-admin.php  # Admin functionality
│   └── class-shopos-core.php   # Core functionality
├── languages/             # Translation files
├── shopos.php            # Main plugin file
├── uninstall.php         # Uninstall cleanup script
└── README.md             # This file
```

## Development

### File Organization

- **Main Plugin File** (`shopos.php`): Entry point with plugin headers and initialization
- **Core Classes** (`includes/`): Main functionality classes
- **Admin Templates** (`admin/`): Page templates for admin interface
- **Assets** (`assets/`): CSS and JavaScript files
- **Uninstall** (`uninstall.php`): Cleanup when plugin is deleted

### Hooks and Filters

The plugin provides several hooks for customization:

**Actions:**
- `shopos_init` - Fired after plugin initialization
- `shopos_admin_init` - Fired during admin initialization

**Filters:**
- `shopos_dashboard_stats` - Filter dashboard statistics
- `shopos_insights_data` - Filter AI insights data

## Security

- All user inputs are sanitized and validated
- Nonce verification on form submissions
- Capability checks for admin access
- Direct file access prevention
- Output escaping for all displayed data

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This plugin is licensed under the GPL v2 or later.

```
Copyright (C) 2024 webhridoyc

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```

## Support

For support, please open an issue on the [GitHub repository](https://github.com/webhridoyc/shopos-woocommerce-manager/issues).

## Changelog

### 1.0.0 (2024)
- Initial release
- Dashboard with key metrics
- Settings page
- AI Insights page
- REST API endpoints
- WooCommerce integration
- Inventory management features
- Order analytics

## Credits

Developed by [webhridoyc](https://github.com/webhridoyc)

---

Made with ❤️ for the WooCommerce community
