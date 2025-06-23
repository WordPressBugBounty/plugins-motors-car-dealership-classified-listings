<?php
namespace MotorsVehiclesListing\ListingManager\Pages;

use MotorsVehiclesListing\ListingManager\Abstracts\Page;

class Location extends Page {
	protected function data(): array {
		return array(
			'title'     => __( 'Location', 'stm_vehicles_listing' ),
			'menu_name' => __( 'Location', 'stm_vehicles_listing' ),
			'icon'      => 'motors-icons-pin-map',
		);
	}

	public function has_preview(): bool {
		return true;
	}

	public function get_preview_url(): string {
		return STM_LISTINGS_URL . '/assets/images/listing-manager/page-preview/location.png';
	}

	public function save( array $data ): array {
		$this->update_text_meta( $data, 'stm_car_location' );
		return array();
	}
}
