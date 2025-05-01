<?php
namespace MotorsStarterTheme\Services;

use MotorsStarterTheme\Instance;

class GoogleFonts extends Instance {

	const TYPOGRAPHY_CONF = array(
		'mst_header_menu_typography',
		'mst_header_logo_typography',
		'mst_header_submenu_typography',
	);

	protected function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	public function wp_enqueue_scripts() {
		$query_args            = array();
		$enable_download_fonts = apply_filters( 'motors_vl_get_nuxy_mod', false, 'mst_download_fonts' );
		$font_families_local   = array();
		$font_families         = array();

		foreach ( static::TYPOGRAPHY_CONF as $conf ) {
			$font = apply_filters( 'motors_vl_get_nuxy_mod', false, $conf );
			if ( $enable_download_fonts && ! empty( $font['font-data']['local_url'] ) && ! empty( $font['font-data']['family'] ) ) {
				$font_families_local['typography_body_font_family'] = $font['font-data']['local_url'];
			} elseif ( ! empty( $font['font-data']['family'] ) ) {
				$font_families[ strtolower( str_replace( ' ', '_', $font['font-data']['family'] ) ) ] = $font['font-data']['family'] . ':' . implode( ',', $font['font-data']['variants'] );
			}
		}

		if ( ! empty( $font_families_local ) ) {
			$fonts_url_local['fonts_local'] = $font_families_local;
		} elseif ( ! empty( $font_families ) ) {
			$query_args = array(
				'family' => rawurlencode( implode( '|', $font_families ) ),
				'subset' => rawurlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		} else {
			$montserrat = _x( 'on', 'Montserrat font: on or off', 'stm_vehicles_listing' );
			$open_sans  = _x( 'on', 'Open Sans font: on or off', 'stm_vehicles_listing' );

			if ( 'off' !== $montserrat || 'off' !== $open_sans ) {
				$font_families = array();

				if ( 'off' !== $montserrat ) {
					$font_families[] = 'Montserrat:400,500,600,700,800,900';
				}

				if ( 'off' !== $open_sans ) {
					$font_families[] = 'Open Sans:300,400,500,700,800,900';
				}

				$query_args = array(
					'family' => rawurlencode( implode( '|', $font_families ) ),
					'subset' => rawurlencode( 'latin,latin-ext' ),
				);

				$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
			}
		}

		if ( ! empty( $fonts_url_local ) ) {
			$fonts_enqueue = $fonts_url_local;
		} else {
			$fonts_enqueue = esc_url_raw( $fonts_url );
		}

		if ( ! empty( $fonts_enqueue['fonts_local'] ) ) {
			foreach ( $fonts_enqueue['fonts_local'] as $key => $font ) {
				$font_url = site_url( $font );
				wp_enqueue_style( 'mst_default_google_font_' . $key, $font_url, null, MOTORS_STARTER_THEME_VERSION, 'all' );
			}
		} else {
			wp_enqueue_style( 'mst_default_google_font', $fonts_enqueue, null, MOTORS_STARTER_THEME_VERSION, 'all' );
		}
	}
}
