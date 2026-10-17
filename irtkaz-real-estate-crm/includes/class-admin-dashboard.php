<?php
if (!defined('ABSPATH')) {
    exit;
}

class Irtkaz_Admin_Dashboard {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function register_admin_menu() {
        $count = Irtkaz_Database::get_leads_count(['status' => 'new']);
        $menu_title = __('Real Estate CRM', 'irtkaz-crm');
        if ($count > 0) {
            $menu_title .= sprintf(' <span class="update-plugins count-%1$d"><span class="plugin-count">%1$d</span></span>', $count);
        }

        add_menu_page(
            __('Irtkaz Real Estate Leads', 'irtkaz-crm'),
            $menu_title,
            'manage_options',
            'irtkaz-leads',
            [__CLASS__, 'render_leads_page'],
            'dashicons-building',
            26
        );

        add_submenu_page(
            'irtkaz-leads',
            __('All Leads & Inquiries', 'irtkaz-crm'),
            __('Leads & Inquiries', 'irtkaz-crm'),
            'manage_options',
            'irtkaz-leads',
            [__CLASS__, 'render_leads_page']
        );

        add_submenu_page(
            'irtkaz-leads',
            __('CRM & Webhook Settings', 'irtkaz-crm'),
            __('Settings & Webhooks', 'irtkaz-crm'),
            'manage_options',
            'irtkaz-settings',
            [__CLASS__, 'render_settings_page']
        );

        add_submenu_page(
            'irtkaz-leads',
            __('1-Click Landing Page Creator', 'irtkaz-crm'),
            __('Page Generator', 'irtkaz-crm'),
            'manage_options',
            'irtkaz-generator',
            ['Irtkaz_Page_Generator', 'render_generator_page']
        );
    }

