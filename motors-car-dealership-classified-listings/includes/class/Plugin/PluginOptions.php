<?php
namespace MotorsVehiclesListing\Plugin;

use MotorsVehiclesListing\Plugin\MVL_Const;

class PluginOptions {
	private static $instance   = null;
	public static $options_map = array();

	private function __construct() {
		$plugin_opts           = get_option( MVL_Const::MVL_PLUGIN_OPT_NAME, array() );
		$add_car_form_opts     = get_option( MVL_Const::ADD_CAR_FORM_OPT_NAME, array() );
		$filter_opts           = get_option( MVL_Const::FILTER_OPT_NAME, array() );
		$listing_details_opts  = get_option( MVL_Const::LISTING_DETAILS_OPT_NAME, array() );
		$search_results_opts   = get_option( MVL_Const::SEARCH_RESULTS_OPT_NAME, array() );
		$listing_template_opts = get_option( MVL_Const::LISTING_TEMPLATE_OPT_NAME, array() );

		self::$options_map = apply_filters(
			'mvl_options_map',
			array_merge(
				(array) $plugin_opts,
				(array) $add_car_form_opts,
				(array) $filter_opts,
				(array) $listing_details_opts,
				(array) $search_results_opts,
				(array) $listing_template_opts
			)
		);

		$plugin_opts_keys = array(
			'featured_listing_price',
			'free_listing_submission',
			'dealer_free_listing_submission',
			'user_post_limit',
			'dealer_post_limit',
			'new_user_registration',
			'allow_user_register_as_dealer',
			'dealer_pay_per_listing',
			'dealer_payments_for_featured_listing',
			'chargeable_listing_images_limit',
		);

		foreach ( $plugin_opts_keys as $key ) {
			if ( isset( $plugin_opts[ $key ] ) ) {
				self::$options_map[ $key ] = $plugin_opts[ $key ];
			}
		}
	}

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new PluginOptions();
		}

		return self::$instance;
	}
}
