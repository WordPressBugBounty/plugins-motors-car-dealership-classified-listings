<?php
namespace MotorsVehiclesListing\Stilization;

/**
 * Singleton class
 * Has only static methods for public usage
 */
class Colors {
	public const DEFAULT = array(
		'free'   => array(
			'accent_color'        => '#1280DF',
			'bg_color'            => '#FFFFFF',
			'bg_shade'            => '#F0F3F7',
			'bg_contrast'         => '#35475A',
			'text_color'          => '#010101',
			'contrast_text_color' => '#FFFFFF',
			'filter_inputs_color' => '#F6F7F9',
			'spec_badge_color'    => '#FAB637',
			'sold_badge_color'    => '#FC4E4E',
			'success_bg_color'    => '#dbf2a2',
			'success_text_color'  => '#5eac3f',
			'notice_bg_color'     => '#fbc45d',
			'notice_text_color'   => '#e4961a',
			'error_bg_color'      => 'rgba(255,127,127,1)',
			'error_text_color'    => 'rgba(244,43,43,1)',
		),
		'luxury' => array(
			'accent_color'        => '#A08254',
			'bg_color'            => '#0C1315',
			'bg_shade'            => '#161A1D',
			'bg_contrast'         => '#161A1D',
			'text_color'          => '#E9E9E9',
			'contrast_text_color' => '#E9E9E9',
			'filter_inputs_color' => 'rgba(115,115,115,0.15)',
			'spec_badge_color'    => '#A08154',
			'sold_badge_color'    => '#FC4E4E',
			'success_bg_color'    => 'rgba(219,243,160,1)',
			'success_text_color'  => 'rgba(70,191,19,1)',
			'notice_bg_color'     => 'rgba(251,197,93,1)',
			'notice_text_color'   => 'rgba(251,149,19,1)',
			'error_bg_color'      => 'rgba(255,127,127,1)',
			'error_text_color'    => 'rgba(209,52,52,1)',
		),
	);

	/**
	 * @var self $instance
	 */
	protected static $instance;

	/**
	 * @var bool $instance_created
	 */
	protected static $instance_created = false;

	/**
	 * @var Color[] $colors
	 */
	protected $colors = array();

	/**
	 * Protected methods
	 */
	protected function __construct() {
		$skin_name = motors_get_skin_name();
		foreach ( static::DEFAULT[ $skin_name ] as $id => $value ) {
			$this->colors[ $id ] = Color::load( $id, $skin_name );
		}
	}

	/**
	 * @param string $id
	 * @param float $alphachannel
	 * @return string
	 */
	protected function get_value( string $id, float $alphachannel ) {
		return $this->colors[ $id ]->get_value( $alphachannel );
	}

	/**
	 * @return array
	 */
	protected function get_in_format_of_elementor_settings() {
		$settings = array();

		foreach ( $this->colors as $color ) {
			if ( $color->is_include_in_elementor() ) {
				$settings[] = $color->get_in_format_of_elementor_settings();

				foreach ( $color->get_children() as $child ) {
					$settings[] = $child->get_in_format_of_elementor_settings();
				}
			}
		}

		return $settings;
	}

	/**
	 * @return string
	 */
	protected function get_elementor_global_vars_css() {
		$css = array();
		foreach ( $this->colors as $color ) {
			if ( $color->is_include_in_elementor() ) {
				$css[] = $color->get_elementor_css_var_name() . ': var(' . $color->get_plugin_css_var_name() . ');';

				foreach ( $color->get_children() as $child ) {
					$css[] = $child->get_elementor_css_var_name() . ': var(' . $child->get_plugin_css_var_name() . ');';
				}
			}
		}
		return implode( PHP_EOL, $css );
	}

	/**
	 * @return Colors
	 */
	protected static function instance() {
		if ( ! static::$instance_created ) {
			static::$instance         = new static();
			static::$instance_created = true;
		}
		return static::$instance;
	}

	//Public methods

	/**
	 * @return string
	 */
	public static function elementor_global_vars_css() {
		return static::instance()->get_elementor_global_vars_css();
	}

	/**
	 * @return array
	 */
	public static function in_elementor_settings_format() {
		return static::instance()->get_in_format_of_elementor_settings();
	}

	/**
	 * @param string $id
	 * @param float $alphachannel
	 * @param string $default_color
	 * @return string
	 */
	public static function value( $id, $alphachannel = -1.0, $default_color = '#000000' ) {
		$value = static::instance()->get_value( $id, $alphachannel );
		return $value ? $value : $default_color;
	}

