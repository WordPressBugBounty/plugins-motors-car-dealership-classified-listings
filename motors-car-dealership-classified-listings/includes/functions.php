<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter(
	'is_core',
	function () {
		return false;
	}
);

if ( ! function_exists( 'is_mvl_pro' ) ) {
	function is_mvl_pro() {
		if ( defined( 'STM_DEV_MODE' ) && STM_DEV_MODE && defined( 'STM_LISTINGS_PRO_PATH' ) ) {
			return true;
		}

		if ( defined( 'STM_LISTINGS_PRO_PATH' ) && ( ( function_exists( 'mp_fs' ) && mp_fs()->_get_license() ) || ( function_exists( 'mvl_deal' ) && mvl_deal()->is_activated() ) ) ) {
			return true;
		}

		$active_plugins = get_option( 'active_plugins', array() );
		return in_array( 'motors-car-dealership-classified-listings-pro/motors-car-dealership-classified-listings-pro.php', $active_plugins, true );
	}

	add_filter( 'is_mvl_pro', 'is_mvl_pro' );
}

if ( ! function_exists( 'mvl_is_woocommerce_active' ) ) {
	function mvl_is_woocommerce_active() {
		$active_plugins = get_option( 'active_plugins', array() );
		return in_array( 'woocommerce/woocommerce.php', $active_plugins, true );
	}

	add_filter( 'mvl_is_woocommerce_active', 'mvl_is_woocommerce_active' );
}

if ( ! function_exists( 'is_pro_plus' ) ) {
	function is_pro_plus() {
		return defined( 'STM_LMS_PLUS_ENABLED' );
	}

	add_filter( 'is_pro_plus', 'is_pro_plus' );
}

function detect_plugin_activation( $plugin, $network_activation ) {
	update_option( 'stm_price_patched', 'updated' );
}

add_action( 'activated_plugin', 'detect_plugin_activation', 10, 2 );

if ( ! function_exists( 'stm_frontend_javascript_variables' ) ) {
	add_action( 'wp_footer', 'stm_frontend_javascript_variables' );
	function stm_frontend_javascript_variables() {
		$stm_security_nonce           = wp_create_nonce( 'stm_security_nonce' );
		$stm_media_security_nonce     = wp_create_nonce( 'stm_media_security_nonce' );
		$stm_listings_user_data_nonce = wp_create_nonce( 'stm_listings_user_data_nonce' );
		$stm_car_price_nonce          = wp_create_nonce( 'stm_car_price_nonce' );
		$stm_compare_list_nonce       = wp_create_nonce( 'stm_compare_list_nonce' );
		$stm_custom_register_nonce    = wp_create_nonce( 'stm_custom_register_nonce' );
		$stm_custom_login_nonce       = wp_create_nonce( 'stm_custom_login_nonce' );
		$stm_add_test_drive_nonce     = wp_create_nonce( 'stm_add_test_drive_nonce' );
		$stm_logout_user_nonce        = wp_create_nonce( 'stm_logout_user_nonce' );

		$compare_cookie_prefix = apply_filters( 'stm_compare_cookie_name_prefix', '' );

		$listing_types       = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );
		$compare_init_object = array();
		foreach ( $listing_types as $slug ) {
			$compare_init_object[ $slug ] = apply_filters( 'stm_get_compared_items', array(), $slug );
		}

		$allow_dealers_add_category = '';
		if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'allow_dealer_add_new_category' ) ) {
			$allow_dealers_add_category = '1';
		}
		//phpcs:disable
		?>
		<script>
            var stm_security_nonce = '<?php echo esc_js( $stm_security_nonce ); ?>';
            var stm_media_security_nonce = '<?php echo esc_js( $stm_media_security_nonce ); ?>';
            var stm_listings_user_data_nonce = '<?php echo esc_js( $stm_listings_user_data_nonce ); ?>';
            var stm_car_price_nonce = '<?php echo esc_js( $stm_car_price_nonce ); ?>';
            var stm_compare_list_nonce = '<?php echo esc_js( $stm_compare_list_nonce ); ?>';
            var stm_custom_register_nonce = '<?php echo esc_js( $stm_custom_register_nonce ); ?>';
            var stm_custom_login_nonce = '<?php echo esc_js( $stm_custom_login_nonce ); ?>';
            var stm_add_test_drive_nonce = '<?php echo esc_js( $stm_add_test_drive_nonce ); ?>';
            var stm_logout_user_nonce = '<?php echo esc_js( $stm_logout_user_nonce ); ?>';
            var cc_prefix = '<?php echo esc_js( $compare_cookie_prefix ); ?>';
            var compare_init_object = <?php echo wp_json_encode( $compare_init_object ); ?>;
            var allowDealerAddCategory = '<?php echo esc_html( $allow_dealers_add_category ); ?>';
            var noFoundSelect2 = '<?php echo esc_html__( 'No results found', 'stm_vehicles_listing' ); ?>';
		</script>
		<?php
		//phpcs:enable
	}
}

if ( ! function_exists( 'stm_get_listing_seller_note' ) ) {
	/**
	 * Get listing seller note
	 *
	 * @param $listing_id
	 *
	 * @return mixed|string
	 */
	function stm_get_listing_seller_note( $listing_id ) {
		return ( defined( 'WPB_VC_VERSION' ) ) ? get_post_meta( $listing_id, 'listing_seller_note', true ) : wp_kses_post( get_the_content( null, null, $listing_id ) );
	}

	add_filter( 'stm_get_listing_seller_note', 'stm_get_listing_seller_note' );
}

/**
 * Get filter configuration
 *
 * @param array $args
 *
 * @return array
 */
if ( ! function_exists( 'stm_listings_attributes' ) ) {
	function stm_listings_attributes( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'where'  => array(),
				'key_by' => '',
			)
		);

		$result  = array();
		$listing_type = get_post_type( get_the_ID() );
		if ( 'page' === $listing_type ) {
			$listing_type = 'listings';
		}
		$options = ( ! empty( $listing_type ) && 'listings' !== $listing_type ) ? "stm_{$listing_type}_options" : 'stm_vehicle_listing_options';
		$data    = array_filter( (array) get_option( $options ) );

		foreach ( $data as $key => $_data ) {
			$passed = true;
			foreach ( $args['where'] as $_field => $_val ) {
				if ( array_key_exists( $_field, $_data ) && boolval( $_data[ $_field ] ) !== boolval( $_val ) ) {
					$passed = false;
					break;
				}
			}

			if ( $passed ) {
				if ( $args['key_by'] ) {
					$result[ $_data[ $args['key_by'] ] ] = $_data;
				} else {
					$result[] = $_data;
				}
			}
		}

		return apply_filters( 'stm_listings_attributes', $result, $args );
	}

	add_filter( 'mvl_listings_attributes', 'stm_listings_attributes' );
}

/**
 * Get single attribute configuration by taxonomy slug
 *
 * @param $taxonomy
 *
 * @return array|mixed
 */
function stm_listings_attribute( $taxonomy ) {
	$attributes = stm_listings_attributes( array( 'key_by' => 'slug' ) );
	if ( array_key_exists( $taxonomy, $attributes ) ) {
		return $attributes[ $taxonomy ];
	}

	return array();
}

/**
 * Get all terms grouped by taxonomy for the filter
 *
 * @return array
 */
