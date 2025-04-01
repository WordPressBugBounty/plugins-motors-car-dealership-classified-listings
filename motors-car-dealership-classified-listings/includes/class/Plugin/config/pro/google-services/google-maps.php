<?php
add_filter(
	'mvl_google_services_config',
	function ( $conf ) {
		if ( ! apply_filters( 'is_mvl_pro', false ) ) {
			$google_conf = array(
				'google_settings_banner' => array(
					'type'            => 'pro_banner',
					'label'           => esc_html__( 'Google Maps Feature', 'stm_vehicles_listing' ),
					'img'             => STM_LISTINGS_URL . '/assets/images/pro/google_settings_banner.png',
					'desc'            => esc_html__( 'Show the Google Maps on your site so users can view vehicle locations and search for nearby listings they’re interested in. It saves them time in finding the right vehicle.', 'stm_vehicles_listing' ),
					'submenu'         => esc_html__( 'Google Maps', 'stm_vehicles_listing' ),
					'second_btn_text' => esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					'second_btn_link' => esc_url( 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-plugin-settings/google-services/google-maps-pro-feature' ),
				),
			);
		} else {
			$google_conf = array(
				'google_api_key' => array(
					'label'       => esc_html__( 'Google Maps API key', 'stm_vehicles_listing' ),
					'type'        => 'text',
					'description' => sprintf( esc_html__( 'Get the necessary API key from this %s to integrate with Google Map', 'stm_vehicles_listing' ), '<a href="https://cloud.google.com/maps-platform/" target="_blank">link</a>' ),
					'submenu'     => esc_html__( 'Google Maps', 'stm_vehicles_listing' ),
					'group'       => 'started',
				),
				'google_pin'     => array(
					'label'       => esc_html__( 'Custom Google Maps pin image:', 'stm_vehicles_listing' ),
					'type'        => 'image',
					'description' => esc_html__( 'Add a custom pin image displayed on Google Maps', 'stm_vehicles_listing' ),
					'submenu'     => esc_html__( 'Google Maps', 'stm_vehicles_listing' ),
					'group'       => 'ended',
				),
			);
		}

		return array_merge( $conf, $google_conf );
	},
	10,
	1
);
