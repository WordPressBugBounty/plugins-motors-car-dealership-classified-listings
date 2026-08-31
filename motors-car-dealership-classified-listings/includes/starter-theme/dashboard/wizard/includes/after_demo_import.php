<?php

// Update Elementor header footer settings
add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_trigger_resave_after_demo_import', 10, 4 );

function mvl_motors_trigger_resave_after_demo_import() {
	$has_motors_resave_callback = function_exists( 'motors_resave_elementor_template' ) && false !== has_action( 'save_post', 'motors_resave_elementor_template' );

	$hf_query = new WP_Query(
		array(
			'post_type'      => 'elementor-hf',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		)
	);

	if ( $hf_query->have_posts() ) {
		if ( $has_motors_resave_callback ) {
			remove_action( 'save_post', 'motors_resave_elementor_template' );
		}

		while ( $hf_query->have_posts() ) {
			$hf_query->the_post();
			$hf_id = get_the_ID();

			if ( ! wp_is_post_revision( $hf_id ) ) {
				wp_update_post(
					array(
						'ID'          => $hf_id,
						'post_status' => 'publish',
					)
				);
			}
		}

		if ( $has_motors_resave_callback ) {
			add_action( 'save_post', 'motors_resave_elementor_template' );
		}

		wp_reset_postdata();
	}
}

function mvl_motors_starter_get_demo_file( $demo, $file ) {
	if ( empty( $demo ) ) {
		$demo = sanitize_key( (string) get_option( 'mvl_motors_starter_demo_name', 'free' ) );
	}

	if ( empty( $demo ) ) {
		$demo = 'free';
	}

	return trailingslashit( get_template_directory() ) . 'includes/demo/' . $demo . '/' . $file;
}

function mvl_motors_starter_get_demo_settings( $demo ) {
	$settings_file = mvl_motors_starter_get_demo_file( $demo, 'elementor_settings.php' );

	if ( ! file_exists( $settings_file ) ) {
		return array();
	}

	$settings = include $settings_file;

	return is_array( $settings ) ? $settings : array();
}

function mvl_motors_starter_get_demo_attachment_map() {
	$map = get_option( 'mvl_motors_starter_demo_attachment_map', array() );

	return is_array( $map ) ? $map : array();
}

function mvl_motors_starter_map_imported_id( $old_id, $processed_posts ) {
	if ( empty( $old_id ) ) {
		return 0;
	}

	if ( ! empty( $processed_posts[ $old_id ] ) ) {
		return (int) $processed_posts[ $old_id ];
	}

	$attachment_map = mvl_motors_starter_get_demo_attachment_map();

	return ! empty( $attachment_map[ $old_id ] ) ? (int) $attachment_map[ $old_id ] : (int) $old_id;
}

add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_restore_demo_local_media', 11, 4 );

function mvl_motors_restore_demo_local_media( $processed_posts = array(), $processed_terms = array(), $processed_menu_items = array(), $demo = '' ) {
	$settings = mvl_motors_starter_get_demo_settings( $demo );

	if ( empty( $settings['local_media'] ) || ! is_array( $settings['local_media'] ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachment_map = mvl_motors_starter_get_demo_attachment_map();
	$uploads        = wp_upload_dir();

	if ( ! empty( $uploads['error'] ) ) {
		return;
	}

	foreach ( $settings['local_media'] as $old_id => $media ) {
		$old_id        = (int) $old_id;
		$attachment_id = ! empty( $processed_posts[ $old_id ] ) ? (int) $processed_posts[ $old_id ] : 0;
		$source        = mvl_motors_starter_get_demo_file( $demo, 'media/' . $media['file'] );

		if ( ! file_exists( $source ) ) {
			continue;
		}

		if ( $attachment_id && file_exists( get_attached_file( $attachment_id ) ) ) {
			$attachment_map[ $old_id ] = $attachment_id;
			continue;
		}

		$file_name   = wp_unique_filename( $uploads['path'], basename( $source ) );
		$target_file = trailingslashit( $uploads['path'] ) . $file_name;

		if ( ! copy( $source, $target_file ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
			continue;
		}

		$file_type = wp_check_filetype( $target_file );

		if ( ! $attachment_id ) {
			$attachment_id = wp_insert_attachment(
				array(
					'post_mime_type' => $file_type['type'],
					'post_title'     => ! empty( $media['title'] ) ? sanitize_text_field( $media['title'] ) : sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) ),
					'post_status'    => 'inherit',
				),
				$target_file
			);
		}

		if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
			continue;
		}

		update_attached_file( $attachment_id, $target_file );
		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $target_file ) );

		$attachment_map[ $old_id ] = (int) $attachment_id;

		$old_urls = array();

		if ( ! empty( $media['old_urls'] ) && is_array( $media['old_urls'] ) ) {
			$old_urls = $media['old_urls'];
		} elseif ( ! empty( $media['old_url'] ) ) {
			$old_urls = array( $media['old_url'] );
		}

		foreach ( $old_urls as $old_url ) {
			mvl_motors_replace_demo_media_references( $old_url, wp_get_attachment_url( $attachment_id ), $old_id, $attachment_id );
		}
	}

	update_option( 'mvl_motors_starter_demo_attachment_map', $attachment_map );
}

