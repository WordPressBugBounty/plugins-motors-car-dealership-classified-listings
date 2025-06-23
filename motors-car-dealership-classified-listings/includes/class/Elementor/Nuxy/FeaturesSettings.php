<?php

namespace MotorsVehiclesListing\Elementor\Nuxy;

class FeaturesSettings {

	public function __construct() {
		add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', array( $this, 'mvl_menu_features_settings' ), 11, 1 );
	}

	public function motors_get_features_list() {
		global $wpdb;

		$terms         = $wpdb->get_results( $wpdb->prepare( "SELECT t.* FROM {$wpdb->prefix}terms AS t LEFT JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy=%s", 'stm_additional_features' ) );
		$features_list = array();

		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $key => $feature ) {
				$features_list[ $key ]['label'] = $feature->name;
				$features_list[ $key ]['value'] = $feature->slug;
			}
		}

		return $features_list;
	}

	public function mvl_menu_features_settings() {
		$title = esc_html__( 'Features', 'stm_vehicles_listing' );

		$post_type = apply_filters( 'stm_listings_post_type', 'listings' );

		add_submenu_page(
			'mvl_plugin_settings',
			$title,
			$title,
			'manage_options',
			'edit-tags.php?taxonomy=stm_additional_features&post_type=' . $post_type,
			'',
			50
		);

		add_filter(
			'mvl_submenu_positions',
			function ( $positions ) use ( $post_type ) {
				$positions[ 'edit-tags.php?taxonomy=stm_additional_features&post_type=' . $post_type ] = 50;

				return $positions;
			}
		);
	}
}
