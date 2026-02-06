<?php

namespace MotorsElementorWidgetsFree\Widgets\SingleListing;

use MotorsElementorWidgetsFree\MotorsElementorWidgetsFree;
use MotorsElementorWidgetsFree\Helpers\Helper;
use MotorsElementorWidgetsFree\Widgets\WidgetBase;

class DealerPhoneNumber extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
		if ( is_rtl() ) {
			$this->stm_ew_enqueue( self::get_name() . '-rtl', STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		}
	}

	public function get_style_depends(): array {
		$widget_styles   = parent::get_style_depends();
		$widget_styles[] = self::get_name() . '-rtl';

		return $widget_styles;
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_SINGLE );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-single-listing-dealer-phone';
	}

	public function get_title() {
		return esc_html__( 'Author Phone Number', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-phone';
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'dpn_content', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'dpn_label',
			array(
				'label'   => __( 'Title', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Call Us', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'dpn_icon',
			array(
				'label'            => __( 'Icon', 'stm_vehicles_listing' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value' => 'motors-icons-sales_phone',
				),
			)
		);

		$this->add_control(
			'dpn_show_number',
			array(
				'label'   => __( 'Number Label', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Number', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'show_number',
			array(
				'label'   => __( 'Hide number', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'dpn_styles', __( 'Style', 'stm_vehicles_listing' ) );

		$this->stm_start_ctrl_tabs( 'dpn_btn_bg_style' );

		$this->stm_start_ctrl_tab(
			'dpn_bg_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'dpn_btn_bg',
			array(
				'label'     => __( 'Background', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_label_color',
			array(
				'label'     => __( 'Label Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#121e24',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone h5' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#121e24',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_show_number_text_color',
			array(
				'label'     => __( 'Show Number Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone .inner .stm-show-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_icon_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#45c655',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-dealer-info-unit.phone svg'      => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e0e3e7',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'dpn_box_shadow',
				'label'          => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => 'yes',
					),
					'box_shadow'      => array(
						'default' => array(
							'horizontal' => 0,
							'vertical'   => 0,
							'blur'       => 7,
							'spread'     => 1,
							'color'      => 'rgba(0, 0, 0, 0.09)',
						),
					),
				),
				'selector'       => '{{WRAPPER}} .stm-dealer-info-unit.phone',
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'dpn_bg_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'dpn_btn_bg_hover',
			array(
				'label'     => __( 'Background', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f8f8f8',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_label_color_hover',
			array(
				'label'     => __( 'Label Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#121e24',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone:hover h5' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#121e24',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_show_number_text_color_hover',
			array(
				'label'     => __( 'Show Number Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone .inner .stm-show-number:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_icon_color_hover',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#45c655',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone:hover i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-dealer-info-unit.phone:hover svg'      => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dpn_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e0e3e7',
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'dpn_box_shadow_hover',
				'label'          => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => 'yes',
					),
					'box_shadow'      => array(
						'default' => array(
							'horizontal' => 0,
							'vertical'   => 0,
							'blur'       => 7,
							'spread'     => 1,
							'color'      => 'rgba(0, 0, 0, 0.09)',
						),
					),
				),
				'selector'       => '{{WRAPPER}} .stm-dealer-info-unit.phone:hover',
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'dpn_btn_bg_after',
			array(
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'solid',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'dpn_typography_title',
				'label'          => __( 'Label Typography', 'stm_vehicles_listing' ),
				'separator'      => 'before',
				'exclude'        => array(
					'font_style',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'fields_options' => array(
					'font_size'      => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
					'font_weight'    => array(
						'default' => '400',
					),
					'line_height'    => array(
						'default' => array(
							'unit' => 'px',
							'size' => 22,
						),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
				),
				'selector'       => '{{WRAPPER}} .stm-dealer-info-unit.phone .inner h5',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'dpn_typography_phone',
				'label'          => __( 'Number Typography', 'stm_vehicles_listing' ),
				'separator'      => 'before',
				'exclude'        => array(
					'font_style',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'fields_options' => array(
					'font_size'      => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
					'font_weight'    => array(
						'default' => '400',
					),
					'line_height'    => array(
						'default' => array(
							'unit' => 'px',
							'size' => 22,
						),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
				),
				'selector'       => '{{WRAPPER}} .stm-dealer-info-unit.phone .inner .phone',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'dpn_typography_show_number',
				'label'          => __( 'Show Number Typography', 'stm_vehicles_listing' ),
				'separator'      => 'before',
				'exclude'        => array(
					'font_style',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'fields_options' => array(
					'font_size'      => array(
						'default' => array(
							'unit' => 'px',
							'size' => 10,
						),
					),
					'font_weight'    => array(
						'default' => '400',
					),
					'line_height'    => array(
						'default' => array(
							'unit' => 'px',
							'size' => 18,
						),
					),
					'text_transform' => array(
						'default' => 'capitalize',
					),
				),
				'selector'       => '{{WRAPPER}} .stm-dealer-info-unit.phone .inner .stm-show-number',
			)
		);

		$this->add_control(
			'border_before',
			array(
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'solid',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'dpn_border',
				'label'          => __( 'Border', 'stm_vehicles_listing' ),
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => true,
						),
					),
				),
				'exclude'        => array( 'color' ),
				'selector'       => '{{WRAPPER}} .stm-dealer-info-unit.phone',
			)
		);

		$this->add_control(
			'dpn_btn_border_radius',
			array(
				'label'     => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'isLinked' => true,
				),
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_after',
			array(
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'solid',
			)
		);

		$this->add_control(
			'dpn_btn_padding',
			array(
				'label'     => __( 'Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '13',
					'right'    => '17',
					'bottom'   => '13',
					'left'     => '17',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_styles',
			array(
				'label'     => __( 'Icon', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'dpn_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 8,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 26,
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .stm-dealer-info-unit.phone svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dpn_icon_margin',
			array(
				'label'     => __( 'Icon Margin', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '',
					'right'    => '17',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .stm-dealer-info-unit.phone i'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-dealer-info-unit.phone img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Helper::stm_ew_load_template( 'elementor/Widgets/single-listing/dealer-phone-number', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {
	}
}