if ( ! function_exists( 'stm_listings_filter_terms' ) ) {
	function stm_listings_filter_terms( $hide_empty = false ) {
		static $terms;

		if ( isset( $terms ) ) {
			return $terms;
		}

		$args_attributes = array(
			'where'  => array( 'use_on_car_filter' => true ),
			'key_by' => 'slug',
		);

		$filters = array_keys( stm_listings_attributes( $args_attributes ) );
		$numeric = array_keys( stm_listings_attributes( wp_parse_args( array( 'where' => array( 'numeric' => true ) ), $args_attributes ) ) );

		$defaults = array(
			'hide_empty'             => $hide_empty,
			'update_term_meta_cache' => false,
		);

		$_terms = array();
		$terms  = array();

		if ( ! $hide_empty ) {
			$taxonomies = array_merge( $filters, $numeric );
		} else {
			if ( count( $numeric ) ) {
				$_terms = get_terms(
					wp_parse_args(
						array(
							'taxonomy'   => $numeric,
							'hide_empty' => false,
						),
						$defaults
					)
				);
			}

			$taxonomies = array_diff( $filters, $numeric );
		}

		$taxonomies     = apply_filters( 'stm_listings_filter_taxonomies', $taxonomies, $hide_empty );
		$terms_received = get_terms( wp_parse_args( array( 'taxonomy' => $taxonomies ), $defaults ) );
		if ( ! is_wp_error( $terms_received ) && is_array( $terms_received ) ) {
			$_terms = array_merge( $_terms, $terms_received );
		}

		foreach ( $taxonomies as $taxonomy ) {
			$terms[ $taxonomy ] = array();
		}

		foreach ( $_terms as $_term ) {
			$terms[ $_term->taxonomy ][ $_term->slug ] = $_term;
		}

		$terms = apply_filters( 'stm_listings_filter_terms', $terms );

		return $terms;
	}
}

/**
 * Drop-down options grouped by attribute for the filter
 *
 * @return array
 */
if ( ! function_exists( 'stm_listings_filter_options' ) ) {
	function stm_listings_filter_options( $hide_empty = false ) {
		static $options;

		if ( isset( $options ) ) {
			return $options;
		}

		$filters = stm_listings_attributes(
			array(
				'where'  => array( 'use_on_car_filter' => true ),
				'key_by' => 'slug',
			)
		);
		$terms   = stm_listings_filter_terms( $hide_empty );
		$options = array();

		foreach ( $terms as $tax => $_terms ) {
			$_filter         = isset( $filters[ $tax ] ) ? $filters[ $tax ] : array();
			$options[ $tax ] = _stm_listings_filter_attribute_options( $tax, $_terms );

			if ( empty( $_filter['numeric'] ) || ! empty( $_filter['use_on_car_filter_links'] ) ) {
				$_remaining = stm_listings_options_remaining( $_terms, stm_listings_query() );

				foreach ( $_terms as $_term ) {
					if ( isset( $_remaining[ $_term->term_taxonomy_id ] ) ) {
						$options[ $tax ][ $_term->slug ]['count'] = (int) $_remaining[ $_term->term_taxonomy_id ];
					} else {
						$options[ $tax ][ $_term->slug ]['count']    = 0;
						$options[ $tax ][ $_term->slug ]['disabled'] = true;
					}
				}
			}
		}

		$options = apply_filters( 'stm_listings_filter_options', $options );

		return $options;
	}
}

/**
 * Get list of attribute options filtered by query
 *
 * @param array $terms
 * @param WP_Query $from
 *
 * @return array
 */
if ( ! function_exists( 'stm_listings_options_remaining' ) ) {
	function stm_listings_options_remaining( $terms, $from = null ) {
		/** !!!!!!!!! VERY IMPORTANT !!!!!!!!!
		 * BEFORE ADD JOIN OR OTHER DATA TO QUERY
		 * CHECK IS THAT DATA ALREADY EXIST IN VARS
		 *  - $meta_query_count_sql, $tax_query_sql etc
		 */
		global $wpdb;

		/** @var WP_Query $from */
		$from = is_null( $from ) ? $GLOBALS['wp_query'] : $from;

		if ( empty( $terms ) || is_null( $from ) ) {
			return array();
		}

		$meta_query_count = new WP_Meta_Query( $from->get( 'meta_query_count', array() ) );
		$tax_query        = new WP_Tax_Query( $from->get( 'tax_query', array() ) );

		/** @var  IMPORTANT $meta_query_count_sql connection with 'postmeta' table */
		$meta_query_count_sql = $meta_query_count->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql        = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$term_ids  = wp_list_pluck( $terms, 'term_taxonomy_id' );
		$post_type = $from->get( 'post_type' );

		// Generate query
		$query           = array();
		$query['select'] = "SELECT term_taxonomy.term_taxonomy_id, COUNT( {$wpdb->posts}.ID ) as count";
		$query['from']   = "FROM {$wpdb->posts}";

		$query['join']  = "LEFT JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id";
		$query['join'] .= "\nLEFT JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )";
		$query['join'] .= "\n" . $tax_query_sql['join'] . $meta_query_count_sql['join'];

		$query['where']  = "WHERE {$wpdb->posts}.post_type IN ( '{$post_type}' ) AND {$wpdb->posts}.post_status = 'publish' ";
		$query['where'] .= "\n" . $tax_query_sql['where'] . $meta_query_count_sql['where'];
		$query['where'] .= "\nAND term_taxonomy.term_taxonomy_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		$query['group_by'] = 'GROUP BY term_taxonomy.term_taxonomy_id';

		$query = apply_filters( 'stm_listings_options_remaining_query', $query );
		$query = join( "\n", $query );

		$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$results = wp_list_pluck( $results, 'count', 'term_taxonomy_id' );

		return $results;
	}
}

/**
 * Filter configuration array
 *
 * @return array
 */
if ( ! function_exists( 'stm_listings_filter_config' ) ) {
	function stm_listings_filter( $source = null, $hide_empty = false, $modern_fields = false ) {
		$query   = stm_listings_query( $source );
		$total   = $query->found_posts;
		$filters = \MotorsVehiclesListing\Helper\OptionsHelper::get_all_listing_categories_by_option( array( 'use_on_car_filter' => true ), true );

		$filter_helper = new \MotorsVehiclesListing\Helper\FilterHelper();
		$options       = $filter_helper->get_all_filter_data_with_options( $hide_empty, false, $modern_fields );

		$url     = '';
		$compact = compact( 'options', 'filters', 'total', 'url' );

		if ( isset( $_GET['listing_type'] ) && 'with_review' === $_GET['listing_type'] ) {
			$listings = array();
			foreach ( $query->posts as $listing ) {
				$listings[] = $listing->ID;
			}
			$compact = compact( 'options', 'filters', 'total', 'url', 'listings' );
		}

		if ( isset( $_GET['result_with_posts'] ) ) {
			$filter_params = explode( ',', $_GET['filter-params'] );
			$fp            = '';
			foreach ( $filter_params as $k => $val ) {
				$get = ( true === apply_filters( 'stm_is_listing_price_field', $val ) ) ? 'max_' . $val : $val;
				if ( isset( $_GET[ $get ] ) && ! empty( $_GET[ $get ] ) ) {

					if ( empty( $fp ) ) {
						$fp .= $filters[ $val ]['single_name'];
					} elseif ( ! empty( $fp ) && 0 !== $k && ( count( $filter_params ) - 1 ) !== $k ) {
						$fp .= ', ' . $filters[ $val ]['single_name'];
					} elseif ( $k >= 1 && ! empty( $fp ) ) {
						$fp .= esc_html__( ' and ', 'stm_vehicles_listing' ) . $filters[ $val ]['single_name'];
					}
				}
			}

			if ( ! empty( $fp ) ) {
				$fp = esc_html__( 'By ', 'stm_vehicles_listing' ) . $fp;
			}

			$posts   = add_review_info_to_listing( $query->posts );
			$compact = compact( 'options', 'filters', 'total', 'url', 'posts', 'fp' );
		}

		if ( isset( $_GET['offset'] ) ) {
			$result_count = count( $query->get_posts() );
			$offset       = $_GET['offset'] + 1;
			if ( $offset * $_GET['posts_per_page'] <= $total ) {

				$offset = ( $offset * $_GET['posts_per_page'] >= $total ) ? 0 : $offset;

				$compact = compact( 'options', 'filters', 'total', 'url', 'posts', 'offset', 'fp', 'result_count' );
			}
		}

		return apply_filters( 'stm_listings_filter', $compact );
	}

	add_filter( 'stm_listings_filter_func', 'stm_listings_filter', 10, 3 );
}

