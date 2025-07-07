<?php
namespace MotorsVehiclesListing\Features\WooCommerce;

class STMWCOrderItemProduct extends \WC_Order_Item_Product { //phpcs:ignore
	public function set_product_id( $value ) {
		$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );

		if ( class_exists( '\STMMultiListing' ) ) {
			$slugs = \STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				$post_types = array_merge( $post_types, $slugs );
			}
		}

		if ( $value > 0 && ! in_array( get_post_type( absint( $value ) ), $post_types, true ) ) {
			$this->error( 'order_item_product_invalid_product_id', __( 'Invalid product ID', 'woocommerce' ) );
		}

		$this->set_prop( 'product_id', absint( $value ) );
	}
}
