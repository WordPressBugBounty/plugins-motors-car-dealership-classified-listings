<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use \MotorsVehiclesListing\Plugin;
/** @var \MotorsVehiclesListing\Plugin $plugin */

$plugin = new Plugin();

add_action(
	'plugins_loaded',
	function () use ( $plugin ) {
		$plugin->register_addons( apply_filters( 'motors_vl_plugin_addons', array() ) );
	}
);

add_action( 'wp', 'stm_listings_template_actions' );

function stm_listings_template_actions() {
	$action = apply_filters( 'stm_listings_input', null, 'ajax_action' );
	if ( $action ) {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		define( 'DOING_AJAX', true );
		switch ( $action ) {
			case 'listings-result':
				$nav_type = ( ! empty( $_POST['navigation_type'] ) ) ? sanitize_text_field( $_POST['navigation_type'] ) : null;
				$source   = ( ! empty( $_POST['posts_per_page'] ) ) ? array( 'posts_per_page' => sanitize_text_field( $_POST['posts_per_page'] ) ) : null;

				if ( ! empty( $_POST['custom_img_size'] ) ) {
					$source['custom_img_size'] = sanitize_text_field( $_POST['custom_img_size'] );
				}
				// if request is for multilisting post types
				if ( stm_is_multilisting() && ! empty( $_GET['posttype'] ) && ! in_array( $_GET['posttype'], array( 'undefined', apply_filters( 'stm_listings_post_type', 'listings' ) ), true ) ) {
					set_query_var( 'listings_type', $_GET['posttype'] );
					HooksMultiListing::stm_listings_attributes_filter( array( 'slug' => $_GET['posttype'] ) );
				}
				stm_listings_ajax_results( $source, null, $nav_type );
				break;
			case 'listings-result-load':
				$nav_type = ( ! empty( $_GET['navigation_type'] ) ) ? $_GET['navigation_type'] : null;
				$source   = ( ! empty( $_GET['posts_per_page'] ) ) ? array( 'posts_per_page' => $_GET['posts_per_page'] ) : null;
				$source   = ( ! empty( $_GET['offset'] ) ) ? array( 'offset' => ( $_GET['offset'] * $_GET['posts_per_page'] ) ) : $source;

				stm_listings_items_ajax_results( $source, null, $nav_type );
				break;
			case 'listings-sold':
				stm_listings_ajax_results( array( 'sold_car' => 'on' ), 'sold_car', null );
				break;
			case 'listings-binding':
				$hide_empty = ( ! empty( $_GET['hide_empty'] ) ) ? sanitize_text_field( $_GET['hide_empty'] ) : false;
				stm_listings_binding_results( $hide_empty );
				break;
			case 'listings-items':
				stm_listings_items();
				break;
			case 'stm_load_dealers_list':
				mvl_load_dealers_list();
				break;
		}
	}
}

/**
 * Ajax filter cars
 */
function stm_listings_ajax_results( $source = null, $type = null, $navigation_type = null ) {
	$r         = apply_filters( 'stm_listings_filter_func', $source );
	$fragments = false;

	if ( ! empty( $_GET['fragments'] ) ) {
		$fragments = explode( ',', $_GET['fragments'] );
	}

	if ( ! $fragments || in_array( 'html', $fragments, true ) ) {
		ob_start();
		do_action( 'stm_listings_load_results', $source, $type, $navigation_type );
		$r['html'] = ob_get_clean();
	}

	$r['filter_links'] = stm_get_car_filter_links();

	$sorts = get_stm_select_sorting_options_for_select2();

	$selected = apply_filters( 'stm_listings_input', stm_get_default_sort_option(), 'sort_order' );

	if ( sort_distance_nearby() ) {
		$sorts = array_merge(
			array(
				array(
					'id'   => 'distance_nearby',
					'text' => esc_html__( 'Distance : nearby', 'stm_vehicles_listing' ),
				),
			),
			$sorts
		);

		$selected = 'distance_nearby';
	}

	foreach ( $sorts as $key => $value ) {
		if ( $value['id'] === $selected ) {
			$sorts[ $key ]['selected'] = true;
		}
	}

	$excluded_params = apply_filters( 'stm_url_excluded_params', array( 'ajax_action', 'fragments', 'stm_location_address', 'security' ) );

	if ( ! is_array( $excluded_params ) ) {
		$excluded_params = array();
	}

	if ( apply_filters( 'stm_listings_input', null, 'stm_lat' ) && apply_filters( 'stm_listings_input', null, 'stm_lng' ) && ! apply_filters( 'stm_listings_input', null, 'ca_location' ) ) {
		$excluded_params[] = 'stm_lat';
		$excluded_params[] = 'stm_lng';
	}

	$r['sorts']    = $sorts;
	$r['url']      = remove_query_arg( $excluded_params );
	$r             = apply_filters( 'stm_listings_ajax_results', $r );
	$filter_badges = apply_filters( 'stm_get_filter_badges', array() );

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ) ) {
		$query_vars = $_GET;
		unset( $query_vars['security'] );
		unset( $query_vars['ajax_action'] );
		unset( $query_vars['fragments'] );
		unset( $query_vars['posttype'] );

		if ( count( $query_vars ) > 0 ) {
			$url = apply_filters( 'stm_filter_listing_link', '' );
			$qs  = array();
			foreach ( $query_vars as $key => $value ) {
				if ( in_array( $key, $excluded_params, true ) ) {
					continue;
				}

				if ( strpos( $key, 'min_' ) !== false || strpos( $key, 'max_' ) !== false ) {
					$qs[] = $key . '=' . $value;
				} else {
					if ( is_array( $value ) ) {
						foreach ( $value as $v ) {
							$numeric = apply_filters( 'get_value_from_listing_category', false, $key, 'numeric' );
							if ( ! $numeric ) {
								$url .= $v . '/';
							} else {
								$qs[] = $key . '[]=' . $v;
							}
						}
					} else {
						$numeric           = apply_filters( 'get_value_from_listing_category', false, $key, 'numeric' );
						$friendly_excluded = apply_filters( 'stm_friendly_url_excluded_params', array( 'stm_keywords', 'stm_lat', 'stm_lng', 'ca_location' ) );

						if ( ! empty( $friendly_excluded ) && is_array( $friendly_excluded ) && in_array( $key, $friendly_excluded, true ) ) {
							$numeric = true;
						}

						if ( ! $numeric ) {
							$url .= $value . '/';
						} else {
							$qs[] = $key . '=' . $value;
						}
					}
				}
			}

			if ( ! empty( get_query_var( 'paged' ) ) ) {
				$url .= 'page/' . get_query_var( 'paged' ) . '/';
			}

			if ( ! empty( $qs ) ) {
				$url .= ( strpos( $url, '?' ) ? '&' : '?' ) . join( '&', $qs );
			}

			$r['url'] = $url;
		}
	}

	if ( $fragments ) {
		$r = array_intersect_key( $r, array_flip( $fragments ) );
	}
	//add filter badges
	$r['filter_badges'] = $filter_badges;
	wp_send_json( $r );
	exit;
}

