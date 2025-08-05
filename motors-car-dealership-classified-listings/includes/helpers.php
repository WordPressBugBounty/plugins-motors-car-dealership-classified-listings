<?php
use MotorsVehiclesListing\Plugin\MVL_Const;
use MotorsVehiclesListing\Plugin\PluginOptions;

/**
 * Use for get value by option key
 **/
add_filter( 'motors_vl_get_nuxy_mod', 'motors_vl_get_nuxy_mod', 10, 3 );
function motors_vl_get_nuxy_mod( $default = '', $opt_name = '', $return_default = false ) {
	$options = PluginOptions::getInstance();
	$options = $options::$options_map;

	if ( is_mvl_pro() && in_array(
		$opt_name,
		array(
			'enable_location',
			'enable_distance_search',
			'distance_measure_unit',
			'distance_search',
			'recommend_items_empty_result',
			'recommend_distance_measure_unit',
			'recommend_distance_search',
		),
		true
	)
	) {
		$location_settings = get_option( 'mvl_location_settings', '' );

		if ( ! empty( $location_settings ) ) {
			if ( 'enable_location' === $opt_name ) {
				return true;
			}

			if ( 'recommend_distance_measure_unit' === $opt_name || 'recommend_distance_search' === $opt_name ) {
				return ( 'recommend_distance_measure_unit' === $opt_name ) ? $location_settings['distance_measure_unit'] : $location_settings['distance_search'];
			}

			if ( isset( $location_settings[ $opt_name ] ) ) {
				return $location_settings[ $opt_name ];
			}
		}
	}

	$value_or_false = ( isset( $options[ $opt_name ] ) ) ? $options[ $opt_name ] : $default;

	if ( has_filter( 'wpcfto_motors_' . $opt_name ) ) {
		return apply_filters( 'wpcfto_motors_' . $opt_name, $value_or_false, $opt_name );
	}

	if ( is_bool( $value_or_false ) || ! empty( $value_or_false ) ) {
		return $value_or_false;
	}

	if ( $return_default ) {
		return $default;
	}

	return false;
}

/**
 * Needed for demo import set content
 */
function mvl_set_wpcfto_mod( $opt_name, $value ) {
	$settings_name = MVL_Const::MVL_PLUGIN_OPT_NAME;
	$options       = get_option( $settings_name, array() );

	if ( ! empty( $options[ $opt_name ] ) ) {
		$options[ $opt_name ] = apply_filters( 'mvl_set_option_' . $opt_name, $value );
	}

	update_option( $settings_name, $options );
}

add_filter( 'mvl_get_nuxy_img_src', 'mvl_get_nuxy_img_src', 10, 3 );
function mvl_get_nuxy_img_src( $default, $opt_name, $size = 'full' ) {
	$image = motors_vl_get_nuxy_mod( $default, $opt_name, true );
	if ( is_numeric( $image ) && $image > 0 ) {
		$image = wp_get_attachment_image_url( $image, $size );

		// always return original full size image for logo.
		if ( 'logo' === $opt_name && is_string( $image ) && preg_match( '/-\d+[Xx]\d+\./', $image ) ) {
			$image = preg_replace( '/-\d+[Xx]\d+\./', '.', $image );
		}
	}

	return $image;
}

add_filter( 'mvl_get_nuxy_icon', 'mvl_get_nuxy_icon', 100, 3 );
function mvl_get_nuxy_icon( $option_name, $default_icon, $other_classes = '' ) {
	$icon_array = motors_vl_get_nuxy_mod( $default_icon, $option_name, false );

	$style_array = array();

	// if color is not default.
	if ( ! empty( $icon_array['color'] ) && '#000' !== $icon_array['color'] ) {
		$style_array['color'] = $icon_array['color'];
	}

	// if icon size is not default.
	if ( ! empty( $icon_array['size'] ) && 15 !== $icon_array['size'] ) {
		$style_array['size'] = $icon_array['size'];
	}

	// if icon is set.
	if ( $icon_array && ! empty( $icon_array['icon'] ) ) {
		$default_icon = $icon_array['icon'];
	}

	// style string.
	$style_string = '';
	if ( ! empty( $style_array['color'] ) ) {
		$style_string .= 'color: ' . $style_array['color'] . '; ';
	}
	if ( ! empty( $style_array['size'] ) ) {
		$style_string .= 'font-size: ' . $style_array['size'] . 'px;';
	}

	$icon_element = '<i class="' . esc_attr( $default_icon . ' ' . $other_classes ) . '" style="' . esc_attr( $style_string ) . '"></i>';

	return $icon_element;
}

add_filter( 'mvl_get_nuxy_icon_class', 'mvl_get_nuxy_icon_class', 2, 2 );
if ( ! function_exists( 'mvl_get_nuxy_icon_class' ) ) {
	function mvl_get_nuxy_icon_class( $default_value, $option_name ) {
		$icon_array = motors_vl_get_nuxy_mod( array(), $option_name, false );
		return isset( $icon_array['icon'] ) ? $icon_array['icon'] : $default_value;
	}
}

add_filter( 'mvl_get_nuxy_icon_css_vars', 'mvl_get_nuxy_icon_css_vars', 2, 2 );
if ( ! function_exists( 'mvl_get_nuxy_icon_css_vars' ) ) {
	function mvl_get_nuxy_icon_css_vars( $default_value, $option_name ) {
		$icon           = motors_vl_get_nuxy_mod( array(), $option_name, false );
		$css_vars       = array();
		$css_properties = array(
			'size'  => array(
				'property' => 'font-size',
				'unit'     => 'px',
			),
			'color' => array(),
		);

		foreach ( $css_properties as $css_property_key => $css_property_data ) {
			$css_property_name = isset( $css_property_data['property'] ) ? $css_property_data['property'] : $css_property_key;
			$css_property_unit = isset( $css_property_data['unit'] ) ? $css_property_data['unit'] : '';
			if ( isset( $icon[ $css_property_key ] ) ) {
				$css_vars[] = '--' . str_replace( '_', '-', $option_name ) . '-' . $css_property_name . ':' . $icon[ $css_property_key ] . $css_property_unit . ';';
			}
		}
		return $css_vars;
	}
}

if ( ! function_exists( 'stm_add_to_any_shortcode' ) ) {
	function stm_add_to_any_shortcode( $postId ) {
		return do_shortcode( '[addtoany]' );
	}

	add_filter( 'stm_add_to_any_shortcode', 'stm_add_to_any_shortcode' );
}

function mvl_nuxy_sidebars() {
	$sidebars = array(
		'no_sidebar' => esc_html__( 'Without sidebar', 'stm_vehicles_listing' ),
		'default'    => esc_html__( 'Primary sidebar', 'stm_vehicles_listing' ),
	);

	$query = get_posts(
		array(
			'post_type'      => 'sidebar',
			'posts_per_page' => - 1,
		)
	);

	if ( $query ) {
		foreach ( $query as $post ) {
			$sidebars[ $post->ID ] = get_the_title( $post->ID );
		}
	}

	$sidebars = apply_filters( 'mvl_nuxy_sidebars_list', $sidebars );

	return $sidebars;
}

function mvl_nuxy_pages_list( $include_default_value = true, $default_value = '' ) {
	if ( $include_default_value ) {
		if ( $default_value ) {
			$post_types[] = $default_value;
		} else {
			$post_types[] = __( 'Choose page', 'stm_vehicles_listing' );
		}
	}

	$query = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => - 1,
		)
	);

	if ( $query ) {
		foreach ( $query as $post ) {
			$post_types[ $post->ID ] = get_the_title( $post->ID );
		}
	}

	return $post_types;
}

