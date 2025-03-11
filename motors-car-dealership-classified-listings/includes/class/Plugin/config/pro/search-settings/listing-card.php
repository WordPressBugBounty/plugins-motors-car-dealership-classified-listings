<?php
add_filter( 'mvl_listing_card_settings', 'mvl_listing_grid_card_settings' );

function mvl_listing_grid_card_settings( $conf ) {
	if ( stm_is_motors_theme() ) {
		return $conf;
	}

	$filter_options = apply_filters( 'stm_get_single_car_listings', array() );

	$taxonomies = array( '' => esc_html__( 'None', 'stm_vehicles_listing' ) );

	if ( ! empty( $filter_options ) ) {
		foreach ( $filter_options as $filter_option ) {
			$taxonomies[ $filter_option['slug'] ] = $filter_option['single_name'];
		}
	}

	return array_merge(
		$conf,
		array(
			'grid_card_skin'      => array(
				'type'       => 'nuxy-radio',
				'label'      => esc_html__( 'Grid Card Skins', 'stm_vehicles_listing' ),
				'value'      => 'skin_1',
				'options'    => array(
					array(
						'value' => 'default',
						'alt'   => 'Classic',
						'img'   => STM_LISTINGS_URL . '/assets/images/pro/listing-card/grid-default.png',
					),
					array(
						'value'         => 'skin_1',
						'alt'           => 'Modern',
						'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-card/grid-1.png',
						'disabled'      => ! is_mvl_pro(),
						'preview_url'   => 'https://motors-plugin.stylemixthemes.com/inventory/?mvl=pro',
						'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
						'pricing_url'   => admin_url( 'admin.php?page=mvl-go-pro' ),
						'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
						'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
					),
				),
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'group'      => 'started',
			),
			'grid_skin_show_logo' => array(
				'label'        => esc_html__( 'Field to display as logo', 'stm_vehicles_listing' ),
				'type'         => 'select',
				'options'      => $taxonomies,
				'default'      => 'make',
				'description'  => esc_html__( 'Choose a custom field that will be displayed as the logo on listing cards', 'stm_vehicles_listing' ),
				'dependency'   => array(
					array(
						'key'   => 'listing_view_type',
						'value' => 'grid',
					),
					array(
						'key'   => 'grid_card_skin',
						'value' => 'skin_1||skin_2||skin_3||skin_4||skin_5||skin_6',
					),
				),
				'dependencies' => '&&',
				'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-logo.png',
				'group'        => 'ended',
			),
			'list_card_skin'      => array(
				'type'       => 'data_select',
				'label'      => esc_html__( 'List Card Skins', 'stm_vehicles_listing' ),
				'value'      => 'skin_1',
				'options'    => array(
					array(
						'value' => 'default',
						'alt'   => 'Classic',
						'img'   => STM_LISTINGS_URL . '/assets/images/pro/listing-card/list-default.png',
					),
					array(
						'value'         => 'skin_1',
						'alt'           => 'Modern',
						'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-card/list-1.png',
						'disabled'      => ! is_mvl_pro(),
						'preview_url'   => 'https://motors-plugin.stylemixthemes.com/inventory/?mvl=pro',
						'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
						'pricing_url'   => admin_url( 'admin.php?page=mvl-go-pro' ),
						'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
						'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
					),
				),
				'dependency' => array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'group'      => 'started',
			),
			'list_skin_show_logo' => array(
				'label'        => esc_html__( 'Field to display as logo', 'stm_vehicles_listing' ),
				'type'         => 'select',
				'options'      => $taxonomies,
				'default'      => 'make',
				'description'  => esc_html__( 'Choose a custom field that will be displayed as the logo on listing cards', 'stm_vehicles_listing' ),
				'dependency'   => array(
					array(
						'key'   => 'listing_view_type',
						'value' => 'list',
					),
					array(
						'key'   => 'list_card_skin',
						'value' => 'skin_1||skin_2',
					),
				),
				'dependencies' => '&&',
				'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
				'preview'      => STM_LISTINGS_URL . '/assets/images/previews/list-logo.png',
				'group'        => 'ended',
			),
		)
	);
}