/**
 * Ajax filter cars
 */
function stm_listings_items_ajax_results( $source = null, $type = null, $navigation_type = null ) {

	$r = apply_filters( 'stm_listings_filter_func', $source );

	$fragments = false;
	if ( ! empty( $_GET['fragments'] ) ) {
		$fragments = explode( ',', $_GET['fragments'] );
	}

	if ( ! $fragments || in_array( 'html', $fragments, true ) ) {
		ob_start();
		stm_listings_load_items_results( $source, $type, $navigation_type );
		$r['html'] = ob_get_clean();
	}

	$r['filter_links'] = stm_get_car_filter_links();

	$sorts = get_stm_select_sorting_options_for_select2();

	if ( sort_distance_nearby() ) {
		$sorts = array_merge(
			array(
				array(
					'id'   => 'distance_nearby',
					'text' => esc_html__(
						'Distance : nearby',
						'stm_vehicles_listing'
					),
				),
			),
			$sorts
		);
	}

	$selected = apply_filters( 'stm_listings_input', stm_get_default_sort_option(), 'sort_order' );

	foreach ( $sorts as $key => $value ) {
		if ( $value['id'] === $selected ) {
			$sorts[ $key ]['selected'] = true;
		}
	}

	$r['sorts'] = $sorts;
	$r['url']   = remove_query_arg( array( 'ajax_action', 'fragments' ) );

	$r = apply_filters( 'stm_listings_ajax_results', $r );

	if ( $fragments ) {
		$r = array_intersect_key( $r, array_flip( $fragments ) );
	}

	wp_send_json( $r );
	exit;
}

/**
 * Ajax filter binding
 */
function stm_listings_binding_results( $hide_empty ) {
	$r = apply_filters( 'stm_listings_filter_func', null, $hide_empty );

	$fragments = apply_filters( 'stm_listings_input', null, 'fragments' );
	if ( ! empty( $fragments ) ) {
		$fragments = array_filter( explode( ',', $fragments ) );
	}

	$r = apply_filters( 'stm_listings_binding_results', $r );

	if ( $fragments ) {
		$r = array_intersect_key( $r, array_flip( $fragments ) );
	}

	wp_send_json( $r, 200 );
	exit;
}

/**
 * Ajax filter items
 */
function stm_listings_items() {
	$r = array();

	$fragments = apply_filters( 'stm_listings_input', null, 'fragments' );
	if ( ! empty( $fragments ) ) {
		$fragments = explode( ',', $fragments );
	}

	if ( ! $fragments ) {
		ob_start();
		do_action( 'stm_listings_load_results' );
		$r['html'] = ob_get_clean();
	}

	$r = apply_filters( 'stm_listings_items', $r );

	if ( $fragments ) {
		$r = array_intersect_key( $r, array_flip( $fragments ) );
	}

	wp_send_json( $r );
	exit;
}