function mvl_nuxy_positions() {
	$positions = array(
		'left'  => esc_html__( 'Left', 'stm_vehicles_listing' ),
		'right' => esc_html__( 'Right', 'stm_vehicles_listing' ),
	);

	return apply_filters( 'motors_filter_position', $positions );
}

function mvl_nuxy_sort_options() {
	$options = array();

	if ( function_exists( 'stm_listings_attributes' ) ) {
		$numeric_filters = array_keys(
			stm_listings_attributes(
				array(
					'where'  => array(
						'numeric' => true,
					),
					'key_by' => 'slug',
				)
			)
		);

		if ( ! empty( $numeric_filters ) ) {
			foreach ( $numeric_filters as $tax_name ) {
				$tax = get_taxonomy( $tax_name );
				if ( $tax ) {
					$options[ $tax->name ] = $tax->labels->singular_name;
				}
			}
		}
	}

	return $options;
}

function mvl_nuxy_sortby() {
	$sorts = array(
		'date_high' => esc_html__( 'Date: newest first', 'stm_vehicles_listing' ),
		'date_low'  => esc_html__( 'Date: oldest first', 'stm_vehicles_listing' ),
	);

	return $sorts;
}

add_filter( 'mvl_nuxy_sortby', 'mvl_nuxy_sortby' );

function mvl_print_settings( $settings_name = null ) {
	if ( empty( $settings_name ) ) {
		$settings_name = MVL_Const::MVL_PLUGIN_OPT_NAME;
	}

	echo wp_json_encode( get_option( $settings_name ), true );
	exit;
}

function mvl_modify_key( $key ) {
	return strtolower( str_replace( array( ' ', '/' ), '_', $key ) );
}

function is_mvl_addon_enabled( $addon ) {
	$enabled_addons = get_option( 'motors_vl_addons' );
	return defined( 'STM_LISTINGS_PRO_PATH' ) && isset( $enabled_addons[ $addon ] ) && 'on' === $enabled_addons[ $addon ];
}

add_filter( 'wp_kses_allowed_html', 'mvl_wp_kses_allowed_html' );
function mvl_wp_kses_allowed_html( $allowed_html ) {
	$allowed_atts = array(
		'align'       => array(),
		'class'       => array(),
		'type'        => array(),
		'id'          => array(),
		'dir'         => array(),
		'lang'        => array(),
		'style'       => array(),
		'xml:lang'    => array(),
		'src'         => array(),
		'alt'         => array(),
		'href'        => array(),
		'rel'         => array(),
		'rev'         => array(),
		'target'      => array(),
		'novalidate'  => array(),
		'value'       => array(),
		'name'        => array(),
		'tabindex'    => array(),
		'action'      => array(),
		'method'      => array(),
		'for'         => array(),
		'width'       => array(),
		'height'      => array(),
		'data'        => array(),
		'title'       => array(),
		'placeholder' => array(),
		'selected'    => array(),
	);

	$allowed_html['select']             = $allowed_atts;
	$allowed_html['input']              = $allowed_atts;
	$allowed_html['option']             = $allowed_atts;
	$allowed_html['option']['selected'] = array();

	$allowed_html['img'] = array(
		'src'      => true,
		'srcset'   => true,
		'sizes'    => true,
		'class'    => true,
		'id'       => true,
		'width'    => true,
		'height'   => true,
		'alt'      => true,
		'loading'  => true,
		'decoding' => true,
	);

	return $allowed_html;
}

function mvl_wp_kses_allowed_html_in_content( $allowed_html ) {
	$allowed_atts = array(
		'align'       => array(),
		'class'       => array(),
		'type'        => array(),
		'id'          => array(),
		'dir'         => array(),
		'lang'        => array(),
		'style'       => array(),
		'xml:lang'    => array(),
		'src'         => array(),
		'alt'         => array(),
		'href'        => array(),
		'rel'         => array(),
		'rev'         => array(),
		'target'      => array(),
		'novalidate'  => array(),
		'value'       => array(),
		'name'        => array(),
		'tabindex'    => array(),
		'action'      => array(),
		'method'      => array(),
		'for'         => array(),
		'width'       => array(),
		'height'      => array(),
		'data'        => array(),
		'title'       => array(),
		'placeholder' => array(),
		'selected'    => array(),
	);

	$allowed_html['select']             = $allowed_atts;
	$allowed_html['input']              = $allowed_atts;
	$allowed_html['option']             = $allowed_atts;
	$allowed_html['option']['selected'] = array();
	$allowed_html['span']               = $allowed_atts;
	$allowed_html['div']                = $allowed_atts;
	$allowed_html['p']                  = $allowed_atts;
	$allowed_html['a']                  = $allowed_atts;
	$allowed_html['i']                  = $allowed_atts;
	$allowed_html['iframe']             = $allowed_atts;

	$allowed_html['img'] = array(
		'src'      => true,
		'srcset'   => true,
		'sizes'    => true,
		'class'    => true,
		'id'       => true,
		'width'    => true,
		'height'   => true,
		'alt'      => true,
		'loading'  => true,
		'decoding' => true,
	);

	return $allowed_html;
}

if ( ! function_exists( 'stm_get_sort_options_array' ) ) {
	function stm_get_sort_options_array() {

		$display_multilisting_sorts = false;

		if ( stm_is_multilisting() ) {
			$current_slug = STMMultiListing::stm_get_current_listing_slug();
			if ( ! empty( $current_slug ) ) {
				$display_multilisting_sorts = true;
			}
		}

		if ( $display_multilisting_sorts ) {
			$ml        = new STMMultiListing();
			$sort_args = multilisting_default_sortby( $current_slug );

			$custom_inventory = $ml->stm_get_listing_type_settings( 'inventory_custom_settings', $current_slug );

			if ( false === $custom_inventory ) {
				$enabled_options = array( 'date_high', 'date_low' );
			} else {
				$enabled_options = apply_filters( 'stm_prefix_given_sort_options', $ml->stm_get_listing_type_settings( 'multilisting_sort_options', $current_slug ) );
			}
		} else {
			$sort_args       = apply_filters( 'mvl_nuxy_sortby', array() );
			$sort_options    = apply_filters( 'motors_vl_get_nuxy_mod', array(), 'sort_options' );
			$enabled_options = apply_filters( 'stm_prefix_given_sort_options', $sort_options );
		}

		if ( ! empty( $enabled_options ) ) {
			foreach ( $sort_args as $slug => $label ) {
				if ( ! in_array( $slug, $enabled_options, true ) ) {
					unset( $sort_args[ $slug ] );
				}
			}
		}

		return $sort_args ?? array();
	}

	add_filter( 'stm_get_sort_options_array', 'stm_get_sort_options_array' );
}

if ( ! function_exists( 'stm_get_sort_options_html' ) ) {
	function stm_get_sort_options_html() {

		$html = '';

		$default_sort       = apply_filters( 'stm_get_default_sort_option', 'date_high' );
		$currently_selected = apply_filters( 'stm_listings_input', $default_sort, 'sort_order' );
		$sort_args          = apply_filters( 'stm_get_sort_options_array', array() );

		if ( sort_distance_nearby() ) {
			$sort_args['distance_nearby'] = esc_html__( 'Distance : nearby', 'stm_vehicles_listing' );
			$currently_selected           = 'distance_nearby';
		}

		foreach ( $sort_args as $slug => $label ) {
			$selected = ( $slug === $currently_selected ) ? ' selected' : '';
			$html    .= '<option value="' . $slug . '" ' . $selected . '>' . apply_filters( 'stm_listings_dynamic_string_translation', $label, 'Sort options - ' . $label ) . '</option>';

		}

		return $html;
	}

	add_filter( 'stm_get_sort_options_html', 'stm_get_sort_options_html' );
}

