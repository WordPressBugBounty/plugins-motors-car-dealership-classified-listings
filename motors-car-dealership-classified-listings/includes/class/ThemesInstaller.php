<?php

namespace MotorsVehiclesListing;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


abstract class ThemesInstaller {

	abstract protected static function get_install_themes_list(): array;

	public static function get_data() {
		$data = static::get_install_themes_list();
		foreach ( $data as $key => $item ) {
			$data[ $key ] = array_merge( $item, static::get_item_info( $item['slug'] ) );
		}
		return $data;
	}

	/**
	 * Get info by slug
	 * @param $slug
	 */
	public static function get_item_info( $slug ) {
		$result = array(
			'data'         => array(),
			'is_installed' => false,
			'is_active'    => false,
		);

		$installed    = wp_get_themes();
		$is_installed = array_key_exists( $slug, $installed ) || in_array( $slug, $installed, true );
		if ( $is_installed ) {
			$result['is_installed']    = true;
			$result['is_active']       = ( wp_get_theme()->get( 'TextDomain' ) === $installed[ $slug ]->get( 'TextDomain' ) );
			$result['data']['version'] = $installed[ $slug ]->get( 'Version' );
		}
		return $result;
	}

	public static function install( $slug ) {
		static::load_wp();

		$src       = static::get_source( $slug );
		$skin      = new \Automatic_Upgrader_Skin();
		$upgrader  = new \Theme_Upgrader( $skin );
		$installed = $upgrader->install( $src );

		$result = array( 'success' => false );

		if ( is_wp_error( $installed ) ) {
			$result['error'] = $installed->get_error_message();
		} else {
			static::activate( $slug );
			$result['success'] = true;
		}

		return $result;
	}

	public static function activate( $slug ) {
		if ( current_user_can( 'switch_themes' ) ) {
			switch_theme( $slug );
		}
	}

	public static function upgrade( $slug ) {
		static::load_wp();
		$upgrader = new \Theme_Upgrader();
		$upgraded = $upgrader->upgrade( $slug );

		return $upgraded;
	}

	private static function load_wp() {
		require_once ABSPATH . 'wp-load.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
	}

	private static function get_source( $slug ) {
		$install_data = static::get_data();

		$key = array_search( $slug, array_column( $install_data, 'slug' ) ); //phpcs:ignore
		$src = null;

		if ( array_key_exists( 'src', $install_data[ $key ] ) ) {
			$src = $install_data[ $key ]['src'];
		}

		if ( null === $src ) {
			$response = themes_api( 'theme_information', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $response ) && ! empty( $response->download_link ) ) {
				$src = $response->download_link;
			}
		}

		return $src;

	}
}
