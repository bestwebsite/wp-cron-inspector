<?php
class WPCI_Admin {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_post_wpci_run', [$this, 'handle_run']);
        add_action('admin_post_wpci_delete', [$this, 'handle_delete']);
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

    private function redirect_with_notice($code, $extra = []) {
        $url = add_query_arg(array_merge(['page' => 'wp-cron-inspector', 'wpci_notice' => $code], $extra), admin_url('tools.php'));
        wp_safe_redirect($url);
        exit;
    }

    public function handle_run() {
        if (!current_user_can('manage_options')) wp_die(__('Unauthorized', 'wp-cron-inspector'));
        check_admin_referer('wpci_run_action');
        $hook = isset($_POST['hook']) ? sanitize_key(wp_unslash($_POST['hook'])) : '';
        if ($hook && WPCI_Core::run_event($hook)) {
            $this->redirect_with_notice('ran', ['hook' => $hook]);
        }
        $this->redirect_with_notice('error');
    }

    public function handle_delete() {
        if (!current_user_can('manage_options')) wp_die(__('Unauthorized', 'wp-cron-inspector'));
        check_admin_referer('wpci_delete_action');
        $hook = isset($_POST['hook']) ? sanitize_key(wp_unslash($_POST['hook'])) : '';
        if ($hook) {
            $cleared = WPCI_Core::delete_event($hook);
            $this->redirect_with_notice('deleted', ['hook' => $hook, 'count' => (int) $cleared]);
        }
        $this->redirect_with_notice('error');
    }

    public function render_page() {
        $q = isset($_GET['wpci_q']) ? sanitize_text_field(wp_unslash($_GET['wpci_q'])) : '';
        $events = WPCI_Core::get_cron_events($q);

        echo '<div class="wrap"><h1>' . esc_html__('WP Cron Inspector', 'wp-cron-inspector') . '</h1>';
        echo '<p>' . esc_html__('View, run, or delete scheduled cron events. Use the search to filter by hook name.', 'wp-cron-inspector') . '</p>';

        if (!empty($_GET['wpci_notice'])) {
            $notice = sanitize_key($_GET['wpci_notice']);
            $hook = isset($_GET['hook']) ? esc_html(sanitize_key($_GET['hook'])) : '';
            $count = isset($_GET['count']) ? intval($_GET['count']) : 0;
            if ($notice === 'ran') {
                echo '<div class="updated notice"><p>' . sprintf(esc_html__('Event executed: %s', 'wp-cron-inspector'), '<code>'.$hook.'</code>') . '</p></div>';
            } elseif ($notice === 'deleted') {
                echo '<div class="updated notice"><p>' . sprintf(esc_html__('Cleared scheduled events for: %s (removed: %d)', 'wp-cron-inspector'), '<code>'.$hook.'</code>', $count) . '</p></div>';
            } elseif ($notice === 'error') {
                echo '<div class="error notice"><p>' . esc_html__('Action failed. Please try again.', 'wp-cron-inspector') . '</p></div>';
            }
        }

        echo '<form method="get" style="margin-bottom:12px;">';
        echo '<input type="hidden" name="page" value="wp-cron-inspector" />';
        echo '<label class="screen-reader-text" for="wpci_q">'.esc_html__('Search hooks', 'wp-cron-inspector').'</label>';
        echo '<input type="search" id="wpci_q" name="wpci_q" value="'. esc_attr($q) .'" placeholder="'. esc_attr__('Filter by hook…', 'wp-cron-inspector') .'" /> ';
        submit_button(__('Search'), 'secondary', '', false);
        echo '</form>';

        if (empty($events)) {
            echo '<p><em>' . esc_html__('No cron events found.', 'wp-cron-inspector') . '</em></p></div>';
            return;
        }

        echo '<table class="widefat striped"><thead><tr>
                <th>' . esc_html__('Hook', 'wp-cron-inspector') . '</th>
                <th>' . esc_html__('Next Run', 'wp-cron-inspector') . '</th>
                <th>' . esc_html__('Schedule', 'wp-cron-inspector') . '</th>
                <th>' . esc_html__('Arguments', 'wp-cron-inspector') . '</th>
                <th>' . esc_html__('Actions', 'wp-cron-inspector') . '</th>
              </tr></thead><tbody>';

        foreach ($events as $event) {
            $hook = sanitize_key($event['hook']);
            $next = esc_html($event['next_run']);
            $schedule = esc_html($event['schedule']);
            $args = !empty($event['args']) ? '<code>' . esc_html(json_encode($event['args'])) . '</code>' : '—';

            $run_form = '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" style="display:inline;">'
                      . '<input type="hidden" name="action" value="wpci_run" />'
                      . '<input type="hidden" name="hook" value="' . esc_attr($hook) . '" />'
                      . wp_nonce_field('wpci_run_action', '_wpnonce', true, false)
                      . '<button class="button button-small">'. esc_html__('Run', 'wp-cron-inspector') .'</button>'
                      . '</form>';

            $del_form = '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" style="display:inline;margin-left:6px;">'
                      . '<input type="hidden" name="action" value="wpci_delete" />'
                      . '<input type="hidden" name="hook" value="' . esc_attr($hook) . '" />'
                      . wp_nonce_field('wpci_delete_action', '_wpnonce', true, false)
                      . '<button class="button button-small">'. esc_html__('Delete', 'wp-cron-inspector') .'</button>'
                      . '</form>';

            echo '<tr>
                    <td><code>' . esc_html($hook) . '</code></td>
                    <td>' . $next . '</td>
                    <td>' . $schedule . '</td>
                    <td>' . $args . '</td>
                    <td>' . $run_form . $del_form . '</td>
                  </tr>';
        }
        echo '</tbody></table></div>';
    }
}
new WPCI_Admin();
