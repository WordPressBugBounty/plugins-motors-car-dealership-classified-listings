<?php

namespace Motors_Elementor_Widgets_Free\Widgets\SingleListing;

use Motors_Elementor_Widgets_Free\MotorsElementorWidgetsFree;
use Motors_Elementor_Widgets_Free\Helpers\Helper;
use Motors_Elementor_Widgets_Free\Widgets\WidgetBase;

class Gallery extends WidgetBase {
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
				'swiper',
				'elementor-frontend',
			)
		);
		if ( is_rtl() ) {
			$this->stm_ew_enqueue( self::get_name() . '-rtl', STM_LISTINGS_PATH, STM_LISTINGS_URL, STM_LISTINGS_V );
		}

	}

	public function get_style_depends(): array {
		$widget_styles   = parent::get_style_depends();
		$widget_styles[] = self::get_name() . '-rtl';
		$widget_styles[] = 'swiper';

		return $widget_styles;
	}

	public function get_script_depends(): array {
		$depends   = parent::get_script_depends();
		$depends[] = 'swiper';

		return $depends;
	}

	public function get_categories() {
		return array( MotorsElementorWidgetsFree::WIDGET_CATEGORY_SINGLE );
	}

	public function get_name() {
		return MotorsElementorWidgetsFree::STM_PREFIX . '-single-listing-gallery';
	}

	public function get_title() {
		return esc_html__( 'Gallery', 'stm_vehicles_listing' );
	}

	public function get_icon() {
		return 'stmew-photo-gallery';
	}

	protected function register_controls() {

		$this->stm_start_content_controls_section( 'Action buttons', esc_html__( 'General', 'stm_vehicles_listing' ) );

		$this->add_control(
			'use_slider',
			array(
				'label'   => esc_html__( 'Display Thumbnails', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			),
		);

		$this->add_control(
			'actions_heading',
			array(
				'label' => esc_html__( 'Action Buttons', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			),
		);

		$this->add_control(
			'show_print',
			array(
				'label'   => esc_html__( 'Print', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'show_compare',
			array(
				'label'   => esc_html__( 'Compare', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'show_share',
			array(
				'label'   => esc_html__( 'Share', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
			$this->add_control(
				'show_featured',
				array(
					'label'   => esc_html__( 'Favorite', 'stm_vehicles_listing' ),
					'type'    => \Elementor\Controls_Manager::SWITCHER,
					'default' => '',
				)
			);
		}

		$this->add_control(
			'show_test_drive',
			array(
				'label'   => esc_html__( 'Test Drive', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'show_pdf',
			array(
				'label'   => esc_html__( 'PDF', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_content_controls_section( 'slg_carousel_settings', esc_html__( 'Carousel Settings', 'stm_vehicles_listing' ) );

		$this->add_control(
			'slg_infinite_loop',
			array(
				'label'        => esc_html__( 'Infinite Loop', 'stm_vehicles_listing' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => true,
			)
		);

		$this->add_control(
			'slg_autoplay',
			array(
				'label' => esc_html__( 'Autoplay', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'slg_autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'slg_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'slg_speed',
			array(
				'label'     => esc_html__( 'Speed', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 300,
				'condition' => array(
					'slg_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'slg_tablet_slides_per_view',
			array(
				'label'     => esc_html__( 'Slides Count in Tablet View', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 3,
				'min'       => 3,
				'condition' => array(
					'use_slider' => 'yes',
				),
			)
		);

		$this->add_control(
			'slg_mobile_slides_per_view',
			array(
				'label'     => esc_html__( 'Slides Count in Mobile View', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 2,
				'condition' => array(
					'use_slider' => 'yes',
				),
			)
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'section_styles', esc_html__( 'Gallery', 'stm_vehicles_listing' ) );

		$this->add_control(
			'badge_position',
			array(
				'label'       => esc_html__( 'Badge Position', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'description' => esc_html__( '"Special", "Sold" etc.', 'stm_vehicles_listing' ),
				'options'     => array(
					'left-top'     => esc_html__( 'Left top', 'stm_vehicles_listing' ),
					'right-top'    => esc_html__( 'Right top', 'stm_vehicles_listing' ),
					'left-bottom'  => esc_html__( 'Left bottom', 'stm_vehicles_listing' ),
					'right-bottom' => esc_html__( 'Right bottom', 'stm_vehicles_listing' ),
				),
				'default'     => 'left-top',
			),
		);

		$this->add_control(
			'onhover_heading',
			array(
				'label' => esc_html__( 'Onhover Effects', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			),
		);

		$this->add_control(
			'show_actions_onhover',
			array(
				'label'       => esc_html__( 'Hide action buttons by default', 'stm_vehicles_listing' ),
				'type'        => \Elementor\Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Show on hover', 'stm_vehicles_listing' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'show_zoom_icon',
			array(
				'label'   => esc_html__( 'Show Zoom Icon', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'zoom_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 85,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 26,
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a .image-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_zoom_icon' => 'yes',
				),
			),
		);

		$this->add_control(
			'zoom_icon_box_style',
			array(
				'label'     => esc_html__( 'Icon Box Style', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => array(
					'none'    => esc_html__( 'None', 'stm_vehicles_listing' ),
					'circle'  => esc_html__( 'Circle', 'stm_vehicles_listing' ),
					'hexagon' => esc_html__( 'Hexagon', 'stm_vehicles_listing' ),
				),
				'default'   => 'none',
				'condition' => array(
					'show_zoom_icon' => 'yes',
				),
			),
		);

		$this->add_control(
			'zoom_icon_box_color',
			array(
				'label'     => esc_html__( 'Icon Box Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a .image-icon'        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a .image-icon:before' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a .image-icon:after'  => 'border-top-color: {{VALUE}};',
				),
				'condition' => array(
					'show_zoom_icon' => 'yes',
				),
			),
		);

		$this->add_control(
			'zoom_icon_color',
			array(
				'label'     => esc_html__( 'Icon color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a .image-icon i' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_zoom_icon' => 'yes',
				),
			),
		);

		$this->add_control(
			'show_overlay',
			array(
				'label'   => esc_html__( 'Show Overlay', 'stm_vehicles_listing' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'      => esc_html__( 'Overlay opacity (%)', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 40,
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a .image-overlay' => 'background-color: rgba(10,10,10,.{{SIZE}});',
				),
				'condition'  => array(
					'show_overlay' => 'yes',
				),
			),
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'border_big_gallery',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a img',
			),
		);

		$this->add_control(
			'border_radius_big_gallery',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-big-gallery .stm-single-image a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'heading_big_gallery_actions_divider_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_control(
			'heading_big_gallery_actions',
			array(
				'label' => esc_html__( 'Action Buttons', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			),
		);

		$this->add_control(
			'heading_big_gallery_actions_divider_after',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_control(
			'actions_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 85,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
		);

		$this->add_responsive_control(
			'padding_big_gallery_actions',
			array(
				'label'      => esc_html__( 'Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_responsive_control(
			'margin_big_gallery_actions',
			array(
				'label'      => esc_html__( 'Margin', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'border_big_gallery_actions',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit',
				'exclude'  => array( 'color' ),
			),
		);

		$this->add_control(
			'border_radius_big_gallery_actions',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->start_controls_tabs( 'tabs_big_gallery_actions' );

		$this->start_controls_tab( 'tab_big_gallery_actions_normal', array( 'label' => esc_html__( 'Normal', 'stm_vehicles_listing' ) ) );

		$this->add_control(
			'actions_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit i' => 'color: {{VALUE}}!important;',
				),
			),
		);

		$this->add_control(
			'actions_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit' => 'background-color: {{VALUE}};',
				),
			),
		);

		$this->add_control(
			'actions_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit' => 'border-color: {{VALUE}};',
				),
			),
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_big_gallery_actions_active', array( 'label' => esc_html__( 'Active', 'stm_vehicles_listing' ) ) );

		$this->add_control(
			'actions_color_active',
			array(
				'label'     => esc_html__( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit.active i' => 'color: {{VALUE}}!important;',
				),
			),
		);

		$this->add_control(
			'actions_background_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit.active' => 'background-color: {{VALUE}};',
				),
			),
		);

		$this->add_control(
			'actions_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-gallery-actions .stm-gallery-action-unit.active' => 'border-color: {{VALUE}};',
				),
			),
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_big_gallery_video_badge_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_control(
			'heading_big_gallery_video_badge',
			array(
				'label' => esc_html__( '(Big gallery) Video Badge > Video Button', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			),
		);

		$this->add_control(
			'heading_big_gallery_video_badge_divider_after',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'video_badge_typography',
				'label'    => esc_html__( 'Text Style', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div span',
			)
		);

		$this->add_control(
			'video_badge_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div span' => 'color: {{VALUE}};',
				),
			),
		);

		$this->add_responsive_control(
			'video_badge_padding',
			array(
				'label'      => esc_html__( 'Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'video_badge_border',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div',
			),
		);

		$this->add_control(
			'video_badge_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'video_badge_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div' => 'background-color: {{VALUE}};',
				),
			),
		);

		$this->add_control(
			'video_badge_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 85,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'video_badge_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div i' => 'color: {{VALUE}};',
				),
			),
		);

		$this->add_responsive_control(
			'video_badge_icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .stm-car-medias > div i' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			),
		);

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'section_styles_thumb_gallery', esc_html__( 'Thumbnail Images', 'stm_vehicles_listing' ) );

		$this->add_control(
			'background_color_small_gallery',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery' => 'background-color: {{VALUE}};',
				),
			),
		);

		$this->add_responsive_control(
			'padding_small_gallery',
			array(
				'label'      => esc_html__( 'Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'border_small_gallery',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery',
			),
		);

		$this->add_control(
			'border_radius_small_gallery',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_control(
			'heading_small_gallery_navigation_divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_control(
			'navigation_heading',
			array(
				'label' => esc_html__( 'Navigation', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			),
		);

		$this->add_control(
			'heading_small_gallery_navigation_divider_after',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_control(
			'navigation_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-prev' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-next' => 'background-color: {{VALUE}};',
				),
			),
		);

		$this->add_responsive_control(
			'navigation_padding',
			array(
				'label'      => esc_html__( 'Padding', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'navigation_border',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-prev, .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-next',
			),
		);

		$this->add_control(
			'navigation_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->add_responsive_control(
			'navigation_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 85,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-prev:after' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-next:after' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
		);

		$this->add_responsive_control(
			'navigation_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-prev:after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-swiper-controls .stm-swiper-next:after' => 'color: {{VALUE}};',
				),
			),
		);

		$this->add_control(
			'heading_small_gallery_divider_before',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_control(
			'heading_small_gallery',
			array(
				'label' => esc_html__( 'Items Settings', 'stm_vehicles_listing' ),
				'type'  => \Elementor\Controls_Manager::HEADING,
			),
		);

		$this->add_control(
			'heading_small_gallery_divider_after',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			),
		);

		$this->add_responsive_control(
			'item_spacing_small_gallery',
			array(
				'label'      => esc_html__( 'Item Spacing', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-single-image' => 'margin-right: {{SIZE}}{{UNIT}}!important;',
				),
			),
		);

		$this->start_controls_tabs( 'tabs_small_gallery' );

		$this->start_controls_tab( 'tab_small_gallery_normal', array( 'label' => esc_html__( 'Normal', 'stm_vehicles_listing' ) ) );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'border_small_gallery_normal',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-single-image',
			),
		);

		$this->add_control(
			'border_radius_small_gallery_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'stm_vehicles_listing' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-single-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_small_gallery_active', array( 'label' => esc_html__( 'Active', 'stm_vehicles_listing' ) ) );

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'border_small_gallery_active',
				'label'    => esc_html__( 'Border', 'stm_vehicles_listing' ),
				'selector' => '{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .stm-single-image.swiper-slide-thumb-active',
			),
		);

		$this->add_control(
			'background_color_small_gallery_active',
			array(
				'label'     => esc_html__( 'Overlay Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .motors-elementor-thumbs-gallery .swiper-slide-thumb-active:after' => 'background: {{VALUE}};',
				),
			),
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->stm_end_control_section();

		$this->stm_start_style_controls_section( 'section_styles_video_preview_button', esc_html__( 'Video Play Button', 'stm_vehicles_listing' ) );

		$this->add_control(
			'video_preview_button_color',
			array(
				'label'     => esc_html__( 'First Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .video-preview a.fancy-iframe:before' => 'background: {{VALUE}};',
				),
			),
		);

		$this->add_control(
			'video_preview_button_color_hover',
			array(
				'label'     => esc_html__( 'Second Color', 'stm_vehicles_listing' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .motors-elementor-single-listing-gallery .video-preview a.fancy-iframe:before' => 'color: {{VALUE}};',
				),
			),
		);

		$this->stm_end_control_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		Helper::stm_ew_load_template( 'elementor/Widgets/single-listing/gallery', STM_LISTINGS_PATH, $settings );
	}

	protected function content_template() {
	}
}
