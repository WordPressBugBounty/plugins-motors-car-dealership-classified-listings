<?php
add_filter(
	'monetization_settings',
	function ( $conf_for_merge ) {

		if ( apply_filters( 'disable_monetization_paid_submission', false ) || ! apply_filters( 'is_mvl_pro', false ) ) {
			return $conf_for_merge;
		}

		$conf = array(
			'dealer_pay_per_listing'          =>
				array(
					'label'       => esc_html__( 'Charge for listing submissions', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Set the maximum number of listings that can be published for free.', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
				),
			'chargeable_listing_images_limit' => array(
				'label'       => esc_html__( 'Image limit per chargeable listing', 'stm_vehicles_listing' ),
				'description' => esc_html__( 'Specify the maximum number of images that can be uploaded for each chargeable listing.', 'stm_vehicles_listing' ),
				'type'        => 'text',
				'value'       => '0',
				'dependency'  => array(
					'key'   => 'dealer_pay_per_listing',
					'value' => 'not_empty',
				),
				'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
			),
			'pay_per_listing_price'           => array(
				'label'       => esc_html__( 'Price', 'stm_vehicles_listing' ),
				'description' => esc_html__( 'Enter the price users need to pay for each listing submission.', 'stm_vehicles_listing' ),
				'type'        => 'text',
				'value'       => '0',
				'dependency'  => array(
					'key'   => 'dealer_pay_per_listing',
					'value' => 'not_empty',
				),
				'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
			),
			'pay_per_listing_period'          => array(
				'label'       => esc_html__( 'Duration', 'stm_vehicles_listing' ),
				'description' => esc_html__( 'Set the duration for which each paid submission will remain active in days.', 'stm_vehicles_listing' ),
				'type'        => 'text',
				'value'       => '30',
				'dependency'  => array(
					'key'   => 'dealer_pay_per_listing',
					'value' => 'not_empty',
				),
				'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
			),
		);

		return array_merge( $conf_for_merge, $conf );
	},
	20,
	1
);