if ( ! function_exists( 'add_review_info_to_listing' ) ) {
	function add_review_info_to_listing( $posts ) {
		$new_posts = array();

		foreach ( $posts as $k => $post ) {
			$listing_id = $post->ID;
			$review_id  = get_post_id_by_meta_k_v( 'review_car', $listing_id );
			$post_type  = get_post_type( $listing_id );
			$start_at   = get_post_meta( $review_id, 'show_title_start_at', true );
			$price      = apply_filters( 'stm_filter_price_view', '', get_post_meta( $listing_id, 'stm_genuine_price', true ) );
			$hwy        = get_post_meta( $listing_id, 'highway_mpg', true );
			$cwy        = get_post_meta( $listing_id, 'sity_mpg', true );
			$title      = $post->post_title;

			if ( ! is_null( $review_id ) ) {
				$title = '<span>' . $title . '</span> ' . apply_filters( 'stm_mr_string_max_charlength_filter', get_the_title( $review_id ), 55 );
			}

			$cars_in_compare    = apply_filters( 'stm_get_compared_items', array(), $post_type );
			$in_compare         = '';
			$car_compare_status = esc_html__( 'Add to compare', 'stm_vehicles_listing' );

			if ( ! empty( $cars_in_compare ) && in_array( $listing_id, $cars_in_compare, true ) ) {
				$in_compare         = 'active';
				$car_compare_status = esc_html__( 'Remove from compare', 'stm_vehicles_listing' );
			}

			$image_url = get_the_post_thumbnail_url( $listing_id, 'stm-img-255' );

			if ( empty( $image_url ) && ! is_null( $review_id ) ) {
				$image_data = get_the_post_thumbnail_url( $review_id, 'stm-img-255' );
				$image_url  = ( ! empty( $image_data ) ) ? $image_data : STM_LISTINGS_URL . '/assets/images/plchldr255_160.jpg';
			} elseif ( ! $image_url ) {
				$image_url = STM_LISTINGS_URL . '/assets/images/plchldr255_160.jpg';
			}

			$post_link = get_the_permalink( $listing_id );
			$excerpt   = apply_filters( 'the_content', get_the_excerpt( $listing_id ) );

			$new_post = array();

			$new_post['id']                 = $listing_id;
			$new_post['car_already_added']  = $in_compare;
			$new_post['car_compare_status'] = $car_compare_status;
			$new_post['title']              = $title;
			$new_post['generate_title']     = apply_filters( 'stm_generate_title_from_slugs', get_the_title( $listing_id ), $listing_id, false );

			$new_post['excerpt']       = $excerpt;
			$new_post['url']           = $post_link;
			$new_post['img_url']       = $image_url;
			$new_post['price']         = $price;
			$new_post['show_start_at'] = $start_at;
			$new_post['hwy']           = $hwy;
			$new_post['cwy']           = $cwy;

			if ( ! is_null( $review_id ) ) {

				$performance = get_post_meta( $review_id, 'performance', true );
				$comfort     = get_post_meta( $review_id, 'comfort', true );
				$interior    = get_post_meta( $review_id, 'interior', true );
				$exterior    = get_post_meta( $review_id, 'exterior', true );

				$rating_summary = ( ( $performance + $comfort + $interior + $exterior ) / 4 );

				$new_post['ratingSumm']   = $rating_summary;
				$new_post['ratingP']      = $rating_summary * 20;
				$new_post['performance']  = $performance;
				$new_post['performanceP'] = $performance * 20;
				$new_post['comfort']      = $comfort;
				$new_post['comfortP']     = $comfort * 20;
				$new_post['interior']     = $interior;
				$new_post['interiorP']    = $interior * 20;
				$new_post['exterior']     = $exterior;
				$new_post['exteriorP']    = $exterior * 20;
			}

			$new_posts[ $k ] = (object) $new_post;
		}

		return $new_posts;
	}
}

if ( ! function_exists( 'get_post_id_by_meta_k_v' ) ) {
	function get_post_id_by_meta_k_v( $key, $value ) {
		global $wpdb;
		$meta = $wpdb->get_results( $wpdb->prepare( 'SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE meta_key=%s AND meta_value=%s', $key, $value ) );

		return ( count( $meta ) > 0 ) ? $meta[0]->post_id : null;
	}
}

/**
 * Retrieve input data from $_POST, $_GET by path
 *
 * @param $path
 * @param $default
 *
 * @return mixed
 */
if ( ! function_exists( 'stm_listings_input' ) ) {
	function stm_listings_input( $default, $path = '' ) {
		if ( empty( trim( $path, '.' ) ) ) {
			return $default;
		}

		$args = array( $_POST, $_GET );
		if ( class_exists( \MotorsVehiclesListing\Features\FriendlyUrl::class ) ) {
			$args = array_merge( $args, array( \MotorsVehiclesListing\Features\FriendlyUrl::$for_filter ) );
		}

		foreach ( $args as $source ) {
			$value = $source;
			foreach ( explode( '.', $path ) as $key ) {
				if ( ! is_array( $value ) || ! array_key_exists( $key, $value ) ) {
					$value = null;
					break;
				}

				$value = &$value[ $key ];
			}

			if ( ! is_null( $value ) ) {
				return $value;
			}
		}

		return $default;
	}

	add_filter( 'stm_listings_input', 'stm_listings_input', 10, 2 );
}

/**
 * Current URL with native WP query string parameters ()
 *
 * @return string
 */
if ( ! function_exists( 'stm_listings_current_url' ) ) {
	function stm_listings_current_url() {
		global $wp, $wp_rewrite;

		$url = preg_replace( '/\/page\/\d+/', '', $wp->request );
		$url = home_url( $url . '/' );
		if ( ! $wp_rewrite->permalink_structure ) {
			parse_str( $wp->query_string, $query_string );

			$leave        = array( 'post_type', 'pagename', 'page_id', 'p' );
			$query_string = array_intersect_key( $query_string, array_flip( $leave ) );

			$url = trim( add_query_arg( $query_string, $url ), '&' );
			$url = str_replace( '&&', '&', $url );
		}

		return $url;
	}

	add_filter( 'stm_listings_current_url', 'stm_listings_current_url' );
}

function _stm_listings_filter_attribute_options( $taxonomy, $_terms ) {
	$attribute = stm_listings_attribute( $taxonomy );
	$attribute = wp_parse_args(
		$attribute,
		array(
			'slug'        => $taxonomy,
			'single_name' => '',
			'numeric'     => false,
			'slider'      => false,
		)
	);

	$options = array();

	if ( ! $attribute['numeric'] ) {

		$options[''] = array(
			'label'    => apply_filters( 'stm_listings_default_tax_name', $attribute['single_name'] ),
			'selected' => apply_filters( 'stm_listings_input', null, $attribute['slug'] ) === null,
			'disabled' => false,
		);

		foreach ( $_terms as $_term ) {
			$options[ $_term->slug ] = array(
				'label'    => $_term->name,
				'selected' => apply_filters( 'stm_listings_input', null, $attribute['slug'] ) === $_term->slug,
				'disabled' => false,
				'count'    => $_term->count,
			);
		}
	} else {
		$numbers = array();
		foreach ( $_terms as $_term ) {
			$numbers[ intval( $_term->slug ) ] = $_term->name;
		}
		ksort( $numbers );

		if ( ! empty( $attribute['slider'] ) ) {
			foreach ( $numbers as $_number => $_label ) {
				$options[ $_number ] = array(
					'label'    => $_label,
					'selected' => apply_filters( 'stm_listings_input', null, $attribute['slug'] ) === $_label,
					'disabled' => false,
				);
			}
		} else {

			$options[''] = array(
				'label'    => sprintf(
				/* translators: %s single name */
					__( 'Max %s', 'stm_vehicles_listing' ),
					$attribute['single_name']
				),
				'selected' => apply_filters( 'stm_listings_input', null, $attribute['slug'] ) === null,
				'disabled' => false,
			);

			$_prev  = null;
			$_affix = empty( $attribute['affix'] ) ? '' : esc_html( $attribute['affix'] );

			foreach ( $numbers as $_number => $_label ) {

				if ( null === $_prev ) {
					$_value = '<' . $_number;
					$_label = '< ' . $_label . ' ' . $_affix;
				} else {
					$_value = $_prev . '-' . $_number;
					$_label = $_prev . '-' . $_label . ' ' . $_affix;
				}

				$options[ $_value ] = array(
					'label'    => $_label,
					'selected' => apply_filters( 'stm_listings_input', null, $attribute['slug'] ) === $_value,
					'disabled' => false,
				);

				$_prev = $_number;
			}

			if ( $_prev ) {
				$_value             = '>' . $_prev;
				$options[ $_value ] = array(
					'label'    => '>' . $_prev . ' ' . $_affix,
					'selected' => apply_filters( 'stm_listings_input', null, $attribute['slug'] ) === $_value,
					'disabled' => false,
				);
			}
		}
	}

	return $options;
}