function stm_listings_ajax_save_user_data() {
	check_ajax_referer( 'stm_listings_user_data_nonce', 'security', false );

	$response = array();

	if ( ! is_user_logged_in() ) {
		die( 'You are not logged in' );
	}

	$got_error_validation = false;
	$error_msg            = esc_html__( 'Settings Saved.', 'stm_vehicles_listing' );

	$user_current = wp_get_current_user();
	$user_id      = $user_current->ID;
	$user         = apply_filters( 'stm_get_user_custom_fields', $user_id );

	/*Get current editing values*/
	$user_mail = apply_filters( 'stm_listings_input', $user['email'], 'stm_email' );
	$user_mail = sanitize_email( $user_mail );
	/*Socials*/
	$socs    = array( 'facebook', 'twitter', 'linkedin', 'youtube' );
	$socials = array();
	if ( empty( $user['socials'] ) ) {
		$user['socials'] = array();
	}
	foreach ( $socs as $soc ) {
		if ( empty( $user['socials'][ $soc ] ) ) {
			$user['socials'][ $soc ] = '';
		}
		$socials[ $soc ] = apply_filters( 'stm_listings_input', $user['socials'][ $soc ], 'stm_user_' . $soc );
	}

	$password_check = false;
	if ( empty( get_user_meta( $user_id, 'wsl_current_provider', true ) ) ) {
		if ( ! empty( $_POST['stm_confirm_password'] ) ) {
			$password_check = wp_check_password( $_POST['stm_confirm_password'], $user_current->data->user_pass, $user_id );
		}

		if ( ! $password_check && ! empty( $_POST['stm_confirm_password'] ) ) {
			$got_error_validation = true;
			$error_msg            = esc_html__( 'Confirmation password is wrong', 'stm_vehicles_listing' );
		}
	} else {
		$password_check = true;
	}

	$demo = apply_filters( 'stm_site_demo_mode', false );

	if ( $password_check && ! $demo ) {
		// Editing/adding user filled fields
		/*Image changing*/
		$allowed = array( 'jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF' );
		if ( ! empty( $_FILES['stm-avatar'] ) ) {
			$file = $_FILES['stm-avatar'];
			if ( is_array( $file ) && ! empty( $file['name'] ) ) {
				$ext = pathinfo( $file['name'] );
				$ext = $ext['extension'];
				if ( in_array( $ext, $allowed, true ) ) {

					$upload_dir  = wp_upload_dir();
					$upload_url  = $upload_dir['url'];
					$upload_path = $upload_dir['path'];

					/*Upload full image*/
					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
					}
					$original_file = wp_handle_upload( $file, array( 'test_form' => false ) );

					if ( ! is_wp_error( $original_file ) ) {
						$image_user = $original_file['file'];
						/*Crop image to square from full image*/
						$image_cropped = image_make_intermediate_size( $image_user, 160, 160, true );

						/*Delete full image*/
						if ( file_exists( $image_user ) ) {
							unlink( $image_user );
						}

						if ( ! $image_cropped ) {
							$got_error_validation = true;
							$error_msg            = esc_html__( 'Error, please try again', 'stm_vehicles_listing' );

						} else {

							/*Get path and url of cropped image*/
							$user_new_image_url  = $upload_url . '/' . $image_cropped['file'];
							$user_new_image_path = $upload_path . '/' . $image_cropped['file'];

							/*Delete from site old avatar*/

							$user_old_avatar = get_the_author_meta( 'stm_user_avatar_path', $user_id );
							if ( ! empty( $user_old_avatar ) && $user_new_image_path !== $user_old_avatar && file_exists( $user_old_avatar ) ) {

								/*Check if prev avatar exists in another users except current user*/
								$args     = array(
									'meta_key'     => 'stm_user_avatar_path',
									'meta_value'   => $user_old_avatar,
									'meta_compare' => '=',
									'exclude'      => array( $user_id ),
								);
								$users_db = get_users( $args );
								if ( empty( $users_db ) ) {
									unlink( $user_old_avatar );
								}
							}

							/*Set new image tmp*/
							$user['image'] = $user_new_image_url;

							/*Update user meta path and url image*/
							update_user_meta( $user_id, 'stm_user_avatar', $user_new_image_url );
							update_user_meta( $user_id, 'stm_user_avatar_path', $user_new_image_path );

							$response               = array();
							$response['new_avatar'] = $user_new_image_url;

						}
					}
				} else {
					$got_error_validation = true;
					$error_msg            = esc_html__( 'Please load image with right extension (jpg, jpeg, png and gif)', 'stm_vehicles_listing' );
				}
			}
		}

		/*Check if delete*/
		if ( empty( $_FILES['stm-avatar']['name'] ) ) {
			if ( ! empty( $_POST['stm_remove_img'] ) && 'delete' === $_POST['stm_remove_img'] ) {
				$user_old_avatar = get_the_author_meta( 'stm_user_avatar_path', $user_id );
				/*Check if prev avatar exists in another users except current user*/
				$args     = array(
					'meta_key'     => 'stm_user_avatar_path',
					'meta_value'   => $user_old_avatar,
					'meta_compare' => '=',
					'exclude'      => array( $user_id ),
				);
				$users_db = get_users( $args );
				if ( empty( $users_db ) ) {
					unlink( $user_old_avatar );
				}
				update_user_meta( $user_id, 'stm_user_avatar', '' );
				update_user_meta( $user_id, 'stm_user_avatar_path', '' );

				$response['new_avatar'] = '';
			}
		}

		/*Change email*/
		$new_user_data = array(
			'ID'         => $user_id,
			'user_email' => $user_mail,
		);

		/*Change email visiblity*/
		if ( ! empty( $_POST['stm_show_mail'] ) && 'on' === $_POST['stm_show_mail'] ) { //phpcs:ignore
			update_user_meta( $user_id, 'stm_show_email', 'on' );
		} else {
			update_user_meta( $user_id, 'stm_show_email', '' );
		}

		// number has whatsapp
		if ( ! empty( $_POST['stm_whatsapp_number'] ) && 'on' === $_POST['stm_whatsapp_number'] ) {
			update_user_meta( $user_id, 'stm_whatsapp_number', 'on' );
		} else {
			update_user_meta( $user_id, 'stm_whatsapp_number', '' );
		}

		if ( ! empty( $_POST['stm_new_password'] ) && ! empty( $_POST['stm_new_password_confirm'] ) ) {
			if ( $_POST['stm_new_password_confirm'] === $_POST['stm_new_password'] ) {
				$new_user_data['user_pass'] = $_POST['stm_new_password'];
			} else {
				$got_error_validation = true;
				$error_msg            = esc_html__( 'New password not saved, because of wrong confirmation.', 'stm_vehicles_listing' );
			}
		}

		/*Author description*/
		$new_user_data['description'] = sanitize_textarea_field( $_POST['author_description'] );

		$user_error = wp_update_user( $new_user_data );
		if ( is_wp_error( $user_error ) ) {
			$got_error_validation = true;
			$error_msg            = $user_error->get_error_message();
		}

		/*
		Change fields with secondary privilegy*/
		/*POST key => user_meta_key*/
		$changed_info = array(
			'stm_first_name'    => 'first_name',
			'stm_last_name'     => 'last_name',
			'stm_phone'         => 'stm_phone',
			'stm_user_facebook' => 'stm_user_facebook',
			'stm_user_twitter'  => 'stm_user_twitter',
			'stm_user_linkedin' => 'stm_user_linkedin',
			'stm_user_youtube'  => 'stm_user_youtube',
		);

		foreach ( $changed_info as $change_to_key => $change_info ) {
			if ( isset( $_POST[ $change_to_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				$escaped_value = $_POST[ $change_to_key ];
				if ( 'stm_phone' === $change_to_key ) {
					$escaped_value = preg_replace( '/[^0-9+\-\(\)\s]/', '', $escaped_value );
				} else {
					$escaped_value = sanitize_text_field( $escaped_value );
				}
				update_user_meta( $user_id, $change_info, $escaped_value );
			}
		}
	} else {
		if ( $demo ) {
			$error_msg            = esc_html__( 'Site is on demo mode', 'stm_vehicles_listing' );
			$got_error_validation = true;
		}
	}

	$response['error']     = $got_error_validation;
	$response['error_msg'] = $error_msg;

	wp_send_json( $response );
}

add_action( 'wp_ajax_stm_listings_ajax_save_user_data', 'stm_listings_ajax_save_user_data' );

add_action( 'trashed_post', 'delete_images_from_trashed_listing' );

function delete_images_from_trashed_listing( $listing_id ) {
	if ( isset( $_COOKIE['deleteListingAttach'] ) ) {
		$featured_image_id = get_post_thumbnail_id( $listing_id );
		$attachment_ids    = get_post_meta( $listing_id, 'gallery' );

		if ( ! empty( $featured_image_id ) ) {
			wp_delete_attachment( $featured_image_id, true );
		}

		if ( isset( $attachment_ids[0] ) ) {
			foreach ( $attachment_ids[0] as $k => $val ) {
				wp_delete_attachment( $val, true );
			}
		}

		unset( $_COOKIE['deleteListingAttach'] );
		setcookie( 'deleteListingAttach', null, - 1, '/' );
	}
}

if ( ! function_exists( 'mvl_get_user_role' ) ) {
	function mvl_get_user_role( $default, $user_id = null, $return_role = false ) {
		$user_data = get_userdata( $user_id ? $user_id : get_current_user_id() );

		if ( $return_role && ! empty( $user_data->roles ) ) {
			return $user_data->roles[0];
		}

		return ! empty( $user_data ) && in_array( 'stm_dealer', $user_data->roles, true );
	}

	add_filter( 'mvl_get_user_role', 'mvl_get_user_role', 10, 2 );
}

add_action( 'pending_to_publish', 'stm_on_publish_pending_post', 10, 1 );
function stm_on_publish_pending_post( $post ) {
	add_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );
	$user_data = get_userdata( $post->post_author );

	if ( apply_filters( 'mvl_get_user_role', false, $post->post_author ) ) {
		$email_to = apply_filters( 'motors_vl_get_nuxy_mod', false, 'send_email_to_dealer' );
	} else {
		$email_to = apply_filters( 'motors_vl_get_nuxy_mod', false, 'send_email_to_user' );
	}

	if ( $email_to && in_array( $post->post_type, apply_filters( 'stm_listings_multi_type', array( 'listings' ) ), true ) ) {
		$to         = $user_data->user_email;
		$listing_id = $post->ID;

		$args = array(
			'car_id'    => $listing_id,
			'car_title' => get_the_title( $listing_id ),
		);

		$subject = apply_filters( 'get_generate_subject_view', '', 'user_listing_approved', $args );
		$body    = apply_filters( 'get_generate_template_view', '', 'user_listing_approved', $args );

		wp_mail( $to, $subject, $body );
	}

	remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );
}

