<?php
add_filter( 'single_listing_layout', 'single_listing_layout', 20, 1 );
function single_listing_layout( $conf ) {
	$conf = array_merge(
		$conf,
		array(
			'show_calculator'          =>
				array(
					'label'       => esc_html__( 'Loan calculator', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Enable a loan calculator and configure the settings in the next section', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'pro'         => true,
					'pro_url'     => \MotorsVehiclesListing\Plugin\Settings::$pro_plans_url,
					'submenu'     => esc_html__( 'Page layout', 'stm_vehicles_listing' ),
				),
			'stm_show_seller_whatsapp' =>
				array(
					'label'       => esc_html__( 'WhatsApp contact button', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Add a button for users to contact sellers via WhatsApp', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'pro'         => true,
					'pro_url'     => \MotorsVehiclesListing\Plugin\Settings::$pro_plans_url,
					'submenu'     => esc_html__( 'Page layout', 'stm_vehicles_listing' ),
				),
		)
	);

	return $conf;
}