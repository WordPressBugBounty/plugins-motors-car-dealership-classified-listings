<?php
add_filter(
	'listing_settings_conf',
	function ( $conf_for_merge ) {
		$grid_title_dependency = apply_filters(
			'grid_title_dependency',
			array(
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
			)
		);

		$conf = array(
			'listing_directory_title_frontend' =>
				array(
					'label'       => esc_html__( 'Auto-Generate Listing Title', 'stm_vehicles_listing' ),
					'type'        => 'text',
					'value'       => '{make} {serie} {ca-year}',
					'description' => esc_html__( 'Automatically create listing titles based on custom fields. Use curly brackets to pull data, e.g., {make} {model} {year}. Leave blank to enter titles manually.', 'stm_vehicles_listing' ),
					'submenu'     => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
					'preview'     => STM_LISTINGS_URL . '/assets/images/previews/list-title.png',
				),
			'listing_view_type'                => array(
				'label'       => esc_html__( 'Default Desktop Layout for Listings', 'stm_vehicles_listing' ),
				'description' => esc_html__( 'Choose how listing pages are displayed by default on desktop. Users can still switch views if needed.', 'stm_vehicles_listing' ),
				'type'        => 'radio',
				'options'     => array(
					'grid' => 'Grid',
					'list' => 'List',
				),
				'image_top'   => array(
					'grid' => STM_LISTINGS_URL . '/assets/images/previews/grid_view_srp.svg',
					'list' => STM_LISTINGS_URL . '/assets/images/previews/list_view_srp.svg',
				),
				'value'       => 'list',
				'submenu'     => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			),
		);

		$conf = apply_filters( 'mvl_listing_card_settings', $conf );

		$mobile_view_type_deps = apply_filters( 'mobile_view_type_deps', '' );

		$conf = array_merge(
			$conf,
			array(
				'show_generated_title_as_label'        =>
				array(
					'label'       => esc_html__( 'First Two Words as Badge', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'description' => esc_html__( 'Show the first two words of the listing title as a badge on listing pages.', 'stm_vehicles_listing' ),
					'dependency'  =>
						array(
							'key'   => 'listing_view_type',
							'value' => 'list',
						),
					'submenu'     => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
					'preview'     => STM_LISTINGS_URL . '/assets/images/previews/first-two-param-srp.png',
				),
				'grid_title_max_length'                =>
				array_merge(
					array(
						'label'       => esc_html__( 'Listing title max length', 'stm_vehicles_listing' ),
						'description' => esc_html__( 'If the title will exceed the max length for symbols they\'ll be cropped with three dots displayed', 'stm_vehicles_listing' ),
						'type'        => 'text',
						'value'       => '44',
						'submenu'     => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
						'preview'     => STM_LISTINGS_URL . '/assets/images/previews/title-max-srp.png',
					),
					$grid_title_dependency,
				),
				'listing_directory_enable_dealer_info' =>
				array(
					'label'      => esc_html__( 'Author Details', 'stm_vehicles_listing' ),
					'type'       => 'checkbox',
					'dependency' => array(
						'key'   => 'listing_view_type',
						'value' => 'list',
					),
					'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
					'preview'    => STM_LISTINGS_URL . '/assets/images/previews/author-details-srp.png',
				),
				'show_listing_stock'                   =>
				array(
					'label'      => esc_html__( 'Listing ID', 'stm_vehicles_listing' ),
					'type'       => 'checkbox',
					'dependency'   => array(
						array(
							'key'   => 'listing_view_type',
							'value' => 'list',
						),
						array(
							'key'   => 'list_card_skin',
							'value' => 'default',
						),
					),
					'dependencies' => '&&',
					'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
					'preview'    => STM_LISTINGS_URL . '/assets/images/previews/listing-id-srp.png',
				),
			)
		);

		$skin_dependency = array(
			'show_listing_test_drive' => array(
				'label'      => esc_html__( 'Test Drive Button', 'stm_vehicles_listing' ),
				'type'       => 'checkbox',
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'preview'    => STM_LISTINGS_URL . '/assets/images/previews/test-drive-srp.png',
			),
			'show_listing_share'      => array(
				'label'      => esc_html__( 'Share Listing Button', 'stm_vehicles_listing' ),
				'type'       => 'checkbox',
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'preview'    => STM_LISTINGS_URL . '/assets/images/previews/share-srp.png',
			),
			'show_listing_pdf'        => array(
				'label'      => esc_html__( 'PDF Brochure Button', 'stm_vehicles_listing' ),
				'type'       => 'checkbox',
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'preview'    => STM_LISTINGS_URL . '/assets/images/previews/pdf-srp.png',
			),
			'show_listing_quote'      => array(
				'label'      => esc_html__( 'Request Listing Price Button', 'stm_vehicles_listing' ),
				'type'       => 'checkbox',
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'preview'    => STM_LISTINGS_URL . '/assets/images/previews/request-srp.png',
			),
			'show_listing_trade'      => array(
				'label'      => esc_html__( 'Trade-In Button', 'stm_vehicles_listing' ),
				'type'       => 'checkbox',
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'preview'    => STM_LISTINGS_URL . '/assets/images/previews/trade-in-srp.png',
			),
		);

		$conf = array_merge( $conf, apply_filters( 'btns_conf_skin_dependency', $skin_dependency ) );

		$conf = array_merge(
			$conf,
			array(
				'show_listing_compare'  =>
					array(
						'label'       => esc_html__( 'Compare Button', 'stm_vehicles_listing' ),
						'description' => esc_html__( 'Add a separate button on the listing card to compare vehicles.', 'stm_vehicles_listing' ),
						'type'        => 'checkbox',
						'submenu'     => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
						'preview'     => STM_LISTINGS_URL . '/assets/images/previews/grid-compare.png',
					),
				'enable_favorite_items' =>
					array(
						'label'       => esc_html__( 'Add to Favorites Button', 'stm_vehicles_listing' ),
						'description' => esc_html__( 'Let users save listings to their favorites by hovering over the image.', 'stm_vehicles_listing' ),
						'type'        => 'checkbox',
						'submenu'     => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
						'preview'     => STM_LISTINGS_URL . '/assets/images/previews/grid-favorites.png',
					),
			)
		);

		return array_merge( $conf_for_merge, $conf );
	},
	40,
	1
);
