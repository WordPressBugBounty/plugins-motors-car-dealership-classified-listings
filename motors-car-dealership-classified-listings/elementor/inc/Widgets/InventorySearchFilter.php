<?php

namespace Motors_Elementor_Widgets_Free\Widgets;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;

class InventorySearchFilter extends WidgetBase {

	public function __construct( array $data = array(), array $args = null ) {
		parent::__construct( $data, $args );

		$this->stm_ew_admin_register_ss( $this->get_admin_name(), self::get_name(), STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		$this->stm_ew_enqueue(
			self::get_name(),
			STM_LISTINGS_PATH,
			STM_LISTINGS_URL,
			STM_LISTINGS_V,
			array(
				'jquery',
				'elementor-frontend',
			)
		);
		if ( is_rtl() ) {
			$this->stm_ew_enqueue( self::get_name() . '-rtl', STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		}

	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-inventory-search-filter';
	}

	public function get_title() {
		return esc_html__( 'Search Filter', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-inventory-filter';
	}

	public function get_script_depends() {
		$depends = array(
			'uniform',
			'uniform-init',
			'jquery-effects-slide',
			'stmselect2',
			'app-select2',
			'bootstrap',
			$this->get_admin_name(),
			$this->get_name(),
		);

		if ( apply_filters( 'stm_enable_location', false ) ) {
			$depends[] = 'stm_gmap';
			$depends[] = 'stm-google-places';
		}

		return $depends;
	}

	public function get_style_depends() {
		$widget_styles   = parent::get_style_depends();
		$widget_styles[] = 'motors-general-admin';
		$widget_styles[] = self::get_name() . '-rtl';
		$widget_styles[] = 'uniform';
		$widget_styles[] = 'uniform-init';
		$widget_styles[] = 'jquery-effects-slide';
		$widget_styles[] = 'stmselect2';
		$widget_styles[] = 'app-select2';
		$widget_styles[] = 'bootstrap';

		return $widget_styles;
	}

	protected function register_controls() {
		$this->stm_start_content_controls_section( 'isf_header_content', __( 'Header Settings', 'stm_vehicles_listing' ) );

		$this->add_control(
			'search_options_icon',
			array(
				'label'            => __( 'Icon', 'stm_vehicles_listing' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'skin'             => 'inline',
				'label_block'      => false,
				'fa4compatibility' => 'icon',
			)
		);

		$this->add_control(
			'isf_title',
			array(
				'label'   => __( 'Title', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search Options', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'isf_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'left'  => 'Left',
					'right' => 'Right',
				),
				'default' => 'left',
			)
		);
		$this->stm_end_control_section();

		$this->stm_start_content_controls_section( 'isf_fields_content', __( 'Custom Fields Settings', 'stm_vehicles_listing' ) );

		if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() ) {

			$this->add_control(
				'post_type',
				array(
					'label'   => __( 'Listing Type', 'stm_vehicles_listing' ),
					'type'    => 'select',
					'options' => Helper::stm_ew_multi_listing_types(),
					'default' => 'listings',
				),
			);

		}
		if ( ! stm_is_multilisting() ) {

			$this->add_control(
				'sb_heading',
				array(
					'label' => $this->motors_selected_filters(),
					'type'  => \Elementor\Controls_Manager::HEADING,
				)
			);

		} else {
			$listing_types = Helper::stm_ew_multi_listing_types();

			if ( $listing_types ) {
				foreach ( $listing_types as $slug => $typename ) {

					$this->add_control(
						'sb_heading_' . $slug,
						array(
							'label'     => $this->motors_selected_filters( $slug ),
							'type'      => \Elementor\Controls_Manager::HEADING,
							'condition' => array(
								'post_type' => $slug,
							),
						)
					);

				}
			}
		}

		$this->add_control(
			'isf_price_single',
			array(
				'label'       => __( 'Show Separate Price Block', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'On', 'stm_vehicles_listing' ),
				'label_off'   => esc_html__( 'Off', 'stm_vehicles_listing' ),
				'default'     => '',
				'description' => esc_html__( 'Display the total price in a separate block below.', 'stm_vehicles_listing' ),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_content_controls_section( 'isf_content', __( 'Buttons Settings', 'stm_vehicles_listing' ) );

		$this->add_control(
			'reset_btn_heading',
			array(
				'label' => __( 'Reset Button', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'reset_btn_icon',
			array(
				'label'            => __( 'Icon', 'stm_vehicles_listing' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'skin'             => 'inline',
				'label_block'      => false,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value' => 'motors-icons-reset',
				),
			)
		);

		$this->add_control(
			'reset_btn_label',
			array(
				'label'   => __( 'Label', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Reset All', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'reset_btn_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_mobile_btn_results_text_heading',
			array(
				'label' => __( 'Show Result Button', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'isf_mobile_results_btn_text',
			array(
				'label'       => esc_html__( 'Button Text', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Show' ) . ' {{total}}',
				'default'     => __( 'Show' ) . ' {{total}}' . __( ' Cars' ),
				'description' => esc_html__( 'The button appears in mobile view, with the dynamic {{total}} value changing based on the number of listings found. It can also be removed to show static text for the filter button.', 'stm_vehicles_listing' ),
			)
		);
		$this->add_control(
			'mobile_content_btn_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_filter_section', __( 'Main Filter Settings', 'stm_vehicles_listing' ) );

		$this->add_responsive_control(
			'isf_main_box_padding',
			array(
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'label'     => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'default'   => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar'                       => 'padding: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .row-pad-top-24'       => 'padding-top: {{TOP}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-entry-header' => 'margin-right: -{{RIGHT}}{{UNIT}}; margin-left: -{{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_main_box_top_space',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'textdomain' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
					'em',
					'rem',
					'custom',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'isf_general_bg',
				'label'    => __( 'Background Color', 'stm_vehicles_listing' ),
				'types'    => array( 'classic', 'gradient', 'image' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_general_border',
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar',
			)
		);

		$this->add_control(
			'isf_general_border_radius',
			array(
				'label'     => __( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-entry-header' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_general_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > .filter-sidebar',
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_header_style_section', __( 'Header Settings', 'stm_vehicles_listing' ) );

		$this->add_control(
			'isf_box_padding',
			array(
				'label'     => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => '26',
					'right'    => '22',
					'bottom'   => '21',
					'left'     => '22',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header'                          => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row .stm-accordion-single-unit > a:not(.collapsed)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .stm-filter-links .stm-accordion-single-unit > a:not(.collapsed)'   => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 8,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 29,
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_icon_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_title_text_color',
			array(
				'label'     => __( 'Title Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header .h4'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row .sidebar-entry-header-mobile .h4' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_title_typography',
				'label'    => __( 'Title Text Style', 'stm_elementor_widgets' ),
				'selector' => '{{WRAPPER}} .classic-filter-row .sidebar-entry-header .h4',
			)
		);

		$this->add_responsive_control(
			'isf_title_bottom_space',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'textdomain' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
					'em',
					'rem',
					'custom',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-entry-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_main_section', __( 'Custom Fields Settings', 'stm_vehicles_listing' ) );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_fields_typography',
				'label'    => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .select2-container--default .select2-selection--single .select2-selection__rendered, {{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input',
			)
		);

		$this->stm_start_ctrl_tabs(
			'isf_fields_style',
		);

		$this->stm_start_ctrl_tab(
			'isf_field_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_general_select_color',
			array(
				'label'     => esc_html__( 'Background Color', 'motors-elementor-settings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#eceff3',
				'selectors' => array(
					'{{WRAPPER}} .filter-sidebar select' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .filter-sidebar input'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered' => 'background-color: {{VALUE}};',
					'.stm-template-car_dealer_two_elementor.no_margin {{WRAPPER}} .classic-filter-row .search-filter-form .filter-sidebar .row-pad-top-24 .stm-slider-filter-type-unit' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .filter-sidebar .select2-container--focus' => 'background-color: {{VALUE}};',
					'.select2-container--default .filter-select .select2-results__option--highlighted[aria-selected]' => 'background-color: {{VALUE}} !important;',
					'.select2-container--default .filter-select .select2-results__option[aria-selected=true]' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar' => '--location-field-bg-color: {{VALUE}};',
					'{{WRAPPER}} .filter-sidebar .select2-container--default .select2-selection--multiple' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]' => 'background-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar select' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar input' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--multiple' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_select_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.stm-template-car_dealer_two_elementor.no_margin {{WRAPPER}} .classic-filter-row .filter-sidebar .row-pad-top-24 .stm-slider-filter-type-unit .clearfix .stm-current-slider-labels' => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered'                        => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--multiple .select2-selection__rendered'                      => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__arrow b'                         => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--multiple'                                                   => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar select'                                                                                                     => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]'                                                                                           => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]::placeholder'                                                                              => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number]'                                                                                         => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search]'                                                                                         => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search]::placeholder'                                                                            => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .stm-location-search-unit:before'                                                                           => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]'                                                                                           => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number]::placeholder'                                                                            => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__arrow b'  => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--multiple'                            => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar select'                                                                              => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar input[type=text]'                                                                    => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar input[type=number]'                                                                  => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar input::placeholder'                                                                  => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar input[type=search]'                                                                  => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar h5.pull-left'                                                                        => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_field_border',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '
					{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered,
					{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--multiple,
					{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar select,
					{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text],
					{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number],
					{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search],
					.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered,
					.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--multiple,
					.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar select,
					.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar input[type=text],
					.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar input[type=number]
				',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'isf_field_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--multiple'                            => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar select'                                                                              => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]'                                                                    => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number]'                                                                  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search]'                                                                  => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar .select2-container--default .select2-selection--multiple'                            => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar select'                                                                              => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar input[type=text]'                                                                    => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter-sidebar input[type=number]'                                                                  => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_field_active',
			array(
				'label' => __( 'Active', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_general_select_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'motors-elementor-settings' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#eceff3',
				'selectors' => array(
					'{{WRAPPER}} .filter-sidebar select:focus'                                                                                                 => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .filter-sidebar input:focus'                                                                                                  => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple'                            => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]:focus'                                           => 'background-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile.mobile .filter-sidebar select:focus'                                                                                                 => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile.mobile .filter-sidebar input:focus'                                                                                                  => 'background-color: {{VALUE}} !important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile.mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile.mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple'                            => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_select_text_color_active',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple'                            => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar select:focus'                                                                                                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]:focus'                                                                                       => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number]:focus'                                                                                     => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search]:focus'                                                                                     => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple'                            => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar select:focus'                                                                                                 => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=text]:focus'                                                                                       => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=number]:focus'                                                                                     => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=search]:focus'                                                                                     => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_field_border_active',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '
				{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]:focus,
				{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar select:focus,
				{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple,
				{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input:focus,
				{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered,
				.classic-filter-row.motors-elementor-widget.mobile-filter-row .filter.filter-sidebar input:focus,
				{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number]:focus,
				{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search]:focus,
				.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered,
				.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple:not,
				.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar select:focus,
				.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=text]:focus,
				.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=number]:focus,
				.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=search]:focus',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'isf_field_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered'               => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple'                                          => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar select:focus'                                                                                                               => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]:focus'                                                                                                     => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number]:focus'                                                                                                   => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search]:focus'                                                                                                   => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--single .select2-selection__rendered' => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .select2-container--default.select2-container--focus .select2-selection--multiple'                            => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar select:focus'                                                                                                 => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=text]:focus'                                                                                       => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=number]:focus'                                                                                     => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar input[type=search]:focus'                                                                                     => 'border-color: {{VALUE}}!important;',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_responsive_control(
			'isf_field_border_radius',
			array(
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default'                                                         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--single'                              => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .select2-container--default .select2-selection--multiple'                            => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar select'                                                                              => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=text]'                                                                    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=number]'                                                                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar input[type=search]'                                                                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar .select2-container--default'                                                         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar .select2-container--default .select2-selection--single'                              => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar .select2-container--default .select2-selection--single .select2-selection__rendered' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar .select2-container--default .select2-selection--multiple'                            => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar select'                                                                              => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar input[type=text]'                                                                    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar input[type=number]'                                                                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile .filter.filter-sidebar input[type=search]'                                                                  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_dropdown_divider_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_dropdown_heading',
			array(
				'label' => esc_html__( 'Dropdown', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->start_controls_tabs( 'isf_dropdown_tabs' );

		$this->start_controls_tab( 'isf_dropdown_normal', array( 'label' => esc_html__( 'Normal', 'textdomain' ) ) );

		$this->add_responsive_control(
			'isf_dropdown_bg_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-search-filter .select2-results__options li' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_dropdown_bg_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-search-filter' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'isf_dropdown_hover', array( 'label' => esc_html__( 'Hover', 'textdomain' ) ) );

		$this->add_responsive_control(
			'isf_dropdown_bg_text_hover_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-container--default .filter-select.inventory-search-filter .select2-results__option--highlighted[aria-selected]' => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_responsive_control(
			'isf_dropdown_bg_bg_hover_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-container--default .filter-select.inventory-search-filter .select2-results__option--highlighted[aria-selected]' => 'background-color: {{VALUE}}!important;',
					'.select2-container--default .filter-select.inventory-search-filter .select2-results__option[aria-selected=true]' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->end_controls_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_responsive_control(
			'isf_dropdown_bg_input_color',
			array(
				'label'     => __( 'Search Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-search-filter input.select2-search__field' => 'background-color: {{VALUE}}!important;',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'isf_dropdown_bg_search_input_color',
			array(
				'label'     => __( 'Search Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.select2-dropdown.inventory-search-filter input.select2-search__field::focus' => 'color: {{VALUE}}!important;',
					'.select2-dropdown.inventory-search-filter input.select2-search__field'        => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'isf_dropdown_divider_after',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_slider_heading',
			array(
				'label' => esc_html__( 'Range Slider', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_slider_text_typography',
				'label'    => __( 'Title Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .stm-slider-filter-type-unit .pull-left, .classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .stm-slider-filter-type-unit .pull-left',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_slider_text_value_typography',
				'label'    => __( 'Value Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .stm-slider-filter-type-unit .stm-current-slider-labels, .classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .stm-slider-filter-type-unit .stm-current-slider-labels',
			)
		);

		$this->add_responsive_control(
			'isf_slider_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar h5.pull-left'                                            => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar h5'                                                      => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .stm-slider-filter-type-unit .pull-left'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .stm-slider-filter-type-unit .stm-current-slider-labels' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar h5.pull-left'                                            => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar h5'                                                      => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .filter-sidebar .stm-slider-filter-type-unit .pull-left'                 => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-slider-filter-type-unit .stm-current-slider-labels'                 => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .stm-slider-filter-type-unit h5.pull-left'       => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .stm-multiple-select.stm_additional_features h5' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_slider_range-color',
			array(
				'label'     => esc_html__( 'Slider Control Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row .ui-slider .ui-slider-range'                                               => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row .ui-slider .ui-slider-handle:after'                                        => 'background-color: {{VALUE}};',
					'.classic-filter-row.mobile-filter-row .ui-slider .ui-slider-range'                                         => 'background-color: {{VALUE}};',
					'.classic-filter-row.mobile-filter-row .ui-slider .ui-slider-handle:after'                                  => 'background-color: {{VALUE}};',
					'.stm-template-car_dealer_two_elementor {{WRAPPER}} .classic-filter-row .ui-slider .ui-slider-range'        => 'background-color: {{VALUE}}!important;',
					'.stm-template-car_dealer_two_elementor {{WRAPPER}} .classic-filter-row .ui-slider .ui-slider-handle:after' => 'background-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .ui-slider .ui-slider-range' => 'background-color: {{VALUE}} !important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .ui-slider .ui-slider-handle:after' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'isf_slider_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_field_title_heading',
			array(
				'label' => esc_html__( 'Field Title Settings', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_field_title_typography',
				'label'    => __( 'Title Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .form-group h5',
			)
		);

		$this->add_responsive_control(
			'isf_field_title_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .form-group h5'                                                           => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter-sidebar .stm-search_keywords h5' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_1main_section', __( 'Reset Button Settings', 'stm_vehicles_listing' ) );

		$this->add_control(
			'isf_btn_heading',
			array(
				'label'     => __( 'Reset Button', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_control(
			'text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => __( 'Left', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-align-start-h',
					),
					'center' => array(
						'title' => __( 'Center', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-align-center-h',
					),
					'end'    => array(
						'title' => __( 'Right', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-align-end-h',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_button_padding',
			array(
				'label'      => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'default'    => array(
					'top'    => '17',
					'right'  => '28',
					'bottom' => '15',
					'left'   => '28',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_btn_tabs' );

		$this->stm_start_ctrl_tab(
			'isf_btn_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_btn_border',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button',
				'default'  => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'isf_btn_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_btn_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_btn_box_shadow',
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'isf_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_btn_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_btn_border_hover',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button:hover',
				'default'  => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'isf_btn_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_btn_bg_hover',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_btn_box_shadow_hover',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button:hover',
			)
		);

		$this->add_control(
			'isf_btn_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'#main {{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'isf_btn_typography',
				'label'          => __( 'Text Style', 'stm_elementor_widgets' ),
				'selector'       => '{{WRAPPER}} .sidebar-action-units .button span',
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
							'size' => 14,
						),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
				),
			)
		);

		$this->add_control(
			'isf_reset_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 8,
						'max'  => 30,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 17,
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .filter-sidebar .sidebar-action-units a.button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'isf_reset_icon_margin',
			array(
				'label'      => __( 'Icon Margin', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '6',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .sidebar-action-units .button i'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					'{{WRAPPER}} .sidebar-action-units .button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_price_content', __( 'Separate Price Block', 'stm_vehicles_listing' ) );

		$this->add_control(
			'isf_second_label_box_price',
			array(
				'label' => __( 'Title Box', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_second_label_typography_price',
				'label'    => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-accordion-single-unit.price a.title h5, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title h5',
			)
		);

		$this->add_responsive_control(
			'isf_second_label_color_price',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-accordion-single-unit.price a.title h5' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-accordion-single-unit.price a.title h5'               => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_label_bg_color_price',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-accordion-single-unit.price a.title' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-accordion-single-unit.price a.title'               => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_second_label_border_price',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price > a.title, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title',
				'exclude'  => array( 'color' ),
				'default'  => array(
					'top'      => '3',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_label_border_color_price',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price > a.title' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-accordion-single-unit.price a.title'               => 'border-color: {{VALUE}};',
				),
				'default'   => '#232628',
			)
		);

		$this->add_responsive_control(
			'isf_second_label_border_radius_price',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price > a.title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-accordion-single-unit.price a.title'               => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_collapse_heading_price',
			array(
				'label' => __( 'Toggle Color', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_collapse_indicator_price' );

		$this->stm_start_ctrl_tab(
			'isf_collapse_indicator_normal_price',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_collapse_indicator_bg_price',
			array(
				'label'     => __( 'Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-accordion-single-unit.price a.title span'       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-accordion-single-unit.price a.title span:after' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-accordion-single-unit.price a.title span'                     => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-accordion-single-unit.price a.title span:after'               => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_collapse_indicator_hover_price',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_collapse_indicator_hover_bg_price',
			array(
				'label'     => __( 'Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6c98e1',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-accordion-single-unit.price a.title:hover span'       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-accordion-single-unit.price a.title:hover span:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'isf_second_label_divider_price',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_price_area_single',
			array(
				'label'       => __( 'Container Settings', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::HEADING,
				'description' => __( 'If you want to use single price field, please enable this option.', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_price_area_bg_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_price_area_border',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content',
				'exclude'  => array(
					'color',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_area_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_area_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_space_bottom_area',
			array(
				'label'      => __( 'Bottom Spacing', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_price_area_box_shadow',
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_second_padding_price_area',
			array(
				'label'      => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper'         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_price_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_price_heading',
			array(
				'label' => __( 'Price Fields', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'isf_price_slider_color',
			array(
				'label'     => __( 'Slider Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper .ui-slider .ui-slider-range'                                               => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper .ui-slider .ui-slider-handle:after'                                        => 'background-color: {{VALUE}};',
					'.classic-filter-row.mobile-filter-row .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper .ui-slider .ui-slider-range'                                         => 'background-color: {{VALUE}};',
					'.classic-filter-row.mobile-filter-row .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper .ui-slider .ui-slider-handle:after'                                  => 'background-color: {{VALUE}};',
					'.stm-template-car_dealer_two_elementor {{WRAPPER}} .classic-filter-row .stm-accordion-single-unit.price .ui-slider .ui-slider-range'        => 'background-color: {{VALUE}}!important;',
					'.stm-template-car_dealer_two_elementor {{WRAPPER}} .classic-filter-row .stm-accordion-single-unit.price .ui-slider .ui-slider-handle:after' => 'background-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-accordion-single-unit.price .ui-slider .ui-slider-range' => 'background-color: {{VALUE}} !important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-accordion-single-unit.price .ui-slider .ui-slider-handle:after' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_price_tabs' );

		$this->stm_start_ctrl_tab(
			'isf_price_normal',
			array(
				'label'     => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'isf_price_border',
				'label'     => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector'  => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input',
				'exclude'   => array(
					'color',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_border_color_input',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'isf_price_single' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_bg_color_input',
			array(
				'label'     => __( 'Input Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_text_color_input',
			array(
				'label'     => __( 'Input Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_price_hover',
			array(
				'label'     => __( 'Active', 'stm_vehicles_listing' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'      => 'isf_price_border_active',
				'label'     => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector'  => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input:focus',
				'exclude'   => array(
					'color',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_border_color_active',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_border_radius_active',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'isf_price_single' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_bg_color_input_active',
			array(
				'label'     => __( 'Input Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_price_text_color_input_active',
			array(
				'label'     => __( 'Input Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-single-unit.price .stm-accordion-content .stm-accordion-content-wrapper input:focus::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_secondary_block_style', __( 'Checkbox Filter', 'stm_vehicles_listing' ) );

		$this->add_control(
			'isf_second_label_box',
			array(
				'label' => __( 'Title Box', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_second_label_typography',
				'label'    => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-listing-directory-checkboxes a.title h5, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title h5',
			)
		);

		$this->add_responsive_control(
			'isf_second_label_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-listing-directory-checkboxes a.title h5' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title h5'               => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_label_bg_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-listing-directory-checkboxes a.title' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title'               => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_second_label_border',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes > a.title, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title',
				'exclude'  => array( 'color' ),
				'default'  => array(
					'top'      => '3',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_label_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes > a.title' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title'               => 'border-color: {{VALUE}};',
				),
				'default'   => '#232628',
			)
		);

		$this->add_responsive_control(
			'isf_second_label_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes > a.title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title'               => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_collapse_heading',
			array(
				'label' => __( 'Toggle Color', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_collapse_indicator' );

		$this->stm_start_ctrl_tab(
			'isf_collapse_indicator_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_collapse_indicator_bg',
			array(
				'label'     => __( 'Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-listing-directory-checkboxes a.title span'       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-listing-directory-checkboxes a.title span:after' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title span'                     => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile .stm-listing-directory-checkboxes a.title span:after'               => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_collapse_indicator_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_collapse_indicator_hover_bg',
			array(
				'label'     => __( 'Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6c98e1',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-listing-directory-checkboxes a.title:hover span'       => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form .stm-listing-directory-checkboxes a.title:hover span:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'isf_second_area_heading_divider_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_second_area_heading',
			array(
				'label' => esc_html__( 'Container Settings', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'isf_second_checkbox_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content .content' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-listing-directory-checkboxes .stm-accordion-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_second_border',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content .content, .classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-listing-directory-checkboxes .stm-accordion-content',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'isf_second_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content .content' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-listing-directory-checkboxes .stm-accordion-content' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-listing-directory-checkboxes .stm-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_margin',
			array(
				'label'      => __( 'Bottom Spacing', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 10,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content .content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-listing-directory-checkboxes .stm-accordion-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_secondary_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content .content, .classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-listing-directory-checkboxes .stm-accordion-content',
			)
		);

		$this->add_responsive_control(
			'isf_second_padding_checkbox',
			array(
				'label'      => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-listing-directory-checkboxes .stm-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_second_checkbox_label_heading_diver_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_second_checkbox_label_heading',
			array(
				'label' => esc_html__( 'Item Settings', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'isf_checkbox_label_typography',
				'label'     => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector'  => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label span, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label span',
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_label_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label span' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_label_bg_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-content-wrapper .stm-option-label' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_label_padding',
			array(
				'label'      => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-content-wrapper .stm-option-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-option-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_checkbox_label_border',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-content-wrapper .stm-option-label, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label',
				'default'  => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_label_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-content-wrapper .stm-option-label' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_label_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-accordion-content-wrapper .stm-option-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-option-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_label_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => __( 'Left', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-align-start-h',
					),
					'center' => array(
						'title' => __( 'Center', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-align-center-h',
					),
					'end'    => array(
						'title' => __( 'Right', 'stm_vehicles_listing' ),
						'icon'  => 'eicon-align-end-h',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper' => 'justify-content: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_checkbox_label_settings_heading',
			array(
				'label' => esc_html__( 'Checkbox Settings', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_checkbox_label_tabs' );

		$this->stm_start_ctrl_tab(
			'isf_checkbox_label_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_normal_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_normal_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_checkbox_label_hover',
			array(
				'label' => __( 'Active', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_active_color',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]:checked' => 'background-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]:checked' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_active_icon_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]:checked::after' => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]:checked::after' => 'border-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_responsive_control(
			'isf_checkbox_active_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]:checked' => 'border-color: {{VALUE}}!important;',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-wrapper input[type=checkbox]:checked' => 'border-color: {{VALUE}}!important;',
				),
			)
		);

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'isf_second_btn_heading_divider_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->second_apply_btn_settings();

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_links', __( 'Linkable Attributes', 'stm_vehicles_listing' ) );

		$this->add_control(
			'isf_second_label_box_links',
			array(
				'label' => __( 'Title Box', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_second_label_typography_links',
				'label'    => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-single-unit a.title h5, .classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title h5',
			)
		);

		$this->add_responsive_control(
			'isf_second_label_color_links',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-single-unit a.title h5'               => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title h5' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_label_bg_color_links',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-single-unit a.title'                                => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_second_label_border_links',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-single-unit > a.title, .classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title',
				'exclude'  => array( 'color' ),
				'default'  => array(
					'top'      => '3',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_label_border_color_links',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-single-unit > a.title' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title' => 'border-color: {{VALUE}};',
				),
				'default'   => '#232628',
			)
		);

		$this->add_responsive_control(
			'isf_second_label_border_radius_links',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-single-unit > a.title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_collapse_heading_links',
			array(
				'label' => __( 'Toggle Color', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_collapse_indicator_links' );

		$this->stm_start_ctrl_tab(
			'isf_collapse_indicator_normal_links',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_collapse_indicator_bg_links',
			array(
				'label'     => __( 'Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-single-unit a.title span'                                      => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .stm-filter-links .stm-accordion-single-unit a.title span:after'                                => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title span'       => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title span:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_collapse_indicator_hover_links',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_collapse_indicator_hover_bg_links',
			array(
				'label'     => __( 'Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6c98e1',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-single-unit a.title:hover span'                                      => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .stm-filter-links .stm-accordion-single-unit a.title:hover span:after'                                => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title:hover span'       => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title:hover span:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'isf_second_area_links_heading_divider_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_second_area_links_heading',
			array(
				'label' => esc_html__( 'Container Settings', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'isf_second_links_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-content .content' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_second_links_border',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-content .content, .classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'isf_second_border_links_color',
			array(
				'label'     => esc_html__( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-content .content' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_border_links_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-content .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_links_margin',
			array(
				'label'      => __( 'Bottom Spacing', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 10,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-content .content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_secondary_box_links_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget .stm-filter-links .stm-accordion-content .content, .classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-content',
			)
		);

		$this->add_responsive_control(
			'isf_second_padding_links_checkbox',
			array(
				'label'      => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_second_checkbox_label_links_heading_divider_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_second_checkbox_label_links_heading',
			array(
				'label' => esc_html__( 'Links Settings', 'textdomain' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_pal_typography',
				'label'    => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li a, .classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-content .list-style-3 li',
			)
		);

		$this->add_control(
			'isf_pal_icon',
			array(
				'label'   => __( 'Icon', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'solid',
				),
			)
		);

		$this->add_responsive_control(
			'isf_pal_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 50,
					),
					'em' => array(
						'min' => 0.1,
						'max' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-content .list-style-3 li:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'.stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_pal' );

		$this->stm_start_ctrl_tab(
			'isf_pal_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_pal_icon_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#cc6119',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li:before' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-content .list-style-3 li:before' => 'color: {{VALUE}};',
					'.stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul li i' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul li i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_pal_link_color',
			array(
				'label'     => __( 'Link Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li a' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-content .list-style-3 li a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_pal_amount_color',
			array(
				'label'     => __( 'Amount Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li a span' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-content .list-style-3 li a span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_pal_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_control(
			'isf_pal_icon_color_hover',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#cc6119',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li:before:hover' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-content .list-style-3 li:before:hover' => 'color: {{VALUE}};',
					'.stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul li:hover i' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul li:hover i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_pal_link_color_hover',
			array(
				'label'     => __( 'Link Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li:hover a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_pal_amount_color_hover',
			array(
				'label'     => __( 'Amount Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#232628',
				'selectors' => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li:hover a span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_responsive_control(
			'isf_pal_icon_margin',
			array(
				'label'      => __( 'Icon Margin', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stm-filter-links .stm-accordion-content .list-style-3 li:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-content .list-style-3 li:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.stm-filter-links .stm-accordion-single-unit .stm-accordion-content > .content > ul li i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'isf_mobile_filter', __( 'Mobile View Settings', 'stm_vehicles_listing' ) );

		$this->add_control(
			'isf_mobile_btn',
			array(
				'label' => __( 'Search Button', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_mobile_btn_tabs' );

		$this->add_control(
			'isf_mobile_btn_bg',
			array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'selectors' => array(
					'{{WRAPPER}} .mobile-filter .mobile-search-btn'                      => 'background-color: {{VALUE}};',
					'.sticky-mobile-filter.make-fixed .mobile-filter .mobile-search-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_mobile_btn_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '.mobile-filter .mobile-search-btn',
			)
		);

		$this->add_control(
			'isf_mobile_btn_text_color',
			array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'selectors' => array(
					'{{WRAPPER}} .mobile-filter .mobile-search-btn .mobile-search-btn-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mobile-filter .mobile-search-btn i'                       => 'color: {{VALUE}};',
					'.sticky-mobile-filter.make-fixed .mobile-filter .mobile-search-btn'    => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_mobile_btn_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'left'  => 'Left',
					'right' => 'Right',
				),
				'default' => 'left',
			)
		);

		$this->add_control(
			'isf_mobile_btn_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'size_units' => array(
					'px',
				),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 8,
						'max'  => 30,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 17,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mobile-filter .mobile-search-btn i'                        => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mobile-filter .mobile-search-btn svg'                      => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'.sticky-mobile-filter.make-fixed .mobile-filter .mobile-search-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'.sticky-mobile-filter.make-fixed .mobile-filter .mobile-search-btn svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_mobile_btn_typography',
				'label'    => __( 'Text Style', 'stm_elementor_widgets' ),
				'selector' => '.mobile-search-btn .mobile-search-btn-text',
				'default'  => array(
					'font_size'      => array(
						'unit' => 'px',
						'size' => 14,
					),
					'font_weight'    => '700',
					'line_height'    => array(
						'unit' => 'px',
						'size' => 14,
					),
					'text_transform' => 'uppercase',
				),
				'fields'   => array(
					'typography' => array(
						'font_family'     => false,
						'font_style'      => false,
						'text_decoration' => false,
						'word_spacing'    => false,
					),
				),
			)
		);

		$this->add_control(
			'isf_mobile_btn_padding',
			array(
				'label'     => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'    => '17',
					'right'  => '28',
					'bottom' => '15',
					'left'   => '28',
				),
				'selectors' => array(
					'{{WRAPPER}} .mobile-filter .mobile-search-btn'                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.sticky-mobile-filter.make-fixed .mobile-filter .mobile-search-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_mobile_sticky_wrapper_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_mobile_sticky_wrapper_heading',
			array(
				'label' => __( 'Fixed Search Bar', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'isf_mobile_sticky_wrapper_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.sticky-mobile-filter.make-fixed' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_mobile_sticky_wrapper_paddings',
			array(
				'label'     => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
				),
				'selectors' => array(
					'.sticky-mobile-filter.make-fixed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_mobile_sticky_wrapper_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '.sticky-mobile-filter.make-fixed',
			)
		);

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'isf_mobile_search_filter_header_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_mobile_filter_heading',
			array(
				'label' => __( 'Search Filter Header', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'isf_mobile_filter_heading_typography',
				'label'    => __( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .sidebar-entry-header-mobile .h4',
			)
		);

		$this->add_control(
			'isf_mobile_filter_heading_text_color',
			array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Search Heading Color', 'stm_vehicles_listing' ),
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .sidebar-entry-header-mobile .h4' => 'color: {{VALUE}};',
				),
				'default'   => '#232628',
			)
		);

		$this->add_control(
			'isf_mobile_filter_close_btn',
			array(
				'type'      => \Elementor\Controls_Manager::COLOR,
				'label'     => __( 'Close Button Color', 'stm_vehicles_listing' ),
				'default'   => '#6c98e1',
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .sidebar-entry-header-mobile .close-btn span.close-btn-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_mobile_search_filter_header_bgr_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_mobile_bgr',
			array(
				'label' => __( 'Filter Background', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'isf_mobile_filter_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .filter.filter-sidebar'                                                                               => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile'                                                                                                                     => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar), {{WRAPPER}} .mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-listing-directory-price .stm-accordion-single-unit.price'                                 => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-single-unit'                                                         => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-filter-links .stm-accordion-single-unit .stm-accordion-content'                                  => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.search-filter-form.mobile'                                                                                                  => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_mobile_reset_btn_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_mobile_secondary_heading',
			array(
				'label'     => __( 'Secondary Block Settings', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_control(
			'isf_mobile_secondary_label_box_heading',
			array(
				'label' => __( 'Item Box', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_mobile_second_filter_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) a.title, .classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .stm-filter-links .stm-accordion-single-unit a.title',
			)
		);

		$this->add_control(
			'isf_mobile_secondary_filter_heading',
			array(
				'label' => __( 'Container Settings', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'isf_mobile_secondary_filter_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-accordion-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_mobile_secondary_filter_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '.classic-filter-row.motors-elementor-widget.mobile-filter-row .search-filter-form.mobile .stm-accordion-content',
			)
		);

		$this->add_control(
			'isf_mobile_secondary_field_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_mobile_show_cars_btn_heading',
			array(
				'label' => __( 'Show Results Button', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'isf_mobile_show_cars_btn_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#fffff',
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .filter-show-cars .show-car-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_mobile_show_cars_btn_border',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .filter-show-cars .show-car-btn',
				'default'  => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'isf_mobile_show_cars_border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'selectors'   => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .filter-show-cars .show-car-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_show_cars_btn_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .filter-show-cars .show-car-btn',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'isf_show_cars_typography',
				'label'          => __( 'Text Style', 'stm_elementor_widgets' ),
				'exclude'        => array(
					'font_family',
					'font_style',
					'text_decoration',
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
							'size' => 14,
						),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
				),
				'selector'       => '.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .filter-show-cars .show-car-btn',
			)
		);

		$this->add_control(
			'isf_show_cars_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .filter-show-cars .show-car-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_show_cars_button_padding',
			array(
				'label'     => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'    => '13',
					'right'  => '28',
					'bottom' => '13',
					'left'   => '28',
					'unit'   => 'px',
				),
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .filter-show-cars .show-car-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'isf_mobile_reset_btn',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'isf_mobile_reset_btn_heading',
			array(
				'label' => __( 'Reset Button', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_mobile_reset_btn_tabs' );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_mobile_reset_btn_border',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .reset-btn-mobile a.button',
				'default'  => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'isf_mobile_reset_border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'default'     => array(
					'top'      => '3',
					'right'    => '3',
					'bottom'   => '3',
					'left'     => '3',
					'isLinked' => true,
				),
				'selectors'   => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .reset-btn-mobile a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'   => 'after',
			)
		);

		$this->add_control(
			'isf_mobile_reset_btn_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#fffff',
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .reset-btn-mobile a.button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'isf_mobile_reset_btn_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .reset-btn-mobile a.button',
			)
		);

		$this->add_control(
			'isf_mobile_reset_btn_text_color',
			array(
				'label'     => __( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .mobile .sticky-filter-actions .reset-btn-mobile a.button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->add_control(
			'isf_mobile_reset_icon_size',
			array(
				'label'      => __( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 8,
						'max'  => 30,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 17,
				),
				'selectors'  => array(
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .sticky-filter-actions .reset-btn-mobile .button i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row .sticky-filter-actions .reset-btn-mobile .button svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

		$this->stm_end_control_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings['filter_bg'] = apply_filters( 'motors_vl_get_nuxy_mod', STM_LISTINGS_URL . '/assets/elementor/img/listing-directory-filter-bg.jpg', 'sidebar_filter_bg' );
		if ( is_int( $settings['filter_bg'] ) ) {
			$settings['filter_bg'] = wp_get_attachment_image_url( $settings['filter_bg'], 'full' );
		}

		$settings['show_sold'] = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_sold_listings' );

		$api_posts            = new \MotorsVehiclesListing\Api\ApiPosts();
		$settings['api_data'] = $api_posts->get_rest_data();

		$settings['filter'] = apply_filters( 'stm_listings_filter_func', null );

		$post_type = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'listings';

		if ( stm_is_multilisting() && 'listings' !== $post_type ) {
			set_query_var( 'listings_type', $post_type );
			\HooksMultiListing::stm_listings_attributes_filter( array( 'slug' => $post_type ) );
			$settings['filter'] = apply_filters( 'stm_listings_filter_func', array( 'post_type' => $post_type ) );
		}

		Helper::stm_ew_load_template( 'elementor/Widgets/inventory-search-filter', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {

	}

	private function second_apply_btn_settings() {
		$this->add_control(
			'isf_second_btn_heading',
			array(
				'label' => __( 'Apply Button', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'second_button_padding',
			array(
				'label'     => __( 'Inner Padding', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'    => '17',
					'right'  => '28',
					'bottom' => '15',
					'left'   => '28',
					'unit'   => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget .search-filter-form .stm-accordion-single-unit.stm-listing-directory-checkboxes .stm-accordion-content .stm-accordion-content-wrapper .stm-accordion-inner .stm-checkbox-submit a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'isf_second_btn_typography',
				'label'          => __( 'Text Style', 'stm_elementor_widgets' ),
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
							'size' => 14,
						),
					),
					'text_transform' => array(
						'default' => 'uppercase',
					),
				),
				'selector'       => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button',
			)
		);

		$this->stm_start_ctrl_tabs( 'isf_second_btn_tabs' );

		$this->stm_start_ctrl_tab(
			'isf_second_btn_normal',
			array(
				'label' => __( 'Normal', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_second_btn_text_color',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-submit a' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_btn_bg',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6c98e1',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button' => 'background-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_second_btn_border',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button',
				'default'  => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
				'exclude'  => array(
					'color',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_btn_border_color',
			array(
				'label'     => __( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button' => 'border-color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'isf_second_btn_border_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.classic-filter-row.motors-elementor-widget .search-filter-form .stm-accordion-single-unit.stm-listing-directory-checkboxes .stm-accordion-content .stm-accordion-content-wrapper .stm-accordion-inner .stm-checkbox-submit a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'second_btn_box_shadow',
				'label'    => __( 'Box Shadow', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button, .classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button',
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_start_ctrl_tab(
			'isf_second_btn_hover',
			array(
				'label' => __( 'Hover', 'stm_vehicles_listing' ),
			)
		);

		$this->add_responsive_control(
			'isf_second_btn_text_color_apply_hover',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'.classic-filter-row.motors-elementor-widget .stm-listing-directory-checkboxes .stm-accordion-content-wrapper .stm-checkbox-submit a:hover' => 'color: {{VALUE}};',
					'.classic-filter-row.motors-elementor-widget.mobile-filter-row form.mobile > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'isf_second_btn_bg_hover',
			array(
				'label'     => __( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#6c98e1',
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'isf_second_btn_border_hover',
				'label'    => esc_html__( 'Button Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button:hover',
				'default'  => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				),
			)
		);

		$this->add_control(
			'isf_second_btn_border_radius_hover',
			array(
				'label'       => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'second_btn_box_shadow_hover',
				'label'    => __( 'Box Shadow Hover', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button:hover',
			)
		);

		$this->add_control(
			'isf_second_btn_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .classic-filter-row.motors-elementor-widget form > div:not(.filter-sidebar) .stm-accordion-content .stm-accordion-inner a.button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->stm_end_ctrl_tab();

		$this->stm_end_ctrl_tabs();

	}

	private function motors_selected_filters( $listing_type = null ) {

		$filter_fields  = 'Filters:';
		$filter_fields .= '<br /><br />';
		$filter_fields .= '<ul style="font-weight: 400;">';

		if ( is_null( $listing_type ) || 'listings' === $listing_type ) {

			$filters = apply_filters(
				'mvl_listings_attributes',
				array(
					'where'  => array( 'use_on_car_filter' => true ),
					'key_by' => 'slug',
				)
			);

			foreach ( $filters as $filter ) {
				$filter_fields .= '<li>&nbsp;- ' . $filter['single_name'] . '</li>';
			}
			$filter_fields .= '</ul>';

			$filter_fields .= '<br /><a href="' . admin_url( '/admin.php?page=listing_categories' ) . '" target="_blank">' . esc_html__( 'Edit Custom Fields', 'stm_vehicles_listing' ) . '</a><p style="font-size: 11px;font-weight: 400;margin-top: 10px;font-style: italic;line-height: 15px;color: #9da5ae;">' . esc_html__( 'Add, remove, and manage custom fields in Motors Plugin Settings > Custom Fields.' ) . '</p>';

		} else {

			$filters = Helper::stm_ew_multi_listing_search_filter_fields( $listing_type );

			foreach ( $filters as $key => $filter ) {
				$filter_fields .= '<li>&nbsp;- ' . $filter . '</li>';
			}
			$filter_fields .= '</ul>';

			$filter_fields .= '<br /><a href="' . admin_url( 'edit.php?post_type=' . $listing_type . '&page=' . $listing_type . '_categories' ) . '" target="_blank">' . esc_html__( 'Edit Custom Fields', 'stm_vehicles_listing' ) . '</a>';
		}

		return $filter_fields;
	}
}
