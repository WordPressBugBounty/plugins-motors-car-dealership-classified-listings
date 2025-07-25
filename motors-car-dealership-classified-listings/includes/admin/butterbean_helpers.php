<?php
// @codingStandardsIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*Load butterbean framework*/
add_action( 'plugins_loaded', 'stm_listings_load_butterbean' );
function stm_listings_load_butterbean() {
	require_once( STM_LISTINGS_PATH . '/includes/admin/butterbean/butterbean.php' );
}

function stm_listings_validate_checkbox( $value ) {
	return wp_validate_boolean( $value ) ? 'on' : false;
}

function stm_listings_no_validate( $value ) {
	return $value;
}

function stm_listings_validate_image( $value ) {
	return ! empty( $value ) ? intval( $value ) : false;
}

function stm_listings_validate_repeater( $value, $butterbean ) {

	/*We need to save user additional features in hidden taxonomy*/
	if ( $butterbean->name == 'additional_features' ) {
		$new_terms = explode( ',', $value );
		wp_set_object_terms( $butterbean->manager->post_id, $new_terms, 'stm_additional_features' );
	}

	/*Saved*/

	return $value;
}

function stm_listings_validate_repeater_specifications( $value, $butterbean ) {
	if ( ! empty( $value ) ) {
		foreach ( $value as $k => $val ) {
			if ( empty( $value[ $k ] ) ) {
				unset( $value[ $k ] );
			}
		}
	}

	return $value;
}

function stm_listings_validate_repeater_videos( $value, $butterbean ) {
	if ( ! empty( $value ) ) {
		foreach ( $value as $k => $val ) {
			if ( empty( $value[ $k ] ) ) {
				unset( $value[ $k ] );
			}
		}
	}

	return $value;
}

/* Save Video Repeater */
function video_repeater( $value, $butterbean ) {
	$links        = isset( $_POST['video_links'] ) ? array_map( 'sanitize_text_field', $_POST['video_links'] ) : [];
	$video_images = isset( $_POST['video_image'] ) ? array_map( 'sanitize_text_field', $_POST['video_image'] ) : [];

	$post_id = $butterbean->manager->post_id;

	if ( isset( $links ) ) {
		update_post_meta( $post_id, 'gallery_videos', $links );
	}

	if ( isset( $video_images ) ) {
		update_post_meta( $post_id, 'gallery_videos_posters', $video_images );
	}

	return $value;
}

/* Save Media Repeater */
function media_repeater( $value, $butterbean ) {
	$post_id = $butterbean->manager->post_id;

	$history = isset( $_POST['certificate_media_file_name'] ) ? array_map( 'sanitize_text_field', $_POST['certificate_media_file_name'] ): [];
	$history_link = isset( $_POST['certificate_media_links'] ) ? array_map( 'sanitize_text_field', $_POST['certificate_media_links'] ): [];
	$certified_logo_1 = isset( $_POST['certificate_media_image'] ) ? array_map( 'sanitize_text_field', $_POST['certificate_media_image'] ): [];

	update_post_meta( $post_id, 'history', isset( $history[0] ) ? $history[0] : '' );
	update_post_meta( $post_id, 'history_link', isset( $history_link[0] ) ? $history_link[0] : '' );
	update_post_meta( $post_id, 'certified_logo_1', isset( $certified_logo_1[0] ) ? $certified_logo_1[0] : '' );
	update_post_meta( $post_id, 'history_2', isset( $history[1] ) ? $history[1] : '' );
	update_post_meta( $post_id, 'certified_logo_2_link', isset( $history_link[1] ) ? $history_link[1] : '' );
	update_post_meta( $post_id, 'certified_logo_2', isset( $certified_logo_1[1] ) ? $certified_logo_1[1] : '' );

	return $value;
}

function stm_listings_multiselect( $value, $butterbean ) {
	wp_set_object_terms( $butterbean->manager->post_id, $value, $butterbean->name );

	return $value ? implode( ',', (array) $value ) : false;
}

function stm_listings_validate_gallery( $value ) {
	$value  = explode( ',', $value );
	$values = array();

	$featured_image = '';

	if ( empty( $value[0] ) ) {
		array_shift( $value );
	}

	if ( ! empty( $value ) ) {
		$i = 0;
		foreach ( $value as $img_id ) {
			$i ++;
			$img_id = intval( $img_id );
			if ( ! empty( $img_id ) ) {
				if ( $i == 1 ) {
					$featured_image = $img_id;
				} else {
					$values[] = $img_id;
				}
			}
		}
	}

	if ( ! empty( $featured_image ) ) {
		set_post_thumbnail( get_the_ID(), $featured_image );
	}

	return ! empty( $values ) ? $values : false;
}

function stm_gallery_videos_posters( $value ) {
	if ( ! empty( $value ) ) {
		$value = explode( ',', $value );
	}

	return $value;
}

