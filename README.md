# ServiceHub MVM

![WordPress](https://img.shields.io/badge/WordPress-%E2%89%A55.0-blue) ![PHP](https://img.shields.io/badge/PHP-%E2%89%A57.4-777BB4) ![License](https://img.shields.io/badge/License-GPL--2.0-green) ![Version](https://img.shields.io/badge/Stable-1.0.0-brightgreen)

A dynamic multi-vendor service marketplace plugin for WordPress, enabling service providers to list, manage, and sell services — complete with vendor dashboards, bookings, payments, and messaging.

---

## ✨ Features

- 🏪 Multi-vendor support — multiple service providers on one platform
- 📋 Service listing and management for vendors
- 📅 Booking system for customers
- 💳 Payment integration
- 💬 Messaging between vendors and customers
- 👤 Custom vendor user role with dedicated dashboard
- ✅ Admin approval/disapproval system for new vendors
- 🔍 Advanced service filtering by type, location, and price range
- 📄 Custom Post Type (`service`) with archive template
- 🔐 Vendor login and registration pages (auto-created on activation)
- 🚫 Admin bar hidden for non-admin users on the frontend

---

## 📦 Installation

1. Upload the plugin folder to `/wp-content/plugins/servicehub-mvm`
2. Activate the plugin via **WordPress Admin → Plugins**
3. On activation, the plugin will automatically:
   - Create the custom `vendor` user role
   - Generate vendor login and registration pages
   - Register the `service` custom post type
   - Flush rewrite rules for clean permalinks

---

## 🛠️ How It Works

### For Admins
- Manage vendors from the WordPress admin dashboard
- Approve or disapprove new vendor registrations
- View dashboard widgets with platform stats

### For Vendors
- Register and log in via dedicated frontend pages
- List and manage services from their vendor dashboard
- Receive bookings and communicate with customers

### For Customers
- Browse all available services via the service archive page
- Filter services by:
  - **Service Type** (taxonomy)
  - **Service Location** (taxonomy)
  - **Price Range** (min/max)
- Book services and communicate with vendors

---

## 🗂️ Plugin Structure

```
servicehub-mvm/
├── admin/
│   ├── admin-dashboard-widgets.php
│   └── vendor-approval-handler.php
├── includes/
│   ├── class-servicehub-mvm-init.php
│   ├── class-servicehub-mvm-activator.php
│   └── class-servicehub-mvm-deactivator.php
├── public/
│   ├── class-servicehub-mvm-auth.php
│   └── templates/
│       └── vendor/
│           └── archive-service.php
├── index.php
├── uninstall.php
└── servicehub-mvm.php
```

---

## ⚙️ Compatibility

| Requirement | Version |
|---|---|
| WordPress | ≥ 5.0 |
| PHP | ≥ 7.4 |

---

## 🔧 Key Technical Details

- Custom Post Type: `service` with custom archive template
- Custom Taxonomies: `service_type`, `service_location`
- Custom User Role: `vendor`
- Uses `pre_get_posts` for frontend filtering
- Activation hooks handle role creation, page creation, CPT registration, and permalink flushing
- Clean uninstall via `uninstall.php`

---

## 🤝 Contributing

Contributions are welcome! Please open an issue first to discuss what you'd like to change, then submit a pull request.

---

## 📄 License

This plugin is licensed under the [GPL-2.0 License](LICENSE).
