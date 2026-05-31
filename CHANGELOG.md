# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2025-02-15

### Added
- Initial release of ServiceHub MVM
- Multi-vendor support with custom `vendor` user role
- Vendor registration and login pages (auto-created on activation)
- Admin approval/disapproval system for new vendor registrations
- Service listing via custom post type (`service`)
- Custom taxonomies: `service_type` and `service_location`
- Frontend service archive page with custom plugin template
- Advanced service filtering by type, location, and price range (min/max)
- Vendor dashboard for managing services and bookings
- Booking system for customers
- Messaging between vendors and customers
- Admin dashboard widgets for platform stats
- Admin bar hidden for non-admin frontend users
- Clean uninstall via `uninstall.php`
- Activation hooks for role creation, page setup, CPT registration, and permalink flushing

---

## [Unreleased]

### Planned
- Payment gateway integrations
- Email notifications for bookings and messages
- Vendor ratings and reviews
- Service search with keyword support
- Multi-language / WPML compatibility
