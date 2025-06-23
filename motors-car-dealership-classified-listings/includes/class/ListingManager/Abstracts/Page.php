<?php
namespace MotorsVehiclesListing\ListingManager\Abstracts;

use MotorsVehiclesListing\Libs\Traits\ArgsSetter;
use MotorsVehiclesListing\Libs\Traits\SnakeCaseClassName;

abstract class Page {
	use ArgsSetter;
	use SnakeCaseClassName;

	protected string $id          = '';
	protected string $title       = '';
	protected string $menu_name   = '';
	protected string $url         = '';
	protected string $icon        = '';
	protected string $preview_url = '';

	public function __construct() {
		$this->set_args( $this->data() );
		$this->id  = $this->get_snake_case_class_name();
		$this->url = apply_filters( 'mvl_listing_manager_url', array() ) . '?page=' . $this->id;

		if ( isset( $_GET['id'] ) ) {
			$this->url .= '&id=' . intval( $_GET['id'] );
		}
	}

	public function render(): void {
		$template_path = 'listing-manager/pages/' . str_replace( '_', '-', $this->id );
		do_action( 'stm_listings_load_template', $template_path, array( 'listing_manager_page' => $this ) );
	}

	public function is_active(): bool {
		return isset( $_GET['page'] ) && $this->id === $_GET['page'];
	}

	public function get_icon(): string {
		return $this->icon;
	}

	public function get_id(): string {
		return $this->id;
	}

	public function get_url(): string {
		return $this->url;
	}

	public function get_title(): string {
		return $this->title;
	}

	public function get_menu_name(): string {
		return $this->menu_name;
	}

	public function has_preview(): bool {
		return (bool) $this->preview_url;
	}

	public function get_preview_url(): string {
		return $this->preview_url;
	}

	public function get_listing_id(): int {
		return apply_filters( 'mvl_listing_manager_item_id', 0 );
	}

	public function get_listing_images_ids(): array {
		if ( ! $this->get_listing_id() ) {
			return array();
		}

		$thumb      = get_post_thumbnail_id( $this->get_listing_id() );
		$gallery    = get_post_meta( $this->get_listing_id(), 'gallery', true );
		$images_ids = array();

		if ( $thumb ) {
			$images_ids[] = $thumb;
		}

		if ( $gallery && is_array( $gallery ) ) {
			$images_ids = array_merge( $images_ids, $gallery );
		}

		$images_ids = array_filter(
			$images_ids,
			function( $image_id ) {
				return get_attached_file( $image_id );
			}
		);

		return $images_ids;
	}

	abstract public function save( array $data ): array;

	abstract protected function data(): array;

