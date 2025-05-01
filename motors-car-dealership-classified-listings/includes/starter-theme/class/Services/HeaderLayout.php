<?php
namespace MotorsStarterTheme\Services;

use MotorsStarterTheme\Instance;

class HeaderLayout extends Instance {

	protected function __construct() {
		add_filter( 'hfe_header_enabled', array( $this, 'hfe_header_enabled' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );

		//Filters using in motors-starter-theme
		add_filter( 'mst_get_logo_src', array( $this, 'logo_src' ) );
		add_filter( 'mst_get_header_socials', array( $this, 'socials' ) );
		add_filter( 'mst_get_logo_width', array( $this, 'logo_width' ) );
		add_filter( 'mst_get_header_layout', array( $this, 'current' ) );
		add_filter( 'mst_is_logo_image_exists', array( $this, 'is_logo_image_exists' ) );
		add_filter( 'mst_header_classes', array( $this, 'header_classes' ) );
	}

	public function header_classes( $classes ) {
		$fixed_header = apply_filters( 'motors_vl_get_nuxy_mod', false, 'mst_header_sticky' );
		if ( ! empty( $fixed_header ) && $fixed_header ) {
			$classes[] = 'header-listing-fixed';
		} else {
			$classes[] = 'header-listing-unfixed';
		}
		if ( boolval( apply_filters( 'is_listing', false ) ) ) {
			$classes[] = 'is-listing';
		}
		return $classes;
	}

	public function socials() {
		return apply_filters( 'mst_socials_buttons', array(), 'mst_header_socials_enable' );
	}

	public function body_class( $classes ) {
		$classes[]    = 'stm-layout-header-' . static::current();
		$fixed_header = apply_filters( 'motors_vl_get_nuxy_mod', false, 'mst_header_sticky' );
		if ( ! empty( $fixed_header ) && $fixed_header ) {
			$classes[] = 'header-sticky-enabled';
		}
		return $classes;
	}

	public function hfe_header_enabled( $status ) {
		if ( 'default' === static::current() ) {
			return $status;
		}
		return false;
	}

	public function logo_width() {
		$width = apply_filters( 'motors_vl_get_nuxy_mod', '140', 'mst_header_logo_width' );
		return $width ? $width : '140';
	}

	public function current() {
		return apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'mst_header_layout' );
	}

	public function is_logo_image_exists() {
		$url         = $this->logo_src();
		$remote_args = array();

		if ( empty( $url ) ) {
			return false;
		}

		if ( defined( 'STM_DEV_MODE' ) && STM_DEV_MODE ) {
			$remote_args = array(
				'sslverify' => false,
			);
		}

		$response = wp_remote_head( $url, $remote_args );

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			return true;
		}

		return false;
	}

	public function logo_src( $size = 'full' ) {
		$image = apply_filters( 'motors_vl_get_nuxy_mod', '', 'mst_header_logo' );
		if ( is_numeric( $image ) && $image > 0 ) {
			$image = wp_get_attachment_image_url( $image, $size );
			// always return original full size image for logo.
			if ( is_string( $image ) && preg_match( '/-\d+[Xx]\d+\./', $image ) ) {
				$image = preg_replace( '/-\d+[Xx]\d+\./', '.', $image );
			}
		}

		return $image;
	}
}
