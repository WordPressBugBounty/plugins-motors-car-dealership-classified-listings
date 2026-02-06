<?php

namespace MotorsElementorWidgetsFree\Widgets;

use MotorsElementorWidgetsFree\MotorsElementorWidgetsFree;
use MotorsElementorWidgetsFree\Helpers\Helper;
use MotorsElementorWidgetsFree\Widgets\WidgetBase;

class LoginRegister extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_enqueue( self::get_name() );
	}

	public function get_categories(): array {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_CLASSIFIED );
	}

	public function get_name(): string {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-login-register';
	}

	public function get_title(): string {
		return esc_html__( 'Login Register', 'stm_vehicles_listing' );
	}

	public function get_icon(): string {
		return 'stmew-login-register';
	}

	public function get_script_depends() {
		return array( 'uniform', 'uniform-init', $this->get_name(), $this->get_admin_name() );
	}

	public function get_style_depends() {
		$widget_styles   = parent::get_style_depends();
		$widget_styles[] = 'uniform';
		$widget_styles[] = 'uniform-init';

		return $widget_styles;
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'section_content', esc_html__( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'terms_label',
			array(
				'label'   => esc_html__( 'Label', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'I accept the terms of the', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'   => esc_html__( 'Link Name', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'service', 'stm_vehicles_listing' ),
			)
		);

		$stm_me_wpcfto_pages_list = MotorsElementorWidgetsFree::motors_ew_get_all_pages();

		$this->add_control(
			'terms_page',
			array(
				'label'     => esc_html__( 'Terms Page', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $stm_me_wpcfto_pages_list,
				'condition' => array(
					'external_link!' => 'yes',
				),
			)
		);

		$this->add_control(
			'external_link',
			array(
				'label' => esc_html__( 'External Link', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'link_of_terms',
			array(
				'label'     => esc_html__( 'Link', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::URL,
				'default'   => array(
					'url'         => 'example.com',
					'is_external' => true,
					'nofollow'    => true,
				),
				'condition' => array(
					'external_link' => 'yes',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'section_style_general', esc_html__( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'form_border_radius',
			array(
				'label'     => __( 'Form Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-login-form form, 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form,
					{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl, 
					{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl
					' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'borders_color',
			array(
				'label'     => esc_html__( 'Form Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-register-form form' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .stm-social-login-wrap'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .stm-register-form form input:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'title_style', esc_html__( 'Forms Titles', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-login-register-form h3',
			)
		);

		//Here incorrect slug but it saves becouse clients already have this control
		$this->add_control(
			'labels_color',
			array(
				'label'     => esc_html__( 'Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form h3' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'labels_style', esc_html__( 'Labels', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => esc_html__( 'Typography', 'stm_vehicles_listing' ),
				'selector' => '
				{{WRAPPER}} .stm-login-register-form .motors-socials-head .motors-socials-head-text, 
				{{WRAPPER}} .stm-login-register-form .stm-register-form .form-group h4,
				{{WRAPPER}} .stm-login-register-form .stm-login-form form h4, 
				{{WRAPPER}} .stm-login-register-form .stm-login-form .form-checker .stm-forgot-password a, 
				{{WRAPPER}} .stm-register-form form .form-checker label span
				',
			)
		);

		$this->add_control(
			'label_margin',
			array(
				'label'     => __( 'Margin', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form .stm-register-form .form-group h4, {{WRAPPER}} .stm-login-register-form .stm-login-form form h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {
			$this->add_control(
				'socials_label_margin',
				array(
					'label'              => __( 'Socials Margin', 'stm_vehicles_listing' ),
					'type'               => \Elementor\Controls_Manager::DIMENSIONS,
					'allowed_dimensions' => array( 'top', 'bottom' ),
					'selectors'          => array(
						'{{WRAPPER}} .stm-login-register-form .stm-login-form .motors-socials-head-text' => 'margin: {{TOP}}{{UNIT}} auto {{BOTTOM}}{{UNIT}};',
					),
				)
			);
		}

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'inputs_style', esc_html__( 'Inputs', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'inputs_typography',
				'label'    => esc_html__( 'Typography', 'stm_vehicles_listing' ),
				'selector' => '
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text], 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number], 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel], 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email], 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search], 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password],
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text], 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number], 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel], 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email], 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search], 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password],
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]::placeholder,
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search]::placeholder, 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password]::placeholder
				',
			)
		);

		$this->add_control(
			'input_padding',
			array(
				'label'     => __( 'Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_border_radius',
			array(
				'label'     => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'buttons_style', esc_html__( 'Buttons', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'buttons_typography',
				'label'    => esc_html__( 'Typography', 'stm_vehicles_listing' ),
				'selector' => '
				{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=submit], 
				{{WRAPPER}} .stm-login-register-form .stm-register-form form .form-group-submit input[type=submit]
				',
			)
		);

		$this->add_control(
			'buttons_margin',
			array(
				'label'     => __( 'Margin', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=submit]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				),
			)
		);

		$this->add_control(
			'buttons_padding',
			array(
				'label'     => __( 'Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=submit], {{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'socials_buttons_typography',
					'label'    => esc_html__( 'Social Button Typography', 'stm_vehicles_listing' ),
					'selector' => '{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl',
				)
			);
			$this->add_control(
				'social_buttons_padding',
				array(
					'label'     => __( 'Social Button Padding', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::DIMENSIONS,
					'selectors' => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					),
				)
			);
			$this->add_control(
				'social_buttons_margin',
				array(
					'label'              => __( 'Social Button Margin', 'stm_vehicles_listing' ),
					'type'               => \Elementor\Controls_Manager::DIMENSIONS,
					'allowed_dimensions' => array( 'top', 'bottom' ),
					'selectors'          => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					),
				)
			);
		}

		$this->stm_start_ctrl_tabs( 'btn_style' );

		$this->stm_start_ctrl_tab(
			'btn_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} form input[type=submit]:not([disabled])' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form .form-checker label input[type=checkbox]:checked' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form .form-checker label input[type=checkbox]:checked' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox]:checked' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} form input[type=submit]:not([disabled])' => 'color: {{VALUE}};',
				),
			)
		);

		if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {
			$this->add_control(
				'social_button_background_color',
				array(
					'label'     => esc_html__( 'Social Button Background Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl' => 'background: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'social_button_text_color',
				array(
					'label'     => esc_html__( 'Social Button Text Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'social_button_border_color',
				array(
					'label'     => esc_html__( 'Social Button Border Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl' => 'border-color: {{VALUE}};',
					),
				)
			);
		}

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'btn_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'background_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} form input[type=submit]:hover:not([disabled])' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} form input[type=submit]:hover:not([disabled])' => 'color: {{VALUE}};',
				),
			)
		);

		if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {
			$this->add_control(
				'social_button_background_color_hover',
				array(
					'label'     => esc_html__( 'Social Button Background Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl:hover, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl:hover' => 'background: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'social_button_text_color_hover',
				array(
					'label'     => esc_html__( 'Social Button Text Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl:hover, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl:hover' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'social_button_border_color_hover',
				array(
					'label'     => esc_html__( 'Social Button Border Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-facebook-sl:hover, {{WRAPPER}} .motors-socials-inner .stm-social-providers a.motors-google-sl:hover' => 'border-color: {{VALUE}};',
					),
				)
			);
		}

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->stm_end_control_section();

		if ( $this->is_wsl_active() ) :

			$this->stm_start_style_controls_section( 'wsl_style', esc_html__( 'WordPress Social Login', 'stm_vehicles_listing' ) );

			$this->add_control(
				'wsl_background_color',
				array(
					'label'     => esc_html__( 'WSL Background Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .stm-social-login-wrap' => 'background-color: {{VALUE}};',
					),
					'separator' => 'before',
				)
			);

			$this->add_control(
				'wsl_text_color',
				array(
					'label'     => esc_html__( 'WSL Text Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => '#232628',
					'selectors' => array(
						'{{WRAPPER}} .stm-social-login-wrap' => 'color: {{VALUE}};',
					),
				)
			);

			$this->stm_end_control_section();

		endif;

		$this->stm_start_style_controls_section( 'sign_in_style', esc_html__( 'Sign In', 'stm_vehicles_listing' ) );

		$this->add_control(
			'sign_in_form_padding',
			array(
				'label'     => __( 'Form Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-login-form form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				),
			)
		);

		$this->add_control(
			'sign_in_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .stm-login-form form h4, {{WRAPPER}} .stm-login-register-form .stm-login-form .form-checker .stm-forgot-password a, {{WRAPPER}} .stm-login-register-form .stm-login-form form .form-checker label span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sign_in_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .stm-login-register-form .stm-login-form form .form-checker label input[type=checkbox]:checked::after' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sign_in_input_text_color',
			array(
				'label'     => esc_html__( 'Inputs Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]
					' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_in_input_placeholder_color',
			array(
				'label'     => esc_html__( 'Inputs Placeholder Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]::placeholder
					' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_in_input_border_color',
			array(
				'label'     => esc_html__( 'Inputs Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]
					' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sign_in_input_text_color',
			array(
				'label'     => esc_html__( 'Inputs Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=checkbox],
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]
					' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sign_in_input_placeholder_color',
			array(
				'label'     => esc_html__( 'Inputs Placeholder Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=checkbox]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password]::placeholder
					' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_in_input_background_color',
			array(
				'label'     => esc_html__( 'Inputs Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=password],
					{{WRAPPER}} .stm-login-register-form .stm-login-form form input[type=checkbox]
					' => 'background-color: {{VALUE}};',
				),
			)
		);

		if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {

			$this->add_control(
				'sign_in_social_label_color',
				array(
					'label'     => esc_html__( 'Social Label Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .stm-login-register-form .stm-login-form .motors-socials-head .motors-socials-head-text' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sign_in_social_separator_color',
				array(
					'label'     => esc_html__( 'Social Separator Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .stm-login-register-form .stm-login-form .motors-socials-head .motors-socials-head-text::after, 
						{{WRAPPER}} .stm-login-register-form .stm-login-form .motors-socials-head .motors-socials-head-text::before' => 'background-color: {{VALUE}};',
					),
				)
			);

		}

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'sign_up_style', esc_html__( 'Sign Up', 'stm_vehicles_listing' ) );

		$this->add_control(
			'sign_up_form_padding',
			array(
				'label'     => __( 'Form Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .stm-register-form form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				),
			)
		);

		$this->add_control(
			'sign_up_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .stm-register-form form, {{WRAPPER}} .stm-register-form form .form-checker label span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .stm-register-form form .text-muted' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_up_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .stm-register-form form' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox]:checked::after' => 'border-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_up_input_text_color',
			array(
				'label'     => esc_html__( 'Inputs Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox],
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password]
					' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_up_input_placeholder_color',
			array(
				'label'     => esc_html__( 'Inputs Placeholder Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password]::placeholder
					' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_up_input_border_color',
			array(
				'label'     => esc_html__( 'Inputs Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password],
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox]
					' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sign_up_input_text_color',
			array(
				'label'     => esc_html__( 'Inputs Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox],
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password]
					' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sign_up_input_placeholder_color',
			array(
				'label'     => esc_html__( 'Inputs Placeholder Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox]::placeholder,
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search]::placeholder, 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password]::placeholder
					' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'sign_up_input_background_color',
			array(
				'label'     => esc_html__( 'Inputs Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'after',
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=text], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=number], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=tel], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox],
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=email], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=search], 
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=password],
					{{WRAPPER}} .stm-login-register-form .stm-register-form form input[type=checkbox]
					' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'sign_up_secondary_checkbox_typography',
				'label'    => esc_html__( 'Secondary Checkbox Typography', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-register-form form #whatsapp-checker ~ span small, {{WRAPPER}} .stm-register-form form #whatsapp-checker ~ span',
			)
		);

		$this->add_control(
			'sign_up_links_color',
			array(
				'label'     => esc_html__( 'Links Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'
					{{WRAPPER}} .stm-login-register-form .stm-register-form form .form-group.form-checker span a, 
					{{WRAPPER}} .stm-login-register-form .stm-login-form form .form-group.form-checker span a
					' => 'color: {{VALUE}};',
				),
			)
		);

		if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {

			$this->add_control(
				'sign_up_social_label_color',
				array(
					'label'     => esc_html__( 'Social Label Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} .stm-login-register-form .stm-register-form .motors-socials-head .motors-socials-head-text' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sign_up_social_separator_color',
				array(
					'label'     => esc_html__( 'Social Separator Color', 'stm_vehicles_listing' ),
					'type'      => \Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .stm-login-register-form .stm-register-form .motors-socials-head .motors-socials-head-text::after, 
						{{WRAPPER}} .stm-login-register-form .stm-register-form .motors-socials-head .motors-socials-head-text::before' => 'background-color: {{VALUE}};',
					),
				)
			);

		}

		$this->stm_end_control_section();

	}

	protected function is_wsl_active() {
		return defined( 'WORDPRESS_SOCIAL_LOGIN_ABS_PATH' ) || function_exists( '_wsl__' );
	}

	protected function render_social_login() {

		if ( ! $this->is_wsl_active() ) {
			return;
		}

		global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

		$auth_mode             = 'login';
		$redirect_to           = wsl_get_current_url();
		$authenticate_base_url = add_query_arg(
			array(
				'action' => 'wordpress_social_authenticate',
				'mode'   => 'login',
			),
			site_url( 'wp-login.php', 'login_post' )
		);
		$social_icon_set       = get_option( 'wsl_settings_social_icon_set' );
		// wpzoom icons set, is shown by default
		if ( empty( $social_icon_set ) ) {
			$social_icon_set = 'wpzoom/';
		}

		$connect_with_label = _wsl__( get_option( 'wsl_settings_connect_with_label' ), 'wordpress-social-login' );

		// HOOKABLE:
		$connect_with_label = apply_filters( 'wsl_render_auth_widget_alter_connect_with_label', $connect_with_label );

		$assets_base_url = WORDPRESS_SOCIAL_LOGIN_PLUGIN_URL . 'assets/elementor/img/32x32/' . $social_icon_set . '/';
		// HOOKABLE:
		$assets_base_url = apply_filters( 'wsl_render_auth_widget_alter_assets_base_url', $assets_base_url );

		?>
		<div class="wp-social-login-widget">

			<div class="wp-social-login-connect-with"><?php echo esc_html( $connect_with_label ); ?></div>

			<div class="wp-social-login-provider-list">
				<?php
				// Widget::Authentication display
				$wsl_settings_use_popup = get_option( 'wsl_settings_use_popup' );

				// if a user is visiting using a mobile device, WSL will fall back to more in page
				$wsl_settings_use_popup = function_exists( 'wp_is_mobile' ) ? wp_is_mobile() ? 2 : $wsl_settings_use_popup : $wsl_settings_use_popup;

				$no_idp_used = true;

				// display provider icons
				foreach ( $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG as $item ) {
					$provider_id   = isset( $item['provider_id'] ) ? $item['provider_id'] : '';
					$provider_name = isset( $item['provider_name'] ) ? $item['provider_name'] : '';

					// provider enabled?
					if ( get_option( 'wsl_settings_' . $provider_id . '_enabled' ) ) {
						// restrict the enabled providers list
						if ( isset( $args['enable_providers'] ) ) {
							$enable_providers = explode( '|', $args['enable_providers'] ); // might add a couple of pico seconds

							if ( ! in_array( strtolower( $provider_id ), $enable_providers, true ) ) {
								continue;
							}
						}

						// build authentication url
						$authenticate_url = add_query_arg(
							array(
								'provider'    => $provider_id,
								'redirect_to' => rawurlencode( $redirect_to ),
							),
							$authenticate_base_url
						);

						// http://codex.wordpress.org/Function_Reference/esc_url
						$authenticate_url = esc_url( $authenticate_url );

						// in case, Widget::Authentication display is set to 'popup', then we overwrite 'authenticate_url'
						// > /assets/elementor/js/connect.js will take care of the rest
						if ( $wsl_settings_use_popup && 'test' !== $auth_mode ) {
							$authenticate_url = 'javascript:void(0);';
						}

						// HOOKABLE: allow user to rebuilt the auth url
						$authenticate_url = apply_filters( 'wsl_render_auth_widget_alter_authenticate_url', $authenticate_url, $provider_id, $auth_mode, $redirect_to, $wsl_settings_use_popup );

						// HOOKABLE: allow use of other icon sets
						$provider_icon_markup = apply_filters( 'wsl_render_auth_widget_alter_provider_icon_markup', $provider_id, $provider_name, $authenticate_url );

						if ( $provider_icon_markup !== $provider_id ) {
							echo wp_kses_post( $provider_icon_markup );
						} else {
							?>

							<a rel="nofollow" href="<?php echo esc_url( $authenticate_url ); ?>"
							title="<?php echo esc_attr( sprintf( _wsl__( 'Connect with %s', 'wordpress-social-login' ), $provider_name ) ); ?>"
							class="wp-social-login-provider wp-social-login-provider-<?php echo esc_attr( strtolower( $provider_id ) ); ?>"
							data-provider="<?php echo esc_attr( $provider_id ); ?>" role="button">
								<?php
								if ( 'none' === $social_icon_set ) {
									echo wp_kses_post( apply_filters( 'wsl_render_auth_widget_alter_provider_name', $provider_name ) );
								} else {
									?>
									<img alt="<?php echo esc_attr( $provider_name ); ?>"
										src="<?php echo esc_url( $assets_base_url . strtolower( $provider_id ) . '.png' ); ?>"
										aria-hidden="true" /><?php } ?>
							</a>
							<?php
						}

						$no_idp_used = false;
					}
				}

				// no provider enabled?
				if ( $no_idp_used ) {
					?>
					<p style="background-color: #FFFFE0;border:1px solid #E6DB55;padding:5px;">
						<?php _wsl_e( '<strong>WordPress Social Login is not configured yet</strong>.<br />Please navigate to <strong>Settings &gt; WP Social Login</strong> to configure this plugin.<br />For more information, refer to the <a rel="nofollow" href="http://miled.github.io/wordpress-social-login">online user guide</a>.', 'wordpress-social-login' ); ?>
						.
					</p>
					<?php
				}
				?>

			</div>

			<div class="wp-social-login-widget-clearing"></div>

		</div>
		<?php

	}

	protected function terms_page( $settings ): string {

		$terms_page = $settings['terms_label'];

		if ( 'yes' === $settings['external_link'] ) {

			$link_of_terms = esc_url( $settings['link_of_terms']['url'] );
			$is_external   = ! empty( $settings['link_of_terms']['is_external'] ) ? ' target="_blank"' : '';
			$nofollow      = ! empty( $settings['link_of_terms']['nofollow'] ) ? ' rel="nofollow"' : '';

			$link        = '<a href="' . $link_of_terms . '"' . $is_external . $nofollow . '>' . esc_html( $settings['link_text'] ) . '</a>';
			$terms_page .= ' ' . $link;

			return $terms_page;
		}

		if ( is_numeric( $settings['terms_page'] ) ) {
			$link_of_terms = get_permalink( (int) $settings['terms_page'] );
			$link          = '<a href="' . esc_url( $link_of_terms ) . '" target="_blank">' . esc_html( $settings['link_text'] ) . '</a>';
			$terms_page   .= ' ' . $link;
		}

		return $terms_page;
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$settings['__link_of_terms__'] = $this->terms_page( $settings );

		ob_start();
		$this->render_social_login();
		$settings['__social_login_html__'] = ob_get_clean();

		Helper::stm_ew_load_template( 'elementor/Widgets/login_register', STM_LISTINGS_PATH, $settings );

	}

}
