<?php

namespace MotorsVehiclesListing\User;

use http\Client\Curl\User;
use MotorsVehiclesListing\Core\CoreController;
use MotorsVehiclesListing\User\Model\UserModel as UserModel;
use MotorsVehiclesListing\User\UserMetaController as UserMetaController;
use MotorsVehiclesListing\User\UserReviewsController as UserReviewsController;
use MotorsVehiclesListing\User\UserListingsController as UserListingsController;

/**
 * UserController Class
 */
class UserController extends CoreController {

	public $user_role = 'stm_dealer';
	public $offset    = 0;
	public $limit     = 12;

	public function init() {
		/**
		 * add fillters, actions etc here
		 */
	}

	/**
	 * Dealer filter terms from the query string: only existing terms of registered taxonomies.
	 *
	 * @return \WP_Term[] Terms keyed by taxonomy name.
	 */
	private static function get_requested_terms() {
		$requested_terms = array();

		foreach ( $_GET as $tax => $term ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! is_string( $tax ) || ! is_string( $term ) ) {
				continue;
			}

			$taxonomy = sanitize_title( wp_unslash( $tax ) );

			if ( '' === $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$term_data = get_term_by( 'slug', sanitize_title( wp_unslash( $term ) ), $taxonomy );

			if ( $term_data instanceof \WP_Term ) {
				$requested_terms[ $taxonomy ] = $term_data;
			}
		}

		return $requested_terms;
	}

	public static function get_dealers_page_title() {
		$title = esc_html__( 'Displaying Local Car Dealerships', 'stm_vehicles_listing' );

		if ( ! empty( $_GET ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$terms = '';
			foreach ( self::get_requested_terms() as $term_data ) {
				$terms .= esc_html( $term_data->name ) . ' ';
			}

			if ( ! empty( $terms ) ) {
				$title = esc_html__( 'Displaying', 'stm_vehicles_listing' ) . ' <span class="green">' . $terms . '</span> ' . esc_html__( 'Dealerships', 'stm_vehicles_listing' );

				if ( ! empty( $_GET['ca_location'] ) && ! empty( $_GET['stm_lng'] ) && ! empty( $_GET['stm_lat'] ) ) {
					$title = esc_html__( 'Displaying', 'stm_vehicles_listing' ) . ' <span class="green">' . $terms . '</span> ' . esc_html__( 'Dealerships near', 'stm_vehicles_listing' ) . ' <span class="green">' . $location_pretty . '</span>';
				}
			}
		}

		return $title;
	}

	public static function get_dealers( $taxonomy = '', $offset = 0, $limit = 12 ) {
		$model         = new UserModel();
		$left_join     = '';
		$where         = '';
		$include_users = array();

		if ( ! empty( $_GET ) ) {// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$requested_terms = self::get_requested_terms();
			$alias_index     = 0;

			foreach ( $requested_terms as $term_data ) {
				// The SQL alias never comes from the request.
				$tr         = 'tax_tr_' . $alias_index;
				$left_join .= $model->left_join( $tr );
				$where     .= $model->where( $tr, $term_data->term_taxonomy_id );
				++$alias_index;
			}

			$lat    = filter_var( apply_filters( 'stm_listings_input', null, 'stm_lat' ), FILTER_VALIDATE_FLOAT );
			$lng    = filter_var( apply_filters( 'stm_listings_input', null, 'stm_lng' ), FILTER_VALIDATE_FLOAT );
			$radius = filter_var( apply_filters( 'motors_vl_get_nuxy_mod', '', 'distance_search' ), FILTER_VALIDATE_FLOAT );
			$radius = ( false !== $radius && $radius > 0 ) ? $radius : 5000;

			if ( empty( $left_join ) && false !== $lat && false !== $lng ) {
				$include_users = $model->get_filtered_users_by_location( $model->fields_by_location( $lat, $lng ), $model->join_by_location( $lat ), $model->having_by_location( $lat, $radius ), $model->order_by_location( $lat ) );
			} elseif ( ! empty( $left_join ) && ! empty( $where ) ) {
				$include_users = $model->get_filtered_users( $left_join, $where, $model->fields_by_location( $lat, $lng ), $model->join_by_location( $lat ), $model->having_by_location( $lat, $radius ), $model->order_by_location( $lat ) );
			}

			$include_users = array_column( $include_users, 'ID' );
			if ( empty( $include_users ) && ! empty( $requested_terms ) ) {
				return array(
					'users' => array(),
					'total' => 0,
				);
			}
		}

		if ( isset( $_GET['stm_sort_by'] ) ) {
			switch ( $_GET['stm_sort_by'] ) {
				case 'alphabet':
					$users = $model->get_sorted_users_by_alphabet( 'stm_company_name', $offset, $limit, $include_users );
					break;
				case 'reviews':
					$users = $model->get_sorted_users_by_review( $offset, $limit, $include_users );
					break;
				case 'date':
					$users = $model->get_users( $offset, $limit, 'user_registered', $include_users );
					break;
				case 'cars': //cars number
					$users = $model->get_sorted_users_by_listings( $offset, $limit, $include_users );
					break;
				case 'watches': //popularity
					$users = $model->get_sorted_users_by_listing_views( $offset, $limit, $include_users );
					break;
			}
		} else {
			$users = $model->get_users( $offset, $limit );
		}

		foreach ( $users as $k => $user ) {
			$reviews               = UserReviewsController::get_reviews( $user->ID );
			$meta                  = UserMetaController::get_usermeta( $user->ID );
			$listings              = UserListingsController::get_listings( $user->ID );
			$users[ $k ]           = (object) array_merge( (array) $user, (array) $meta );
			$users[ $k ]->listings = $listings;
			$users[ $k ]->ratings  = $reviews;
		}

		return array(
			'users' => $users,
			'total' => $model->users_total,
		);
	}

	public static function get_dealers_data( $taxonomy = '', $offset = 0, $limit = 12 ) {
		$users = self::get_dealers( $taxonomy, $offset, $limit );

		$button = 'hide';

		if ( $users['total'] > intval( ( $offset + $limit ) ) ) {
			$button = 'show';
		}

		$users['button'] = $button;

		return $users;
	}
}