// get compare listings.
if ( ! function_exists( 'stm_get_compared_items' ) ) {
	function stm_get_compared_items( $listing_type = null ) {
		$post_types     = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );
		$compared_items = array();
		$prefix         = apply_filters( 'stm_compare_cookie_name_prefix', '' );

		if ( empty( $listing_type ) ) {
			foreach ( $post_types as $post_type ) {
				if ( ! empty( $_COOKIE[ $prefix . $post_type ] ) && is_array( $_COOKIE[ $prefix . $post_type ] ) ) {
					foreach ( $_COOKIE[ $prefix . $post_type ] as $key => $listing_id ) {
						if ( 'publish' !== get_post_status( $listing_id ) ) {
							do_action( 'stm_remove_compared_item', $listing_id );
						}
					}

					$compared_items = array_merge( $compared_items, $_COOKIE[ $prefix . $post_type ] );
				}
			}
		} elseif ( ! empty( $listing_type ) && in_array( $listing_type, $post_types, true ) ) {
			if ( ! empty( $_COOKIE[ $prefix . $listing_type ] ) && is_array( $_COOKIE[ $prefix . $listing_type ] ) ) {
				foreach ( $_COOKIE[ $prefix . $listing_type ] as $key => $listing_id ) {
					if ( 'publish' !== get_post_status( $listing_id ) ) {
						do_action( 'stm_remove_compared_item', $listing_id );
					}
				}

				$compared_items = $_COOKIE[ $prefix . $listing_type ];
			}
		}

		return array_values( $compared_items );
	}

	function stm_motors_get_compared_items( $compared_items, $listing_type = null ) {
		return stm_get_compared_items( $listing_type );
	}

	add_filter( 'stm_get_compared_items', 'stm_motors_get_compared_items', 10, 2 );
}

// compare cookie name
if ( ! function_exists( 'stm_compare_cookie_name_prefix' ) ) {
	function stm_compare_cookie_name_prefix() {
		return 'stm' . get_current_blog_id() . '_compare_';
	}

	add_filter( 'stm_compare_cookie_name_prefix', 'stm_compare_cookie_name_prefix' );
}

// remove listing from compare list.
if ( ! function_exists( 'stm_remove_compared_item' ) ) {
	function stm_remove_compared_item( $item_id = null ) {
		if ( ! empty( $item_id ) && is_numeric( $item_id ) ) {
			$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );
			$post_type  = get_post_type( $item_id );

			if ( in_array( $post_type, $post_types, true ) ) {
				$prefix = apply_filters( 'stm_compare_cookie_name_prefix', '' );
				if ( ! empty( $_COOKIE[ $prefix . $post_type ] ) && is_array( $_COOKIE[ $prefix . $post_type ] ) && in_array( strval( $item_id ), $_COOKIE[ $prefix . $post_type ], true ) ) {
					$status = setcookie( $prefix . $post_type . '[' . $item_id . ']', '', time() - 3600, '/' );
					unset( $_COOKIE[ $prefix . $post_type ][ $item_id ] );

					return $status;
				}
			}
		}

		return false;
	}

	add_action( 'stm_remove_compared_item', 'stm_remove_compared_item', 10, 1 );
}

// we've made the listing price field dynamic, this function checks if the given option is the price field.
if ( ! function_exists( 'stm_is_listing_price_field' ) ) {
	add_filter( 'stm_is_listing_price_field', 'stm_is_listing_price_field', 10, 2 );
	function stm_is_listing_price_field( $default, $field = false ) {

		if ( false === $field ) {
			return false;
		}

		// check the default listing type price field.
		if ( 'price' === $field ) {
			return true;
		}

		// check for multilisting fields.
		if ( stm_is_multilisting() ) {
			$opts  = array();
			$slugs = STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				foreach ( $slugs as $slug ) {
					$type_options = get_option( "stm_{$slug}_options", array() );
					if ( ! empty( $type_options ) ) {
						$opts = array_merge( $opts, $type_options );
					}
				}

				if ( ! empty( $opts ) ) {
					$arr_key = array_search( $field, array_column( $opts, 'slug' ), true );
					if ( false !== $arr_key ) {
						if ( ! empty( $opts[ $arr_key ]['listing_price_field'] ) && 1 === $opts[ $arr_key ]['listing_price_field'] ) {
							return true;
						}
					}
				}
			}
		}

		return false;
	}
}

// check if Motors Theme is active
if ( ! function_exists( 'stm_is_motors_theme' ) ) {
	function stm_is_motors_theme() {
		if ( defined( 'STM_THEME_NAME' ) && 'Motors' === STM_THEME_NAME ) {
			return true;
		}

		return wp_get_theme()->get( 'Name' ) === 'Motors' || wp_get_theme()->get( 'Name' ) === 'Motors Child' || wp_get_theme()->get( 'Name' ) === 'Motors - Child Theme';
	}

	add_filter( 'stm_is_motors_theme', 'stm_is_motors_theme', 1000, 1 );
}

// check if Motors Starter Theme is active
if ( ! function_exists( 'mvl_is_motors_starter_theme' ) ) {
	function mvl_is_motors_starter_theme() {
		if ( defined( 'MOTORS_STARTER_THEME_DIR' ) ) {
			return true;
		}

		$starter_theme_names = array(
			'Motors Starter Theme'               => true,
			'Motors Starter Theme Child'         => true,
			'Motors Starter Theme - Child Theme' => true,
		);

		$current_theme_name = wp_get_theme()->get( 'Name' );

		return isset( $starter_theme_names[ $current_theme_name ] );
	}

	add_filter( 'stm_is_motors_theme', 'stm_is_motors_theme', 1000, 1 );
}

// get gallery image URLs for interactive hoverable gallery
if ( ! function_exists( 'stm_get_hoverable_thumbs' ) ) {
	function stm_get_hoverable_thumbs( $returned_value, $listing_id, $thumb_size = 'thumbnail' ) {
		$ids   = array_unique( (array) get_post_meta( $listing_id, 'gallery', true ) );
		$count = 0;

		// push featured image id
		if ( has_post_thumbnail( $listing_id ) && ! in_array( get_post_thumbnail_id( $listing_id ), $ids, true ) ) {
			array_unshift( $ids, get_post_thumbnail_id( $listing_id ) );
		}

		$returned_value = array(
			'gallery'   => array(),
			'ids'       => array(),
			'remaining' => 0,
		);

		$ids = array_filter( $ids );

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $attachment_id ) {
				// only first five images!
				if ( $count >= 5 ) {
					continue;
				}

				$img = wp_get_attachment_image_url( $attachment_id, $thumb_size );

				if ( ! empty( $img ) ) {
					array_push( $returned_value['gallery'], $img );
					array_push( $returned_value['ids'], $attachment_id );
					$count ++;
				}
			}
		}

		// get remaining count of gallery images
		$remaining                   = count( $ids ) - count( $returned_value['ids'] );
		$returned_value['remaining'] = ( 0 <= $remaining ) ? $remaining : 0;

		return $returned_value;
	}

	add_filter( 'stm_get_hoverable_thumbs', 'stm_get_hoverable_thumbs', 10, 3 );
}

function motors_vl_body_class( $classes ) {
	global $wp_query;

	if ( apply_filters( 'motors_vl_get_nuxy_mod', '', 'gallery_hover_interaction' ) ) {
		$classes[] = 'stm-hoverable-interactive-galleries';
	}

	if ( ! apply_filters( 'stm_is_motors_theme', false ) ) {
		$classes[] = 'stm-vl-plugin-pure';
	}

	if ( ! is_user_logged_in() ) {
		$classes[] = 'stm-user-not-logged-in';
	}

	return $classes;
}

add_filter( 'body_class', 'motors_vl_body_class' );

