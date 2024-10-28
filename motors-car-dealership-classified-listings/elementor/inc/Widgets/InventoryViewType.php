<?php

namespace Motors_Elementor_Widgets_Free\Widgets;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;

class InventoryViewType extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-inventory-view-type';
	}

	public function get_title() {
		return esc_html__( 'View Type', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-framed-eye';
	}

	public function get_script_depends() {
		return array( 'inventory-view-type', 'motors-general-admin' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'inventory_view_type_content',
			array(
				'label' => esc_html__( 'View Type Switcher', 'stm_vehicle_listings' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'inventory_view_type_list_icon',
			array(
				'label' => esc_html__( 'List View Icon', 'stm_vehicle_listings' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'inventory_view_type_grid_icon',
			array(
				'label' => esc_html__( 'Grid View Icon', 'stm_vehicle_listings' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'inventory_view_type_style',
			array(
				'label' => esc_html__( 'Style', 'stm_vehicle_listings' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'inventory_view_type_padding',
			array(
				'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-view-by a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->start_controls_tabs( 'inventory_view_type_tabs' );

		$this->start_controls_tab(
			'inventory_view_type_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'stm_vehicle_listings' ),
			)
		);

		$this->add_control(
			'inventory_view_type_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-view-by a' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'inventory_view_type_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
					'em' => array(
						'min' => 0.1,
						'max' => 10,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-view-by a i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'inventory_view_type_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'stm_vehicle_listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-view-by a i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'inventory_view_type_border',
				'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .stm-view-by a',
			)
		);

		$this->add_control(
			'inventory_view_type_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-view-by a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inventory_view_type_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .stm-view-by a',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'inventory_view_type_active_tab',
			array(
				'label' => esc_html__( 'Active', 'stm_vehicle_listings' ),
			)
		);

		$this->add_control(
			'inventory_view_type_active_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-view-by a.active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'inventory_view_type_active_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'stm_vehicle_listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-view-by a.active i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'inventory_view_type_active_border',
				'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .stm-view-by a.active',
			)
		);

		$this->add_control(
			'inventory_view_type_active_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-view-by a.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inventory_view_type_active_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .stm-view-by a.active',
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Helper::stm_ew_load_template( 'elementor/Widgets/inventory-view-type', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {

	}
}
