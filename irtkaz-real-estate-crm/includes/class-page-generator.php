<?php
if (!defined('ABSPATH')) {
    exit;
}

class Irtkaz_Page_Generator {
    public static function init() {
        add_action('admin_post_irtkaz_generate_landing_page', [__CLASS__, 'handle_page_generation']);
        add_filter('theme_page_templates', [__CLASS__, 'register_plugin_page_template']);
        add_filter('template_include', [__CLASS__, 'load_plugin_page_template']);
    }

    /**
     * Register Built-in Full-Width Real Estate Landing Page Template
     */
    public static function register_plugin_page_template($templates) {
        $templates['irtkaz-real-estate-canvas'] = __('City Gate Luxury Landing Page (Irtkaz)', 'irtkaz-crm');
        return $templates;
    }

    /**
     * Load the template file when selected
     */
    public static function load_plugin_page_template($template) {
        if (is_page()) {
            $page_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
            if ($page_template === 'irtkaz-real-estate-canvas') {
                $file = IRTKAZ_CRM_PATH . 'templates/template-landing-page.php';
                if (file_exists($file)) {
                    return $file;
                }
            }
        }
        return $template;
    }

    /**
     * Handle the 1-Click Page Generation POST
     */
    public static function handle_page_generation() {
        if (!current_user_can('edit_pages')) {
            wp_die('Unauthorized', 403);
        }

        check_admin_referer('irtkaz_gen_nonce');

        $page_title = sanitize_text_field($_POST['page_title'] ?? 'City Gate New Cairo');
        $page_slug  = sanitize_title($_POST['page_slug'] ?? 'city-gate-new-cairo');

        // Check if page already exists
        $existing_page = get_page_by_path($page_slug);

        if ($existing_page) {
            $page_id = $existing_page->ID;
            wp_update_post([
                'ID'          => $page_id,
                'post_status' => 'publish',
            ]);
        } else {
            $page_id = wp_insert_post([
                'post_title'   => $page_title,
                'post_name'    => $page_slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '<!-- Irtkaz Real Estate Landing Page -->',
            ]);
        }

        if (is_wp_error($page_id)) {
            wp_die($page_id->get_error_message());
        }

        // Set Template to our built-in canvas template
        update_post_meta($page_id, '_wp_page_template', 'irtkaz-real-estate-canvas');

        // Enable Elementor Canvas compatibility
        update_post_meta($page_id, '_elementor_template_type', 'page');
        update_post_meta($page_id, '_elementor_version', '3.20.0');

        // Redirect back with success message
        wp_safe_redirect(add_query_arg([
            'page'             => 'irtkaz-generator',
            'generated_page_id'=> $page_id,
            'success'          => 1
        ], admin_url('admin.php')));
        exit;
    }

