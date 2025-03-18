<?php
use MotorsVehiclesListing\Stilization\Colors;

add_filter(
	'mvl_get_all_nuxy_config',
	function ( $global_conf ) {

		if ( ! apply_filters( 'motors_plugin_setting_classified_show', true ) || get_template() === 'motors' ) {
			return $global_conf;
		}

		$config_fields = array();

		if ( get_template() === 'motors-starter-theme' ) {
			$config_fields = array(
				'colors_config_title'      => array(
					'type'    => 'group_title',
					'label'   => esc_html__( 'Customization', 'stm_vehicles_listing' ),
					'submenu' => esc_html__( 'Colors', 'stm_vehicles_listing' ),
					'group'   => 'started',
				),
				'replace_elementor_colors' => array(
					'label'       => esc_html__( 'Replace Global Colors', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'value'       => 1,
					'description' => esc_html__( 'The colors you set below will replace global colors in Elementor ', 'stm_vehicles_listing' ),
					'submenu'     => esc_html__( 'Colors', 'stm_vehicles_listing' ),
					'group'       => 'ended',
				),
			);
		}

		$palette_fields = array(
			'general_colors_title' => array(
				'type'    => 'group_title',
				'label'   => esc_html__( 'Main Colors', 'stm_vehicles_listing' ),
				'submenu' => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'   => 'started',
			),
			'accent_color'         => array(
				'label'         => esc_html__( 'Accent', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'accent_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'accent_color' ),
			),
			'bg_color'             => array(
				'label'         => esc_html__( 'Background', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'bg_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'bg_color' ),
			),
			'bg_shade'             => array(
				'label'         => esc_html__( 'Secondary Background', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'bg_shade' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'bg_shade' ),
			),
			'bg_contrast'          => array(
				'label'         => esc_html__( 'Accent Background', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'bg_contrast' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'         => 'ended',
				'default_value' => Colors::default_value( 'bg_contrast' ),
			),
		);

		$font_color_fields = array(
			'text_colors_title'   => array(
				'type'    => 'group_title',
				'label'   => esc_html__( 'Typography', 'stm_vehicles_listing' ),
				'submenu' => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'   => 'started',
			),
			'text_color'          => array(
				'label'         => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'text_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'text_color' ),
			),
			'contrast_text_color' => array(
				'label'         => esc_html__( 'Accent Background Text Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'contrast_text_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'         => 'ended',
				'default_value' => Colors::default_value( 'contrast_text_color' ),
			),
		);

		$other_colors_fields = array(
			'other_colors_title'  => array(
				'type'    => 'group_title',
				'label'   => esc_html__( 'Buttons & Badges', 'stm_vehicles_listing' ),
				'submenu' => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'   => 'started',
			),
			'filter_inputs_color' => array(
				'label'         => esc_html__( 'Inputs and actions', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'filter_inputs_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'filter_inputs_color' ),
			),
			'spec_badge_color'    => array(
				'label'         => esc_html__( 'Special Badge', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'spec_badge_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'spec_badge_color' ),
			),
			'sold_badge_color'    => array(
				'label'         => esc_html__( 'Sold Badge', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'sold_badge_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'sold_badge_color' ),
			),
			'endgroup'            => array(
				'type'  => 'group_end',
				'group' => 'ended',
			),
		);

		$success_colors_fields = array(
			'success_colors_title' => array(
				'type'    => 'group_title',
				'label'   => esc_html__( 'Success Messages', 'stm_vehicles_listing' ),
				'submenu' => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'   => 'started',
			),
			'success_bg_color'     => array(
				'label'         => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'success_bg_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'success_bg_color' ),
			),
			'success_text_color'   => array(
				'label'         => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'success_text_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'         => 'ended',
				'default_value' => Colors::default_value( 'success_text_color' ),
			),
		);

		$notices_colors_fields = array(
			'notice_colors_title' => array(
				'type'    => 'group_title',
				'label'   => esc_html__( 'Notices', 'stm_vehicles_listing' ),
				'submenu' => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'   => 'started',
			),
			'notice_bg_color'     => array(
				'label'         => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'notice_bg_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'notice_bg_color' ),
			),
			'notice_text_color'   => array(
				'label'         => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'notice_text_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'         => 'ended',
				'default_value' => Colors::default_value( 'notice_text_color' ),
			),
		);

		$errors_colors_fields = array(
			'errors_colors_title' => array(
				'type'    => 'group_title',
				'label'   => esc_html__( 'Error Messages', 'stm_vehicles_listing' ),
				'submenu' => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'   => 'started',
			),
			'error_bg_color'      => array(
				'label'         => esc_html__( 'Background Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'error_bg_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'default_value' => Colors::default_value( 'error_bg_color' ),
			),
			'error_text_color'    => array(
				'label'         => esc_html__( 'Text Color', 'stm_vehicles_listing' ),
				'type'          => 'color',
				'value'         => Colors::value( 'error_text_color' ),
				'submenu'       => esc_html__( 'Colors', 'stm_vehicles_listing' ),
				'group'         => 'ended',
				'default_value' => Colors::default_value( 'error_text_color' ),
			),
		);

		$fields = apply_filters(
			'style_settings_colors',
			array_merge(
				$config_fields,
				$palette_fields,
				$font_color_fields,
				$other_colors_fields,
				$success_colors_fields,
				$notices_colors_fields,
				$errors_colors_fields,
			)
		);

		$conf = array(
			'name'   => esc_html__( 'Appearance', 'stm_vehicles_listing' ),
			'fields' => $fields,
		);

		$global_conf[ mvl_modify_key( $conf['name'] ) ] = $conf;

		return $global_conf;
	},
	40,
	1
);

add_action( 'wpcfto_after_settings_saved', array( \MotorsVehiclesListing\Stilization\Colors::class, 'import_to_elementor' ) );
