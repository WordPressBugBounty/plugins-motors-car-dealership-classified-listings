<?php
namespace MotorsVehiclesListing\ListingManager\Pages;

use MotorsVehiclesListing\ListingManager\Abstracts\Page;

class Option extends Page {

	protected function data(): array {
		return array(
			'title'     => 'Details',
			'menu_name' => 'Details',
			'icon'      => 'motors-icons-mvl-settings',
		);
	}

	public function save( array $data ): array {
		$listing_id = $data['post_id'];
		$options    = array_column(
			array_filter(
				$this->get_listing_options(),
				function( $option ) {
					return isset( $option['slug'] ) && ! empty( $option['slug'] );
				}
			),
			'slug'
		);

		foreach ( $data['terms'] as $key => $value ) {
			if ( in_array( $key, $options, true ) ) {
				delete_post_meta( $listing_id, $key );

				if ( $value ) {
					$term = get_term_by( 'slug', $value, $key );

					if ( $term && ! is_wp_error( $term ) ) {
						update_post_meta( $listing_id, $key, $value );
					}
				}
			}
		}

		foreach ( $data['numeric'] as $key => $value ) {
			if ( in_array( $key, $options, true ) ) {
				if ( is_numeric( $value ) || empty( $value ) ) {
					delete_post_meta( $listing_id, $key );
					update_post_meta( $listing_id, $key, $value );
				}
			}
		}

		return array();
	}

	public function has_preview(): bool {
		return true;
	}

	public function get_preview_url(): string {
		return STM_LISTINGS_URL . '/assets/images/listing-manager/page-preview/options.png';
	}

	public function get_option_form() {
		return array(
			'html' => '',
		);
	}

	public function save_option_form() {
	}

	public function get_edit_option_form() {
		$options     = $this->get_listing_options();
		$fields_html = array();

		if ( ! empty( $options ) && is_array( $options ) ) {
			foreach ( $options as $option ) {

				$slug = isset( $option['slug'] ) ? $option['slug'] : '';
				if ( empty( $slug ) ) {
					continue;
				}
				ob_start();
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/field-item',
					array(
						'option'    => $option,
						'page'      => 'option',
						'data_attr' => array(
							'confirmation-title'           => __( 'Are you sure you want to delete this custom field?', 'stm_vehicles_listing' ),
							'confirmation-message'         => __( 'This will permanently remove the field from all parts of the site, including inventory filters. This action cannot be undone.', 'stm_vehicles_listing' ),
							'confirmation-accept'          => __( 'Cancel', 'stm_vehicles_listing' ),
							'confirmation-cancel'          => __( 'Delete', 'stm_vehicles_listing' ),
							'confirmation-delete-btn-icon' => '',
							'confirmation-slug'            => $option['slug'],
						),
					)
				);
				$fields_html[] = ob_get_clean();
			}
		}

