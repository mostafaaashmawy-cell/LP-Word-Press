<?php
if (!defined('ABSPATH')) {
    exit;
}

class Irtkaz_Database {
    public static function table_name() {
        global $wpdb;
        return $wpdb->prefix . 'irtkaz_leads';
    }

    public static function init() {
        // Initialization if needed
    }

    public static function create_tables() {
        global $wpdb;
        $table_name = self::table_name();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            full_name varchar(191) NOT NULL,
            country_code varchar(10) DEFAULT '+20',
            phone_number varchar(50) NOT NULL,
            full_phone varchar(60) NOT NULL,
            project_name varchar(191) DEFAULT 'City Gate New Cairo',
            agency varchar(191) DEFAULT 'Irtkaz',
            status varchar(50) DEFAULT 'new',
            notes text NULL,
            landing_page_url text NULL,
            utm_source varchar(100) DEFAULT '',
            utm_medium varchar(100) DEFAULT '',
            utm_campaign varchar(100) DEFAULT '',
            ip_address varchar(45) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY status_idx (status),
            KEY project_idx (project_name),
            KEY created_idx (created_at)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function insert_lead($data) {
        global $wpdb;
        $table = self::table_name();

        $defaults = [
            'full_name'        => '',
            'country_code'     => '+20',
            'phone_number'     => '',
            'full_phone'       => '',
            'project_name'     => 'City Gate New Cairo',
            'agency'           => 'Irtkaz',
            'status'           => 'new',
            'notes'            => '',
            'landing_page_url' => '',
            'utm_source'       => '',
            'utm_medium'       => '',
            'utm_campaign'     => '',
            'ip_address'       => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
            'created_at'       => current_time('mysql'),
        ];

        $data = wp_parse_args($data, $defaults);

        $inserted = $wpdb->insert(
            $table,
            [
                'full_name'        => sanitize_text_field($data['full_name']),
                'country_code'     => sanitize_text_field($data['country_code']),
                'phone_number'     => sanitize_text_field($data['phone_number']),
                'full_phone'       => sanitize_text_field($data['full_phone']),
                'project_name'     => sanitize_text_field($data['project_name']),
                'agency'           => sanitize_text_field($data['agency']),
                'status'           => sanitize_text_field($data['status']),
                'notes'            => sanitize_textarea_field($data['notes']),
                'landing_page_url' => esc_url_raw($data['landing_page_url']),
                'utm_source'       => sanitize_text_field($data['utm_source']),
                'utm_medium'       => sanitize_text_field($data['utm_medium']),
                'utm_campaign'     => sanitize_text_field($data['utm_campaign']),
                'ip_address'       => sanitize_text_field($data['ip_address']),
                'created_at'       => $data['created_at'],
            ]
        );

        if ($inserted) {
            return $wpdb->insert_id;
        }

        return false;
    }

    public static function get_leads($args = []) {
        global $wpdb;
        $table = self::table_name();

        $defaults = [
            'status'   => '',
            'search'   => '',
            'project'  => '',
            'limit'    => 50,
            'offset'   => 0,
            'orderby'  => 'id',
            'order'    => 'DESC',
        ];

        $args = wp_parse_args($args, $defaults);

        $where = ['1=1'];
        $params = [];

        if (!empty($args['status'])) {
            $where[] = 'status = %s';
            $params[] = $args['status'];
        }

        if (!empty($args['project'])) {
            $where[] = 'project_name = %s';
            $params[] = $args['project'];
        }

        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where[] = '(full_name LIKE %s OR phone_number LIKE %s OR full_phone LIKE %s OR notes LIKE %s)';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $where_clause = implode(' AND ', $where);
        $order_by = in_array(strtoupper($args['orderby']), ['ID', 'CREATED_AT', 'FULL_NAME', 'STATUS']) ? $args['orderby'] : 'id';
        $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';

        $query = "SELECT * FROM $table WHERE $where_clause ORDER BY $order_by $order LIMIT %d OFFSET %d";
        $params[] = absint($args['limit']);
        $params[] = absint($args['offset']);

        return $wpdb->get_results($wpdb->prepare($query, $params));
    }

    public static function get_leads_count($args = []) {
        global $wpdb;
        $table = self::table_name();

        $where = ['1=1'];
        $params = [];

        if (!empty($args['status'])) {
            $where[] = 'status = %s';
            $params[] = $args['status'];
        }

        if (!empty($args['project'])) {
            $where[] = 'project_name = %s';
            $params[] = $args['project'];
        }

        if (!empty($args['search'])) {
            $search = '%' . $wpdb->esc_like($args['search']) . '%';
            $where[] = '(full_name LIKE %s OR phone_number LIKE %s OR full_phone LIKE %s OR notes LIKE %s)';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $where_clause = implode(' AND ', $where);
        $query = "SELECT COUNT(*) FROM $table WHERE $where_clause";

        if (!empty($params)) {
            return (int) $wpdb->get_var($wpdb->prepare($query, $params));
        }

        return (int) $wpdb->get_var($query);
    }

    public static function get_lead($id) {
        global $wpdb;
        $table = self::table_name();
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    }

    public static function update_status($id, $status) {
        global $wpdb;
        $table = self::table_name();
        return $wpdb->update($table, ['status' => sanitize_text_field($status)], ['id' => absint($id)]);
    }

    public static function update_notes($id, $notes) {
        global $wpdb;
        $table = self::table_name();
        return $wpdb->update($table, ['notes' => sanitize_textarea_field($notes)], ['id' => absint($id)]);
    }

    public static function delete_lead($id) {
        global $wpdb;
        $table = self::table_name();
        return $wpdb->delete($table, ['id' => absint($id)]);
    }

    public static function get_stats() {
        global $wpdb;
        $table = self::table_name();

        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table) {
            return [
                'total'     => 0,
                'today'     => 0,
                'new'       => 0,
                'contacted' => 0,
                'qualified' => 0,
                'won'       => 0,
            ];
        }

        $today = current_time('Y-m-d');

        $total     = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
        $today_cnt = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE DATE(created_at) = %s", $today));
        $new_cnt   = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'new'");
        $cont_cnt  = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'contacted'");
        $qual_cnt  = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'qualified'");
        $won_cnt   = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table WHERE status = 'won'");

        return [
            'total'     => $total,
            'today'     => $today_cnt,
            'new'       => $new_cnt,
            'contacted' => $cont_cnt,
            'qualified' => $qual_cnt,
            'won'       => $won_cnt,
        ];
    }
}
