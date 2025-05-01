<?php

add_filter(
	'mst_skin_settings_header_conf',
	function ( $conf_fields ) {
		$fields = array(
			'mst_header_logo'            =>
				array(
					'label'       => esc_html__( 'Logo', 'stm_vehicles_listing' ),
					'type'        => 'image',
					'submenu'     => esc_html__( 'Logo & Title', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Upload your logo image or enter an image URL.', 'stm_vehicles_listing' ),
					'dependency'  => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
				),
			'mst_header_logo_width'      =>
				array(
					'label'        => esc_html__( 'Logo Max Width (px)', 'stm_vehicles_listing' ),
					'description'  => esc_html__( 'The height of the image will increase proportionately', 'stm_vehicles_listing' ),
					'type'         => 'number',
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_logo',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'submenu'      => esc_html__( 'Logo & Title', 'stm_vehicles_listing' ),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-logo-link img',
								'.header-classic .header-logo-link img',
								'.header-light .header-logo-link img',
								'max-width' => '{{value}}px',
							),
						),
					),
				),
			'mst_header_logo_typography' =>
				array(
					'label'       => esc_html__( 'Site Title Typography', 'stm_vehicles_listing' ),
					'type'        => 'typography',
					'description' => __( 'Customize the font, size, and style of your site’s title if you\'re not using a logo.' ),
					'dependency'  => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
					'submenu'     => esc_html__( 'Logo & Title', 'stm_vehicles_listing' ),
					'output_css'  => array(
						'any' => array(
							array_merge(
								array(
									'.header-focus .header-logo-link h1',
									'.header-classic .header-logo-link h1',
									'.header-light .header-logo-link h1',
								),
								MotorsStarterTheme\Services\OutputCSS::TYPOGRAPHY,
							),
						),
					),
				),
			'mst_logo_empty_notice'      =>
				array(
					'label'      => esc_html__( 'Settings Not Available For This Header Type', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'default',
					),
					'submenu'    => esc_html__( 'Logo & Title', 'stm_vehicles_listing' ),
				),
		);

		return array_merge( $conf_fields, $fields );
	},
	20
);
