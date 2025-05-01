<?php
add_filter(
	'mst_skin_settings_conf',
	function ( $global_conf ) {
		$global_conf['header'] = array(
			'name'   => esc_html__( 'Header', 'stm_vehicles_listing' ),
			'fields' => apply_filters( 'mst_skin_settings_header_conf', array() ),
		);
		return $global_conf;
	}
);
