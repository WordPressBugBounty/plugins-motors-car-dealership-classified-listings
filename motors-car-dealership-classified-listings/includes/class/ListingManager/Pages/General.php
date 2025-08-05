<?php
namespace MotorsVehiclesListing\ListingManager\Pages;

use MotorsVehiclesListing\ListingManager\Abstracts\Page;

class General extends Page {
	protected function data(): array {
		return array(
			'title'     => __( 'Summary', 'stm_vehicles_listing' ),
			'menu_name' => __( 'Summary', 'stm_vehicles_listing' ),
			'icon'      => 'motors-icons-steering-wheel-skin',
		);
	}

	public function is_active(): bool {
		return ! isset( $_GET['page'] ) || parent::is_active();
	}

	public function save( array $data ): array {
		$title     = sanitize_text_field( $data['title'] );
		$post_data = array(
			'ID'           => intval( $data['post_id'] ),
			'post_title'   => $title ? $title : __( 'Untitled', 'stm_vehicles_listing' ),
			'post_content' => $this->validate_tiny_mce( $data['description'] ),
			'post_type'    => 'listings',
			'post_status'  => sanitize_text_field( $data['post_status'] ),
		);

		wp_update_post( $post_data );

		$post_author = get_post_field( 'post_author', $data['post_id'] );

		if ( $post_author ) {
			update_post_meta( $data['post_id'], 'stm_car_user', $post_author );
		}

		$valdation_methods = array(
			'update_text_meta'    => array(
				'badge_text',
				'badge_bg_color',
			),
			'update_boolean_meta' => array(
				'car_mark_as_sold',
				'special_car',
			),
		);

		foreach ( $valdation_methods as $method => $keys ) {
			foreach ( $keys as $key ) {
				$this->$method( $data, $key );
			}
		}

		return array();
	}

	protected function validate_tiny_mce( string $content ): string {
		$content = preg_replace_callback(
			'/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/i',
			function( $matches ) {
				$r = intval( $matches[1] );
				$g = intval( $matches[2] );
				$b = intval( $matches[3] );

				return sprintf( '#%02x%02x%02x', $r, $g, $b );
			},
			$content
		);

		$content = preg_replace_callback(
			'/rgba\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*[\d.]+\s*\)/i',
			function( $matches ) {
				$r = intval( $matches[1] );
				$g = intval( $matches[2] );
				$b = intval( $matches[3] );
				$a = floatval( $matches[4] );

				$r = max( 0, min( 255, intval( $r ) ) );
				$g = max( 0, min( 255, intval( $g ) ) );
				$b = max( 0, min( 255, intval( $b ) ) );
				$a = max( 0, min( 1, floatval( $a ) ) );

				if ( 0 === $a ) {
					return 'transparent';
				}

				if ( 1 === $a ) {
					return sprintf( '#%02x%02x%02x', $r, $g, $b );
				}

				return sprintf( '#%02x%02x%02x%02x', $r, $g, $b, round( $a * 255 ) );
			},
			$content
		);

		return wp_kses( $content, mvl_wp_kses_allowed_html_in_content( wp_kses_allowed_html( 'post' ) ) );
	}
}
