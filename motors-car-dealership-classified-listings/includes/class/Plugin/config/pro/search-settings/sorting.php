<?php
add_filter(
	'search_settings_conf',
	function ( $conf_for_merge ) {
		$conf = array(
			'sort_options' =>
				array(
					'label'   => esc_html__( 'Sort by custom fields', 'stm_vehicles_listing' ),
					'type'    => 'multi_checkbox',
					'options' => mvl_nuxy_sort_options(),
					'preview'     => STM_LISTINGS_URL . '/assets/images/previews/filter-by-sf.png',
				),
		);

		return array_merge( $conf_for_merge, $conf );
	},
	23,
	1
);
