<?php

namespace Motors_Elementor_Widgets_Free\Widgets\SingleListing\Classified;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;

class Price extends WidgetBase {
	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_SINGLE );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-single-listing-classified-price';
	}

	public function get_icon() {
		return 'stmew-price-tag';
	}

	public function get_title() {
		return esc_html__( 'Price Classified', 'stm_vehicles_listing' );
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'price_section', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'price_heading',
			array(
				'label' => __( 'The listing price value can be edited in<br />the Listing Manager > Prices section<br />individually for each single listing.', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'detailed_view',
			array(
				'label'       => __( 'Show Regural price', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => __( 'This setting works when the listing includes a discounted price. It will be shown with a strikethrough.', 'stm_vehicles_listing' ),
			),
		);

		$this->add_control(
			'show_custom_label',
			array(
				'label'     => __( 'Show price labels', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'detailed_view' => 'yes',
				),
			),
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'price_style', __( 'Style', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'regular_price_typography',
				'label'    => __( 'Regular Price Typography', 'stm_vehicles_listing' ),
				'exclude'  => array(
					'font_style',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'selector' => '{{WRAPPER}} .stm-listing-single-price-title .price.discounted-price .regular-price .value',
			)
		);

		$this->add_control(
			'regular_price_color',
			array(
				'label'     => __( 'Regular Price Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-listing-single-price-title .price.discounted-price .regular-price .value' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'sale_price_typography',
				'label'    => __( 'Sale Price Typography', 'stm_vehicles_listing' ),
				'exclude'  => array(
					'font_style',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'selector' => '{{WRAPPER}} .stm-listing-single-price-title .price.discounted-price .sale-price .value',
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => __( 'Sale Price Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-listing-single-price-title .price.discounted-price .sale-price .value' => 'color: {{VALUE}}',
				),
			)
		);

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		Helper::stm_ew_load_template( 'elementor/Widgets/single-listing/classified/price', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {
	}
}