if ( ! function_exists( 'stm_listings_user_defined_filter_page' ) ) {
	function stm_listings_user_defined_filter_page() {
		$listing_archive = apply_filters( 'motors_vl_get_nuxy_mod', '', 'listing_archive' );

		return apply_filters( 'stm_listings_inventory_page_id', $listing_archive );
	}

	add_filter( 'stm_listings_user_defined_filter_page', 'stm_listings_user_defined_filter_page' );
}

function stm_listings_paged_var() {
	global $wp;

	$paged = null;

	if ( isset( $wp->query_vars['paged'] ) ) {
		$paged = $wp->query_vars['paged'];
	} elseif ( isset( $_GET['paged'] ) ) {
		$paged = sanitize_text_field( $_GET['paged'] );
	}

	return $paged;
}

/**
 * Listings post type identifier
 *
 * @return string
 */
if ( ! function_exists( 'stm_listings_post_type' ) ) {
	function stm_listings_post_type() {
		return 'listings';
	}

	add_filter( 'stm_listings_post_type', 'stm_listings_post_type' );
}

add_action( 'init', 'stm_listings_init', 1 );

function stm_listings_init() {
	$options = get_option( 'stm_post_types_options' );

	$stm_vehicle_options = wp_parse_args(
		$options,
		array(
			'listings' => array(
				'title'        => __( 'Listings', 'stm_vehicles_listing' ),
				'plural_title' => __( 'Listings', 'stm_vehicles_listing' ),
				'rewrite'      => 'listings',
			),
		)
	);

	register_post_type(
		apply_filters( 'stm_listings_post_type', 'listings' ),
		array(
			'labels'             => array(
				'name'               => $stm_vehicle_options['listings']['plural_title'],
				'singular_name'      => $stm_vehicle_options['listings']['title'],
				'add_new'            => __( 'Add New', 'stm_vehicles_listing' ),
				'add_new_item'       => __( 'Add New Item', 'stm_vehicles_listing' ),
				'edit_item'          => __( 'Edit Item', 'stm_vehicles_listing' ),
				'new_item'           => __( 'New Item', 'stm_vehicles_listing' ),
				'all_items'          => __( 'All Items', 'stm_vehicles_listing' ),
				'view_item'          => __( 'View Item', 'stm_vehicles_listing' ),
				'search_items'       => __( 'Search Items', 'stm_vehicles_listing' ),
				'not_found'          => __( 'No items found', 'stm_vehicles_listing' ),
				'not_found_in_trash' => __( 'No items found in Trash', 'stm_vehicles_listing' ),
				'parent_item_colon'  => '',
				'menu_name'          => $stm_vehicle_options['listings']['plural_title'],
			),
			'menu_icon'          => 'dashicons-location-alt',
			'show_in_nav_menus'  => true,
			'supports'           => array(
				'title',
				'editor',
				'thumbnail',
				'comments',
				'excerpt',
				'author',
				'revisions',
			),
			'rewrite'            => array( 'slug' => $stm_vehicle_options['listings']['rewrite'] ),
			'has_archive'        => ! ( apply_filters( 'listings_without_archive', false ) && empty( apply_filters( 'motors_vl_get_nuxy_mod', '', 'listing_archive' ) ) ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'admin.php?page=mvl_plugin_settings',
			'query_var'          => true,
			'hierarchical'       => false,
			'capability_type'    => 'listings_post',
			'capabilities'       => array(
				'publish_post'        => 'publish_listings_post',
				'edit_post'           => 'edit_listings_post',
				'read_post'           => 'read_listings_post',
				'delete_post'         => 'delete_listings_post',
				'publish_posts'       => 'publish_listings_posts',
				'edit_posts'          => 'edit_listings_posts',
				'delete_posts'        => 'delete_listings_posts',
				'edit_others_posts'   => 'edit_others_listings_posts',
				'delete_others_posts' => 'delete_others_listings_posts',
				'read_private_posts'  => 'read_private_listings_posts',
			),
		)
	);

	register_post_type(
		'test_drive_request',
		array(
			'labels'               => array(
				'name'               => __( 'Test Drives', 'stm_vehicles_listing' ),
				'singular_name'      => __( 'Test Drives', 'stm_vehicles_listing' ),
				'add_new'            => __( 'Add New', 'stm_vehicles_listing' ),
				'add_new_item'       => __( 'Add New Test Drives', 'stm_vehicles_listing' ),
				'edit_item'          => __( 'Edit Test Drives', 'stm_vehicles_listing' ),
				'new_item'           => __( 'New Test Drives', 'stm_vehicles_listing' ),
				'all_items'          => __( 'All Test Drives', 'stm_vehicles_listing' ),
				'view_item'          => __( 'View Test Drives', 'stm_vehicles_listing' ),
				'search_items'       => __( 'Search Test Drives', 'stm_vehicles_listing' ),
				'not_found'          => __( 'No Test Drives found', 'stm_vehicles_listing' ),
				'not_found_in_trash' => __( 'No Test Drives found in Trash', 'stm_vehicles_listing' ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Test Drives', 'stm_vehicles_listing' ),
			),
			'public'               => true,
			'publicly_queryable'   => false,
			'show_ui'              => true,
			'show_in_menu'         => 'admin.php?page=mvl_plugin_settings',
			'show_in_nav_menus'    => false,
			'query_var'            => true,
			'has_archive'          => true,
			'hierarchical'         => false,
			'menu_position'        => null,
			'menu_icon'            => null,
			'supports'             => array( 'title', 'editor' ),
			'register_meta_box_cb' => 'stm_add_test_drives_metaboxes',
		)
	);

	if ( apply_filters( 'is_mvl_pro', false ) && ! apply_filters( 'stm_is_motors_theme', false ) ) {
		register_post_type(
			'dealer_review',
			array(
				'labels'               => array(
					'name'               => __( 'Dealer Reviews', 'stm_vehicles_listing' ),
					'singular_name'      => __( 'Dealer Reviews', 'stm_vehicles_listing' ),
					'add_new'            => __( 'Add New', 'stm_vehicles_listing' ),
					'add_new_item'       => __( 'Add New Dealer Reviews', 'stm_vehicles_listing' ),
					'edit_item'          => __( 'Edit Dealer Reviews', 'stm_vehicles_listing' ),
					'new_item'           => __( 'New Dealer Reviews', 'stm_vehicles_listing' ),
					'all_items'          => __( 'All Dealer Reviews', 'stm_vehicles_listing' ),
					'view_item'          => __( 'View Dealer Reviews', 'stm_vehicles_listing' ),
					'search_items'       => __( 'Search Dealer Reviews', 'stm_vehicles_listing' ),
					'not_found'          => __( 'No Dealer Reviews found', 'stm_vehicles_listing' ),
					'not_found_in_trash' => __( 'No Dealer Reviews found in Trash', 'stm_vehicles_listing' ),
					'parent_item_colon'  => '',
					'menu_name'          => __( 'Dealer Reviews', 'stm_vehicles_listing' ),
				),
				'public'               => true,
				'publicly_queryable'   => false,
				'show_ui'              => true,
				'show_in_menu'         => 'admin.php?page=mvl_plugin_settings',
				'show_in_nav_menus'    => false,
				'query_var'            => true,
				'has_archive'          => true,
				'hierarchical'         => false,
				'menu_position'        => null,
				'menu_icon'            => null,
				'supports'             => array( 'title', 'editor' ),
				'register_meta_box_cb' => 'stm_add_dealer_review_metabox',
			)
		);
	}
}

add_filter( 'get_pagenum_link', 'stm_listings_get_pagenum_link' );

function stm_add_dealer_review_metabox() {
	$rates = array();
	for ( $i = 1; $i < 6; $i ++ ) {
		$rates[ $i ] = $i;
	}

	$likes = array(
		'neutral' => esc_html__( 'Neutral', 'stm_vehicles_listing' ),
		'yes'     => esc_html__( 'Yes', 'stm_vehicles_listing' ),
		'no'      => esc_html__( 'No', 'stm_vehicles_listing' ),
	);
	add_meta_box(
		'dealer_review_form',
		__( 'Dealer Reviews', 'stm_vehicles_listing' ),
		'display_metaboxes',
		'dealer_review',
		'normal',
		'',
		array(
			'fields' => array(
				'stm_review_added_by' => array(
					'label'   => __( 'User added by', 'stm_vehicles_listing' ),
					'type'    => 'select',
					'options' => stm_listings_get_user_list(),
				),
				'stm_review_added_on' => array(
					'label'   => __( 'User added on', 'stm_vehicles_listing' ),
					'type'    => 'select',
					'options' => stm_listings_get_user_list(),
				),
				'stm_rate_1'          => array(
					'label'   => __( 'Rate 1', 'stm_vehicles_listing' ),
					'type'    => 'select',
					'options' => $rates,
				),
				'stm_rate_2'          => array(
					'label'   => __( 'Rate 2', 'stm_vehicles_listing' ),
					'type'    => 'select',
					'options' => $rates,
				),
				'stm_rate_3'          => array(
					'label'   => __( 'Rate 3', 'stm_vehicles_listing' ),
					'type'    => 'select',
					'options' => $rates,
				),
				'stm_recommended'     => array(
					'label'   => __( 'Recommended', 'stm_vehicles_listing' ),
					'type'    => 'select',
					'options' => $likes,
				),
			),
		)
	);
}

function stm_add_test_drives_metaboxes() {
	add_meta_box(
		'test_drive_form',
		__( 'Credentials', 'stm_vehicles_listing' ),
		'display_metaboxes',
		'test_drive_request',
		'normal',
		'',
		array(
			'fields' => array(
				'name'  => array(
					'label' => __( 'Name', 'stm_vehicles_listing' ),
					'type'  => 'text',
				),
				'email' => array(
					'label' => __( 'E-mail', 'stm_vehicles_listing' ),
					'type'  => 'text',
				),
				'phone' => array(
					'label' => __( 'Phone', 'stm_vehicles_listing' ),
					'type'  => 'text',
				),
				'date'  => array(
					'label' => __( 'Day', 'stm_vehicles_listing' ),
					'type'  => 'text',
				),
			),
		)
	);
}

function display_metaboxes( $post, $metabox ) {

	$fields = $metabox['args']['fields'];

	$html = '<input type="hidden" name="stm_custom_nonce" value="' . wp_create_nonce( basename( __FILE__ ) ) . '" />';//phpcs:ignore
	$html .= '<table class="form-table stm">';
	foreach ( $fields as $key => $field ) {
		$meta = get_post_meta( $post->ID, $key, true );
		if ( 'hidden' !== $field['type'] ) {
			if ( 'separator' !== $field['type'] ) {
				$html .= '<tr class="stm_admin_' . $key . '"><th><label for="' . $key . '">' . $field['label'] . '</label></th><td>';
			} else {
				$html .= '<tr><th><h3>' . $field['label'] . '</h3></th><td>';
			}
		}
		switch ( $field['type'] ) {
			case 'text':
				if ( empty( $meta ) && ! empty( $field['default'] ) && 'auto-draft' === $post->post_status ) {
					$meta = $field['default'];
				}
				echo '<input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $meta ) . '" />';
				if ( isset( $field['description'] ) ) {
					$html .= '<p class="textfield-description">' . $field['description'] . '</p>';
				}
				break;
			case 'select':
				$html .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '">';
				foreach ( $field['options'] as $option_key => $option_value ) {
					$html .= '<option' . ( $meta === $option_key ? ' selected="selected"' : '' ) . ' value="' . esc_attr( $option_key ) . '">' . esc_html( $option_value ) . '</option>';
				}
				$html .= '</select>';
				break;
		}
		$html .= '</td></tr>';
	}
	$html .= '</table>';

	echo $html; //phpcs:ignore
}

function stm_save_metaboxes( $post_id ) {

	if ( ! isset( $_POST['stm_custom_nonce'] ) ) {
		return $post_id;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}
	$metaboxes = array(
		'fields' => array(
			'name'                => array(
				'label' => __( 'Name', 'stm_vehicles_listing' ),
				'type'  => 'text',
			),
			'email'               => array(
				'label' => __( 'E-mail', 'stm_vehicles_listing' ),
				'type'  => 'text',
			),
			'phone'               => array(
				'label' => __( 'Phone', 'stm_vehicles_listing' ),
				'type'  => 'text',
			),
			'date'                => array(
				'label' => __( 'Day', 'stm_vehicles_listing' ),
				'type'  => 'text',
			),
			//dealer reviews
			'stm_review_added_by' => array(
				'label' => __( 'User added by', 'stm_vehicles_listing' ),
				'type'  => 'select',
			),
			'stm_review_added_on' => array(
				'label' => __( 'User added on', 'stm_vehicles_listing' ),
				'type'  => 'select',
			),
			'stm_rate_1'          => array(
				'label' => __( 'Rate 1', 'stm_vehicles_listing' ),
				'type'  => 'select',
			),
			'stm_rate_2'          => array(
				'label' => __( 'Rate 2', 'stm_vehicles_listing' ),
				'type'  => 'select',
			),
			'stm_rate_3'          => array(
				'label' => __( 'Rate 3', 'stm_vehicles_listing' ),
				'type'  => 'select',
			),
			'stm_recommended'     => array(
				'label' => __( 'Recommended', 'stm_vehicles_listing' ),
				'type'  => 'select',
			),
		),
	);

	foreach ( $metaboxes as $stm_field_key => $fields ) {

		foreach ( $fields as $field => $data ) {
			$old = get_post_meta( $post_id, $field, true );
			if ( isset( $_POST[ $field ] ) ) {
				$new = sanitize_text_field( $_POST[ $field ] );
				if ( $new && $new !== $old ) {
					if ( 'listing_select' === $data['type'] ) {
						update_post_meta( $post_id, $field, implode( ',', $new ) );
					} else {
						update_post_meta( $post_id, $field, $new );
					}
				} elseif ( '' === $new && $old ) {
					delete_post_meta( $post_id, $field, $old );
				}
			} else {
				delete_post_meta( $post_id, $field, $old );
			}
		}

		if ( 'listing_filter' === $stm_field_key ) {
			foreach ( $fields as $field => $data ) {

				if ( 'listing_select' === $data['type'] ) {
					if ( isset( $_POST[ $field ] ) ) {
						$new = sanitize_text_field( $_POST[ $field ] );
						if ( 'none' !== $new ) {
							wp_set_object_terms( $post_id, $new, $field );
						}
					}
				}
			}
		}
	}
}

add_action( 'save_post', 'stm_save_metaboxes' );

function stm_listings_get_pagenum_link( $link ) {
	return remove_query_arg( 'ajax_action', $link );
}

/*Functions*/
function stm_check_motors() {
	return apply_filters( 'stm_listing_is_motors_theme', false );
}

require_once 'templates.php';
require_once 'enqueue.php';
require_once 'vehicle_functions.php';

if ( ! function_exists( 'stm_generate_title_from_slugs' ) ) {
	function stm_generate_title_from_slugs( $title, $post_id, $show_labels = false ) {
		$title_from = apply_filters( 'motors_vl_get_nuxy_mod', '', 'listing_directory_title_frontend' );
		if ( stm_is_multilisting() && $show_labels && get_post_type( $post_id ) !== apply_filters( 'stm_listings_post_type', 'listings' ) ) {
			$multilisting = new STMMultiListing();
			$title_from   = $multilisting->stm_get_listing_type_settings( 'listing_directory_title_frontend', get_post_type( $post_id ) );
		}

		if ( empty( $title_from ) ) {
			return strip_shortcodes( $title );
		}

		$title_return = apply_filters( 'generate_title_from_slugs', '', $post_id, $show_labels );

		if ( ! empty( $title_return ) ) {
			return strip_shortcodes( $title_return );
		}

		$taxonomies  = apply_filters( 'stm_replace_curly_brackets', $title_from );
		$title_parts = array();

		foreach ( $taxonomies as $taxonomy_slug ) {
			$terms = wp_get_post_terms( $post_id, strtolower( $taxonomy_slug ), array( 'orderby' => 'none' ) );
			foreach ( $terms as $term ) {
				if ( ! empty( $term->name ) ) {
					$title_parts[] = $term->name;
				}
			}
		}

		if ( ! empty( $title_parts ) ) {
			$count = count( $title_parts );
			if ( $count > 2 && $show_labels ) {
				$first_two     = implode( ' ', array_slice( $title_parts, 0, 2 ) );
				$title_return .= '<div class="labels">' . $first_two . '</div>';

				for ( $i = 2; $i < $count; $i ++ ) {
					$title_return .= ' ' . $title_parts[ $i ];
				}
			} else {
				$title_return = implode( ' ', $title_parts );
			}
		}

		if ( empty( $title_return ) ) {
			$title_return = get_the_title( $post_id );
		}

		return strip_shortcodes( $title_return );
	}

	add_filter( 'stm_generate_title_from_slugs', 'stm_generate_title_from_slugs', 10, 3 );
}

if ( ! function_exists( 'stm_replace_curly_brackets' ) ) {
	function stm_replace_curly_brackets( $string ) {
		$matches = array();
		preg_match_all( '/{(.*?)}/', $string, $matches );

		return $matches[1];
	}

	add_filter( 'stm_replace_curly_brackets', 'stm_replace_curly_brackets', 10, 1 );
}

function stm_listings_search_inventory() {
	$enable_search = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_search' );

	return apply_filters( 'stm_listings_default_search_inventory', $enable_search );
}

function mvl_get_dynamic_string_translation( $string, $desc ) {
	return apply_filters( 'wpml_translate_single_string', $string, 'stm_vehicles_listing', $desc );
}
add_filter( 'mvl_get_dynamic_string_translation', 'mvl_get_dynamic_string_translation', 10, 2 );

function stm_listings_dynamic_string_translation_e( $string, $desc ) {
	do_action( 'wpml_register_single_string', 'stm_vehicles_listing', $desc, $string );
	echo wp_kses_post( mvl_get_dynamic_string_translation( $string, $desc ) );
}

function stm_listings_dynamic_string_translation( $string, $desc ) {
	do_action( 'wpml_register_single_string', 'stm_vehicles_listing', $desc, $string );
	return mvl_get_dynamic_string_translation( $string, $desc );
}
add_filter( 'stm_listings_dynamic_string_translation', 'stm_listings_dynamic_string_translation', 10, 2 );

// check for multilisting
if ( ! function_exists( 'stm_is_multilisting' ) ) {
	function stm_is_multilisting() {
		if ( defined( 'MULTILISTING_PATH' ) && class_exists( 'STMMultiListing' ) ) {
			return true;
		} else {
			return false;
		}
	}
}


// get multilisting post types (array of post types) including/excluding default "listings" post type
if ( ! function_exists( 'stm_listings_multi_type' ) ) {

	function stm_listings_multi_type( $include_default = false ) {
		$post_types = array();

		if ( $include_default ) {
			$post_types[] = apply_filters( 'stm_listings_post_type', 'listings' );
		}

		if ( stm_is_multilisting() ) {
			$types = STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $types ) ) {
				$post_types = array_merge( $post_types, $types );
			}
		}

		return $post_types;
	}

	add_filter( 'stm_listings_multi_type', 'stm_listings_multi_type' );
}

