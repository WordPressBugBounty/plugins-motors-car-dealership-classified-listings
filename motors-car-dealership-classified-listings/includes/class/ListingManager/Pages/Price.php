<?php
namespace MotorsVehiclesListing\ListingManager\Pages;

use MotorsVehiclesListing\ListingManager\Abstracts\Page;

class Price extends Page {

	protected function data(): array {
		return array(
			'title'     => __( 'Price', 'stm_vehicles_listing' ),
			'menu_name' => __( 'Price', 'stm_vehicles_listing' ),
			'icon'      => 'motors-icons-mvl-wallet',
		);
	}

	public function save( array $data ): array {
		if ( apply_filters( 'mvl_listing_manager_use_rental_price', false, absint( $data['post_id'] ?? 0 ) ) ) {
			return $this->save_rental_price( $data );
		}

		$valdation_methods = array(
			'update_numeric_meta' => array(
				'price',
				'sale_price',
			),
			'update_text_meta'    => array(
				'regular_price_label',
				'regular_price_description',
				'special_price_label',
				'instant_savings_label',
				'car_price_form_label',
			),
			'update_boolean_meta' => array(
				'car_price_form',
			),
		);

		foreach ( $valdation_methods as $method => $keys ) {
			foreach ( $keys as $key ) {
				$this->$method( $data, $key );
			}
		}

		$post_id       = $data['post_id'];
		$genuine_price = '';

		if ( isset( $data['sale_price'] ) && ! empty( $data['sale_price'] ) ) {
			$genuine_price = $data['sale_price'];
		} elseif ( isset( $data['price'] ) && ! empty( $data['price'] ) ) {
			$genuine_price = $data['price'];
		}

		if ( ! empty( $genuine_price ) ) {
			update_post_meta( $post_id, 'stm_genuine_price', $genuine_price );
		}

		return array();
	}

	private function save_rental_price( array $data ): array {
		$post_id   = absint( $data['post_id'] ?? 0 );
		$price     = isset( $data['price'] ) ? $this->sanitize_rental_amount( $data['price'] ) : '';
		$pay_later = isset( $data['mvl_rental_pay_later_price'] ) ? $this->sanitize_rental_amount( $data['mvl_rental_pay_later_price'] ) : '';

		update_post_meta( $post_id, 'price', $price );
		update_post_meta( $post_id, 'stm_genuine_price', $price );
		update_post_meta( $post_id, 'mvl_rental_deposit', $pay_later );
		update_post_meta( $post_id, 'mvl_rental_pay_later_price', $pay_later );
		update_post_meta( $post_id, 'mvl_rental_pay_now_label', isset( $data['mvl_rental_pay_now_label'] ) ? sanitize_text_field( $data['mvl_rental_pay_now_label'] ) : '' );
		update_post_meta( $post_id, 'mvl_rental_pay_later_label', isset( $data['mvl_rental_pay_later_label'] ) ? sanitize_text_field( $data['mvl_rental_pay_later_label'] ) : '' );
		$this->update_boolean_meta( $data, 'mvl_rental_pay_later_enabled' );
		delete_post_meta( $post_id, 'mvl_rental_class_id' );
		delete_post_meta( $post_id, 'sale_price' );

		return array();
	}

	private function sanitize_rental_amount( $value ): string {
		$value = is_string( $value ) ? preg_replace( '/[^0-9.]/', '', $value ) : $value;

		if ( '' === $value || ! is_numeric( $value ) ) {
			return '';
		}

		return (string) max( 0, round( (float) $value, 2 ) );
	}

	public function has_preview(): bool {
		return ! apply_filters( 'mvl_is_rental_business_type', false );
	}

	public function get_preview_url(): string {
		return STM_LISTINGS_URL . '/assets/images/listing-manager/page-preview/price.png';
	}

}
