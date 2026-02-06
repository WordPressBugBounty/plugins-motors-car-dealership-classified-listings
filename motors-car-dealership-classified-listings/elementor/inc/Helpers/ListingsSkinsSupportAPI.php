<?php
namespace MotorsElementorWidgetsFree\Helpers;

use MotorsVehiclesListing\Libs\Traits\ProtectedHooks;
use MotorsVehiclesListing\Libs\Traits\Instance;

final class ListingsSkinsSupportAPI {
	use ProtectedHooks;
	use Instance;

	const GRID_SKINS = array(
		'default',
		'skin_1',
		'skin_2',
		'skin_3',
		'skin_4',
	);

	const LIST_SKINS = array(
		'default',
		'skin_1',
		'skin_2',
		'skin_3',
		'skin_4',
	);

	protected function __construct() {
		$this->add_action( 'mvl_add_listings_grid_skins_elementor_control', 1, 2 );
		$this->add_action( 'mvl_add_listings_list_skins_elementor_control', 1, 2 );
		$this->add_action( 'mvl_add_listings_grid_styles_elementor_section', 1, 3 );
		$this->add_action( 'mvl_add_listings_list_styles_elementor_section', 1, 3 );
		$this->add_filter( 'mvl_add_listings_skins_to_elementor_display_settings', 1, 3 );
		$this->add_filter( 'mvl_add_listings_grid_skins_to_elementor_control_options', 1, 1 );
		$this->add_filter( 'mvl_add_listings_list_skins_to_elementor_control_options', 1, 1 );
	}

	protected function mvl_add_listings_grid_skins_to_elementor_control_options( array $options ): array {
		return array_merge(
			$options,
			array(
				is_mvl_pro() ? 'skin_1' : 'modern__option_disabled' => esc_html__( 'Modern', 'stm_vehicles_listing' ),
				is_mvl_pro() ? 'skin_2' : 'elegant__option_disabled' => esc_html__( 'Elegant', 'stm_vehicles_listing' ),
				is_mvl_pro() ? 'skin_3' : 'compact__option_disabled' => esc_html__( 'Compact', 'stm_vehicles_listing' ),
				is_mvl_pro() ? 'skin_4' : 'luxury__option_disabled' => esc_html__( 'Luxury', 'stm_vehicles_listing' ),
			)
		);
	}

	protected function mvl_add_listings_list_skins_to_elementor_control_options( array $options ): array {
		return array_merge(
			$options,
			array(
				is_mvl_pro() ? 'skin_1' : 'modern__option_disabled' => esc_html__( 'Modern', 'stm_vehicles_listing' ),
				is_mvl_pro() ? 'skin_2' : 'elegant__option_disabled' => esc_html__( 'Elegant', 'stm_vehicles_listing' ),
				is_mvl_pro() ? 'skin_3' : 'compact__option_disabled' => esc_html__( 'Compact', 'stm_vehicles_listing' ),
				is_mvl_pro() ? 'skin_4' : 'luxury__option_disabled' => esc_html__( 'Luxury', 'stm_vehicles_listing' ),
			)
		);
	}

	protected function mvl_add_listings_skins_to_elementor_display_settings( array $settings, $skin_type = 'list', $additional_skins = array() ): array {
		$skins = array(
			'list' => self::LIST_SKINS,
			'grid' => self::GRID_SKINS,
		);

		if ( isset( $additional_skins['list'] ) ) {
			$skins['list'] = array_merge( $skins['list'], $additional_skins['list'] );
		}

		if ( isset( $additional_skins['grid'] ) ) {
			$skins['grid'] = array_merge( $skins['grid'], $additional_skins['grid'] );
		}

		foreach ( array( 'list', 'grid' ) as $type ) {
			if ( ! isset( $settings[ 'listings_' . $type . '_view_skin' ] ) || ! in_array( $settings[ 'listings_' . $type . '_view_skin' ], $skins[ $type ], true ) || ! is_mvl_pro() && 'default' !== $settings[ 'listings_' . $type . '_view_skin' ] ) {
				$settings[ 'listings_' . $type . '_view_skin' ] = 'default';
			}
		}

		return $settings;
	}