		return array(
			'fields_html' => $fields_html,
		);
	}

	public function save_edit_option_form(): array {
		if ( ! isset( $_POST['order'] ) || ! is_array( $_POST['order'] ) ) {
			return array(
				'success' => false,
				'message' => __( 'Invalid order data.', 'stm_vehicles_listing' ),
			);
		}

		$options   = $this->get_listing_options();
		$new_order = array();

		foreach ( $_POST['order'] as $item ) {
			if ( ! isset( $item['slug'] ) || ! isset( $item['order'] ) ) {
				continue;
			}

			foreach ( $options as $key => $option ) {
				if ( isset( $option['slug'] ) && $option['slug'] === $item['slug'] ) {
					$new_order[ $item['order'] ] = $option;
					break;
				}
			}
		}

		ksort( $new_order );
		$new_order = array_values( $new_order );

		if ( update_option( 'stm_vehicle_listing_options', $new_order ) ) {
			return array(
				'success' => true,
				'message' => __( 'Field order updated successfully.', 'stm_vehicles_listing' ),
			);
		}

		return array(
			'success' => false,
			'message' => __( 'Failed to update field order.', 'stm_vehicles_listing' ),
		);
	}

	public function get_listing_options(): array {
		return get_option( 'stm_vehicle_listing_options', array() );
	}

	public function get_options_form(): array {
		$listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
		$options    = $this->get_listing_options();

		if ( empty( $options ) ) {
			return array(
				'html' => '',
			);
		}

		$selected_options = array();
		if ( $listing_id ) {
			$meta_keys = array_column(
				array_filter(
					$options,
					function( $option ) {
						return isset( $option['slug'] ) && ! empty( $option['slug'] );
					}
				),
				'slug'
			);

			if ( ! empty( $meta_keys ) ) {
				$meta_values = get_post_meta( $listing_id );
				foreach ( $meta_keys as $key ) {
					$selected_options[ $key ] = isset( $meta_values[ $key ][0] ) ? $meta_values[ $key ][0] : '';
				}
			}
		}

		ob_start();
		foreach ( $options as $option ) {
			$slug = isset( $option['slug'] ) ? $option['slug'] : '';
			if ( empty( $slug ) ) {
				continue;
			}
			if ( ( isset( $option['field_type'] ) && in_array( $option['field_type'], array( 'price', 'location' ), true ) ) || in_array( $slug, array( 'price', 'location' ), true ) ) {
				continue;
			}

			if ( isset( $option['numeric'] ) && $option['numeric'] ) {
				$selected = isset( $selected_options[ $slug ] ) ? $selected_options[ $slug ] : '';
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/input',
					array(
						'id'          => $slug,
						'label'       => isset( $option['single_name'] ) ? $option['single_name'] : $slug,
						'placeholder' => sprintf(
							__( 'Enter %s', 'stm_vehicles_listing' ),
							isset( $option['single_name'] ) ? $option['single_name'] : $slug
						),
						'value'       => isset( $selected_options[ $slug ] ) ? $selected_options[ $slug ] : '',
						'type'        => ( isset( $option['numeric'] ) && $option['numeric'] ) ? 'number' : 'text',
						'input_name'  => $this->get_id() . '[numeric][' . $slug . ']',
						'label'       => isset( $option['single_name'] ) ? $option['single_name'] : $slug,
					)
				);
			} else {
				$args  = array(
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => false,
					'fields'     => 'all',
					'pad_counts' => false,
				);
				$terms = get_terms( $slug, $args );
				if ( is_array( $terms ) ) {
					$terms = array_map( 'get_object_vars', $terms );
				} else {
					$terms = array();
				}
				$selected = isset( $selected_options[ $slug ] ) ? $selected_options[ $slug ] : '';

				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/select',
					array(
						'id'          => $slug,
						'label'       => isset( $option['single_name'] ) ? $option['single_name'] : $slug,
						'placeholder' => sprintf(
							__( 'Select %s', 'stm_vehicles_listing' ),
							isset( $option['single_name'] ) ? $option['single_name'] : $slug
						),
						'options'     => $terms,
						'value'       => $selected,
						'label'       => isset( $option['single_name'] ) ? $option['single_name'] : $slug,
						'input_name'  => $this->get_id() . '[terms][' . $slug . ']',
					)
				);
			}
		}
		$html = ob_get_clean();

		return array(
			'html' => $html,
		);
	}

	public function get_terms_list_form(): array {
		$taxonomy = isset( $_POST['taxonomy'] ) ? sanitize_text_field( $_POST['taxonomy'] ) : '';

		if ( empty( $taxonomy ) ) {
			return array(
				'success' => false,
				'message' => __( 'Taxonomy is required.', 'stm_vehicles_listing' ),
			);
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array(
				'success' => false,
				'message' => $terms->get_error_message(),
			);
		}

		$formatted_terms = array();
		foreach ( $terms as $term ) {
			$formatted_terms[] = array(
				'value' => $term->term_id,
				'label' => $term->name,
			);
		}

		ob_start();
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/checkboxes',
			array(
				'id'      => 'taxonomy_terms',
				'options' => $formatted_terms,
				'class'   => 'terms-list two-columns',
			)
		);
		$html = ob_get_clean();

		return array(
			'success' => true,
			'html'    => $html,
		);
	}

	public function get_modal_form(): array {
		$option_id = isset( $_POST['option_id'] ) ? sanitize_text_field( $_POST['option_id'] ) : '';
		$options   = $this->get_listing_options();

		$current_option = null;
		foreach ( $options as $option ) {
			if ( isset( $option['slug'] ) && $option['slug'] === $option_id ) {
				$current_option = $option;
				break;
			}
		}

		$listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
		$title      = empty( $option_id ) ? __( 'Add Custom Field', 'stm_vehicles_listing' ) : __( 'Edit Custom Field', 'stm_vehicles_listing' );
		$buttons    = array(
			'save'           => array(
				'text'  => __( 'Save Changes', 'stm_vehicles_listing' ),
				'icon'  => '',
				'class' => 'mvl-listing-manager-modal-btn mvl-primary-btn',
			),
			'mvl-cancel-btn' => array(
				'text'      => __( 'Cancel', 'stm_vehicles_listing' ),
				'icon'      => '',
				'class'     => 'mvl-listing-manager-modal-btn mvl-secondary-btn',
				'data_attr' => array(
					'confirmation-title'           => __( 'Are you sure you want to cancel?', 'stm_vehicles_listing' ),
					'confirmation-message'         => __( 'This action cannot be undone.', 'stm_vehicles_listing' ),
					'confirmation-accept'          => __( 'Cancel', 'stm_vehicles_listing' ),
					'confirmation-cancel'          => __( 'Discard', 'stm_vehicles_listing' ),
					'confirmation-delete-btn-icon' => '',
					'confirmation-slug'            => $option_id,
				),
			),
		);

		if ( ! empty( $current_option ) ) {
			$buttons['delete'] = array(
				'text'      => __( 'Delete', 'stm_vehicles_listing' ),
				'icon'      => 'motors-icons-mvl-trash',
				'class'     => 'mvl-listing-manager-modal-btn mvl-delete-btn',
				'data_attr' => array(
					'confirmation-title'           => __( 'Are you sure you want to delete this field?', 'stm_vehicles_listing' ),
					'confirmation-message'         => __( 'This action cannot be undone.', 'stm_vehicles_listing' ),
					'confirmation-accept'          => __( 'Cancel', 'stm_vehicles_listing' ),
					'confirmation-cancel'          => __( 'Delete', 'stm_vehicles_listing' ),
					'confirmation-delete-btn-icon' => '',
					'confirmation-slug'            => $option_id,
				),
			);
		}

		ob_start();
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/modal',
			array(
				'title'      => $title,
				'modal_name' => 'option',
				'template'   => 'listing-manager/parts/modals/option-popup',
				'option'     => $current_option,
				'buttons'    => $buttons,
				'listing_id' => $listing_id,
			)
		);
		$html = ob_get_clean();

		return array(
			'html' => $html,
		);
	}

	public function save_term_option_form() {
		if ( ! isset( $_POST['term'] ) || ! isset( $_POST['taxonomy'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid data.', 'stm_vehicles_listing' ),
				)
			);
		}

		$term      = sanitize_text_field( $_POST['term'] );
		$taxonomy  = sanitize_text_field( $_POST['taxonomy'] );
		$term_meta = isset( $_POST['term_meta'] ) ? sanitize_text_field( $_POST['term_meta'] ) : '';
		$image_id  = isset( $_POST['image_id'] ) ? intval( $_POST['image_id'] ) : 0;

		if ( empty( $term ) || empty( $taxonomy ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Term and taxonomy are required.', 'stm_vehicles_listing' ),
				)
			);
		}

		$result = wp_insert_term( $term, $taxonomy );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				array(
					'message' => $result->get_error_message(),
				)
			);
		}

		if ( ! empty( $term_meta ) ) {
			update_term_meta( $result['term_id'], '_category_color', $term_meta );
		}

		if ( ! empty( $image_id ) ) {
			update_term_meta( $result['term_id'], 'stm_image', $image_id );
		}

		wp_send_json_success(
			array(
				'term'    => array(
					'id'   => $result['term_id'],
					'name' => $term,
					'slug' => $result['slug'],
				),
				'message' => __( 'Term added successfully.', 'stm_vehicles_listing' ),
			)
		);
	}

	public function add_terms_to_option( $terms, $taxonomy ) {
		if ( empty( $terms ) || ! is_array( $terms ) ) {
			return false;
		}

		$results = array();

		foreach ( $terms as $term ) {
			if ( empty( $term['name'] ) ) {
				continue;
			}

			$result = wp_insert_term(
				$term['name'],
				$taxonomy
			);

			if ( ! is_wp_error( $result ) ) {
				$term_id = $result['term_id'];

				if ( ! empty( $term['term_meta'] ) ) {
					update_term_meta( $term_id, '_category_color', $term['term_meta'] );
				}

				if ( ! empty( $term['image_id'] ) ) {
					update_term_meta( $term_id, 'stm_image', $term['image_id'] );
				}

				$results[] = $result;
			}
		}

		return ! empty( $results );
	}

	public function save_modal_form() {
		$data        = $_POST;
		$single_name = isset( $data['single_name'] ) ? sanitize_text_field( $data['single_name'] ) : '';
		$field_type  = isset( $data['field_type'] ) ? sanitize_text_field( $data['field_type'] ) : '';
		$plural_name = isset( $data['plural_name'] ) ? sanitize_text_field( $data['plural_name'] ) : '';

		if ( empty( $single_name ) || empty( $field_type ) || empty( $plural_name ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Required fields are not filled (name, plural name, field type)', 'stm_vehicles_listing' ),
				)
			);
		}

		if ( 'numeric' === $field_type || 'price' === $field_type ) {
			$data['numeric'] = true;
		}

		$slug = ( isset( $data['slug'] ) && $data['slug'] ) ? sanitize_title( urldecode( $data['slug'] ) ) : sanitize_title( urldecode( $single_name ) );

		$options   = $this->get_listing_options();
		$found     = false;
		$option_id = isset( $data['option_id'] ) ? sanitize_text_field( $data['option_id'] ) : '';

		if ( ! taxonomy_exists( $slug ) ) {
			register_taxonomy(
				$slug,
				'listings',
				array(
					'label'        => $single_name,
					'rewrite'      => array( 'slug' => $slug ),
					'hierarchical' => true,
				)
			);
		}

		if ( ! empty( $data['new_terms'] ) ) {
			$terms = json_decode( stripslashes( $data['new_terms'] ), true );
			if ( is_array( $terms ) ) {
				$this->add_terms_to_option( $terms, $slug );
			}
		}

		if ( ! empty( $option_id ) ) {
			foreach ( $options as $i => $option ) {
				if ( isset( $option['slug'] ) && $option['slug'] === $option_id ) {
					$new_option = array();
					foreach ( $data as $key => $value ) {
						if ( in_array( $key, array( 'action', 'nonce', 'template', 'listing_manager_page_id', 'option_id', 'new_terms' ), true ) ) {
							continue;
						}
						$new_option[ $key ] = sanitize_text_field( $value );
					}
					$new_option['slug'] = $option['slug'];
					$options[ $i ]      = array_merge( $option, $new_option );
					$found              = true;
					break;
				}
			}
		} else {
			foreach ( $options as $option ) {
				if ( isset( $option['slug'] ) && $option['slug'] === $slug ) {
					wp_send_json_error(
						array(
							'message'        => __( 'Option with this slug already exists', 'stm_vehicles_listing' ),
							'already_exists' => true,
						)
					);
				}
			}
			$new_option = array();
			foreach ( $data as $key => $value ) {
				if ( in_array( $key, array( 'action', 'nonce', 'template', 'listing_manager_page_id', 'option_id', 'new_terms' ), true ) ) {
					continue;
				}
				$new_option[ $key ] = sanitize_text_field( $value );
			}
			$new_option['slug'] = $slug;
			$options[]          = $new_option;
			$found              = true;
		}

		if ( ! $found ) {
			wp_send_json_error(
				array(
					'message' => __( 'Option not found', 'stm_vehicles_listing' ),
				)
			);
		}

		if ( update_option( 'stm_vehicle_listing_options', $options ) ) {
			wp_send_json_success(
				array(
					'message' => __( 'Option successfully saved', 'stm_vehicles_listing' ),
				)
			);
		}
	}

	public function delete_option_form() {
		$option_id = isset( $_POST['option_id'] ) ? sanitize_text_field( $_POST['option_id'] ) : '';

		if ( empty( $option_id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Option ID is not specified', 'stm_vehicles_listing' ),
				)
			);
			return;
		}

		$options = $this->get_listing_options();
		$found   = false;

		foreach ( $options as $key => $option ) {
			if ( isset( $option['slug'] ) && $option['slug'] === $option_id ) {
				$terms = get_terms(
					array(
						'taxonomy'   => $option_id,
						'hide_empty' => false,
					)
				);

				if ( ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						wp_delete_term( $term->term_id, $option_id );
					}
				}
				unregister_taxonomy( $option_id );

				unset( $options[ $key ] );
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			wp_send_json_error(
				array(
					'message' => __( 'Option not found', 'stm_vehicles_listing' ),
				)
			);
			return;
		}

		$options = array_values( $options );

		if ( update_option( 'stm_vehicle_listing_options', $options ) ) {
			wp_send_json_success(
				array(
					'message' => __( 'Option successfully deleted', 'stm_vehicles_listing' ),
				)
			);
		} else {
			wp_send_json_error(
				array(
					'message' => __( 'Error deleting option', 'stm_vehicles_listing' ),
				)
			);
		}
	}

	public function get_preview_card_data_form(): array {
		$listing_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
		if ( empty( $listing_id ) ) {
			return array(
				'success' => false,
				'message' => __( 'Listing ID is required.', 'stm_vehicles_listing' ),
			);
		}

		ob_start();
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/listing-preview-card-data',
			array(
				'listing_id' => $listing_id,
			)
		);
		$html = ob_get_clean();

		return array(
			'success' => true,
			'html'    => $html,
		);
	}
}