	/**
	 * @param string $id
	 * @return string
	 */
	public static function default_value( $id ) {
		$skin_name = motors_get_skin_name();
		return static::DEFAULT[ $skin_name ][ $id ];
	}

	/**
	 * @return void
	 */
	public static function import_to_elementor() {
		$kit = static::get_elementor_kit();
		if ( $kit && static::of_elementor_can_be_replaced() ) {
			$kit->update_settings(
				array(
					'system_colors' => static::in_elementor_settings_format(),
				)
			);
		}
	}

	/**
	 * @return bool|\Elementor\Core\Base\Document
	 */
	public static function get_elementor_kit() {
		$kit = false;

		if ( class_exists( '\\Elementor\\Plugin' ) ) {
			$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();

			if ( ! $kit->get_id() ) {
				$created_default_kit = \Elementor\Plugin::$instance->kits_manager->create_default();

				if ( $created_default_kit ) {
					update_option( \Elementor\Core\Kits\Manager::OPTION_ACTIVE, $created_default_kit );
				}

				$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();
			}
		}

		return $kit;
	}

	/**
	 * @return bool
	 */
	public static function of_elementor_can_be_replaced() {
		return apply_filters( 'motors_vl_get_nuxy_mod', false, 'replace_elementor_colors' ) && class_exists( '\\Elementor\\Plugin' ) && get_template() === 'motors-starter-theme';
	}

	/**
	 * @param string $id
	 * @return Color
	 */
	public static function item( $id ) {
		return static::instance()->colors[ $id ];
	}

	/**
	 * @param string $color_id
	 * @param string $color_data_key
	 * @return array
	 */
	public static function data_for_elementor( $color_id = '' ) {
		$colors_data = array(
			'accent_color'        => array(
				'name' => __( 'Accent', 'stm_vehicles_listing' ),
				'id'   => 'accent',
			),
			'bg_color'            => array(
				'name' => __( 'Background' ),
				'id'   => 'bg',
			),
			'bg_shade'            => array(
				'name' => __( 'Secondary Background', 'stm_vehicles_listing' ),
				'id'   => 'bg_shade',
			),
			'bg_contrast'         => array(
				'name' => __( 'Accent Background', 'stm_vehicles_listing' ),
				'id'   => 'bg_contrast',
			),
			'text_color'          => array(
				'name'     => __( 'Text', 'stm_vehicles_listing' ),
				'id'       => 'text',
				'children' => array(
					'border_color'         => array(
						'name'         => __( 'Border', 'stm_vehicles_listing' ),
						'id'           => 'border',
						'alphachannel' => 0.15,
					),
					'text_alpha_color'     => array(
						'name'         => __( 'Text (0.5)', 'stm_vehicles_listing' ),
						'id'           => 'alpha_text',
						'alphachannel' => 0.50,
					),
					'text_highalpha_color' => array(
						'name'         => __( 'Text (0.7)', 'stm_vehicles_listing' ),
						'id'           => 'highalpha_text',
						'alphachannel' => 0.70,
					),
				),
			),
			'contrast_text_color' => array(
				'name'     => __( 'Accent BG Text', 'stm_vehicles_listing' ),
				'id'       => 'contrast_text',
				'children' => array(
					'border_contrast'           => array(
						'name'         => __( 'Accent BG Border', 'stm_vehicles_listing' ),
						'id'           => 'contrast_border',
						'alphachannel' => 0.15,
					),
					'contrast_text_alpha_color' => array(
						'name'         => __( 'Accent BG Text (0.5)', 'stm_vehicles_listing' ),
						'id'           => 'alpha_contrast_text',
						'alphachannel' => 0.50,
					),
				),
			),
			'spec_badge_color'    => array(
				'name' => __( 'Special Badge', 'stm_vehicles_listing' ),
				'id'   => 'spec_badge',
			),
			'sold_badge_color'    => array(
				'name' => __( 'Sold Badge', 'stm_vehicles_listing' ),
				'id'   => 'sold_badge',
			),
			'filter_inputs_color' => array(
				'name' => __( 'Inputs and actions', 'stm_vehicles_listing' ),
				'id'   => 'inputs_actions',
			),
		);

		if ( $color_id ) {
			return isset( $colors_data[ $color_id ] ) ? $colors_data[ $color_id ] : array();
		}

		return $colors_data;
	}

}
