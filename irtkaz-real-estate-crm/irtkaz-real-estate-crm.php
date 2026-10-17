<?php
/**
 * Plugin Name: Irtkaz Real Estate CRM & Landing Suite
 * Plugin URI: https://irtkaz.com
 * Description: Ultra-luxurious real estate lead capture system, custom Elementor Free widget, and dedicated Leads CRM Dashboard. 100% Free with zero paid plugins required.
 * Version: 1.0.0
 * Author: Mostafa Ashmawy / Irtkaz
 * Author URI: https://irtkaz.com
 * Text Domain: irtkaz-crm
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('IRTKAZ_CRM_VERSION', '1.0.0');
define('IRTKAZ_CRM_PATH', plugin_dir_path(__FILE__));
define('IRTKAZ_CRM_URL', plugin_dir_url(__FILE__));
define('IRTKAZ_CRM_BASENAME', plugin_basename(__FILE__));

// Require Core Classes
require_once IRTKAZ_CRM_PATH . 'includes/class-database.php';
require_once IRTKAZ_CRM_PATH . 'includes/class-ajax-handler.php';
require_once IRTKAZ_CRM_PATH . 'includes/class-admin-dashboard.php';
require_once IRTKAZ_CRM_PATH . 'includes/class-elementor-widget.php';
require_once IRTKAZ_CRM_PATH . 'includes/class-page-generator.php';

// Activation & Deactivation
register_activation_hook(__FILE__, ['Irtkaz_Database', 'create_tables']);

/**
 * Initialize Plugin Components
 */
function irtkaz_crm_init() {
    Irtkaz_Database::init();
    Irtkaz_Ajax_Handler::init();
    Irtkaz_Admin_Dashboard::init();
    Irtkaz_Elementor_Integration::init();
    Irtkaz_Page_Generator::init();
}
add_action('plugins_loaded', 'irtkaz_crm_init');

/**
 * Enqueue Frontend Scripts & Styles
 */
function irtkaz_crm_enqueue_frontend_scripts() {
    // Google Fonts for Luxury Editorial Styling
    wp_enqueue_style(
        'irtkaz-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Cormorant+Garamond:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap',
        [],
        null
    );

    // Landing Page Styles
    wp_enqueue_style(
        'irtkaz-landing-style',
        IRTKAZ_CRM_URL . 'assets/css/landing-style.css',
        [],
        IRTKAZ_CRM_VERSION
    );

    // Lead Form JavaScript
    wp_enqueue_script(
        'irtkaz-lead-form',
        IRTKAZ_CRM_URL . 'assets/js/lead-form.js',
        ['jquery'],
        IRTKAZ_CRM_VERSION,
        true
    );

    wp_localize_script('irtkaz-lead-form', 'irtkaz_ajax', [
        'ajax_url'   => admin_url('admin-ajax.php'),
        'nonce'      => wp_create_nonce('irtkaz_lead_nonce'),
        'default_project' => get_option('irtkaz_default_project', 'City Gate New Cairo'),
        'strings'    => [
            'submitting' => __('Submitting...', 'irtkaz-crm'),
            'submit'     => __('SUBMIT', 'irtkaz-crm'),
            'error_name' => __('Please enter your full name.', 'irtkaz-crm'),
            'error_phone'=> __('Please enter your phone number.', 'irtkaz-crm'),
            'error_gen'  => __('Something went wrong. Please try again.', 'irtkaz-crm'),
        ]
    ]);
}
add_action('wp_enqueue_scripts', 'irtkaz_crm_enqueue_frontend_scripts');

/**
 * Register Shortcode for Lead Capture Form
 * Usage: [irtkaz_lead_form project="City Gate New Cairo"]
 */
