<?php
add_filter(
	'search_settings_conf',
	function ( $conf_for_merge ) {

		$skins = apply_filters(
			'mvl_inventory_skins',
			array(
				array(
					'value' => 'default',
					'alt'   => 'Default',
					'img'   => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=100',
				),
				array(
					'value'         => 'sidebar_left',
					'alt'           => 'Sidebar Left',
					'img'           => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=100',
					'disabled'      => ! apply_filters( 'is_mvl_pro', false ),
					'pro_img'       => esc_url( STM_LISTINGS_URL . '/assets/images/pro/unlock-pro-logo.svg' ),
					'preview_url'   => 'https://motors.stylemixthemes.com/',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
				),
				array(
					'value'         => 'sidebar_right',
					'alt'           => 'Sidebar Right',
					'img'           => 'https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=100&h=100',
					'disabled'      => ! apply_filters( 'is_mvl_pro', false ),
					'pro_img'       => esc_url( STM_LISTINGS_URL . '/assets/images/pro/unlock-pro-logo.svg' ),
					'preview_url'   => 'https://motors.stylemixthemes.com/',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
				),
				array(
					'value'         => 'sidebar_horizontal',
					'alt'           => 'Sidebar Horizontal',
					'img'           => 'https://images.unsplash.com/photo-1505118380757-91f5f5632de0?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=100&h=100',
					'disabled'      => ! apply_filters( 'is_mvl_pro', false ),
					'pro_img'       => esc_url( STM_LISTINGS_URL . '/assets/images/pro/unlock-pro-logo.svg' ),
					'preview_url'   => 'https://motors.stylemixthemes.com/',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
				),
			)
		);

		$conf = array(
			'inventory_skin' => array(
				'label'   => esc_html__( 'Inventory Page Skins', 'stm_vehicles_listing' ),
				'type'    => 'nuxy-radio',
				'width'   => 250,
				'height'  => 150,
				'value'   => 'default',
				'options' => $skins,
			),
		);

		return array_merge( $conf_for_merge, $conf );
	},
	19,
	1
);
