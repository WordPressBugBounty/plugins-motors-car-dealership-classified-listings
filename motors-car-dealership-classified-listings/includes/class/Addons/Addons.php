<?php

namespace MotorsVehiclesListing\Addons;

class Addons {
	private const OPTION_NAME = 'motors_vl_addons';

	public const VIN_DECODER  = 'vin_decoder';
	public const SOCIAL_LOGIN = 'social_login';
	public const SAVED_SEARCH = 'saved_search';

	public static function all(): array {
		return array(
			self::VIN_DECODER,
			self::SOCIAL_LOGIN,
			self::SAVED_SEARCH,
		);
	}

	public static function enabled_addons(): array {
		return array_map(
			function ( $value ) {
				return (bool) $value;
			},
			get_option( self::OPTION_NAME, array() )
		);
	}

	public static function list(): array {
		return array(
			self::VIN_DECODER  => array(
				'name'          => esc_html__( 'VIN Decoder', 'stm_vehicles_listing' ),
				'url'           => esc_url( STM_LISTINGS_URL . '/assets/addons/img/VinDecoder.png' ),
				'settings'      => admin_url( 'admin.php?page=vin_decoders_settings' ),
				'description'   => esc_html__( 'Get a comprehensive overview of a vehicle’s history with important details like make and mileage by VIN number. Connect up to 5 VIN services for comprehensive insights.', 'stm_vehicles_listing' ),
				'pro_url'       => 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=motorswpadmin&utm_campaign=motors-plugin&licenses=1&billing_cycle=annual',
				'documentation' => 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/vin-decoder',
				'img_url'       => 'vin-decoder',
			),

			self::SAVED_SEARCH => array(
				'name'          => esc_html__( 'Saved Searches', 'stm_vehicles_listing' ),
				'url'           => esc_url( STM_LISTINGS_URL . '/assets/addons/img/SavedSearch.png' ),
				'settings'      => admin_url( 'admin.php?page=mvl_search_and_filter_settings#saved_search' ),
				'description'   => esc_html__( 'Allow your visitors to create saved searches and get email notifications for new items that match their criteria. So they will stay updated with the latest posts in real-time.', 'stm_vehicles_listing' ),
				'pro_url'       => 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=motorswpadmin&utm_campaign=motors-plugin&licenses=1&billing_cycle=annual',
				'documentation' => 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/saved-searches',
				'img_url'       => 'saved-search',
			),

			self::SOCIAL_LOGIN => array(
				'name'          => esc_html__( 'Social Login', 'stm_vehicles_listing' ),
				'url'           => esc_url( STM_LISTINGS_URL . '/assets/addons/img/SocialLogin.png' ),
				'settings'      => admin_url( 'admin.php?page=mvl_plugin_settings#social_login' ),
				'description'   => esc_html__( 'Let users log in to your site quickly using their existing Google or Facebook accounts. Forget about remembering passwords – it’s just one click to get started!', 'stm_vehicles_listing' ),
				'pro_url'       => 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=motorswpadmin&utm_campaign=motors-plugin&licenses=1&billing_cycle=annual',
				'documentation' => 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/social-login',
				'img_url'       => 'social-login',
			),

		);
	}
}