	protected function mvl_add_listings_grid_styles_elementor_section( $widget, array $section_args = array(), array $args = array() ): void {
		$widget->start_controls_section(
			'listings_grid_styles',
			wp_parse_args(
				$section_args,
				array(
					'label'     => esc_html__( 'Listing Card Styles', 'stm_vehicles_listing' ),
					'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => array(
						'listings_grid_view_skin!' => 'default',
					),
				)
			)
		);

		$widget->add_control(
			'listings_grid_styles_info',
			wp_parse_args(
				$args,
				array(
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'You can edit Listing Card styles in', 'stm_vehicles_listing' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=mvl_search_results_settings' ) ) . '" target="_blank">' . esc_html__( 'Search Results Settings', 'stm_vehicles_listing' ) . '</a>',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			)
		);

		$widget->end_controls_section();
	}

	protected function mvl_add_listings_list_styles_elementor_section( $widget, array $section_args = array(), array $args = array() ): void {
		$widget->start_controls_section(
			'listings_list_styles',
			wp_parse_args(
				$section_args,
				array(
					'label'     => esc_html__( 'Listing Card Styles', 'stm_vehicles_listing' ),
					'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
					'condition' => array(
						'listings_list_view_skin!' => 'default',
					),
				)
			)
		);

		$widget->add_control(
			'listings_list_styles_info',
			wp_parse_args(
				$args,
				array(
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'You can edit Listing Card styles in', 'stm_vehicles_listing' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=mvl_search_results_settings' ) ) . '" target="_blank">' . esc_html__( 'Search Results Settings', 'stm_vehicles_listing' ) . '</a>',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			)
		);

		$widget->end_controls_section();
	}

	protected function mvl_add_listings_grid_skins_elementor_control( $widget, $args = array() ): void {
		$id = 'listings_grid_view_skin';

		if ( isset( $args['id'] ) ) {
			$id = $args['id'];
			unset( $args['id'] );
		}

		if ( isset( $args['options'] ) ) {
			$args['lockedOptions'] = array_keys( $args['options'] );
		}

		$widget->add_control(
			$id,
			wp_parse_args(
				$args,
				array(
					'label'         => esc_html__( 'Listing Card Skin', 'stm_vehicles_listing' ),
					'type'          => \Elementor\Controls_Manager::SELECT2,
					'default'       => 'default',
					'multiple'      => false,
					'options'       => apply_filters(
						'mvl_add_listings_grid_skins_to_elementor_control_options',
						array(
							'default' => esc_html__( 'Default', 'stm_vehicles_listing' ),
						)
					),
					'lockedOptions' => self::GRID_SKINS,
				),
			)
		);
	}

	protected function mvl_add_listings_list_skins_elementor_control( $widget, $args = array() ): void {
		$id = 'listings_list_view_skin';

		if ( isset( $args['id'] ) ) {
			$id = $args['id'];
			unset( $args['id'] );
		}

		if ( isset( $args['options'] ) ) {
			$args['lockedOptions'] = array_keys( $args['options'] );
		}

		$widget->add_control(
			$id,
			wp_parse_args(
				$args,
				array(
					'label'         => esc_html__( 'Listing Card Skin', 'stm_vehicles_listing' ),
					'type'          => \Elementor\Controls_Manager::SELECT2,
					'default'       => 'default',
					'multiple'      => false,
					'options'       => apply_filters(
						'mvl_add_listings_list_skins_to_elementor_control_options',
						array(
							'default' => esc_html__( 'Default', 'stm_vehicles_listing' ),
						)
					),
					'lockedOptions' => self::LIST_SKINS,
				),
			)
		);
	}
}