if ( ! function_exists( 'stm_ajax_get_car_price' ) ) {
	//Ajax request test drive
	function stm_ajax_get_car_price() {
		check_ajax_referer( 'stm_car_price_nonce', 'security', false );
		$response['errors'] = array();

		if ( ! filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) ) {
			$response['errors']['name'] = true;
		}

		if ( ! is_email( $_POST['email'] ) ) {
			$response['errors']['email'] = true;
		}

		if ( ! is_numeric( $_POST['phone'] ) ) {
			$response['errors']['phone'] = true;
		}

		if ( empty( $response['errors'] ) && ! empty( $_POST['vehicle_id'] ) ) {
			$response['response'] = esc_html__( 'Your request was sent', 'stm_vehicles_listing' );
			$response['status']   = 'success';

			//Sending Mail to admin
			add_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );

			$to = get_bloginfo( 'admin_email' );

			$args = array(
				'car'   => get_the_title( filter_var( $_POST['vehicle_id'], FILTER_SANITIZE_NUMBER_INT ) ),
				'name'  => sanitize_text_field( $_POST['name'] ),
				'email' => sanitize_email( $_POST['email'] ),
				'phone' => sanitize_text_field( $_POST['phone'] ),
			);

			$subject = apply_filters( 'get_generate_subject_view', '', 'request_price', $args );
			$body    = apply_filters( 'get_generate_template_view', '', 'request_price', $args );

			if ( 'classified' === apply_filters( 'motors_vl_get_nuxy_mod', 'dealer', 'directory_type' ) ) {
				$car_owner = get_post_meta( filter_var( $_POST['vehicle_id'], FILTER_SANITIZE_NUMBER_INT ), 'stm_car_user', true );

				if ( ! empty( $car_owner ) ) {
					$user_fields = apply_filters( 'stm_get_user_custom_fields', $car_owner );
					if ( ! empty( $user_fields ) && ! empty( $user_fields['email'] ) ) {
						wp_mail( $to, $subject, $body );
					}
				}
			} else {
				wp_mail( $to, $subject, $body, '' );
			}

			remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );
		} else {
			$response['response'] = esc_html__( 'Please fill all fields', 'stm_vehicles_listing' );
			$response['status']   = 'danger';
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_stm_ajax_get_car_price', 'stm_ajax_get_car_price' );
	add_action( 'wp_ajax_nopriv_stm_ajax_get_car_price', 'stm_ajax_get_car_price' );
}

if ( ! function_exists( 'stm_ajax_get_compare_list' ) ) {
	function stm_ajax_get_compare_list() {

		check_ajax_referer( 'stm_compare_list_nonce', 'security', false );

		$post_type   = apply_filters( 'stm_listings_post_type', 'listings' );
		$compare_ids = apply_filters( 'stm_get_compared_items', array(), $post_type );

		$str = '';
		if ( ! empty( $compare_ids ) || 0 !== count( $compare_ids ) ) :
			$args = array(
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => 3,
				'post__in'       => $compare_ids,
			);

			$compares = new WP_Query( $args );

			$str = $str . '<ul class="stm-mc-items-wrap">';
			while ( $compares->have_posts() ) :
				$compares->the_post();

				$carImg = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ) );
				$str    = $str . '<li class="stm-mc-item-wrap">';
				$str    = $str . '<div class="stm-mc-item-img">';
				$str    = $str . '<img src="' . esc_url( $carImg[0] ) . '" />';
				$str    = $str . '</div>';
				$str    = $str . '<div class="stm-mc-item-title">' . get_the_title() . '</div>';
				$str    = $str . '<div class="stm-mc-item-remove">';
				$str    = $str . '<button class="button add-to-compare-modal" data-post-type="' . $post_type . '" data-id="' . get_the_id() . '" data-title="' . get_the_title() . '">';
				$str    = $str . '<i class="fa fa-times" aria-hidden="true"></i>';
				$str    = $str . '</button>';
				$str    = $str . '</div>';
				$str    = $str . '</li>';
			endwhile;

			$str = $str . '<li class="stm-mc-item-wrap">';
			$str = $str . '<a href="' . esc_url( get_page_link( apply_filters( 'motors_vl_get_nuxy_mod', '156', 'compare_page' ) ) ) . '" class="stm-compare-btn">' . esc_html__( 'Compare', 'stm_vehicles_listing' ) . '</a>';
			$str = $str . '</li>';
			$str = $str . '</ul>';

			wp_reset_postdata();
		endif;

		$response = ( strlen( $str ) > 0 ) ? $str : 'empty';
		wp_send_json( $response );
	}

	add_action( 'wp_ajax_stm_ajax_get_compare_list', 'stm_ajax_get_compare_list' );
	add_action( 'wp_ajax_nopriv_stm_ajax_get_compare_list', 'stm_ajax_get_compare_list' );
}

