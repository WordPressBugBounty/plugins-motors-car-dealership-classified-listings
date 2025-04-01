<?php
add_filter(
	'search_settings_conf',
	function ( $conf_for_merge ) {
		if ( ! apply_filters( 'is_mvl_pro', false ) ) {
			$conf = array(
				'location_settings_banner' => array(
					'type'            => 'pro_banner',
					'label'           => esc_html__( 'Filter by Location Feature', 'stm_vehicles_listing' ),
					'img'             => STM_LISTINGS_URL . '/assets/images/pro/location_settings_banner.png',
					'desc'            => esc_html__( 'Let users search listings based on their preferred location. It allows potential buyers to narrow down their search to listings in their area, making it quicker for them to find the vehicle nearby.', 'stm_vehicles_listing' ),
					'second_btn_text' => esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					'second_btn_link' => esc_url( 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/listing-manager-settings/search-filters#filter-by-location-pro-feature' ),
					'preview'     => STM_LISTINGS_URL . '/assets/images/previews/filter-by-location-sf.png',
				),
			);
		} else {
			$conf = array(
				'enable_location'                 => array(
					'label'       => esc_html__( 'Filter by location', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'The search results can be filtered based on location', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'preview'     => STM_LISTINGS_URL . '/assets/images/previews/filter-by-location-sf.png',
				),
				'enable_distance_search'          => array(
					'label'       => esc_html__( 'Sort by distance', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'The results will be shown from the nearest to the farthest for the location', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'dependency'  => array(
						'key'   => 'enable_location',
						'value' => 'not_empty',
					),
					'value'       => true,
				),
				'distance_measure_unit'           => array(
					'label'        => esc_html__( 'Unit of measurement', 'stm_vehicles_listing' ),
					'description'  => esc_html__( 'Define a measuring unit for distance', 'stm_vehicles_listing' ),
					'type'         => 'select',
					'options'      =>
						array(
							'miles'      => esc_html__( 'Miles', 'stm_vehicles_listing' ),
							'kilometers' => esc_html__( 'Kilometers', 'stm_vehicles_listing' ),
						),
					'dependency'   => array(
						array(
							'key'   => 'enable_location',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'enable_distance_search',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'value'        => 'miles',
				),
				'distance_search'                 => array(
					'label'        => esc_html__( 'Max search radius', 'stm_vehicles_listing' ),
					'description'  => esc_html__( 'Put the maximum search radius', 'stm_vehicles_listing' ),
					'type'         => 'text',
					'dependency'   => array(
						array(
							'key'   => 'enable_location',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'enable_distance_search',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
				),
				'recommend_items_empty_result'    => array(
					'label'        => esc_html__( 'Recommend vehicles in other locations in case of empty result', 'stm_vehicles_listing' ),
					'description'  => esc_html__( 'If there are no search results there will be a list of other recommended vehicles', 'stm_vehicles_listing' ),
					'type'         => 'checkbox',
					'dependency'   => array(
						array(
							'key'   => 'enable_location',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'enable_distance_search',
							'value' => 'empty',
						),
					),
					'value'        => true,
					'dependencies' => '&&',
				),
				'recommend_distance_measure_unit' => array(
					'label'        => esc_html__( 'Unit of measurement', 'stm_vehicles_listing' ),
					'description'  => esc_html__( 'Define a measuring unit for distance', 'stm_vehicles_listing' ),
					'type'         => 'select',
					'options'      =>
						array(
							'miles'      => esc_html__( 'Miles', 'stm_vehicles_listing' ),
							'kilometers' => esc_html__( 'Kilometers', 'stm_vehicles_listing' ),
						),
					'dependency'   => array(
						array(
							'key'   => 'enable_location',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'enable_distance_search',
							'value' => 'empty',
						),
						array(
							'key'   => 'recommend_items_empty_result',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
					'value'        => 'miles',
				),
				'recommend_distance_search'       => array(
					'label'        => esc_html__( 'Max search radius', 'stm_vehicles_listing' ),
					'description'  => esc_html__( 'Put the maximum search radius', 'stm_vehicles_listing' ),
					'type'         => 'text',
					'dependency'   => array(
						array(
							'key'   => 'enable_location',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'enable_distance_search',
							'value' => 'empty',
						),
						array(
							'key'   => 'recommend_items_empty_result',
							'value' => 'not_empty',
						),
					),
					'dependencies' => '&&',
				),
			);
		}

		return array_merge( $conf_for_merge, $conf );
	},
	40,
	1
);
