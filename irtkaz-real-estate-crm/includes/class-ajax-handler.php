<?php
if (!defined('ABSPATH')) {
    exit;
}

class Irtkaz_Ajax_Handler {
    public static function init() {
        // Frontend AJAX handlers (logged in and guests)
        add_action('wp_ajax_irtkaz_submit_lead', [__CLASS__, 'handle_frontend_submission']);
        add_action('wp_ajax_nopriv_irtkaz_submit_lead', [__CLASS__, 'handle_frontend_submission']);

        // Admin AJAX handlers
        add_action('wp_ajax_irtkaz_update_lead_status', [__CLASS__, 'handle_update_status']);
        add_action('wp_ajax_irtkaz_update_lead_notes', [__CLASS__, 'handle_update_notes']);
        add_action('wp_ajax_irtkaz_delete_lead', [__CLASS__, 'handle_delete_lead']);
        add_action('wp_ajax_irtkaz_export_csv', [__CLASS__, 'handle_export_csv']);
    }

    /**
     * Frontend Lead Submission
     */
    public static function handle_frontend_submission() {
        // Optional nonce check (graceful for page caching)
        if (isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'irtkaz_lead_nonce')) {
            // Log or continue gracefully if nonce expired due to caching
        }

        $full_name    = sanitize_text_field($_POST['fullName'] ?? '');
        $country_code = sanitize_text_field($_POST['countryCode'] ?? '+20');
        $phone_number = sanitize_text_field($_POST['phoneNumber'] ?? '');
        $project      = sanitize_text_field($_POST['project'] ?? 'City Gate New Cairo');
        $agency       = sanitize_text_field($_POST['agency'] ?? 'Irtkaz');
        $page_url     = esc_url_raw($_POST['landingPageUrl'] ?? wp_get_referer());

        if (empty($full_name) || empty($phone_number)) {
            wp_send_json_error([
                'message' => __('Please provide both your name and phone number.', 'irtkaz-crm')
            ], 400);
        }

        // Clean phone number and build full international format
        $clean_phone = preg_replace('/[^0-9]/', '', $phone_number);
        $full_phone  = $country_code . ' ' . $clean_phone;

        // Extract UTM parameters from URL if present
        $utm_source   = sanitize_text_field($_POST['utm_source'] ?? '');
        $utm_medium   = sanitize_text_field($_POST['utm_medium'] ?? '');
        $utm_campaign = sanitize_text_field($_POST['utm_campaign'] ?? '');

        if (empty($utm_source) && !empty($page_url)) {
            $parsed_url = wp_parse_url($page_url);
            if (!empty($parsed_url['query'])) {
                parse_str($parsed_url['query'], $query_params);
                $utm_source   = sanitize_text_field($query_params['utm_source'] ?? '');
                $utm_medium   = sanitize_text_field($query_params['utm_medium'] ?? '');
                $utm_campaign = sanitize_text_field($query_params['utm_campaign'] ?? '');
            }
        }

        $lead_data = [
            'full_name'        => $full_name,
            'country_code'     => $country_code,
            'phone_number'     => $clean_phone,
            'full_phone'       => $full_phone,
            'project_name'     => $project,
            'agency'           => $agency,
            'status'           => 'new',
            'notes'            => '',
            'landing_page_url' => $page_url,
            'utm_source'       => $utm_source,
            'utm_medium'       => $utm_medium,
            'utm_campaign'     => $utm_campaign,
        ];

        $lead_id = Irtkaz_Database::insert_lead($lead_data);

        if (!$lead_id) {
            wp_send_json_error([
                'message' => __('Database error: Could not save your inquiry.', 'irtkaz-crm')
            ], 500);
        }

        // Send Email Notification
        self::send_admin_email($lead_id, $lead_data);

        // Forward to External Webhook if configured
        self::dispatch_webhook($lead_id, $lead_data);

