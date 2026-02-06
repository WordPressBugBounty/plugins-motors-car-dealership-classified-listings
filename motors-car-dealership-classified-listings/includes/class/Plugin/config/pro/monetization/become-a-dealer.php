<?php
add_filter(
	'mvl_monetization_subscription_settings',
	function ( $global_conf ) {
		if ( ! apply_filters( 'is_mvl_pro', false ) || apply_filters( 'disable_monetization_subscription', false ) ) {
			return $global_conf;
		}

		$dealer_conf = array(
			'become_a_dealer' => array(
				'label'       => esc_html__( 'Become a dealer button', 'stm_vehicles_listing' ),
				'description' => esc_html__( 'Add a button for users to sign up and become registered dealers', 'stm_vehicles_listing' ),
				'type'        => 'checkbox',
				'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
			),
		);

		if ( apply_filters( 'stm_is_motors_theme', false ) && ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) ) {
			$dealer_conf['become_a_dealer']['dependency'] = array(
				'key'   => 'enable_plans',
				'value' => 'empty',
			);
		}

		return array_merge( $global_conf, $dealer_conf );
	},
	20,
	1
);
