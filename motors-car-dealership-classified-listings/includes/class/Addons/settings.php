<?php

use MotorsVehiclesListing\Addons\Addons;

if ( ! defined( 'STM_LISTINGS_PRO_PATH' ) && ! stm_is_motors_theme() ) {
	add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', 'addons_add_submenu_pages', 100 );

	function addons_add_submenu_pages() {
		if ( ! defined( 'STM_LMS_PRO_PATH' ) ) {
			$addons   = Addons::list();
			$position = 110;

			foreach ( $addons as $key => $addon ) {
				add_submenu_page(
					'mvl_plugin_settings',
					$addon['name'],
					$addon['name'],
					'manage_options',
					$key,
					function () use ( $key ) {
						unlock_addons_callback( $key );
					}
				);

				add_filter(
					'mvl_submenu_positions',
					function ( $positions ) use ( $key, $position ) {
						$positions[ $key ] = $position;
						return $positions;
					}
				);
			}
		}
	}

	function unlock_addons_callback( $addon ) {
		$version = ( WP_DEBUG ) ? time() : STM_LISTINGS_V;

		wp_enqueue_style( 'mvl_unlock_addons', STM_LISTINGS_URL . '/assets/css/unlock_addons.css', null, $version );
		require_once STM_LISTINGS_PATH . '/templates/journey/free-journey-addons-sidebars.php';
	}
}

function mvl_addons_dynamic_url( $addon_key ) {
	$addons_site_url = 'https://stylemixthemes.com/car-dealer-plugin/addons/';
	$addon_urls      = array(
		'social-login' => 'social-login',
		'saved-search' => 'saved-search',
		'vin-decoder'  => 'vin-decoder',
	);

	return $addons_site_url . ( $addon_urls[ $addon_key ] ?? $addon_key ) . '/';
}