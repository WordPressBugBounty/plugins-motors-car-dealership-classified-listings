<?php
use MotorsStarterTheme\Services\OutputCSS;

add_filter(
	'mst_skin_settings_header_conf',
	function( $conf_fields ) {
		$fields = array(
			'mst_header_compare_button_show'             =>
				array(
					'label'      => esc_html__( 'Compare Button', 'stm_vehicles_listing' ),
					'type'       => 'checkbox',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
				),
			'mst_header_compare_button_icon'             =>
				array(
					'label'        => esc_html__( 'Compare Icon', 'stm_vehicles_listing' ),
					'type'         => 'icon_picker',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_compare_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-compare-button:before',
								'.header-classic .header-compare-button:before',
								'.header-light .header-compare-button:before',
								'font-size' => '{{size}}px',
								'color'     => '{{color}}',
							),
						),
					),
				),
			'mst_header_compare_button_text'             =>
				array(
					'label'        => esc_html__( 'Compare Text', 'stm_vehicles_listing' ),
					'type'         => 'text',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_compare_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'focus||classic',
						),
					),
				),
			'mst_header_compare_button_text_color'       =>
				array(
					'label'        => esc_html__( 'Compare Button Text Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_layout',
							'value' => 'focus||classic',
						),
						array(
							'key'   => 'mst_header_compare_button_show',
							'value' => 'not_empty',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-compare-button',
								'.header-classic .header-compare-button',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_compare_button_hover_text_color' =>
				array(
					'label'        => esc_html__( 'Compare Button Text/Icon Hover Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_compare_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-compare-button:hover',
								'.header-focus .header-compare-button:hover:before',
								'.header-classic .header-compare-button:hover',
								'.header-classic .header-compare-button:hover:before',
								'.header-light .header-compare-button:hover',
								'.header-light .header-compare-button:hover:before',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_compare_button_bg_color'         =>
				array(
					'label'        => esc_html__( 'Compare Button Background Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_compare_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any'    => array(
							array(
								'.header-light .header-compare-button',
								'min-width'     => '38px',
								'min-height'    => '38px',
								'max-width'     => '38px',
								'max-height'    => '38px',
								'border-radius' => '50%',
								'margin'        => '0 6px',
							),
							array(
								'.header-classic .header-compare-button',
								'margin'  => '0 6px',
								'padding' => '8px 15px',
							),
							array(
								'.header-focus .header-compare-button',
								'.header-classic .header-compare-button',
								'.header-light .header-compare-button',
								'background-color' => '{{value}}',
							),
						),
						'mobile' => array(
							array(
								'.header-light .header-compare-button',
								'.header-focus .header-compare-button',
								'.header-classic .header-compare-button',
								'min-width'     => '38px',
								'min-height'    => '38px',
								'max-width'     => '38px',
								'max-height'    => '38px',
								'border-radius' => '50%',
							),
						),
					),
				),
			'mst_header_compare_button_hover_bg_color'   =>
				array(
					'label'        => esc_html__( 'Compare Button Hover Background Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_compare_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-compare-button:hover',
								'.header-classic .header-compare-button:hover',
								'.header-light .header-compare-button:hover',
								'background-color' => '{{value}}',
							),
						),
					),

				),
			'mst_header_compare_button_border_radius'    =>
				array(
					'label'        => esc_html__( 'Compare Button Border Radius', 'stm_vehicles_listing' ),
					'type'         => 'number',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'min'          => 0,
					'max'          => 25,
					'step'         => 1,
					'value'        => 0,
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic',
						),
						array(
							'key'   => 'mst_header_compare_button_show',
							'value' => 'not_empty',
						),
					),
					'output_css'   => array(
						'desktop' => array(
							array(
								'.header-classic .header-compare-button',
								'border-radius' => '{{value}}px',
							),
						),
					),
				),
			'mst_header_compare_button_padding'          =>
			array(
				'label'        => esc_html__( 'Compare Button Padding', 'stm_vehicles_listing' ),
				'type'         => 'spacing',
				'units'        => array( 'px', 'em' ),
				'value'        => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
					'unit'   => 'px',
				),
				'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
				'dependencies' => '&&',
				'dependency'   => array(
					array(
						'key'   => 'mst_header_compare_button_show',
						'value' => 'not_empty',
					),
					array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic',
					),
				),
				'output_css'   => array(
					'desktop' => array(
						array_merge(
							array(
								'.header-focus .header-compare-button',
								'.header-classic .header-compare-button',
							),
							OutputCSS::PADDING
						),
					),
				),
				'group'        => 'ended',
			),
			'mst_header_profile_button_show'             =>
				array(
					'label'      => esc_html__( 'Profile Button', 'stm_vehicles_listing' ),
					'type'       => 'checkbox',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
				),
			'mst_header_profile_button_icon'             =>
				array(
					'label'        => esc_html__( 'Profile Icon', 'stm_vehicles_listing' ),
					'type'         => 'icon_picker',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-profile-button-link:before',
								'.header-classic .header-profile-button-link:before',
								'.header-light .header-profile-button-link:before',
								'font-size' => '{{size}}px',
								'color'     => '{{color}}',
							),
						),
					),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_profile_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
				),
			'mst_header_profile_button_text'             =>
				array(
					'label'        => esc_html__( 'Profile Text', 'stm_vehicles_listing' ),
					'type'         => 'text',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_profile_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
				),
			'mst_header_profile_button_text_color'       =>
				array(
					'label'        => esc_html__( 'Profile Button Text Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_layout',
							'value' => 'focus||classic',
						),
						array(
							'key'   => 'mst_header_profile_button_show',
							'value' => 'not_empty',
						),
					),
					'output_css'   => array(
						'desktop' => array(
							array(
								'.header-focus .header-profile-button-link',
								'.header-classic .header-profile-button-link',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_profile_button_hover_text_color' =>
				array(
					'label'        => esc_html__( 'Profile Button Text/Icon Hover Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_profile_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-profile-button-link:hover',
								'.header-focus .header-profile-button-link:hover:before',
								'.header-classic .header-profile-button-link:hover',
								'.header-classic .header-profile-button-link:hover:before',
								'.header-light .header-profile-button-link:hover',
								'.header-light .header-profile-button-link:hover:before',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_profile_button_bg_color'         =>
				array(
					'label'        => esc_html__( 'Profile Button Background Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_profile_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any'    => array(
							array(
								'.header-light .header-profile-button-link',
								'min-width'     => '38px',
								'min-height'    => '38px',
								'max-width'     => '38px',
								'max-height'    => '38px',
								'border-radius' => '50%',
							),
							array(
								'.header-focus .header-profile-button-link',
								'.header-classic .header-profile-button-link',
								'.header-light .header-profile-button-link',
								'background-color' => '{{value}}',
							),
							array(
								'.header-classic .header-profile-button-link',
								'padding' => '8px 15px',
							),
							array(
								'.header-classic .header-profile-button',
								'.header-light .header-profile-button',
								'margin' => '0 6px',
							),
						),
						'mobile' => array(
							array(
								'.header-focus .header-profile-button-link',
								'.header-classic .header-profile-button-link',
								'padding'       => '0',
								'min-width'     => '38px',
								'min-height'    => '38px',
								'max-width'     => '38px',
								'max-height'    => '38px',
								'border-radius' => '50%',
							),
							array(
								'.header-focus .header-profile-button',
								'margin' => '0 6px',
							),
						),
					),
				),
			'mst_header_profile_button_hover_bg_color'   =>
				array(
					'label'        => esc_html__( 'Profile Hover Background Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_profile_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-focus .header-profile-button-link:hover',
								'.header-classic .header-profile-button-link:hover',
								'.header-light .header-profile-button-link:hover',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_profile_button_border_radius'    =>
				array(
					'label'        => esc_html__( 'Profile Button Border Radius', 'stm_vehicles_listing' ),
					'type'         => 'number',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'min'          => 0,
					'max'          => 25,
					'step'         => 1,
					'value'        => 0,
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic',
						),
						array(
							'key'   => 'mst_header_profile_button_show',
							'value' => 'not_empty',
						),
					),
					'output_css'   => array(
						'desktop' => array(
							array(
								'.header-classic .header-profile-button-link',
								'border-radius' => '{{value}}px',
							),
						),
					),
				),
			'mst_header_profile_button_padding'          =>
			array(
				'label'        => esc_html__( 'Profile Button Padding', 'stm_vehicles_listing' ),
				'type'         => 'spacing',
				'units'        => array( 'px', 'em' ),
				'value'        => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
					'unit'   => 'px',
				),
				'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
				'dependencies' => '&&',
				'dependency'   => array(
					array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic',
					),
					array(
						'key'   => 'mst_header_profile_button_show',
						'value' => 'not_empty',
					),
				),
				'group'        => 'ended',
				'output_css'   => array(
					'desktop' => array(
						array_merge(
							array(
								'.header-focus .header-profile-button-link',
								'.header-classic .header-profile-button-link',
							),
							OutputCSS::PADDING
						),
					),
				),
			),
			'mst_header_add_car_button_show'             =>
				array(
					'label'      => esc_html__( 'Add Car Button', 'stm_vehicles_listing' ),
					'type'       => 'checkbox',
					'group'      => 'started',
					'submenu'    => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependency' => array(
						'key'   => 'mst_header_layout',
						'value' => 'classic||focus||light',
					),
				),
			'mst_header_add_car_button_icon'             =>
				array(
					'label'        => esc_html__( 'Add Car Icon', 'stm_vehicles_listing' ),
					'type'         => 'icon_picker',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_add_car_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-classic .header-add-car-button::before',
								'.header-focus .header-add-car-button::before',
								'.header-light .header-add-car-button::before',
								'font-size' => '{{size}}px',
								'color'     => '{{color}}',
							),
						),
					),
				),
			'mst_header_add_car_button_text'             =>
				array(
					'label'        => esc_html__( 'Add Car Button Text', 'stm_vehicles_listing' ),
					'type'         => 'text',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_add_car_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'focus||classic',
						),
					),
				),
			'mst_header_add_car_button_text_color'       =>
				array(
					'label'        => esc_html__( 'Add Car Button Text Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_add_car_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'focus||classic',
						),
					),
					'dependencies' => '&&',
					'output_css'   => array(
						'desktop' => array(
							array(
								'.header-focus .header-add-car-button',
								'.header-classic .header-add-car-button',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_add_car_button_bg_color'         =>
				array(
					'label'        => esc_html__( 'Add Car Button Background Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_add_car_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any'    => array(
							array(
								'.header-light .header-add-car-button',
								'background-color' => '{{value}}',
								'margin'           => '0 6px',
								'padding'          => '0',
								'min-width'        => '38px',
								'min-height'       => '38px',
								'max-width'        => '38px',
								'max-height'       => '38px',
								'border-radius'    => '50%',
							),
							array(
								'.header-classic .header-add-car-button',
								'background-color' => '{{value}}',
								'margin'           => '0 6px',
								'padding'          => '8px 15px',
							),
							array(
								'.header-focus .header-add-car-button',
								'background-color' => '{{value}}',
							),
						),
						'mobile' => array(
							array(
								'.header-focus .header-add-car-button',
								'.header-classic .header-add-car-button',
								'padding'       => '0',
								'min-width'     => '38px',
								'min-height'    => '38px',
								'max-width'     => '38px',
								'max-height'    => '38px',
								'border-radius' => '50%',
								'margin'        => '0 6px',
							),
						),
					),
				),
			'mst_header_add_car_button_hover_bg_color'   =>
				array(
					'label'        => esc_html__( 'Add Car Hover Background Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_add_car_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-classic .header-add-car-button:hover',
								'.header-light .header-add-car-button:hover',
								'.header-focus .header-add-car-button:hover',
								'background-color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_add_car_button_hover_text_color' =>
				array(
					'label'        => esc_html__( 'Add Car Button Text/Icon Hover Color', 'stm_vehicles_listing' ),
					'type'         => 'color',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_add_car_button_show',
							'value' => 'not_empty',
						),
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic||focus||light',
						),
					),
					'output_css'   => array(
						'any' => array(
							array(
								'.header-classic .header-add-car-button:hover',
								'.header-classic .header-add-car-button:hover:before',
								'.header-light .header-add-car-button:hover',
								'.header-light .header-add-car-button:hover:before',
								'.header-focus .header-add-car-button:hover',
								'.header-focus .header-add-car-button:hover:before',
								'color' => '{{value}}',
							),
						),
					),
				),
			'mst_header_add_car_button_border_radius'    =>
				array(
					'label'        => esc_html__( 'Add Car Button Border Radius', 'stm_vehicles_listing' ),
					'type'         => 'number',
					'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
					'min'          => 0,
					'max'          => 25,
					'step'         => 1,
					'value'        => 0,
					'dependencies' => '&&',
					'dependency'   => array(
						array(
							'key'   => 'mst_header_layout',
							'value' => 'classic',
						),
						array(
							'key'   => 'mst_header_add_car_button_show',
							'value' => 'not_empty',
						),
					),
					'output_css'   => array(
						'desktop' => array(
							array(
								'.header-classic .header-add-car-button',
								'border-radius' => '{{value}}px',
							),
						),
					),
				),
			'mst_header_add_car_button_padding'          =>
			array(
				'label'        => esc_html__( 'Add Car Button Padding', 'stm_vehicles_listing' ),
				'type'         => 'spacing',
				'units'        => array( 'px', 'em' ),
				'value'        => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
					'unit'   => 'px',
				),
				'submenu'      => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
				'dependencies' => '&&',
				'dependency'   => array(
					array(
						'key'   => 'mst_header_layout',
						'value' => 'focus||classic',
					),
					array(
						'key'   => 'mst_header_add_car_button_show',
						'value' => 'not_empty',
					),
				),
				'group'        => 'ended',
				'output_css'   => array(
					'desktop' => array(
						array_merge(
							array(
								'.header-focus .header-add-car-button',
								'.header-classic .header-add-car-button',
							),
							OutputCSS::PADDING
						),
					),
				),
			),
			'mst_buttons_empty_notice'                   =>
			array(
				'label'      => esc_html__( 'Settings Not Available For This Header Type', 'stm_vehicles_listing' ),
				'type'       => 'notice',
				'dependency' => array(
					'key'   => 'mst_header_layout',
					'value' => 'default',
				),
				'submenu'    => esc_html__( 'Buttons/Actions', 'stm_vehicles_listing' ),
			),
		);

		return array_merge( $conf_fields, $fields );
	},
	30
);