add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_remap_demo_term_images', 12, 4 );

/**
 * Term meta is imported before attachments, so stm_image still stores XML IDs.
 * Remap those IDs after attachments have been created (they often get new IDs).
 */
function mvl_motors_remap_demo_term_images( $processed_posts = array() ) {
	if ( empty( $processed_posts ) || ! is_array( $processed_posts ) ) {
		$processed_posts = array();
	}

	$attachment_map = mvl_motors_starter_get_demo_attachment_map();
	$taxonomies     = get_taxonomies( array(), 'names' );

	if ( empty( $taxonomies ) ) {
		return;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => array_values( $taxonomies ),
			'hide_empty' => false,
		)
	);

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}

	foreach ( $terms as $term ) {
		$old_id = (int) get_term_meta( $term->term_id, 'stm_image', true );

		if ( $old_id <= 0 ) {
			continue;
		}

		$new_id = mvl_motors_starter_map_imported_id( $old_id, $processed_posts );

		if ( $new_id && 'attachment' !== get_post_type( $new_id ) && ! empty( $attachment_map[ $old_id ] ) ) {
			$new_id = (int) $attachment_map[ $old_id ];
		}

		if ( $new_id && 'attachment' === get_post_type( $new_id ) && (int) $new_id !== $old_id ) {
			update_term_meta( $term->term_id, 'stm_image', $new_id );
		}
	}
}

function mvl_motors_replace_demo_media_references( $old_url, $new_url, $old_id, $new_id ) {
	global $wpdb;

	if ( empty( $old_url ) || empty( $new_url ) ) {
		return;
	}

	$replacements = array(
		$old_url                             => $new_url,
		str_replace( '/', '\/', $old_url )   => str_replace( '/', '\/', $new_url ),
		'"id":' . (int) $old_id              => '"id":' . (int) $new_id,
		'\"id\":' . (int) $old_id            => '\"id\":' . (int) $new_id,
		'"attachment_id":' . (int) $old_id   => '"attachment_id":' . (int) $new_id,
		'\"attachment_id\":' . (int) $old_id => '\"attachment_id\":' . (int) $new_id,
	);

	foreach ( $replacements as $from => $to ) {
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key = '_elementor_data' AND meta_value LIKE %s",
				$from,
				$to,
				'%' . $wpdb->esc_like( $from ) . '%'
			)
		);
	}
}

// Restore Motors Starter Theme skin settings from demo data.
add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_restore_starter_skin_settings', 12, 4 );

