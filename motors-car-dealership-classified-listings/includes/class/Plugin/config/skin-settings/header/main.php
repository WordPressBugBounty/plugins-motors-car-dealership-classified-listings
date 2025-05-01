<?php
use MotorsVehiclesListing\Stilization\Colors;

add_filter(
	'mst_skin_settings_header_conf',
	function ( $conf_fields ) {

		$fields = array(
			'mst_header_layout'                            =>
				array(
					'label'         => esc_html__( 'Header Layout', 'stm_vehicles_listing' ),
					'type'          => 'select',
					'options'       => array(
						'default' => esc_html__( 'Header Footer Elementor', 'stm_vehicles_listing' ),
						'focus'   => esc_html__( 'Focus', 'stm_vehicles_listing' ),
						'classic' => esc_html__( 'Classic', 'stm_vehicles_listing' ),
						'light'   => esc_html__( 'Light', 'stm_vehicles_listing' ),
					),
					'submenu'       => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'default_value' => 'default',
				),
			'mst_header_sticky'                            =>
				array(
					'label'      => esc_html__( 'Sticky', 'stm_vehicles_listing' ),
					'type'       => 'checkbox',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
				),
			'mst_header_desktop_settings_title'            =>
				array(
					'label'      => esc_html__( 'Desktop Header Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
				),
			'mst_header_sticky_bg_color'                   =>
				array(
					'label'        => esc_html__( 'Sticky Background Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_layout',
							'value' => 'focus||light||classic',
						),
						array(
							'key'   => 'mst_header_sticky',
							'value' => 'not_empty',
						),
					),
					'output_css'   => array(
						'desktop' => array(
							array(
								'body.header-sticky .header-classic',
								'.header-sticky .header-focus .header-nav',
								'.header-sticky .header-light .header-menu',
								'.header-sticky .header-light .header-nav:before',
								'.header-sticky .header-light .header-nav:after',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_bg_color'                          =>
				array(
					'label'      => esc_html__( 'Header Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'body .header-focus',
								'body .header-classic',
								'body .header-light',
								'body.header-sticky-prepare-slidedown .header-classic',
								'.header-light .header-contacts',
								'.header-focus .header-contacts',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-light .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'background-color' => '{{value}}',
							),
						),
						'mobile'  => array(
							array(
								'.header-light .header-contacts',
								'.header-focus .header-contacts',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_text_color'                        =>
				array(
					'label'      => esc_html__( 'Header Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-address',
								'.header-focus .header-phone',
								'.header-focus .header-main-phone',
								'.header-classic .header-main-phone',
								'.header-light .header-main-phone',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_socials_icon_color'                =>
				array(
					'label'      => esc_html__( 'Header Socials Icon Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-focus .header-socials a',
								'.header-light .header-socials a',
								'color' => '{{value}}',
							),
							array(
								'.header-focus .header-socials a',
								'background-color' => '{{value::0.2}}',
							),
						),
					),
				),
			'mst_header_socials_hover_icon_color'          =>
				array(
					'label'      => esc_html__( 'Header Socials Hover Icon Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-focus .header-socials a:hover',
								'.header-light .header-socials a:hover',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_desktop_border_bottom_color'       =>
				array(
					'label'      => esc_html__( 'Header Border Bottom Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-nav',
								'.header-classic',
								'.header-light',
								'border-bottom' => '1px solid {{value}}',
							),
						),
					),
				),
			'mst_header_sticky_height'                     => array(
				'label'      => esc_html__( 'Sticky Header Height', 'stm_vehicles_listing' ),
				'type'       => 'number',
				'min'        => 20,
				'max'        => 140,
				'step'       => 1,
				'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
				'dependency' => array(
					'key'   => 'mst_header_layout',
					'value' => 'classic',
				),
				'output_css' => array(
					'desktop' => array(
						array(
							'.header-sticky-enabled.header-sticky .header-classic .header-menu > .menu-item',
							'.header-sticky-enabled.header-sticky .header-classic .header-container',
							'min-height' => '{{value}}px',
							'max-height' => '{{value}}px',
						),
					),
				),
			),
			'mst_header_height'                            => array(
				'label'      => esc_html__( 'Header Height', 'stm_vehicles_listing' ),
				'type'       => 'number',
				'min'        => 20,
				'max'        => 140,
				'step'       => 1,
				'group'      => 'ended',
				'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
				'dependency' => array(
					'key'   => 'mst_header_layout',
					'value' => 'classic||focus||light',
				),
				'output_css' => array(
					'desktop' => array(
						array(
							'.header-focus .header-container',
							'min-height' => '{{value}}px',
						),
						array(
							'.header-light .header-container',
							'.header-classic .header-container',
							'.header-classic .header-menu > .menu-item',
							'min-height' => '{{value}}px',
							'max-height' => '{{value}}px',
						),
						array(
							'.stm-layout-header-focus.header-sticky-enabled',
							'.stm-layout-header-classic.header-sticky-enabled',
							'.stm-layout-header-light.header-sticky-enabled',
							'padding-top' => '{{value}}px',
						),
					),
				),
			),
			'mst_header_mobile_settings_title'             =>
				array(
					'label'      => esc_html__( 'Mobile Header Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
				),
			'mst_header_mobile_bg_color'                   =>
				array(
					'label'      => esc_html__( 'Mobile Header Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
					'output_css' => array(
						'mobile' => array(
							array(
								'body .header-focus',
								'.header-focus .header-container',
								'.header-focus .header-nav',
								'.header-focus .header-menu .sub-menu',
								'.header-classic .header-container',
								'.header-classic .header-nav:after',
								'body .header-light',
								'.header-light .header-container',
								'.header-light .header-nav',
								'.header-light .header-menu .sub-menu',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_mobile_text_color'                 =>
				array(
					'label'      => esc_html__( 'Mobile Header Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
					'output_css' => array(
						'mobile' => array(
							array(
								'.header-focus .header-main-phone',
								'.header-focus .header-phone',
								'.header-focus .header-address',
								'.header-focus .header-profile-button-link:not(.has-bg-color):before',
								'.header-focus .header-add-car-button:not(.has-bg-color):before',
								'.header-focus .header-compare-button:not(.has-bg-color):before',
								'.header-classic .header-main-phone',
								'.header-classic .header-profile-button-link:not(.has-bg-color):before',
								'.header-classic .header-add-car-button:not(.has-bg-color):before',
								'.header-classic .header-compare-button:not(.has-bg-color):before',
								'.header-light .header-main-phone',
								'.header-light .header-profile-button-link:not(.has-bg-color):before',
								'.header-light .header-add-car-button:not(.has-bg-color):before',
								'.header-light .header-compare-button:not(.has-bg-color):before',
								'color' => '{{value}}',
							),
							array(
								'.header-focus .mobile-menu-trigger',
								'.header-focus .mobile-menu-trigger:before',
								'.header-focus .header-menu .sub-menu .menu-item-has-children:after',
								'.header-classic .mobile-menu-trigger',
								'.header-classic .mobile-menu-trigger:before',
								'.header-classic .header-menu .sub-menu .menu-item-has-children:after',
								'.header-light .mobile-menu-trigger',
								'.header-light .mobile-menu-trigger:before',
								'.header-light .header-menu .sub-menu .menu-item-has-children:after',
								'border-color' => '{{value}}',
							),
							array(
								'.header-focus .mobile-menu-trigger:after',
								'.header-classic .mobile-menu-trigger:after',
								'.header-light .mobile-menu-trigger:after',
								'background-color' => '{{value}}',
							),
							array(
								'.header-focus .header-menu .sub-menu .menu-item a',
								'.header-classic .header-menu .sub-menu .menu-item a',
								'.header-light .header-menu .sub-menu .menu-item a',
								'border-color' => '{{value::0.1}}',
							),
						),
					),
				),
			'mst_header_mobile_border_bottom_color'        =>
				array(
					'label'      => esc_html__( 'Mobile Header Border Bottom Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
					'output_css' => array(
						'mobile' => array(
							array(
								'.header-focus .header-container',
								'.header-classic .header-container',
								'.header-light .header-container',
								'border-bottom' => '1px solid {{value}}',
							),
						),
					),
				),
			'mst_header_mobile_contacts_button_icon'       =>
				array(
					'label'      => esc_html__( 'Mobile Contacts Button Icon', 'stm_vehicles_listing' ),
					'type'       => 'icon_picker',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-light .header-contacts-trigger:before',
								'.header-focus .header-contacts-trigger:before',
								'color'     => '{{color}}',
								'font-size' => '{{size}}px',
							),
						),
					),
				),
			'mst_header_mobile_contacts_close_button_icon' =>
				array(
					'label'      => esc_html__( 'Mobile Contacts Close Button Icon', 'stm_vehicles_listing' ),
					'type'       => 'icon_picker',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-contacts-opened .header-light .header-contacts-trigger:before',
								'.header-contacts-opened .header-focus .header-contacts-trigger:before',
								'color'     => '{{color}}',
								'font-size' => '{{size}}px',
							),
						),
					),
				),
			'mst_header_mobile_contacts_close_button_bg_color' =>
				array(
					'label'      => esc_html__( 'Mobile Contacts Close Button Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'group'      => 'ended',
					'output_css' => array(
						'any' => array(
							array(
								'.header-contacts-opened .header-light .header-contacts-trigger',
								'.header-contacts-opened .header-focus .header-contacts-trigger',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_phone_settings_start'                     =>
				array(
					'label'      => esc_html__( 'Main Phone Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
				),
			'mst_header_main_phone_icon'                   =>
				array(
					'label'      => esc_html__( 'Main Phone Icon', 'stm_vehicles_listing' ),
					'type'       => 'icon_picker',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-focus .header-main-phone:before',
								'.header-light .header-main-phone:before',
								'color'     => '{{color}}',
								'font-size' => '{{size}}px',
							),
						),
					),
				),
			'mst_header_main_phone_label_color'            =>
				array(
					'label'      => esc_html__( 'Main Phone Label Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-focus .header-main-phone-label',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_main_phone_label'                  =>
				array(
					'label'      => esc_html__( 'Main Phone Label', 'stm_vehicles_listing' ),
					'type'       => 'text',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'value'      => esc_html__( 'Sales', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_main_phone'                        =>
				array(
					'label'      => esc_html__( 'Main Phone', 'stm_vehicles_listing' ),
					'type'       => 'text',
					'value'      => '878-9671-4455',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
				),
			'mst_header_secondary_phone_one_settings_title' =>
				array(
					'label'      => esc_html__( 'Secondary Phone 1 Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_secondary_phone_one_label_color'   =>
				array(
					'label'      => esc_html__( 'Secondary Phone 1 Label Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-focus .header-phone.one .header-phone-label',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_secondary_phone_one_label'         =>
				array(
					'label'      => esc_html__( 'Secondary Phone 1 Label', 'stm_vehicles_listing' ),
					'type'       => 'text',
					'value'      => 'Service',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_secondary_phone_one'               =>
				array(
					'label'      => esc_html__( 'Secondary Phone 1', 'stm_vehicles_listing' ),
					'type'       => 'text',
					'value'      => '878-3971-3223',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_secondary_phone_two_settings_title' =>
				array(
					'label'      => esc_html__( 'Secondary Phone 2 Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_secondary_phone_two_label_color'   =>
				array(
					'label'      => esc_html__( 'Secondary Phone 2 Label Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-focus .header-phone.two .header-phone-label',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_secondary_phone_two_label'         =>
				array(
					'label'      => esc_html__( 'Secondary Phone 2 Label', 'stm_vehicles_listing' ),
					'type'       => 'text',
					'value'      => 'Parts',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_secondary_phone_two'               =>
				array(
					'label'      => esc_html__( 'Secondary Phone 2', 'stm_vehicles_listing' ),
					'type'       => 'text',
					'value'      => '878-0910-0770',
					'group'      => 'ended',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_address_settings_start'            =>
				array(
					'label'      => esc_html__( 'Address Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_address_icon'                      =>
				array(
					'label'      => esc_html__( 'Address Icon', 'stm_vehicles_listing' ),
					'type'       => 'icon_picker',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
					'output_css' => array(
						'any' => array(
							array(
								'.header-focus .header-address:before',
								'color' => '{{color}}',
							),
						),
					),
				),
			'mst_header_address'                           =>
				array(
					'label'      => esc_html__( 'Address', 'stm_vehicles_listing' ),
					'type'       => 'text',
					'value'      => '1840 E Garvey Ave South West Covina, CA 91791',
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
					'group'      => 'ended',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus',
					),
				),
			'mst_header_empty_notice'                      =>
				array(
					'label'      => esc_html__( 'Settings Not Available For This Header Type', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'default',
					),
					'submenu'    => esc_html__( 'Main', 'stm_vehicles_listing' ),
				),
		);

		return array_merge( $conf_fields, $fields );
	},
	20,
	1
);
