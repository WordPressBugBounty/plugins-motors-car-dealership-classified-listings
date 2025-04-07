<?php

function mvl_setup_wizard_load_step() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'stm_vehicles_listing' ) );
	}

	$response = array();

	$prefix_data        = 'mvl_data_';
	$prefix_settings    = 'mvl_setting_';
	$data_to_update     = array();
	$settings_to_update = array();

	foreach ( $_POST as $post_key => $post_data ) {
		if ( strpos( $post_key, $prefix_data ) === 0 ) {
			$data_to_update[ substr( $post_key, strlen( $prefix_data ) ) ] = sanitize_text_field( $post_data );
		}
		if ( strpos( $post_key, $prefix_settings ) === 0 ) {
			$settings_to_update[ substr( $post_key, strlen( $prefix_settings ) ) ] = sanitize_text_field( $post_data );
		}
	}

	if ( ! empty( $data_to_update ) ) {
		update_option( 'mvl_setup_wizard_data', $data_to_update );
		$response['wizard_sata_updated'] = true;
	}

	if ( ! empty( $settings_to_update ) ) {
		$options_draft = get_option( 'mvl_setup_wizard_settings_temp', array() );
		update_option( 'mvl_setup_wizard_settings_temp', array_unique( array_merge( $options_draft, $settings_to_update ) ) );
		mvl_setup_wizard_update_settings( $settings_to_update );

		$response['plugin_settings_updated'] = true;
	}

	ob_start();

	do_action( 'mvl_setup_wizard_load_step', sanitize_text_field( $_POST['step'] ) );

	$response['output'] = ob_get_clean();

	wp_send_json( $response );
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_load_step', 'mvl_setup_wizard_load_step' );

function mvl_setup_wizard_update_settings( $settings_to_update ) {
	$settings_names = apply_filters( 'mvl_settings_option_names', array() );

	foreach ( $settings_names as $settings_key => $settings_name ) {
		$options     = get_option( $settings_name, array() );
		$options_map = wpcfto_get_settings_map( 'settings', $settings_name );

		if ( ! is_array( $options_map ) || ! isset( $options_map[ $settings_key ] ) || ! isset( $options_map[ $settings_key ]['fields'] ) ) {
			continue;
		}

		$options_fields = $options_map[ $settings_key ]['fields'];

		foreach ( $settings_to_update as $opt_name => $value ) {
			if ( isset( $options_fields[ $opt_name ] ) ) {
				if ( is_string( $value ) && in_array( strtolower( trim( $value ) ), array( 'true', 'false' ), true ) ) {
					$options[ $opt_name ] = ( 'true' === $value );
				} else {
					$options[ $opt_name ] = $value;
				}
			}
		}

		update_option( $settings_name, $options );
	}
}

function mvl_setup_wizard_install_starter_theme() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'install_themes' ) ) {
		wp_send_json_error( __( 'You do not have permission to install themes', 'stm_vehicles_listing' ) );
	}

	$install_class = \MotorsVehiclesListing\StarterTheme\Helpers\Themes::class;

	if ( null === $install_class ) {
		wp_send_json_error( array( 'error' => 'Class not exist' ) );
	}

	$slug = 'motors-starter-theme';

	$data = $install_class::get_item_info( $slug );
	if ( false === $data['is_installed'] ) {
		$install_class::install( $slug );
		$install_class::activate( $slug );
	}

	if ( $data['is_installed'] && false === $data['is_active'] ) {
		$install_class::activate( $slug );
	}

	$final_data = $install_class::get_item_info( $slug );

	wp_send_json_success( $final_data );
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_install_starter_theme', 'mvl_setup_wizard_install_starter_theme' );

