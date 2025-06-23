<?php
namespace MotorsVehiclesListing\ListingManager\Pages;

use MotorsVehiclesListing\ListingManager\Abstracts\Page;

class OtherDetails extends Page {

	protected function data(): array {
		return array(
			'title'     => __( 'Additional Info', 'stm_vehicles_listing' ),
			'menu_name' => __( 'Additional Info', 'stm_vehicles_listing' ),
			'icon'      => 'motors-icons-mvl-layers',
		);
	}

	public function save( array $data ): array {
		$this->update_text_meta( $data, 'vin_number' );
		$this->update_numeric_meta( $data, 'stock_number' );
		$this->update_date_meta( $data, 'registration_date' );
		$this->update_numeric_meta( $data, 'city_mpg' );
		$this->update_numeric_meta( $data, 'highway_mpg' );
		$this->update_numeric_meta( $data, 'car_brochure' );

		$post_id = $data['post_id'];

		$history          = isset( $data['certificate_media_file_name'] ) ? array_map( 'sanitize_text_field', $data['certificate_media_file_name'] ) : array();
		$history_link     = isset( $data['certificate_media_links'] ) ? array_map( 'sanitize_text_field', $data['certificate_media_links'] ) : array();
		$certified_logo_1 = isset( $data['certificate_media_image'] ) ? array_map( 'sanitize_text_field', $data['certificate_media_image'] ) : array();

		update_post_meta( $post_id, 'history', isset( $history[0] ) ? $history[0] : '' );
		update_post_meta( $post_id, 'history_link', isset( $history_link[0] ) ? $history_link[0] : '' );
		update_post_meta( $post_id, 'certified_logo_1', isset( $certified_logo_1[0] ) ? $certified_logo_1[0] : '' );
		update_post_meta( $post_id, 'history_2', isset( $history[1] ) ? $history[1] : '' );
		update_post_meta( $post_id, 'certified_logo_2_link', isset( $history_link[1] ) ? $history_link[1] : '' );
		update_post_meta( $post_id, 'certified_logo_2', isset( $certified_logo_1[1] ) ? $certified_logo_1[1] : '' );

		return array();
	}

	public function get_certificates( $post_id ): array {
		$certificates = array(
			array(
				'name'  => get_post_meta( $post_id, 'history', true ),
				'link'  => get_post_meta( $post_id, 'history_link', true ),
				'image' => get_post_meta( $post_id, 'certified_logo_1', true ),
			),
		);

		$certificate_2 = array(
			'name'  => get_post_meta( $post_id, 'history_2', true ),
			'link'  => get_post_meta( $post_id, 'certified_logo_2_link', true ),
			'image' => get_post_meta( $post_id, 'certified_logo_2', true ),
		);

		if ( $certificate_2['name'] || $certificate_2['link'] || $certificate_2['image'] ) {
			$certificates[] = $certificate_2;
		}

		return $certificates;
	}
}
