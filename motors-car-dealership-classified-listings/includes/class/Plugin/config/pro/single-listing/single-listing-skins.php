<?php
// add_filter( 'me_single_listing_template_settings_conf', 'mvl_single_listing_skins_list_conf', 20, 1 );

function mvl_single_listing_skins_list_conf( $conf ) {
	return array_merge(
		$conf,
		array(
			'listing_details_skin' => array(
				'type'    => 'nuxy-radio',
				'label'   => esc_html__( 'Listing Details Skins', 'stm_vehicles_listing' ),
				'value'   => 'skin_1',
				'options' => array(
					array(
						'value' => 'default',
						'alt'   => 'Default',
						'img'   => STM_LISTINGS_URL . '/assets/images/pro/listing-details/default.png',
					),
					array(
						'value'         => 'skin_1',
						'alt'           => 'Skin One',
						'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-details/skin-1.png',
						'disabled'      => ! apply_filters( 'is_mvl_pro', false ),
						'pro_img'       => esc_url( STM_LISTINGS_URL . '/assets/images/pro/unlock-pro-logo.svg' ),
						'preview_url'   => 'https://motors.stylemixthemes.com/',
						'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					),
					array(
						'value'         => 'skin_2',
						'alt'           => 'Skin Two',
						'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-details/skin-2.png',
						'disabled'      => ! apply_filters( 'is_mvl_pro', false ),
						'pro_img'       => esc_url( STM_LISTINGS_URL . '/assets/images/pro/unlock-pro-logo.svg' ),
						'preview_url'   => 'https://motors.stylemixthemes.com/',
						'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					),
				),
			),
		)
	);
}
