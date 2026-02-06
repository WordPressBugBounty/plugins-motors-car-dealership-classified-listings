<?php

namespace MotorsElementorWidgetsFree\Widgets\HeaderFooter;

use MotorsElementorWidgetsFree\MotorsElementorWidgetsFree;
use MotorsElementorWidgetsFree\Helpers\Helper;
use MotorsElementorWidgetsFree\Widgets\WidgetBase;

class CompareButton extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_HEADER_FOOTER );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-compare-button';
	}

	public function get_title() {
		return esc_html__( 'Compare Button', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'fas fa-balance-scale';
	}

	protected function register_controls() {

		$this->stm_start_content_controls_section( 'compare_btn_content', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'compare_btn_text',
			array(
				'label'   => __( 'Button Text', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Compare', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'compare_btn_icon',
			array(
				'label'       => __( 'Icon', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fa-solid fa-scale-balanced',
					'library' => 'solid',
				),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'compare_btn_style', __( 'Style', 'stm_vehicles_listing' ) );

		$this->start_controls_tabs( 'tabs_compare_btn_style' );

		$this->start_controls_tab(
			'tab_compare_btn_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'compare_btn_background_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-compare-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'compare_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-compare-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motors-compare-button span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motors-compare-button svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_compare_btn_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'compare_btn_background_hover_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-compare-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'compare_btn_text_hover_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-compare-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motors-compare-button:hover span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motors-compare-button:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'compare_btn_style_divider',
			array(
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'compare_btn_typography',
				'label'    => __( 'Typography', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-compare-button span',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'compare_btn_border',
				'label'    => __( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-compare-button',
			)
		);

		$this->add_control(
			'compare_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-compare-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'compare_btn_padding',
			array(
				'label'      => __( 'Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-compare-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'compare_btn_icon_margin',
			array(
				'label'      => __( 'Icon Margin', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-compare-button i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motors-compare-button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'compare_btn_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px'  => array(
						'min' => 10,
						'max' => 100,
					),
					'em'  => array(
						'min' => 1,
						'max' => 10,
					),
					'rem' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-compare-button i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motors-compare-button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'compare_btn_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-compare-button',
			)
		);

		$this->stm_end_control_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Helper::stm_ew_load_template( 'elementor/Widgets/header-footer/compare-button', STM_LISTINGS_PATH, $settings );
	}

}
