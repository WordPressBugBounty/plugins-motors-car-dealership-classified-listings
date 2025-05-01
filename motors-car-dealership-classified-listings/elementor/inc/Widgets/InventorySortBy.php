<?php

namespace Motors_Elementor_Widgets_Free\Widgets;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\Controls\ContentControls\HeadingControl;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;
use MotorsVehiclesListing\Plugin\MVL_Const;

class InventorySortBy extends WidgetBase {

	protected $wpcfto_settings = '';

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->wpcfto_settings = admin_url( '/admin.php?page=' . \MotorsVehiclesListing\Plugin\MVL_Const::FILTER_OPT_NAME . '#inventory_settings' );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
		if ( is_rtl() ) {
			$this->stm_ew_enqueue( self::get_name() . '-rtl', STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		}
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-inventory-sort-by';
	}

	public function get_title() {
		return esc_html__( 'Sort By', 'stm-vehicle-listings' );
	}

	public function get_icon() {
		return 'stmew-inventory-sort';
	}

	public function get_style_depends() {
		$widget_styles   = parent::get_style_depends();
		$widget_styles[] = 'motors-general-admin';
		$widget_styles[] = self::get_name() . '-rtl';

		return $widget_styles;
	}

	public function get_script_depends() {
		$depends = array(
			'jquery',
			'stmselect2',
			'app-select2',
			$this->get_name(),
		);

		return $depends;
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'sb_content', __( 'General', 'stm-vehicle-listings' ) );

		$this->add_control(
			'sb_divider_before',
			array(
				'type'  => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'solid',
			)
		);

		$this->add_control(
			'sb_heading',
			array(
				'label' => sprintf(
					'<a href="' . $this->wpcfto_settings . '" target="_blank">%s</a> <p style="display:block;color: #c8c8c8;font-weight: 400;font-size: 12px;margin-top: 10px;font-style: italic;">%s</p>',
					__( 'Custom Sort Options', 'stm-vehicle-listings' ),
					__( 'Set custom options for sorting listings in Motors Plugin Settings > Search Filters > Sort by custom fields.', 'stm-vehicle-listings' )
				),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->stm_end_control_section();

		$this->start_controls_section(
			'sb_style',
			array(
				'label' => esc_html__( 'Title Settings', 'stm-vehicle-listings' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'sb_title_typography',
				'label'    => __( 'Text Style', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .stm-sort-by-options > span',
			)
		);

		$this->add_control(
			'sb_title_color',
			array(
				'label'     => __( 'Text Color', 'stm-vehicle-listings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-sort-by-options > span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'sb_title_margin',
			array(
				'label'      => __( 'Margin', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-sort-by-options > span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sb_fields',
			array(
				'label' => esc_html__( 'Field Settings', 'stm-vehicle-listings' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'sb_fields_bg_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-select-sorting .select2-container--default .select2-selection--single' => 'background-color: {{VALUE}}!important;',
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting select' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'sb_fields_text_typography',
				'label'    => __( 'Text Style', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .stm-sort-by-options .stm-select-sorting .select2-container--default .select2-selection--single .select2-selection__rendered, {{WRAPPER}} .stm-sort-by-options .stm-select-sorting select, {{WRAPPER}} .stm-sort-by-options .stm-select-sorting:before, {{WRAPPER}} .stm-select-sorting .select2-container--default .select2-selection--single .select2-selection__arrow b, .select2-dropdown.inventory-sort-by .select2-results__options li',
				'exclude'  => array( 'line_height' ),
			)
		);

		$this->add_control(
			'sb_fields_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting .select2-container--default .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-select-sorting .select2-container--default .select2-selection--single .select2-selection__arrow b' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'sb_fields_padding',
			array(
				'label'      => __( 'Padding', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting .select2-container--default .select2-selection--single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sb_fields_height',
			array(
				'label'      => __( 'Height', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 20,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-select-sorting .select2-container--default .select2-selection--single' => 'height: {{SIZE}}px;',
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting select' => 'height: {{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'sb_fields_border',
				'label'    => __( 'Border', 'stm_vehicle_listings' ),
				'selector' => '{{WRAPPER}} .stm-select-sorting .select2-container--default .select2-selection--single, {{WRAPPER}} .stm-sort-by-options .stm-select-sorting select',
			)
		);

		$this->add_responsive_control(
			'sb_fields_border_radius',
			array(
				'label'      => __( 'Border Radius', 'stm_vehicle_listings' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-select-sorting .select2-container--default .select2-selection--single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-sort-by-options .stm-select-sorting select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->start_controls_section(
			'sb_dropdown',
			array(
				'label' => esc_html__( 'Dropdown Settings', 'stm-vehicle-listings' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'sb_dropdown_tabs' );

		$this->start_controls_tab( 'sb_dropdown_normal', array( 'label' => esc_html__( 'Normal', 'textdomain' ) ) );

		$this->add_responsive_control(
			'sb_dropdown_bg_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-sort-by' => 'background-color: {{VALUE}}!important;',
					'.select2-container--default .select2-dropdown.inventory-sort-by .select2-results__option--highlighted[aria-selected]' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_responsive_control(
			'sb_dropdown_bg_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-sort-by .select2-results__options li' => 'color: {{VALUE}};',
					'.select2-container--default .select2-dropdown.inventory-sort-by .select2-results__option--highlighted[aria-selected]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'sb_dropdown_hover', array( 'label' => esc_html__( 'Hover', 'textdomain' ) ) );

		$this->add_responsive_control(
			'sb_dropdown_bg_bg_hover_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-sort-by .select2-results__options li:hover' => 'background-color: {{VALUE}}!important;',
					'.select2-container--default .select2-dropdown.inventory-sort-by .select2-results__option[aria-selected=true]' => 'background-color: {{VALUE}}!important;',
					'.select2-container--default .select2-dropdown.inventory-sort-by .select2-results__option--highlighted[aria-selected]:hover' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_responsive_control(
			'sb_dropdown_bg_text_hover_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-sort-by .select2-results__options li:hover' => 'color: {{VALUE}}!important;',
					'.select2-container--default .select2-dropdown.inventory-sort-by .select2-results__option[aria-selected=true]' => 'color: {{VALUE}}!important;',
					'.select2-container--default .select2-dropdown.inventory-sort-by .select2-results__option--highlighted[aria-selected]:hover' => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->stm_end_ctrl_tabs();

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		Helper::stm_ew_load_template( 'elementor/Widgets/inventory-sort-by', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {

	}
}
