<?php


namespace MotorsVehiclesListing\User;

class UserRoles {

	public function __construct() {
		add_action( 'init', array( $this, 'mvl_add_user_role_caps' ) );
		add_action( 'admin_init', array( $this, 'add_roles' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu_for_listing_manager' ) );
	}

	public function add_roles() {
		$exist_listing_manager_role = get_role( 'listing_manager' );
		if ( empty( $exist_listing_manager_role ) ) {
			add_role(
				'listing_manager',
				'Listing Manager',
				array(
					'read'                         => true,
					'list_users'                   => true,
					'moderate_comments'            => false,
					'manage_categories'            => true,
					'upload_files'                 => true,
					'publish_posts'                => true,
					'edit_posts'                   => true,
					'delete_posts'                 => true,
					'publish_listings_post'        => true,
					'edit_listings_post'           => true,
					'read_listings_post'           => true,
					'delete_listings_post'         => true,
					'publish_listings_posts'       => true,
					'edit_listings_posts'          => true,
					'delete_listings_posts'        => true,
					'edit_others_listings_posts'   => true,
					'delete_others_listings_posts' => true,
					'read_private_listings_posts'  => true,
				)
			);

		}
	}

	public function admin_menu_for_listing_manager() {
		$current_user = wp_get_current_user();

		if ( ! empty( $current_user->roles ) && 'listing_manager' === $current_user->roles[0] ) {
			$post_types = array(
				'listings',
			);

			foreach ( $post_types as $post_type ) {
				$post_type_data = get_post_type_object( $post_type );

				if ( empty( $post_type_data ) ) {
					continue;
				}

				add_menu_page(
					$post_type_data->label,
					$post_type_data->label,
					'listing_manager',
					'/edit.php?post_type=' . $post_type,
					'',
					'dashicons-car',
					6
				);
			}

			add_menu_page(
				__( 'Custom Fields', 'stm_vehicles_listing' ),
				__( 'Custom Fields', 'stm_vehicles_listing' ),
				'listing_manager',
				'listing_categories',
				'stm_listings_vehicle_listing_settings_page',
				'',
				7
			);
		}
	}

	public function mvl_add_user_role_caps() {
		$listing_manager   = array();
		$admin_users       = array();
		$admin_users[]     = get_role( 'administrator' );
		$listing_manager[] = get_role( 'listing_manager' );

		if ( ! empty( $admin_users ) ) {
			foreach ( $admin_users as $user ) {
				if ( empty( $user ) ) {
					continue;
				}

				$user->add_cap( 'edit_listings_post' );
				$user->add_cap( 'read_listings_post' );
				$user->add_cap( 'delete_listings_post' );

				$caps = array(
					'publish',
					'delete',
					'delete_others',
					'delete_private',
					'delete_published',
					'edit',
					'edit_others',
					'edit_private',
					'edit_published',
					'read_private',
				);

				foreach ( $caps as $cap ) {
					$user->add_cap( "{$cap}_listings_posts" );
				}
			}
		}

		if ( ! empty( $listing_manager ) ) {
			foreach ( $listing_manager as $user ) {
				if ( empty( $user ) ) {
					continue;
				}

				$user->add_cap( 'publish_posts' );
				$user->add_cap( 'edit_posts' );
				$user->add_cap( 'delete_posts' );
				$user->add_cap( 'publish_listings_post' );
				$user->add_cap( 'edit_listings_post' );
				$user->add_cap( 'read_listings_post' );
				$user->add_cap( 'delete_listings_post' );

				foreach ( array( 'publish', 'delete', 'edit', 'edit_others', 'read_private' ) as $cap ) {
					$user->add_cap( "{$cap}_listings_posts" );
				}
			}
		}
	}
}
