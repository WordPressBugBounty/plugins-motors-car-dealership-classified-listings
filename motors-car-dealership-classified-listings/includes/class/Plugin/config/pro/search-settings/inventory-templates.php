<?php
add_filter(
	'search_settings_conf',
	function ( $conf_for_merge ) {

		$skins = apply_filters(
			'mvl_inventory_skins',
			array(
				array(
					'value' => 'default',
					'alt'   => 'Classic',
					'img'   => esc_url( STM_LISTINGS_URL . '/assets/images/pro/inventory/inventory-classic.jpg' ),
				),
				array(
					'value'         => 'inventory-modern',
					'alt'           => 'Modern',
					'img'           => esc_url( STM_LISTINGS_URL . '/assets/images/pro/inventory/inventory-modern.png' ),
					'disabled'      => ! is_mvl_pro(),
					'preview_url'   => 'https://motors-plugin.stylemixthemes.com/inventory/',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					'pricing_url'   => 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wp-admin&utm_medium=push&utm_campaign=motors&utm_content=gopro',
					'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
					'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
				),
				array(
					'value'         => 'inventory-modular',
					'alt'           => 'Modular',
					'img'           => esc_url( STM_LISTINGS_URL . '/assets/images/pro/inventory/inventory-modular.png' ),
					'disabled'      => ! is_mvl_pro(),
					'preview_url'   => 'https://motors-plugin.stylemixthemes.com/modular-inventory/',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					'pricing_url'   => 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wp-admin&utm_medium=push&utm_campaign=motors&utm_content=gopro',
					'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
					'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
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
