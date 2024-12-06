<?php
add_action( 'add_classified_fields', 'set_classified_fields' );
function set_classified_fields( $manager ) {
	$manager->register_control(
		'vin_number',
		array(
			'type'    => 'text',
			'section' => 'stm_options',
			'preview' => 'vin',
			'label'   => esc_html__( 'VIN number', 'stm_vehicles_listing' ),
			'attr'    => array( 'class' => 'widefat' ),
		)
	);

	/**
	* Register stock/serial number controls from selected demo
	*/
	do_action( 'listing_stock_number_register_control', $manager );

	$manager->register_control(
		'registration_date',
		array(
			'type'    => 'datepicker',
			'section' => 'stm_options',
			'label'   => esc_html__( 'Manufacture Date', 'stm_vehicles_listing' ),
			'preview' => 'regist',
			'attr'    => array( 'class' => 'widefat' ),
		)
	);

	$manager->register_control(
		'city_mpg',
		array(
			'type'    => 'text',
			'section' => 'stm_options',
			'label'   => esc_html__( 'City Fuel Efficiency', 'stm_vehicles_listing' ),
			'attr'    => array( 'class' => 'widefat' ),
			'preview' => 'mpg',
		)
	);

	$manager->register_control(
		'highway_mpg',
		array(
			'type'    => 'text',
			'section' => 'stm_options',
			'label'   => esc_html__( 'Highway Fuel Efficiency', 'stm_vehicles_listing' ),
			'attr'    => array( 'class' => 'widefat' ),
			'preview' => 'hgw',
		)
	);
}

if ( ! has_action( 'listing_stock_number_register_control' ) ) {
	add_action( 'listing_stock_number_register_control', 'set_listing_stock_number_register_control' );
	function set_listing_stock_number_register_control( $manager ) {
		$manager->register_control(
			'stock_number',
			array(
				'type'    => 'text',
				'section' => 'stm_options',
				'preview' => 'stockid',
				'label'   => esc_html__( 'Stock number', 'stm_vehicles_listing' ),
				'attr'    => array( 'class' => 'widefat' ),
			)
		);
	}
}