if ( ! function_exists( 'stm_distance_measure_unit_value' ) ) {
	function stm_distance_measure_unit_value() {
		$enable_distance  = apply_filters( 'motors_vl_get_nuxy_mod', true, 'enable_distance_search' );
		$enable_recommend = apply_filters( 'motors_vl_get_nuxy_mod', true, 'recommend_items_empty_result' );

		$key = 'distance_measure_unit';
		if ( ! $enable_distance && $enable_recommend ) {
			$key = 'recommend_distance_measure_unit';
		}

		return apply_filters( 'motors_vl_get_nuxy_mod', 'miles', $key );
	}
}

add_filter( 'stm_distance_measure_unit_value', 'stm_distance_measure_unit_value' );

if ( ! function_exists( 'stm_distance_search_value' ) ) {
	function stm_distance_search_value() {
		$enable_distance  = apply_filters( 'motors_vl_get_nuxy_mod', true, 'enable_distance_search' );
		$enable_recommend = apply_filters( 'motors_vl_get_nuxy_mod', true, 'recommend_items_empty_result' );

		$key = 'distance_search';
		if ( ! $enable_distance && $enable_recommend ) {
			$key = 'recommend_distance_search';
		}

		return apply_filters( 'motors_vl_get_nuxy_mod', 100, $key );
	}
}