// Function add to favourites
if ( ! function_exists( 'stm_ajax_add_to_favourites' ) ) {
	function stm_ajax_add_to_favourites() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response = array();
		$count    = 0;

		if ( ! empty( $_POST['car_id'] ) ) {
			$car_id      = intval( filter_var( $_POST['car_id'], FILTER_SANITIZE_NUMBER_INT ) );
			$post_status = get_post_status( $car_id );

			if ( ! $post_status ) {
				$post_status = 'deleted';
			}

			if ( is_user_logged_in() && in_array( $post_status, array( 'publish', 'pending', 'draft', 'deleted' ), true ) ) {
				$user           = wp_get_current_user();
				$user_id        = $user->ID;
				$user_added_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
				if ( empty( $user_added_fav ) ) {
					update_user_meta( $user_id, 'stm_user_favourites', $car_id );
				} else {
					$user_added_fav  = array_filter( explode( ',', $user_added_fav ) );
					$response['fil'] = $user_added_fav;
					$response['id']  = $car_id;
					if ( in_array( strval( $car_id ), $user_added_fav, true ) ) {
						$user_added_fav = array_diff( $user_added_fav, array( $car_id ) );
					} else {
						$user_added_fav[] = $car_id;
					}
					$user_added_fav = implode( ',', $user_added_fav );

					update_user_meta( $user_id, 'stm_user_favourites', $user_added_fav );
				}

				$user_added_fav    = get_the_author_meta( 'stm_user_favourites', $user_id );
				$user_added_fav    = count( array_filter( explode( ',', $user_added_fav ) ) );
				$response['count'] = intval( $user_added_fav );
			}
		}

		wp_send_json( $response );
		exit;
	}

	add_action( 'wp_ajax_stm_ajax_add_to_favourites', 'stm_ajax_add_to_favourites' );
	add_action( 'wp_ajax_nopriv_stm_ajax_add_to_favourites', 'stm_ajax_add_to_favourites' );
}

if ( ! function_exists( 'stm_ajax_get_favourites' ) ) {
	function stm_ajax_get_favourites() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$ids = array_filter(
			explode( ',', get_the_author_meta( 'stm_user_favourites', get_current_user_id() ) )
		);

		wp_send_json( $ids );
		exit;
	}

	add_action( 'wp_ajax_stm_ajax_get_favourites', 'stm_ajax_get_favourites' );
}

add_filter( 'stm_sold_status_enabled', 'motors_vl_sold_status_enabled', 10, 1 );
function motors_vl_sold_status_enabled() {
	return true;
}

if ( ! function_exists( 'stm_ajax_get_seller_phone' ) ) {
	function stm_ajax_get_seller_phone() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$phone_owner_id = isset( $_GET['phone_owner_id'] ) ? intval( $_GET['phone_owner_id'] ) : 0;
		$phone_number   = get_user_meta( $phone_owner_id, 'stm_phone', true );

		if ( isset( $_GET['listing_id'] ) && ! empty( $_GET['listing_id'] ) && 0 !== $_GET['listing_id'] ) {

			$listing_id = intval( $_GET['listing_id'] );

			$cookies = '';

			if ( empty( $_COOKIE['stm_phone_revealed'] ) ) {
				$cookies = $listing_id;
				setcookie( 'stm_phone_revealed', $cookies, time() + ( 86400 * 30 ), '/' );

				// total reveals counter
				$total_reveals = intval( get_post_meta( $listing_id, 'stm_phone_reveals', true ) );
				if ( empty( $total_reveals ) ) {
					update_post_meta( $listing_id, 'stm_phone_reveals', 1 );
				} else {
					++ $total_reveals;
					update_post_meta( $listing_id, 'stm_phone_reveals', $total_reveals );
				}

				// date based counter for statistics.
				$reveals_today = intval( get_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), true ) );
				if ( empty( $reveals_today ) ) {
					update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), 1 );
				} else {
					++ $reveals_today;
					update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), $reveals_today );
				}
			} else {
				$cookies = sanitize_text_field( $_COOKIE['stm_phone_revealed'] );
				$cookies = explode( ',', $cookies );

				if ( ! in_array( $listing_id, $cookies, true ) ) {
					$cookies[] = $listing_id;

					$cookies = implode( ',', $cookies );

					setcookie( 'stm_phone_revealed', $cookies, time() + ( 86400 * 30 ), '/' );

					// total reveals counter
					$total_reveals = intval( get_post_meta( $listing_id, 'stm_phone_reveals', true ) );
					if ( empty( $total_reveals ) ) {
						update_post_meta( $listing_id, 'stm_phone_reveals', 1 );
					} else {
						++ $total_reveals;
						update_post_meta( $listing_id, 'stm_phone_reveals', $total_reveals );
					}

					// date based counter for statistics
					$reveals_today = intval( get_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), true ) );
					if ( empty( $reveals_today ) ) {
						update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), 1 );
					} else {
						++ $reveals_today;
						update_post_meta( $listing_id, 'phone_reveals_stat_' . gmdate( 'Y-m-d' ), $reveals_today );
					}
				}
			}
		}

		wp_send_json( $phone_number );
		exit;
	}

	add_action( 'wp_ajax_stm_ajax_get_seller_phone', 'stm_ajax_get_seller_phone' );
	add_action( 'wp_ajax_nopriv_stm_ajax_get_seller_phone', 'stm_ajax_get_seller_phone' );
}

