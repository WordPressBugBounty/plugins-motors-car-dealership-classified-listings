<?php

namespace MotorsElementorWidgetsFree\Widgets;

use MotorsElementorWidgetsFree\MotorsElementorWidgetsFree;
use MotorsElementorWidgetsFree\Helpers\Helper;
use MotorsElementorWidgetsFree\Widgets\WidgetBase;

class ContactFormSeven extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_enqueue( self::get_name() );
		if ( is_rtl() ) {
			$this->stm_ew_enqueue( self::get_name() . '-rtl', STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		}
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-contact-form-seven';
	}

	public function get_title() {
		return esc_html__( 'Contact form 7', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-mountain';
	}

	public function get_script_depends() {
		return array( 'uniform', 'uniform-init', 'stmselect2', 'app-select2', $this->get_admin_name() );
	}

	public function get_style_depends() {
		$widget_styles   = parent::get_style_depends();
		$widget_styles[] = 'uniform';
		$widget_styles[] = 'uniform-init';
		$widget_styles[] = 'stmselect2';
		$widget_styles[] = 'app-select2';
		$widget_styles[] = self::get_name() . '-rtl';

		return $widget_styles;
	}

	protected function register_controls() {

		$this->stm_start_content_controls_section( 'section_content', __( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'title',
			array(
				'label' => esc_html__( 'Title', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'   => __( 'Title Heading', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
					'h1' => __( 'Heading 1', 'stm_vehicles_listing' ),
					'h2' => __( 'Heading 2', 'stm_vehicles_listing' ),
					'h3' => __( 'Heading 3', 'stm_vehicles_listing' ),
					'h4' => __( 'Heading 4', 'stm_vehicles_listing' ),
					'h5' => __( 'Heading 5', 'stm_vehicles_listing' ),
					'h6' => __( 'Heading 6', 'stm_vehicles_listing' ),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Icon', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
			)
		);

		$this->add_control(
			'form_id',
			array(
				'label'   => __( 'Contact Form', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => Helper::stm_ew_get_cf7_select(),
			)
		);

		$this->add_control(
			'form_wide',
			array(
				'label'   => esc_html__( 'Wide', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->stm_end_control_section();

		/*Start style section*/
		$this->stm_start_style_controls_section( 'section_styles', __( 'Styles', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'svg_width',
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
					'size' => 27,
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-elementor-contact-form-seven .icon-title i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .stm-elementor-contact-form-seven .icon-title svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Title Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-elementor-contact-form-seven .icon-title .title',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Label Typography', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form .stm-single-car-contact .bottom > div label',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'inputs_typography',
				'label'    => __( 'Inputs Typography', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form textarea, {{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form textarea::placeholder, {{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form input:not([type=submit]), {{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form input:not([type=submit])::placeholder',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Button Typography', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form .stm-single-car-contact .wpcf7-submit',
			)
		);

		$this->add_control(
			'inputs_border_radius',
			array(
				'label'     => __( 'Inputs Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form .stm-single-car-contact .wpcf7-submit, {{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form .stm-single-car-contact input:not([type=submit]), {{WRAPPER}} .stm-elementor-contact-form-seven.stm_listing_car_form .stm-single-car-contact textarea, .stm-elementor-contact-form-seven.stm_listing_car_form .stm-single-car-contact input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'section_colors', __( 'Colors', 'stm_vehicles_listing' ) );

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-elementor-contact-form-seven .icon-title i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .stm-elementor-contact-form-seven .icon-title svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .stm-elementor-contact-form-seven .icon-title .title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		Helper::stm_ew_load_template( 'elementor/Widgets/contact-form-seven', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {
	}
}
