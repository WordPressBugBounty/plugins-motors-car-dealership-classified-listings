<?php
function mvl_setup_wizard_load_step() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

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

		$settings_names = apply_filters( 'mvl_settings_option_names', array() );

		foreach ( $settings_names as $settings_name ) {
			$options = get_option( $settings_name, array() );

			foreach ( $settings_to_update as $opt_name => $value ) {
				if ( isset( $options[ $opt_name ] ) ) {
					if ( in_array( strtolower( trim( $value ) ), array( 'true', 'false' ) ) ) {
						$options[ $opt_name ] = ( 'true' === $value );
					} else {
						$options[ $opt_name ] = $value;
					}
				}
			}

			update_option( $settings_name, $options );
		}

		$response['plugin_settings_updated'] = true;
	}

	ob_start();

	do_action( 'mvl_setup_wizard_load_step', sanitize_text_field( $_POST['step'] ) );

	$response['output'] = ob_get_clean();

	wp_send_json( $response );
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_load_step', 'mvl_setup_wizard_load_step' );
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_load_step', 'mvl_setup_wizard_load_step' );

function mvl_setup_wizard_install_starter_theme() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

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
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_install_starter_theme', 'mvl_setup_wizard_install_starter_theme' );

function mvl_setup_wizard_install_plugin() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	$plugin_slug = isset( $_POST['plugin'] ) ? sanitize_text_field( $_POST['plugin'] ) : '';

	if ( empty( $plugin_slug ) ) {
		wp_send_json_error( 'Plugin info not provided' );
	}

	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' ) ) {

		$plugin_api_url = 'https://api.wordpress.org/plugins/info/1.0/' . $plugin_slug . '.json';

		$response = wp_remote_get( $plugin_api_url );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( 'Error requesting plugin' );
		}

		$plugin_data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $plugin_data['download_link'] ) ) {
			wp_send_json_error( 'Error downloading plugin' );
		}

		$plugin_url = esc_url_raw( $plugin_data['download_link'] );

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$skin      = new Automatic_Upgrader_Skin();
		$upgrader  = new Plugin_Upgrader( $skin );
		$installed = $upgrader->install( $plugin_url );

		if ( is_wp_error( $installed ) ) {
			wp_send_json_error( 'Error installing plugin' );
		}
	}

	$activated = activate_plugin( WP_PLUGIN_DIR . '/' . apply_filters( 'mvl_setup_wizard_plugin_main_file', $plugin_slug ), false, false, true );

	if ( ! is_wp_error( $activated ) ) {
		wp_send_json_success( 'Plugin succesfully activated' );
	} else {
		wp_send_json_error( 'Error activating plugin' );
	}
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_install_plugin', 'mvl_setup_wizard_install_plugin' );
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_install_plugin', 'mvl_setup_wizard_install_plugin' );

function mvl_setup_wizard_starter_import_fields() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	$response = wp_remote_get( apply_filters( 'mvl_import_data_url', 'listing_categories.json' ) );

	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_null( $response ) ) {
			update_option( 'stm_vehicle_listing_options', $response );
			wp_send_json_success( $response );
		}
	}

	wp_send_json_error();
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_starter_import_fields', 'mvl_setup_wizard_starter_import_fields' );
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_starter_import_fields', 'mvl_setup_wizard_starter_import_fields' );

function mvl_setup_wizard_starter_import_settings() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	$response = wp_remote_get( apply_filters( 'mvl_import_data_url', 'mvl_settings.json' ) );

	if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_null( $response ) ) {

			update_option( 'motors_vehicles_listing_plugin_settings', $response );

			$home = get_page_by_path( 'home' );
			if ( $home ) {
				update_option( 'page_on_front', $home->ID );
				update_option( 'show_on_front', 'page' );
			}

			if ( ! is_plugin_active( 'elementor/elementor.php' ) ) {
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
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_starter_import_settings', 'mvl_setup_wizard_starter_import_settings' );

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

	$importer = mvl_setup_wizard_get_importer();

	if ( ! is_object( $importer ) ) {
		wp_send_json_error( 'Error creating Importer object' );
	}

	$importer->fetch_attachments = true;

	$import_file = ( defined( 'MOTORS_STARTER_THEME_TEMPLATE_DIR' ) ) ? MOTORS_STARTER_THEME_TEMPLATE_DIR . '/includes/demo/elementor/starter_import.xml' : STM_LISTINGS_PATH . '/dummy_content/demo-listings.xml';

	ob_start();

	$importer->import( $import_file );

	ob_end_clean();

	if ( ! empty( $importer->processed_posts ) ) {
		wp_send_json_success();
	} else {
		wp_send_json_error();
	}
}
add_action( 'wp_ajax_mvl_setup_wizard_starter_import_content', 'mvl_setup_wizard_starter_import_content' );
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_starter_import_content', 'mvl_setup_wizard_starter_import_content' );

function mvl_setup_wizard_generate_pages() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

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
		} else {
			error_log( 'Failed to import page ' . $page['title'] );
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

				$sl_options = get_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_TEMPLATE_OPT_NAME, array() );
				if ( ! empty( $sl_options['single_listing_template'] ) ) {
					$sl_options['single_listing_template'] = $id;
				}
				update_option( \MotorsVehiclesListing\Plugin\MVL_Const::LISTING_TEMPLATE_OPT_NAME, $sl_options );
			} else {
				error_log( 'Failed to import template ' . $template['title'] );
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
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_generate_pages', 'mvl_setup_wizard_generate_pages' );

function mvl_setup_wizard_mock_event() {
	check_ajax_referer( 'stm_mvl_setup_wizard_nonce', 'security' );

	$response = array(
		'success' => true,
	);

	wp_send_json( $response );
	exit;
}
add_action( 'wp_ajax_mvl_setup_wizard_mock_event', 'mvl_setup_wizard_mock_event' );
add_action( 'wp_ajax_nopriv_mvl_setup_wizard_mock_event', 'mvl_setup_wizard_mock_event' );
