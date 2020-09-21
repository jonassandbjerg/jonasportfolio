<?php

namespace WTS_EAE\Modules\Unfold;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes;
class Module  {
	private static $_instance = null;

	public function __construct()
	{
		//Widget add fields
		add_action('elementor/element/common/_section_style/after_section_end', [$this, 'widget_unfold'], 10);
		//Widget FrontEnd
		add_action( 'elementor/widget/render_content', [$this, 'render_content'], 10 , 2 );
		//Column and Section add fields
		add_action( 'elementor/element/after_section_end', [ $this, 'register_controls' ], 10, 3 );
		//Column and Section Editor
		add_action( 'elementor/element/print_template', [ $this, '_print_template'],10,2);
		add_filter( 'elementor/widget/print_template', [ $this, '_content_template'],10,2);
		add_action( 'elementor/section/print_template', [ $this, '_print_template'],10,2);
		add_action( 'elementor/column/print_template', [ $this, '_print_template'],10,2);
		//Column and Section Front End
		add_action( 'elementor/frontend/before_render', [ $this, '_before_render']);
	}

	public function widget_unfold($element){

		$element->start_controls_section(
			'widget_unfold',
			[
				'tab' => Controls_Manager::TAB_ADVANCED,
				'label' => __('EAE - Unfold', 'wts-eae')
			]
		);
		$element->add_control(
			'widget_enable_unfold',
			[
				'type'  => Controls_Manager::SWITCHER,
				'label' => __('Enable', 'wts-eae'),
				'default' => '',
				'label_on' => __( 'Yes', 'wts-eae' ),
				'label_off' => __( 'No', 'wts-eae' ),
				'prefix_class'  =>  'eae-widget-unfold-',
				'return_value' => 'yes',
				'render_type'   => 'template',
			]
		);

		$element->start_controls_tabs(
			'widget_unfold_tabs',
			[
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);


		$element->start_controls_tab(
			'widget_unfold_tab',
			[
				'label' => __( 'Unfold', 'wts-eae' ),

			]
		);

		$element->add_control(
			'widget_unfold_button_text',
			[
				'label' => __( 'Text', 'wts-eae' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Unfold', 'wts-eae' ),
				'placeholder' => __( 'Type your text here', 'wts-eae' ),
			]
		);

		$element->add_control(
			'widget_icon_unfold',
			[
				'label' => __( 'Icon', 'wts-eae' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-down',
					'library' => 'solid',
				],
				'skin'  =>  'inline',
				'label_block'   => false,
				'exclude_inline_options'    =>  'svg',
				'recommended' => [
					'fa-solid' => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
			]
		);



		$element->end_controls_tab();

		$element->start_controls_tab(
			'widget_fold_tab',
			[
				'label' => __( 'Fold', 'wts-eae' ),
			]
		);

		$element->add_control(
			'widget_fold_button_text',
			[
				'label' => __( 'Text', 'wts-eae' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Fold', 'wts-eae' ),
				'placeholder' => __( 'Type your text here', 'wts-eae' ),
			]
		);
		$element->add_control(
			'widget_icon_fold',
			[
				'label' => __( 'Icon', 'wts-eae' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-up',
					'library' => 'solid',
				],
				'skin'  =>  'inline',
				'label_block'   => false,
				'exclude_inline_options'    =>  'svg',
				'recommended' => [
					'fa-solid' => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
			]
		);


		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->add_responsive_control(
			'widget_icon_spacing',
			[
				'label'     => __( 'Icon Spacing', 'wts-eae' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'default'   =>  [
					'size'  =>  '10',
					'unit'  =>  'px'
				],
				'selectors' => [
					'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-align-icon-before' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-align-icon-after' => 'margin-left: {{SIZE}}px;',

				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],

			]
		);

		$element->add_control(
			'widget_unfold_button_icon_position',
			[
				'label'         => __('Icon Position', 'premium-addons-pro'),
				'type'          => Controls_Manager::SELECT,
				'default'       => 'before',
				'options'       => [
					'before'            =>  __('Before', 'premium-addons-pro'),
					'after'           =>  __('After', 'premium-addons-pro'),
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],

			]
		);

		$element->add_responsive_control(
			'widget_unfold_button_align',
			[
				'label'             => __( 'Alignment', 'premium-addons-pro' ),
				'type'              => Controls_Manager::CHOOSE,
				'options'           => [
					'left'    => [
						'title' => __( 'Left', 'premium-addons-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'premium-addons-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'premium-addons-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors'         => [
					'{{WRAPPER}} .eae-element-unfold-content' => 'text-align: {{VALUE}}',
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
				'default' => 'center',
			]
		);

		$element->add_control(
			'widget_unfold_adv_set',
			[
				'label' => __( 'Additional Setting', 'wts-eae' ),
				'type' =>Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->add_responsive_control(
			'widget_fold_max_height',
			[
				'label' => __( 'Fold Height', 'ae-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 300,
				],
				'tablet_default' => [
					'size' => 400,
				],
				'mobile_default' => [
					'size' => 500,
				],
				'render_type' => 'template',

				'selectors' => [
					'{{WRAPPER}}.eae-widget-unfold-yes.elementor-widget' => 'max-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->add_control(
			'widget_unfold_animation_speed',
			[
				'label' => __( 'Animation Speed', 'ae-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 500,
						'max' => 5000,
						'step' => 100
					],
				],
				'default' => [
					'size' => 500,
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->add_control(
			'widget_button_hover_animation',
			[
				'label'   => __( 'Animation', 'wts-eae' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'          => __( 'None', 'wts-eae' ),
					'sweep-left'    => __( 'Style 1', 'wts-eae' ),
					'sweep-right'   => __( 'Style 2', 'wts-eae' ),
					'bounce-left'   => __( 'Style 3', 'wts-eae' ),
					'bounce-right'  => __( 'Style 4', 'wts-eae' ),
					'sweep-top'     => __( 'Style 5', 'wts-eae' ),
					'sweep-bottom'  => __( 'Style 6', 'wts-eae' ),
					'bounce-top'    => __( 'Style 7', 'wts-eae' ),
					'bounce-bottom' => __( 'Style 8', 'wts-eae' ),
				],
				'render_type'   => 'template',
				'default' => 'none',
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);



		$element->add_control(
			'widget_unfold_style',
			[
				'label' => __( 'Style Unfold Container', 'wts-eae' ),
				'type' =>Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->start_controls_tabs(
			'widget_unfold_section_style',
			[
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->start_controls_tab(
			'widget_unfold_section_style_normal',
			[
				'label' => __( 'Normal', 'wts-eae' ),
			]
		);
		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'widget_unfold_section_background',
				'label'    => __( 'Background Color', 'wts-eae' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eae-element-unfold-content',
			]
		);
		$element->end_controls_tab();

		$element->start_controls_tab(
			'widget_unfold_section_style_hover',
			[
				'label' => __( 'Hover', 'wts-eae' ),
			]
		);
		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'widget_unfold_section_background_hover',
				'label'    => __( 'Background Color', 'wts-eae' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eae-element-unfold-content:hover',
			]
		);
		$element->end_controls_tab();

		$element->end_controls_tabs();



		$element->add_responsive_control(
			'widget_unfold_section_padding',
			[
				'label' => __( 'Padding', 'wts-eae' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eae-element-unfold-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '15',
					'right' => '',
					'bottom' => '15',
					'left' => '',
					'unit' => 'px',
					'isLinked' => '',
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);

		$element->add_control(
			'widget_unfold_style_button',
			[
				'label' => __( 'Style Unfold Button', 'wts-eae' ),
				'type' =>Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->start_controls_tabs(
			'widget_unfold_button_style',
			[
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->start_controls_tab(
			'widget_unfold_button_style_normal',
			[
				'label' => __( 'Normal', 'wts-eae' ),
			]
		);
		$element->add_control(
			'widget_unfold_button_color',
			[
				'label' => __( 'Color', 'wts-eae' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.eae-unfold-link' => 'color: {{VALUE}}',
					'{{WRAPPER}} a.eae-unfold-link svg' => 'background-color: {{VALUE}}',
				],
			]
		);
		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'widget_unfold_button_background',
				'label'    => __( 'Background Color', 'wts-eae' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} a.eae-unfold-link',
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'widget_unfold_button_style_hover',
			[
				'label' => __( 'Hover', 'wts-eae' ),
			]
		);
		$element->add_control(
			'widget_unfold_button_color_hover',
			[
				'label' => __( 'Color', 'wts-eae' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.eae-unfold-link:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} a.eae-unfold-link:hover svg' => 'background-color: {{VALUE}}',
				],
			]
		);
		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'widget_unfold_button_background_hover',
				'label'    => __( 'Background Color', 'wts-eae' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} a.eae-unfold-link.eae-none:hover , {{WRAPPER}} a.eae-unfold-link:hover:before , {{WRAPPER}} a.eae-unfold-link:before',
			]
		);
		$element->add_control(
			'widget_unfold_border_hover_color',
			[
				'label'     => __( 'Border Color', 'wts-eae' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'unfold_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.eae-unfold-link:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'widget_unfold_button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.eae-unfold-link .eae-unfold-button-text',
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);
		$element->add_responsive_control(
			'widget_icon_size',
			[
				'label'     => __( 'Icon Size', 'wts-eae' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   =>  [
					'size'  =>  '18',
					'unit'  =>  'px'
				],
				'selectors' => [
					'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-button-icon i' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-button-icon svg' => 'width: {{SIZE}}px; height : {{SIZE}}px',

				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],

			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'widget_unfold_button_border',
				'fields_options' => [
//						'border' => [
//							'default' => 'solid',
//						],
//						'width'  => [
//							'default' => [
//								'top'    => 1,
//								'right'  => 1,
//								'bottom' => 1,
//								'left'   => 1,
//								'unit'   => 'px'
//							],
//						],
//						'color'  => [
//							'default' => '#0c0c0c',
//						]
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
				'selector'       => '{{WRAPPER}} a.eae-unfold-link',
			]
		);
		$element->add_control(
			'widget_border_hover_color',
			[
				'label'     => __( 'Border Color Hover', 'wts-eae' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'widget_unfold_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.eae-unfold-link:hover' => 'border-color: {{VALUE}};',
				],

			]
		);

		$element->add_control(
			'widget_unfold_border_radius',
			[
				'label'      => __( 'Border Radius', 'wts-eae' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'       => [
					'{{WRAPPER}} a.eae-unfold-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);

		$element->add_responsive_control(
			'widget_unfold_button_padding',
			[
				'label' => __( 'Padding', 'wts-eae' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} a.eae-unfold-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '5',
					'right' => '20',
					'bottom' => '5',
					'left' => '20',
					'unit' => 'px',
				],
				'condition'  => [
					'widget_enable_unfold'  =>  'yes',
				],
			]
		);




		$element->end_controls_section();

	}
	public function register_controls($element, $section_id, $args){
		if ( ('section' === $element->get_name() && 'section_background' === $section_id) || ('column' === $element->get_name() && 'section_style' === $section_id) || ('heading' === $element->get_name() && 'section_effects' === $section_id) ) {
			$elm = $element->get_name();
			$element->start_controls_section(
				'sc_unfold',
				[
					'tab' => Controls_Manager::TAB_STYLE,
					'label' => __('EAE - Unfold', 'wts-eae')
				]
			);
			$element->add_control(
				'sc_enable_unfold',
				[
					'type'  => Controls_Manager::SWITCHER,
					'label' => __('Enable', 'wts-eae'),
					'default' => '',
					'label_on' => __( 'Yes', 'wts-eae' ),
					'label_off' => __( 'No', 'wts-eae' ),
					'prefix_class'  =>  'eae-widget-unfold-',
					'return_value' => 'yes',
					'render_type'   => 'template',
				]
			);

			$element->start_controls_tabs(
				'sc_unfold_tabs',
				[
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);


			$element->start_controls_tab(
				'sc_unfold_tab',
				[
					'label' => __( 'Unfold', 'wts-eae' ),

				]
			);

			$element->add_control(
				'sc_unfold_button_text',
				[
					'label' => __( 'Text', 'wts-eae' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'Unfold', 'wts-eae' ),
					'placeholder' => __( 'Type your text here', 'wts-eae' ),
				]
			);

			$element->add_control(
				'sc_icon_unfold',
				[
					'label' => __( 'Icon', 'wts-eae' ),
					'type' => Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-angle-down',
						'library' => 'solid',
					],
					'skin'  =>  'inline',
					'label_block'   => false,
					'exclude_inline_options'    =>  'svg',
					'recommended' => [
						'fa-solid' => [
							'chevron-down',
							'angle-down',
							'angle-double-down',
							'caret-down',
							'caret-square-down',
						],
						'fa-regular' => [
							'caret-square-down',
						],
					],

				]
			);



			$element->end_controls_tab();

			$element->start_controls_tab(
				'sc_fold_tab',
				[
					'label' => __( 'Fold', 'wts-eae' ),
				]
			);

			$element->add_control(
				'sc_fold_button_text',
				[
					'label' => __( 'Text', 'wts-eae' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'Fold', 'wts-eae' ),
					'placeholder' => __( 'Type your text here', 'wts-eae' ),
				]
			);
			$element->add_control(
				'sc_icon_fold',
				[
					'label' => __( 'Icon', 'wts-eae' ),
					'type' => Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-angle-up',
						'library' => 'solid',
					],
					'skin'  =>  'inline',
					'label_block'   => false,
					'exclude_inline_options'    =>  'svg',
					'recommended' => [
						'fa-solid' => [
							'chevron-up',
							'angle-up',
							'angle-double-up',
							'caret-up',
							'caret-square-up',
						],
						'fa-regular' => [
							'caret-square-up',
						],
					],
				]
			);


			$element->end_controls_tab();

			$element->end_controls_tabs();

			$element->add_responsive_control(
				'sc_icon_spacing',
				[
					'label'     => __( 'Icon Spacing', 'wts-eae' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 50,
						],
					],
					'default'   =>  [
						'size'  =>  '10',
						'unit'  =>  'px'
					],
					'selectors' => [
						'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-align-icon-before' => 'margin-right: {{SIZE}}px;',
						'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-align-icon-after' => 'margin-left: {{SIZE}}px;',

					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],

				]
			);

			$element->add_control(
				'sc_unfold_button_icon_position',
				[
					'label'         => __('Icon Position', 'premium-addons-pro'),
					'type'          => Controls_Manager::SELECT,
					'default'       => 'before',
					'options'       => [
						'before'            =>  __('Before', 'premium-addons-pro'),
						'after'           =>  __('After', 'premium-addons-pro'),
					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],

				]
			);

			$element->add_responsive_control(
				'sc_unfold_button_align',
				[
					'label'             => __( 'Alignment', 'premium-addons-pro' ),
					'type'              => Controls_Manager::CHOOSE,
					'options'           => [
						'left'    => [
							'title' => __( 'Left', 'premium-addons-pro' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'premium-addons-pro' ),
							'icon'  => 'fa fa-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'premium-addons-pro' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'selectors'         => [
						'{{WRAPPER}} .eae-element-unfold-content' => 'text-align: {{VALUE}}',
					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
					'default' => 'center',
				]
			);

			$element->add_control(
				'sc_unfold_adv_set',
				[
					'label' => __( 'Additional Setting', 'wts-eae' ),
					'type' =>Controls_Manager::HEADING,
					'separator' => 'before',
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);
			$element->add_responsive_control(
				'sc_fold_max_height',
				[
					'label' => __( 'Fold Height', 'ae-pro' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1
						],
					],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'desktop_default' => [
						'size' => 300,
					],
					'tablet_default' => [
						'size' => 400,
					],
					'mobile_default' => [
						'size' => 500,
					],
					'render_type' => 'template',

					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);
			$element->add_control(
				'sc_unfold_animation_speed',
				[
					'label' => __( 'Animation Speed', 'ae-pro' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 500,
							'max' => 5000,
							'step' => 100
						],
					],
					'default' => [
						'size' => 500,
					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);

			$element->add_control(
				'sc_button_hover_animation',
				[
					'label'   => __( 'Animation', 'wts-eae' ),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'none'          => __( 'None', 'wts-eae' ),
						'sweep-left'    => __( 'Style 1', 'wts-eae' ),
						'sweep-right'   => __( 'Style 2', 'wts-eae' ),
						'bounce-left'   => __( 'Style 3', 'wts-eae' ),
						'bounce-right'  => __( 'Style 4', 'wts-eae' ),
						'sweep-top'     => __( 'Style 5', 'wts-eae' ),
						'sweep-bottom'  => __( 'Style 6', 'wts-eae' ),
						'bounce-top'    => __( 'Style 7', 'wts-eae' ),
						'bounce-bottom' => __( 'Style 8', 'wts-eae' ),
					],
					'render_type'   => 'template',
					'default' => 'none',
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);
			if($element->get_name() === 'section'){
				$element->add_control(
					'unfold_position',
					[
						'label'   => __( 'Position', 'wts-eae' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'inside',
						'options' => [
							'inside'        =>  __( 'Inside', 'wts-eae' ),
							'outside'       =>  __( 'Outside', 'wts-eae' ),
						],
						'condition'  => [
							'sc_enable_unfold'  =>  'yes',
						],
					]
				);
			}

			$element->add_control(
				'sc_unfold_style',
				[
					'label' => __( 'Style Unfold Container', 'wts-eae' ),
					'type' =>Controls_Manager::HEADING,
					'separator' => 'before',
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);
			$element->start_controls_tabs(
				'unfold_section_style',
				[
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);
			$element->start_controls_tab(
				'unfold_section_style_normal',
				[
					'label' => __( 'Normal', 'wts-eae' ),
				]
			);
			$element->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'unfold_section_background',
					'label'    => __( 'Background Color', 'wts-eae' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .eae-element-unfold-content',
				]
			);
			$element->end_controls_tab();

			$element->start_controls_tab(
				'unfold_section_style_hover',
				[
					'label' => __( 'Hover', 'wts-eae' ),
				]
			);
			$element->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'unfold_section_background_hover',
					'label'    => __( 'Background Color', 'wts-eae' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .eae-element-unfold-content:hover',
				]
			);
			$element->end_controls_tab();

			$element->end_controls_tabs();



			$element->add_responsive_control(
				'unfold_section_padding',
				[
					'label' => __( 'Padding', 'wts-eae' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .eae-element-unfold-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '15',
						'right' => '',
						'bottom' => '15',
						'left' => '',
						'unit' => 'px',
						'isLinked' => '',
					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);

			$element->add_control(
				'sc_unfold_style_button',
				[
					'label' => __( 'Style Unfold Button', 'wts-eae' ),
					'type' =>Controls_Manager::HEADING,
					'separator' => 'before',
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);
			$element->start_controls_tabs(
				'unfold_button_style',
				[
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);
			$element->start_controls_tab(
				'unfold_button_style_normal',
				[
					'label' => __( 'Normal', 'wts-eae' ),
				]
			);
			$element->add_control(
				'unfold_button_color',
				[
					'label' => __( 'Color', 'wts-eae' ),
					'type'  =>  Controls_Manager::COLOR,
					'scheme' => [
						'type' => Schemes\Color::get_type(),
						'value' => Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} a.eae-unfold-link' => 'color: {{VALUE}}',
						'{{WRAPPER}} a.eae-unfold-link svg' => 'background-color: {{VALUE}}; fill: {{VALUE}};',
						'{{WRAPPER}}.eae-unfold-icon-type-svg a.eae-unfold-link .eae-unfold-button-icon' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.eae-fold-icon-type-svg a.eae-unfold-link .eae-unfold-button-icon' => 'background-color: {{VALUE}};',
					],
				]
			);
			$element->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'unfold_button_background',
					'label'    => __( 'Background Color', 'wts-eae' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} a.eae-unfold-link',
				]
			);

			$element->end_controls_tab();

			$element->start_controls_tab(
				'unfold_button_style_hover',
				[
					'label' => __( 'Hover', 'wts-eae' ),
				]
			);
			$element->add_control(
				'unfold_button_color_hover',
				[
					'label' => __( 'Color', 'wts-eae' ),
					'type'      => Controls_Manager::COLOR,
					'scheme' => [
						'type' => Schemes\Color::get_type(),
						'value' => Schemes\Color::COLOR_1,
					],
					'selectors' => [
						'{{WRAPPER}} a.eae-unfold-link:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} a.eae-unfold-link:hover svg' => 'background-color: {{VALUE}}',
					],
				]
			);
			$element->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'unfold_button_background_hover',
					'label'    => __( 'Background Color', 'wts-eae' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} a.eae-unfold-link.eae-none:hover , {{WRAPPER}} a.eae-unfold-link:hover:before , {{WRAPPER}} a.eae-unfold-link:before',
				]
			);
			$element->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'unfold_button_box_shadow_hover',
					'selector' => '{{WRAPPER}} a.eae-unfold-link:hover',
				]
			);

			$element->end_controls_tab();

			$element->end_controls_tabs();

			$element->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'unfold_button_typography',
					'scheme' => Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} a.eae-unfold-link .eae-unfold-button-text',
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);

			$element->add_responsive_control(
				'sc_icon_size',
				[
					'label'     => __( 'Icon Size', 'wts-eae' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max' => 100,
						],
					],
					'default'   =>  [
						'size'  =>  '18',
						'unit'  =>  'px'
					],
					'selectors' => [
						'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-button-icon i' => 'font-size: {{SIZE}}px;',
						'{{WRAPPER}} .eae-element-unfold-content span.eae-unfold-button-icon svg' => 'width: {{SIZE}}px; height : {{SIZE}}px',

					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],

				]
			);

			$element->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'           => 'unfold_button_border',
					'fields_options' => [
//						'border' => [
//							'default' => 'solid',
//						],
//						'width'  => [
//							'default' => [
//								'top'    => 1,
//								'right'  => 1,
//								'bottom' => 1,
//								'left'   => 1,
//								'unit'   => 'px'
//							],
//						],
//						'color'  => [
//							'default' => '#0c0c0c',
//						]
					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
					'selector'       => '{{WRAPPER}} a.eae-unfold-link',
				]
			);
			$element->add_control(
				'unfold_border_hover_color',
				[
					'label'     => __( 'Border Color Hover', 'wts-eae' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'unfold_button_border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} a.eae-unfold-link:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

			$element->add_control(
				'unfold_border_radius',
				[
					'label'      => __( 'Border Radius', 'wts-eae' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'       => [
					        '{{WRAPPER}} a.eae-unfold-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);

			$element->add_responsive_control(
				'unfold_button_padding',
				[
					'label' => __( 'Padding', 'wts-eae' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} a.eae-unfold-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default' => [
						'top' => '5',
						'right' => '20',
						'bottom' => '5',
						'left' => '20',
						'unit' => 'px',
					],
					'condition'  => [
						'sc_enable_unfold'  =>  'yes',
					],
				]
			);




			$element->end_controls_section();
		}
	}

	public function _before_render($element){
		if($element->get_name() != 'section' && $element->get_name() != 'column'){
			return;
		}
		$settings = $element->get_settings();
		if($settings['sc_enable_unfold'] == 'yes') {
			$max_fold_height['desktop'] = $settings['sc_fold_max_height']['size'];
			$max_fold_height['tablet'] = $settings['sc_fold_max_height_tablet']['size'];
			$max_fold_height['mobile'] = $settings['sc_fold_max_height_mobile']['size'];

			if($element->get_name() == 'section'){
				$element->add_render_attribute('_wrapper' , 'data-unfold-position' , $settings['unfold_position']);
			}
			$element->add_render_attribute('_wrapper' , 'data-fold-text' , $settings['sc_fold_button_text']);
			$element->add_render_attribute('_wrapper' , 'data-unfold-text' , $settings['sc_unfold_button_text']);
			//$element->add_render_attribute('_wrapper' , 'data-unfold-icon' , $settings['sc_icon_unfold']['value']);
			//$element->add_render_attribute('_wrapper' , 'data-fold-icon' , $settings['sc_icon_fold']['value']);
			$element->add_render_attribute('_wrapper' , 'data-fold-height' , json_encode($max_fold_height, JSON_NUMERIC_CHECK));
			$element->add_render_attribute('_wrapper' , 'data-animation-speed' , $settings['sc_unfold_animation_speed']['size']);
			$element->add_render_attribute('_wrapper' , 'data-hover-animation' , $settings['sc_button_hover_animation']);
			$element->add_render_attribute('_wrapper' , 'data-icon-pos' , $settings['sc_unfold_button_icon_position']);
			$unfold_button_icon_type = isset($settings['sc_icon_unfold']['value']['url']) ? 'svg' : 'icon';
			$fold_button_icon_type = isset($settings['sc_icon_fold']['value']['url']) ? 'svg' : 'icon';
			$class = [
				'eae-fold-yes',
				'eae-unfold-icon-type-'.$unfold_button_icon_type,
				'eae-fold-icon-type-'.$fold_button_icon_type,
			];
			$element->add_render_attribute('_wrapper' , 'class' , $class);

			$element->add_render_attribute( '_wrapper', 'data-unfold-icon-type', $unfold_button_icon_type);
			if($unfold_button_icon_type == 'svg'){
				$element->add_render_attribute( '_wrapper', 'data-unfold-icon', $settings['sc_icon_unfold']['value']['url'] );
//				$icon_html = "<svg style='-webkit-mask: url(". $settings['widget_icon_unfold']['value']['url'] . "); mask: url(". $settings['widget_icon_unfold']['value']['url'] . "'); ></svg>";
			}else{
				$element->add_render_attribute( '_wrapper', 'data-unfold-icon', $settings['sc_icon_unfold']['value'] );
//				$icon_html = "<i class='".$settings['widget_icon_fold']['value']."'></i>";
			}

			$element->add_render_attribute( '_wrapper', 'data-fold-icon-type',  $fold_button_icon_type);
			if($fold_button_icon_type == 'svg'){
				$element->add_render_attribute( '_wrapper', 'data-fold-icon', $settings['sc_icon_fold']['value']['url'] );
			}else{
				$element->add_render_attribute( '_wrapper', 'data-fold-icon', $settings['sc_icon_fold']['value'] );
			}
		?>
<!--            <div class="ae-element-post-content-inner">-->
<!--                <div class="eae-unfold-button">-->
<!--                    <a class="li-post-content-unfold-link" href="#">-->
<!--                    --><?php //if($settings['sc_unfold_button_icon_position'] == 'before'){ ?>
<!--                        <span class="eae-unfold-button-icon eae-unfold-align-icon---><?php //echo $settings['sc_unfold_button_icon_position']; ?><!--">-->
<!--                                    <i class="--><?php //echo $settings['sc_icon_unfold']['value']; ?><!--"></i>-->
<!--                        </span>-->
<!--                    --><?php // } ?>
<!--                        <span class="eae-unfold-button-text">--><?php //echo $settings['sc_fold_button_text']; ?><!--</span>-->
<!--                    --><?php //if($settings['sc_unfold_button_icon_position'] == 'after'){ ?>
<!--                        <span class="eae-unfold-button-icon eae-unfold-align-icon---><?php //echo $settings['sc_unfold_button_icon_position']; ?><!--">-->
<!--                                    <i class="--><?php //echo $settings['sc_icon_unfold']['value']; ?><!--"></i>-->
<!--                        </span>-->
<!--                    --><?php // } ?>
<!--                </a>-->
<!--                </div>-->
<!--            </div>-->
        <?php }

	}
	public function render_content( $content, $widget ) {
		$widget_id = $widget->get_id();
		$settings = $widget->get_settings_for_display();

		if($settings['widget_enable_unfold'] == 'yes') {
			$max_fold_height['desktop'] = $settings['widget_fold_max_height']['size'];
			$max_fold_height['tablet'] = $settings['widget_fold_max_height_tablet']['size'];
			$max_fold_height['mobile'] = $settings['widget_fold_max_height_mobile']['size'];
			$widget->add_render_attribute( '_wrapper', 'class', ['eae-fold-yes' ,'eae-rc'] );
			$widget->add_render_attribute( '_wrapper', 'data-fold-text', $settings['widget_fold_button_text'] );
			$widget->add_render_attribute( '_wrapper', 'data-unfold-text', $settings['widget_unfold_button_text'] );
			$widget->add_render_attribute('_wrapper' , 'data-fold-height' , json_encode($max_fold_height, JSON_NUMERIC_CHECK));
			$widget->add_render_attribute( '_wrapper', 'data-animation-speed', $settings['widget_unfold_animation_speed']['size'] );
			$widget->add_render_attribute( '_wrapper', 'data-icon-pos', $settings['widget_unfold_button_icon_position'] );
			$widget->add_render_attribute( '_wrapper', 'data-hover-animation', $settings['widget_button_hover_animation'] );
			$widget->add_render_attribute( 'unfold_link', 'class', 'eae-unfold-link' );
			$widget->add_render_attribute( 'unfold_link', 'href', '#' );
			$unfold_button_icon_type = isset($settings['widget_icon_unfold']['value']['url']) ? 'svg' : 'icon';
			$widget->add_render_attribute( '_wrapper', 'data-unfold-icon-type', $unfold_button_icon_type);
			if($unfold_button_icon_type == 'svg'){
				$widget->add_render_attribute( '_wrapper', 'data-unfold-icon', $settings['widget_icon_unfold']['value']['url'] );
				ob_start();
				Icons_Manager::render_icon($settings['widget_icon_unfold']);
				$icon_html = ob_get_contents();
				ob_end_clean();
			}else{
				$widget->add_render_attribute( '_wrapper', 'data-unfold-icon', $settings['widget_icon_unfold']['value'] );
				ob_start();
				Icons_Manager::render_icon($settings['widget_icon_unfold']);
				$icon_html = ob_get_contents();
				ob_end_clean();

			}
			$fold_button_icon_type = isset($settings['widget_icon_fold']['value']['url']) ? 'svg' : 'icon';
			$widget->add_render_attribute( '_wrapper', 'data-fold-icon-type',  $fold_button_icon_type);
			if($fold_button_icon_type == 'svg'){
				$widget->add_render_attribute( '_wrapper', 'data-fold-icon', $settings['widget_icon_fold']['value']['url'] );
			}else{
				$widget->add_render_attribute( '_wrapper', 'data-fold-icon', $settings['widget_icon_fold']['value'] );
			}

			$button_str     =    "<div ". $widget->get_render_attribute_string( '_wrapper' ).">";
			$button_str     .=    "<a ".$widget->get_render_attribute_string( 'unfold_link' ).">";
			if($settings['widget_unfold_button_icon_position'] == 'before'){
				$button_str    .=    "<span class='eae-unfold-button-icon eae-unfold-align-icon-".$settings['widget_unfold_button_icon_position']."'>";
				$button_str    .=     $icon_html;
				$button_str    .=    "</span>";
			}
			$button_str    .=    "<span class='eae-unfold-button-text'>".$settings['widget_unfold_button_text']."</span>";
			if($settings['widget_unfold_button_icon_position'] == 'after'){
				$button_str    .=    "<span class='eae-unfold-button-icon eae-unfold-align-icon-".$settings['widget_unfold_button_icon_position']."'>";
				$button_str    .=    $icon_html;
				$button_str    .=    "</span>";
			}
			$button_str     .=    "</div>";
			$button_str     .=    "</a>";
			$content = $content . $button_str;
		}
		return $content;
	}

	function _print_template($template,$widget){
		if ( $widget->get_name() != 'section' && $widget->get_name() != 'column' ) {
			return $template;
		}

		$old_template = $template;
		ob_start();
		if($widget->get_name() == 'section'){ ?>
          <# if(settings.sc_enable_unfold == 'yes'){
				view.addRenderAttribute('wrapper', 'data-unfold-position', settings.unfold_position);
            } #>
		<?php }
		?>
		<# if(settings.sc_enable_unfold == 'yes'){
            unfold_icon_settings = settings.sc_icon_unfold;
            max_fold_height = {
                'desktop' : settings.sc_fold_max_height.size,
                'tablet' : settings.sc_fold_max_height_tablet.size,
                'mobile' : settings.sc_fold_max_height_mobile.size,
            };

            fold_icon_settings = settings.sc_icon_fold;
            view.addRenderAttribute('wrapper', 'class', ['eae-unfold-setting-data', 'eae-fold']);
            view.addRenderAttribute('wrapper', 'data-unfold-text', settings.sc_unfold_button_text);
            view.addRenderAttribute('wrapper', 'data-fold-text', settings.sc_fold_button_text);
            view.addRenderAttribute('wrapper', 'data-unfold-animation-speed', settings.sc_unfold_animation_speed.size);
            view.addRenderAttribute('wrapper', 'data-fold-height', JSON.stringify(max_fold_height));
            view.addRenderAttribute('wrapper', 'data-hover-animation', settings.sc_button_hover_animation);
            view.addRenderAttribute('wrapper', 'data-icon-pos', settings.sc_unfold_button_icon_position);
            if(unfold_icon_settings.value.hasOwnProperty('url')){
                view.addRenderAttribute('wrapper', 'data-unfold-icon-type', 'svg');
                view.addRenderAttribute('wrapper', 'data-unfold-icon', settings.sc_icon_unfold.value.url);
            }else{
                view.addRenderAttribute('wrapper', 'data-unfold-icon-type', 'icon');
                view.addRenderAttribute('wrapper', 'data-unfold-icon', settings.sc_icon_unfold.value);
            }
            if(fold_icon_settings.value.hasOwnProperty('url')){
                view.addRenderAttribute('wrapper', 'data-fold-icon-type', 'svg');
                view.addRenderAttribute('wrapper', 'data-fold-icon', settings.sc_icon_fold.value.url);
            }else{
                view.addRenderAttribute('wrapper', 'data-fold-icon-type', 'icon');
                view.addRenderAttribute('wrapper', 'data-fold-icon', settings.sc_icon_fold.value);
            }
        #>
            <div  {{{ view.getRenderAttributeString( 'wrapper' ) }}} ></div>
       <# } #>
		<?php
		$slider_content = ob_get_contents();
		ob_end_clean();
		$template = $slider_content . $old_template;
		return $template;
	}
	function _content_template($template,$widget){
		if(empty($template)){
			return $template;
		}
		$old_template = $template;
		ob_start();
		?>
		<# if(settings.widget_enable_unfold == 'yes'){
	        unfold_icon_settings = settings.widget_icon_unfold;
	        fold_icon_settings = settings.widget_icon_fold;
	        max_fold_height = {
	            'desktop' : settings.widget_fold_max_height.size,
	            'tablet' : settings.widget_fold_max_height_tablet.size,
	            'mobile' : settings.widget_fold_max_height_mobile.size,
	        };
			view.addRenderAttribute('wrapper', 'class', ['eae-unfold-setting-data', 'eae-fold']);
			view.addRenderAttribute('wrapper', 'data-unfold-text', settings.widget_unfold_button_text);
			view.addRenderAttribute('wrapper', 'data-fold-text', settings.widget_fold_button_text);
			view.addRenderAttribute('wrapper', 'data-unfold-animation-speed', settings.widget_unfold_animation_speed.size);
	        view.addRenderAttribute('wrapper', 'data-hover-animation', settings.widget_button_hover_animation);
			view.addRenderAttribute('wrapper', 'data-fold-height', JSON.stringify(max_fold_height));
			view.addRenderAttribute('wrapper', 'data-icon-pos', settings.widget_unfold_button_icon_position);
	        if(unfold_icon_settings.value.hasOwnProperty('url')){
	            view.addRenderAttribute('wrapper', 'data-unfold-icon-type', 'svg');
	            view.addRenderAttribute('wrapper', 'data-unfold-icon', settings.widget_icon_unfold.value.url);
	        }else{
	            view.addRenderAttribute('wrapper', 'data-unfold-icon-type', 'icon');
	            view.addRenderAttribute('wrapper', 'data-unfold-icon', settings.widget_icon_unfold.value);
	        }
	        if(fold_icon_settings.value.hasOwnProperty('url')){
	            view.addRenderAttribute('wrapper', 'data-fold-icon-type', 'svg');
	            view.addRenderAttribute('wrapper', 'data-fold-icon', settings.widget_icon_fold.value.url);
	        }else{
	            view.addRenderAttribute('wrapper', 'data-fold-icon-type', 'icon');
	            view.addRenderAttribute('wrapper', 'data-fold-icon', settings.widget_icon_fold.value);
	        }
			#>
			<div  {{{ view.getRenderAttributeString( 'wrapper' ) }}} ></div>
		<# } #>
		<?php
		$slider_content = ob_get_contents();
		ob_end_clean();
		$template = $slider_content . $old_template;
		return $template;
	}



	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
//AnimatedGradientBackground::instance();