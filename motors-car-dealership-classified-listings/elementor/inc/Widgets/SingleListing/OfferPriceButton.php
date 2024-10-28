<?php

namespace Motors_Elementor_Widgets_Free\Widgets\SingleListing;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;

class OfferPriceButton extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		add_filter(
			'mew_include_offer_price_modal',
			function () {
				return true;
			}
		);

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_SINGLE );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-single-listing-offer-price';
	}

	public function get_title() {
		return esc_html__( 'Offer Price Button', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-currency-usd';
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'opb_content', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'opb_btn_label',
			array(
				'label'   => __( 'Label', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Make an Offer Price', 'stm_vehicles_listing' ),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'opb_styles', __( 'Style', 'stm_vehicles_listing' ) );

		$this->stm_start_ctrl_tabs( 'opb_btn_bg_style' );

		$this->stm_start_ctrl_tab(
			'opb_bg_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'opb_btn_bg',
			array(
				'label'     => __( 'Background', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'opb_icon_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-car_dealer-buttons a svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'opb_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'opb_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'opb_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-car_dealer-buttons a',
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'opb_bg_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'opb_btn_bg_hover',
			array(
				'label'     => __( 'Background', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'opb_icon_color_hover',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a:hover i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-car_dealer-buttons a:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'opb_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'opb_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'opb_box_shadow_hover',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-car_dealer-buttons a:hover',
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'opb_btn_bg_after',
			array(
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'solid',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'opb_typography',
				'label'     => __( 'Typography', 'stm_vehicles_listing' ),
				'separator' => 'before',
				'exclude'   => array(
					'font_family',
					'font_style',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'selector'  => '{{WRAPPER}} .stm-car_dealer-buttons a',
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
				'name'     => 'opb_border',
				'label'    => __( 'Border', 'stm_vehicles_listing' ),
				'exclude'  => array( 'color' ),
				'selector' => '{{WRAPPER}} .stm-car_dealer-buttons a',
			)
		);

		$this->add_control(
			'opb_btn_border_radius',
			array(
				'label'     => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => true,
				),
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'opb_btn_padding',
			array(
				'label'     => __( 'Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '17',
					'right'    => '25',
					'bottom'   => '17',
					'left'     => '25',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'opb_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 8,
						'max'  => 40,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .stm-car_dealer-buttons a svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'opb_icon_position',
			array(
				'label'     => __( 'Icon Position', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'row-reverse' => __( 'Left', 'stm_vehicles_listing' ),
					'row'         => __( 'Right', 'stm_vehicles_listing' ),
				),
				'default'   => 'row',
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a' => 'flex-direction: {{VALUE}};',
				),
			),
		);

		$this->add_control(
			'opb_icon_margin',
			array(
				'label'     => __( 'Icon Margin', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => true,
				),
				'selectors' => array(
					'{{WRAPPER}} .stm-car_dealer-buttons a i'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-car_dealer-buttons a img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Helper::stm_ew_load_template( 'elementor/Widgets/single-listing/offer-price', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {
	}

	public function motors_add_modal() {
		do_action( 'stm_listings_load_template', 'listings/modals/trade-offer' );
	}
}
