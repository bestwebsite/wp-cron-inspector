<?php
class WPCI_Core {

    public static function get_cron_events($match = null) {
        $crons = _get_cron_array();
        $events = [];
        if (empty($crons) || !is_array($crons)) return [];

        $match = is_string($match) && $match !== '' ? strtolower($match) : null;

        foreach ($crons as $timestamp => $hooks) {
            foreach ($hooks as $hook => $details) {
                if ($match && strpos(strtolower($hook), $match) === false) continue;
                foreach ($details as $sig => $event) {
                    $events.append([
                        'hook' => $hook,
                        'timestamp' => (int) $timestamp,
                        'next_run' => wp_date('Y-m-d H:i:s', $timestamp),
                        'schedule' => isset($event['schedule']) && $event['schedule'] ? $event['schedule'] : 'Non-repeating',
                        'args' => isset($event['args']) ? $event['args'] : [],
                    ]);
                }
            }
        }
        usort($events, function($a, $b){ return $a['timestamp'] <=> $b['timestamp']; });
        return $events;
    }

    public static function run_event($hook) {
        $hook = sanitize_key($hook);
        if (empty($hook)) return false;
        do_action($hook);
        return true;
    }

    public static function delete_event($hook) {
        $hook = sanitize_key($hook);
        if (empty($hook)) return false;
        return wp_clear_scheduled_hook($hook);
    }
}