if ( ! function_exists( 'stm_check_if_car_imported' ) ) {
	function stm_check_if_car_imported( $id ) {
		$return = false;
		if ( ! empty( $id ) ) {
			$has_id = get_post_meta( $id, 'automanager_id', true );
			if ( ! empty( $has_id ) ) {
				$return = true;
			} else {
				$return = false;
			}
		}

		return $return;
	}
}

if ( ! function_exists( 'stm_display_script_sort' ) ) {
	function stm_display_script_sort( $tax_info ) {
		?>
		case '<?php echo esc_attr( $tax_info['slug'] . '_low' ); ?>':
		<?php
		$slug      = sanitize_title( str_replace( '-', '_', $tax_info['slug'] ) );
		$sort_asc  = 'true';
		$sort_desc = 'false';
		?>
		$container.isotope({
		getSortData: {
		<?php echo esc_attr( $slug ); ?>: function( itemElem ) {
		<?php if ( ! empty( $tax_info['numeric'] ) && $tax_info['numeric'] ) : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = '0';
			}
			if (typeof(<?php echo esc_attr( $slug ); ?>) == 'string') {
				<?php echo esc_attr( $slug ); ?> = <?php echo esc_attr( $slug ); ?>.replace(/[^0-9.]/g, '');
			}

			return parseFloat(<?php echo esc_attr( $slug ); ?>);
		<?php else : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = 'n/a';
			}

			return <?php echo esc_attr( $slug ); ?>;
		<?php endif; ?>

		}
		},
		sortBy: '<?php echo esc_attr( $slug ); ?>',
		sortAscending: <?php echo esc_attr( $sort_asc ); ?>
		});
		break
		case '<?php echo esc_attr( $tax_info['slug'] . '_high' ); ?>':
		$container.isotope({
		getSortData: {
		<?php echo esc_attr( $slug ); ?>: function( itemElem ) {
		<?php if ( ! empty( $tax_info['numeric'] ) && $tax_info['numeric'] ) : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = '0';
			}
			if (typeof(<?php echo esc_attr( $slug ); ?>) == 'string') {
				<?php echo esc_attr( $slug ); ?> = <?php echo esc_attr( $slug ); ?>.replace(/[^0-9.]/g, '');
			}

			return parseFloat(<?php echo esc_attr( $slug ); ?>);
		<?php else : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = 'n/a';
			}

			return <?php echo esc_attr( $slug ); ?>;
		<?php endif; ?>

		}
		},
		sortBy: '<?php echo esc_attr( $tax_info['slug'] ); ?>',
		sortAscending: <?php echo esc_attr( $sort_desc ); ?>
		});
		break
		<?php
	}
}

if ( ! function_exists( 'stm_data_binding' ) ) {
	function stm_data_binding( $data = array(), $allowAll = false, $is_add_car = false ) {
		$attributes = apply_filters( 'stm_get_car_parent_exist', array() );
		$bind_tax   = array();
		$depends    = array();
		foreach ( $attributes as $attr ) {

			$parent = $attr['listing_taxonomy_parent'];
			$slug   = $attr['slug'];

			$depends[] = array(
				'parent' => $parent,
				'dep'    => $slug,
			);

			if ( ! isset( $bind_tax[ $parent ] ) ) {
				$bind_tax[ $parent ] = array();
			}

			$bind_tax[ $slug ] = array(
				'dependency' => $parent,
				'allowAll'   => $allowAll,
				'options'    => array(),
			);

			/** @var WP_Term $term */

			$terms = apply_filters( 'stm_get_category_by_slug_all', array(), $slug, $is_add_car, true, $attr );

			foreach ( $terms as $term ) {
				$deps = array_values( array_filter( (array) get_term_meta( $term->term_id, 'stm_parent' ) ) );

				$bind_tax[ $slug ]['options'][] = array(
					'value' => $term->slug,
					'label' => $term->name,
					'count' => $term->count,
					'deps'  => $deps,
				);
			}
		}

		$sort_dependencies = array();
		$dependency_count  = count( $depends );
		for ( $q = 0; $q < $dependency_count; $q ++ ) {
			if ( 0 === $q ) {
				$sort_dependencies[] = $depends[ $q ]['parent'];
				$sort_dependencies[] = $depends[ $q ]['dep'];
			} else {
				if ( in_array( $depends[ $q ]['dep'], $sort_dependencies, true ) ) {
					array_splice( $sort_dependencies, array_search( $depends[ $q ]['dep'], $sort_dependencies, true ), 0, $depends[ $q ]['parent'] );
				} elseif ( in_array( $depends[ $q ]['parent'], $sort_dependencies, true ) ) {
					array_splice( $sort_dependencies, array_search( $depends[ $q ]['parent'], $sort_dependencies, true ) + 1, 0, $depends[ $q ]['dep'] );
				} elseif ( ! in_array( $depends[ $q ]['parent'], $sort_dependencies, true ) ) {
					array_splice( $sort_dependencies, 0, 0, $depends[ $q ]['parent'] );
					array_splice( $sort_dependencies, count( $sort_dependencies ), 0, $depends[ $q ]['dep'] );
				}
			}
		}

		$new_tax_bind = array();

		foreach ( $sort_dependencies as $val ) {
			$new_tax_bind[ $val ] = $bind_tax[ $val ];
		}

		return apply_filters( 'stm_data_binding', $new_tax_bind );
	}

	add_filter( 'stm_data_binding_func', 'stm_data_binding', 10, 3 );
}

if ( ! function_exists( 'stm_get_all_listing_attributes' ) ) {
	function stm_get_all_listing_attributes( $default, $filter = 'all' ) {
		$multilisting_attrs = array();
		$attributes         = array();

		// default attributes
		$default_attrs = get_option( 'stm_vehicle_listing_options', array() );

		// get multilisting attributes, if MLT is active
		if ( stm_is_multilisting() && ( 'all' === $filter || 'multilisting' === $filter ) ) {
			$slugs = STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				foreach ( $slugs as $slug ) {
					$type_options = get_option( "stm_{$slug}_options", array() );
					if ( ! empty( $type_options ) ) {
						$multilisting_attrs = array_merge( $multilisting_attrs, $type_options );
					}
				}
			}
		}

		if ( 'all' === $filter ) {
			$attributes = array_merge( $default_attrs, $multilisting_attrs );
		} elseif ( 'multilisting' === $filter ) {
			$attributes = $multilisting_attrs;
		} else {
			$attributes = $default_attrs;
		}

		return $attributes;
	}

	add_filter( 'stm_get_all_listing_attributes', 'stm_get_all_listing_attributes' );
}

if ( ! function_exists( 'stm_upload_user_file' ) ) {
	function stm_upload_user_file( $default, $file = array() ) {
		require_once ABSPATH . 'wp-admin/includes/admin.php';

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

		if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
			return $default;
		} else {
			$filename   = $file_return['file'];
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $file_return['url'],
			);

			$attachment_id = wp_insert_attachment( $attachment, $file_return['file'] );
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			if ( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}

		return $default;
	}

	add_filter( 'stm_upload_user_file', 'stm_upload_user_file', 10, 2 );
}

