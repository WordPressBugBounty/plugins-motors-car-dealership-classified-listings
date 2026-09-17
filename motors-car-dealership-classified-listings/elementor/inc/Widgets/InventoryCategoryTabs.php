<?php

namespace MotorsElementorWidgetsFree\Widgets;

use MotorsElementorWidgetsFree\MotorsElementorWidgetsFree;
use MotorsElementorWidgetsFree\Helpers\Helper;
use MotorsElementorWidgetsFree\Widgets\WidgetBase;

class InventoryCategoryTabs extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-inventory-category-tabs';
	}

	public function get_title() {
		return esc_html__( 'Inventory Category Tabs', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-listing-search-tabs';
	}

	public function get_style_depends() {
		return array( $this->get_name() );
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'section_content', esc_html__( 'General', 'stm_vehicles_listing' ) );

		$listing_categories = function_exists( 'stm_listings_attributes' ) ? stm_listings_attributes() : array();
		$category_options   = array();
		$default_taxonomy   = '';

		if ( ! empty( $listing_categories ) ) {
			foreach ( $listing_categories as $category ) {
				if ( empty( $category['slug'] ) || ! empty( $category['numeric'] ) ) {
					continue;
				}

				$category_options[ $category['slug'] ] = ! empty( $category['single_name'] ) ? $category['single_name'] : $category['slug'];

				if ( ! empty( $category['use_on_tabs'] ) && empty( $default_taxonomy ) ) {
					$default_taxonomy = $category['slug'];
				}
			}

			if ( empty( $default_taxonomy ) && ! empty( $category_options ) ) {
				$slugs            = array_keys( $category_options );
				$default_taxonomy = (string) reset( $slugs );
			}
		}

		$this->add_control(
			'taxonomy',
			array(
				'label'       => esc_html__( 'Listing Category', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => $category_options,
				'default'     => $default_taxonomy,
				'description' => esc_html__( 'Tabs filter inventory listings by the selected category. Place this widget on the inventory page.', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'show_all_tab',
			array(
				'label'   => esc_html__( 'Show All Tab', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'all_tab_label',
			array(
				'label'     => esc_html__( 'All Tab Label', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'All Types', 'stm_vehicles_listing' ),
				'condition' => array(
					'show_all_tab' => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'   => esc_html__( 'Hide Empty Terms', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'   => esc_html__( 'Show Listings Count', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_responsive_control(
			'tabs_align',
			array(
				'label'     => esc_html__( 'Alignment', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .stm-inventory-category-tabs' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'section_style', esc_html__( 'Tabs', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .stm-inventory-category-tabs__link',
			)
		);

		$this->add_responsive_control(
			'tab_padding',
			array(
				'label'      => esc_html__( 'Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-inventory-category-tabs__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_gap',
			array(
				'label'      => esc_html__( 'Gap', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-inventory-category-tabs' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tab_colors' );

		$this->start_controls_tab(
			'tab_colors_normal',
			array(
				'label' => esc_html__( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'tab_color',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-inventory-category-tabs__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-inventory-category-tabs__link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_colors_hover',
			array(
				'label' => esc_html__( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'tab_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-inventory-category-tabs__link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_bg_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-inventory-category-tabs__link:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_colors_active',
			array(
				'label' => esc_html__( 'Active', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'tab_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-inventory-category-tabs__item.is-active .stm-inventory-category-tabs__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_bg_active',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-inventory-category-tabs__item.is-active .stm-inventory-category-tabs__link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		Helper::stm_ew_load_template( 'elementor/Widgets/inventory-category-tabs', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {
	}
}