function irtkaz_lead_form_shortcode($atts) {
    $atts = shortcode_atts([
        'project'  => get_option('irtkaz_default_project', 'City Gate New Cairo'),
        'title'    => 'REGISTER YOUR INTEREST',
        'subtitle' => 'Receive comprehensive master plans, pricing, and availability',
        'agency'   => get_option('irtkaz_agency_name', 'Irtkaz'),
        'privacy_url' => get_option('irtkaz_privacy_url', '#'),
        'terms_url'   => get_option('irtkaz_terms_url', '#'),
    ], $atts, 'irtkaz_lead_form');

    ob_start();
    ?>
    <div class="lead-form-wrapper" id="register">
        <div class="form-header">
            <h2 class="form-title"><?php echo esc_html($atts['title']); ?></h2>
            <p class="form-subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
        </div>

        <form class="lead-form irtkaz-ajax-lead-form" id="leadCaptureForm" data-project="<?php echo esc_attr($atts['project']); ?>" data-agency="<?php echo esc_attr($atts['agency']); ?>">
            <input type="hidden" name="project" value="<?php echo esc_attr($atts['project']); ?>">
            <input type="hidden" name="agency" value="<?php echo esc_attr($atts['agency']); ?>">

            <!-- Full Name Input -->
            <div class="form-group">
                <label for="fullName" class="form-label"><?php esc_html_e('Full Name', 'irtkaz-crm'); ?></label>
                <div class="form-input-box">
                    <input type="text" id="fullName" name="fullName" class="form-input" placeholder="<?php esc_attr_e('Your Full Name', 'irtkaz-crm'); ?>" required>
                </div>
            </div>

            <!-- Phone Number Input with Country Code Selector -->
            <div class="form-group">
                <label for="phoneNumber" class="form-label"><?php esc_html_e('Phone Number', 'irtkaz-crm'); ?></label>
                <div class="phone-input-row">
                    <select id="countryCode" name="countryCode" class="country-code-select" aria-label="Country Code">
                        <option value="+20" selected>🇪🇬 +20 (Egypt)</option>
                        <option value="+966">🇸🇦 +966 (KSA)</option>
                        <option value="+971">🇦🇪 +971 (UAE)</option>
                        <option value="+974">🇶🇦 +974 (Qatar)</option>
                        <option value="+965">🇰🇼 +965 (Kuwait)</option>
                        <option value="+973">🇧🇭 +973 (Bahrain)</option>
                        <option value="+968">🇴🇲 +968 (Oman)</option>
                        <option value="+44">🇬🇧 +44 (UK)</option>
                        <option value="+1">🇺🇸 +1 (USA)</option>
                        <option value="+1">🇨🇦 +1 (Canada)</option>
                        <option value="+49">🇩🇪 +49 (Germany)</option>
                        <option value="+33">🇫🇷 +33 (France)</option>
                        <option value="+39">🇮🇹 +39 (Italy)</option>
                        <option value="+962">🇯🇴 +962 (Jordan)</option>
                        <option value="+961">🇱🇧 +961 (Lebanon)</option>
                    </select>
                    <input type="tel" id="phoneNumber" name="phoneNumber" class="form-input" placeholder="<?php esc_attr_e('Phone Number', 'irtkaz-crm'); ?>" required>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn" class="btn-form-submit">
                <?php esc_html_e('SUBMIT', 'irtkaz-crm'); ?>
            </button>
        </form>

        <!-- Inline Success State -->
        <div id="formSuccessState" class="form-success-state" style="display: none;">
            <div class="success-box-inner">
                <h3 class="success-box-title"><?php esc_html_e('Thank You!', 'irtkaz-crm'); ?></h3>
                <p class="success-box-desc"><?php esc_html_e('Your details have been received. We will be in touch shortly.', 'irtkaz-crm'); ?></p>
            </div>
        </div>

        <!-- Legal Disclaimer Notice -->
        <div class="form-legal-notice">
            <?php 
            printf(
                esc_html__('By submitting, you agree to receive communications from %s regarding real estate projects. This landing page is operated by %s, an independent real estate consultancy, and is not the official website of the developer. Prices and availability are subject to change.', 'irtkaz-crm'),
                esc_html($atts['agency']),
                esc_html($atts['agency'])
            );
            ?>
        </div>

        <!-- Privacy & Terms Links -->
        <div class="accessibility-links-a">
            <a href="<?php echo esc_url($atts['privacy_url']); ?>" class="legal-routed-link"><?php esc_html_e('Privacy Policy', 'irtkaz-crm'); ?></a>
            <span class="accessibility-separator">•</span>
            <a href="<?php echo esc_url($atts['terms_url']); ?>" class="legal-routed-link"><?php esc_html_e('Terms & Conditions', 'irtkaz-crm'); ?></a>
        </div>
    </div>

    <!-- Success Modal Popup -->
    <div class="modal-backdrop" id="successModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal-box">
            <div class="modal-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h3 class="modal-title" id="modalTitle"><?php esc_html_e('Inquiry Received', 'irtkaz-crm'); ?></h3>
            <p class="modal-text">
                <?php esc_html_e('Thank you', 'irtkaz-crm'); ?> <strong id="modalLeadName" style="color: #0f172a;"></strong>! <?php printf(esc_html__('Your registration has been securely dispatched to our senior consultancy team at %s. A property advisor will contact you shortly with full brochures and availability.', 'irtkaz-crm'), esc_html($atts['agency'])); ?>
            </p>
            <div class="modal-actions">
                <button type="button" class="btn-modal-close" id="modalCloseBtn"><?php esc_html_e('Close Window', 'irtkaz-crm'); ?></button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('irtkaz_lead_form', 'irtkaz_lead_form_shortcode');

/**
 * Register Shortcode for Floating WhatsApp Button
 * Usage: [irtkaz_whatsapp phone="+201033373331" message="Hello..."]
 */
function irtkaz_whatsapp_button_shortcode($atts) {
    $default_phone = get_option('irtkaz_whatsapp_number', '+201033373331');
    $atts = shortcode_atts([
        'phone'   => $default_phone,
        'message' => 'Hello Irtkaz, I am inquiring about City Gate New Cairo by Qatari Diar. Please provide me with master plans, pricing, and availability.',
    ], $atts, 'irtkaz_whatsapp');

    $clean_phone = preg_replace('/[^0-9]/', '', $atts['phone']);
    $url = 'https://wa.me/' . $clean_phone . '?text=' . rawurlencode($atts['message']);

    ob_start();
    ?>
    <a href="<?php echo esc_url($url); ?>" class="floating-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Direct WhatsApp Contact">
        <svg viewBox="0 0 448 512" aria-hidden="true" width="28" height="28" fill="#ffffff">
            <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
        </svg>
    </a>
    <?php
    return ob_get_clean();
}
add_shortcode('irtkaz_whatsapp', 'irtkaz_whatsapp_button_shortcode');