add_filter( 'btns_conf_skin_dependency', 'mvl_btns_conf_skin_dependency', 10, 1 );
function mvl_btns_conf_skin_dependency( $conf ) {

	if ( stm_is_motors_theme() ) {
		return $conf;
	}

	$list_skins = 'skin_1||skin_2';
	$grid_skins = 'skin_1||skin_2||skin_3||skin_4||skin_5';

	$list_conf = array(
		'show_view_details_button'       => array(
			'label'      => esc_html__( 'View Details Button', 'stm_vehicles_listing' ),
			'type'       => 'checkbox',
			'dependency' => array(
				'key'   => 'listing_view_type',
				'value' => 'list',
			),
			'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'    => STM_LISTINGS_URL . '/assets/images/previews/list-btns.png',
			'group'      => 'started',
		),
		'show_view_details_title'        => array(
			'label'        => esc_html__( 'Button Title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'show_view_details_button',
					'value' => 'not_empty',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_view_detail_icon'          => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'show_view_details_button',
					'value' => 'not_empty',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*ended View Details*/
		'show_listing_test_drive'        => array(
			'label'      => esc_html__( 'Test Drive', 'stm_vehicles_listing' ),
			'type'       => 'checkbox',
			'dependency' => array(
				'key'   => 'listing_view_type',
				'value' => 'list',
			),
			'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'    => STM_LISTINGS_URL . '/assets/images/previews/list-popup-btns.png',
			'group'      => 'started',
		),
		'show_listing_test_drive_as_btn' => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/list-btns.png',
		),
		'show_listing_test_drive_title'  => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_test_drive_icon'   => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*ended Test Drive*/
		'show_listing_share'             => array(
			'label'      => esc_html__( 'Share Listing Button', 'stm_vehicles_listing' ),
			'type'       => 'checkbox',
			'dependency' => array(
				'key'   => 'listing_view_type',
				'value' => 'list',
			),
			'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'    => STM_LISTINGS_URL . '/assets/images/previews/list-popup-btns.png',
			'group'      => 'started',
		),
		'show_listing_share_as_btn'      => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/list-btns.png',
		),
		'show_listing_share_title'       => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_share_icon'        => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*ended Share Listing*/
		'show_listing_pdf'               => array(
			'label'      => esc_html__( 'PDF Brochure Button', 'stm_vehicles_listing' ),
			'type'       => 'checkbox',
			'dependency' => array(
				'key'   => 'listing_view_type',
				'value' => 'list',
			),
			'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'    => STM_LISTINGS_URL . '/assets/images/previews/list-popup-btns.png',
			'group'      => 'started',
		),
		'show_listing_pdf_as_btn'        => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/list-btns.png',
		),
		'show_listing_pdf_title'         => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_pdf_icon'          => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*Ended PDF*/
		'show_listing_quote'             => array(
			'label'      => esc_html__( 'Request Listing Price Button', 'stm_vehicles_listing' ),
			'type'       => 'checkbox',
			'dependency' => array(
				'key'   => 'listing_view_type',
				'value' => 'list',
			),
			'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'    => STM_LISTINGS_URL . '/assets/images/previews/list-popup-btns.png',
			'group'      => 'started',
		),
		'show_listing_quote_as_btn'      => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/list-btns.png',
		),
		'show_listing_quote_title'       => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_quote_icon'        => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*Ended Request Price*/
		'show_listing_trade'             => array(
			'label'      => esc_html__( 'Trade-In Button', 'stm_vehicles_listing' ),
			'type'       => 'checkbox',
			'dependency' => array(
				'key'   => 'listing_view_type',
				'value' => 'list',
			),
			'submenu'    => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'    => STM_LISTINGS_URL . '/assets/images/previews/list-popup-btns.png',
			'group'      => 'started',
		),
		'show_listing_trade_as_btn'      => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/list-btns.png',
		),
		'show_listing_trade_title'       => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_trade_icon'        => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'list',
				),
				array(
					'key'   => 'list_card_skin',
					'value' => $list_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		),
	);

	/*******************
	 *
	 * GRID CONF
	 *
	 ************************/

	$grid_conf = array(
		'show_view_details_button_grid'       => array(
			'label'        => esc_html__( 'View Details Button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-btns.png',
			'group'        => 'started',
		),
		'show_view_details_title_grid'        => array(
			'label'        => esc_html__( 'Button Title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'show_view_details_button_grid',
					'value' => 'not_empty',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_view_detail_icon_grid'          => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'show_view_details_button_grid',
					'value' => 'not_empty',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*ended View Details*/
		'show_listing_test_drive_grid'        => array(
			'label'        => esc_html__( 'Test Drive', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-popup-btns.png',
			'group'        => 'started',
		),
		'show_listing_test_drive_grid_as_btn' => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-btns.png',
		),
		'show_listing_test_drive_grid_title'  => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_test_drive_grid_icon'   => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*ended Test Drive*/
		'show_listing_share_grid'             => array(
			'label'        => esc_html__( 'Share Listing Button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-popup-btns.png',
			'group'        => 'started',
		),
		'show_listing_share_grid_as_btn'      => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-btns.png',
		),
		'show_listing_share_grid_title'       => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_share_grid_icon'        => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*ended Share Listing*/
		'show_listing_pdf_grid'               => array(
			'label'        => esc_html__( 'PDF Brochure Button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-popup-btns.png',
			'group'        => 'started',
		),
		'show_listing_pdf_grid_as_btn'        => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-btns.png',
		),
		'show_listing_pdf_grid_title'         => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_pdf_grid_icon'          => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*Ended PDF*/
		'show_listing_quote_grid'             => array(
			'label'        => esc_html__( 'Request Listing Price Button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-popup-btns.png',
			'group'        => 'started',
		),
		'show_listing_quote_grid_as_btn'      => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-btns.png',
		),
		'show_listing_quote_grid_title'       => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_quote_grid_icon'        => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*Ended Request Price*/
		'show_listing_trade_grid'             => array(
			'label'        => esc_html__( 'Trade-In Button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-popup-btns.png',
			'group'        => 'started',
		),
		'show_listing_trade_grid_as_btn'      => array(
			'label'        => esc_html__( 'Display as button', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'preview'      => STM_LISTINGS_URL . '/assets/images/previews/grid-btns.png',
		),
		'show_listing_trade_grid_title'       => array(
			'label'        => esc_html__( 'Button title', 'stm_vehicles_listing' ),
			'type'         => 'text',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
		),
		'show_listing_trade_grid_icon'        => array(
			'label'        => esc_html__( 'Icon', 'stm_vehicles_listing' ),
			'type'         => 'icon_picker',
			'previewLabel' => 'icon',
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'group'        => 'ended',
		), /*Ended Trade-In*/
		'show_actions_button_on_hover_grid'   => array(
			'label'        => esc_html__( 'Show Actions Button On Hover', 'stm_vehicles_listing' ),
			'description'  => esc_html__( 'Action buttons appear below the listing when you hover over it', 'stm_vehicles_listing' ),
			'type'         => 'checkbox',
			'submenu'      => esc_html__( 'Listing info card', 'stm_vehicles_listing' ),
			'dependency'   => array(
				array(
					'key'   => 'listing_view_type',
					'value' => 'grid',
				),
				array(
					'key'   => 'grid_card_skin',
					'value' => $grid_skins,
				),
			),
			'dependencies' => '&&',
		),
	);

	return array_merge( $list_conf, $grid_conf );
}

add_filter(
	'mobile_view_type_deps',
	function () {
		if ( stm_is_motors_theme() ) {
			return '';
		}

		return array(
			'dependency'   =>
				array(
					array(
						'key'   => 'grid_card_skin',
						'value' => 'default',
					),
					array(
						'key'   => 'list_card_skin',
						'value' => 'default',
					),
				),
			'dependencies' => '||',
		);
	}
);
