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
		$valdation_methods = array(
			'update_numeric_meta' => array(
				'price',
				'sale_price',
			),
			'update_text_meta'    => array(
				'regular_price_label',
				'regular_price_description',
				'sale_price_label',
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

		return array();
	}

	public function has_preview(): bool {
		return true;
	}

	public function get_preview_url(): string {
		return STM_LISTINGS_URL . '/assets/images/listing-manager/page-preview/price.png';
	}

}
