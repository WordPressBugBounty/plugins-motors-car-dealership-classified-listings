<?php
add_filter(
	'mst_skin_settings_conf',
	function ( $conf ) {

		$fields = array(
			'mst_preloader_enabled'         =>
			array(
				'label' => esc_html__( 'Page Preloader', 'stm_vehicles_listing' ),
				'type'  => 'checkbox',
				'value' => false,
			),
			'mst_404_page'                  =>
				array(
					'type'          => 'select',
					'label'         => esc_html__( 'Choose 404 Page', 'stm_vehicles_listing' ),
					'options'       => mvl_nuxy_pages_list( true, __( 'Default', 'stm_vehicles_listing' ) ),
					'default_value' => 0,
					'description'   => esc_html__( 'Choose a page to display when a user lands on a missing or broken link', 'stm_vehicles_listing' ),
				),
			'mst_underconstruction_mode'    =>
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__( 'Under Construction Mode', 'stm_vehicles_listing' ),
					'value'       => false,
					'description' => esc_html__( 'Display a maintenance page for visitors while you update your site. Admins will still have access.', 'stm_vehicles_listing' ),
					'group'       => 'started',
				),
			'mst_underconstruction_page_id' =>
				array(
					'type'        => 'select',
					'label'       => esc_html__( 'Choose Under Construction Page', 'stm_vehicles_listing' ),
					'description' => 'Select a page for the Under Construction Mode setting to work.',
					'options'     => mvl_nuxy_pages_list( false ),
					'dependency'  => array(
						'key'   => 'mst_underconstruction_mode',
						'value' => 'not_empty',
					),
					'group'       => 'ended',
				),
		);

		$conf['general'] = array(
			'name'   => esc_html__( 'General', 'stm_vehicles_listing' ),
			'fields' => $fields,
		);

		return $conf;
	}
);
