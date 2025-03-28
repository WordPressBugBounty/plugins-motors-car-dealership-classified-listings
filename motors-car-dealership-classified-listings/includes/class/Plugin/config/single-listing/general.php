<?php
add_filter(
	'single_listing_conf',
	function ( $conf_for_merge ) {

		$listing_img_settings = apply_filters( 'listing_img_settings', array() );

		$listing_img_settings['user_image_size_limit'] = array(
			'label'       => esc_html__( 'Max image size (Kb)', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Specify the maximum file size for uploaded images', 'stm_vehicles_listing' ),
			'type'        => 'text',
			'value'       => '4000',
			'submenu'     => esc_html__( 'General', 'stm_vehicles_listing' ),
		);

		$conf = array_merge( $conf_for_merge, $listing_img_settings );

		return array_merge( $conf_for_merge, $conf );
	},
	10,
	1
);
