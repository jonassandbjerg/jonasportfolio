<?php

namespace WTS_EAE\Modules\Charts\Widgets;

use Elementor\Controls_Manager;
use WTS_EAE\Base\EAE_Widget_Base;
use WTS_EAE\Modules\Charts\Skins;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

class Charts extends EAE_Widget_Base {
    public function get_name() {
        return 'eae-charts';
    }

    public function get_title() {

        return __('EAE-Charts', 'wts-eae');

    }

    public function get_icon() {
        return 'eae-icons eae-compare-table';
    }

    public function get_categories() {
        return [ 'wts-eae' ];
    }

    protected function _register_skins(){
	    $this->add_skin( new Skins\Skin_Bar_Chart( $this ) );
        $this->add_skin( new Skins\Skin_horizontal_Bar_Chart( $this ) );
        $this->add_skin( new Skins\Skin_Line_Chart( $this ) );
        $this->add_skin( new Skins\Skin_Pie_Chart( $this ) );
        $this->add_skin( new Skins\Skin_Doughnut_Chart( $this ) );
        $this->add_skin( new Skins\Skin_Polar_Area_Chart( $this ) );
        $this->add_skin( new Skins\Skin_Radar_Chart( $this ) );
//        $this->add_skin( new Skins\Skin_Bubble_Chart( $this ) );

    }

