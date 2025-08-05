<?php

namespace MotorsVehiclesListing\Features\Elementor\Nuxy;

use \Elementor\Plugin;

class TemplateManager {

	private $post_type    = 'listing_template';
	private $plural       = 'Listing Templates';
	private $single       = 'Listing Template';
	private $setting_name = 'single_listing_template';

	private $templates_sync_data = array(
		'modern'           => array(
			'title' => 'Modern',
		),
		'mosaic-gallery'   => array(
			'title' => 'Mosaic Gallery',
		),
		'carousel-gallery' => array(
			'title' => 'Carousel Gallery',
		),
	);

	private $data_for_select;

	public static $selected_template_id;

	public function __construct() {
		$this->motors_get_templates_list();

		add_action( 'init', array( $this, 'motors_register_post_type' ) );

		add_action( 'wp_ajax_motors_wpcfto_create_template', array( $this, 'motors_create_template' ) );
		add_action( 'wp_ajax_motors_wpcfto_delete_template', array( $this, 'motors_delete_template' ) );

		add_filter( 'single_template', array( $this, 'motors_override_single_template' ) );
		add_filter( 'me_single_listing_template_settings_conf', array( $this, 'motors_car_settings_conf' ) );
		add_filter( 'wpcfto_field_mew-repeater-radio', array( $this, 'motors_register_wpcfto_repeater_radio' ) );
		if ( apply_filters( 'is_mvl_pro', false ) ) {
			add_action( 'wp_loaded', array( $this, 'motors_single_pro_template_library_sync' ) );
		} else {
			add_filter( 'wp_loaded', array( $this, 'motors_single_template_library_sync' ) );
		}
	}

	public function motors_register_post_type() {

		self::$selected_template_id = apply_filters( 'motors_vl_get_nuxy_mod', null, $this->setting_name );

		if ( null === self::$selected_template_id ) {
			self::$selected_template_id = array_key_first( $this->data_for_select );
		}

		// @codingStandardsIgnoreStart
		$labels = array(
			'name'               => __( $this->plural, 'stm_vehicles_listing' ),
			'singular_name'      => __( $this->single, 'stm_vehicles_listing' ),
			'add_new'            => __( 'Add New', 'stm_vehicles_listing' ),
			'add_new_item'       => __( 'Add New ' . $this->single, 'stm_vehicles_listing' ),
			'edit_item'          => __( 'Edit ' . $this->single, 'stm_vehicles_listing' ),
			'new_item'           => __( 'New ' . $this->single, 'stm_vehicles_listing' ),
			'all_items'          => __( 'All ' . $this->plural, 'stm_vehicles_listing' ),
			'view_item'          => __( 'View ' . $this->single, 'stm_vehicles_listing' ),
			'search_items'       => __( 'Search ' . $this->plural, 'stm_vehicles_listing' ),
			'not_found'          => __( 'No ' . $this->plural . ' found', 'stm_vehicles_listing' ),
			'not_found_in_trash' => __( 'No ' . $this->plural . '  found in Trash', 'stm_vehicles_listing' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( $this->plural, 'stm_vehicles_listing' ),
		);
		// @codingStandardsIgnoreEnd

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_in_nav_menus'  => false,
			'query_var'          => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => null,
			'supports'           => array( 'title', 'editor' ),
		);

		register_post_type( $this->post_type, $args );
	}

	public function motors_override_single_template( $single_template ) {
		global $post;

		$file = STM_LISTINGS_PATH . '/templates/single-' . $post->post_type . '.php';

		if ( file_exists( $file ) ) {
			$single_template = $file;
		}

		return $single_template;
	}

