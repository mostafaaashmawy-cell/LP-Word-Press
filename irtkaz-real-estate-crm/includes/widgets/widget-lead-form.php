<?php
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Irtkaz_Lead_Form_Widget extends Widget_Base {
    public function get_name() {
        return 'irtkaz_lead_form';
    }

    public function get_title() {
        return __('Real Estate Lead Form', 'irtkaz-crm');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['irtkaz-real-estate', 'general'];
    }

    public function get_keywords() {
        return ['lead', 'form', 'real estate', 'inquiry', 'city gate', 'contact'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Form Content', 'irtkaz-crm'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'project',
            [
                'label'       => __('Project Name', 'irtkaz-crm'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'City Gate New Cairo',
                'placeholder' => __('Enter project name', 'irtkaz-crm'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => __('Form Heading', 'irtkaz-crm'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'REGISTER YOUR INTEREST',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'       => __('Form Subtitle', 'irtkaz-crm'),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 2,
                'default'     => 'Receive comprehensive master plans, pricing, and availability',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'agency',
            [
                'label'   => __('Agency Name', 'irtkaz-crm'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'Irtkaz',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => __('Submit Button Text', 'irtkaz-crm'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'SUBMIT',
            ]
        );

        $this->add_control(
            'privacy_url',
            [
                'label'       => __('Privacy Policy URL', 'irtkaz-crm'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://...',
                'default'     => ['url' => '#'],
            ]
        );

        $this->add_control(
            'terms_url',
            [
                'label'       => __('Terms & Conditions URL', 'irtkaz-crm'),
                'type'        => Controls_Manager::URL,
                'placeholder' => 'https://...',
                'default'     => ['url' => '#'],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style_form',
            [
                'label' => __('Form Container Style', 'irtkaz-crm'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label'     => __('Background Color', 'irtkaz-crm'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.96)',
                'selectors' => [
                    '{{WRAPPER}} .lead-form-wrapper' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .lead-form-wrapper',
            ]
        );

        $this->end_controls_section();

        // Button Style Section
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Submit Button Style', 'irtkaz-crm'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label'     => __('Button Background', 'irtkaz-crm'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#091524',
                'selectors' => [
                    '{{WRAPPER}} .btn-form-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label'     => __('Button Text Color', 'irtkaz-crm'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .btn-form-submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $privacy_url = !empty($settings['privacy_url']['url']) ? $settings['privacy_url']['url'] : '#';
        $terms_url   = !empty($settings['terms_url']['url']) ? $settings['terms_url']['url'] : '#';

        echo irtkaz_lead_form_shortcode([
            'project'     => $settings['project'],
            'title'       => $settings['title'],
            'subtitle'    => $settings['subtitle'],
            'agency'      => $settings['agency'],
            'privacy_url' => $privacy_url,
            'terms_url'   => $terms_url,
        ]);
    }
}
