<p align="center">
  <img src="https://raw.githubusercontent.com/bestwebsite/wp-cron-inspector/master/assets/social/wp-cron-inspector-banner.png"
       alt="WP Cron Inspector — inspect, search, and run WordPress cron events" />
</p>

# WP Cron Inspector

[![Latest release](https://img.shields.io/github/v/release/bestwebsite/wp-cron-inspector)](../../releases)
[![License: GPL-2.0+](https://img.shields.io/badge/license-GPL--2.0%2B-blue.svg)](LICENSE)
[![WP-CLI](https://img.shields.io/badge/WP--CLI-supported-2ea44f.svg)](https://wp-cli.org/)
[![Maintained by Best Website](https://img.shields.io/badge/maintainer-Best%20Website-3AA0FF)](https://bestwebsite.com)

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
