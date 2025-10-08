<?php
/**
 * Plugin Name: WP Cron Inspector
 * Description: Inspect, debug, and manually run WordPress cron jobs from the admin dashboard or WP-CLI.
 * Version: 1.0.0
 * Author: Best Website
 * Author URI: https://bestwebsite.com/
 * License: GPL-2.0+
 * Text Domain: wp-cron-inspector
 */

if (!defined('ABSPATH')) exit;

define('WPCI_VERSION', '1.0.0');
define('WPCI_PATH', plugin_dir_path(__FILE__));

require_once WPCI_PATH . 'includes/class-wpci-core.php';
require_once WPCI_PATH . 'includes/class-wpci-admin.php';

if (defined('WP_CLI') && WP_CLI) {
    require_once WPCI_PATH . 'includes/class-wpci-cli.php';
}