function stm_listings_get_user_list() {
	$users_args     = array(
		'blog_id'      => $GLOBALS['blog_id'],
		'role'         => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'meta_compare' => '',
		'meta_query'   => array(),
		'date_query'   => array(),
		'include'      => array(),
		'exclude'      => array(),
		'orderby'      => 'registered',
		'order'        => 'ASC',
		'offset'       => '',
		'search'       => '',
		'number'       => '',
		'count_total'  => false,
		'fields'       => 'all',
		'who'          => ''
	);
	$users          = get_users( $users_args );
	$users_dropdown = array(
		'' => esc_html__( 'Not assigned', 'stm_vehicles_listing' )
	);
	if ( ! is_wp_error( $users ) ) {
		foreach ( $users as $user ) {
			$users_dropdown[ $user->data->ID ] = $user->data->user_login;
		}
	}

	return $users_dropdown;
}

function stm_listings_add_category_in() {
	check_ajax_referer( 'listings_add_category_in', 'security', false );
	$response = array();
	$category = $term = '';

	if ( ! empty( $_GET['category'] ) ) {
		$category = sanitize_text_field( $_GET['category'] );
	}

	if ( ! empty( $_GET['term'] ) ) {
		$term = sanitize_text_field( $_GET['term'] );
	}

	if ( ! empty( $term ) and ! empty( $category ) ) {
		$term_slug = sanitize_title( $term );
		$term_id   = term_exists( $term_slug, $category );
		if ( $term_id === 0 or $term_id === null ) {
			$term_id = wp_insert_term( $term, $category );
		} else {
			$term_info           = get_term_by( 'id', $term_id['term_id'], $category );
			$response['message'] = sprintf( __( '%s already added!', 'stm_vehicles_listing' ), esc_html( $term_info->name ) );
			$response['status']  = 'success';
			$response['slug']    = $term_info->slug;
			$response['name']    = $term_info->name;

			wp_send_json( $response );
			exit;
		}

		if ( ! empty( $term_id ) and ! is_wp_error( $term_id ) ) {
			$term_info           = get_term_by( 'id', $term_id['term_id'], $category );
			$response['message'] = sprintf( __( '%s added!', 'stm_vehicles_listing' ), esc_html( $term_info->name ) );
			$response['status']  = 'success';
			$response['slug']    = $term_info->slug;
			$response['name']    = $term_info->name;
		} else {
			$response['status']  = 'danger';
			$response['message'] = $term_id->get_error_message();
		}
	}

	wp_send_json( $response );
	exit;
}

add_action( 'wp_ajax_stm_listings_add_category_in', 'stm_listings_add_category_in' );

add_action( 'save_post', 'stm_butterbean_add_new_listings_terms', 50, 1 );

function stm_butterbean_add_new_listings_terms( $post_id ) {
	$manager_slug = 'stm_car';
	$options      = get_option( 'stm_vehicle_listing_options' );
	$post_type    = get_post_type( $post_id );

	if ( $post_type != apply_filters( 'stm_listings_post_type', 'listings' ) ) {
		$manager_slug = $post_type;
		$options      = get_option( "stm_{$post_type}_options" );
	}
	$field_prefix = 'butterbean_' . $manager_slug . '_manager_setting_';

	foreach ( $_POST as $field_key => $field_value ) {
		if ( str_starts_with( $field_key, $field_prefix ) ) {
			$field_name = substr( $field_key, strlen( $field_prefix ) );
			foreach ( $options as $option ) {
				if ( $field_name === $option['slug'] && $option['numeric'] ) {
					$term_slug = sanitize_title( $field_value );
					$term_id   = term_exists( $term_slug, $field_name );
					if ( 0 === $term_id || is_null( $term_id ) ) {
						$new_term = wp_insert_term( $field_value, $field_name );
						if ( ! is_wp_error( $new_term ) ) {
							wp_set_object_terms( $post_id, $new_term['term_id'], $field_name, true );
						}
					}
				}
			}
		}
	}
}

add_action( 'save_post', 'stm_butterbean_save_post', 100, 1 );