// sell car online, only for Dealership Two layout
if ( ! function_exists( 'stm_ajax_buy_car_online' ) ) {
	add_action( 'wp_ajax_stm_ajax_buy_car_online', 'stm_ajax_buy_car_online' );
	add_action( 'wp_ajax_nopriv_stm_ajax_buy_car_online', 'stm_ajax_buy_car_online' );

	function stm_ajax_buy_car_online() {
		if ( ! class_exists( 'WooCommerce' ) || ! apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' ) ) {
			wp_send_json( array( 'status' => 'Error' ) );
		}

		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response = array( 'status' => 'Error' );

		$car_id = intval( filter_var( wp_unslash( $_POST['car_id'] ), FILTER_SANITIZE_NUMBER_INT ) );
		$price  = floatval( filter_var( wp_unslash( $_POST['price'] ), FILTER_SANITIZE_NUMBER_FLOAT ) );

		if ( ! empty( $car_id ) && ! empty( $price ) ) {

			update_post_meta( $car_id, '_price', $price );
			update_post_meta( $car_id, 'is_sell_online_status', 'in_cart' );

			$checkout_url = wc_get_checkout_url() . '?add-to-cart=' . $car_id;

			$response = array(
				'status'       => 'success',
				'redirect_url' => $checkout_url,
			);

			wp_send_json( $response );
		}

		wp_send_json( $response );
	}
}

//Trade in form ajax
if ( ! function_exists( 'handle_stm_trade_in_form' ) ) {
	function handle_stm_trade_in_form() {
		check_ajax_referer( 'stm_trade_in_nonce', 'trade_in_wpnonce' );

		$recaptcha_enabled    = apply_filters( 'motors_vl_get_nuxy_mod', 0, 'enable_recaptcha' );
		$recaptcha_secret_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'recaptcha_secret_key' );
		$stm_errors           = array();

		if ( $recaptcha_enabled && isset( $_POST['g-recaptcha-response'] ) ) {
			if ( ! stm_motors_check_recaptcha( $recaptcha_secret_key, sanitize_text_field( $_POST['g-recaptcha-response'] ) ) ) {
				$stm_errors['recaptcha_error'] = esc_html__( 'Please prove you\'re not a robot', 'stm_vehicles_listing' );
			}
		}

		if ( ! empty( $stm_errors ) ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => implode( '', $stm_errors ),
				)
			);
		}

		$files           = array();
		$stm_urls        = '';
		$files_to_delete = array();
		$images_name     = array();

		if ( ! empty( $_FILES ) ) {
			foreach ( $_FILES as $file ) {
				if ( is_array( $file ) && isset( $file['tmp_name'] ) && ! empty( $file['tmp_name'] ) ) {
					$attachment_id     = apply_filters( 'stm_upload_user_file', false, $file );
					$files_to_delete[] = $attachment_id;
					if ( $attachment_id ) {
						$file_path = get_attached_file( $attachment_id );
						if ( file_exists( $file_path ) ) {
							$files[]       = $file_path;
							$url           = wp_get_attachment_url( $attachment_id );
							$stm_urls     .= esc_url( $url ) . '<br/>';
							$images_name[] = basename( $file['name'] );
						}
					}
				}
			}
		}

		$fields = array(
			'first_name',
			'last_name',
			'email',
			'phone',
			'car',
			'make',
			'model',
			'stm_year',
			'transmission',
			'mileage',
			'vin',
			'exterior_color',
			'interior_color',
			'exterior_condition',
			'interior_condition',
			'owner',
			'accident',
			'comments',
			'video_url',
			'image_urls' => implode( ', ', $images_name ),
		);

		$args = array();
		foreach ( $fields as $field => $value ) {
			if ( 'image_urls' === $field ) {
				$args[ $field ] = $value;
			} else {
				$args[ $value ] = isset( $_POST[ $value ] ) ? sanitize_text_field( $_POST[ $value ] ) : '';
			}
		}

		$body = apply_filters( 'get_generate_template_view', '', 'trade_in', $args );

		if ( ! empty( $body ) ) {
			$to = get_bloginfo( 'admin_email' );

			$subject_type = is_singular( apply_filters( 'stm_listings_post_type', 'listings' ) ) ? 'trade_in' : 'sell_a_car';
			$subject      = apply_filters( 'get_generate_subject_view', '', $subject_type, $args );

			$stm_blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$wp_email     = 'wordpress@' . preg_replace( '#^www\.#', '', strtolower( $_SERVER['SERVER_NAME'] ) );

			$headers = array(
				'From: ' . $stm_blogname . ' <' . $wp_email . '>',
				'Content-Type: text/html; charset=UTF-8',
			);

			$mail_sent = wp_mail( $to, $subject, $body, $headers, $files );

			if ( $mail_sent ) {
				$response = array(
					'success' => true,
					'message' => __( 'Form submitted successfully!', 'stm_vehicles_listing' ),
					'data'    => $_POST,
				);
				foreach ( $files_to_delete as $file ) {
					wp_delete_attachment( $file, true );
				}
			} else {
				$response = array(
					'success' => false,
					'message' => __( 'Failed to send email', 'stm_vehicles_listing' ),
				);
			}
		} else {
			$response = array(
				'success' => false,
				'message' => __( 'No content to send', 'stm_vehicles_listing' ),
			);
		}

		wp_send_json( $response );
	}
}

add_action( 'wp_ajax_stm_trade_in_form', 'handle_stm_trade_in_form' );
add_action( 'wp_ajax_nopriv_stm_trade_in_form', 'handle_stm_trade_in_form' );