add_filter( 'stm_distance_search_value', 'stm_distance_search_value' );

if ( ! function_exists( 'stm_distance_measure_unit' ) ) {
	function stm_distance_measure_unit() {
		$distance_measure = apply_filters( 'stm_distance_measure_unit_value', '' );
		$distance_affix   = esc_html__( 'mi', 'stm_vehicles_listing' );

		if ( 'kilometers' === $distance_measure ) {
			$distance_affix = esc_html__( 'km', 'stm_vehicles_listing' );
		}

		return $distance_affix;
	}
}

add_filter( 'stm_distance_measure_unit', 'stm_distance_measure_unit' );

if ( ! function_exists( 'stm_calculate_distance_between_two_points' ) ) {
	function stm_calculate_distance_between_two_points( $value, $la_from, $lo_from, $la_to, $lo_to ) {
		$distance_measure = apply_filters( 'stm_distance_measure_unit_value', '' );
		$la_from          = esc_attr( floatval( $la_from ) );
		$lo_from          = esc_attr( floatval( $lo_from ) );
		$distance_affix   = apply_filters( 'stm_distance_measure_unit', '' );
		$theta            = $lo_from - $lo_to;
		$value            = sin( deg2rad( $la_from ) ) * sin( deg2rad( $la_to ) ) + cos( deg2rad( $la_from ) ) * cos( deg2rad( $la_to ) ) * cos( deg2rad( $theta ) );
		$value            = acos( $value );
		$value            = rad2deg( $value );
		$value            = $value * 60 * 1.515;

		if ( 'kilometers' !== $distance_measure ) {
			$value = $value / 1.609344;
		}

		return round( $value, 1 ) . ' ' . $distance_affix;
	}
}