if ( ! function_exists( 'stm_similar_cars' ) ) {
	function stm_similar_cars( $default, $similar_taxonomies = array(), $postsnum = 3, $exclude = array() ) {
		$tax_query = array();
		$taxes     = ( count( $similar_taxonomies ) === 0 ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'stm_similar_query' ) : $similar_taxonomies;
		$query     = array(
			'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
			'post_status'    => 'publish',
			'posts_per_page' => $postsnum,
			'post__not_in'   => array_merge( array( get_the_ID() ), $exclude ),
		);

		if ( ! empty( $taxes ) ) {
			if ( count( $similar_taxonomies ) === 0 ) {
				$taxes = array_filter( array_map( 'trim', explode( ',', $taxes ) ) );
			}

			$attributes = stm_listings_attributes( array( 'key_by' => 'slug' ) );

			foreach ( $taxes as $tax ) {
				if ( ! isset( $attributes[ $tax ] ) || ! empty( $attributes[ $tax ]['numeric'] ) ) {
					continue;
				}

				$terms = get_the_terms( get_the_ID(), $tax );
				if ( ! is_array( $terms ) ) {
					continue;
				}

				$tax_query[] = array(
					'taxonomy' => esc_attr( $tax ),
					'field'    => 'slug',
					'terms'    => wp_list_pluck( $terms, 'slug' ),
				);
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query['tax_query'] = array( 'relation' => 'OR' ) + $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		return new WP_Query( apply_filters( 'stm_similar_cars_query', $query ) );
	}

	add_filter( 'stm_similar_cars', 'stm_similar_cars', 10, 4 );
}

if ( ! function_exists( 'stm_set_html_content_type_mail' ) ) {
	function stm_set_html_content_type_mail() {
		return 'text/html';
	}
}

if ( ! function_exists( 'motors_listing_filter_get_selects' ) && defined( 'STM_LISTINGS' ) ) {
	function motors_listing_filter_get_selects( $select_strings, $tab_name = '', $words = array(), $show_amount = 'yes' ) {
		if ( ! empty( $select_strings ) ) {
			$select_strings = explode( ',', $select_strings );

			if ( ! empty( $select_strings ) ) {
				$i       = 0;
				$output  = '';
				$output .= '<div class="row">';
				foreach ( $select_strings as $select_string ) {

					if ( empty( $select_string ) ) {
						continue;
					}

					$select_string = trim( $select_string );

					$taxonomy_info = stm_get_taxonomies_with_type( $select_string );

					$output .= '<div class="stm-select-col">';
					if ( ! empty( $taxonomy_info['slider_in_tabs'] ) && $taxonomy_info['slider_in_tabs'] ) {
						$args = array(
							'orderby'    => 'name',
							'order'      => 'ASC',
							'hide_empty' => false,
							'fields'     => 'all',
						);

						$for_range = array();

						$terms = get_terms( $select_string, $args );

						if ( ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								$for_range[] = intval( $term->name );
							}

							sort( $for_range );
						}

						ob_start();
						do_action(
							'stm_listings_load_template',
							'filter/types/vc_price',
							array(
								'taxonomy'    => $select_string,
								'options'     => $for_range,
								'tab_name'    => 'all',
								'label'       => $taxonomy_info['single_name'],
								'slider_step' => ( ! empty( $taxonomy_info['slider_step'] ) ) ? $taxonomy_info['slider_step'] : 10,
							)
						);

						$output .= ob_get_clean();

						// price.
					} elseif ( 'price' === $select_string ) {
						$args = array(
							'orderby'    => 'name',
							'order'      => 'ASC',
							'hide_empty' => false,
							'fields'     => 'all',
						);

						$prices = array();

						$terms = get_terms( $select_string, $args );

						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								$prices[] = intval( $term->name );
							}

							sort( $prices );
						}

						$number_string = '';

						if ( ! empty( $words['number_prefix'] ) ) {
							$number_string .= $words['number_prefix'] . ' ';
						} else {
							$number_string = esc_html__( 'Max', 'stm_vehicles_listing' ) . ' ';
						}

						$number_string .= apply_filters( 'stm_listings_dynamic_string_translation', stm_get_name_by_slug( $select_string ), 'Select Text' );

						if ( ! empty( $words['number_affix'] ) ) {
							$number_string .= ' ' . $words['number_affix'];
						}

						$output .= '<select class="stm-filter-ajax-disabled-field" name="max_' . $select_string . '" data-class="stm_select_overflowed price">';
						$output .= '<option value="">' . $number_string . '</option>';
						if ( ! empty( $terms ) ) {
							foreach ( $prices as $price ) {
								$selected = '';
								if ( apply_filters( 'stm_is_equipment', false ) ) {
									$selected = ( isset( $_GET[ $select_string ] ) && $_GET[ $select_string ] === $price ) ? 'selected' : '';
								}

								$output .= '<option value="' . esc_attr( $price ) . '" ' . $selected . '>' . apply_filters( 'stm_filter_price_view', '', $price ) . '</option>';
							}
						}
						$output .= '</select>';
					} else {
						// If numeric.
						if ( ! empty( $taxonomy_info['numeric'] ) && $taxonomy_info['numeric'] ) {
							$args    = array(
								'orderby'    => 'name',
								'order'      => 'ASC',
								'hide_empty' => false,
								'fields'     => 'all',
							);
							$numbers = array();

							$terms = get_terms( $select_string, $args );

							$select_main = '';
							if ( ! empty( $words['number_prefix'] ) ) {
								$select_main .= $words['number_prefix'] . ' ';
							} else {
								$select_main .= esc_html__( 'Choose', 'stm_vehicles_listing' ) . ' ';
							}

							$select_main .= apply_filters( 'stm_listings_dynamic_string_translation', stm_get_name_by_slug( $select_string ), 'Option text' );

							if ( ! empty( $words['number_affix'] ) ) {
								$select_main .= ' ' . $words['number_affix'];
							}

							if ( ! empty( $terms ) ) {
								foreach ( $terms as $term ) {
									$numbers[] = intval( $term->name );
								}
							}
							sort( $numbers );

							if ( ! empty( $numbers ) ) {
								$output .= '<select name="' . $select_string . '" data-class="stm_select_overflowed numeric" data-sel-type="' . esc_attr( $select_string ) . '">';
								$output .= '<option value="">' . $select_main . '</option>';
								foreach ( $numbers as $number_key => $number_value ) {

									$selected = '';

									if ( 0 === $number_key ) {
										if ( apply_filters( 'stm_is_equipment', false ) ) {
											$selected = ( isset( $_GET[ $select_string ] ) && sprintf( '< %s', esc_attr( $number_value ) ) === $_GET[ $select_string ] ) ? 'selected' : '';
										}

										$output .= '<option value="' . sprintf( '< %s', esc_attr( $number_value ) ) . '" ' . $selected . '>< ' . $number_value . '</option>';
									} elseif ( count( $numbers ) - 1 === $number_key ) {
										if ( apply_filters( 'stm_is_equipment', false ) ) {
											$selected = ( isset( $_GET[ $select_string ] ) && sprintf( '> %s', esc_attr( $number_value ) ) === $_GET[ $select_string ] ) ? 'selected' : '';
										}

										$output .= '<option value="' . sprintf( '> %s', esc_attr( $number_value ) ) . '" ' . $selected . '>> ' . $number_value . '</option>';
									} else {
										$option_value = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;
										$option_name  = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;

										if ( apply_filters( 'stm_is_equipment', false ) ) {
											$selected = ( isset( $_GET[ $select_string ] ) && $_GET[ $select_string ] === $option_value ) ? 'selected' : '';
										}

										$output .= '<option value="' . esc_attr( $option_value ) . '" ' . $selected . '> ' . $option_name . '</option>';
									}
								}
								$output .= '<input type="hidden" name="min_' . $select_string . '"/>';
								$output .= '<input type="hidden" name="max_' . $select_string . '"/>';
								$output .= '</select>';
							}
							// other default values.
						} else {
							if ( ! empty( $taxonomy_info['listing_taxonomy_parent'] ) ) {
								$terms = array();
							} else {
								$terms = apply_filters( 'stm_get_category_by_slug_all', array(), $select_string, false, $taxonomy_info );
							}

							$select_main = '';
							if ( ! empty( $words['select_prefix'] ) ) {
								$select_main .= $words['select_prefix'] . ' ';
							} else {
								$select_main .= esc_html__( 'Choose', 'stm_vehicles_listing' ) . ' ';
							}

							$select_main .= apply_filters( 'stm_listings_dynamic_string_translation', stm_get_name_by_slug( $select_string ), 'Option select text' );

							if ( ! empty( $words['select_affix'] ) ) {
								$select_main .= ' ' . $words['select_affix'];
							}

							$output .= '<div class="stm-ajax-reloadable">';
							$output .= '<select name="' . esc_attr( $select_string ) . '" data-class="stm_select_overflowed other">';
							$output .= '<option value="">' . $select_main . '</option>';
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $term ) {

									if ( ! $term || is_array( $term ) && ! empty( $term['invalid_taxonomy'] ) ) {
										continue;
									}

									$selected = '';
									if ( apply_filters( 'stm_is_equipment', false ) ) {
										$selected = ( isset( $_GET[ $select_string ] ) && $_GET[ $select_string ] === $term->slug ) ? 'selected' : '';
									}

									if ( 'yes' === $show_amount ) {
										$output .= '<option value="' . esc_attr( $term->slug ) . '" ' . $selected . '>' . $term->name . ' (' . $term->count . ') </option>';
									} else {
										$output .= '<option value="' . esc_attr( $term->slug ) . '" ' . $selected . '>' . $term->name . ' </option>';
									}
								}
							}
							$output .= '</select>';
							$output .= '</div>';
						}
					}
					$output .= '</div>';
					$i ++;
				}

				$output .= '</div>'; // row.

				if ( ! empty( $output ) ) {
					echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}
	}

	add_filter( 'motors_listing_filter_get_selects', 'motors_listing_filter_get_selects', 10, 6 );
}

