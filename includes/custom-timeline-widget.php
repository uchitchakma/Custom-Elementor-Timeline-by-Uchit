<?php

if (!defined('ABSPATH')) {
    exit;
}

class CustomTimelineWidget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom-timeline';
    }

    public function get_title() {
        return __('Timeline by Uchit', 'custom-timeline-by-uchit');
    }

    public function get_icon() {
        return 'eicon-time-line';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['timeline', 'history', 'process', 'custom', 'container'];
    }

    public function get_script_depends() {
        return ['custom-timeline-widget'];
    }

    public function get_style_depends() {
        return ['custom-timeline-widget'];
    }

    public function show_in_panel() {
        return true;
    }

    protected function register_controls() {
        
        // Timeline Items Section
        $this->start_controls_section(
            'timeline_items_section',
            [
                'label' => __('Timeline Items', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'item_title',
            [
                'label' => __('Item Title', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Timeline Item', 'custom-timeline-by-uchit'),
                'label_block' => true,
            ]
        );

        // Left Container Template ID
        $repeater->add_control(
            'left_template_id',
            [
                'label' => __('Left Container Template', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_page_templates(),
                'label_block' => true,
            ]
        );

        // Right Container Template ID
        $repeater->add_control(
            'right_template_id',
            [
                'label' => __('Right Container Template', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_page_templates(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'timeline_items',
            [
                'label' => __('Timeline Items', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_title' => __('Timeline Item 1', 'custom-timeline-by-uchit'),
                    ],
                    [
                        'item_title' => __('Timeline Item 2', 'custom-timeline-by-uchit'),
                    ],
                    [
                        'item_title' => __('Timeline Item 3', 'custom-timeline-by-uchit'),
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );

        $this->add_control(
            'note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('<div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin-top: 10px;"><strong>💡 How to use:</strong><br>1. Create Elementor templates for your content<br>2. Assign templates to Left/Right containers<br>3. Or edit directly in Navigator panel</div>', 'custom-timeline-by-uchit'),
            ]
        );

        $this->end_controls_section();

        // Timeline Line Style
        $this->start_controls_section(
            'timeline_line_style',
            [
                'label' => __('Timeline Line', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'line_width',
            [
                'label' => __('Line Width', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-line' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'line_color',
            [
                'label' => __('Line Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .timeline-line' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-mobile .timeline-line' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'active_line_color',
            [
                'label' => __('Active Line Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .timeline-line-progress' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-mobile .timeline-line-progress' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->end_controls_section();

        // Timeline Moving Marker
        $this->start_controls_section(
            'timeline_moving_marker_style',
            [
                'label' => __('Moving Timeline Marker', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'marker_type',
            [
                'label' => __('Marker Type', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'dot',
                'options' => [
                    'dot' => __('Dot/Circle', 'custom-timeline-by-uchit'),
                    'icon' => __('Icon', 'custom-timeline-by-uchit'),
                ],
            ]
        );

        $this->add_control(
            'marker_icon',
            [
                'label' => __('Choose Icon', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'marker_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'moving_marker_size',
            [
                'label' => __('Marker Size', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-moving-marker' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .timeline-moving-marker i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .timeline-moving-marker svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'moving_marker_color',
            [
                'label' => __('Marker Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .timeline-moving-marker' => 'background-color: {{VALUE}} !important; color: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-moving-marker i' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-moving-marker svg' => 'fill: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-mobile .timeline-moving-marker' => 'background-color: {{VALUE}} !important; color: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-mobile .timeline-moving-marker i' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-mobile .timeline-moving-marker svg' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'moving_marker_bg_color',
            [
                'label' => __('Background Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .timeline-moving-marker[data-marker-type="icon"]' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .timeline-mobile .timeline-moving-marker[data-marker-type="icon"]' => 'background-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'marker_type' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'moving_marker_border_width',
            [
                'label' => __('Border Width', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-moving-marker' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
            ]
        );

        $this->add_control(
            'moving_marker_border_color',
            [
                'label' => __('Border Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .timeline-moving-marker' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'moving_marker_box_shadow',
                'label' => __('Box Shadow', 'custom-timeline-by-uchit'),
                'selector' => '{{WRAPPER}} .timeline-moving-marker',
            ]
        );

       $this->add_control(
            'moving_marker_animation',
            [
                'label' => __('Pulse Animation', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-timeline-by-uchit'),
                'label_off' => __('No', 'custom-timeline-by-uchit'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'marker_position_heading',
            [
                'label' => __('Position & Rotation', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'marker_horizontal_offset',
            [
                'label' => __('Horizontal Offset', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-desktop .timeline-moving-marker' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'description' => __('Move marker left (negative) or right (positive)', 'custom-timeline-by-uchit'),
            ]
        );

        $this->add_responsive_control(
            'marker_vertical_offset',
            [
                'label' => __('Vertical Offset', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-desktop .timeline-moving-marker' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'description' => __('Move marker up (negative) or down (positive)', 'custom-timeline-by-uchit'),
            ]
        );

        $this->add_responsive_control(
            'marker_rotation',
            [
                'label' => __('Rotation', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-desktop .timeline-moving-marker' => 'transform: translateX(-50%) rotate({{SIZE}}{{UNIT}}) !important;',
                    '{{WRAPPER}} .timeline-mobile .timeline-moving-marker' => 'transform: translateX(-50%) rotate({{SIZE}}{{UNIT}}) !important;',
                ],
                'description' => __('Rotate the marker (useful for custom icons)', 'custom-timeline-by-uchit'),
            ]
        );

        // Mobile specific position controls
        $this->add_control(
            'mobile_marker_position_heading',
            [
                'label' => __('Mobile Position', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'mobile_marker_horizontal_offset',
            [
                'label' => __('Mobile Horizontal Offset', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-mobile .timeline-moving-marker' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'description' => __('Adjust horizontal position on mobile', 'custom-timeline-by-uchit'),
            ]
        );

        $this->add_responsive_control(
            'mobile_marker_vertical_offset',
            [
                'label' => __('Mobile Vertical Offset', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -50,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-mobile .timeline-moving-marker' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'description' => __('Adjust vertical position on mobile', 'custom-timeline-by-uchit'),
            ]
        );

        $this->end_controls_section();


        // Container Style
        $this->start_controls_section(
            'container_style',
            [
                'label' => __('Container Style', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => __('Background', 'custom-timeline-by-uchit'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .timeline-inner-container',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => __('Border', 'custom-timeline-by-uchit'),
                'selector' => '{{WRAPPER}} .timeline-inner-container',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .timeline-inner-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Box Shadow', 'custom-timeline-by-uchit'),
                'selector' => '{{WRAPPER}} .timeline-inner-container',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Container Padding', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-inner-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Container Spacing
        $this->start_controls_section(
            'container_spacing_style',
            [
                'label' => __('Container Spacing', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => __('Items Gap', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_gap',
            [
                'label' => __('Left/Right Container Gap', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-desktop .timeline-content-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .timeline-desktop .timeline-content-right' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'mobile_container_gap',
            [
                'label' => __('Mobile Container Gap', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-mobile .timeline-content-left' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .timeline-mobile .timeline-content-right' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Mobile Settings
        $this->start_controls_section(
            'mobile_settings',
            [
                'label' => __('Mobile Settings', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mobile_breakpoint',
            [
                'label' => __('Mobile Breakpoint', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 320,
                        'max' => 1024,
                    ],
                ],
                'default' => [
                    'size' => 768,
                ],
                'description' => __('Screen width below which the mobile layout will be activated', 'custom-timeline-by-uchit'),
            ]
        );

        $this->add_responsive_control(
            'mobile_line_left_position',
            [
                'label' => __('Mobile Line Position', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-mobile .timeline-line-wrapper' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Animation Settings
        $this->start_controls_section(
            'animation_settings',
            [
                'label' => __('Animation', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_animation',
            [
                'label' => __('Enable Animation', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'custom-timeline-by-uchit'),
                'label_off' => __('No', 'custom-timeline-by-uchit'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function get_page_templates() {
        $templates = \Elementor\Plugin::$instance->templates_manager->get_source('local')->get_items();
        $options = ['' => __('-- Select Template --', 'custom-timeline-by-uchit')];
        
        foreach ($templates as $template) {
            $options[$template['template_id']] = $template['title'];
        }
        
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $timeline_items = $settings['timeline_items'];
        $widget_id = $this->get_id();
        $mobile_breakpoint = $settings['mobile_breakpoint']['size'] ?? 768;
        $marker_type = $settings['marker_type'] ?? 'dot';
        $marker_icon = $settings['marker_icon'] ?? [];
        $marker_animation = $settings['moving_marker_animation'] ?? 'yes';

        ?>
        <div class="custom-timeline-wrapper" data-widget-id="<?php echo esc_attr($widget_id); ?>" data-breakpoint="<?php echo esc_attr($mobile_breakpoint); ?>">
            
            <!-- Desktop Timeline -->
            <div class="timeline-container timeline-desktop">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                        
                        <!-- Moving Marker -->
                        <div class="timeline-moving-marker <?php echo $marker_animation === 'yes' ? 'with-pulse' : ''; ?>" data-marker-type="<?php echo esc_attr($marker_type); ?>">
                            <?php if ($marker_type === 'icon' && !empty($marker_icon['value'])) : ?>
                                <?php \Elementor\Icons_Manager::render_icon($marker_icon, ['aria-hidden' => 'true']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php foreach ($timeline_items as $index => $item) : ?>
                    <div class="timeline-item" data-item-index="<?php echo esc_attr($index); ?>">
                        <div class="timeline-content-left">
                            <div class="timeline-inner-container">
                                <?php 
                                if (!empty($item['left_template_id'])) {
                                    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($item['left_template_id']);
                                } else {
                                    echo '<p class="timeline-placeholder">Select a template or add content directly</p>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="timeline-center">
                            <!-- Empty center for spacing -->
                        </div>

                        <div class="timeline-content-right">
                            <div class="timeline-inner-container">
                                <?php 
                                if (!empty($item['right_template_id'])) {
                                    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($item['right_template_id']);
                                } else {
                                    echo '<p class="timeline-placeholder">Select a template or add content directly</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Timeline -->
            <div class="timeline-container timeline-mobile">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                        
                        <!-- Moving Marker -->
                        <div class="timeline-moving-marker <?php echo $marker_animation === 'yes' ? 'with-pulse' : ''; ?>" data-marker-type="<?php echo esc_attr($marker_type); ?>">
                            <?php if ($marker_type === 'icon' && !empty($marker_icon['value'])) : ?>
                                <?php \Elementor\Icons_Manager::render_icon($marker_icon, ['aria-hidden' => 'true']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php foreach ($timeline_items as $index => $item) : ?>
                    <div class="timeline-item" data-item-index="<?php echo esc_attr($index); ?>">
                        <div class="timeline-content-wrapper">
                            <div class="timeline-content-left">
                                <div class="timeline-inner-container">
                                    <?php 
                                    if (!empty($item['left_template_id'])) {
                                        echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($item['left_template_id']);
                                    } else {
                                        echo '<p class="timeline-placeholder">Select a template</p>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="timeline-content-right">
                                <div class="timeline-inner-container">
                                    <?php 
                                    if (!empty($item['right_template_id'])) {
                                        echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($item['right_template_id']);
                                    } else {
                                        echo '<p class="timeline-placeholder">Select a template</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var widgetId = view.getID();
        var mobileBreakpoint = settings.mobile_breakpoint.size || 768;
        var markerType = settings.marker_type || 'dot';
        var markerAnimation = settings.moving_marker_animation || 'yes';
        #>
        
        <div class="custom-timeline-wrapper" data-widget-id="{{ widgetId }}" data-breakpoint="{{ mobileBreakpoint }}">
            
            <!-- Desktop Timeline -->
            <div class="timeline-container timeline-desktop">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                        
                        <!-- Moving Marker Preview -->
                        <div class="timeline-moving-marker {{ markerAnimation === 'yes' ? 'with-pulse' : '' }}" data-marker-type="{{ markerType }}" style="top: 0;">
                            <# if (markerType === 'icon' && settings.marker_icon && settings.marker_icon.value) { #>
                                <i class="{{ settings.marker_icon.value }}"></i>
                            <# } #>
                        </div>
                    </div>
                </div>

                <# _.each( settings.timeline_items, function( item, index ) { #>
                    <div class="timeline-item" data-item-index="{{ index }}">
                        <div class="timeline-content-left">
                            <div class="timeline-inner-container">
                                <# if (item.left_template_id && item.left_template_id !== '') { #>
                                    <div class="timeline-placeholder-editor" style="padding: 40px; text-align: center; background: #f8f9fa; border: 2px dashed #ddd; border-radius: 8px;">
                                        <i class="eicon-document-file" style="font-size: 32px; color: #3b82f6; display: block; margin-bottom: 10px;"></i>
                                        <p style="margin: 0; color: #555; font-size: 14px; font-weight: 600;">Template Selected</p>
                                        <p style="margin: 5px 0 0; color: #999; font-size: 12px;">Preview available on frontend</p>
                                    </div>
                                <# } else { #>
                                    <div class="timeline-placeholder-editor" style="padding: 40px; text-align: center; background: #f8f9fa; border: 2px dashed #ddd; border-radius: 8px;">
                                        <i class="eicon-drag-n-drop" style="font-size: 32px; color: #ccc; display: block; margin-bottom: 10px;"></i>
                                        <p style="margin: 0; color: #999; font-size: 14px;">Left Container {{ index + 1 }}</p>
                                        <p style="margin: 5px 0 0; color: #bbb; font-size: 12px;">Select a template to begin</p>
                                    </div>
                                <# } #>
                            </div>
                        </div>

                        <div class="timeline-center">
                            <!-- Empty center -->
                        </div>

                        <div class="timeline-content-right">
                            <div class="timeline-inner-container">
                                <# if (item.right_template_id && item.right_template_id !== '') { #>
                                    <div class="timeline-placeholder-editor" style="padding: 40px; text-align: center; background: #f8f9fa; border: 2px dashed #ddd; border-radius: 8px;">
                                        <i class="eicon-document-file" style="font-size: 32px; color: #3b82f6; display: block; margin-bottom: 10px;"></i>
                                        <p style="margin: 0; color: #555; font-size: 14px; font-weight: 600;">Template Selected</p>
                                        <p style="margin: 5px 0 0; color: #999; font-size: 12px;">Preview available on frontend</p>
                                    </div>
                                <# } else { #>
                                    <div class="timeline-placeholder-editor" style="padding: 40px; text-align: center; background: #f8f9fa; border: 2px dashed #ddd; border-radius: 8px;">
                                        <i class="eicon-drag-n-drop" style="font-size: 32px; color: #ccc; display: block; margin-bottom: 10px;"></i>
                                        <p style="margin: 0; color: #999; font-size: 14px;">Right Container {{ index + 1 }}</p>
                                        <p style="margin: 5px 0 0; color: #bbb; font-size: 12px;">Select a template to begin</p>
                                    </div>
                                <# } #>
                            </div>
                        </div>
                    </div>
                <# }); #>
            </div>

            <!-- Mobile Timeline -->
            <div class="timeline-container timeline-mobile" style="display: none;">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                        <div class="timeline-moving-marker {{ markerAnimation === 'yes' ? 'with-pulse' : '' }}" data-marker-type="{{ markerType }}">
                            <# if (markerType === 'icon' && settings.marker_icon && settings.marker_icon.value) { #>
                                <i class="{{ settings.marker_icon.value }}"></i>
                            <# } #>
                        </div>
                    </div>
                </div>

                <# _.each( settings.timeline_items, function( item, index ) { #>
                    <div class="timeline-item" data-item-index="{{ index }}">
                        <div class="timeline-content-wrapper">
                            <div class="timeline-content-left">
                                <div class="timeline-inner-container">
                                    <p class="timeline-placeholder">Left Container (Mobile)</p>
                                </div>
                            </div>

                            <div class="timeline-content-right">
                                <div class="timeline-inner-container">
                                    <p class="timeline-placeholder">Right Container (Mobile)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <# }); #>
            </div>
        </div>
        <?php
    }
}