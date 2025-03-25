<?php

namespace MotorsVehiclesListing\Elementor\Nuxy;

class FeaturesSettings {

	public function __construct() {
		add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', array( $this, 'mvl_menu_features_settings' ), 11, 1 );
		add_filter( 'me_car_settings_conf', array( $this, 'motors_config_map_tab_features_settings' ), 41, 1 );
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

	public function motors_config_map_tab_features_settings( $global_conf ) {
		return array_merge( $global_conf, $this->listing_features() );
	}

	private function listing_features() {

		//require_once MOTORS_ELEMENTOR_WIDGETS_INC_PATH . 'admin-new-features-notice.php';

		$new_settings = array();
		if ( ! empty( get_option( 'stm_motors_chosen_template' ) ) ) {
			$options      = get_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_DETAILS_OPT_NAME, array() );
			$new_settings = ( ! empty( $options['fs_user_features'] ) ) ? $options['fs_user_features'] : $new_settings;
			if ( empty( $new_settings ) ) {
				$features     = $this->motors_get_features_list();
				$old_settings = ( ! empty( $options['addl_user_features'] ) ) ? $options['addl_user_features'] : array();

				if ( ! empty( $old_settings ) ) {
					foreach ( $old_settings as $k => $old_setting ) {
						$old_features        = explode( ',', $old_setting['tab_title_labels'] );
						$features_for_select = array();
						foreach ( $features as $feature ) {
							if ( in_array( $feature['label'], $old_features, true ) ) {
								$features_for_select[] = $feature;
							}
						}
						$old_settings[ $k ]['tab_title_selected_labels'] = $features_for_select;
					}
				}
				$options['fs_user_features'] = $old_settings;
				update_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_DETAILS_OPT_NAME, $options );
				do_action( 'stm_print_styles_color' );
			}
		}

		return array(
			'fs_group_features_title' => array(
				'type'             => 'group_title',
				'label'            => esc_html__( 'Features list', 'stm_vehicles_listing' ),
				'description'      => wp_kses_post( 'Create a list of features for your listings. <br/>You can specify a title for the feature list and choose which features to include in it. <a href="' . get_site_url() . '/wp-admin/edit-tags.php?taxonomy=stm_additional_features&post_type=listings" target="_blank">Add Feature</a>' ),
				'preview'          => STM_LISTINGS_URL . '/assets/images/previews/features.png',
				'preview_position' => 'preview_bottom',
				'submenu'          => esc_html__( 'Features list', 'stm_vehicles_listing' ),
				'group'            => 'started',
			),
			'fs_user_features'        => array(
				'type'   => 'repeater',
				'label'  => esc_html__( 'List', 'stm_vehicles_listing' ),
				'fields' => array(
					'tab_title_single'          => array(
						'type'  => 'text',
						'label' => esc_html__( 'Title', 'stm_vehicles_listing' ),
					),
					'tab_title_selected_labels' => array(
						'type'    => 'multiselect',
						'label'   => esc_html__( 'Add features to the list', 'stm_vehicles_listing' ),
						'options' => $this->motors_get_features_list(),
						'new_tag_settings' => array(
							'add_label'     => esc_html__( 'Add New Feature', 'stm_vehicles_listing' ),
							'taxonomy_name' => 'stm_additional_features',
							'placeholder'   => 'Enter feature name',
							'add_btn'       => 'Add',
							'cancel_btn'    => 'Cancel',
						),
					),
				),
				'load_labels' => array(
					'add_label' => esc_html__( 'Add New List', 'stm_vehicles_listing' ),
				),
				'value'  => $new_settings ?? array(),
				'submenu' => esc_html__( 'Features list', 'stm_vehicles_listing' ),
				'group'  => 'ended',
			),
		);
	}

	public function mvl_menu_features_settings() {
		$title = esc_html__( 'Extra Features', 'stm_vehicles_listing' );

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
