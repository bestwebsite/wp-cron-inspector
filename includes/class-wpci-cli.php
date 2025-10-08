<?php
if (defined('WP_CLI') && WP_CLI) {
    class WPCI_CLI extends WP_CLI_Command {
        public function list($args, $assoc_args) {
            $events = WPCI_Core::get_cron_events();
            if (empty($events)) {
                WP_CLI::log('No cron events found.');
                return;
            }
            WP_CLI\Utils\format_items('table', $events, ['hook', 'next_run', 'schedule', 'args']);
        }
        public function run($args) {
            $hook = $args[0] ?? null;
            if (!$hook) WP_CLI::error('Please specify a hook name.');
            if (WPCI_Core::run_event($hook)) {
                WP_CLI::success("Event '{$hook}' executed.");
            } else {
                WP_CLI::error("Event '{$hook}' not found or has no action.");
            }
        }
        public function delete($args) {
            $hook = $args[0] ?? null;
            if (!$hook) WP_CLI::error('Please specify a hook name.');
            if (WPCI_Core::delete_event($hook)) {
                WP_CLI::success("Deleted all scheduled events for '{$hook}'.");
            } else {
                WP_CLI::error("No events found for '{$hook}'.");
            }
        }
    }
    WP_CLI::add_command('cron-inspector', 'WPCI_CLI');
}