if ( ! function_exists( 'stm_ajax_add_test_drive' ) ) {
	//Ajax request test drive
	function stm_ajax_add_test_drive() {
		check_ajax_referer( 'stm_add_test_drive_nonce', 'security', false );
		$response['errors'] = array();

		if ( ! filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) ) {
			$response['response']       = esc_html__( 'Please fill all fields', 'stm_vehicles_listing' );
			$response['errors']['name'] = true;
		}
		if ( ! is_email( $_POST['email'] ) ) {
			$response['response']        = esc_html__( 'Please enter correct email', 'stm_vehicles_listing' );
			$response['errors']['email'] = true;
		}
		if ( ! is_numeric( $_POST['phone'] ) ) {
			$response['response']        = esc_html__( 'Please enter correct phone number', 'stm_vehicles_listing' );
			$response['errors']['phone'] = true;
		}
		if ( empty( $_POST['date'] ) ) {
			$response['response']       = esc_html__( 'Please fill all fields', 'stm_vehicles_listing' );
			$response['errors']['date'] = true;
		}

		if ( ! filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) && ! is_email( $_POST['email'] ) && ! is_numeric( $_POST['phone'] ) && empty( $_POST['date'] ) ) {
			$response['response'] = esc_html__( 'Please fill all fields', 'stm_vehicles_listing' );
		}

		if ( empty( $response['errors'] ) && ! empty( $_POST['vehicle_id'] ) ) {
			$vehicle_id                = intval( $_POST['vehicle_id'] );
			$test_drive['post_title']  = esc_html__( 'New request for test drive', 'stm_vehicles_listing' ) . ' ' . get_the_title( $vehicle_id );
			$test_drive['post_type']   = 'test_drive_request';
			$test_drive['post_status'] = 'draft';
			$test_drive_id             = wp_insert_post( $test_drive );
			update_post_meta( $test_drive_id, 'name', sanitize_text_field( $_POST['name'] ) );
			update_post_meta( $test_drive_id, 'email', sanitize_email( $_POST['email'] ) );
			update_post_meta( $test_drive_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
			update_post_meta( $test_drive_id, 'date', sanitize_text_field( $_POST['date'] ) );
			$response['response'] = esc_html__( 'Your request was sent', 'stm_vehicles_listing' );
			$response['status']   = 'success';

			//Sending Mail to admin
			add_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );

			$to      = get_bloginfo( 'admin_email' );
			$subject = esc_html__( 'Request for a test drive', 'stm_vehicles_listing' ) . ' ' . get_the_title( $vehicle_id );
			$body    = esc_html__( 'Name - ', 'stm_vehicles_listing' ) . esc_html( $_POST['name'] ) . '<br/>';
			$body   .= esc_html__( 'Email - ', 'stm_vehicles_listing' ) . esc_html( $_POST['email'] ) . '<br/>';
			$body   .= esc_html__( 'Phone - ', 'stm_vehicles_listing' ) . esc_html( $_POST['phone'] ) . '<br/>';
			$body   .= esc_html__( 'Date - ', 'stm_vehicles_listing' ) . esc_html( $_POST['date'] ) . '<br/>';

			wp_mail( $to, $subject, $body );

			$car_owner = get_post_meta( $vehicle_id, 'stm_car_user', true );
			if ( ! empty( $car_owner ) ) {
				$user_fields = stm_get_user_custom_fields( $car_owner );
				if ( ! empty( $user_fields ) && ! empty( $user_fields['email'] ) ) {
					wp_mail( $user_fields['email'], $subject, $body );
				}
			}

			remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );

		} else {
			$response['status'] = 'danger';
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_stm_ajax_add_test_drive', 'stm_ajax_add_test_drive' );
	add_action( 'wp_ajax_nopriv_stm_ajax_add_test_drive', 'stm_ajax_add_test_drive' );
}

// Media upload limit.
if ( ! function_exists( 'stm_filter_media_upload_size' ) ) {
	function stm_filter_media_upload_size( $size ) {
		$size = apply_filters( 'motors_vl_get_nuxy_mod', '4000', 'user_image_size_limit' ) * 1024;

		return $size;
	}

	add_filter( 'stm_listing_media_upload_size', 'stm_filter_media_upload_size', 100 );
}

if ( ! function_exists( 'stm_load_dealers_list' ) ) {
	function mvl_load_dealers_list() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response = array();

		$per_page = 12;

		$remove_button = '';
		$new_offset    = 0;

		if ( ! empty( $_GET['offset'] ) ) {
			$offset = intval( $_GET['offset'] );
		}

		if ( ! empty( $offset ) ) {
			$dealers = \MotorsVehiclesListing\User\UserController::get_dealers_data( array(), $offset, $per_page );
			if ( 'show' === $dealers['button'] ) {
				$new_offset = $offset + $per_page;
			} else {
				$remove_button = 'hide';
			}
			if ( ! empty( $dealers['users'] ) ) {
				ob_start();
				$user_list = $dealers['users'];
				if ( ! empty( $user_list ) ) {
					foreach ( $user_list as $user ) {
						apply_filters( 'stm_get_single_dealer', '', $user );
					}
				}
				$response['user_html'] = ob_get_clean();
			}
		}

		$response['remove']     = $remove_button;
		$response['new_offset'] = $new_offset;

		wp_send_json( $response );
		exit;
	}

	add_action( 'wp_ajax_stm_load_dealers_list', 'mvl_load_dealers_list' );
	add_action( 'wp_ajax_nopriv_stm_load_dealers_list', 'mvl_load_dealers_list' );
}

function stm_sort_listings_callback() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'Unauthorized access' );
	}

	if ( ! isset( $_POST['sort_by'] ) || ! isset( $_POST['user_id'] ) ) {
		wp_send_json_error( 'Invalid request' );
	}

	$sort_by         = sanitize_text_field( $_POST['sort_by'] );
	$user_id         = intval( $_POST['user_id'] );
	$current_user_id = get_current_user_id();

	if ( $user_id !== $current_user_id && ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Access denied' );
	}

	$page           = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
	$posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 6;
	$offset         = $posts_per_page * ( $page - 1 );
	$status         = 'any';
	if ( 'pending' === $sort_by ) {
		$status = 'pending';
	} elseif ( 'draft' === $sort_by ) {
		$status = 'draft';
	}
	$query = stm_user_listings_query( $user_id, $status, $posts_per_page, false, $offset );
	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<div class="stm_listing_edit_car <?php echo esc_attr( get_post_status( get_the_id() ) ); ?>">
				<?php do_action( 'stm_listings_load_template', 'listing-cars/listing-list-directory-edit-loop' ); ?>
			</div>
			<?php
		}
		wp_reset_postdata();
	} else {
		echo wp_kses_post( '<h4 class="stm-seller-title">' . __( 'No listings found.', 'stm_vehicles_listing' ) . '</h4>' );
	}
	$listings_html = ob_get_clean();
	ob_start();
	$total_pages = $query->max_num_pages;
	if ( $total_pages > 1 ) {
		echo wp_kses_post(
			paginate_links(
				array(
					'type'      => 'list',
					'format'    => '?page=%#%',
					'current'   => $page,
					'total'     => $total_pages,
					'prev_text' => '<i class="fas fa-angle-left"></i>',
					'next_text' => '<i class="fas fa-angle-right"></i>',
				)
			)
		);
	}
	$pagination_html = ob_get_clean();

	$show_more_button = ( $query->found_posts > $posts_per_page && $page < $total_pages );

	wp_send_json_success(
		array(
			'listings_html'    => $listings_html,
			'pagination_html'  => $pagination_html,
			'total_pages'      => $total_pages,
			'current_page'     => $page,
			'show_more_button' => $show_more_button,
		)
	);
	wp_die();
}