        wp_send_json_success([
            'lead_id'   => $lead_id,
            'full_name' => $full_name,
            'message'   => __('Thank you! Your registration has been securely dispatched.', 'irtkaz-crm')
        ]);
    }

    /**
     * Send instant email alert to admin
     */
    private static function send_admin_email($lead_id, $data) {
        $enabled = get_option('irtkaz_email_notifications', 'yes');
        if ($enabled !== 'yes') {
            return;
        }

        $to = get_option('irtkaz_notification_email', get_option('admin_email'));
        $subject = sprintf('[New Lead #%d] %s — %s', $lead_id, $data['full_name'], $data['project_name']);

        $message  = "New Real Estate Lead Received:\n\n";
        $message .= "• Name: " . $data['full_name'] . "\n";
        $message .= "• Phone: " . $data['full_phone'] . "\n";
        $message .= "• Project: " . $data['project_name'] . "\n";
        $message .= "• Date: " . current_time('Y-m-d H:i:s') . "\n";
        $message .= "• Landing Page: " . $data['landing_page_url'] . "\n";

        if (!empty($data['utm_source'])) {
            $message .= "• UTM Source: " . $data['utm_source'] . "\n";
            $message .= "• UTM Campaign: " . $data['utm_campaign'] . "\n";
        }

        $message .= "\nView all leads in your WordPress Dashboard:\n";
        $message .= admin_url('admin.php?page=irtkaz-leads');

        $headers = ['Content-Type: text/plain; charset=UTF-8'];
        wp_mail($to, $subject, $message, $headers);
    }

    /**
     * Dispatch webhook payload (e.g., Zapier, Make, Google Sheets)
     */
    private static function dispatch_webhook($lead_id, $data) {
        $webhook_url = get_option('irtkaz_webhook_url', '');
        if (empty($webhook_url)) {
            return;
        }

        $payload = [
            'leadId'           => $lead_id,
            'fullName'         => $data['full_name'],
            'countryCode'      => $data['country_code'],
            'phoneNumber'      => $data['full_phone'],
            'rawPhone'         => $data['phone_number'],
            'project'          => $data['project_name'],
            'agency'           => $data['agency'],
            'landingPageUrl'   => $data['landing_page_url'],
            'submissionDate'   => current_time('mysql'),
            'utmSource'        => $data['utm_source'],
            'utmMedium'        => $data['utm_medium'],
            'utmCampaign'      => $data['utm_campaign'],
        ];

        wp_remote_post($webhook_url, [
            'method'      => 'POST',
            'timeout'     => 10,
            'headers'     => ['Content-Type' => 'application/json; charset=utf-8'],
            'body'        => wp_json_encode($payload),
            'data_format' => 'body',
        ]);
    }

    /**
     * Admin: Update Lead Status
     */
    public static function handle_update_status() {
        check_ajax_referer('irtkaz_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $id     = absint($_POST['id'] ?? 0);
        $status = sanitize_text_field($_POST['status'] ?? 'new');

        if (!$id) {
            wp_send_json_error(['message' => 'Invalid lead ID'], 400);
        }

        $updated = Irtkaz_Database::update_status($id, $status);
        if ($updated !== false) {
            wp_send_json_success(['message' => 'Status updated successfully']);
        } else {
            wp_send_json_error(['message' => 'Failed to update status'], 500);
        }
    }

    /**
     * Admin: Update Lead Notes
     */
    public static function handle_update_notes() {
        check_ajax_referer('irtkaz_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $id    = absint($_POST['id'] ?? 0);
        $notes = sanitize_textarea_field($_POST['notes'] ?? '');

        if (!$id) {
            wp_send_json_error(['message' => 'Invalid lead ID'], 400);
        }

        $updated = Irtkaz_Database::update_notes($id, $notes);
        if ($updated !== false) {
            wp_send_json_success(['message' => 'Notes saved successfully']);
        } else {
            wp_send_json_error(['message' => 'Failed to save notes'], 500);
        }
    }

    /**
     * Admin: Delete Lead
     */
    public static function handle_delete_lead() {
        check_ajax_referer('irtkaz_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized'], 403);
        }

        $id = absint($_POST['id'] ?? 0);

        if (!$id) {
            wp_send_json_error(['message' => 'Invalid lead ID'], 400);
        }

        $deleted = Irtkaz_Database::delete_lead($id);
        if ($deleted) {
            wp_send_json_success(['message' => 'Lead deleted successfully']);
        } else {
            wp_send_json_error(['message' => 'Failed to delete lead'], 500);
        }
    }

    /**
     * Admin: Export Leads to CSV
     */
    public static function handle_export_csv() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', 403);
        }

        $status  = sanitize_text_field($_GET['status'] ?? '');
        $project = sanitize_text_field($_GET['project'] ?? '');
        $search  = sanitize_text_field($_GET['search'] ?? '');

        $leads = Irtkaz_Database::get_leads([
            'status'  => $status,
            'project' => $project,
            'search'  => $search,
            'limit'   => 5000,
            'offset'  => 0,
            'orderby' => 'id',
            'order'   => 'DESC'
        ]);

        $filename = 'irtkaz-leads-' . date('Y-m-d_H-i') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // Add BOM for Excel UTF-8 compatibility
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'Lead ID',
            'Full Name',
            'Country Code',
            'Phone Number',
            'Full Phone Number',
            'Project',
            'Agency',
            'Status',
            'Date Created',
            'Landing Page URL',
            'UTM Source',
            'UTM Medium',
            'UTM Campaign',
            'Notes'
        ]);

        foreach ($leads as $lead) {
            fputcsv($output, [
                $lead->id,
                $lead->full_name,
                $lead->country_code,
                $lead->phone_number,
                $lead->full_phone,
                $lead->project_name,
                $lead->agency,
                ucfirst($lead->status),
                $lead->created_at,
                $lead->landing_page_url,
                $lead->utm_source,
                $lead->utm_medium,
                $lead->utm_campaign,
                $lead->notes,
            ]);
        }

        fclose($output);
        exit;
    }
}
