<?php
namespace MotorsVehiclesListing\Features\WooCommerce;

use MotorsVehiclesListing\Libs\Traits\Instance;
use MotorsVehiclesListing\Libs\Traits\ProtectedHooks;

class ListingCheckoutHooks {
	use Instance;
	use ProtectedHooks;

	public function __construct() {
		$this->add_action( 'woocommerce_checkout_create_order_line_item', 20, 3 );
		$this->add_action( 'woocommerce_payment_complete', 20, 1 );
		$this->add_action( 'woocommerce_order_status_completed', 20, 1 );
		$this->add_action( 'woocommerce_order_status_failed', 20, 1 );
		$this->add_action( 'woocommerce_order_status_refunded', 20, 1 );
		$this->add_action( 'woocommerce_order_status_cancelled', 20, 1 );

		$this->add_filter( 'woocommerce_data_stores', 10, 1 );
		$this->add_filter( 'woocommerce_checkout_create_order_line_item_object', 20, 3 );
		$this->add_filter( 'woocommerce_get_order_item_classname', 20, 3 );
		$this->add_filter( 'woocommerce_payment_complete_order_status', 20, 3 );
		$this->add_filter( 'woocommerce_add_to_cart_validation', 20, 2 );
	}

	protected function woocommerce_data_stores( array $stores ): array {
		$stores['product'] = 'MotorsVehiclesListing\Features\WooCommerce\STMListingDataStoreCPT';
		return $stores;
	}

	protected function woocommerce_checkout_create_order_line_item_object( $item, $cart_item_key, $values ) {
		$product = $values['data'];

		$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );

		if ( class_exists( '\STMMultiListing' ) ) {
			$slugs = \STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				$post_types = array_merge( $post_types, $slugs );
			}
		}

		if ( in_array( get_post_type( $product->get_id() ), $post_types, true ) ) {
			wc_delete_product_transients( $product->get_id() );
			$transients = array(
				'wc_var_prices_',
				'wc_product_children_',
				'wc_related_',
				'wc_product_data_',
				'wc_product_data_store_',
			);

			foreach ( $transients as $prefix ) {
				delete_transient( $prefix . $product->get_id() );
			}
			return new STMWCOrderItemProduct();
		}

		return $item;
	}

	protected function woocommerce_get_order_item_classname( $classname, $item_type, $order_item_id ) {
		global $wpdb;

		$prepare            = $wpdb->prepare(
			"SELECT meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d AND meta_key = %s",
			$order_item_id,
			'_stm-motors-listing'
		);
		$is_pay_per_listing = $wpdb->get_var( $prepare ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( 'yes' === $is_pay_per_listing && 'line_item' === $item_type ) {
			$classname = 'MotorsVehiclesListing\Features\WooCommerce\STMWCOrderItemProduct';
		}

		return $classname;
	}

	protected function woocommerce_checkout_create_order_line_item( $item, $cart_item_key, $values ) {
		$product = $values['data'];

		$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );

		if ( class_exists( '\STMMultiListing' ) ) {
			$slugs = \STMMultiListing::stm_get_listing_type_slugs();
			if ( ! empty( $slugs ) ) {
				$post_types = array_merge( $post_types, $slugs );
			}
		}

		$product_id = $product->get_id();

		if ( in_array( get_post_type( $product_id ), $post_types, true ) ) {
			$item->update_meta_data( '_stm-motors-listing', 'yes' );

			if ( ! empty( get_post_meta( $product_id, 'car_make_featured_status', true ) ) ) {
				update_post_meta( $product_id, 'car_make_featured_status', 'processing' );
				$item->update_meta_data( '_car_make_featured', 'yes' );
			} elseif ( ! empty( get_post_meta( $product_id, 'is_sell_online_status', true ) ) ) {
				update_post_meta( $product_id, 'is_sell_online_status', 'processing' );
				$item->update_meta_data( '_is_sell_online_car', 'yes' );
			} else {
				update_post_meta( $product_id, 'pay_per_order_id', $item->get_order_id() );
				$item->update_meta_data( '_order_pay_per_listing', 'yes' );
			}
		}
	}

	protected function woocommerce_payment_complete_order_status( $status, $order_id, $order ) {
		if ( ! $order->get_id() ) { // Order must exist.
			return $status;
		}

		if ( 'completed' === $status ) {
			return $status;
		}

		// Get order items
		$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

		// Iterate over order items and get product ids
		foreach ( $order_items as $order_item ) {
			$is_listing = $order_item->get_meta( '_stm-motors-listing' );

			if ( 'yes' !== $is_listing ) {
				continue;
			}

			$status = 'completed';
		}

		return $status;
	}

	protected function woocommerce_payment_complete( $order_id ) {
		// Load order object
		$order = wc_get_order( $order_id );

		// Check if order was loaded
		if ( ! $order ) {
			return;
		}

		$order_status = $order->get_status();

		// Get order items
		$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

		// Iterate over order items and get product ids
		foreach ( $order_items as $order_item ) {
			$product = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );

			if ( 'product' !== get_post_type( $product->get_id() ) ) {
				$pay_per_listing = $order_item->get_meta( '_order_pay_per_listing' );

				if ( 'yes' !== $pay_per_listing ) {
					continue;
				}

				$product = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );

				if ( ! $product ) {
					continue;
				}

				$to      = get_bloginfo( 'admin_email' );
				$args    = array(
					'first_name'    => $order->get_billing_first_name(),
					'last_name'     => $order->get_billing_last_name(),
					'email'         => $order->get_billing_email(),
					'order_id'      => $order->get_id(),
					'order_status'  => $order_status,
					'listing_title' => $product->get_title(),
					'car_id'        => $product->get_id(),
				);
				$subject = stm_generate_subject_view( 'pay_per_listing', $args );
				$body    = stm_generate_template_view( 'pay_per_listing', $args );

				add_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );

				wp_mail( $to, $subject, $body );

				remove_filter( 'wp_mail_content_type', 'stm_set_html_content_type_mail' );
			}
		}
	}

	protected function woocommerce_add_to_cart_validation( $passed, $product_id ) {
		if ( ! apply_filters( 'motors_vl_perpay_stock', false ) ) {
			return $passed;
		}

		$car_stock = (int) get_post_meta( $product_id, 'stm_car_stock', true );

		if ( get_post_type( $product_id ) === apply_filters( 'stm_listings_post_type', 'listings' ) && $car_stock <= 0 ) {
			$passed = false;
			wc_add_notice( __( 'Sorrys, item is out of stock and cannot be added to cart', 'stm_vehicles_listing' ), 'error' );
		}

		return $passed;
	}

	protected function woocommerce_order_status_completed( $order_id ) {
		// Load order object
		$order = wc_get_order( $order_id );

		// Check if order was loaded
		if ( ! $order ) {
			return;
		}

		// Get order items
		$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

		// Iterate over order items and get product ids
		foreach ( $order_items as $order_item ) {
			$is_sell_online = $order_item->get_meta( '_is_sell_online_car' );

			if ( 'yes' !== $is_sell_online ) {
				continue;
			}

			$product = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );

			if ( ! $product ) {
				continue;
			}

			$vehicle_id = $product->get_id();

			if ( ! empty( $vehicle_id ) ) {
				$car_stock = (int) get_post_meta( $vehicle_id, 'stm_car_stock', true );

				if ( is_numeric( $car_stock ) ) {
					if ( 1 === $car_stock ) {
						update_post_meta( $vehicle_id, 'car_mark_as_sold', 'on' );
					}

					update_post_meta( $vehicle_id, 'stm_car_stock', $car_stock - 1 );
				}
			}
		}
	}

	protected function woocommerce_order_status_failed( $order_id ) {
		$order = wc_get_order( $order_id );
		// Load order object
		$order = wc_get_order( $order_id );

		// Check if order was loaded
		if ( ! $order ) {
			return;
		}

		// Get order items
		$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

		// Iterate over order items and get product ids
		foreach ( $order_items as $order_item ) {
			$is_sell_online = $order_item->get_meta( '_is_sell_online_car' );

			if ( 'yes' !== $is_sell_online ) {
				continue;
			}

			$product = apply_filters( 'woocommerce_order_item_product', $order_item->get_product(), $order_item );

			if ( ! $product ) {
				continue;
			}

			$vehicle_id = $product->get_id();

			if ( ! empty( $vehicle_id ) ) {
				$car_stock = (int) get_post_meta( $vehicle_id, 'stm_car_stock', true );

				if ( is_numeric( $car_stock ) ) {
					if ( ! empty( get_post_meta( $vehicle_id, 'car_mark_as_sold', true ) ) ) {
						update_post_meta( $vehicle_id, 'car_mark_as_sold', '' );
					}

					update_post_meta( $vehicle_id, 'stm_car_stock', $car_stock + 1 );
				}
			}
		}
	}

	protected function woocommerce_order_status_refunded( $order_id ) {
		$this->woocommerce_order_status_failed( $order_id );
	}

	protected function woocommerce_order_status_cancelled( $order_id ) {
		$this->woocommerce_order_status_failed( $order_id );
	}
}
