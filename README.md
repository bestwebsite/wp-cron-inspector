# WP Cron Inspector

Inspect, debug, and manually run WordPress cron jobs from the admin dashboard or WP-CLI.

## Features
- View all registered WP cron events
- Manually run or delete events
- Clean admin UI under Tools → Cron Inspector
- Full WP-CLI support

## Installation
1. Upload `wp-cron-inspector` to `/wp-content/plugins/`
2. Activate via Plugins screen
3. Navigate to Tools → Cron Inspector

## WP-CLI Usage
```bash
wp cron-inspector list
wp cron-inspector run my_custom_hook
wp cron-inspector delete my_custom_hook
```

## License
GPL-2.0 or later