function mvl_motors_restore_starter_skin_settings( $processed_posts = array(), $processed_terms = array(), $processed_menu_items = array(), $demo = '' ) {
	if ( empty( $demo ) ) {
		$demo = sanitize_key( (string) get_option( 'mvl_motors_starter_demo_name', 'free' ) );
	}

	if ( empty( $demo ) ) {
		$demo = 'free';
	}

	$settings_file = mvl_motors_starter_get_demo_file( $demo, 'skin_settings.dat' );

	if ( ! file_exists( $settings_file ) ) {
		return;
	}

	$settings = maybe_unserialize( file_get_contents( $settings_file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( empty( $settings ) || ! is_array( $settings ) ) {
		return;
	}

	$id_fields = array(
		'mst_404_page',
		'mst_underconstruction_page_id',
		'mst_body_image',
		'mst_header_logo',
	);

	foreach ( $id_fields as $field ) {
		if ( ! empty( $settings[ $field ] ) ) {
			$settings[ $field ] = mvl_motors_starter_map_imported_id( $settings[ $field ], $processed_posts );
		}
	}

	update_option( 'mst_skin_settings', $settings );

	do_action( 'wpcfto_after_settings_saved', 'mst_skin_settings', $settings );
}

add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_restore_demo_elementor_settings', 13, 4 );

function mvl_motors_restore_demo_elementor_settings( $processed_posts = array(), $processed_terms = array(), $processed_menu_items = array(), $demo = '' ) {
	$settings = mvl_motors_starter_get_demo_settings( $demo );

	if ( empty( $settings ) ) {
		return;
	}

	if ( ! empty( $settings['options'] ) && is_array( $settings['options'] ) ) {
		foreach ( $settings['options'] as $option => $value ) {
			update_option( $option, $value );
		}
	}

	if ( ! empty( $settings['theme_mods'] ) && is_array( $settings['theme_mods'] ) ) {
		foreach ( $settings['theme_mods'] as $theme_mod => $value ) {
			set_theme_mod( $theme_mod, is_numeric( $value ) ? mvl_motors_starter_map_imported_id( $value, $processed_posts ) : $value );
		}
	}

	if ( ! empty( $settings['page_template'] ) ) {
		$page_template = sanitize_text_field( $settings['page_template'] );

		if ( ! empty( $settings['page_template_post_ids'] ) && is_array( $settings['page_template_post_ids'] ) ) {
			foreach ( $settings['page_template_post_ids'] as $old_page_id ) {
				$page_id = mvl_motors_starter_map_imported_id( $old_page_id, $processed_posts );

				if ( $page_id && 'page' === get_post_type( $page_id ) ) {
					update_post_meta( $page_id, '_wp_page_template', $page_template );
				}
			}
		}
	}

	if ( empty( $settings['kit_settings'] ) || ! is_array( $settings['kit_settings'] ) ) {
		return;
	}

	$kit_id = 0;

	if ( ! empty( $settings['active_kit_post_id'] ) ) {
		$kit_id = mvl_motors_starter_map_imported_id( $settings['active_kit_post_id'], $processed_posts );
	}

	if ( ! $kit_id ) {
		$kit_id = (int) get_option( 'elementor_active_kit', 0 );
	}

	if ( ! $kit_id || 'elementor_library' !== get_post_type( $kit_id ) ) {
		$kits = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'orderby'        => 'ID',
				'order'          => 'DESC',
				'meta_key'       => '_elementor_template_type',
				'meta_value'     => 'kit',
			)
		);

		if ( ! empty( $kits[0]->ID ) ) {
			$kit_id = (int) $kits[0]->ID;
		}
	}

	if ( ! $kit_id ) {
		return;
	}

	$kit_meta = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$kit_meta = is_array( $kit_meta ) ? $kit_meta : array();
	$kit_meta = array_merge( $kit_meta, $settings['kit_settings'] );

	update_option( 'elementor_active_kit', $kit_id );
	update_post_meta( $kit_id, '_elementor_page_settings', $kit_meta );

	if ( class_exists( 'Elementor\Core\Breakpoints\Manager' ) ) {
		Elementor\Core\Breakpoints\Manager::compile_stylesheet_templates();
	}

	if ( class_exists( 'Elementor\Plugin' ) ) {
		Elementor\Plugin::instance()->files_manager->clear_cache();
	}
}

add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_regenerate_demo_elementor_css', 30, 4 );

function mvl_motors_regenerate_demo_elementor_css( $processed_posts = array() ) {
	if ( empty( $processed_posts ) || ! is_array( $processed_posts ) ) {
		return;
	}

	if ( class_exists( 'Elementor\Plugin' ) ) {
		Elementor\Plugin::instance()->files_manager->clear_cache();
	}

	foreach ( $processed_posts as $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || ! get_post_meta( $post_id, '_elementor_data', true ) ) {
			continue;
		}

		delete_post_meta( $post_id, '_elementor_element_cache' );
		delete_post_meta( $post_id, '_elementor_css' );

		if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new Elementor\Core\Files\CSS\Post( $post_id );
			$css_file->update();
		}
	}
}

// Restore CCB calculator appearance presets (saved_1..saved_6)
add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_restore_ccb_appearance_presets', 15, 4 );