    /**
     * Render the Page Generator UI
     */
    public static function render_generator_page() {
        $generated_page_id = absint($_GET['generated_page_id'] ?? 0);
        $success = isset($_GET['success']) && $_GET['success'] == 1;
        $page_url = $generated_page_id ? get_permalink($generated_page_id) : '';
        $elementor_url = $generated_page_id ? admin_url('post.php?post=' . $generated_page_id . '&action=elementor') : '';
        ?>
        <div class="wrap irtkaz-crm-wrap">
            <div class="irtkaz-header-bar">
                <div class="irtkaz-header-title">
                    <span class="irtkaz-badge">ONE-CLICK SETUP</span>
                    <h1><?php esc_html_e('Landing Page Generator', 'irtkaz-crm'); ?></h1>
                    <p class="irtkaz-header-sub"><?php esc_html_e('Instantly generate the City Gate New Cairo luxury landing page with zero coding.', 'irtkaz-crm'); ?></p>
                </div>
            </div>

            <?php if ($success && $generated_page_id): ?>
                <div class="notice notice-success is-dismissible" style="padding: 16px; margin: 20px 0; border-left-color: #10b981; background: #ecfdf5;">
                    <h3 style="margin-top: 0; color: #065f46;">🚀 <?php esc_html_e('Landing Page Created Successfully!', 'irtkaz-crm'); ?></h3>
                    <p style="color: #047857; font-size: 15px;">
                        <?php esc_html_e('Your luxury City Gate landing page is live and ready for traffic or custom edits.', 'irtkaz-crm'); ?>
                    </p>
                    <div style="display: flex; gap: 12px; margin-top: 14px;">
                        <a href="<?php echo esc_url($page_url); ?>" target="_blank" class="button button-primary" style="background: #091524; border-color: #091524; padding: 6px 18px; font-weight: 600;">
                            <span class="dashicons dashicons-external" style="margin-top: 4px;"></span> <?php esc_html_e('View Live Landing Page', 'irtkaz-crm'); ?>
                        </a>
                        <a href="<?php echo esc_url($elementor_url); ?>" target="_blank" class="button button-secondary" style="font-weight: 600; padding: 6px 18px;">
                            <span class="dashicons dashicons-edit" style="margin-top: 4px;"></span> <?php esc_html_e('Edit with Elementor', 'irtkaz-crm'); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="irtkaz-generator-grid" style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; margin-top: 24px;">
                <!-- Form Box -->
                <div class="irtkaz-settings-card" style="margin-bottom: 0;">
                    <h2><?php esc_html_e('Generate City Gate Landing Page', 'irtkaz-crm'); ?></h2>
                    <p style="color: #64748b; margin-bottom: 24px; font-size: 14px;">
                        <?php esc_html_e('This creates a clean, full-screen landing page using the exact layout, luxury typography, itemized pricing breakdown, amenities, and AJAX lead form from the reference site.', 'irtkaz-crm'); ?>
                    </p>

                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <input type="hidden" name="action" value="irtkaz_generate_landing_page">
                        <?php wp_nonce_field('irtkaz_gen_nonce'); ?>

                        <div style="margin-bottom: 20px;">
                            <label for="page_title" style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 13px; text-transform: uppercase; color: #1e293b;">
                                <?php esc_html_e('Page Title', 'irtkaz-crm'); ?>
                            </label>
                            <input type="text" id="page_title" name="page_title" value="City Gate New Cairo — Qatari Diar" class="large-text" required style="padding: 10px 14px; border-radius: 6px;">
                        </div>

                        <div style="margin-bottom: 24px;">
                            <label for="page_slug" style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 13px; text-transform: uppercase; color: #1e293b;">
                                <?php esc_html_e('URL Slug (Permalink)', 'irtkaz-crm'); ?>
                            </label>
                            <input type="text" id="page_slug" name="page_slug" value="city-gate-new-cairo" class="large-text" required style="padding: 10px 14px; border-radius: 6px;">
                            <p class="description" style="margin-top: 6px; color: #64748b;">
                                <?php echo esc_html(home_url('/')); ?><strong>city-gate-new-cairo</strong>/
                            </p>
                        </div>

                        <button type="submit" class="button button-primary" style="background: #091524; border-color: #091524; padding: 10px 28px; height: auto; font-size: 15px; font-weight: 700; letter-spacing: 0.5px; border-radius: 6px;">
                            ⚡ <?php esc_html_e('Create Landing Page Now', 'irtkaz-crm'); ?>
                        </button>
                    </form>
                </div>

                <!-- Preview / Features Card -->
                <div class="irtkaz-settings-card" style="background: #091524; color: #f8fafc; border-color: #1e293b; margin-bottom: 0;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <span class="dashicons dashicons-yes-alt" style="color: #22c55e; font-size: 24px; width: 24px; height: 24px;"></span>
                        <h3 style="color: #ffffff; margin: 0; font-size: 18px;"><?php esc_html_e('What is Included (100% Free):', 'irtkaz-crm'); ?></h3>
                    </div>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px; font-size: 13.5px; color: #cbd5e1;">
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Hero Section:</strong> Qatari Diar XL Logo, 9M EGP Badge, 10 Yrs Installments, Feature Pills.</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Frosted Glass Lead Form:</strong> Name + International Phone Selector (+20, +966, +971...).</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Inventory & Pricing Cards:</strong> Apartments & Jade Hills Villas with itemized price tables & WhatsApp CTA.</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Amenities:</strong> 9-Card Compact Grid (Golf, Clubhouse, Ring Park, Sports, Hotels, etc.).</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Strategic Location & Developer:</strong> Distance markers + Qatari Diar narrative.</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Why Advisory Section:</strong> 3 Value Pillars for the agency.</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Floating WhatsApp:</strong> Pulse green button with prefilled lead inquiry.</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #38bdf8;">✔</span>
                            <span><strong>Leads Dashboard:</strong> Real-time capture in WP Admin with 1-click WhatsApp dial!</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}
