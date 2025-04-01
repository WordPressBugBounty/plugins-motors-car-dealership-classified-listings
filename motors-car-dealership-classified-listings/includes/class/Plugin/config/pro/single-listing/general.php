<?php
add_filter(
	'listing_img_settings',
	function ( $conf_for_merge ) {
		if ( ! apply_filters( 'is_mvl_pro', false ) ) {
			$conf = array(
				'optimize_image_settings_banner' => array(
					'type'            => 'pro_banner',
					'label'           => esc_html__( 'Image Optimization', 'stm_vehicles_listing' ),
					'img'             => STM_LISTINGS_URL . '/assets/images/pro/optimize_image_settings_banner.png',
					'desc'            => esc_html__( 'Crop images to the right dimensions, set custom width and height and control maximum upload size to ensure fast page load times and a smooth user experience. Keep your site visually appealing while optimizing performance.', 'stm_vehicles_listing' ),
					'submenu'         => esc_html__( 'General', 'stm_vehicles_listing' ),
					'second_btn_text' => esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					'second_btn_link' => esc_url( 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/listing-manager-settings/listing-page-details/general#optimizing-image-pro-feature' ),
				),
			);
		} else {
			$conf = array(
				'user_image_crop_optimized' => array(
					'type'    => 'group_title',
					'label'   => esc_html__( 'Optimizing Image', 'stm_vehicles_listing' ),
					'submenu' => esc_html__( 'General', 'stm_vehicles_listing' ),
					'group'   => 'started',
				),
				'user_image_crop_checkbox'  => array(
					'label'       => esc_html__( 'Image cropping', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Customize the dimensions of uploaded images', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'value'       => false,
					'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
				),
				'user_image_crop_width'     =>
					array(
						'label'        => esc_html__( 'Width for cropped images', 'stm_vehicles_listing' ),
						'description'  => esc_html__( 'Set the desired width for cropped images', 'stm_vehicles_listing' ),
						'type'         => 'text',
						'value'        => '800',
						'dependency'   => array(
							array(
								'key'   => 'user_image_crop_checkbox',
								'value' => 'not_empty',
							),
						),
						'dependencies' => '&&',
						'submenu'      => esc_html__( 'General', 'stm_vehicles_listing' ),
					),
				'user_image_crop_height'    =>
					array(
						'label'        => esc_html__( 'Height for cropped images', 'stm_vehicles_listing' ),
						'description'  => esc_html__( 'Define the preferred height for cropped images', 'stm_vehicles_listing' ),
						'type'         => 'text',
						'value'        => '600',
						'dependency'   => array(
							array(
								'key'   => 'user_image_crop_checkbox',
								'value' => 'not_empty',
							),
						),
						'dependencies' => '&&',
						'submenu'      => esc_html__( 'General', 'stm_vehicles_listing' ),
						'group'        => 'ended',
					),
			);
		}

		return array_merge( $conf_for_merge, $conf );
	},
	14,
	1
);
