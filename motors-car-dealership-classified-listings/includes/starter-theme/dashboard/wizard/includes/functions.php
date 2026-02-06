<?php
use MotorsVehiclesListing\Plugin\MVL_Const;

//Required plugins
add_filter(
	'mvl_motors_starter_theme_plugins',
	function () {
		function get_plugin_status( $plugin_slug ) {

			switch ( $plugin_slug ) {
				case 'motors-car-dealership-classified-listings':
					$plugin_slug = $plugin_slug . '/stm_vehicles_listing.php';
					break;
				case 'contact-form-7':
					$plugin_slug = $plugin_slug . '/wp-contact-form-7.php';
					break;
				default:
					$plugin_slug = $plugin_slug . '/' . $plugin_slug . '.php';
					break;
			}

			$plugin_file = WP_PLUGIN_DIR . '/' . $plugin_slug;

			if ( file_exists( $plugin_file ) ) {
				if ( is_plugin_active( $plugin_slug ) ) {
					return 'Activated';
				} else {
					return 'Installed but not activated';
				}
			}

			return 'Not installed';
		}

		$plugins = array(
			array(
				'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/motors-logo.png',
				'title'       => 'Motors – Car Dealer, Classifieds & Listing',
				'slug'        => 'motors-car-dealership-classified-listings',
				'description' => get_plugin_status( 'motors-car-dealership-classified-listings' ),
			),
			array(
				'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/elementor.png',
				'title'       => 'Elementor',
				'slug'        => 'elementor',
				'description' => get_plugin_status( 'elementor' ),
			),
			array(
				'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/elementor-hfe.png',
				'title'       => 'Elementor Header & Footer Builder',
				'slug'        => 'header-footer-elementor',
				'description' => get_plugin_status( 'header-footer-elementor' ),
			),
			array(
				'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/cf7.png',
				'title'       => 'Contact Form 7',
				'slug'        => 'contact-form-7',
				'description' => get_plugin_status( 'contact-form-7' ),
			),
		);

		return $plugins;
	}
);

//Loading templates using ajax
add_action( 'wp_ajax_mvl_motors_starter_demo_options', 'mvl_motors_starter_demo_options' );

function mvl_motors_starter_demo_options() {
	check_ajax_referer( 'mvl_motors_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to export users.', 'motors-starter-theme' ) );
	}

	$demo = sanitize_text_field( wp_unslash( $_POST['demo'] ?? '' ) );

	if ( ! empty( $demo ) ) {
		update_option( 'mvl_motors_starter_demo_name', $demo );
		update_option( MotorsVehiclesListing\Plugin\MVL_Const::ACTIVE_SKIN_OPT_NAME, $demo );
	}

	update_option( 'mst-starter-theme-builder', 'elementor' );
	mvl_ajax_install_starter_theme();
	wp_die();
}

//Loading templates using ajax
add_action( 'wp_ajax_mvl_motors_starter_template', 'mvl_motors_starter_template' );
add_action( 'wp_ajax_nopriv_mvl_motors_starter_template', 'mvl_motors_starter_template' );

function mvl_motors_starter_template() {
	check_ajax_referer( 'mvl_motors_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to export users.', 'motors-starter-theme' ) );
	}

	ob_start();
	require_once STM_LISTINGS_PATH . '/includes/starter-theme/dashboard/wizard/templates/' . sanitize_text_field( $_POST['template'] ) . '.php';
	$content = ob_get_clean();

	$allowed_html = wp_kses_allowed_html( 'post' );

	echo wp_kses( $content, array_merge( $allowed_html, array( 'lottie-player' => array( 'src' => 1, 'background' => 1, 'speed' => 1, 'style' => 1, 'autoplay' => 1 ) ) ) );//phpcs:ignore
	wp_die();
}

// Install and activate plugins
add_action( 'wp_ajax_mvl_motors_starter_plugins_install', 'mvl_motors_starter_plugins_install' );
add_action( 'wp_ajax_nopriv_mvl_motors_starter_plugins_install', 'mvl_motors_starter_plugins_install' );

