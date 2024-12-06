<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once STM_LISTINGS_PATH . '/includes/admin/butterbean_helpers.php';
do_action( 'stm_add_new_auto_complete' );
add_action( 'butterbean_register', 'stm_listings_register_manager', 10, 2 );

function stm_listings_register_manager( $butterbean, $post_type ) {
	$listings = apply_filters( 'stm_listings_post_type', 'listings' );

	// Register managers, sections, controls, and settings here.
	if ( $post_type !== $listings ) {
		return;
	}

	$butterbean->register_manager(
		'stm_car_manager',
		array(
			'label'     => esc_html__( 'Listing manager', 'stm_vehicles_listing' ),
			'post_type' => $listings,
			'context'   => 'normal',
			'priority'  => 'high',
		)
	);

	$manager = $butterbean->get_manager( 'stm_car_manager' );

	/*Register sections*/
	$manager->register_section(
		'stm_features',
		array(
			'label' => esc_html__( 'Options', 'stm_vehicles_listing' ),
			'icon'  => 'motors-icons-simple-car-ico big_icon',
		)
	);

	$manager->register_section(
		'stm_price',
		array(
			'label' => esc_html__( 'Price', 'stm_vehicles_listing' ),
			'icon'  => 'fa fa-dollar',
		)
	);

	$manager->register_section(
		'stm_additional_features',
		array(
			'label' => esc_html__( 'Extra Features', 'stm_vehicles_listing' ),
			'icon'  => 'fa-solid fa-square-check',
		)
	);

	$manager->register_section(
		'stm_media',
		array(
			'label' => html_entity_decode( esc_html__( 'Images & Videos', 'stm_vehicles_listing' ) ),
			'icon'  => 'motors-icons-media-ico',
		)
	);

	$manager->register_section(
		'map_location',
		array(
			'label' => esc_html__( 'Location', 'stm_vehicles_listing' ),
			'icon'  => 'fa-solid fa-location-dot',
		)
	);

	$manager->register_section(
		'stm_video',
		array(
			'label' => esc_html__( 'Videos', 'stm_vehicles_listing' ),
			'icon'  => 'fa fa-video-camera',
		)
	);

	$manager->register_section(
		'special_offers',
		array(
			'label' => esc_html__( 'Mark as Featured', 'stm_vehicles_listing' ),
			'icon'  => 'fa fa-bookmark',
		)
	);

	$manager->register_section(
		'motors_listing_info',
		array(
			'label' => esc_html__( 'Specifications', 'stm_vehicles_listing' ),
			'icon'  => 'fa fa-th-list',
		)
	);

	$manager->register_section(
		'stm_options',
		array(
			'label' => esc_html__( 'Other Details', 'stm_vehicles_listing' ),
			'icon'  => 'fa-solid fa-list',
		)
	);

	/**
	 * Register settings from selected demo
	 */
	do_action( 'listing_settings_register_section', $manager );

	/*Registering controls*/

	/*Media*/

	$manager->register_control(
		'gallery_title',
		array(
			'type'    => 'section_title',
			'section' => 'stm_media',
			'heading' => esc_html__( 'Image Gallery', 'stm_vehicles_listing' ),
		)
	);

	$manager->register_control(
		'gallery',
		array(
			'type'        => 'gallery',
			'section'     => 'stm_media',
			'description' => esc_html__( 'Add images to your listing item. The first image will be featured. Drag and drop to change the order.', 'stm_vehicles_listing' ),
			'size'        => 'stm-img-398-x-2',
		)
	);

	$manager->register_control(
		'gallery_videos_title',
		array(
			'type'    => 'section_title',
			'section' => 'stm_media',
			'heading' => esc_html__( 'Videos', 'stm_vehicles_listing' ),
		)
	);

	$manager->register_control(
		'video_posters_repeater',
		array(
			'type'        => 'video_repeater',
			'section'     => 'stm_media',
			'description' => esc_html__( 'The video will open and play in a pop-up window.', 'stm_vehicles_listing' ),
		)
	);

	/*Map Location*/
	$manager->register_control(
		'map_location_title',
		array(
			'type'    => 'section_title',
			'section' => 'map_location',
			'heading' => esc_html__( 'Location', 'stm_vehicles_listing' ),
		)
	);

	/*Additional features*/
	$manager->register_control(
		'additional_features_title',
		array(
			'type'    => 'section_title',
			'section' => 'stm_additional_features',
			'heading' => esc_html__( 'Extra Features', 'stm_vehicles_listing' ),
			'preview' => 'features',
			'link'    => esc_url( admin_url( 'admin.php?page=mvl_listing_details_settings' ) ),
		)
	);

	$manager->register_control(
		'additional_features',
		array(
			'type'    => ( ! empty( apply_filters( 'motors_vl_get_nuxy_mod', array(), 'fs_user_features' ) ) ) ? 'grouped_checkboxes' : 'checkbox_repeater',
			'section' => 'stm_additional_features',
		)
	);

	/*Price*/
	$manager->register_control(
		'price_title',
		array(
			'type'    => 'section_title',
			'section' => 'stm_price',
			'heading' => esc_html__( 'Price', 'stm_vehicles_listing' ),
		)
	);

	$manager->register_control(
		'price',
		array(
			'type'    => 'number',
			'section' => 'stm_price',
			'label'   => esc_html__( 'regular price', 'stm_vehicles_listing' ),
			'preview' => 'price_msrp',
			'attr'    => array(
				'class' => 'widefat',
			),
		)
	);

	$manager->register_control(
		'regular_price_label',
		array(
			'type'    => 'text',
			'section' => 'stm_price',
			'label'   => esc_html__( 'regular price label', 'stm_vehicles_listing' ),
			'preview' => 'price_regular_label',
			'attr'    => array(
				'class' => 'widefat',
			),
		)
	);

	$manager->register_control(
		'regular_price_description',
		array(
			'type'    => 'text',
			'section' => 'stm_price',
			'label'   => esc_html__( 'regular price description', 'stm_vehicles_listing' ),
			'preview' => 'regular_price_descr',
			'attr'    => array(
				'class' => 'widefat',
			),
		)
	);

	/**
	 * Register price controls from selected demo
	 */
	do_action( 'listing_price_register_control', $manager );

	$manager->register_control(
		'stm_genuine_price',
		array(
			'type'    => 'hidden',
			'section' => 'stm_price',
			'preview' => 'price',
			'label'   => esc_html__( 'Genuine Price', 'stm_vehicles_listing' ),
			'attr'    => array(
				'class' => 'widefat',
			),
		)
	);

	$manager->register_control(
		'sale_price',
		array(
			'type'    => 'number',
			'section' => 'stm_price',
			'preview' => 'price',
			'label'   => esc_html__( 'sale price', 'stm_vehicles_listing' ),
			'attr'    => array(
				'class' => 'widefat',
			),
		)
	);

	$manager->register_control(
		'special_price_label',
		array(
			'type'    => 'text',
			'section' => 'stm_price',
			'label'   => esc_html__( 'sale price label', 'stm_vehicles_listing' ),
			'preview' => 'price_special',
			'attr'    => array(
				'class' => 'widefat',
			),
		)
	);

	$manager->register_control(
		'instant_savings_label',
		array(
			'type'    => 'text',
			'section' => 'stm_price',
			'label'   => esc_html__( 'instant savings label', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Show the difference between the regular price and sale price', 'stm_vehicles_listing' ),
			'preview' => 'price_instant',
			'attr'    => array(
				'class' => 'widefat',
			),
		)
	);

	$manager->register_control(
		'car_price_form',
		array(
			'type'    => 'checkbox',
			'section' => 'stm_price',
			'preview' => 'price_request',
			'value'   => '1',
			'label'   => esc_html__( 'Request a Price Option', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Opens a form to request a price quote', 'stm_vehicles_listing' ),
		)
	);

	$manager->register_control(
		'car_price_form_label',
		array(
			'type'        => 'text',
			'section'     => 'stm_price',
			'label'       => esc_html__( 'Request a Price Button', 'stm_vehicles_listing' ),
			'preview'     => 'price_request',
			'description' => esc_html__( 'This text replaces the price with a \'Request a Price\' option.', 'stm_vehicles_listing' ),
			'attr'        => array(
				'data-dep'    => 'car_price_form',
				'data-value'  => 'true',
				'placeholder' => esc_html__( 'Enter button text', 'stm_vehicles_listing' ),
				'class'       => 'widefat',
			),
		)
	);

	do_action( 'add_pro_butterbean_fields', $manager );

	/*Map Location*/
	$manager->register_control(
		'stm_car_location',
		array(
			'type'    => 'location',
			'section' => 'map_location',
			'label'   => esc_html__( 'address', 'stm_vehicles_listing' ),
			'attr'    => array(
				'class' => 'widefat',
				'id'    => 'stm_car_location',
			),
		)
	);

	$manager->register_control(
		'stm_lat_car_admin',
		array(
			'type'    => 'text',
			'section' => 'map_location',
			'label'   => esc_html__( 'latitude', 'stm_vehicles_listing' ),
			'attr'    => array(
				'class' => 'widefat',
				'id'    => 'stm_lat_car_admin',
			),
		)
	);

	$manager->register_control(
		'stm_lng_car_admin',
		array(
			'type'    => 'text',
			'section' => 'map_location',
			'label'   => esc_html__( 'longitude', 'stm_vehicles_listing' ),
			'attr'    => array(
				'class' => 'widefat',
				'id'    => 'stm_lng_car_admin',
			),
		)
	);

	/*Options*/
	$manager->register_control(
		'automanager_id',
		array(
			'type'    => 'hidden',
			'section' => 'stm_options',
			'label'   => esc_html__( 'Listing ID', 'stm_vehicles_listing' ),
			'attr'    => array( 'class' => 'widefat' ),
		)
	);

	$manager->register_control(
		'stm_location_address',
		array(
			'type'    => 'hidden',
			'section' => 'stm_options',
			'label'   => esc_html__( 'Address Components', 'stm_vehicles_listing' ),
			'attr'    => array(
				'class' => 'widefat',
				'id'    => 'stm_location_address',
			),
		)
	);

	do_action( 'add_classified_fields', $manager );

	do_action( 'listing_settings_register_controls', $manager );

	$manager->register_control(
		'certificate_repeater',
		array(
			'type'           => 'media_repeater',
			'section'        => 'stm_options',
			'label'          => esc_html__( 'certificate', 'stm_vehicles_listing' ),
			'preview'        => 'history-txt',
			'second_preview' => array(
				'preview_url' => STM_LISTINGS_URL . '/includes/admin/butterbean/images/CERT1.jpg',
			),
		)
	);

	$manager->register_control(
		'car_brochure',
		array(
			'type'    => 'file',
			'section' => 'stm_options',
			'label'   => esc_html__( 'Vehicle Info PDF', 'stm_vehicles_listing' ),
			'preview' => 'pdf',
			'attr'    => array(
				'class'     => 'widefat',
				'data-type' => 'application/pdf',
			),
		)
	);

	do_action( 'listing_settings_register_controls_end', $manager );

	/*Registering Setting*/

	/*Media*/

	$manager->register_setting(
		'gallery',
		array( 'sanitize_callback' => 'stm_listings_validate_gallery' )
	);

	/*Video*/
	$manager->register_setting(
		'video_preview',
		array( 'sanitize_callback' => 'stm_listings_validate_image' )
	);

	$manager->register_setting(
		'video_posters_repeater',
		array( 'sanitize_callback' => 'video_repeater' )
	);

	$manager->register_setting(
		'certificate_repeater',
		array( 'sanitize_callback' => 'media_repeater' )
	);

	/*Price*/
	$manager->register_setting(
		'price',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'sale_price',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'stm_genuine_price',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'regular_price_label',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'regular_price_description',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'special_price_label',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'instant_savings_label',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'car_price_form',
		array(
			'sanitize_callback' => 'stm_listings_validate_checkbox',
		)
	);

	$manager->register_setting(
		'car_price_form_label',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	/*Options*/
	$manager->register_setting(
		'automanager_id',
		array(
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		)
	);

	$manager->register_setting(
		'stock_number',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'serial_number',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'registration_number',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'stm_car_location',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'stm_lat_car_admin',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'stm_lng_car_admin',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'stm_location_address',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'vin_number',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'city_mpg',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'highway_mpg',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'home_charge_time',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'fast_charge_time',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'registration_date',
		array( 'sanitize_callback' => 'wp_filter_nohtml_kses' )
	);

	$manager->register_setting(
		'car_brochure',
		array( 'sanitize_callback' => 'stm_listings_validate_image' )
	);

	$manager->register_setting(
		'additional_features',
		array( 'sanitize_callback' => 'stm_listings_validate_repeater' )
	);

	$manager->register_setting(
		'listing_specifications',
		array( 'sanitize_callback' => 'stm_listings_validate_repeater_specifications' )
	);

	/*Features*/
	$options = get_option( 'stm_vehicle_listing_options' );

	$manager->register_control(
		'stm_features_title',
		array(
			'type'    => 'section_title',
			'section' => 'stm_features',
			'heading' => esc_html__( 'Options', 'stm_vehicles_listing' ),
		)
	);

	if ( ! empty( $options ) ) {
		$args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'fields'     => 'all',
			'pad_counts' => false,
		);

		/*Add multiselects*/
		foreach ( $options as $key => $option ) {

			if ( 'price' === $option['slug'] || ( stm_is_multilisting() && isset( $option['listing_price_field'] ) && true === $option['listing_price_field'] ) ) {
				continue;
			}

			$terms       = get_terms( $option['slug'], $args );
			$single_term = array();

			foreach ( $terms as $tax_key => $taxonomy ) {
				if ( ! empty( $taxonomy ) ) {
					unset( $single_term[''] );
					$single_term[ $taxonomy->slug ] = $taxonomy->name;
				}
			}

			if ( empty( $option['numeric'] ) ) {
				$parent = array_key_exists( 'listing_taxonomy_parent', $option ) ? $option['listing_taxonomy_parent'] : '';
				$manager->register_control(
					$option['slug'],
					array(
						'type'    => 'multiselect',
						'section' => 'stm_features',
						'label'   => $option['plural_name'],
						'choices' => $single_term,
						'attr'    => array( 'data-parent' => $parent ),
					)
				);

				$manager->register_setting(
					$option['slug'],
					array(
						'sanitize_callback' => 'stm_listings_multiselect',
					)
				);
			} else { /*Add number fields*/
				$manager->register_control(
					$option['slug'],
					array(
						'type'    => 'text',
						'section' => 'stm_features',
						'label'   => $option['single_name'],
						'attr'    => array( 'class' => 'widefat' ),
					)
				);

				$manager->register_setting(
					$option['slug'],
					array(
						'sanitize_callback' => 'wp_filter_nohtml_kses',
					)
				);
			}
		}
	}
}

function stm_listings_sanitize_location_address( $value, $settings ) {
	stm_sanitize_location_address_update( $value, $settings->manager->post_id );

	return $value;
}

add_filter( 'butterbean_stm_car_manager_sanitize_stm_location_address', 'stm_listings_sanitize_location_address', 10, 2 );