function stm_butterbean_save_post( $post_id ) {

	$post_types = [ apply_filters( 'stm_listings_post_type', 'listings' ) ];

	$post_type = get_post_type( $post_id );

	if ( stm_is_multilisting() ) {
		$slugs = STMMultiListing::stm_get_listing_type_slugs();

		if ( ! empty( $slugs ) ) {
			$post_types = array_merge( $post_types, $slugs );
		}
	}

	// stop if not saving STM listing
	if ( ! in_array( $post_type, $post_types ) ) {
		return;
	}

	do_action( 'motors_butterbean_save_post', $post_id );

	// multilisting ready post type slug fix for car manager
	$manager_slug = 'stm_car';
	if ( $post_type != apply_filters( 'stm_listings_post_type', 'listings' ) ) {
		$manager_slug = $post_type;
	}

	// prettify butterbean post requests
	if ( isset( $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_price' ] ) ) {
		$request_real_price = $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_price' ];
	}

	if ( isset( $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_sale_price' ] ) ) {
		$request_sale_price = $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_sale_price' ];
	}

	if ( isset( $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_rent_price' ] ) ) {
		$request_rent_price = $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_rent_price' ];
	}

	if ( isset( $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_sale_rent_price' ] ) ) {
		$reqeust_rent_sale_price = $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_sale_rent_price' ];
	}

	if ( isset( $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_stm_car_user' ] ) ) {
		$request_car_manager = $_POST[ 'butterbean_' . $manager_slug . '_manager_setting_stm_car_user' ];
	}

	if ( ! empty( $request_real_price ) || ! empty( $request_sale_price ) ) {
		$price = ( ! empty( $request_sale_price ) ) ? $request_sale_price : $request_real_price;
	}

	// when listing saved from frontend
	if ( ! empty( $_POST['stm_car_sale_price'] ) || ! empty( $_POST['stm_car_price'] ) ) {
		$price = ( ! empty( $_POST['stm_car_sale_price'] ) ) ? $_POST['stm_car_sale_price'] : $_POST['stm_car_price'];
	}

	// rent price is only for motors rental layouts
	if ( ! empty( $request_rent_price ) ) {
		$rent_price = $request_rent_price;
	}

	if ( ! empty( $reqeust_rent_sale_price ) ) {
		$rent_sale_price = $reqeust_rent_sale_price;
	}

	// genuine prices are used programmatically, in things like sorting and filtering
	if ( ! empty( $price ) ) {
		update_post_meta( $post_id, 'stm_genuine_price', $price );
	}
	if ( ! empty( $rent_price ) ) {
		update_post_meta( $post_id, 'rent_price', $rent_price );
	}
	if ( ! empty( $rent_sale_price ) ) {
		update_post_meta( $post_id, 'sale_rent_price', $rent_sale_price );
	}

	// change author when owner changed
	if ( ! empty( $request_car_manager ) ) {
		$author_id = get_post_field( 'post_author', $post_id );
		if ( $author_id != $request_car_manager ) {
			wp_update_post( [ 'ID' => $post_id, 'post_author' => $request_car_manager ] );
		}
	}

	$mark_as_sold = isset( $_POST['car_mark_as_sold'] ) ? $_POST['car_mark_as_sold'] : '';

	update_post_meta( $post_id, 'car_mark_as_sold', $mark_as_sold );

}

function stm_save_genuine_price( $post_id ) {
	$post_type = get_post_type( $post_id );
	if ( apply_filters( 'stm_listings_post_type', 'listings' ) != $post_type ) {
		return;
	}

	$price = $sale_price = '';
	if ( isset( $_POST['butterbean_stm_car_manager_setting_price'] ) ) {
		$price = sanitize_text_field( $_POST['butterbean_stm_car_manager_setting_price'] );
	}

	if ( isset( $_POST['butterbean_stm_car_manager_setting_sale_price'] ) ) {
		$sale_price = sanitize_text_field( $_POST['butterbean_stm_car_manager_setting_sale_price'] );
	}

	if ( ! empty( $sale_price ) ) {
		$price = $sale_price;
	}

	if ( ! empty( $price ) ) {
		update_post_meta( $post_id, 'stm_genuine_price', $price );
	}
}

add_action( 'save_post', 'stm_save_genuine_price', 100, 1 );
// @codingStandardsIgnoreEnd

function mvl_show_pro_fields( $choices ) {
	$pro_choices = array(
		'checkbox'  => array(
			'label'     => esc_html__( 'Checkbox', 'stm_vehicles_listing' ),
			'pro_field' => true,
		),
		'price'     => array(
			'label'     => esc_html__( 'Price', 'stm_vehicles_listing' ),
			'pro_field' => true,
		),
		'location'  => array(
			'label'     => esc_html__( 'Location', 'stm_vehicles_listing' ),
			'pro_field' => true,
		),
		'color'     => array(
			'label'     => esc_html__( 'Color', 'stm_vehicles_listing' ),
			'pro_field' => true,
		),
	);

	return array_merge( $choices, $pro_choices );
}

function mvl_show_pro_fields_settings( $settings ) {
	$settings['skins']['label'] = __( 'Skins settings', 'stm_vehicles_listing' );

	return $settings;
}

if ( ! apply_filters( 'is_mvl_pro', false ) ) {
	add_filter( 'mvl_field_type_choices', 'mvl_show_pro_fields', 1, 1 );
	add_filter( 'mvl_custom_fields_settings', 'mvl_show_pro_fields_settings', 10, 1 );
}
