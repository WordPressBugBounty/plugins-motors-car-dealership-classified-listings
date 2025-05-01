<?php
add_filter(
	'mst_skin_settings_header_conf',
	function ( $conf_fields ) {
		$fields = array(
			'mst_header_socials_enable'   =>
				array(
					'label'      => esc_html__( 'Header Social Icons', 'stm_vehicles_listing' ),
					'type'       => 'multi_checkbox',
					'options'    => apply_filters( 'mst_socials_list', array() ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'submenu'    => esc_html__( 'Social Icons', 'stm_vehicles_listing' ),
				),
			'header_socials_empty_notice' =>
				array(
					'label'      => esc_html__( 'Settings Not Available For This Header Type', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||default',
					),
					'submenu'    => esc_html__( 'Social Icons', 'stm_vehicles_listing' ),
				),
		);

		return array_merge( $conf_fields, $fields );
	},
	30
);