add_action( 'wp_ajax_stm_sort_listings', 'stm_sort_listings_callback' );

function stm_validate_password() {
	check_ajax_referer( 'stm_security_nonce', 'security' );

	$password = isset( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '';
	$response = array(
		'valid'   => strlen( $password ) >= 8,
		'message' => strlen( $password ) >= 8 ? '' : esc_html__( 'Password must be at least 8 characters long', 'stm_vehicles_listing' ),
	);

	wp_send_json( $response );
}

add_action( 'wp_ajax_stm_validate_password', 'stm_validate_password' );
add_action( 'wp_ajax_nopriv_stm_validate_password', 'stm_validate_password' );

/*WP MAIL FUNC*/
if ( ! function_exists( 'mvl_set_mail_html_content_type' ) ) {
	function mvl_set_mail_html_content_type() {
		return 'text/html';
	}

	add_action( 'mvl_set_html_content_type', 'mvl_set_mail_html_content_type' );
}

if ( ! function_exists( 'mvl_wp_mail' ) ) {
	function mvl_wp_mail( $to, $subject, $body, $headers ) {
		add_filter( 'wp_mail_content_type', 'mvl_set_mail_html_content_type' );
		wp_mail( $to, $subject, $body, $headers );
		remove_filter( 'wp_mail_content_type', 'mvl_set_mail_html_content_type' );
	}

	add_action( 'mvl_wp_mail', 'mvl_wp_mail', 10, 4 );
}

if ( ! function_exists( 'mvl_restore_password' ) ) {
	// Ajax filter cars remove unfiltered cars
	function mvl_restore_password() {
		check_ajax_referer( 'stm_security_nonce', 'security' );

		$response = array();

		$errors = array();

		if ( empty( $_POST['stm_user_login'] ) ) {
			$errors['stm_user_login'] = true;
		} else {
			$username = sanitize_text_field( $_POST['stm_user_login'] );
		}

		$stm_link_send_to = '';

		if ( ! empty( $_POST['stm_link_send_to'] ) ) {
			$stm_link_send_to = sanitize_text_field( $_POST['stm_link_send_to'] );
		}

		$login_page = apply_filters( 'motors_vl_get_nuxy_mod', 1718, 'login_page' );
		$page_link  = get_permalink( $login_page );
		if ( $page_link ) {
			$stm_link_send_to = $page_link;
		}

		$demo = apply_filters( 'stm_site_demo_mode', false );

		if ( $demo ) {
			$errors['demo'] = true;
		}

		if ( empty( $errors ) ) {
			if ( filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
				$user = get_user_by( 'email', $username );
			} else {
				$user = get_user_by( 'login', $username );
			}

			if ( ! $user ) {
				$response['message'] = esc_html__( 'User not found', 'motors' );
			} else {

				$hash    = apply_filters( 'stm_media_random_affix', 20 );
				$user_id = $user->ID;

				$stm_link_send_to = add_query_arg(
					array(
						'user_id'    => $user_id,
						'hash_check' => $hash,
					),
					$stm_link_send_to
				);

				update_user_meta( $user_id, 'stm_lost_password_hash', $hash );

				/*Sending mail*/
				$to = $user->data->user_email;

				$args = array(
					'password_content' => $stm_link_send_to,
				);

				$subject = apply_filters( 'get_generate_subject_view', '', 'password_recovery', $args );
				$body    = apply_filters( 'get_generate_template_view', '', 'password_recovery', $args );

				do_action( 'mvl_wp_mail', $to, $subject, $body, '' );

				$response['message'] = esc_html__( 'Instructions send on your email', 'motors' );
			}
		} else {
			if ( $demo ) {
				$response['message'] = esc_html__( 'Site is on demo mode.', 'motors' );
			} else {
				$response['message'] = esc_html__( 'Please fill required fields', 'motors' );
			}
		}

		$response['errors'] = $errors;

		wp_send_json( $response );
		exit;
	}

	add_action( 'wp_ajax_mvl_restore_password', 'mvl_restore_password' );
	add_action( 'wp_ajax_nopriv_mvl_restore_password', 'mvl_restore_password' );
}

/**
 * Flush rewrite rules on plugin update. Delete this after 1.4.75 version.
 */

function stm_flush_rewrite_rules_on_update() {
	if ( defined( 'STM_LISTINGS_V' ) && STM_LISTINGS_V === '1.4.76' && ! get_option( 'stm_re_flush_rewrite_rules' ) ) {
		flush_rewrite_rules();
		update_option( 'stm_re_flush_rewrite_rules', true );
	}
}

add_action( 'init', 'stm_flush_rewrite_rules_on_update' );

function stm_sync_car_user_meta( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return;
	}

	$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );

	if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() ) {
		$post_types = array_merge( $post_types, STMMultiListing::stm_get_listing_type_slugs() );
	}

	if ( ! in_array( $post->post_type, $post_types, true ) ) {
		return;
	}

	$stm_car_user = get_post_meta( $post_id, 'stm_car_user', true );

	if ( empty( $stm_car_user ) || intval( $stm_car_user ) !== intval( $post->post_author ) ) {
		update_post_meta( $post_id, 'stm_car_user', $post->post_author );
	}
}

add_action( 'save_post', 'stm_sync_car_user_meta', 20, 1 );
