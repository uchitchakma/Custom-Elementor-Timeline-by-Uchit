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
        return ['timeline', 'history', 'process', 'custom'];
    }

    public function get_script_depends() {
        return ['custom-timeline-widget'];
    }

    public function get_style_depends() {
        return ['custom-timeline-widget'];
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
            'item_id',
            [
                'label' => __('Item ID', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Unique identifier for this timeline item (auto-generated)', 'custom-timeline-by-uchit'),
            ]
        );

        $this->add_control(
            'timeline_items',
            [
                'label' => __('Timeline Items', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['item_id' => ''],
                    ['item_id' => ''],
                    ['item_id' => ''],
                ],
                'title_field' => 'Timeline Item #{{{ _id }}}',
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
                    '{{WRAPPER}} .timeline-line' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .timeline-line-progress' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Timeline Dot Style
        $this->start_controls_section(
            'timeline_dot_style',
            [
                'label' => __('Timeline Dot', 'custom-timeline-by-uchit'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'dot_size',
            [
                'label' => __('Dot Size', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .timeline-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => __('Dot Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .timeline-dot' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'active_dot_color',
            [
                'label' => __('Active Dot Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3b82f6',
                'selectors' => [
                    '{{WRAPPER}} .timeline-dot.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dot_border_width',
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
                    '{{WRAPPER}} .timeline-dot' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'dot_border_color',
            [
                'label' => __('Border Color', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .timeline-dot' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dot_box_shadow',
                'label' => __('Box Shadow', 'custom-timeline-by-uchit'),
                'selector' => '{{WRAPPER}} .timeline-dot',
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
                    '{{WRAPPER}} .timeline-content-left' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .timeline-content-right' => 'padding-left: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .timeline-mobile .timeline-line' => 'left: {{SIZE}}{{UNIT}};',
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

        $this->add_control(
            'animation_duration',
            [
                'label' => __('Animation Duration (ms)', 'custom-timeline-by-uchit'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'default' => [
                    'size' => 300,
                ],
                'condition' => [
                    'enable_animation' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $timeline_items = $settings['timeline_items'];
        $widget_id = $this->get_id();
        $mobile_breakpoint = $settings['mobile_breakpoint']['size'] ?? 768;

        ?>
        <div class="custom-timeline-wrapper" data-widget-id="<?php echo esc_attr($widget_id); ?>" data-breakpoint="<?php echo esc_attr($mobile_breakpoint); ?>">
            
            <!-- Desktop Timeline -->
            <div class="timeline-container timeline-desktop">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                    </div>
                </div>

                <?php foreach ($timeline_items as $index => $item) : 
                    $item_id = !empty($item['item_id']) ? $item['item_id'] : 'item-' . $index;
                ?>
                    <div class="timeline-item" data-item-index="<?php echo esc_attr($index); ?>">
                        <div class="timeline-content-left">
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($this->get_id() . '-left-' . $index, true); ?>
                        </div>

                        <div class="timeline-center">
                            <div class="timeline-dot" data-index="<?php echo esc_attr($index); ?>"></div>
                        </div>

                        <div class="timeline-content-right">
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($this->get_id() . '-right-' . $index, true); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Timeline -->
            <div class="timeline-container timeline-mobile">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                    </div>
                </div>

                <?php foreach ($timeline_items as $index => $item) : 
                    $item_id = !empty($item['item_id']) ? $item['item_id'] : 'item-' . $index;
                ?>
                    <div class="timeline-item" data-item-index="<?php echo esc_attr($index); ?>">
                        <div class="timeline-dot" data-index="<?php echo esc_attr($index); ?>"></div>
                        
                        <div class="timeline-content-left">
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($this->get_id() . '-left-' . $index, true); ?>
                        </div>

                        <div class="timeline-content-right">
                            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($this->get_id() . '-right-' . $index, true); ?>
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
        #>
        <div class="custom-timeline-wrapper" data-widget-id="{{ widgetId }}" data-breakpoint="{{ mobileBreakpoint }}">
            
            <!-- Desktop Timeline -->
            <div class="timeline-container timeline-desktop">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                    </div>
                </div>

                <# _.each( settings.timeline_items, function( item, index ) { #>
                    <div class="timeline-item" data-item-index="{{ index }}">
                        <div class="timeline-content-left">
                            <div class="elementor-container">
                                <p style="padding: 20px; background: #f3f4f6; border-radius: 8px; text-align: center;">
                                    Left Container {{{ index + 1 }}}
                                    <br><small>Add your content here</small>
                                </p>
                            </div>
                        </div>

                        <div class="timeline-center">
                            <div class="timeline-dot" data-index="{{ index }}"></div>
                        </div>

                        <div class="timeline-content-right">
                            <div class="elementor-container">
                                <p style="padding: 20px; background: #f3f4f6; border-radius: 8px; text-align: center;">
                                    Right Container {{{ index + 1 }}}
                                    <br><small>Add your content here</small>
                                </p>
                            </div>
                        </div>
                    </div>
                <# }); #>
            </div>

            <!-- Mobile Timeline -->
            <div class="timeline-container timeline-mobile">
                <div class="timeline-line-wrapper">
                    <div class="timeline-line">
                        <div class="timeline-line-progress"></div>
                    </div>
                </div>

                <# _.each( settings.timeline_items, function( item, index ) { #>
                    <div class="timeline-item" data-item-index="{{ index }}">
                        <div class="timeline-dot" data-index="{{ index }}"></div>
                        
                        <div class="timeline-content-left">
                            <div class="elementor-container">
                                <p style="padding: 20px; background: #f3f4f6; border-radius: 8px; margin-bottom: 15px;">
                                    Left Container {{{ index + 1 }}}
                                </p>
                            </div>
                        </div>

                        <div class="timeline-content-right">
                            <div class="elementor-container">
                                <p style="padding: 20px; background: #f3f4f6; border-radius: 8px;">
                                    Right Container {{{ index + 1 }}}
                                </p>
                            </div>
                        </div>
                    </div>
                <# }); #>
            </div>
        </div>
        <?php
    }
}