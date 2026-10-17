<?php
if (!defined('ABSPATH')) {
    exit;
}

class Irtkaz_Elementor_Integration {
    public static function init() {
        add_action('elementor/elements/categories_registered', [__CLASS__, 'register_category']);
        add_action('elementor/widgets/register', [__CLASS__, 'register_widgets']);
    }

    public static function register_category($elements_manager) {
        $elements_manager->add_category(
            'irtkaz-real-estate',
            [
                'title' => __('Irtkaz Real Estate', 'irtkaz-crm'),
                'icon'  => 'fa fa-building',
            ]
        );
    }

    public static function register_widgets($widgets_manager) {
        if (!class_exists('\Elementor\Widget_Base')) {
            return;
        }

        require_once __DIR__ . '/widgets/widget-lead-form.php';
        $widgets_manager->register(new \Irtkaz_Lead_Form_Widget());
    }
}
