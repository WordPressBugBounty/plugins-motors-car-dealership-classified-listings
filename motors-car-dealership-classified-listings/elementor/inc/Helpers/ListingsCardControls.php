<?php
function grid_listing_card_controls( $widget, $widget_name ) {
	$widget->start_controls_section(
		'isr_grid_card_settings_title_' . $widget_name,
		array(
			'label' => esc_html__( 'Card Layout Settings', 'stm_vehicle_listings' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_item_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_item_' . $widget_name,
		array(
			'label' => esc_html__( 'Item', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_item_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_item_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item a' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_item_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_item_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item a',
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_item_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Box_Shadow::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_item_box_shadow_' . $widget_name,
			'label'    => esc_html__( 'Box Shadow', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item a',
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_image_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_image_' . $widget_name,
		array(
			'label' => esc_html__( 'Image', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_image_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'grid_thumb_height_' . $widget_name,
		array(
			'label'      => __( 'Image Height', 'stm_vehicles_listing' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array(
				'px',
			),
			'range'      => array(
				'px' => array(
					'min'  => 100,
					'max'  => 300,
					'step' => 1,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .stm-isotope-sorting-grid .image img, {{WRAPPER}} .car-listing-modern-grid .image img'                                 => 'height: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .stm-isotope-sorting-grid .interactive-hoverable, {{WRAPPER}} .car-listing-modern-grid .interactive-hoverable'         => 'min-height: {{SIZE}}{{UNIT}}',
				'{{WRAPPER}} .stm-isotope-sorting-grid .interactive-hoverable img, {{WRAPPER}} .car-listing-modern-grid .interactive-hoverable img' => 'height: 100%',
				'.stm-hoverable-interactive-galleries {{WRAPPER}} .stm-directory-grid-loop .image-inner .interactive-hoverable'                                              => 'height: {{SIZE}}{{UNIT}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_image_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .stm-directory-grid-loop .image',
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_image_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .stm-directory-grid-loop .image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_meta_top_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_meta_top_' . $widget_name,
		array(
			'label' => esc_html__( 'Top Info', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_meta_top_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_top_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-top' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_top_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_top_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}}  .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_meta_top_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-top',
		)
	);

	//border radius
	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_top_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_title_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_title_' . $widget_name,
		array(
			'label' => esc_html__( 'Listing Title', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_title_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_title_typography_' . $widget_name,
			'label'    => esc_html__( 'Title Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .stm-directory-grid-loop .listing-car-item-meta .car-meta-top .car-title',
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_title_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Title Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .stm-directory-grid-loop .listing-car-item-meta .car-meta-top .car-title' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_title_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Title Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .stm-directory-grid-loop .listing-car-item-meta .car-meta-top .car-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_price_divider_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_price_' . $widget_name,
		array(
			'label' => esc_html__( 'Price', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_price_divider_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_price_typography_' . $widget_name,
			'label'    => esc_html__( 'Price Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price .normal-price',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_sale_price_typography_' . $widget_name,
			'label'    => esc_html__( 'Regular Price Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price.discounted-price .sale-price',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_regular_price_typography_' . $widget_name,
			'label'    => esc_html__( 'Strikethrough Price Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price.discounted-price .regular-price',
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_price_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Price Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price .normal-price' => 'color: {{VALUE}}',
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price.discounted-price .sale-price' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_sale_price_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Strikethrough Price Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price.discounted-price .regular-price' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_price_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Price Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price.discounted-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_price_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .price:before' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_meta_bottom_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_meta_bottom_' . $widget_name,
		array(
			'label' => esc_html__( 'Bottom Info', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_divider_meta_bottom_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_bottom_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-bottom' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_bottom_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-bottom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_bottom_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-bottom' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_meta_bottom_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-bottom',
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_bottom_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-bottom' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_control(
		'isr_grid_card_settings_heading_meta_info_' . $widget_name,
		array(
			'label'     => esc_html__( 'Meta Info', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::HEADING,
			'separator' => 'after',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_grid_card_settings_meta_info_typography_' . $widget_name,
			'label'    => esc_html__( 'Meta Info Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-bottom ul li span',
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_info_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Meta Info Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .car-listing-modern-grid .stm-isotope-listing-item .listing-car-item-meta .car-meta-bottom ul li span' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_info_icon_size_' . $widget_name,
		array(
			'label'      => esc_html__( 'Icon Size', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .stm-directory-grid-loop .listing-car-item-meta .car-meta-bottom ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_grid_card_settings_meta_info_icon_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Icon Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .stm-directory-grid-loop .listing-car-item-meta .car-meta-bottom ul li i' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->end_controls_section();
}

add_filter( 'grid_listing_card_controls', 'grid_listing_card_controls', 10, 2 );

function list_listing_card_controls( $widget, $widget_name ) {
	$widget->start_controls_section(
		'isr_list_card_settings_title_' . $widget_name,
		array(
			'label' => esc_html__( 'List Layout Settings', 'stm_vehicle_listings' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_item_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_item_' . $widget_name,
		array(
			'label' => esc_html__( 'Item', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_item_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_item_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .stm-isotope-sorting-list .listing-list-loop' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_item_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .stm-isotope-sorting-list .listing-list-loop' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_item_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .stm-isotope-sorting-list .listing-list-loop' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_item_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .stm-isotope-sorting-list .listing-list-loop',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_item_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .stm-isotope-sorting-list .listing-list-loop' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Box_Shadow::get_type(),
		array(
			'name'     => 'isr_list_card_settings_item_box_shadow_' . $widget_name,
			'label'    => esc_html__( 'Box Shadow', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .stm-isotope-sorting-list .listing-list-loop',
		)
	);

	//heading
	$widget->add_control(
		'isr_list_card_settings_heading_divider_image_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_image_' . $widget_name,
		array(
			'label' => esc_html__( 'Image', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_image_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'list_thumb_height_' . $widget_name,
		array(
			'label'      => __( 'Image Height', 'stm_vehicles_listing' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array(
				'px',
			),
			'range'      => array(
				'px' => array(
					'min'  => 100,
					'max'  => 300,
					'step' => 1,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .stm-isotope-sorting .listing-list-loop.stm-listing-directory-list-loop .image'                                        => 'min-height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .stm-isotope-sorting .listing-list-loop.stm-listing-directory-list-loop .image img'                                    => 'height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .stm-isotope-sorting .listing-list-loop.stm-listing-directory-list-loop .interactive-hoverable'                        => 'height:{{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}} !important;',
				'{{WRAPPER}} .stm-isotope-sorting .listing-list-loop.stm-listing-directory-list-loop .interactive-hoverable img'                    => 'height: 100%; width: 100%; object-fit: cover;',
				'{{WRAPPER}} .listing-list-loop .image .image-inner img'                                                                            => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
				'.stm-hoverable-interactive-galleries {{WRAPPER}} .image-inner .interactive-hoverable'                                              => 'height: {{SIZE}}{{UNIT}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_image_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .listing-list-loop .image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_image_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .listing-list-loop .image',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_image_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .motors-elementor-inventory-search-results#listings-result .listing-list-loop .image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	//heading
	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_top_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_meta_top_' . $widget_name,
		array(
			'label' => esc_html__( 'Top Info', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_top_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_top_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_top_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_top_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_top_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-top',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_top_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_title_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_title_' . $widget_name,
		array(
			'label' => esc_html__( 'Listing Title', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_title_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_title_typography_' . $widget_name,
			'label'    => esc_html__( 'Title Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .title a',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_title_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Title Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .title a' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_label_typography_' . $widget_name,
			'label'    => esc_html__( 'Label Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .title a .labels',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_label_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Label Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .title a .labels' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_price_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_price_' . $widget_name,
		array(
			'label' => esc_html__( 'Price', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_price_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_price_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price:before' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_price_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Price Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_price_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Price Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_price_typography_' . $widget_name,
			'label'    => esc_html__( 'Price Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-top .price .normal-price .heading-font',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_price_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Price Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price .normal-price .heading-font' => 'color: {{VALUE}}',
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price .sale-price .heading-font' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_price_label_typography_' . $widget_name,
			'label'    => esc_html__( 'Price Label Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-top .price .normal-price .label-price',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_price_label_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Price Labels Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price .normal-price .label-price' => 'color: {{VALUE}}',
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price.discounted-price .regular-price .label-price' => 'color: {{VALUE}}',
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price .sale-price .label-price' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_price_label_offset_' . $widget_name,
		array(
			'label'      => esc_html__( 'Price Label Offset', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => -100,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-top .price .normal-price .label-price' => 'top: {{SIZE}}{{UNIT}};',
			),
			'separator'  => 'after',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_regular_price_typography_' . $widget_name,
			'label'    => esc_html__( 'Strikethrough Price Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-top .price.discounted-price .regular-price',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_regular_price_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Strikethrough Price Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .price .regular-price' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_regular_price_label_typography_' . $widget_name,
			'label'    => esc_html__( 'Strikethrough Price Label Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-top .price.discounted-price .regular-price .label-price',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_sale_price_typography_' . $widget_name,
			'label'    => esc_html__( 'Regular Price Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-top .price .sale-price .heading-font',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_sale_price_label_typography_' . $widget_name,
			'label'    => esc_html__( 'Regular Price Label Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-top .price .sale-price .label-price',
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_middle_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_meta_middle_' . $widget_name,
		array(
			'label' => esc_html__( 'Center Info', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_middle_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_middle_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-middle',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_middle_info_typography_' . $widget_name,
			'label'    => esc_html__( 'Name Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit .name',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_info_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Name Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit .name' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_middle_value_typography_' . $widget_name,
			'label'    => esc_html__( 'Value Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit .value ',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_value_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Value Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit .value' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_icon_size_' . $widget_name,
		array(
			'label'      => esc_html__( 'Icon Size', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit .icon' => 'font-size: {{SIZE}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_icon_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Icon Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit .icon' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_icon_spacing_' . $widget_name,
		array(
			'label'      => esc_html__( 'Icon Spacing', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit .icon' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_middle_separator_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Separator Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-middle .meta-middle-unit' => 'border-right-color: {{VALUE}}',
			),
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_bottom_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_meta_bottom_' . $widget_name,
		array(
			'label' => esc_html__( 'Bottom Info', 'stm_vehicle_listings' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_bottom_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_bottom_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-bottom',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_profile_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_meta_profile_' . $widget_name,
		array(
			'label'     => esc_html__( 'Seller Details', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::HEADING,
			'separator' => 'after',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_profile_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_profile_title_typography_' . $widget_name,
			'label'    => esc_html__( 'Title Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block div.title',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_title_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Title Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block div.title' => 'color: {{VALUE}}',
			),
			'separator' => 'after',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_phone_icon_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Phone Icon Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block .dealer-information i' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_phone_icon_size_' . $widget_name,
		array(
			'label'      => esc_html__( 'Phone Icon Size', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block .dealer-information i' => 'font-size: {{SIZE}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_phone_icon_spacing_' . $widget_name,
		array(
			'label'      => esc_html__( 'Phone Icon Spacing', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block .dealer-information i' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
			'separator'  => 'after',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_profile_phone_typography_' . $widget_name,
			'label'    => esc_html__( 'Phone Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block .dealer-information .phone',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_phone_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Phone Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block .dealer-information .phone' => 'color: {{VALUE}}',
			),
			'separator' => 'after',
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_profile_show_more_typography_' . $widget_name,
			'label'    => esc_html__( 'Show More Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block .dealer-information .stm-show-number',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_profile_show_more_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Show More Text Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .listing-list-loop .content .meta-bottom .listing-archive-dealer-info .dealer-info-block .dealer-information .stm-show-number' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_stock_after_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_meta_bottom_title_' . $widget_name,
		array(
			'label'     => esc_html__( 'Stock Number Settings', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::HEADING,
			'separator' => 'after',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_title_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions .stock-num' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_title_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions .stock-num' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_bottom_title_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .meta-bottom .single-car-actions .stock-num',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_title_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions .stock-num' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_bottom_title_typography_' . $widget_name,
			'label'    => esc_html__( 'Title Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .meta-bottom .single-car-actions .stock-num span',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_title_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Title Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions .stock-num span' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_bottom_value_typography_' . $widget_name,
			'label'    => esc_html__( 'Value Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .meta-bottom .single-car-actions .stock-num .stock-num-value',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_bottom_value_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Value Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions .stock-num .stock-num-value' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_divider_meta_actions_' . $widget_name,
		array(
			'type' => \Elementor\Controls_Manager::DIVIDER,
		)
	);

	$widget->add_control(
		'isr_list_card_settings_heading_meta_actions_' . $widget_name,
		array(
			'label'     => esc_html__( '(List Card Settings)> Actions Button', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::HEADING,
			'separator' => 'after',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_padding_' . $widget_name,
		array(
			'label'      => esc_html__( 'Padding', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_margin_' . $widget_name,
		array(
			'label'      => esc_html__( 'Margin', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_actions_typography_' . $widget_name,
			'label'    => esc_html__( 'Text Style', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit span',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_icon_size_' . $widget_name,
		array(
			'label'      => esc_html__( 'Icon Size', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit i' => 'font-size: {{SIZE}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_icon_spacing_' . $widget_name,
		array(
			'label'      => esc_html__( 'Icon Spacing', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array( 'px', 'em', 'rem' ),
			'range'      => array(
				'px' => array(
					'min' => 0,
					'max' => 100,
				),
			),
			'selectors'  => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit i' => 'margin-right: {{SIZE}}{{UNIT}};',
			),
		)
	);

	$widget->start_controls_tabs( 'isr_list_card_settings_meta_actions_tabs_' . $widget_name );

	$widget->start_controls_tab( 'isr_list_card_settings_meta_actions_tab_' . $widget_name, array( 'label' => esc_html__( 'Normal', 'stm_vehicle_listings' ) ) );

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_actions_border_' . $widget_name,
			'label'    => esc_html__( 'Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_border_radius_' . $widget_name,
		array(
			'label'      => esc_html__( 'Border Radius', 'stm_vehicle_listings' ),
			'type'       => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_text_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Text Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit.car-action-unit' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_icon_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Icon Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit i' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->end_controls_tab();

	$widget->start_controls_tab( 'isr_list_card_settings_meta_actions_hover_tab_' . $widget_name, array( 'label' => esc_html__( 'Hover', 'stm_vehicle_listings' ) ) );

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_hover_bg_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Background Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit:hover' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit.stm-added' => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit.active' => 'background-color: {{VALUE}}',
			),
		)
	);

	$widget->add_group_control(
		\Elementor\Group_Control_Border::get_type(),
		array(
			'name'     => 'isr_list_card_settings_meta_actions_hover_border_' . $widget_name,
			'label'    => esc_html__( 'Hover Border', 'stm_vehicle_listings' ),
			'selector' => '{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit:hover',
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_hover_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Hover Text Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit:hover' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->add_responsive_control(
		'isr_list_card_settings_meta_actions_hover_icon_color_' . $widget_name,
		array(
			'label'     => esc_html__( 'Hover Icon Color', 'stm_vehicle_listings' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => array(
				'{{WRAPPER}} .meta-bottom .single-car-actions ul.list-unstyled li > a.car-action-unit:hover i' => 'color: {{VALUE}}',
			),
		)
	);

	$widget->end_controls_tab();

	$widget->end_controls_tabs();

	$widget->end_controls_section();
}

add_filter( 'list_listing_card_controls', 'list_listing_card_controls', 10, 2 );