function mvl_motors_starter_plugins_install() {
	check_ajax_referer( 'mvl_motors_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_die( esc_html__( 'You do not have permission to install plugins.', 'motors-starter-theme' ) );
	}

	$plugin_slug = isset( $_POST['plugin_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_slug'] ) ) : '';

	if ( empty( $plugin_slug ) ) {
		wp_send_json_error( esc_html__( 'No plugin specified for installation.', 'motors-starter-theme' ) );
	}

	$plugin_file = '';

	switch ( $plugin_slug ) {
		case 'motors-car-dealership-classified-listings':
			$plugin_file = $plugin_slug . '/stm_vehicles_listing.php';
			break;
		case 'contact-form-7':
			$plugin_file = $plugin_slug . '/wp-contact-form-7.php';
			break;
		default:
			$plugin_file = $plugin_slug . '/' . $plugin_slug . '.php';
			break;
	}

	if ( is_plugin_active( $plugin_file ) ) {
		wp_send_json_success( esc_html__( 'Plugin is already active.', 'motors-starter-theme' ) );
	}

	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
		$result = mvl_install_plugin( $plugin_slug );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}
	}
	$activated = activate_plugin( $plugin_file, '', false, true );

	if ( is_wp_error( $activated ) ) {
		wp_send_json_error( $activated->get_error_message() );
	}

	wp_send_json_success( esc_html__( 'Plugin installed and activated successfully.', 'motors-starter-theme' ) );
}

function mvl_install_plugin( $slug ) {
	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	$api = plugins_api(
		'plugin_information',
		array(
			'slug'   => $slug,
			'fields' => array(
				'sections' => false,
			),
		)
	);

	if ( is_wp_error( $api ) ) {
		return $api;
	}

	$upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );

	return $upgrader->install( $api->download_link );
}

// Install and activate demos
add_action( 'wp_ajax_mvl_motors_starter_demo_install', 'mvl_motors_starter_demo_install' );
add_action( 'wp_ajax_nopriv_mvl_motors_starter_demo_install', 'mvl_motors_starter_demo_install' );

