<?php
if (defined('WP_CLI') && WP_CLI) {
    class WPCI_CLI extends WP_CLI_Command {

        public function list($args, $assoc_args) {
            $match = isset($assoc_args['match']) ? (string) $assoc_args['match'] : null;
            $events = WPCI_Core::get_cron_events($match);
            if (empty($events)) { WP_CLI::log('No cron events found.'); return; }
            $rows = array_map(function($e){
                return ['hook'=>$e['hook'],'next_run'=>$e['next_run'],'schedule'=>$e['schedule'],'args'=>empty($e['args'])?'':json_encode($e['args'])];
            }, $events);
            WP_CLI\Utils\format_items('table', $rows, ['hook','next_run','schedule','args']);
        }

        public function run($args) {
            $hook = isset($args[0]) ? sanitize_key($args[0]) : '';
            if (!$hook) WP_CLI::error('Please specify a hook name.');
            if (WPCI_Core::run_event($hook)) WP_CLI::success("Event '{$hook}' executed.");
            else WP_CLI::error("Event '{$hook}' failed or not found.");
        }

        public function delete($args) {
            $hook = isset($args[0]) ? sanitize_key($args[0]) : '';
            if (!$hook) WP_CLI::error('Please specify a hook name.');
            $count = WPCI_Core::delete_event($hook);
            if ($count) WP_CLI::success("Deleted {$count} scheduled events for '{$hook}'.");
            else WP_CLI::warning("No events found for '{$hook}'.");
        }
    }
    WP_CLI::add_command('cron-inspector', 'WPCI_CLI');
}
