<?php
add_filter(
	'user_settings_main',
	function ( $conf ) {
		return array_merge(
			$conf,
			array(
				'allow_user_register_as_dealer' => array(
					'label'       => esc_html__( 'Dealer Registration', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'A registration form will have an option for dealer to sign up', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'value'       => true,
					'pro'         => true,
					'pro_url'     => \MotorsVehiclesListing\Plugin\Settings::$pro_plans_url,
					'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
				),
				'users_posts_title'             => array(
					'type'    => 'group_title',
					'label'   => esc_html__( 'Listings Display Options', 'stm_vehicles_listing' ),
					'submenu' => esc_html__( 'General', 'stm_vehicles_listing' ),
					'group'   => 'started',
				),
				'show_more_button_user_profile' => array(
					'label'       => esc_html__( 'Show More Button', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Use a "Show More" button to load more listings on a seller\'s page instead of pagination.', 'stm_vehicles_listing' ),
					'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
				),
				'post_per_page_user_inventory'  => array(
					'label'       => esc_html__( 'Listings Per Page', 'stm_vehicles_listing' ),
					'type'        => 'number',
					'value'       => '6',
					'group'       => 'ended',
					'description' => esc_html__( 'The number of listings displayed on a seller\'s page', 'stm_vehicles_listing' ),
					'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
				),

			)
		);
	}
);
