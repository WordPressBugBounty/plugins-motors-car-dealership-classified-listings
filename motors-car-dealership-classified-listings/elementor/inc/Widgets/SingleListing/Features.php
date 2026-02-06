<?php

namespace MotorsElementorWidgetsFree\Widgets\SingleListing;

use MotorsElementorWidgetsFree\MotorsElementorWidgetsFree;
use MotorsElementorWidgetsFree\Helpers\Helper;
use MotorsElementorWidgetsFree\Widgets\WidgetBase;

class Features extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue( self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V, array( 'jquery' ) );
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_SINGLE );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-single-listing-features';
	}

	public function get_title() {
		return esc_html__( 'Features', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-star';
	}

	protected function register_controls() {

		$this->stm_start_content_controls_section( 'title_content', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'features_type',
			array(
				'label'   => __( 'View Type', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'vertical'   => array(
						'title' => __( 'Vertical', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-ellipsis-v',
					),
					'horizontal' => array(
						'title' => __( 'Horizontal', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'default' => 'horizontal',
			)
		);

		$this->add_control(
			'features_rows',
			array(
				'label'     => __( 'Rows', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'2' => array(
						'title' => 2,
						'icon'  => 'eicon-gallery-grid',
					),
					'3' => array(
						'title' => 3,
						'icon'  => 'eicon-gallery-grid',
					),
					'4' => array(
						'title' => 4,
						'icon'  => 'eicon-gallery-grid',
					),
				),
				'default'   => '4',
				'condition' => array( 'features_type' => 'horizontal' ),
			)
		);

		$this->add_control(
			'features_icon',
			array(
				'label'            => __( 'Icon', 'stm_vehicles_listing' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'skin'             => 'inline',
				'label_block'      => false,
				'fa4compatibility' => 'icon',
			)
		);

		$this->add_control(
			'features_notice',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => '<div class="custom-widget-notice" style="display:flex;"> <p style="font-size: 11px; color:#9da5ae; line-height:1.4; font-style:italic;">' . __( 'The features shown are only for customization purposes. Actual listing features can be added when creating a listing.', 'stm_vehicles_listing' ) . '</p></div>',
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'features_style', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'icon_typography',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 8,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-single-listing-car-features i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .stm-single-listing-car-features svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'features_icon_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-single-listing-car-features ul li i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-single-listing-car-features ul li svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'title_typography',
				'label'          => __( 'Text Typography', 'stm_vehicles_listing' ),
				'exclude'        => array(
					'font_family',
					'font_style',
					'text_transform',
					'text_decoration',
					'letter_spacing',
					'word_spacing',
				),
				'fields_options' => array(
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => 13,
						),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 16,
						),
					),
				),
				'selector'       => '{{WRAPPER}} .stm-single-listing-car-features ul li span',
			)
		);

		$this->add_control(
			'feature_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .stm-single-listing-car-features ul li span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			Helper::stm_ew_load_template( 'elementor/Widgets/single-listing/features-editor-view', STM_LISTINGS_PATH, $settings );
		} else {
			Helper::stm_ew_load_template( 'elementor/Widgets/single-listing/features', STM_LISTINGS_PATH, $settings );
		}

	}

	protected function content_template() {
	}

	private function motors_features_list() {
		$features = get_terms(
			array(
				'taxonomy'   => 'stm_additional_features',
				'hide_empty' => false,
			)
		);

		$for_select = array();

		foreach ( $features as $feature ) {
			$for_select[ $feature->name ] = $feature->name;
		}

		return $for_select;
	}
}
