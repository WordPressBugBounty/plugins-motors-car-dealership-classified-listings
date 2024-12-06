<?php

namespace MotorsVehiclesListing;

use STMMultiListing;

class ListingMetaboxes {


	public function __construct() {
		add_action( 'save_post', array( $this, 'save_metaboxes' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
	}

	public function add_metaboxes() {
		$custom_post_types = ( class_exists( 'STMMultiListing' ) ) ? STMMultiListing::stm_get_listing_type_slugs() : array();
		$post_types        = array_merge( array( 'listings' ), $custom_post_types );

		add_meta_box(
			'car_phone_views',
			esc_html__( 'Views & Clicks Activity', 'stm_vehicles_listing' ),
			array( $this, 'display_car_phone_views' ),
			$post_types,
			'side',
			'default',
		);

		add_meta_box(
			'listing_author',
			esc_html__( 'Author', 'stm_vehicles_listing' ),
			array( $this, 'display_author' ),
			$post_types,
			'side',
			'default',
		);

		add_meta_box(
			'listing_badges',
			esc_html__( 'Featured Listing', 'stm_vehicles_listing' ),
			array( $this, 'display_car_options' ),
			$post_types,
			'side',
			'default',
		);
		if ( apply_filters( 'is_mvl_pro', false ) || apply_filters( 'stm_is_motors_theme', false ) ) {
			add_meta_box(
				'sell_car_online',
				esc_html__( 'Listing Sale Actions', 'stm_vehicles_listing' ),
				array( $this, 'display_sell_car_online' ),
				$post_types,
				'side',
				'default'
			);
		}
	}

	public function display_car_phone_views( $post, $metabox ) {
		do_action(
			'stm_listings_load_template',
			'admin-metaboxes/views-clicks',
			array(
				'post'    => $post,
				'metabox' => $metabox,
			)
		);
	}

	public function display_author( $post, $metabox ) {
		$author_id = get_post_meta( $post->ID, 'stm_car_user', true );
		$authors   = get_users();

		do_action(
			'stm_listings_load_template',
			'admin-metaboxes/listing-author',
			array(
				'post'              => $post,
				'metabox'           => $metabox,
				'authors'           => $authors,
				'current_author_id' => $author_id,
			)
		);
	}

	public function display_car_options( $post, $metabox ) {
		do_action(
			'stm_listings_load_template',
			'admin-metaboxes/listing-badges',
			array(
				'post'    => $post,
				'metabox' => $metabox,
			)
		);
	}

	public function display_sell_car_online( $post, $metabox ) {
		do_action(
			'stm_listings_load_template',
			'admin-metaboxes/sell-car-online',
			array(
				'post'    => $post,
				'metabox' => $metabox,
			)
		);
	}

	public function save_metaboxes( $post_id ) {
		if ( ! isset( $_POST['stm_custom_nonce'] ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$metabox_fields = array(
			'stm_car_views',
			'stm_phone_reveals',
			'stm_car_user',
			'special_car',
			'badge_text',
			'badge_bg_color',
		);

		if ( apply_filters( 'is_mvl_pro', false ) || apply_filters( 'stm_is_motors_theme', false ) ) {
			$metabox_fields[] = 'car_mark_as_sold';
			$metabox_fields[] = 'special_text';
			$metabox_fields[] = 'special_image';
			$metabox_fields[] = 'car_mark_woo_online';
			$metabox_fields[] = 'stm_car_stock';
		}

		foreach ( $metabox_fields as $field ) {
			$old = get_post_meta( $post_id, $field, true );
			$new = isset( $_POST[ $field ] ) ? sanitize_text_field( $_POST[ $field ] ) : '';

			if ( $new !== $old ) {
				if ( ! empty( $new ) ) {
					update_post_meta( $post_id, $field, $new );
				} else {
					delete_post_meta( $post_id, $field );
				}
			}
		}
	}
}