function mvl_setup_wizard_install_plugin() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_send_json_error( __( 'You do not have permission to install plugins', 'stm_vehicles_listing' ) );
	}

	$plugin_slug = isset( $_POST['plugin'] ) ? sanitize_text_field( $_POST['plugin'] ) : '';

	if ( empty( $plugin_slug ) ) {
		wp_send_json_error( __( 'Plugin info not provided', 'stm_vehicles_listing' ) );
	}

	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' ) ) {

		$plugin_api_url = 'https://api.wordpress.org/plugins/info/1.0/' . $plugin_slug . '.json';

		$response = wp_remote_get( $plugin_api_url );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( __( 'Error requesting plugin', 'stm_vehicles_listing' ) );
		}

		$plugin_data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $plugin_data['download_link'] ) ) {
			wp_send_json_error( __( 'Error downloading plugin', 'stm_vehicles_listing' ) );
		}

		$plugin_url = esc_url_raw( $plugin_data['download_link'] );

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$skin      = new Automatic_Upgrader_Skin();
		$upgrader  = new Plugin_Upgrader( $skin );
		$installed = $upgrader->install( $plugin_url );

		if ( is_wp_error( $installed ) ) {
			wp_send_json_error( __( 'Error installing plugin', 'stm_vehicles_listing' ) );
		}
	}

	$plugins = get_plugins( '/' . $plugin_slug );

	if ( empty( $plugins ) ) {
		wp_send_json_error( __( 'Could not find main plugin file', 'stm_vehicles_listing' ) );
	}

	$plugin_main_file = key( $plugins );
	$activated        = activate_plugin( $plugin_slug . '/' . $plugin_main_file, false, false, true );

	if ( ! is_wp_error( $activated ) ) {
		wp_send_json_success( __( 'Plugin successfully activated', 'stm_vehicles_listing' ) );
	} else {
		wp_send_json_error( __( 'Error activating plugin', 'stm_vehicles_listing' ) );
	}
	exit;
}

add_action( 'wp_ajax_mvl_setup_wizard_install_plugin', 'mvl_setup_wizard_install_plugin' );

function mvl_setup_wizard_starter_import_fields() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'stm_vehicles_listing' ) );
	}

	$response = motors_get_demo_data( 'listing_categories.json' );

	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_null( $response ) ) {
			$options_preset = get_option( 'stm_vehicle_listing_options', array() );
			update_option( 'stm_vehicle_listing_options', array_merge( $options_preset, $response ) );
			wp_send_json_success( $response );
		}
	}

	wp_send_json_error();
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_starter_import_fields', 'mvl_setup_wizard_starter_import_fields' );

function mvl_setup_wizard_starter_import_settings() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'stm_vehicles_listing' ) );
	}

	$remote_args = array();
	if ( defined( 'STM_DEV_MODE' ) && true === STM_DEV_MODE ) {
		$remote_args = array(
			'sslverify' => false,
		);
	}

	$response = motors_get_demo_data( 'mvl_settings.json' );

	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_null( $response ) ) {

			$options_draft     = get_option( 'mvl_setup_wizard_settings_temp', array() );
			$combined_settings = array_merge( $response, $options_draft );

			mvl_setup_wizard_update_settings( $combined_settings );
			update_option( 'motors_vehicles_listing_plugin_settings_updated', true );

			$home = get_page_by_path( 'home' );
			if ( $home ) {
				update_option( 'page_on_front', $home->ID );
				update_option( 'show_on_front', 'page' );
			}

			if ( is_plugin_active( 'elementor/elementor.php' ) ) {
				$elementor_cpt = get_option( 'elementor_cpt_support' );
				if ( ! empty( $elementor_cpt ) ) {
					if ( is_array( $elementor_cpt ) && ! isset( $elementor_cpt['listing_template'] ) ) {
						$elementor_cpt[] = 'listing_template';
						update_option( 'elementor_cpt_support', $elementor_cpt );
					}
				} else {
					$elementor_cpt = array( 'post', 'page', 'listing_template' );
					update_option( 'elementor_cpt_support', $elementor_cpt );
				}

				if ( ! empty( $options_draft ) && ! empty( $options_draft['single_listing_template_name'] ) ) {
					$selected_id = apply_filters( 'mvl_get_template_id_by_slug', $options_draft['single_listing_template_name'] );
					$sl_options  = get_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_TEMPLATE_OPT_NAME, array() );
					if ( $selected_id && ! empty( $sl_options ) ) {
						$sl_options['single_listing_template'] = $selected_id;
						update_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_TEMPLATE_OPT_NAME, $sl_options );
					}
				}
			}

			if ( defined( 'MOTORS_STARTER_THEME_TEMPLATE_DIR' ) ) {
				\MotorsVehiclesListing\Plugin\Settings::update_one( 'replace_elementor_colors', 1 );
				add_filter( 'motors_vl_get_nuxy_mod', 'mvl_setup_wizard_replace_elementor_colors_filter', 100, 2 );
				\MotorsVehiclesListing\Stilization\Colors::import_to_elementor();
				remove_filter( 'motors_vl_get_nuxy_mod', 'mvl_setup_wizard_replace_elementor_colors_filter', 100, 2 );
				update_option( 'motors_starter_demo_activated', 1 );
			}

			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
			$wp_rewrite->flush_rules();

			wp_send_json_success( $response );
		}
	}

	wp_send_json_error();
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_starter_import_settings', 'mvl_setup_wizard_starter_import_settings' );

