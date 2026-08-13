<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user = wp_get_current_user();

$vars = get_queried_object();

if ( ! empty( $_GET['view-myself'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
	do_action( 'stm_listings_load_template', 'user/public/user' );
} else {
	if ( $user->ID !== $vars->ID ) {
		do_action( 'stm_listings_load_template', 'user/public/user' );
	} else {
		if (
			class_exists( 'MotorsVehiclesListing\\Pro\\BusinessType\\Resolver' )
			&& MotorsVehiclesListing\Pro\BusinessType\Resolver::is_rental()
			&& defined( 'STM_LISTINGS_PRO_PATH' )
		) {
			$rental_account_template = STM_LISTINGS_PRO_PATH . '/templates/rental-form/account.php';

			if ( file_exists( $rental_account_template ) ) {
				require $rental_account_template;
				return;
			}
		}

		do_action( 'stm_listings_load_template', 'user/private/user' );
	}
}