if ( ! function_exists( 'stm_get_car_medias' ) ) {
	function stm_get_car_medias( $default, $post_id = '' ) {
		if ( ! empty( $post_id ) ) {

			$image_limit = '';

			if ( apply_filters( 'stm_pricing_enabled', false ) ) {
				$user_added = get_post_meta( $post_id, 'stm_car_user', true );
				if ( ! empty( $user_added ) ) {
					$limits      = apply_filters(
						'stm_get_post_limits',
						array(
							'premoderation' => true,
							'posts_allowed' => 0,
							'posts'         => 0,
							'images'        => 0,
							'role'          => 'user',
						),
						$user_added
					);
					$image_limit = $limits['images'];
				}
			}
			$car_media = array();

			// Photo.
			$car_photos         = array();
			$car_gallery        = get_post_meta( $post_id, 'gallery', true );
			$car_videos_posters = get_post_meta( $post_id, 'gallery_videos_posters', true );

			if ( has_post_thumbnail( $post_id ) ) {
				$car_photos[] = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
			}

			if ( ! empty( $car_gallery ) ) {
				$i = 0;
				foreach ( $car_gallery as $car_gallery_image ) {
					if ( empty( $image_limit ) ) {
						if ( wp_get_attachment_url( $car_gallery_image ) ) {
							$car_photos[] = wp_get_attachment_url( $car_gallery_image );
						}
					} else {
						$i ++;
						if ( $i < $image_limit ) {
							if ( wp_get_attachment_url( $car_gallery_image ) ) {
								$car_photos[] = wp_get_attachment_url( $car_gallery_image );
							}
						}
					}
				}
			}

			$car_photos = array_unique( $car_photos );

			$car_media['car_photos']       = $car_photos;
			$car_media['car_photos_count'] = count( $car_photos );

			// Video.
			$car_video      = array();
			$car_video_main = get_post_meta( $post_id, 'gallery_video', true );
			$car_videos     = get_post_meta( $post_id, 'gallery_videos', true );

			if ( ! empty( $car_video_main ) ) {
				$car_video[] = $car_video_main;
			}

			if ( ! empty( $car_videos ) ) {
				foreach ( $car_videos as $car_video_single ) {
					if ( ! empty( $car_video_single ) ) {
						$car_video[] = $car_video_single;
					}
				}
			}

			$car_media['car_videos']         = $car_video;
			$car_media['car_videos_posters'] = $car_videos_posters;
			$car_media['car_videos_count']   = count( $car_video );

			return $car_media;
		}
	}

	add_filter( 'stm_get_car_medias', 'stm_get_car_medias', 10, 2 );
}

if ( ! function_exists( 'motors_vl_dealer_logo_placeholder' ) ) {
	function motors_vl_dealer_logo_placeholder() {
		return STM_LISTINGS_URL . '/assets/images/empty_dealer_logo.png';
	}

	add_filter( 'motors_vl_dealer_logo_placeholder', 'motors_vl_dealer_logo_placeholder' );
}

if ( ! function_exists( 'stm_account_current_page' ) ) {
	function stm_account_current_page() {
		$page = 'inventory';

		if ( isset( $_GET['page'] ) ) {
			$page = sanitize_text_field( $_GET['page'] );
		}

		if ( ! empty( $_GET['my_favourites'] ) ) {
			$page = 'favourite';
		}

		if ( ! empty( $_GET['my_settings'] ) ) {
			$page = 'settings';
		}

		if ( ! empty( $_GET['become_dealer'] ) ) {
			$page = 'become-dealer';
		}

		return $page;
	}

	add_filter( 'stm_account_current_page', 'stm_account_current_page' );
}

if ( ! function_exists( 'mvl_payment_enabled' ) ) {
	function mvl_payment_enabled() {
		$paypal_options = array(
			'enabled' => false,
		);

		$paypal_email    = apply_filters( 'motors_vl_get_nuxy_mod', '', 'paypal_email' );
		$paypal_currency = apply_filters( 'motors_vl_get_nuxy_mod', 'USD', 'paypal_currency' );
		$paypal_mode     = apply_filters( 'motors_vl_get_nuxy_mod', 'sandbox', 'paypal_mode' );
		$membership_cost = apply_filters( 'motors_vl_get_nuxy_mod', '', 'membership_cost' );

		if ( ! empty( $paypal_email ) && ! empty( $paypal_currency ) && ! empty( $paypal_mode ) && ! empty( $membership_cost ) ) {
			$paypal_options['enabled'] = true;
		}

		$paypal_options['email']    = $paypal_email;
		$paypal_options['currency'] = $paypal_currency;
		$paypal_options['mode']     = $paypal_mode;
		$paypal_options['price']    = $membership_cost;

		return $paypal_options;
	}

	add_filter( 'mvl_payment_enabled', 'mvl_payment_enabled' );
}

if ( ! function_exists( 'mvl_generate_payment' ) ) {
	function mvl_generate_payment() {
		$user = wp_get_current_user();

		if ( ! empty( $user->ID ) ) {

			$user_id = $user->ID;

			$return['result'] = true;

			$base = 'https://' . apply_filters( 'mvl_paypal_url', '' ) . '/cgi-bin/webscr';

			$return_url = add_query_arg( array( 'become_dealer' => 1 ), apply_filters( 'stm_get_author_link', $user_id ) );

			$url_args = array(
				'cmd'           => '_xclick',
				'business'      => apply_filters( 'motors_vl_get_nuxy_mod', '', 'paypal_email' ),
				'item_name'     => $user->data->user_login,
				'item_number'   => $user_id,
				'amount'        => apply_filters( 'motors_vl_get_nuxy_mod', '', 'membership_cost' ),
				'no_shipping'   => '1',
				'no_note'       => '1',
				'currency_code' => apply_filters( 'motors_vl_get_nuxy_mod', 'USD', 'paypal_currency' ),
				'bn'            => 'PP%2dBuyNowBF',
				'charset'       => 'UTF%2d8',
				'invoice'       => $user_id,
				'return'        => $return_url,
				'rm'            => '2',
				'notify_url'    => home_url(),
			);

			$return = add_query_arg( $url_args, $base );
		}

		return $return;
	}

	add_filter( 'mvl_generate_payment', 'mvl_generate_payment' );
}