function mvl_setup_wizard_get_importer() {

	if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
		define( 'WP_LOAD_IMPORTERS', true );
	}

	require_once STM_LISTINGS_PATH . '/includes/lib/wordpress-importer/class-stm-wp-import.php';

	$importer = new STM_WP_Import();

	return $importer;
}

function mvl_setup_wizard_starter_import_content() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'stm_vehicles_listing' ) );
	}

	$importer = mvl_setup_wizard_get_importer();

	if ( ! is_object( $importer ) ) {
		wp_send_json_error( __( 'Error creating Importer object', 'stm_vehicles_listing' ) );
	}

	$importer->fetch_attachments = true;

	if ( defined( 'MOTORS_STARTER_THEME_TEMPLATE_DIR' ) ) {
		$response = motors_get_demo_data( 'demo.xml' );
		$demo     = motors_get_skin_name();

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( __( 'Failed to download demo content.', 'motors-starter-theme' ) );
		}

		$xml_content = wp_remote_retrieve_body( $response );

		if ( empty( $xml_content ) ) {
			wp_send_json_error( __( 'Demo content is empty or unavailable.', 'motors-starter-theme' ) );
		}

		$upload_dir  = wp_upload_dir();
		$import_file = $upload_dir['path'] . '/' . $demo . '-demo-content.xml';

		file_put_contents( $import_file, $xml_content );//phpcs:ignore

		if ( ! file_exists( $import_file ) ) {
			wp_send_json_error( __( 'Failed to save demo content locally.', 'motors-starter-theme' ) );
		}

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}
	} else {
		$import_file = STM_LISTINGS_PATH . '/dummy_content/demo-listings.xml';
	}

	ob_start();

	$importer->import( $import_file );

	ob_end_clean();

	if ( defined( 'MOTORS_STARTER_THEME_TEMPLATE_DIR' ) ) {
		unlink( $import_file );
	}

	if ( ! empty( $importer->processed_posts ) ) {
		wp_send_json_success();
	} else {
		wp_send_json_error();
	}
}
add_action( 'wp_ajax_mvl_setup_wizard_starter_import_content', 'mvl_setup_wizard_starter_import_content' );

