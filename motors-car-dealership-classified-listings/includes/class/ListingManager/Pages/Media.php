<?php
namespace MotorsVehiclesListing\ListingManager\Pages;

use MotorsVehiclesListing\ListingManager\Abstracts\Page;

class Media extends Page {

	protected function data(): array {
		return array(
			'title'     => __( 'Images & Videos', 'stm_vehicles_listing' ),
			'menu_name' => __( 'Images & Videos', 'stm_vehicles_listing' ),
			'icon'      => 'motors-icons-mvl-media',
		);
	}

	public function get_videos(): array {
		$videos_urls = $this->get_listing_id() ? get_post_meta( $this->get_listing_id(), 'gallery_videos', true ) : array();
		$posters_ids = $this->get_listing_id() ? get_post_meta( $this->get_listing_id(), 'gallery_videos_posters', true ) : array();

		$result = array();

		if ( ! is_array( $videos_urls ) ) {
			$videos_urls = array();
		}

		if ( ! is_array( $posters_ids ) ) {
			$posters_ids = array();
		}

		foreach ( $videos_urls as $key => $video_url ) {
			$result[ $key ] = array(
				'url'        => $video_url,
				'poster_id'  => is_array( $posters_ids ) && isset( $posters_ids[ $key ] ) ? $posters_ids[ $key ] : '',
				'poster_url' => is_array( $posters_ids ) && isset( $posters_ids[ $key ] ) ? wp_get_attachment_image_url( $posters_ids[ $key ], 'full' ) : '',
			);
		}

		return $result;
	}

	public function has_preview(): bool {
		return true;
	}

	public function get_preview_url(): string {
		return STM_LISTINGS_URL . '/assets/images/listing-manager/page-preview/media.png';
	}

	public function save( array $data ): array {
		if ( isset( $data['files'] ) ) {
			$files = $data['files'];

			if ( isset( $files['name'] ) && isset( $files['name']['gallery'] ) ) {
				$image_file = array(
					'name'     => isset( $files['name']['gallery'][0] ) ? $files['name']['gallery'][0] : $files['name']['gallery'],
					'type'     => isset( $files['type']['gallery'][0] ) ? $files['type']['gallery'][0] : $files['type']['gallery'],
					'tmp_name' => isset( $files['tmp_name']['gallery'][0] ) ? $files['tmp_name']['gallery'][0] : $files['tmp_name']['gallery'],
					'error'    => isset( $files['error']['gallery'][0] ) ? $files['error']['gallery'][0] : $files['error']['gallery'],
					'size'     => isset( $files['size']['gallery'][0] ) ? $files['size']['gallery'][0] : $files['size']['gallery'],
				);
				$this->upload_listing_file_and_send_json( $data['post_id'], $image_file, 'image', $this->get_id() . '[gallery][]' );
			}

			if ( isset( $files['name'] ) && isset( $files['name']['video']['image'] ) ) {
				$video_file = array(
					'name'     => isset( $files['name']['video']['image'][0] ) ? $files['name']['video']['image'][0] : $files['name']['video']['image'],
					'type'     => isset( $files['type']['video']['image'][0] ) ? $files['type']['video']['image'][0] : $files['type']['video']['image'],
					'tmp_name' => isset( $files['tmp_name']['video']['image'][0] ) ? $files['tmp_name']['video']['image'][0] : $files['tmp_name']['video']['image'],
					'error'    => isset( $files['error']['video']['image'][0] ) ? $files['error']['video']['image'][0] : $files['error']['video']['image'],
					'size'     => isset( $files['size']['video']['image'][0] ) ? $files['size']['video']['image'][0] : $files['size']['video']['image'],
				);

				$this->upload_listing_file_and_send_json( $data['post_id'], $video_file, 'image', $this->get_id() . '[video][image][]' );
			}
		}

		$validated_videos_urls    = array();
		$validated_videos_posters = array();

		if ( isset( $data['gallery'] ) && ! empty( $data['gallery'] ) ) {
			$thumbnail_key         = array_key_first( $data['gallery'] );
			$data['_thumbnail_id'] = $data['gallery'][ $thumbnail_key ];

			unset( $data['gallery'][ $thumbnail_key ] );

			$this->update_numeric_meta( $data, '_thumbnail_id' );
			$this->update_numeric_array_meta( $data, 'gallery' );
		} else {
			update_post_meta( $data['post_id'], '_thumbnail_id', '' );
			update_post_meta( $data['post_id'], 'gallery', '' );
		}

		if ( isset( $data['video'] ) && ! empty( $data['video'] ) ) {
			foreach ( $data['video']['url'] as $key => $video_url ) {
				if ( ! empty( $video_url ) || ! empty( $data['video']['image'][ $key ] ) ) {
					$validated_videos_urls[ $key ]    = esc_url_raw( $video_url );
					$validated_videos_posters[ $key ] = isset( $data['video']['image'][ $key ] ) && is_numeric( $data['video']['image'][ $key ] ) ? $data['video']['image'][ $key ] : '';
				}
			}
		} else {
			update_post_meta( $data['post_id'], 'gallery_videos', '' );
			update_post_meta( $data['post_id'], 'gallery_videos_posters', '' );
		}

		update_post_meta( $data['post_id'], 'gallery_videos', $validated_videos_urls );
		update_post_meta( $data['post_id'], 'gallery_videos_posters', $validated_videos_posters );

		return $data;
	}

}
