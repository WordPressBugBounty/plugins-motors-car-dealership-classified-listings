<?php
add_filter(
	'listing_settings_conf',
	function ( $conf_for_merge ) {
		$conf = array(
			'friendly_url'              => array(
				'label'       => esc_html__( 'SEO-Friendly URL', 'stm_vehicles_listing' ),
				'description' => esc_html__( 'All listing links leading to the Inventory page will be turned into SEO-friendly URLs.', 'stm_vehicles_listing' ),
				'pro'         => true,
				'pro_url'     => \MotorsVehiclesListing\Plugin\Settings::$pro_plans_url,
				'preview'          => STM_LISTINGS_URL . '/assets/images/previews/seo-url-srp.png',
				'preview_position' => 'preview_bottom',
				'type'        => 'checkbox',
				'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
			),
			'gallery_hover_interaction' => array(
				'label'       => esc_html__( 'Image sliding on Hover', 'stm_vehicles_listing' ),
				'description' => esc_html__( 'When hovering the listing images will be previewed as a slider', 'stm_vehicles_listing' ),
				'pro'         => true,
				'pro_url'     => \MotorsVehiclesListing\Plugin\Settings::$pro_plans_url,
				'type'        => 'checkbox',
				'preview'          => STM_LISTINGS_URL . '/assets/images/previews/image-slide-srp.png',
				'preview_position' => 'preview_bottom',
				'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
			),
		);

		if ( ! apply_filters( 'is_mvl_pro', false ) ) {
			$sold_settings = array(
				'show_sold_settings_banner' => array(
					'type'            => 'pro_banner',
					'label'           => esc_html__( 'Sold Status for Listings', 'stm_vehicles_listing' ),
					'img'             => STM_LISTINGS_URL . '/assets/images/pro/show_sold_settings_banner.png',
					'desc'            => esc_html__( 'Show "Sold" status for vehicles to keep your listings transparent and avoid confusion. This feature marks vehicles that are no longer available, so users always know which listings are still active.', 'stm_vehicles_listing' ),
					'submenu'         => esc_html__( 'General', 'stm_vehicles_listing' ),
					'second_btn_text' => esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					'second_btn_link' => esc_url( 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/listing-manager-settings/search-results-page/general#sold-status-for-vehicles-pro-feature' ),
				),
			);
		} else {
			$sold_settings = array(
				'show_sold_listings'  => array(
					'label'       => esc_html__( 'Sold status for vehicles', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Sold vehicles will have a Sold badge', 'stm_vehicles_listing' ),
					'pro'         => true,
					'pro_url'     => \MotorsVehiclesListing\Plugin\Settings::$pro_plans_url,
					'type'        => 'checkbox',
					'preview'          => STM_LISTINGS_URL . '/assets/images/previews/sold-status-srp.png',
					'preview_position' => 'preview_bottom',
					'value'       => false,
					'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
					'group'       => 'started',
				),
				'sold_badge_bg_color' => array(
					'label'       => esc_html__( 'Sold Badge Color', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Choose the color for a badge to show sold vehicles', 'stm_vehicles_listing' ),
					'type'        => 'color',
					'mode'        => 'background-color',
					'value'       => '#fc4e4e',
					'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
					'group'       => 'ended',
					'dependency'  => array(
						'key'   => 'show_sold_listings',
						'value' => 'not_empty',
					),
				),
			);
		}

		$conf = array_merge( $conf, $sold_settings );

		return array_merge( $conf_for_merge, $conf );
	},
	22,
	1
);