function mvl_setup_wizard_generate_pages() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'stm_vehicles_listing' ) );
	}

	if ( defined( 'MOTORS_STARTER_THEME_VERSION' ) ) {
		wp_send_json_success();
	}

	$pages = apply_filters( 'mvl_essential_pages', array() );

	foreach ( $pages as $slug => $page ) {

		$post_data = array(
			'post_title'   => $page['title'],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => ( ! defined( 'ELEMENTOR_VERSION' ) ) ? $page['shortcode'] : '',
		);

		$id = wp_insert_post( $post_data );

		if ( $id ) {
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				update_post_meta( $id, '_elementor_edit_mode', 'builder' );
				update_post_meta( $id, '_elementor_template_type', 'wp-page' );
				update_post_meta( $id, '_wp_page_template', 'default' );
				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					update_post_meta( $id, '_elementor_version', ELEMENTOR_VERSION );
				}
				if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
					update_post_meta( $id, '_elementor_pro_version', ELEMENTOR_PRO_VERSION );
				}
				update_post_meta( $id, '_elementor_page_assets', array() );
				update_post_meta( $id, '_elementor_data', wp_slash( $page['elementor-data'] ) );
			}
		}
	}

	if ( defined( 'ELEMENTOR_VERSION' ) ) {

		$templates = apply_filters( 'mvl_elementor_listing_templates', array() );

		foreach ( $templates as $slug => $template ) {

			if ( apply_filters( 'mvl_get_template_id_by_slug', $slug ) ) {
				continue;
			}

			$post_data = array(
				'post_title'  => $template['title'],
				'post_name'   => $slug,
				'post_status' => 'publish',
				'post_type'   => 'listing_template',
			);

			$id = wp_insert_post( $post_data );

			if ( $id ) {
				update_post_meta( $id, '_elementor_edit_mode', 'builder' );
				update_post_meta( $id, '_elementor_template_type', 'wp-post' );
				update_post_meta( $id, '_wp_page_template', 'default' );
				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					update_post_meta( $id, '_elementor_version', ELEMENTOR_VERSION );
				}
				if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
					update_post_meta( $id, '_elementor_pro_version', ELEMENTOR_PRO_VERSION );
				}
				update_post_meta( $id, '_elementor_page_assets', array() );
				update_post_meta( $id, '_elementor_data', wp_slash( $template['elementor-data'] ) );
			}
		}

		$settings_temp = get_option( 'mvl_setup_wizard_settings_temp', array() );
		if ( ! empty( $settings_temp ) && ! empty( $settings_temp['single_listing_template_name'] ) ) {
			$selected_id = apply_filters( 'mvl_get_template_id_by_slug', $settings_temp['single_listing_template_name'] );
			$sl_options  = get_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_TEMPLATE_OPT_NAME, array() );
			if ( $selected_id && ! empty( $sl_options ) ) {
				$sl_options['single_listing_template'] = $selected_id;
				update_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_TEMPLATE_OPT_NAME, $sl_options );
			}
		}
	}

	$response = array(
		'success' => true,
	);

	wp_send_json( $response );
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_generate_pages', 'mvl_setup_wizard_generate_pages' );

function mvl_setup_wizard_replace_elementor_colors_filter( $value, $option ) {
	if ( 'replace_elementor_colors' === $option ) {
		$value = true;
	}
	return $value;
}

function mvl_setup_wizard_create_term() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'stm_vehicles_listing' ) );
	}

	$success  = true;
	$response = array();

	if ( ! empty( $_POST['terms'] ) && is_array( $_POST['terms'] ) ) {
		foreach ( $_POST['terms'] as $term ) {
			$new_born_term = wp_insert_term(
				$term,
				sanitize_key( $_POST['taxonomy'] ),
			);
			if ( is_wp_error( $new_born_term ) ) {
				$success = false;
			}
		}
	}

	if ( $success ) {
		wp_send_json_success( $response );
	} else {
		wp_send_json_error( $response );
	}

	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_create_term', 'mvl_setup_wizard_create_term' );

function mvl_setup_wizard_mock_event() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'You do not have permission to perform this action', 'stm_vehicles_listing' ) );
	}

	$response = array(
		'success' => true,
	);

	wp_send_json( $response );
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_mock_event', 'mvl_setup_wizard_mock_event' );