	protected function upload_file( int $listing_id, array $file, string $mime = 'image' ): array {
		$result = array(
			'success'   => false,
			'attach_id' => 0,
			'url'       => '',
			'message'   => '',
		);

		$mime_list = array(
			'image' => array(
				'jpg|jpeg' => 'image/jpeg',
				'png'      => 'image/png',
			),
			'pdf'   => array(
				'pdf' => 'application/pdf',
			),
		);

		$upload_overrides = array(
			'test_form' => false,
			'mimes'     => $mime_list[ $mime ],
			'action'    => 'mvl_listing_manager_' . $mime . '_upload',
		);

		$file_type = wp_check_filetype( $file['name'], $mime_list[ $mime ] );

		if ( ! in_array( $file_type['type'], $mime_list[ $mime ], true ) ) {
			$result['message'] = esc_html__( 'Invalid file type', 'stm_vehicles_listing' );
			return $result;
		}

		$uploaded = wp_handle_upload( $file, $upload_overrides );

		if ( $uploaded && empty( $uploaded['error'] ) ) {
			$filetype = wp_check_filetype( basename( $uploaded['file'] ), null );

			// Insert attachment to the database
			$attach_id = wp_insert_attachment(
				array(
					'guid'           => $uploaded['url'],
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $uploaded['file'] ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				),
				$uploaded['file'],
				$listing_id,
				true
			);

			if ( is_wp_error( $attach_id ) ) {
				$result['message'] = $attach_id->get_error_message();
			} else {
				$result['success']   = true;
				$result['attach_id'] = $attach_id;
				$result['url']       = wp_get_attachment_image_url( $attach_id, 'full' );

				// Generate meta data
				wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $uploaded['file'] ) );
			}
		} else {
			$result['message'] = $uploaded['error'];
		}

		return $result;
	}

	protected function upload_listing_file_and_send_json( int $listing_id, array $file, string $mime = 'image', string $input_name = '' ): void {
		$result            = $this->upload_file( $listing_id, $file, $mime );
		$result['post_id'] = $listing_id;

		if ( $result['success'] ) {
			$result['uploaded_item'] = array(
				'name'       => $file['name'],
				'url'        => $result['url'],
				'id'         => $result['attach_id'],
				'input_name' => $input_name,
			);

			if ( $input_name ) {
				$result['uploaded_item']['input_name'] = $input_name;
			}

			$result['callback'] = array(
				'name' => 'sendFormData',
				'args' => array(
					'post_id'       => $listing_id,
					'progress_page' => $this->get_id(),
				),
			);

			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}

	protected function update_numeric_meta( array $data, string $meta_key ): void {
		delete_post_meta( $data['post_id'], $meta_key );

		if ( isset( $data[ $meta_key ] ) && '' !== $data[ $meta_key ] && is_numeric( $data[ $meta_key ] ) ) {
			update_post_meta( $data['post_id'], $meta_key, $data[ $meta_key ] );
		} else {
			update_post_meta( $data['post_id'], $meta_key, '' );
		}
	}

	protected function update_text_meta( array $data, string $meta_key ): void {
		delete_post_meta( $data['post_id'], $meta_key );

		if ( isset( $data[ $meta_key ] ) ) {
			update_post_meta( $data['post_id'], $meta_key, sanitize_text_field( $data[ $meta_key ] ) );
		} else {
			update_post_meta( $data['post_id'], $meta_key, '' );
		}
	}

	protected function update_boolean_meta( array $data, string $meta_key ): void {
		delete_post_meta( $data['post_id'], $meta_key );

		if ( isset( $data[ $meta_key ] ) ) {
			update_post_meta( $data['post_id'], $meta_key, 'on' );
		} else {
			update_post_meta( $data['post_id'], $meta_key, '' );
		}
	}

	protected function update_numeric_array_meta( array $data, string $meta_key ): void {
		delete_post_meta( $data['post_id'], $meta_key );

		if ( isset( $data[ $meta_key ] ) ) {
			$data[ $meta_key ] = array_filter( $data[ $meta_key ], 'is_numeric' );
			update_post_meta( $data['post_id'], $meta_key, $data[ $meta_key ] );
		} else {
			update_post_meta( $data['post_id'], $meta_key, '' );
		}
	}

	protected function implode_and_update_text_array_meta( array $data, string $meta_key ): void {
		delete_post_meta( $data['post_id'], $meta_key );

		if ( isset( $data[ $meta_key ] ) && is_array( $data[ $meta_key ] ) ) {
			$data[ $meta_key ] = array_filter( $data[ $meta_key ], 'is_string' );
			$data[ $meta_key ] = array_filter( $data[ $meta_key ], 'strlen' );
			$data[ $meta_key ] = array_map( 'sanitize_text_field', array_unique( $data[ $meta_key ], SORT_STRING ) );

			update_post_meta( $data['post_id'], $meta_key, implode( ',', $data[ $meta_key ] ) );
		} else {
			update_post_meta( $data['post_id'], $meta_key, '' );
		}
	}

	protected function update_date_meta( array $data, string $meta_key ): void {
		delete_post_meta( $data['post_id'], $meta_key );

		if ( isset( $data[ $meta_key ] ) && preg_match( '/^\d{2}\/\d{2}\/\d{4}$/', $data[ $meta_key ] ) ) {
			list( $day, $month, $year ) = explode( '/', $data[ $meta_key ] );

			if ( checkdate( (int) $month, (int) $day, (int) $year ) ) {
				update_post_meta( $data['post_id'], $meta_key, $data[ $meta_key ] );
			} else {
				update_post_meta( $data['post_id'], $meta_key, '' );
			}
		} else {
			update_post_meta( $data['post_id'], $meta_key, '' );
		}
	}
}