function mvl_motors_starter_demo_install() {
	check_ajax_referer( 'mvl_motors_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to install demo.', 'motors-starter-theme' ) );
	}

	$builder = get_option( 'mst-starter-theme-builder' );
	$demo    = get_option( 'mvl_motors_starter_demo_name' );
	$type    = sanitize_text_field( wp_unslash( $_POST['type'] ?? '' ) );

	if ( ! $demo || ! $type || ! $builder ) {
		wp_send_json_error( __( 'Required data is missing.', 'motors-starter-theme' ) );
	}

	switch ( $type ) {
		case 'demo_taxonomy':
			$response = apply_filters( 'motors_get_demo_data', 'listing_categories.json' );

			if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
				$response = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( ! is_null( $response ) ) {
					update_option( 'stm_vehicle_listing_options', $response );
					wp_send_json_success( __( 'Demo taxonomies imported successfully.', 'motors-starter-theme' ) );
					break;
				}
			}

			wp_send_json_error( __( 'Failed to import taxonomies.', 'motors-starter-theme' ) );
			break;
		case 'demo_content':
			$response = apply_filters( 'motors_get_demo_data', 'demo.xml' );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( __( 'Failed to download demo content.', 'motors-starter-theme' ) );
				break;
			}

			$xml_content = wp_remote_retrieve_body( $response );

			if ( empty( $xml_content ) ) {
				wp_send_json_error( __( 'Demo content is empty or unavailable.', 'motors-starter-theme' ) );
				break;
			}

			$upload_dir     = wp_upload_dir();
			$temp_file_path = $upload_dir['path'] . '/' . $demo . '-demo-content.xml';

			file_put_contents( $temp_file_path, $xml_content );//phpcs:ignore

			if ( ! file_exists( $temp_file_path ) ) {
				wp_send_json_error( __( 'Failed to save demo content locally.', 'motors-starter-theme' ) );
				break;
			}

			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true );
			}

			require_once STM_LISTINGS_PATH . '/includes/starter-theme/dashboard/wizard/includes/wordpress-importer/class-stm-wp-import.php';

			$wp_import                    = new STM_WP_Import();
			$wp_import->theme             = 'Motors Skins';
			$wp_import->layout            = $demo;
			$wp_import->builder           = $builder;
			$wp_import->fetch_attachments = true;

			ob_start();
			$wp_import->import( $temp_file_path );
			ob_end_clean();

			do_action(
				'mvl_motors_starter_after_demo_import',
				$wp_import->processed_posts,
				$wp_import->processed_terms,
				$wp_import->processed_menu_items,
			);

			unlink( $temp_file_path );

			wp_send_json_success( __( 'Demo content imported successfully.', 'motors-starter-theme' ) );

			break;

		case 'theme_settings':
			$response = apply_filters( 'motors_get_demo_data', 'customizer.dat' );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				wp_send_json_error( __( 'Customizer settings file not found or failed to retrieve.', 'motors-starter-theme' ) );
				break;
			}

			$file_content    = wp_remote_retrieve_body( $response );
			$customizer_data = maybe_unserialize( $file_content );

			if ( is_array( $customizer_data ) && ! empty( $customizer_data ) ) {
				foreach ( $customizer_data as $mod => $value ) {
					if ( 'mods' === $mod ) {
						if ( isset( $value['mst_body_image'] ) ) {
							$value['mst_body_image'] = str_replace( 'motors-plugin.stylemixthemes.com', wp_parse_url( home_url(), PHP_URL_HOST ), $value['mst_body_image'] );
						}
						foreach ( $value as $k => $v ) {
							set_theme_mod( $k, $v );
						}
					}
				}

				$homepage_query = new WP_Query(
					array(
						'post_type'   => 'page',
						'title'       => 'Home',
						'post_status' => 'publish',
						'numberposts' => 1,
					)
				);

				$homepage = ! empty( $homepage_query->posts ) ? $homepage_query->posts[0] : null;

				if ( $homepage ) {
					update_option( 'page_on_front', $homepage->ID );
					update_option( 'show_on_front', 'page' );
				}

				wp_send_json_success( __( 'Theme settings imported successfully.', 'motors-starter-theme' ) );

			} else {
				wp_send_json_error( __( 'Failed to decode Customizer data.', 'motors-starter-theme' ) );
			}

			break;

		case 'mst_options':
			$response = apply_filters( 'motors_get_demo_data', 'mvl_settings.json' );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				wp_send_json_error( __( 'Motors options JSON file not found or failed to retrieve.', 'motors-starter-theme' ) );
				break;
			}

			$current_settings = get_option( 'motors_vehicles_listing_plugin_settings', array() );

			if ( ! is_array( $current_settings ) ) {
				$current_settings = array();
			}

			$mst_settings = array_merge( $current_settings, json_decode( wp_remote_retrieve_body( $response ), true ) );

			update_option( 'motors_vehicles_listing_plugin_settings', $mst_settings );

			if ( class_exists( '\\MotorsVehiclesListing\\Stilization\\Colors' ) ) {
				\MotorsVehiclesListing\Plugin\Settings::update_one( 'replace_elementor_colors', 1 );
				add_filter( 'motors_vl_get_nuxy_mod', 'mvl_setup_wizard_replace_elementor_colors_filter', 100, 2 );
				\MotorsVehiclesListing\Stilization\Colors::import_to_elementor();
				remove_filter( 'motors_vl_get_nuxy_mod', 'mvl_setup_wizard_replace_elementor_colors_filter', 100, 2 );
			}

			if ( is_mvl_pro() ) {
				$search_results_settings                   = get_option( MVL_Const::SEARCH_RESULTS_OPT_NAME, array() );
				$search_results_settings['grid_card_skin'] = 'skin_3';
				$search_results_settings['list_card_skin'] = 'skin_3';

				update_option( MVL_Const::SEARCH_RESULTS_OPT_NAME, $search_results_settings );
			}

			update_option( '_motors_widgets_default_settings_updated', array() );

			wp_send_json_success( __( 'Motors options imported and settings updated successfully.', 'motors-starter-theme' ) );

			break;
		case 'generate_pages':
			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$options = get_option( 'motors_vehicles_listing_plugin_settings', array() );

			$target_page_title  = 'Inventory';
			$add_car_page_title = 'Add Car';
			$compare_page_title = 'Compare';

			$found_page   = mvl_get_page_id_by_title( $target_page_title );
			$add_car_page = mvl_get_page_id_by_title( $add_car_page_title );
			$compare_page = mvl_get_page_id_by_title( $compare_page_title );

			if ( ! empty( $found_page ) ) {
				$options['listing_archive'] = $found_page;
				update_option( 'motors_vehicles_listing_plugin_settings', $options );
			}

			if ( ! empty( $add_car_page ) ) {
				$options['user_add_car_page'] = $add_car_page;
				update_option( 'motors_vehicles_listing_plugin_settings', $options );
			}

			if ( ! empty( $compare_page ) ) {
				$options['compare_page'] = $compare_page;
				update_option( 'motors_vehicles_listing_plugin_settings', $options );
			}

			$id       = 'motors_vehicles_listing_plugin_settings';
			$settings = get_option( $id, array() );

			$response = array(
				'reload'  => false,
				'updated' => false,
			);

			$response['reload'] = apply_filters( 'wpcfto_reload_after_save', $id, $settings );

			do_action( 'wpcfto_settings_saved', $id, $settings );

			$response['updated'] = update_option( $id, $settings );
			do_action( 'wpcfto_after_settings_saved', $id, $settings );

			do_action( 'mvl_reset_elementor_cache' );

			wp_send_json_success( __( 'Motors Skins Pages imported.', 'motors-starter-theme' ) );

			break;
		default:
			wp_send_json_error( __( 'Unknown import type.', 'motors-starter-theme' ) );
	}

	wp_send_json_error( false );
}