add_filter( 'stm_calculate_distance_between_two_points', 'stm_calculate_distance_between_two_points', 10, 5 );


if ( ! function_exists( 'stm_enable_location' ) ) {
	function stm_enable_location() {
		$enable_location = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_location' );

		return $enable_location;
	}

	add_filter( 'stm_enable_location', 'stm_enable_location' );
}

if ( ! function_exists( 'stm_location_components_func' ) ) {
	function stm_location_components_func() {
		return array( 'country', 'locality', 'sublocality_level_1', 'administrative_area_level_1', 'route' );
	}
}

add_filter( 'stm_location_components', 'stm_location_components_func' );

if ( ! function_exists( 'stm_sanitize_location_address' ) ) {
	function stm_sanitize_location_address( $value ) {
		$_items = array();

		if ( ! empty( $value ) ) {
			$_value = html_entity_decode( $value );
			$_value = json_decode( wp_unslash( $_value ), true );

			if ( JSON_ERROR_NONE === json_last_error() && ! empty( $_value ) ) {
				$_items = (array) $_value;
			}
		}

		return $_items;
	}
}

if ( ! function_exists( 'stm_sanitize_location_address_update' ) ) {
	function stm_sanitize_location_address_update( $value, $post_id ) {
		$components  = apply_filters( 'stm_location_components', array() );
		$remove_keys = $components;
		$_items      = stm_sanitize_location_address( $value );

		if ( ! empty( $_items ) ) {
			foreach ( $_items as $item ) {
				if ( in_array( $item['key'], $components, true ) ) {
					update_post_meta(
						$post_id,
						sanitize_key( 'stm_listing_' . $item['key'] ),
						sanitize_text_field( $item['value'] )
					);
				}
			}
		}

		$remove_keys = array_diff( $remove_keys, wp_list_pluck( $_items, 'key' ) );

		if ( ! empty( $remove_keys ) ) {
			foreach ( $remove_keys as $remove_key ) {
				delete_post_meta(
					$post_id,
					sanitize_key( 'stm_listing_' . $remove_key )
				);
			}
		}
	}
}

if ( ! function_exists( 'stm_sort_distance_nearby' ) ) {
	function stm_sort_distance_nearby() {
		$ca_location = apply_filters( 'stm_listings_input', null, 'ca_location' );
		$stm_lat     = apply_filters( 'stm_listings_input', null, 'stm_lat' );
		$stm_lng     = apply_filters( 'stm_listings_input', null, 'stm_lng' );

		if ( $ca_location && $stm_lat && $stm_lng ) {
			return true;
		}

		return false;
	}
}

/**
 *return top listings for mega menu
 */
function get_top_vehicles_for_mm( $default = array(), $ppp = 8 ) {
	global $wpdb;

	$post_type    = apply_filters( 'stm_listings_post_type', 'listings' );
	$sticky_posts = get_option( 'sticky_posts', array() );

	$sql = "SELECT p.ID, pm2.meta_value as make_slug, t2.name as make, pm3.meta_value as serie_slug, t3.name as serie
			FROM $wpdb->posts as p
			JOIN $wpdb->postmeta as pm ON p.ID = pm.post_id
			LEFT JOIN $wpdb->postmeta as pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'make'
			LEFT JOIN $wpdb->postmeta as pm3 ON p.ID = pm3.post_id AND pm3.meta_key = 'serie'
			LEFT JOIN $wpdb->terms as t2 ON t2.slug = pm2.meta_value
			LEFT JOIN $wpdb->terms as t3 ON t3.slug = pm3.meta_value
			WHERE p.post_type = '$post_type' AND p.post_status = 'publish' AND pm.meta_key = 'stm_car_views' AND pm.meta_value != '0'
			ORDER BY pm.meta_value DESC
			LIMIT 0, %1d";

	$posts = $wpdb->get_results( $wpdb->prepare( $sql, $ppp ) );//phpcs:ignore
	if ( ! is_wp_error( $posts ) ) {
		foreach ( $posts as $k => $post ) {
			if ( in_array( $post->ID, $sticky_posts, true ) ) {
				unset( $posts[ $k ] );
			}
		}
	}

	return ( ! is_wp_error( $posts ) ) ? $posts : false;
}

add_filter( 'get_top_vehicles_for_mm', 'get_top_vehicles_for_mm', 10, 2 );

function add_footer_template() {
	global $wp_query;

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote_grid' ) || apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote' ) || ( is_singular( apply_filters( 'stm_listings_post_type', 'listings' ) ) && 'on' === get_post_meta( get_the_ID(), 'car_price_form', true ) ) ) {
		stm_listings_load_template( 'modals/get-car-price' );
	}

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive' ) || apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_test_drive' ) ) {
		stm_listings_load_template( 'modals/test-drive' );
	}

	if ( ! defined( 'MOTORS_THEME' ) && ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' ) || apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_compare' ) ) ) {
		stm_listings_load_template( 'compare/compare-footer-modal' );
	}

	if (
		apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade_grid' )
		|| apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade' )
		|| apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_offer_price' )
		|| ( class_exists( 'Elementor\Plugin' ) && $wp_query->get( 'show_offer_price' ) )
	) {
		stm_listings_load_template( 'modals/trade-offer' );
	}

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_trade_in' ) || ( class_exists( 'Elementor\Plugin' ) && $wp_query->get( 'show_trade_in' ) ) ) {
		stm_listings_load_template( 'modals/trade-in' );
	}

	if ( class_exists( 'Elementor\Plugin' ) && $wp_query->get( 'show_calculator' ) ) {
		do_action( 'stm_listings_load_template', 'modals/car-calculator' );
	}

	stm_listings_load_template( 'modals/statistics-modal' );
}

add_action( 'wp_footer', 'add_footer_template' );

if ( class_exists( 'Header_Footer_Elementor' ) && has_action( 'elementor/page_templates/canvas/after_content' ) ) {
	add_action( 'elementor/page_templates/canvas/after_content', 'add_footer_template' );
}

if ( ! function_exists( 'stm_ajax_add_review' ) ) {
	function stm_ajax_add_review() {
		check_ajax_referer( 'stm_add_review_nonce', 'security', false );
		$status = sanitize_text_field( $_GET['add_review_status'] );
		update_option( 'add_review_status', $status );
	}
}

add_action( 'wp_ajax_stm_ajax_add_review', 'stm_ajax_add_review' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_review', 'stm_ajax_add_review' );

function review_admin_notice() {
	$status = get_option( 'add_review_status', '' );

	if ( empty( $status ) ) {
		$theme_info = ( WP_DEBUG ) ? time() : '1';
		$assets     = STM_LISTINGS_URL . '/includes/admin/announcement/assets/';
		wp_enqueue_script( 'app-motors.js', $assets . 'app-motors.js', 'jQuery', $theme_info, true );

		echo '<div id="message" class="notice notice-info motors-message">
            <p>If you are happy with the <b>Motors - Classified Listing</b>, please give it a  review on WordPress.org :)</p>
            <p class="submit">
                <a href="https://wordpress.org/support/plugin/motors-car-dealership-classified-listings/reviews/" class="add_review button-primary" target="_blank">Leave a Review</a>
                <a href="" class="skip_review button-secondary">No, thank you</a>
            </p>
        </div>'; //phpcs:ignore
	}
}

add_action( 'admin_notices', 'review_admin_notice' );