    public static function enqueue_admin_assets($hook) {
        if (strpos($hook, 'irtkaz-') === false) {
            return;
        }

        wp_enqueue_style(
            'irtkaz-admin-css',
            IRTKAZ_CRM_URL . 'assets/css/admin-dashboard.css',
            [],
            IRTKAZ_CRM_VERSION
        );

        wp_enqueue_script(
            'irtkaz-admin-js',
            IRTKAZ_CRM_URL . 'assets/js/admin-dashboard.js',
            ['jquery'],
            IRTKAZ_CRM_VERSION,
            true
        );

        wp_localize_script('irtkaz-admin-js', 'irtkaz_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('irtkaz_admin_nonce'),
            'strings'  => [
                'confirm_delete' => __('Are you sure you want to delete this lead? This cannot be undone.', 'irtkaz-crm'),
                'status_updated' => __('Lead status updated!', 'irtkaz-crm'),
                'notes_saved'    => __('Notes saved successfully!', 'irtkaz-crm'),
            ]
        ]);
    }

    public static function register_settings() {
        register_setting('irtkaz_settings_group', 'irtkaz_default_project');
        register_setting('irtkaz_settings_group', 'irtkaz_agency_name');
        register_setting('irtkaz_settings_group', 'irtkaz_whatsapp_number');
        register_setting('irtkaz_settings_group', 'irtkaz_notification_email');
        register_setting('irtkaz_settings_group', 'irtkaz_email_notifications');
        register_setting('irtkaz_settings_group', 'irtkaz_webhook_url');
        register_setting('irtkaz_settings_group', 'irtkaz_privacy_url');
        register_setting('irtkaz_settings_group', 'irtkaz_terms_url');
    }

    /**
     * Render the Real Estate Leads CRM Dashboard
     */
    public static function render_leads_page() {
        $stats = Irtkaz_Database::get_stats();

        $search  = sanitize_text_field($_GET['s'] ?? '');
        $status  = sanitize_text_field($_GET['status'] ?? '');
        $project = sanitize_text_field($_GET['project'] ?? '');
        $paged   = max(1, absint($_GET['paged'] ?? 1));
        $limit   = 25;
        $offset  = ($paged - 1) * $limit;

        $total_leads = Irtkaz_Database::get_leads_count([
            'search'  => $search,
            'status'  => $status,
            'project' => $project,
        ]);

        $leads = Irtkaz_Database::get_leads([
            'search'  => $search,
            'status'  => $status,
            'project' => $project,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);

        $total_pages = ceil($total_leads / $limit);
        $export_url = add_query_arg([
            'action'  => 'irtkaz_export_csv',
            'search'  => $search,
            'status'  => $status,
            'project' => $project,
        ], admin_url('admin-ajax.php'));

        ?>
        <div class="wrap irtkaz-crm-wrap">
            <div class="irtkaz-header-bar">
                <div class="irtkaz-header-title">
                    <span class="irtkaz-badge">IRTKAZ REAL ESTATE CRM</span>
                    <h1><?php esc_html_e('Leads & Client Inquiries', 'irtkaz-crm'); ?></h1>
                    <p class="irtkaz-header-sub"><?php esc_html_e('Manage, contact, and convert inquiries captured across your landing pages.', 'irtkaz-crm'); ?></p>
                </div>
                <div class="irtkaz-header-actions">
                    <a href="<?php echo esc_url($export_url); ?>" class="button button-primary irtkaz-btn-export">
                        <span class="dashicons dashicons-download"></span> <?php esc_html_e('Export to CSV', 'irtkaz-crm'); ?>
                    </a>
                </div>
            </div>

            <!-- KPI Summary Cards -->
            <div class="irtkaz-kpi-grid">
                <div class="irtkaz-kpi-card">
                    <div class="kpi-icon icon-blue"><span class="dashicons dashicons-groups"></span></div>
                    <div class="kpi-data">
                        <span class="kpi-label"><?php esc_html_e('Total Leads', 'irtkaz-crm'); ?></span>
                        <span class="kpi-value"><?php echo number_format_i18n($stats['total']); ?></span>
                    </div>
                </div>

                <div class="irtkaz-kpi-card">
                    <div class="kpi-icon icon-green"><span class="dashicons dashicons-calendar-alt"></span></div>
                    <div class="kpi-data">
                        <span class="kpi-label"><?php esc_html_e("Today's Inquiries", 'irtkaz-crm'); ?></span>
                        <span class="kpi-value"><?php echo number_format_i18n($stats['today']); ?></span>
                    </div>
                </div>

                <div class="irtkaz-kpi-card">
                    <div class="kpi-icon icon-amber"><span class="dashicons dashicons-phone"></span></div>
                    <div class="kpi-data">
                        <span class="kpi-label"><?php esc_html_e('Contacted', 'irtkaz-crm'); ?></span>
                        <span class="kpi-value"><?php echo number_format_i18n($stats['contacted']); ?></span>
                    </div>
                </div>

                <div class="irtkaz-kpi-card">
                    <div class="kpi-icon icon-purple"><span class="dashicons dashicons-awards"></span></div>
                    <div class="kpi-data">
                        <span class="kpi-label"><?php esc_html_e('Qualified / Won', 'irtkaz-crm'); ?></span>
                        <span class="kpi-value"><?php echo number_format_i18n($stats['qualified'] + $stats['won']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Filters & Search Toolbar -->
            <div class="irtkaz-toolbar">
                <form method="get" action="<?php echo esc_url(admin_url('admin.php')); ?>" class="irtkaz-filter-form">
                    <input type="hidden" name="page" value="irtkaz-leads">

                    <!-- Search Input -->
                    <div class="irtkaz-search-box">
                        <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="<?php esc_attr_e('Search name, phone, notes...', 'irtkaz-crm'); ?>">
                        <button type="submit" class="button"><span class="dashicons dashicons-search"></span></button>
                    </div>

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()">
                        <option value=""><?php esc_html_e('All Statuses', 'irtkaz-crm'); ?></option>
                        <option value="new" <?php selected($status, 'new'); ?>><?php esc_html_e('New', 'irtkaz-crm'); ?></option>
                        <option value="contacted" <?php selected($status, 'contacted'); ?>><?php esc_html_e('Contacted', 'irtkaz-crm'); ?></option>
                        <option value="follow_up" <?php selected($status, 'follow_up'); ?>><?php esc_html_e('Follow Up', 'irtkaz-crm'); ?></option>
                        <option value="qualified" <?php selected($status, 'qualified'); ?>><?php esc_html_e('Qualified', 'irtkaz-crm'); ?></option>
                        <option value="won" <?php selected($status, 'won'); ?>><?php esc_html_e('Closed / Won', 'irtkaz-crm'); ?></option>
                        <option value="lost" <?php selected($status, 'lost'); ?>><?php esc_html_e('Lost', 'irtkaz-crm'); ?></option>
                    </select>

                    <?php if (!empty($search) || !empty($status)): ?>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=irtkaz-leads')); ?>" class="button irtkaz-btn-reset">
                            <?php esc_html_e('Reset', 'irtkaz-crm'); ?>
                        </a>
                    <?php endif; ?>
                </form>

                <div class="irtkaz-lead-count-badge">
                    <?php printf(esc_html__('Showing %d of %d leads', 'irtkaz-crm'), count($leads), $total_leads); ?>
                </div>
            </div>

            <!-- Leads Table -->
            <div class="irtkaz-table-card">
                <table class="wp-list-table widefat fixed striped table-view-list irtkaz-leads-table">
                    <thead>
                        <tr>
                            <th class="col-id" style="width: 60px;"><?php esc_html_e('#ID', 'irtkaz-crm'); ?></th>
                            <th class="col-name"><?php esc_html_e('Lead Name', 'irtkaz-crm'); ?></th>
                            <th class="col-phone"><?php esc_html_e('Phone Number', 'irtkaz-crm'); ?></th>
                            <th class="col-project"><?php esc_html_e('Project', 'irtkaz-crm'); ?></th>
                            <th class="col-date"><?php esc_html_e('Submitted At', 'irtkaz-crm'); ?></th>
                            <th class="col-status" style="width: 140px;"><?php esc_html_e('Status', 'irtkaz-crm'); ?></th>
                            <th class="col-actions" style="width: 180px; text-align: right;"><?php esc_html_e('Quick Actions', 'irtkaz-crm'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($leads)): ?>
                            <tr>
                                <td colspan="7" class="irtkaz-empty-state">
                                    <span class="dashicons dashicons-info"></span>
                                    <h3><?php esc_html_e('No leads found.', 'irtkaz-crm'); ?></h3>
                                    <p><?php esc_html_e('Once prospective buyers submit your landing page form, inquiries will appear here instantly.', 'irtkaz-crm'); ?></p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($leads as $lead): 
                                $clean_phone = preg_replace('/[^0-9]/', '', $lead->full_phone);
                                $wa_msg = sprintf(
                                    __('Hello %s, I am contacting you regarding your inquiry for %s on our landing page. Are you currently available for a quick chat?', 'irtkaz-crm'),
                                    $lead->full_name,
                                    $lead->project_name
                                );
                                $wa_url = 'https://wa.me/' . $clean_phone . '?text=' . rawurlencode($wa_msg);
                            ?>
                                <tr id="irtkaz-lead-row-<?php echo esc_attr($lead->id); ?>" data-id="<?php echo esc_attr($lead->id); ?>">
                                    <td class="col-id"><strong>#<?php echo esc_html($lead->id); ?></strong></td>
                                    
                                    <td class="col-name">
                                        <div class="irtkaz-user-cell">
                                            <div class="irtkaz-avatar-initials"><?php echo esc_html(mb_substr($lead->full_name, 0, 1)); ?></div>
                                            <div class="irtkaz-user-info">
                                                <span class="irtkaz-user-name"><?php echo esc_html($lead->full_name); ?></span>
                                                <?php if (!empty($lead->utm_source)): ?>
                                                    <span class="irtkaz-utm-pill"><?php echo esc_html($lead->utm_source); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="col-phone">
                                        <div class="irtkaz-phone-wrap">
                                            <span class="irtkaz-flag-badge"><?php echo esc_html($lead->country_code); ?></span>
                                            <a href="tel:<?php echo esc_attr($lead->full_phone); ?>" class="irtkaz-phone-link"><?php echo esc_html($lead->full_phone); ?></a>
                                        </div>
                                    </td>

                                    <td class="col-project">
                                        <span class="irtkaz-project-badge"><?php echo esc_html($lead->project_name); ?></span>
                                    </td>

                                    <td class="col-date">
                                        <span class="irtkaz-date-main"><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($lead->created_at))); ?></span>
                                        <span class="irtkaz-date-diff"><?php echo esc_html(human_time_diff(strtotime($lead->created_at), current_time('timestamp'))) . ' ' . esc_html__('ago', 'irtkaz-crm'); ?></span>
                                    </td>

                                    <td class="col-status">
                                        <select class="irtkaz-status-select irtkaz-status-<?php echo esc_attr($lead->status); ?>" data-id="<?php echo esc_attr($lead->id); ?>">
                                            <option value="new" <?php selected($lead->status, 'new'); ?>><?php esc_html_e('New', 'irtkaz-crm'); ?></option>
                                            <option value="contacted" <?php selected($lead->status, 'contacted'); ?>><?php esc_html_e('Contacted', 'irtkaz-crm'); ?></option>
                                            <option value="follow_up" <?php selected($lead->status, 'follow_up'); ?>><?php esc_html_e('Follow Up', 'irtkaz-crm'); ?></option>
                                            <option value="qualified" <?php selected($lead->status, 'qualified'); ?>><?php esc_html_e('Qualified', 'irtkaz-crm'); ?></option>
                                            <option value="won" <?php selected($lead->status, 'won'); ?>><?php esc_html_e('Closed / Won', 'irtkaz-crm'); ?></option>
                                            <option value="lost" <?php selected($lead->status, 'lost'); ?>><?php esc_html_e('Lost', 'irtkaz-crm'); ?></option>
                                        </select>
                                    </td>

                                    <td class="col-actions" style="text-align: right;">
                                        <!-- WhatsApp 1-Click Button -->
                                        <a href="<?php echo esc_url($wa_url); ?>" target="_blank" class="irtkaz-action-btn btn-wa" title="<?php esc_attr_e('Chat on WhatsApp', 'irtkaz-crm'); ?>">
                                            <svg viewBox="0 0 448 512" width="14" height="14" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                                        </a>

                                        <!-- Phone Call Button -->
                                        <a href="tel:<?php echo esc_attr($lead->full_phone); ?>" class="irtkaz-action-btn btn-call" title="<?php esc_attr_e('Call Phone', 'irtkaz-crm'); ?>">
                                            <span class="dashicons dashicons-phone"></span>
                                        </a>

                                        <!-- Notes Button -->
                                        <button type="button" class="irtkaz-action-btn btn-notes" data-id="<?php echo esc_attr($lead->id); ?>" data-notes="<?php echo esc_attr($lead->notes); ?>" title="<?php esc_attr_e('Add / View Notes', 'irtkaz-crm'); ?>">
                                            <span class="dashicons dashicons-edit"></span>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" class="irtkaz-action-btn btn-delete" data-id="<?php echo esc_attr($lead->id); ?>" title="<?php esc_attr_e('Delete Lead', 'irtkaz-crm'); ?>">
                                            <span class="dashicons dashicons-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="tablenav">
                    <div class="tablenav-pages">
                        <?php
                        echo paginate_links([
                            'base'      => add_query_arg('paged', '%#%'),
                            'format'    => '',
                            'prev_text' => __('&laquo; Prev', 'irtkaz-crm'),
                            'next_text' => __('Next &raquo;', 'irtkaz-crm'),
                            'total'     => $total_pages,
                            'current'   => $paged,
                        ]);
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Notes Modal -->
        <div id="irtkaz-notes-modal" class="irtkaz-admin-modal" style="display: none;">
            <div class="irtkaz-modal-overlay"></div>
            <div class="irtkaz-modal-window">
                <div class="irtkaz-modal-header">
                    <h3><?php esc_html_e('Internal Lead Notes', 'irtkaz-crm'); ?></h3>
                    <button type="button" class="irtkaz-modal-close">&times;</button>
                </div>
                <div class="irtkaz-modal-body">
                    <input type="hidden" id="modal-lead-id">
                    <textarea id="modal-lead-notes" rows="6" placeholder="<?php esc_attr_e('Write private notes regarding client budget, preferred unit, follow-up schedule...', 'irtkaz-crm'); ?>"></textarea>
                </div>
                <div class="irtkaz-modal-footer">
                    <button type="button" class="button button-secondary irtkaz-modal-close"><?php esc_html_e('Cancel', 'irtkaz-crm'); ?></button>
                    <button type="button" class="button button-primary" id="btn-save-notes"><?php esc_html_e('Save Notes', 'irtkaz-crm'); ?></button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render CRM & Webhooks Settings Page
     */
    public static function render_settings_page() {
        ?>
        <div class="wrap irtkaz-crm-wrap">
            <div class="irtkaz-header-bar">
                <div class="irtkaz-header-title">
                    <span class="irtkaz-badge">IRTKAZ CONFIGURATION</span>
                    <h1><?php esc_html_e('CRM & Webhook Settings', 'irtkaz-crm'); ?></h1>
                    <p class="irtkaz-header-sub"><?php esc_html_e('Configure WhatsApp numbers, automated email notifications, and external webhook integrations.', 'irtkaz-crm'); ?></p>
                </div>
            </div>

            <form method="post" action="options.php" class="irtkaz-settings-form">
                <?php
                settings_fields('irtkaz_settings_group');
                do_settings_sections('irtkaz_settings_group');
                ?>

                <div class="irtkaz-settings-card">
                    <h2><?php esc_html_e('Project & Agency Details', 'irtkaz-crm'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="irtkaz_default_project"><?php esc_html_e('Default Project Name', 'irtkaz-crm'); ?></label></th>
                            <td>
                                <input type="text" id="irtkaz_default_project" name="irtkaz_default_project" value="<?php echo esc_attr(get_option('irtkaz_default_project', 'City Gate New Cairo')); ?>" class="regular-text">
                                <p class="description"><?php esc_html_e('Default project assigned to incoming inquiries (e.g. City Gate New Cairo, Hyde Park, Palm Hills).', 'irtkaz-crm'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="irtkaz_agency_name"><?php esc_html_e('Agency / Consultancy Name', 'irtkaz-crm'); ?></label></th>
                            <td>
                                <input type="text" id="irtkaz_agency_name" name="irtkaz_agency_name" value="<?php echo esc_attr(get_option('irtkaz_agency_name', 'Irtkaz')); ?>" class="regular-text">
                                <p class="description"><?php esc_html_e('Displayed in the legal disclaimers and branding (e.g. Irtkaz or properties-z.com).', 'irtkaz-crm'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="irtkaz_whatsapp_number"><?php esc_html_e('WhatsApp Sales Phone Number', 'irtkaz-crm'); ?></label></th>
                            <td>
                                <input type="text" id="irtkaz_whatsapp_number" name="irtkaz_whatsapp_number" value="<?php echo esc_attr(get_option('irtkaz_whatsapp_number', '+201033373331')); ?>" class="regular-text">
                                <p class="description"><?php esc_html_e('International format with country code (e.g. +201033373331). Used for landing page WhatsApp CTA buttons.', 'irtkaz-crm'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="irtkaz-settings-card">
                    <h2><?php esc_html_e('Email Notifications', 'irtkaz-crm'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable Email Alerts', 'irtkaz-crm'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="irtkaz_email_notifications" value="yes" <?php checked(get_option('irtkaz_email_notifications', 'yes'), 'yes'); ?>>
                                    <?php esc_html_e('Send an immediate email alert when a new inquiry arrives.', 'irtkaz-crm'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="irtkaz_notification_email"><?php esc_html_e('Recipient Email(s)', 'irtkaz-crm'); ?></label></th>
                            <td>
                                <input type="text" id="irtkaz_notification_email" name="irtkaz_notification_email" value="<?php echo esc_attr(get_option('irtkaz_notification_email', get_option('admin_email'))); ?>" class="regular-text">
                                <p class="description"><?php esc_html_e('Email address where new lead notifications will be sent (comma-separated for multiple recipients).', 'irtkaz-crm'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="irtkaz-settings-card">
                    <h2><?php esc_html_e('External Webhook Integration (100% Free)', 'irtkaz-crm'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="irtkaz_webhook_url"><?php esc_html_e('Webhook URL', 'irtkaz-crm'); ?></label></th>
                            <td>
                                <input type="url" id="irtkaz_webhook_url" name="irtkaz_webhook_url" value="<?php echo esc_attr(get_option('irtkaz_webhook_url', '')); ?>" class="large-text" placeholder="https://hooks.zapier.com/hooks/catch/... or Make.com / Google Sheets webhook">
                                <p class="description"><?php esc_html_e('Optional: Automatically mirror every new inquiry to Zapier, Make.com, Pabbly, or a Google Sheet script in real-time.', 'irtkaz-crm'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="irtkaz-settings-card">
                    <h2><?php esc_html_e('Legal Pages Routing', 'irtkaz-crm'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="irtkaz_privacy_url"><?php esc_html_e('Privacy Policy URL', 'irtkaz-crm'); ?></label></th>
                            <td>
                                <input type="text" id="irtkaz_privacy_url" name="irtkaz_privacy_url" value="<?php echo esc_attr(get_option('irtkaz_privacy_url', '#')); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="irtkaz_terms_url"><?php esc_html_e('Terms & Conditions URL', 'irtkaz-crm'); ?></label></th>
                            <td>
                                <input type="text" id="irtkaz_terms_url" name="irtkaz_terms_url" value="<?php echo esc_attr(get_option('irtkaz_terms_url', '#')); ?>" class="regular-text">
                            </td>
                        </tr>
                    </table>
                </div>

                <?php submit_button(__('Save Settings', 'irtkaz-crm')); ?>
            </form>
        </div>
        <?php
    }
}