    protected $_has_template_content = false;

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'wts-eae')
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_x_axis',
            [
                'label' => __('X - Axis', 'wts-eae')
            ]
        );

        $this->add_control(
            'x_axis_title',
            [
                'label' => __('X-Axis', 'wts-eae'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'x_axis_show_title',
            [
                'label'        => __('Enable X-Axis Title', 'wts-eae'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wts-eae' ),
                'label_off'    => __( 'No', 'wts-eae' ),
                'return_value' => 'true',
                'default'      => '',
            ]
        );

        $this->add_control(
            'x_axis_label',
            [
                'label' => __('Title', 'wts-eae'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter Title', 'wts-eae'),
                'condition' => [
                    'x_axis_show_title' => 'true'
                ]
            ]
        );

        $this->add_control(
            'data_label',
            [
                'label' => __('Data Labels', 'wts-eae'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Jan, Feb, Mar, Apr',
                'placeholder' => __("January, February, March", 'wts-eae'),
                'description' => __("Enter X-Axis Data Labels Separated With ' , ' "),
            ]
        );

        $this->add_control(
            'x_axis_grid_line',
            [
                'label'        => __('Enable Grid Lines', 'wts-eae'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wts-eae' ),
                'label_off'    => __( 'No', 'wts-eae' ),
                'return_value' => 'true',
                'default'      => '',
                'description' => __(" Enable or Disable X-Axis Grid Lines "),
            ]
        );


        $this->add_control('label_rotation',
            [
                'label'			=> __( 'Label\'s Rotation ', 'wts-eae' ),
                'type'			=> Controls_Manager::NUMBER,
                'min'           => 0,
                'max'           => 360,
                'default'       => 0,
                'condition' => [
                    '_skin' => 'bar'
                ]
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_y_axis',
            [
                'label' => __('Y - Axis', 'wts-eae')
            ]
        );

        $this->add_control(
            'y_axis_show_title',
            [
                'label' => __('Enable Y-Axis Title', 'wts-eae'),
                'type' => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wts-eae' ),
                'label_off'    => __( 'No', 'wts-eae' ),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'y_axis_label',
            [
                'label' => __('Title', 'wts-eae'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter Title', 'wts-eae'),
                'condition' => [
                    'y_axis_show_title' => 'true'
                ]
            ]
        );


        $repeater = new Repeater();

        $repeater->start_controls_tabs('chart_items_tab');

        $repeater->start_controls_tab(
            'content',
            [
                'label' => __('Content', 'wts-eae'),
            ]
        );
        $repeater->add_control(
            'dataset_label',
            [
                'label' => __('Label', 'wts-eae'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'wts-eae'),
                'placeholder' => __('Enter your label', 'wts-eae')
            ]
        );

        $repeater->add_control(
            'dataset_data',
            [
                'label' => __('Data', 'wts-eae'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __("10, 20, 30", 'wts-eae'),
                'description' => __("Enter Y-Axis Data Numbers Separated With ' , ' "),
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
                'style',
                [
                    'label' => __('Style', 'wts-eae'),
                ]
            );

        $repeater->add_control(
            'bar_chart_background',
            [
                'label' => __('Bar Background', 'wts-eae'),
                'type' => Controls_Manager::COLOR,
                'default'   => '',
            ]
        );

        $repeater->add_control(
            'bar_border_width',
            [
                'label'     => __('Border Width', 'wts-eae'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 1,
                'min'       => 1,
                'max'       => 10,
            ]
        );

        $repeater->add_control(
            'bar_chart_border_color',
            [
                'label' => __(' Bar Border Color', 'wts-eae'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#000000',
            ]
        );

        $repeater->end_controls_tab();


        $this->add_control(
            'dataset_text',
            [
                'label'      => __( 'Datasets', 'wts-eae' ),
                'type'       => Controls_Manager::REPEATER,
                'show_label' => true,
                'default'    => [
                    [
                        'dataset_label'          => __( 'Elementor', 'wts-eae' ),
                        'dataset_data'           => [13, 20, 30, 40],
                        'bar_chart_background'   => '#D1406F',
                        'bar_border_width'       => 1,
                        'bar_chart_border_color' => '#ffffff'


                    ],
                    [
                        'dataset_label'          => __( 'Beaver Builder', 'wts-eae' ),
                        'dataset_data'           => [15, 25, 35, 45],
                        'bar_chart_background'   => '#E08A11',
                        'bar_border_width'       => 1,
                        'bar_chart_border_color' => '#ffffff'

                    ],
                ],
                'fields'     => array_values( $repeater->get_controls() ),
            ]
        );

        $this->add_control(
            'y_axis_grid_line',
            [
                'label'        => __('Enable Grid Lines', 'wts-eae'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wts-eae' ),
                'label_off'    => __( 'No', 'wts-eae' ),
                'return_value' => 'true',
                'default'      => '',
                'description' => __(" Enable or Disable Y-Axis Grid Lines "),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Title', 'wts-eae')
            ]
        );

        $this->add_control(
            'chart_Heading',
            [
                'label' => __('Enable Title', 'wts-eae'),
                'type' => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wts-eae' ),
                'label_off'    => __( 'No', 'wts-eae' ),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'chart_main_title',
            [
                'label' => __('Title', 'wts-eae'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter Title', 'wts-eae'),
                'condition' => [
                    'chart_Heading' => 'true'
                ]
            ]
        );

        $this->add_control(
            'title_position',
            [
                'type'        => Controls_Manager::SELECT,
                'label'       => __( 'Position', 'wts-eae' ),
                'default'     => 'top',
                'condition' => [
                    'chart_Heading' => 'true'
                ],
                'options'     => [
                    'top'      => __( 'Top', 'wts-eae' ),
                    'left'     => __( 'Left', 'wts-eae' ),
                    'right'    => __( 'Right', 'wts-eae' ),
                    'bottom'   =>__( 'Bottom', 'wts-eae' ),

                ]

            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_additional_settings',
            [
                'label' => __('Additional Settings', 'wts-eae')
            ]
        );

        $this->add_control('step_size',
            [
                'label'         => __( 'Step Size', 'wts-eae' ),
                'type'          => Controls_Manager::NUMBER,
                'condition'     => [
                    '_skin!'   => [ 'pie', 'doughnut' ]
                ]
            ]
        );

        $this->add_control('y_axis_min_val',
            [
                'label'         => __( 'Minimum Value', 'wts-eae' ),
                'type'          => Controls_Manager::NUMBER,
                'title'         => __('Set Y-axis minimum value, to override if data has a smaller value'),
                'condition'     => [
                    '_skin!'   => [ 'pie', 'doughnut', 'radar', 'polarArea' ]
                ]
            ]
        );

        $this->add_control('y_axis_max_val',
            [
                'label'         => __( 'Maximum Value', 'wts-eae' ),
                'type'          => Controls_Manager::NUMBER,
                'title'         => __('Set Y-axis maximum value, to override if data has a larger value'),
                'min'           => 0,
                'default'       => 1,
                'condition'     => [
                    '_skin!'   => [ 'pie', 'doughnut' ]
                ]
            ]
        );

        $this->add_control('legend_display',
            [
                'label'         => __('Show Legend', 'wts-eae'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => 'Yes',
                'label_off'     => 'No',
                'return_value'  => 'true',
                'description'   => __('Show or Hide datasets label','wts-eae'),
            ]
        );

        $this->add_control('legend_position',
            [
                'label'         => __('Legend Position', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'top'           => __('Top', 'wts-eae'),
                    'right'         => __('Right', 'wts-eae'),
                    'bottom'        => __('Bottom', 'wts-eae'),
                    'left'          => __('Left', 'wts-eae'),
                ],
                'default'       => 'top',
                'condition'     => [
                    'legend_display'  => 'true'
                ]
            ]
        );

//        $this->add_control(
//            'legend_align',
//            [
//                'label'     => __( 'Alignment', 'wts-eae' ),
//                'type'      => Controls_Manager::CHOOSE,
//                'options'   => [
//                    'start'         => [
//                        'title' => __( 'Left', 'wts-eae' ),
//                        'icon'  => 'fa fa-align-left',
//                    ],
//                    'center'      => [
//                        'title' => __( 'Center', 'wts-eae' ),
//                        'icon'  => 'fa fa-align-center',
//                    ],
//                    'end' => [
//                        'title' => __( 'Right', 'wts-eae' ),
//                        'icon'  => 'fa fa-align-right',
//                    ],
//                ],
//                'default'   => 'center',
//                'condition' => [
//                    'legend_display'  => 'true'
//                ]
//            ]
//        );

        $this->add_control('show_tooltips',
            [
                'label'         => __('Show Tooltip', 'wts-eae'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => 'Yes',
                'label_off'     => 'No',
                'return_value'  => 'true',
            ]
        );

        $this->add_control('tooltips_mode',
            [
                'label'         => __('Mode', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'index'         => __('Index', 'wts-eae'),
                    'point'         => __('Point', 'wts-eae'),
                    'nearest'       => __('Nearest', 'wts-eae'),
                    'dataset'       => __('Dataset', 'wts-eae'),
                    'x'             => __('X', 'wts-eae'),
                    'y'             => __('Y', 'wts-eae'),
                ],
                'default'       => 'nearest',
                'condition'     => [
                    'show_tooltips'  => 'true'
                ]
            ]
        );

        $this->add_control('chart_animation',
            [
                'label'         => __('Animation', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'linear'            => __('Linear','wts-eae'),
                    'easeInQuad'        => __('Ease in Quad','wts-eae'),
                    'easeOutQuad'       => __('Ease out Quad','wts-eae'),
                    'easeInOutQuad'     => __('Ease in out Quad','wts-eae'),
                    'easeInCubic'       => __('Ease in Cubic','wts-eae'),
                    'easeOutCubic'      => __('Ease out Cubic','wts-eae'),
                    'easeInOutCubic'    => __('Ease in out Cubic','wts-eae'),
                    'easeInQuart'       => __('Ease in Quart','wts-eae'),
                    'easeOutQuart'      => __('Ease out Quart','wts-eae'),
                    'easeInOutQuart'    => __('Ease in out Quart','wts-eae'),
                    'easeInQuint'       => __('Ease in Quint','wts-eae'),
                    'easeOutQuint'      => __('Ease out Quint','wts-eae'),
                    'easeInOutQuint'    => __('Ease in out Quint','wts-eae'),
                    'easeInSine'        => __('Ease in Sine','wts-eae'),
                    'easeOutSine'       => __('Ease out Sine','wts-eae'),
                    'easeInOutSine'     => __('Ease in out Sine','wts-eae'),
                    'easeInExpo'        => __('Ease in Expo','wts-eae'),
                    'easeOutExpo'       => __('Ease out Expo','wts-eae'),
                    'easeInOutExpo'     => __('Ease in out Cubic','wts-eae'),
                    'easeInCirc'        => __('Ease in Circle','wts-eae'),
                    'easeOutCirc'       => __('Ease out Circle','wts-eae'),
                    'easeInOutCirc'     => __('Ease in out Circle','wts-eae'),
                    'easeInElastic'     => __('Ease in Elastic','wts-eae'),
                    'easeOutElastic'    => __('Ease out Elastic','wts-eae'),
                    'easeInOutElastic'  => __('Ease in out Elastic','wts-eae'),
                    'easeInBack'        => __('Ease in Back','wts-eae'),
                    'easeOutBack'       => __('Ease out Back','wts-eae'),
                    'easeInOutBack'     => __('Ease in Out Back','wts-eae'),
                    'easeInBounce'      => __('Ease in Bounce','wts-eae'),
                    'easeOutBounce'     => __('Ease out Bounce','wts-eae'),
                    'easeInOutBounce'   => __('Ease in out Bounce','wts-eae'),
                ],
                'default'       => 'easeOutQuart',
            ]
        );

        $this->add_control('duration_animation',
            [
                'label'         => __('Animation Duration', 'wts-eae'),
                'type'          => Controls_Manager::NUMBER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'General', 'wts-eae' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control('eae_chart_height',
            [
                'label'         => __('Height', 'wts-eae'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 500,
                'selectors'     => [
                    '{{WRAPPER}} .eae-chart-wrapper'   => 'height: {{SIZE}}px'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'eae_chart_background_color',
                'label'    => __( 'Background Color', 'wts-eae' ),
                'types'    => [ 'none', 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .eae-chart-outer-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eae_chart_border',
                'label'    => __( 'Border', 'wts-eae' ),
                'selector' => '{{WRAPPER}} .eae-chart-outer-wrapper',
            ]
        );

        $this->add_control(
            'eae_chart_border_radius',
            [
                'label'      => __( 'Border Radius', 'wts-eae' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => [
                    '{{WRAPPER}}  .eae-chart-outer-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eae_chart_box_shadow',
                'label'    => 'Box Shadow',
                'selector' => '{{WRAPPER}} .eae-chart-outer-wrapper',
            ]
        );

        $this->add_responsive_control('eae_chart_margin',
            [
                'label'         => __('Margin', 'wts-eae'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-chart-outer-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->add_responsive_control('eae_chart_padding',
            [
                'label'         => __('Padding', 'wts-eae'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .eae-chart-outer-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'heading_style',
            [
                'label' => __( 'Title', 'wts-eae' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'chart_Heading' => 'true'
                ]
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label'     => __( 'Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],

            ]
        );

        $this->add_control('title_typography',
            [
                'label'         => __('Typography', 'wts-eae'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
            ]
        );

        $this->start_popover();

        $this->add_control(
            'heading_font_family',
            [
                'label'     => __( 'Family', 'wts-eae'),
                'type'      => Controls_Manager::FONT,
                'default' => '',
            ]
        );

        $this->add_control('heading_font_size',
            [
                'label'			=> __( 'Size', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 200,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 15
                ],

            ]
        );

        $this->add_control('heading_font_style',
            [
                'label'         => __('Style', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'default'       => '',
                'options' => [
                    ''       => _x( 'Default', 'wts-eae' ),
                    'normal' => _x( 'Normal', 'wts-eae' ),
                    'bold' => _x( 'Bold', 'wts-eae' ),
                    'italic' => _x( 'Italic', 'wts-eae' ),
                    'oblique' => _x( 'Oblique', 'wts-eae' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'heading_line_height',
            [
                'label'         => __('Line-Height', 'wts-eae'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'default'       => '',
            ]
        );

        $this->end_popover();

        $this->add_control('heading_padding',
            [
                'label'         => __('Padding', 'wts-eae'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'max'           => 200,
                'default'       => '',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'xaxis_style',
            [
                'label' => __( 'X-Axis', 'wts-eae' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'xaxis_label_color',
            [
                'label'     => __( 'Axis Label Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'condition' => [
                    'x_axis_show_title' => 'true'
                ]

            ]
        );

        $this->add_control('xaxis_typography',
            [
                'label'         => __('Axis Label Typography', 'wts-eae'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'x_axis_show_title' => 'true'
                ]
            ]
        );

        $this->start_popover();

        $this->add_control(
            'xaxis_font_family',
            [
                'label'     => __( 'Family', 'wts-eae'),
                'type'      => Controls_Manager::FONT,
                'default' => '',
            ]
        );

        $this->add_control('xaxis_font_size',
            [
                'label'			=> __( 'Size', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 200,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 12
                ],

            ]
        );

        $this->add_control('xaxis_font_style',
            [
                'label'         => __('Style', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'default'       => '',
                'options' => [
                    ''       => _x( 'Default', 'wts-eae' ),
                    'normal' => _x( 'Normal', 'wts-eae' ),
                    'bold' => _x( 'Bold', 'wts-eae' ),
                    'italic' => _x( 'Italic', 'wts-eae' ),
                    'oblique' => _x( 'Oblique', 'wts-eae' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'xaxis_line_height',
            [
                'label'         => __('Line-Height', 'wts-eae'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'default'       => '',
            ]
        );

        $this->end_popover();

        $this->add_control(
            'data_label_color',
            [
                'label'     => __( 'Data Label Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],

            ]
        );

        $this->add_control('data_label_typography',
            [
                'label'         => __('Data Label Typography', 'wts-eae'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
            ]
        );

        $this->start_popover();

        $this->add_control(
            'data_font_family',
            [
                'label'     => __( 'Family', 'wts-eae'),
                'type'      => Controls_Manager::FONT,
                'default' => '',
            ]
        );

        $this->add_control('data_font_size',
            [
                'label'			=> __( 'Size', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 200,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 12
                ],

            ]
        );

        $this->add_control('data_font_style',
            [
                'label'         => __('Style', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'default'       => '',
                'options' => [
                    ''       => _x( 'Default', 'wts-eae' ),
                    'normal' => _x( 'Normal', 'wts-eae' ),
                    'bold' => _x( 'Bold', 'wts-eae' ),
                    'italic' => _x( 'Italic', 'wts-eae' ),
                    'oblique' => _x( 'Oblique', 'wts-eae' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'data_line_height',
            [
                'label'         => __('Line-Height', 'wts-eae'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'default'       => '',
            ]
        );

        $this->end_popover();

        $this->add_control(
            'xaxis_grid_line_color',
            [
                'label'     => __( 'Grid Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'condition' => [
                    'x_axis_grid_line' => 'true'
                ],

            ]
        );

        $this->add_control('xaxis_grid_line_width',
            [
                'label'			=> __( 'Grid Width', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 10,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 1
                ],
                'condition' => [
                    'x_axis_grid_line' => 'true'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'yaxis_style',
            [
                'label' => __( 'Y-Axis', 'wts-eae' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'yaxis_label_color',
            [
                'label'     => __( 'Axis Label Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'condition' => [
                    'y_axis_show_title' => 'true'
                ]

            ]
        );

        $this->add_control('yaxis_typography',
            [
                'label'         => __('Typography', 'wts-eae'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
                'condition' => [
                    'y_axis_show_title' => 'true'
                ]
            ]
        );

        $this->start_popover();

        $this->add_control(
            'yaxis_font_family',
            [
                'label'     => __( 'Family', 'wts-eae'),
                'type'      => Controls_Manager::FONT,
                'default' => '',
            ]
        );

        $this->add_control('yaxis_font_size',
            [
                'label'			=> __( 'Size', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 200,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 12
                ],

            ]
        );

        $this->add_control('yaxis_font_style',
            [
                'label'         => __('Style', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'default'       => '',
                'options' => [
                    ''       => _x( 'Default', 'wts-eae' ),
                    'normal' => _x( 'Normal', 'wts-eae' ),
                    'bold' => _x( 'Bold', 'wts-eae' ),
                    'italic' => _x( 'Italic', 'wts-eae' ),
                    'oblique' => _x( 'Oblique', 'wts-eae' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'yaxis_line_height',
            [
                'label'         => __('Line-Height', 'wts-eae'),
                'type'          => Controls_Manager::NUMBER,
                'min'           => 1,
                'default'       => '',
            ]
        );

        $this->end_popover();

        $this->add_control('yaxis_data',
            [
                'label'         => __('Data', 'wts-eae'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,

            ]
        );

        $this->start_popover();

        $this->add_control(
            'yaxis_data_color',
            [
                'label'     => __( 'Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ]
            ]
        );

        $this->add_control('yaxis_data_font_size',
            [
                'label'			=> __( 'Size', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 200,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 12
                ],

            ]
        );

        $this->end_popover();

        $this->add_control(
            'yaxis_grid_line_color',
            [
                'label'     => __( 'Grid Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'condition' => [
                    'y_axis_grid_line' => 'true'
                ],

            ]
        );

        $this->add_control('yaxis_grid_line_width',
            [
                'label'			=> __( 'Grid Width', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 0,
                        'max'   => 10,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 1
                ],
                'condition' => [
                    'y_axis_grid_line' => 'true'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'legend_style',
            [
                'label' => __( 'Legend', 'wts-eae' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'legend_display'  => 'true'
                ]
            ]
        );

        $this->add_control(
            'legend_color',
            [
                'label'     => __( 'Legend Color', 'wts-eae' ),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
            ]
        );

        $this->add_control('legend_typography',
            [
                'label'         => __('Typography', 'wts-eae'),
                'type'          => Controls_Manager::POPOVER_TOGGLE,
            ]
        );

        $this->start_popover();

        $this->add_control(
            'legend_font_family',
            [
                'label'     => __( 'Family', 'wts-eae'),
                'type'      => Controls_Manager::FONT,
                'default' => '',
            ]
        );

        $this->add_control('legend_font_size',
            [
                'label'			=> __( 'Size', 'wts-eae' ),
                'type'			=> Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1,
                        'max'   => 200,
                    ]
                ],
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 12
                ],

            ]
        );

        $this->add_control('legend_font_style',
            [
                'label'         => __('Style', 'wts-eae'),
                'type'          => Controls_Manager::SELECT,
                'default'       => '',
                'options' => [
                    ''       => _x( 'Default', 'wts-eae' ),
                    'normal' => _x( 'Normal', 'wts-eae' ),
                    'bold' => _x( 'Bold', 'wts-eae' ),
                    'italic' => _x( 'Italic', 'wts-eae' ),
                    'oblique' => _x( 'Oblique', 'wts-eae' ),
                ],
            ]
        );

        $this->end_popover();

        $this->end_controls_section();

    }



}