function mvl_admin_bar_item( $admin_bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( is_admin() ) {
		$icon_margin_top = '1px';
	} else {
		$icon_margin_top = '-5px';
	}
	$admin_bar_icon = '<span class="ab-icon"><img style="margin-top: ' . $icon_margin_top . '; max-height: 22px;" height="22" width="22" src="' . STM_LISTINGS_URL . '/includes/class/Plugin/assets/img/icon.png" alt="" /></span>';

	if ( apply_filters( 'stm_disable_settings_setup', true ) ) {
		$admin_bar->add_menu(
			array(
				'id'     => 'mvl-plugin-settings',
				'parent' => null,
				'group'  => null,
				'title'  => $admin_bar_icon . '<span class="ab-label">' . esc_html__( 'Motors Plugin Settings', 'stm_vehicles_listing' ),
				'</span>',
				'href'   => admin_url( 'admin.php?page=mvl_plugin_settings' ),
				'meta'   => array(
					'title' => esc_html__( 'Motors Plugin Settings', 'stm_vehicles_listing' ),
				),
			)
		);

		$submenus = array(
			array(
				'id'    => 'mvl-plugin-submenu-listings',
				'title' => esc_html__( 'Listings', 'stm_vehicles_listing' ),
				'href'  => admin_url( 'edit.php?post_type=listings' ),
			),
			array(
				'id'    => 'mvl-plugin-add-new-listing',
				'title' => esc_html__( 'Add New Listing', 'stm_vehicles_listing' ),
				'href'  => admin_url( 'post-new.php?post_type=listings' ),
			),
			array(
				'id'    => 'mvl-plugin-submenu-custom-fields',
				'title' => esc_html__( 'Custom Fields', 'stm_vehicles_listing' ),
				'href'  => admin_url( 'admin.php?page=listing_categories' ),
			),
			array(
				'id'    => 'mvl-plugin-submenu-settings',
				'title' => esc_html__( 'Settings', 'stm_vehicles_listing' ),
				'href'  => admin_url( 'admin.php?page=mvl_plugin_settings' ),
			),
			array(
				'id'    => 'mvl-plugin-submenu-community',
				'title' => esc_html__( 'Community', 'stm_vehicles_listing' ),
				'href'  => esc_url( 'https://www.facebook.com/groups/motorstheme' ),
				'meta'  => array(
					'target' => '_blank',
				),
			),
			array(
				'id'    => 'mvl-plugin-submenu-documentation',
				'title' => esc_html__( 'Documentation', 'stm_vehicles_listing' ),
				'href'  => esc_url( 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing' ),
				'meta'  => array(
					'target' => '_blank',
				),
			),
		);

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$listing_template_menu = array(
				'id'    => 'mvl-plugin-listing-templates',
				'title' => esc_html__( 'Listing Templates', 'stm_vehicles_listing' ),
				'href'  => admin_url( 'admin.php?page=mvl_single_listing_template_settings' ),
			);
			array_splice( $submenus, 2, 0, array( $listing_template_menu ) );
		}

		if ( is_mvl_pro() ) {
			$listing_template_menu = array(
				'id'    => 'mvl-plugin-pro',
				'title' => esc_html__( 'Addons', 'stm_vehicles_listing' ),
				'href'  => admin_url( 'admin.php?page=mvl-addons' ),
			);
			array_splice( $submenus, 5, 0, array( $listing_template_menu ) );
		}

		foreach ( $submenus as $submenu ) {
			$admin_bar->add_menu(
				array(
					'id'     => $submenu['id'],
					'parent' => 'mvl-plugin-settings',
					'title'  => $submenu['title'],
					'href'   => $submenu['href'],
					'meta'   => isset( $submenu['meta'] ) ? $submenu['meta'] : array(),
				)
			);
		}

		if ( ! is_mvl_pro() ) {

			if ( apply_filters( 'stm_hide_pro_if_is_premium_theme', false ) ) {
				return;
			}

			$admin_bar->add_menu(
				array(
					'id'     => 'mvl-plugin-unlock-pro',
					'parent' => 'mvl-plugin-settings',
					'title'  => '<span>' . esc_html__( 'Unlock PRO', 'stm_vehicles_listing' ) . '</span>',
					'href'   => admin_url( 'admin.php?page=mvl-go-pro' ),
					'meta'   => array(
						'title' => esc_html__( 'Unlock PRO', 'stm_vehicles_listing' ),
					),
				)
			);
		}
	}
}

add_action( 'admin_bar_menu', 'mvl_admin_bar_item', 500 );

if ( ! function_exists( 'stm_force_favourites' ) ) {
	function stm_force_favourites( $user_id ) {
		$user_exist_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
		if ( ! empty( $user_exist_fav ) ) {
			$user_exist_fav = explode( ',', $user_exist_fav );
		} else {
			$user_exist_fav = array();
		}

		if ( ! empty( $_COOKIE['stm_car_favourites'] ) ) {
			$cookie_fav = explode( ',', sanitize_text_field( $_COOKIE['stm_car_favourites'] ) );
			setcookie( 'stm_car_favourites', '', time() - 3600, '/' );
		} else {
			$cookie_fav = array();
		}

		if ( ! empty( $user_exist_fav ) || ! empty( $cookie_fav ) ) {
			$new_fav = implode( ',', array_unique( array_merge( $user_exist_fav, $cookie_fav ) ) );
			if ( ! empty( $new_fav ) ) {
				update_user_meta( $user_id, 'stm_user_favourites', $new_fav );
			}
		}
	}
}

// patch for creating car_mark_as_sold meta need to be removed after update
if ( ! function_exists( 'stm_create_car_mark_as_sold_meta' ) ) {
	function stm_create_car_mark_as_sold_meta() {
		$post_types = stm_listings_multi_type( true );

		$args = array(
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => 'car_mark_as_sold',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		foreach ( $post_types as $post_type ) {
			$args['post_type'] = $post_type;
			$posts             = new WP_Query( $args );

			if ( ! empty( $posts->posts ) ) {
				foreach ( $posts->posts as $post_id ) {
					add_post_meta( $post_id, 'car_mark_as_sold', '' );
				}
			}
		}

		update_option( 'mark_as_sold_pathed', true );
	}
}

add_action(
	'admin_init',
	function() {
		if ( ! get_option( 'mark_as_sold_pathed' ) ) {
			stm_create_car_mark_as_sold_meta();
		}
	}
);

add_filter( 'motors_get_demo_data', 'motors_get_demo_data' );

add_filter(
	'mvl_add_listing_form_enable',
	function () {
		if ( apply_filters( 'stm_is_motors_theme', false ) ) {
			$chosen_template = get_option( 'stm_motors_chosen_template', 'car_dealer' );

			return in_array(
				$chosen_template,
				array(
					'listing_one_elementor',
					'listing_two_elementor',
					'listing_three_elementor',
					'listing_four_elementor',
					'listing_five_elementor',
				),
				true
			);
		}

		return true;
	}
);

if ( ! function_exists( 'mvl_clear_woocommerce_cache_safe' ) ) {
	function mvl_clear_woocommerce_cache_safe() {
		if ( ! apply_filters( 'mvl_is_woocommerce_active', false ) ) {
			return;
		}

		if ( ! function_exists( 'delete_transient' ) ) {
			require_once ABSPATH . 'wp-includes/option.php';
		}

		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wc_%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_wc_%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_woocommerce_%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_woocommerce_%'" );

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}woocommerce_sessions'" ) ) {
			$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}woocommerce_sessions" );
		}

		wp_cache_flush();

		if ( class_exists( 'WC_Cache_Helper' ) ) {
			WC_Cache_Helper::get_transient_version( 'product', true );
			WC_Cache_Helper::get_transient_version( 'shipping', true );
			WC_Cache_Helper::get_transient_version( 'tax', true );
		}

		wc_delete_product_transients();

		if ( function_exists( 'wp_clear_scheduled_hook' ) ) {
			wp_clear_scheduled_hook( 'woocommerce_cleanup_sessions' );
			wp_clear_scheduled_hook( 'woocommerce_scheduled_sales' );
			wp_clear_scheduled_hook( 'woocommerce_scheduled_subscriptions' );
		}
	}
}
