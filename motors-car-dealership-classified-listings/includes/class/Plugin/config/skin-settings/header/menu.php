<?php
use MotorsStarterTheme\Services\OutputCSS;

add_filter(
	'mst_skin_settings_header_conf',
	function ( $conf_fields ) {
		$fields = array(
			'mst_header_menu_typography'                  =>
				array(
					'label'      => esc_html__( 'Main Menu Font Settings', 'stm_vehicles_listing' ),
					'type'       => 'typography',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic||light',
					),
					'excluded'   => array(
						'color',
					),
					'output_css' => array(
						'any' => array(
							array_merge(
								array(
									'.header-light .header-menu .menu-item a',
									'.header-classic .header-menu .menu-item a',
									'.header-focus .header-menu .menu-item a',
								),
								OutputCSS::TYPOGRAPHY
							),
						),
					),
				),
			'mst_header_submenu_typography'               =>
				array(
					'label'      => esc_html__( 'SubMenu Font Family', 'stm_vehicles_listing' ),
					'type'       => 'typography',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic||light',
					),
					'excluded'   => array(
						'color',
						'font-size',
						'font-weight',
						'font-style',
						'google-weight',
						'subset',
						'text-align',
						'word-spacing',
						'letter-spacing',
						'line-height',
						'text-transform',
					),
					'output_css' => array(
						'any'     => array(
							array_merge(
								array(
									'.header-focus .header-menu > .menu-item > .sub-menu a',
									'.header-light .header-menu > .menu-item > .sub-menu a',
									'.header-classic .header-menu > .menu-item > .sub-menu a',
								),
								OutputCSS::TYPOGRAPHY,
							),
						),
						'desktop' => array(
							array(
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:not(:last-child)',
								'.header-classic .header-menu > .menu-item .sub-menu .menu-item:not(:last-child)',
								'.header-light .header-menu > .menu-item .sub-menu .menu-item:not(:last-child)',
								'border-color' => '{{color::0.2}}',
							),
							array(
								'.header-focus .header-menu > .menu-item > .sub-menu .menu-item-has-children:after',
								'.header-classic .header-menu > .menu-item > .sub-menu .menu-item-has-children:after',
								'.header-light .header-menu > .menu-item > .sub-menu .menu-item-has-children:after',
								'border-color' => '{{color}}',
							),
						),
					),
				),
			'mst_header_desktop_menu_title'               =>
				array(
					'label'      => esc_html__( 'Desktop Menu Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
				),
			'mst_header_menu_height'                      => array(
				'label'      => esc_html__( 'Menu Height', 'stm_vehicles_listing' ),
				'type'       => 'number',
				'min'        => 20,
				'max'        => 100,
				'step'       => 1,
				'value'      => 50,
				'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
				'dependency' => array(
					'key'   => 'mst_header_layout',
					'value' => 'focus||light',
				),
				'output_css' => array(
					'desktop' => array(
						array(
							'body.stm-layout-header-focus:not(.home).page .mst-wrapper-inner > .container > .page-content > p:first-child',
							'body.stm-layout-header-light:not(.home).page .mst-wrapper-inner > .container > .page-content > p:first-child',
							'body.stm-layout-header-focus:not(.home):not(.page) .mst-wrapper-inner > .container > h1:first-child',
							'body.stm-layout-header-focus:not(.home):not(.page) .mst-wrapper-inner > .container > .page-content:first-child',
							'body.stm-layout-header-light:not(.home):not(.page) .mst-wrapper-inner > .container > h1:first-child',
							'body.stm-layout-header-light:not(.home):not(.page) .mst-wrapper-inner > .container > .page-content:first-child',
							'body.stm-layout-header-focus:not(.home).page #main > .elementor > .elementor-section > .elementor-container',
							'body.stm-layout-header-focus:not(.home).page .mst-wrapper-inner > .container > .page-content > .elementor > .elementor-section > .elementor-container',
							'body.stm-layout-header-light:not(.home).page #main > .elementor > .elementor-section > .elementor-container',
							'body.stm-layout-header-light:not(.home).page .mst-wrapper-inner > .container > .page-content > .elementor > .elementor-section > .elementor-container',
							'padding-top' => '{{value}}px',
						),
						array(
							'body.stm-layout-header-focus.archive.author .stm-user-public-profile > *:first-child',
							'body.stm-layout-header-focus.archive.author .stm-user-private-main > *:first-child',
							'body.stm-layout-header-focus.archive.author .stm-user-top',
							'body.stm-layout-header-light.archive.author .stm-user-public-profile > *:first-child',
							'body.stm-layout-header-light.archive.author .stm-user-private-main > *:first-child',
							'body.stm-layout-header-light.archive.author .stm-user-top',
							'margin-top' => '{{value}}px',
						),
						array(
							'.header-focus .header-menu',
							'.header-focus .header-menu > .menu-item > a',
							'.header-light .header-menu',
							'.header-light .header-menu > .menu-item > a',
							'min-height' => '{{value}}px',
							'max-height' => '{{value}}px',
						),
					),
				),
			),
			'mst_header_menu_item_horizontal_spacing'     => array(
				'label'      => esc_html__( 'Spacing Between Menu Items', 'stm_vehicles_listing' ),
				'type'       => 'number',
				'min'        => 0,
				'max'        => 60,
				'step'       => 1,
				'value'      => 20,
				'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
				'dependency' => array(
					'key'   => 'mst_header_layout',
					'value' => 'light||classic',
				),
				'output_css' => array(
					'desktop' => array(
						array(
							'.header-focus .header-menu > .menu-item > a',
							'.header-classic .header-menu > .menu-item',
							'.header-light .header-menu > .menu-item > a',
							'margin-left'  => '{{value}}px',
							'margin-right' => '{{value}}px',
						),
					),
				),
			),
			'mst_header_menu_item_text_color'             =>
				array(
					'label'      => esc_html__( 'Item Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-menu > .menu-item > a',
								'.header-classic .header-menu > .menu-item > a',
								'.header-light .header-menu > .menu-item > a',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_menu_bg_color'                    =>
				array(
					'label'      => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-nav',
								'.header-light .header-menu',
								'.header-light .header-nav:before',
								'.header-light .header-nav:after',
								'background-color' => '{{value}}',
							),
							array(
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-light .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_menu_item_hover_bg_color'         =>
				array(
					'label'      => esc_html__( 'Hover Item Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-menu > .menu-item:hover',
								'.header-light .header-menu > .menu-item:hover',
								'background-color' => '{{value}}',
							),
							array(
								'.header-focus .header-menu > .menu-item .sub-menu > .menu-item:hover > a',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_menu_item_hover_text_color'       =>
				array(
					'label'      => esc_html__( 'Hover Item Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-menu > .menu-item:hover > a',
								'.header-classic .header-menu > .menu-item:hover > a',
								'.header-light .header-menu > .menu-item:hover > a',
								'color' => '{{value}}',
							),
							array(
								'.header-focus .header-menu > .menu-item.menu-item-has-children:hover > a:after',
								'.header-classic .header-menu > .menu-item.menu-item-has-children:hover > a:after',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:hover > a:after',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_menu_item_hover_underline_color'  =>
				array(
					'label'      => esc_html__( 'Hover Item Underline Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-classic .header-menu > .menu-item:hover > a',
								'.header-light .header-menu > .menu-item:hover > a',
								'border-bottom-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_menu_item_active_bg_color'        =>
				array(
					'label'      => esc_html__( 'Active Item Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-menu > .menu-item.current-menu-item',
								'.header-light .header-menu > .menu-item.current-menu-item',
								'background-color' => '{{value}}',
							),
							array(
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item.current-menu-item > a',
								'.header-light .header-menu > .menu-item .sub-menu .menu-item.current-menu-item > a',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_menu_item_active_text_color'      =>
				array(
					'label'      => esc_html__( 'Active Item Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-menu > .menu-item.current-menu-item > a',
								'.header-classic .header-menu > .menu-item.current-menu-item > a',
								'.header-light .header-menu > .menu-item.current-menu-item > a',
								'color' => '{{value}}',
							),
							array(
								'.header-focus .header-menu > .menu-item.current-menu-item.menu-item-has-children > a:after',
								'.header-classic .header-menu > .menu-item.current-menu-item.menu-item-has-children > a:after',
								'.header-light .header-menu > .menu-item.current-menu-item.menu-item-has-children > a:after',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_menu_item_active_underline_color' =>
				array(
					'label'      => esc_html__( 'Active Item Underline Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||light',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-focus .header-menu > .menu-item.current-menu-item > a',
								'.header-classic .header-menu > .menu-item.current-menu-item > a',
								'.header-light .header-menu > .menu-item.current-menu-item > a',
								'border-bottom-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_submenu_bg_color'                 =>
				array(
					'label'      => esc_html__( 'Submenu Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-light .header-menu > .menu-item .sub-menu',
								'.header-classic .header-menu > .menu-item .sub-menu',
								'.header-focus .header-menu > .menu-item .sub-menu',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_submenu_item_hover_bg_color'      =>
				array(
					'label'      => esc_html__( 'Submenu Item Hover Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-light .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-classic .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_submenu_item_text_color'          =>
				array(
					'label'      => esc_html__( 'Submenu Item Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-light .header-menu > .menu-item .sub-menu a',
								'.header-classic .header-menu > .menu-item .sub-menu a',
								'.header-focus .header-menu > .menu-item .sub-menu a',
								'color' => '{{value}}',
							),
							array(
								'.header-light .header-menu > .menu-item .sub-menu .menu-item.menu-item-has-children:after',
								'.header-classic .header-menu > .menu-item .sub-menu .menu-item.menu-item-has-children:after',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item.menu-item-has-children:after',
								'border-color' => '{{value}}',
							),
							array(
								'.header-light .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-classic .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_submenu_item_hover_text_color'    =>
				array(
					'label'      => esc_html__( 'Submenu Item Hover Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'group'      => 'ended',
					'output_css' => array(
						'desktop' => array(
							array(
								'.header-light .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-classic .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'color' => '{{value}}',
							),
							array(
								'.header-light .header-menu > .menu-item .sub-menu .menu-item.menu-item-has-children:hover:after',
								'.header-classic .header-menu > .menu-item .sub-menu .menu-item.menu-item-has-children:hover:after',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item.menu-item-has-children:hover:after',
								'border-color' => '{{value}}',
							),
							array(
								'.header-light .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-classic .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'.header-focus .header-menu > .menu-item .sub-menu .menu-item:hover > a',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_mobile_menu_title'                =>
				array(
					'label'      => esc_html__( 'Mobile Menu Settings', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
				),
			'mst_header_mobile_menu_bg_color'             =>
				array(
					'label'      => esc_html__( 'Menu Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'output_css' => array(
						'mobile' => array(
							array(
								'.header-focus .header-nav',
								'.header-light .header-nav',
								'.header-classic .header-nav',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_mobile_menu_items_bg_color'       =>
				array(
					'label'      => esc_html__( 'Menu Item Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'output_css' => array(
						'mobile' => array(
							array(
								'.header-light .header-menu > .menu-item > a',
								'.header-focus .header-menu > .menu-item > a',
								'.header-classic .header-menu > .menu-item > a',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_mobile_menu_items_text_color'     =>
				array(
					'label'      => esc_html__( 'Menu Item Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'output_css' => array(
						'mobile' => array(
							array(
								'.header-focus .header-menu > .menu-item > a',
								'.header-light .header-menu > .menu-item > a',
								'.header-classic .header-menu > .menu-item > a',
								'color'        => '{{value}}',
								'border-color' => '{{value::0.3}}',
							),
							array(
								'.header-focus .header-menu > .menu-item.menu-item-has-children:after',
								'.header-light .header-menu > .menu-item.menu-item-has-children:after',
								'.header-classic .header-menu > .menu-item.menu-item-has-children:after',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_mobile_submenu_bg_color'          =>
				array(
					'label'      => esc_html__( 'SubMenu Item Background Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'output_css' => array(
						'mobile' => array(
							array(
								'.header-focus .header-menu > .menu-item > .sub-menu',
								'.header-light .header-menu > .menu-item > .sub-menu',
								'.header-classic .header-menu > .menu-item > .sub-menu',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_mobile_submenu_items_text_color'  =>
				array(
					'label'      => esc_html__( 'SubMenu Item Text Color', 'stm_vehicles_listing' ),
					'type'       => 'color',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||light||classic',
					),
					'group'      => 'ended',
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
					'output_css' => array(
						'mobile' => array(
							array(
								'.header-focus .header-menu > .menu-item > .sub-menu > .menu-item a',
								'.header-light .header-menu > .menu-item > .sub-menu > .menu-item a',
								'.header-classic .header-menu > .menu-item > .sub-menu > .menu-item a',
								'color'        => '{{value}}',
								'border-color' => '{{value::0.3}}',
							),
							array(
								'.header-focus .header-menu > .menu-item > .sub-menu > .menu-item.menu-item-has-children:after',
								'.header-light .header-menu > .menu-item > .sub-menu > .menu-item.menu-item-has-children:after',
								'.header-classic .header-menu > .menu-item > .sub-menu > .menu-item.menu-item-has-children:after',
								'border-color' => '{{value}}',
							),
						),
					),
				),
			'mst_menu_empty_notice'                       =>
				array(
					'label'      => esc_html__( 'Settings Not Available For This Header Type', 'stm_vehicles_listing' ),
					'type'       => 'notice',
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'default',
					),
					'submenu'    => esc_html__( 'Menu', 'stm_vehicles_listing' ),
				),
		);

		return array_merge( $conf_fields, $fields );
	},
	20,
	1
);
