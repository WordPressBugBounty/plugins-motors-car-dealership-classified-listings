<?php


namespace MotorsVehiclesListing\Addons;

use MotorsVehiclesListing\Addons\Addons;

class AddonsPage {
	public function __construct() {
		add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', array( $this, 'add_menu' ) );
	}

	public function add_menu() {

		$is_locked = apply_filters( 'is_mvl_pro', false ) ? '' : 'mvl-addons-locked';

		add_submenu_page(
			'mvl_plugin_settings',
			__( 'Pro Addons', 'stm_vehicles_listing' ),
			'<span class="mvl-addons-menu ' . $is_locked . '"><span class="mvl-addons-pro">PRO</span> <span class="mvl-addons-text">' . __( 'Addons', 'stm_vehicles_listing' ) . '</span></span>',
			'manage_options',
			'mvl-addons',
			array( $this, 'addons_page' ),
		);

		add_filter(
			'mvl_submenu_positions',
			function ( $positions ) {
				$positions['mvl-addons'] = 100;

				return $positions;
			}
		);
	}

	public function addons_page() {
		$addons           = Addons::list();
		$enabled_addons   = get_option( 'motors_vl_addons', array() );
		$mvl_addons_nonce = wp_create_nonce( 'mvl_addons_nonce' );

		wp_enqueue_style( 'mvl-addons', STM_LISTINGS_URL . '/assets/css/addons.css', array(), STM_LISTINGS_V );
		wp_enqueue_script( 'mvl-addons', STM_LISTINGS_URL . '/assets/js/admin/addons.js', array( 'jquery' ), STM_LISTINGS_V, true );
		wp_localize_script(
			'mvl-addons',
			'mvl_addons',
			array(
				'enabled_addons'   => wp_json_encode( $enabled_addons, JSON_FORCE_OBJECT ),
				'mvl_addons_nonce' => $mvl_addons_nonce,
			)
		);

		stm_listings_load_template( 'addons/main', compact( 'addons', 'enabled_addons' ) );
	}
}
