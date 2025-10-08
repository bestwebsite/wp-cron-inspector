<?php
class WPCI_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
    }

    public function add_menu_page() {
        add_management_page(
            __('Cron Inspector', 'wp-cron-inspector'),
            __('Cron Inspector', 'wp-cron-inspector'),
            'manage_options',
            'wp-cron-inspector',
            [$this, 'render_page']
        );
    }

    public function render_page() {
        $events = WPCI_Core::get_cron_events();
        echo '<div class="wrap"><h1>WP Cron Inspector</h1>';
        echo '<p>View, run, or delete scheduled cron events.</p>';
        if (empty($events)) {
            echo '<p><em>No cron events found.</em></p></div>';
            return;
        }

        echo '<table class="widefat striped"><thead><tr>
                <th>Hook</th><th>Next Run</th><th>Schedule</th><th>Arguments</th><th>Actions</th>
              </tr></thead><tbody>';

        foreach ($events as $event) {
            $hook = esc_html($event['hook']);
            $run_url = wp_nonce_url(admin_url('tools.php?page=wp-cron-inspector&run=' . $hook), 'run_cron_' . $hook);
            $del_url = wp_nonce_url(admin_url('tools.php?page=wp-cron-inspector&delete=' . $hook), 'del_cron_' . $hook);

            echo "<tr>
                    <td>{$hook}</td>
                    <td>{$event['next_run']}</td>
                    <td>{$event['schedule']}</td>
                    <td><code>{$event['args']}</code></td>
                    <td>
                        <a href='{$run_url}' class='button'>Run</a>
                        <a href='{$del_url}' class='button'>Delete</a>
                    </td>
                  </tr>";
        }
        echo '</tbody></table></div>';

        if (isset($_GET['run']) && check_admin_referer('run_cron_' . $_GET['run'])) {
            WPCI_Core::run_event($_GET['run']);
            echo '<div class="updated"><p>Event executed: ' . esc_html($_GET['run']) . '</p></div>';
        }

        if (isset($_GET['delete']) && check_admin_referer('del_cron_' . $_GET['delete'])) {
            WPCI_Core::delete_event($_GET['delete']);
            echo '<div class="updated"><p>Event deleted: ' . esc_html($_GET['delete']) . '</p></div>';
        }
    }
}
new WPCI_Admin();