function mvl_motors_restore_ccb_appearance_presets() {
	$demo = sanitize_key( (string) get_option( 'mvl_motors_starter_demo_name', 'free' ) );

	if ( empty( $demo ) ) {
		$demo = 'free';
	}

	$presets_file = trailingslashit( get_template_directory() ) . 'includes/demo/' . $demo . '/ccb-presets.php';

	if ( ! file_exists( $presets_file ) ) {
		$presets_file = trailingslashit( get_template_directory() ) . 'includes/demo/free/ccb-presets.php';
	}

	if ( ! file_exists( $presets_file ) ) {
		return;
	}

	$presets = include $presets_file;

	if ( empty( $presets ) || ! is_array( $presets ) ) {
		return;
	}

	$ccb_plugin_url = trailingslashit( plugins_url( '/', 'cost-calculator-builder/cost-calculator-builder.php' ) );

	array_walk_recursive(
		$presets,
		function ( &$value ) use ( $ccb_plugin_url ) {
			if ( is_string( $value ) ) {
				$value = str_replace( '{{CCB_PLUGIN_URL}}', $ccb_plugin_url, $value );
			}
		}
	);

	update_option( 'ccb_appearance_presets', $presets );
}

add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_disable_addtoany_page_placement', 16, 4 );

function mvl_motors_disable_addtoany_page_placement( $processed_posts = array(), $processed_terms = array(), $processed_menu_items = array(), $demo = '' ) {
	if ( 'car_dealer_elementor' !== $demo ) {
		return;
	}

	$options = get_option( 'addtoany_options', array() );
	$options = is_array( $options ) ? $options : array();

	$options['display_in_pages'] = '-1';

	update_option( 'addtoany_options', $options );
}

add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_disable_woocommerce_coming_soon', 16, 4 );

function mvl_motors_disable_woocommerce_coming_soon() {
	update_option( 'woocommerce_coming_soon', 'no' );
}

/**
 * Set CCB calculator layout by slug (post_name) or title.
 * Matches calculators from demo and sets general.layout = horizontal-layout.
 *
 * @see CCBSettingsData::settings_data() for default structure.
 */
add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_set_ccb_calc_layout_by_slug', 20, 4 );

function mvl_motors_set_ccb_calc_layout_by_slug( $processed_posts = array(), $processed_terms = array(), $processed_menu_items = array(), $demo = '' ) {
	if ( ! class_exists( 'cBuilder\Classes\CCBSettingsData' ) ) {
		return;
	}

	if ( in_array( $demo, array( 'classified_listing', 'classified_listing_two', 'car_dealer_elementor' ), true ) ) {
		return;
	}

	$calc_slugs_or_titles = array(
		'Import Car Calculator',
		'Insurance Calculator',
		'Leasing Calculator — Advanced',
		'Loan Calculator – Amount',
	);

	$slugs_normalized = array_map( 'sanitize_title', $calc_slugs_or_titles );

	$query = new WP_Query(
		array(
			'post_type'      => 'cost-calc',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
		)
	);

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return;
	}

	while ( $query->have_posts() ) {
		$query->the_post();
		$post   = get_post();
		$calc_id = $post->ID;
		$post_slug = $post->post_name;
		$title_slug = sanitize_title( $post->post_title );

		$match = in_array( $post_slug, $slugs_normalized, true )
		|| in_array( $title_slug, $slugs_normalized, true )
		|| in_array( $post->post_title, $calc_slugs_or_titles, true );

		if ( ! $match ) {
			continue;
		}

		$option   = get_option( 'stm_ccb_form_settings_' . $calc_id, null );
		$settings = ( null !== $option && is_array( $option ) )
			? $option
			: \cBuilder\Classes\CCBSettingsData::settings_data();

		if ( empty( $settings['general'] ) ) {
			$settings['general'] = array();
		}
		$settings['general']['layout'] = 'horizontal-layout';

		update_option( 'stm_ccb_form_settings_' . $calc_id, $settings );
	}

	wp_reset_postdata();
}

//Update menu location
add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_starter_update_menu_location', 10, 4 );

function mvl_motors_starter_update_menu_location() {
	$locations = get_theme_mod( 'nav_menu_locations' );
	$menus     = wp_get_nav_menus();

	if ( ! empty( $menus ) ) {
		foreach ( $menus as $menu ) {
			$menu_names = array(
				'Motors Skins Main Menu',
			);

			if ( is_object( $menu ) && in_array( $menu->name, $menu_names, true ) ) {
				$locations['motors-starter-theme-main-menu'] = $menu->term_id;
			}
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}
