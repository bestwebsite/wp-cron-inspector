<?php
class WPCI_Core {

    public static function get_cron_events() {
        $crons = _get_cron_array();
        $events = [];
        if (empty($crons)) return [];

        foreach ($crons as $timestamp => $hooks) {
            foreach ($hooks as $hook => $details) {
                foreach ($details as $sig => $event) {
                    $events[] = [
                        'hook' => $hook,
                        'next_run' => wp_date('Y-m-d H:i:s', $timestamp),
                        'schedule' => $event['schedule'] ?? 'Non-repeating',
                        'args' => isset($event['args']) ? json_encode($event['args']) : '—'
                    ];
                }
            }
        }
        return $events;
    }

    public static function run_event($hook) {
        if (!has_action($hook)) return false;
        do_action($hook);
        return true;
    }

    public static function delete_event($hook) {
        return wp_clear_scheduled_hook($hook);
    }
}
