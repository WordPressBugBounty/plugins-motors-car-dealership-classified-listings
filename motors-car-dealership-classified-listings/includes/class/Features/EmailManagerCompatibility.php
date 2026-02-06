<?php
namespace MotorsVehiclesListing\Features;

use MotorsVehiclesListing\Libs\Traits\Instance;

class EmailManagerCompatibility {
	use Instance;

	protected const SMART_TAGS_ASSOCIATIONS = array(
		'password_content' => 'password_reset_link',
		'best_time'        => 'date',
		'car'              => 'listing_id',
		'car_id'           => 'listing_id',
		'car_title'        => 'listing_title',
		'stm_year'         => 'year',
		'search_data_str'  => 'search_data',
		'user_name'        => 'user_login',
		'user_login'       => 'user_login',
	);

	protected const CONFIG_ASSOCIATIONS = array(
		'saved_search'            => 'saved_search',
		'welcome'                 => 'welcome',
		'new_user'                => 'new_user',
		'user_email_confirmation' => 'confirmation',
		'password_recovery'       => 'password_recovery',
		'request_for_a_dealer'    => 'become_dealer_request',
		'test_drive'              => 'test_drive',
		'request_price'           => 'request_price',
		'trade_in'                => 'trade_in',
		'trade_offer'             => 'trade_offer',
		'add_a_car'               => 'user_add_listing',
		'update_a_car'            => 'user_update_listing',
		'pay_per_listing'         => 'pay_per_listing',
		'update_a_car_ppl'        => 'updated_payment_listing',
		'report_review'           => 'report_review',
		'user_listing_wait'       => 'listing_waiting',
		'user_listing_approved'   => 'listing_approved',
		'message_to_dealer'       => 'message_to_dealer',
	);

	public function get_free_config_name( $config_name ) {
		$configs = array_flip( self::CONFIG_ASSOCIATIONS );
		return $configs[ $config_name ];
	}

	public function get_free_smart_tags( $smart_tags_args ) {
		$smart_tags  = array_flip( self::SMART_TAGS_ASSOCIATIONS );
		$free_values = array();
		foreach ( $smart_tags_args as $smart_tag => $value ) {
			if ( isset( $smart_tags[ $smart_tag ] ) ) {
				$free_values[ $smart_tags[ $smart_tag ] ] = $value;
			} else {
				$free_values[ $smart_tag ] = $value;
			}
		}
		return $free_values;
	}
}
