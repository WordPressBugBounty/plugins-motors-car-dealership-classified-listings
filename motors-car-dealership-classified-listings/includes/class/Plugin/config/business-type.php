<?php

use MotorsVehiclesListing\Plugin\MVL_Const;
use MotorsVehiclesListing\Plugin\Settings;

add_filter(
	'wpcfto_field_mvl_business_type_selector',
	function () {
		return STM_LISTINGS_PATH . '/includes/nuxy/custom-fields/business-type-selector.php';
	}
);

add_action(
	'admin_init',
	function () {
		if ( is_mvl_pro() ) {
			return;
		}

		$settings = get_option( MVL_Const::MVL_PLUGIN_OPT_NAME, array() );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		if ( isset( $settings['motors_business_type'] ) && 'dealership' === $settings['motors_business_type'] ) {
			return;
		}

		$settings['motors_business_type'] = 'dealership';
		update_option( MVL_Const::MVL_PLUGIN_OPT_NAME, $settings );
	}
);

add_filter(
	'pre_update_option_' . MVL_Const::MVL_PLUGIN_OPT_NAME,
	function ( $value ) {
		if ( is_mvl_pro() || ! is_array( $value ) ) {
			return $value;
		}

		$value['motors_business_type'] = 'dealership';

		return $value;
	}
);

add_filter(
	'mvl_get_all_nuxy_config',
	function ( $global_conf ) {
		if ( is_mvl_pro() ) {
			return $global_conf;
		}

		$global_conf['business_type'] = array(
			'name'   => esc_html__( 'Business Type', 'stm_vehicles_listing' ),
			'fields' => array(
				'motors_business_type' => array(
					'label'              => esc_html__( 'Business Type', 'stm_vehicles_listing' ),
					'description'        => esc_html__( 'Defines how Motors handles listings across your site. Dealership is included; Classified and Rental require Motors Pro.', 'stm_vehicles_listing' ),
					'type'               => 'mvl_business_type_selector',
					'options'            => array(
						'dealership' => esc_html__( 'Dealership', 'stm_vehicles_listing' ),
						'classified' => esc_html__( 'Classified', 'stm_vehicles_listing' ),
						'rental'     => esc_html__( 'Rental', 'stm_vehicles_listing' ),
					),
					'locked'             => array(
						'classified' => true,
						'rental'     => true,
					),
					'pro_url'            => Settings::$pro_plans_url,
					'change_text'        => esc_html__( 'Change business type', 'stm_vehicles_listing' ),
					'upgrade_text'       => esc_html__( 'Upgrade to Pro', 'stm_vehicles_listing' ),
					'active_text'        => esc_html__( 'Active mode', 'stm_vehicles_listing' ),
					'running_text'       => esc_html__( 'Running', 'stm_vehicles_listing' ),
					'prompt_text'        => esc_html__( 'Need a different workflow? Choose another business type, then save your settings.', 'stm_vehicles_listing' ),
					'choose_text'        => esc_html__( 'Choose your business type', 'stm_vehicles_listing' ),
					'choose_description' => esc_html__( 'Select the workflow that matches how listings are managed on this site.', 'stm_vehicles_listing' ),
					'notice_text'        => esc_html__( 'With Motors Pro, changing business type moves existing listings to Draft. Review and update each listing for the selected workflow, then publish it again.', 'stm_vehicles_listing' ),
					'close_text'         => esc_html__( 'Close', 'stm_vehicles_listing' ),
					'done_text'          => esc_html__( 'Continue', 'stm_vehicles_listing' ),
					'value'              => 'dealership',
				),
			),
		);

		return $global_conf;
	},
	10,
	1
);