	public function motors_car_settings_conf( $conf ) {
		$is_mvl_pro             = is_mvl_pro();
		$listing_template_type  = 'default';
		$listing_template_types = array(
			'default' => esc_html__( 'Default', 'stm_vehicles_listing' ),
			'motors'  => esc_html__( 'Built-in', 'stm_vehicles_listing' ),
		);

		if ( class_exists( 'Elementor\Plugin' ) ) {
			$listing_template_type               = 'elementor';
			$listing_template_types['elementor'] = esc_html__( 'Elementor', 'stm_vehicles_listing' );
		}

		$conf_tm['listing_template_type'] = array(
			'label'       => esc_html__( 'Template Source', 'stm_vehicles_listing' ),
			'type'        => 'select',
			'description' => __( 'Choose how your listing posts will look on the site. Use ready-made built-in templates or design your own with Elementor.', 'stm_vehicles_listing' ),
			'options'     => $listing_template_types,
			'value'       => $listing_template_type,
		);

		$conf_tm['listing_motors_template'] = array(
			'type'        => 'nuxy-radio',
			'label'       => esc_html__( 'Template Layout', 'stm_vehicles_listing' ),
			'description' => __( 'Select the layout you want to use for your listing posts.', 'stm_vehicles_listing' ),
			'value'       => 'classic',
			'options'     => array(
				array(
					'value'         => 'classic',
					'alt'           => 'Classic',
					'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-templates/classic.png',
					'disabled'      => ! $is_mvl_pro,
					'preview_url'   => 'https://motors-plugin.stylemixthemes.com/listings/ferrari-f40/',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					'pricing_url'   => admin_url( 'admin.php?page=mvl-go-pro' ),
					'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
					'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
				),
				array(
					'value'         => 'modern',
					'alt'           => 'Modern',
					'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-templates/modern.png',
					'disabled'      => ! $is_mvl_pro,
					'preview_url'   => 'https://motors-plugin.stylemixthemes.com/listings/mercedes-benz-e63/?mvl=pro',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					'pricing_url'   => admin_url( 'admin.php?page=mvl-go-pro' ),
					'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
					'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
				),
				array(
					'value'         => 'mosaic',
					'alt'           => 'Mosaic Gallery',
					'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-templates/mosaic.png',
					'disabled'      => ! $is_mvl_pro,
					'preview_url'   => 'https://motors-plugin.stylemixthemes.com/listings/nissan-gtr-r34/?mvl=pro',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					'pricing_url'   => admin_url( 'admin.php?page=mvl-go-pro' ),
					'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
					'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
				),
				array(
					'value'         => 'carousel',
					'alt'           => 'Carousel Gallery',
					'img'           => STM_LISTINGS_URL . '/assets/images/pro/listing-templates/carousel.png',
					'disabled'      => ! $is_mvl_pro,
					'preview_url'   => 'https://motors-plugin.stylemixthemes.com/listings/bmw-m5-f90/?mvl=pro',
					'preview_label' => esc_html__( 'Preview', 'stm_vehicles_listing' ),
					'pricing_url'   => admin_url( 'admin.php?page=mvl-go-pro' ),
					'pricing_label' => esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ),
					'lock_icon'     => esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ),
				),
			),
		);

		$conf_tm['listing_motors_template_container_width'] = array(
			'label'       => esc_html__( 'Container Width', 'stm_vehicles_listing' ),
			'type'        => 'number',
			'description' => __( 'Set how wide the template should be. Enter a value in pixels (ex. 1200).', 'stm_vehicles_listing' ),
			'value'       => 1160,
			'min'         => 900,
			'max'         => 1450,
			'step'        => 1,
			'unit'        => 'px',
		);

		if ( isset( $conf_tm['listing_template_type'] ) ) {
			$conf_tm['listing_motors_template']['dependency']                 = array(
				'key'   => 'listing_template_type',
				'value' => 'motors',
			);
			$conf_tm['listing_motors_template_container_width']['dependency'] = array(
				'key'   => 'listing_template_type',
				'value' => 'motors',
			);
		}

		if ( class_exists( 'Elementor\Plugin' ) ) {
			$conf_tm[ $this->setting_name ] = array(
				'label'       => esc_html__( 'Template Layout', 'stm_vehicles_listing' ),
				'description' => __( 'Select the template you want to use for your listing posts.', 'stm_vehicles_listing' ),
				'type'        => 'mew-repeater-radio',
				'fields'      => $this->data_for_select,
				'value'       => array_key_first( $this->data_for_select ),
			);
		}

		if ( isset( $conf_tm[ $this->setting_name ] ) ) {
			$conf_tm[ $this->setting_name ]['dependency'] = array(
				'key'   => 'listing_template_type',
				'value' => 'elementor',
			);
		}

		return array_merge( $conf, $conf_tm );
	}

	public function motors_get_templates_list() {
		$args = array(
			'post_type'              => $this->post_type,
			'post_status'            => 'publish',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'posts_per_page'         => 99,
		);

		$posts = new \WP_Query( $args );

		$for_select = array();

		foreach ( $posts->posts as $post ) {
			$for_select[] = array(
				'post_id'   => $post->ID,
				'slug'      => $post->post_name,
				'title'     => $post->post_title,
				'edit_link' => get_admin_url( null, 'post.php?post=' . $post->ID . '&action=elementor' ),
				'view_link' => get_the_permalink( $post->ID ),
			);
		}

		$this->data_for_select = $for_select;

		wp_reset_postdata();
	}

	public function motors_register_wpcfto_repeater_radio() {
		return STM_LISTINGS_PATH . '/includes/nuxy/mew-repeater-radio.php';
	}

	public function motors_create_template() {
		check_ajax_referer( 'motors_create_template', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to create listing template', 'stm_vehicles_listing' ) ) );
		}

		$found_posts = new \WP_Query(
			array(
				'post_type'   => $this->post_type,
				'post_status' => 'publish',
			)
		);

		$post_title = 'Listing Template #' . ( $found_posts->found_posts + 1 );

		$post_data = array(
			'post_title'  => $post_title,
			'post_status' => 'publish',
			'post_type'   => $this->post_type,
		);

		wp_reset_postdata();

		$id = wp_insert_post( $post_data );

		if ( $id ) {
			update_post_meta( $id, '_elementor_edit_mode', 'builder' );

			$new_template = array(
				'post_id'   => $id,
				'title'     => get_the_title( $id ),
				'edit_link' => get_admin_url( null, 'post.php?post=' . $id . '&action=elementor' ),
				'view_link' => get_the_permalink( $id ),
			);

			wp_send_json(
				array(
					'msg'     => $new_template,
					'post_id' => $id,
					'code'    => 200,
				)
			);
		}
	}

	public function motors_delete_template() {
		check_ajax_referer( 'motors_delete_template', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You do not have permission to delete listing template', 'stm_vehicles_listing' ) ) );
		}

		$data = array(
			'error' => true,
		);

		if ( ! empty( $_REQUEST['post_id'] ) && wp_delete_post( sanitize_text_field( $_REQUEST['post_id'] ) ) ) {
			$data['error']  = '';
			$data['status'] = 200;
		}

		wp_send_json( $data );
	}

	/**
	 * TEMPLATE LIBRIARY SYNC will be moved to PRO VERSION
	*/

	public function motors_single_template_library_sync() {

		if ( ! is_admin() || wp_doing_ajax() || ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}

		$layouts = array(
			'listing_one_elementor',
			'listing_two_elementor',
			'listing_three_elementor',
			'listing_four_elementor',
			'listing_five_elementor',
			'car_dealer_elementor',
			'car_dealer_two_elementor',
			'car_dealer_elementor_rtl',
		);

		if ( ! in_array( get_option( 'stm_motors_chosen_template' ), $layouts, true ) ) {
			return;
		}

		$existing_templates = array();

		foreach ( $this->data_for_select as $data ) {
			$existing_templates[] = $data['slug'];
		}

		foreach ( $this->templates_sync_data as $slug => $data ) {

			if ( ! in_array( $slug, $existing_templates, true ) && file_exists( STM_LISTINGS_PATH . '/includes/listing-templates/' . get_option( 'stm_motors_chosen_template' ) . '/' . $slug . '.json' ) ) {

				global $wp_filesystem;

				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}

				$elementor_data = $wp_filesystem->get_contents( STM_LISTINGS_PATH . '/includes/listing-templates/' . get_option( 'stm_motors_chosen_template' ) . '/' . $slug . '.json' );

				if ( ! empty( $elementor_data ) ) {

					$post_data = array(
						'post_title'  => $data['title'],
						'post_name'   => $slug,
						'post_status' => 'publish',
						'post_type'   => $this->post_type,
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
						update_post_meta( $id, '_elementor_data', wp_slash( $elementor_data ) );

					}
				}
			}
		}

	}

	public function motors_single_pro_template_library_sync() {
		if ( ! is_admin() || wp_doing_ajax() || ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}
		$existing_templates = array();

		foreach ( $this->data_for_select as $data ) {
			$existing_templates[] = $data['slug'];
		}

		foreach ( $this->templates_sync_data as $slug => $data ) {
			$path = STM_LISTINGS_PATH . '/includes/listing-templates/pro/' . $slug . '.json';

			if ( ! in_array( $slug, $existing_templates, true ) && file_exists( $path ) ) {

				global $wp_filesystem;

				if ( empty( $wp_filesystem ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
					WP_Filesystem();
				}

				$elementor_data = $wp_filesystem->get_contents( $path );

				if ( ! empty( $elementor_data ) ) {

					$post_data = array(
						'post_title'  => $data['title'],
						'post_name'   => $slug,
						'post_status' => 'publish',
						'post_type'   => $this->post_type,
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
						update_post_meta( $id, '_elementor_data', wp_slash( $elementor_data ) );

					}
				}
			}
		}
	}

	public static function motors_display_template() {
		if ( ! class_exists( 'Elementor\Plugin' ) || apply_filters( 'motors_vl_get_nuxy_mod', 'elementor', 'listing_template_type' ) !== 'elementor' ) {
			return;
		}

		global $post;
		$special_listing_template = get_post_meta( $post->ID, 'special_listing_template', true );
		$template_listing_id      = ( $special_listing_template ) ? $special_listing_template : self::$selected_template_id;
		$template_listing         = get_post( $template_listing_id );
		setup_postdata( $template_listing );
		//phpcs:ignore
		echo Plugin::instance()->frontend->get_builder_content_for_display( $template_listing->ID );
		wp_reset_postdata();
	}
}
