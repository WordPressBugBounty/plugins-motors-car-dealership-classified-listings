<?php

namespace Motors_Elementor_Widgets_Free\Widgets\SingleListing;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;

class Similar extends WidgetBase {
	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_SINGLE );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-single-listing-similar-listings';
	}

	public function get_title() {
		return esc_html__( 'Similar Listings', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-grid-view';
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'similar_content', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'similar_title',
			array(
				'label'   => __( 'Title', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Similar listing', 'stm_vehicles_listing' ),
			)
		);

		if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() ) {

			$this->add_control(
				'listing_type_heading',
				array(
					'label'     => esc_html__( 'Default Listing type', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

		}

		$this->add_control(
			'similar_taxonomies',
			array(
				'label'       => __( 'Show Similar By', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'description' => __( 'Enter slug of listing category', 'stm_vehicles_listing' ),
				'options'     => $this->motors_get_listing_taxonomies(),
				'multiple'    => true,
			)
		);

		if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() ) {

			$listing_types = Helper::stm_ew_get_multilisting_types();

			if ( $listing_types ) {
				foreach ( $listing_types as $slug => $typename ) {
					if ( apply_filters( 'stm_listings_post_type', 'listings' ) !== $slug ) {

						$this->add_control(
							'listing_type_' . $slug . '_heading',
							array(
								'label'     => esc_html( $typename ),
								'type'      => \Elementor\Controls_Manager::HEADING,
								'separator' => 'before',
							)
						);

						$this->add_control(
							'similar_taxonomies_' . $slug,
							array(
								'label'       => __( 'Show Similar By', 'stm_vehicles_listing' ),
								'type'        => \Elementor\Controls_Manager::SELECT2,
								'description' => __( 'Enter slug of listing category', 'stm_vehicles_listing' ),
								'options'     => Helper::stm_ew_multi_listing_search_filter_fields( $slug ),
								'multiple'    => true,
							)
						);

					}
				}
			}
		}

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'similar_start', __( 'Widget Title', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'similar_typography_title',
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
						'default' => '700',
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
				'selector'       => '{{WRAPPER}} .similar-listings .similar-listings-title',
			)
		);

		$this->add_control(
			'features_icon_color',
			array(
				'label'     => __( 'Label Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .similar-listings .similar-listings-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'label_border',
				'label'          => __( 'Label Border', 'stm_vehicles_listing' ),
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '4',
							'left'     => '0',
							'isLinked' => false,
						),
					),
					'color'  => array(),
				),
				'selector'       => '{{WRAPPER}} .similar-listings .similar-listings-title',
			)
		);

		$this->add_control(
			'label_padding',
			array(
				'label'     => __( 'Label Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '30',
					'right'    => '0',
					'bottom'   => '19',
					'left'     => '0',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .similar-listings .similar-listings-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'label_margin',
			array(
				'label'     => __( 'Label Margin', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '25',
					'left'     => '0',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .similar-listings .similar-listings-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'similar_item', __( 'Similar item', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'similar_item_title_typography',
				'label'    => __( 'Title Typography', 'stm_vehicles_listing' ),
				'exclude'  => array(
					'font_style',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'selector' => '{{WRAPPER}} .stm-similar-cars-units .stm-similar-car .right-unit .title',
			)
		);

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		Helper::stm_ew_load_template( 'elementor/Widgets/single-listing/similar', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {
	}

	private function motors_get_listing_taxonomies() {
		$filters = stm_listings_attributes(
			array(
				'key_by' => 'slug',
			)
		);

		$filter_fields = array();

		foreach ( $filters as $filter ) {
			$filter_fields[ $filter['slug'] ] = $filter['slug'];
		}

		return $filter_fields;
	}
}