// Install and activate child theme
add_action( 'wp_ajax_mvl_motors_starter_child_theme_install', 'mvl_motors_starter_child_theme_install' );
add_action( 'wp_ajax_nopriv_mvl_motors_starter_child_theme_install', 'mvl_motors_starter_child_theme_install' );

function mvl_motors_starter_child_theme_install() {
	check_ajax_referer( 'mvl_motors_starter_wizard_nonce', 'nonce' );

	$theme_url  = 'https://motors-plugin.stylemixthemes.com/starter-theme-demo/motors-starter-theme-child.zip';
	$theme_slug = 'motors-starter-theme-child';
	$theme_dir  = get_theme_root() . '/' . $theme_slug;

	if ( is_dir( $theme_dir ) ) {
		switch_theme( $theme_slug );
		wp_send_json_success(
			array(
				'message' => __( 'Child theme already exists and has been activated.', 'motors-starter-theme' ),
			)
		);

		return;
	}

	$temp_file = download_url( $theme_url );

	if ( is_wp_error( $temp_file ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Error downloading file: ', 'motors-starter-theme' ) . $temp_file->get_error_message(),
			)
		);

		return;
	}

	$zip_path = get_theme_root() . '/motors-starter-theme-child.zip';

	if ( ! rename( $temp_file, $zip_path ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Error moving archive to the themes folder.', 'motors-starter-theme' ),
			)
		);

		return;
	}

	$unzip_result = unzip_file( $zip_path, get_theme_root() );

	if ( is_wp_error( $unzip_result ) ) {
		unlink( $zip_path );
		wp_send_json_error(
			array(
				'message' => __( 'Error while unzipping: ', 'motors-starter-theme' ) . $unzip_result->get_error_message(),
			)
		);

		return;
	}

	unlink( $zip_path );
	switch_theme( $theme_slug );

	wp_send_json_success(
		array(
			'message' => __( 'Child theme already exists and has been activated.', 'motors-starter-theme' ),
		)
	);
}

// Reset demo
add_action( 'wp_ajax_mvl_motors_starter_template_reset', 'mvl_motors_starter_template_reset' );
add_action( 'wp_ajax_nopriv_mvl_motors_starter_template_reset', 'mvl_motors_starter_template_reset' );

