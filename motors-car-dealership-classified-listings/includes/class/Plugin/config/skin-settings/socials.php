<?php
add_filter(
	'mst_skin_settings_conf',
	function( $global_conf ) {
		$conf = array(
			'name'   => esc_html__( 'Social Links', 'stm_vehicles_listing' ),
			'fields' =>
				array(
					'mst_socials_link' =>
						array(
							'label'   => esc_html__( 'Socials Links', 'stm_vehicles_listing' ),
							'type'    => 'multi_input',
							'options' => apply_filters( 'mst_socials_options', array() ),
						),
				),
		);

		$global_conf['socials'] = $conf;

		return $global_conf;
	},
	55,
	1
);
