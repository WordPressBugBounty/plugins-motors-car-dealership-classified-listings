<?php
namespace MotorsStarterTheme\Services;

use MotorsStarterTheme\Instance;

class Socials extends Instance {
	protected const ICONS = array();

	public function __construct() {
		add_filter( 'mst_socials_list', array( $this, 'list' ) );
		add_filter( 'mst_socials_options', array( $this, 'multi_input_options' ) );
		add_filter( 'mst_socials_buttons', array( $this, 'buttons' ), 10, 2 );
		add_filter( 'mst_social_icon_class', array( $this, 'icon_class' ) );
	}

	public function icon_class( $social ) {
		return isset( static::ICONS[ $social ] ) ? static::ICONS[ $social ] : $social;
	}

	public function list() {
		$socials = array(
			'facebook'   => esc_html__( 'Facebook', 'stm_vehicles_listing' ),
			'x-twitter'  => esc_html__( 'X', 'stm_vehicles_listing' ),
			'vk'         => esc_html__( 'VK', 'stm_vehicles_listing' ),
			'instagram'  => esc_html__( 'Instagram', 'stm_vehicles_listing' ),
			'behance'    => esc_html__( 'Behance', 'stm_vehicles_listing' ),
			'dribbble'   => esc_html__( 'Dribbble', 'stm_vehicles_listing' ),
			'flickr'     => esc_html__( 'Flickr', 'stm_vehicles_listing' ),
			'linkedin'   => esc_html__( 'Linkedin', 'stm_vehicles_listing' ),
			'pinterest'  => esc_html__( 'Pinterest', 'stm_vehicles_listing' ),
			'yahoo'      => esc_html__( 'Yahoo', 'stm_vehicles_listing' ),
			'delicious'  => esc_html__( 'Delicious', 'stm_vehicles_listing' ),
			'dropbox'    => esc_html__( 'Dropbox', 'stm_vehicles_listing' ),
			'reddit'     => esc_html__( 'Reddit', 'stm_vehicles_listing' ),
			'soundcloud' => esc_html__( 'Soundcloud', 'stm_vehicles_listing' ),
			'youtube'    => esc_html__( 'Youtube', 'stm_vehicles_listing' ),
			'tumblr'     => esc_html__( 'Tumblr', 'stm_vehicles_listing' ),
			'whatsapp'   => esc_html__( 'Whatsapp', 'stm_vehicles_listing' ),
			'tiktok'     => esc_html__( 'Tiktok', 'stm_vehicles_listing' ),
		);

		return $socials;
	}

	public function multi_input_options() {
		$socials          = static::list();
		$response_socials = array();

		foreach ( $socials as $k => $social ) {
			$response_socials[] = array(
				'key'   => $k,
				'label' => $social,
			);
		}

		return $response_socials;
	}

	public function buttons( $socials_array, $socials_conf_name ) {

		$header_socials_enable = apply_filters( 'motors_vl_get_nuxy_mod', false, $socials_conf_name );

		$socials = apply_filters( 'motors_vl_get_nuxy_mod', '', 'mst_socials_link' );

		$socials_values = array();
		if ( ! empty( $socials ) && is_array( $socials ) ) {
			foreach ( $socials as $k => $soc ) {
				if ( ! empty( $soc['value'] ) ) {
					$socials_values[ $soc['key'] ] = $soc['value'];
				}
			}
		}

		if ( $header_socials_enable && is_array( $header_socials_enable ) ) {
			foreach ( $socials_values as $social => $value ) {
				if ( in_array( $social, $header_socials_enable, true ) ) {
					$socials_array[ $social ] = $value;
				}
			}
		}

		return $socials_array;
	}
}
