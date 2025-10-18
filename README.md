# WP Cron Inspector

Inspect, debug, and manually run WordPress cron jobs from the admin dashboard or WP-CLI.

## Features
- View all registered WP cron events
- **NEW:** Search/filter by hook name in admin
- **NEW:** Secure action endpoints for Run/Delete (POST + nonce)
- Manually run or delete events
- Full WP-CLI support (`list`, `run`, `delete`)

## Installation
1. Upload `wp-cron-inspector` to `/wp-content/plugins/`
2. Activate via Plugins
3. Navigate to **Tools → Cron Inspector**

## Admin
- Use the search box to filter by hook name.
- Click **Run** to execute a hook immediately or **Delete** to clear its scheduled events.

## WP-CLI
```bash
wp cron-inspector list --match=woocommerce
wp cron-inspector run my_custom_hook
wp cron-inspector delete my_custom_hook
```

## Changelog
See `CHANGELOG.md` for details.

## Author
Built and maintained by **Best Website** — https://bestwebsite.com  
Support: support@bestwebsite.com

## License
GPL-2.0 or later
