<?php
namespace MotorsVehiclesListing\Plugin;

class MVL_Patcher {
	private static $updates = array(
		'1.0.0' => array(
			'update_seller_notes',
		),
	);

	public function __construct() {
		add_action( 'init', array( self::class, 'init_patcher' ), 100, 1 );
	}

	public static function init_patcher() {
		self::update_version();
	}

	public static function get_updates() {
		return self::$updates;
	}

	public static function needs_to_update() {
		$current_db_version = get_option( 'motors_vl_db_version' );
		$update_versions    = array_keys( self::get_updates() );
		usort( $update_versions, 'version_compare' );

		return ! is_null( $current_db_version ) && version_compare( $current_db_version, end( $update_versions ), '<' );
	}

	private static function maybe_update_db_version() {
		if ( self::needs_to_update() ) {
			$current_db_version = get_option( 'motors_vl_db_version' );
			$updates            = self::get_updates();

			foreach ( $updates as $version => $callback_arr ) {
				if ( version_compare( $current_db_version, $version, '<' ) ) {
					foreach ( $callback_arr as $callback ) {
						call_user_func( array( self::class, $callback ) );
					}
				}
			}
		}

		update_option( 'motors_vl_db_version', STM_LISTINGS_DB_VERSION, true );
	}

	public static function update_version() {
		self::maybe_update_db_version();
	}

	private static function update_seller_notes( $page = 0 ) {
		if ( ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}

		global $wpdb;

		$offset = 10 * $page;
		$query  = $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE post_type = %s ORDER BY 'ID' ASC LIMIT 10 OFFSET %d", 'listings', $offset );
		$posts  = $wpdb->get_results( $query );//phpcs:ignore

		if ( count( $posts ) > 0 ) {
			foreach ( $posts as $post ) {
				$seller_note = get_post_meta( $post->ID, 'listing_seller_note', true );
				if ( ! empty( $seller_note ) ) {
					$wpdb->update(
						$wpdb->posts,
						array(
							'post_content' => $seller_note,
						),
						array(
							'ID' => $post->ID,
						),
						array(
							'%s',
						),
						array(
							'%d',
						)
					);
				}
			}

			self::update_seller_notes( $page + 1 );
		}
	}
}