if ( ! function_exists( 'mvl_paypal_url' ) ) {
	function mvl_paypal_url() {
		$paypal_mode = apply_filters( 'motors_vl_get_nuxy_mod', 'sandbox', 'paypal_mode' );
		$paypal_url  = ( 'live' === $paypal_mode ) ? 'www.paypal.com' : 'www.sandbox.paypal.com';

		return $paypal_url;
	}

	add_filter( 'mvl_paypal_url', 'mvl_paypal_url' );
}

if ( ! function_exists( 'mvl_check_payment' ) ) {

	function mvl_check_payment( $data ) {
		if ( ! empty( $data['invoice'] ) ) {

			$invoice = $data['invoice'];

			$req = 'cmd=_notify-validate';

			foreach ( $data as $key => $value ) {
				$value = rawurlencode( stripslashes( $value ) );
				$req  .= "&$key=$value";
			}

			echo 'https://' . esc_url( apply_filters( 'mvl_paypal_url', '' ) ) . '/cgi-bin/webscr';

			$ch = curl_init( 'https://' . apply_filters( 'mvl_paypal_url', '' ) . '/cgi-bin/webscr' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_init
			curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_POST, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $req ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_FORBID_REUSE, 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_SSLVERSION, 6 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Connection: Close' ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_setopt

			$res = curl_exec( $ch ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_exec

			if ( empty( $res ) ) {
				echo( 'Got ' . esc_html( curl_error( $ch ) ) . ' when processing IPN data' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_error
				curl_close( $ch ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_close
				return false;
			}

			curl_close( $ch ); // phpcs:ignore WordPress.WP.AlternativeFunctions.curl_curl_close

			if ( 0 === strcmp( $res, 'VERIFIED' ) ) {

				update_user_meta( intval( $invoice ), 'stm_payment_status', 'completed' );

				$member_admin_email_subject = esc_html__( 'New Payment received', 'stm_vehicles_listing' );
				$member_admin_email_message = esc_html__( 'User paid for submission. User ID:', 'stm_vehicles_listing' ) . ' ' . $invoice;

				do_action( 'stm_set_html_content_type' );

				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
				$wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', strtolower( apply_filters( 'stm_get_global_server_val', 'SERVER_NAME' ) ) );
				$headers  = 'From: ' . $blogname . ' <' . $wp_email . '>' . "\r\n";

				do_action( 'stm_wp_mail_files', get_bloginfo( 'admin_email' ), $member_admin_email_subject, nl2br( $member_admin_email_message ), $headers );
			}
		}
	}

	add_filter( 'mvl_check_payment', 'mvl_check_payment' );
}

//this function is used to check if the value is empty except zero
add_filter( 'is_empty_value', 'is_empty_value' );
function is_empty_value( $value ) {
	$value = floatval( $value );
	if ( 0 === $value ) {
		return false;
	}
}

// Disable the regeneration of fonts
add_filter(
	'wpcfto_enable_regenerate_fonts',
	function () {
		return false;
	},
	99
);

// Disable auto-sizing responsive images
add_filter(
	'wp_img_tag_add_auto_sizes',
	function() {
		return false;
	},
);

function delear_public_page_pagination_action( $query, $page, $posts_per_page, $sort_by ) {
	echo paginate_links( //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		array(
			'type'           => 'list',
			'format'         => '?page=%#%',
			'current'        => $page,
			'total'          => $query->max_num_pages,
			'posts_per_page' => $posts_per_page,
			'prev_text'      => '<i class="fas fa-angle-left"></i>',
			'next_text'      => '<i class="fas fa-angle-right"></i>',
			'add_args'       => array( 'sort_by' => $sort_by ),
		)
	);
}
add_action( 'delear_public_page_pagination', 'delear_public_page_pagination_action', 4, 99 );


if ( ! function_exists( 'mvl_get_dealer_list_page' ) ) {
	function mvl_get_dealer_list_page() {
		$dealer_list_page = apply_filters( 'motors_vl_get_nuxy_mod', 2173, 'dealer_list_page' );

		$dealer_list_page = apply_filters( 'stm_motors_wpml_is_page', $dealer_list_page );
		$link             = get_permalink( $dealer_list_page );

		return $link;
	}

	add_filter( 'mvl_get_dealer_list_page', 'mvl_get_dealer_list_page' );
}

if ( ! function_exists( 'motors_skin_name' ) ) {
	function motors_get_skin_name() {
		return get_option( MVL_Const::ACTIVE_SKIN_OPT_NAME, 'free' );
	}
}

if ( ! function_exists( 'motors_get_demo_data' ) ) {
	function motors_get_demo_data( $filename ) {
		$file_url    = motors_get_demo_file_url( $filename );
		$remote_args = array();
		if ( defined( 'STM_DEV_MODE' ) && STM_DEV_MODE ) {
			$remote_args = array(
				'sslverify' => false,
			);
		}
		$demo_data = wp_remote_get( $file_url, $remote_args );
		return apply_filters( 'motors_demo_data', $demo_data, $filename, $file_url );
	}
	add_filter( 'motors_get_demo_data', 'motors_get_demo_data' );
}

if ( ! function_exists( 'motors_get_demo_file_url' ) ) {
	function motors_get_demo_file_url( $filename ) {
		$skin_name = motors_get_skin_name();
		if ( defined( 'STM_DEV_MODE' ) && STM_DEV_MODE && defined( 'MOTORS_STARTER_THEME_TEMPLATE_URI' ) ) {
			$file_url = MOTORS_STARTER_THEME_TEMPLATE_URI . '/includes/demo/' . $skin_name . '/' . $filename;
		} else {
			$file_url = 'https://motors-plugin.stylemixthemes.com/starter-theme-demo/' . $skin_name . '/' . $filename;
		}
		return apply_filters( 'motors_demo_file_url', $file_url, $filename );
	}
}

if ( ! function_exists( 'motors_get_demo_file_path' ) ) {
	function motors_get_demo_file_path( $filename ) {
		$skin_name = motors_get_skin_name();
		$filepath  = MOTORS_STARTER_THEME_TEMPLATE_DIR . '/includes/demo/' . $skin_name . '/' . $filename;
		return apply_filters( 'motors_demo_file_path', $filepath, $filename );
	}
}


function mvl_dealer_gmap( $lat, $lng ) {
	do_action( 'stm_google_places_script', 'enqueue', true );
	if ( ! empty( apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_pin' ) ) ) {
		$pin_url = wp_get_attachment_url( apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_pin' ) );
	} else {
		$pin_url = STM_LISTINGS_URL . '/assets/elementor/img/marker-listing-two.png';
	}
	?>

	<div id="stm-dealer-gmap"></div>
	<script>
		jQuery(document).ready(function ($) {
			var center, map;

			function init() {
				center = new google.maps.LatLng(<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>);
				var mapOptions = {
					zoom: 15,
					center: center,
					fullscreenControl: true,
					scrollwheel: false
				};
				var mapElement = document.getElementById('stm-dealer-gmap');
				map = new google.maps.Map(mapElement, mapOptions);
				var marker = new google.maps.Marker({
					position: center,
					icon: '<?php echo esc_url( $pin_url ); ?>',
					map: map
				});
			}

			$(window).on('resize', function () {
				if (typeof map != 'undefined' && typeof center != 'undefined') {
					setTimeout(function () {
						map.setCenter(center);
					}, 1000);
				}
			});

			document.body.addEventListener('stm_gmap_api_loaded', init, false);
		});
	</script>
	<?php
}

add_action( 'mvl_dealer_gmap_hook', 'mvl_dealer_gmap', 10, 2 );

if ( ! function_exists( 'stm_get_filter_badges' ) ) {
	function stm_get_filter_badges() {
		$attributes    = stm_listings_filter_terms();
		$filter_badges = array();
		foreach ( $attributes as $attribute => $terms ) {
			/*Text field*/
			$options = apply_filters( 'stm_vl_get_all_by_slug', array(), $attribute );

			/*Field affix like mi, km or another defined by user*/
			$affix = '';
			if ( ! empty( $options['number_field_affix'] ) ) {
				$affix = apply_filters( 'stm_dynamic_string_translation', $options['number_field_affix'], 'Affix text' );
			}

			$numeric_skins = array(
				'skin_2',
				'skin_3',
			);

			/*Slider badge*/
			if ( ( ! empty( $options['slider'] ) && $options['slider'] ) || ( ! empty( $options['numeric_skins'] ) && in_array( $options['numeric_skins'], $numeric_skins, true ) ) ) {
				if ( isset( $_GET[ 'max_' . $attribute ] ) && ( isset( $_GET[ 'min_' . $attribute ] ) ) ) {
					reset( $terms );
					$start_value = key( $terms );
					end( $terms );
					$end_value = key( $terms );

					if ( 'price' === $attribute ) {
						if ( 0 === $start_value ) {
							$start_value = apply_filters( 'stm_filter_price_view', '', 0 );
							$value       = $start_value . ' - ' . apply_filters( 'stm_filter_price_view', '', apply_filters( 'stm_listings_input', $end_value, 'max_' . $attribute ) );
						} else {
							$value = apply_filters( 'stm_filter_price_view', '', apply_filters( 'stm_listings_input', $start_value, 'min_' . $attribute ) ) . ' - ' . apply_filters( 'stm_filter_price_view', '', apply_filters( 'stm_listings_input', $end_value, 'max_' . $attribute ) );
						}
					} else {
						$value = apply_filters( 'stm_listings_input', $start_value, 'min_' . $attribute ) . ' - ' . apply_filters( 'stm_listings_input', $end_value, 'max_' . $attribute ) . ' ' . $affix;
					}

					$filter_badges[ $attribute ] = array(
						'slug'   => $attribute,
						'name'   => stm_get_name_by_slug( $attribute ),
						'type'   => ( ! empty( $options['slider'] ) && $options['slider'] ) ? 'slider' : 'number',
						'value'  => $value,
						'origin' => array( 'min_' . $attribute, 'max_' . $attribute ),
					);

					$filter_badges[ $attribute ]['url'] = stm_get_filter_badge_url( $filter_badges[ $attribute ] );
				}
				/*Badge of number field*/
			} elseif ( ! empty( $options['numeric'] ) && $options['numeric'] ) {
				if ( ! empty( $_GET[ $attribute ] ) ) {
					$filter_badges[ $attribute ] = array(
						'slug'   => $attribute,
						'name'   => stm_get_name_by_slug( $attribute ),
						'value'  => sanitize_text_field( $_GET[ $attribute ] ) . ' ' . $affix,
						'type'   => 'number',
						'origin' => array( $attribute ),
					);

					$filter_badges[ $attribute ]['url'] = stm_get_filter_badge_url( $filter_badges[ $attribute ] );
				}
				/*Badge of text field*/
			} else {
				if ( ! empty( $_GET[ $attribute ] ) || ( class_exists( \MotorsVehiclesListing\Features\FriendlyUrl::class ) && ! empty( \MotorsVehiclesListing\Features\FriendlyUrl::$for_filter[ $attribute ] ) ) ) {

					$selected = ( ! empty( $_GET[ $attribute ] ) ) ? $_GET[ $attribute ] : '';

					if ( class_exists( \MotorsVehiclesListing\Features\FriendlyUrl::class ) && ! empty( \MotorsVehiclesListing\Features\FriendlyUrl::$for_filter[ $attribute ] ) ) {
						$selected = \MotorsVehiclesListing\Features\FriendlyUrl::$for_filter[ $attribute ];
					}

					$txt = '';
					if ( is_array( $selected ) ) {
						foreach ( $selected as $k => $val ) {
							if ( ! isset( $terms[ $val ] ) ) {
								continue;
							}

							$txt .= $terms[ $val ]->name;
							$txt .= ( count( $selected ) - 1 !== $k ) ? ', ' : '';
						}
					} else {
						$txt = ( ! empty( $terms[ $selected ] ) ) ? $terms[ $selected ]->name : '';
					}

					$filter_badges[ $attribute ]        = array(
						'slug'     => $attribute,
						'name'     => stm_get_name_by_slug( $attribute ),
						'value'    => $txt,
						'origin'   => array( $attribute ),
						'type'     => 'select',
						'multiple' => array_key_exists( 'is_multiple_select', $options ) ? $options['is_multiple_select'] : 0,
					);
					$filter_badges[ $attribute ]['url'] = stm_get_filter_badge_url( $filter_badges[ $attribute ] );
				}
			}
		}
		return $filter_badges;
	}

	add_filter( 'stm_get_filter_badges', 'stm_get_filter_badges' );
}

if ( ! function_exists( 'stm_get_filter_badge_url' ) ) {
	function stm_get_filter_badge_url( $badge_info ) {
		$remove_args   = $badge_info['origin'];
		$remove_args[] = 'ajax_action';

		return apply_filters( 'stm_get_filter_badge_url', remove_query_arg( $remove_args ), $badge_info, $remove_args );
	}
}

if ( ! function_exists( 'mvl_get_listing_certificates' ) ) {
	function mvl_get_listing_certificates( $certificates, $post_id ) {
		$certificates = array(
			array(
				'name'  => get_post_meta( $post_id, 'history', true ),
				'link'  => get_post_meta( $post_id, 'history_link', true ),
				'image' => get_post_meta( $post_id, 'certified_logo_1', true ),
			),
		);

		$certificate_2 = array(
			'name'  => get_post_meta( $post_id, 'history_2', true ),
			'link'  => get_post_meta( $post_id, 'certified_logo_2_link', true ),
			'image' => get_post_meta( $post_id, 'certified_logo_2', true ),
		);

		if ( $certificate_2['name'] || $certificate_2['link'] || $certificate_2['image'] ) {
			$certificates[] = $certificate_2;
		}

		return $certificates;
	}

	add_filter( 'mvl_get_listing_certificates', 'mvl_get_listing_certificates', 10, 2 );
}

if ( ! function_exists( 'mvl_get_listing_videos' ) ) {
	function mvl_get_listing_videos( $videos, $post_id, $check_poster_exists = false ) {
		$videos_urls = get_post_meta( $post_id, 'gallery_videos', true );
		$posters_ids = get_post_meta( $post_id, 'gallery_videos_posters', true );

		$result = array();

		if ( ! is_array( $videos_urls ) ) {
			$videos_urls = array();
		}

		if ( ! is_array( $posters_ids ) ) {
			$posters_ids = array();
		}

		foreach ( $videos_urls as $key => $video_url ) {
			if ( ! isset( $posters_ids[ $key ] ) ) {
				continue;
			}

			$poster_url = wp_get_attachment_image_url( $posters_ids[ $key ], 'full' );

			if ( ! $poster_url ) {
				continue;
			}

			$relative_path = str_replace( wp_get_upload_dir()['baseurl'], '', $poster_url );
			$absolute_path = wp_get_upload_dir()['basedir'] . $relative_path;

			if ( $check_poster_exists && ( ! file_exists( $absolute_path ) || ! $relative_path ) ) {
				continue;
			}

			$result[ $key ] = array(
				'url'        => $video_url,
				'poster_id'  => is_array( $posters_ids ) && isset( $posters_ids[ $key ] ) ? $posters_ids[ $key ] : '',
				'poster_url' => is_array( $posters_ids ) && isset( $posters_ids[ $key ] ) ? $poster_url : '',
			);
		}

		return $result;
	}

	add_filter( 'mvl_get_listing_videos', 'mvl_get_listing_videos', 10, 2 );
}