function mvl_motors_starter_template_reset() {
	check_ajax_referer( 'mvl_motors_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to export users.', 'motors-starter-theme' ) );
	}

	// Remove all posts marked with 'mvl_motors_starter_demo' meta
	$all_posts = get_posts(
		array(
			'numberposts' => - 1,
			'post_type'   => 'any',
			'post_status' => 'any',
			'meta_key'    => 'mvl_motors_starter_demo',
		)
	);

	$hf_posts = get_posts(
		array(
			'numberposts' => - 1,
			'post_type'   => 'elementor-hf',
			'post_status' => 'any',
		)
	);

	foreach ( $hf_posts as $post ) {
		delete_post_meta( $post->ID, '_elementor_data' );
	}

	foreach ( $all_posts as $post ) {
		delete_post_meta( $post->ID, '_elementor_data' );
		wp_delete_post( $post->ID, true );
	}

	// Remove all terms marked with 'mvl_motors_starter_demo' meta
	$taxonomies = get_taxonomies( array(), 'objects' );
	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy->name,
				'hide_empty' => false,
			)
		);

		foreach ( $terms as $term ) {
			$meta_value = get_term_meta( $term->term_id, 'mvl_motors_starter_demo', true );
			if ( $meta_value ) {
				wp_delete_term( $term->term_id, $taxonomy->name );
			}
		}

		unregister_taxonomy( $taxonomy->name );
	}

	// Remove all taxonomy_option marked with 'mvl_motors_starter_demo' meta
	delete_option( 'stm_vehicle_listing_options' );

	// Remove all menu items marked with 'mvl_motors_starter_demo' meta
	$menu_items = get_posts(
		array(
			'numberposts' => - 1,
			'post_type'   => 'nav_menu_item',
			'post_status' => 'any',
			'meta_key'    => 'mvl_motors_starter_demo',
		)
	);

	foreach ( $menu_items as $menu_item ) {
		wp_delete_post( $menu_item->ID, true );
	}

	// Remove options and widgets
	update_option( 'show_on_front', 'posts' );

	wp_send_json_success( 'Database was reset successfully!' );
}

function mvlGetAnnualPriceFromAPI() {
	$response = wp_remote_get( 'https://stylemixthemes.com/api/freemius/motors-templates.json' );
	if ( is_wp_error( $response ) ) {
		return 'Error: Unable to fetch data from API.';
	}

	$jsonContent = wp_remote_retrieve_body( $response );
	if ( empty( $jsonContent ) ) {
		return 'Error: Empty response from API.';
	}

	$data = json_decode( $jsonContent, true );
	if ( null === $data ) {
		return 'Error: Unable to decode JSON data.';
	}

	$defaultPlanId = $data['default_plan_id'] ?? null;
	if ( $defaultPlanId && isset( $data['plans'][ $defaultPlanId ] ) ) {
		$pricing       = $data['plans'][ $defaultPlanId ]['pricing'][0];
		$annualPrice   = $pricing['annual_price'] ?? '';
		$lifetimePrice = $pricing['lifetime_price'] ?? '';
		$sale          = mvl_check_data_sale();

		if ( $sale ) {
			return array(
				'annual_price'   => ' $<s>' . esc_html( $annualPrice ) . '</s> $' . esc_html( number_format( $annualPrice * 0.50, 0, '.', '' ) ),
				'lifetime_price' => ' $<s>' . esc_html( $lifetimePrice ) . '</s> $' . esc_html( number_format( $lifetimePrice * 0.50, 0, '.', '' ) ),
			);
		} else {
			return array(
				'annual_price'   => ' $' . $annualPrice,
				'lifetime_price' => ' $' . $lifetimePrice,
			);
		}
	}

	return 'Plan or pricing information not found.';
}

function mvl_check_data_sale() {
	$current_date = new DateTime();
	$start_date   = new DateTime( '2024-11-14' );
	$end_date     = new DateTime( '2024-12-04' );

	return $current_date >= $start_date && $current_date <= $end_date;
}
