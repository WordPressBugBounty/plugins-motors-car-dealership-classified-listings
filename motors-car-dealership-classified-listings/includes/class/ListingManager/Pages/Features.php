<?php
namespace MotorsVehiclesListing\ListingManager\Pages;

use MotorsVehiclesListing\ListingManager\Abstracts\Page;

class Features extends Page {

	protected function data(): array {
		return array(
			'title'     => __( 'Features', 'stm_vehicles_listing' ),
			'menu_name' => __( 'Features', 'stm_vehicles_listing' ),
			'icon'      => 'motors-icons-mvl-bolt',
		);
	}

	public function has_preview(): bool {
		return true;
	}

	public function get_preview_url(): string {
		return STM_LISTINGS_URL . '/assets/images/listing-manager/page-preview/features.png';
	}

	public function save( array $data ): array {
		if ( isset( $data['additional_features'] ) ) {
			$data['additional_features'] = array_map(
				function( $value ) {
					return get_term_by( 'slug', $value, 'stm_additional_features' )->name;
				},
				$data['additional_features']
			);

			$this->implode_and_update_text_array_meta( $data, 'additional_features' );
		} else {
			update_post_meta( $data['post_id'], 'additional_features', '' );
		}

		return array();
	}

	public function get_features_form() {
		$features        = apply_filters( 'mvl_listing_manager_get_group_features', array() );
		$listing_id      = apply_filters( 'mvl_listing_manager_item_id', 0 );
		$selected_values = get_post_meta( $listing_id, 'additional_features', true );
		if ( ! is_array( $selected_values ) ) {
			if ( is_string( $selected_values ) && strlen( $selected_values ) > 0 ) {
				$selected_values = array_map( 'trim', explode( ',', $selected_values ) );
			} else {
				$selected_values = array();
			}
		}

		$selected_values           = array_unique( $selected_values );
		$formatted_selected_values = array();

		foreach ( $selected_values as $value ) {
			$term = get_term_by( 'name', $value, 'stm_additional_features' );
			if ( $term && ! is_wp_error( $term ) ) {
				$formatted_selected_values[] = array(
					'value' => urldecode( $term->slug ),
					'label' => $term->name,
				);
			} else {
				$formatted_selected_values[] = array(
					'value' => $value,
					'label' => $value,
				);
			}
		}

		ob_start();

		foreach ( $features as $tab ) {
			if ( ! empty( $tab['tab_title_single'] ) ) {
				echo '<div class="mvl-features-list-item">';

				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/checkboxes',
					array(
						'id'         => 'features_' . sanitize_title( $tab['tab_title_single'] ),
						'label'      => $tab['tab_title_single'],
						'options'    => $tab['tab_title_selected_labels'],
						'class'      => 'features-list column',
						'selected'   => $formatted_selected_values,
						'input_name' => $this->get_id() . '[additional_features][]',
						'label'      => $tab['tab_title_single'],
					)
				);
				echo '</div>';
			}
		}

		$html = ob_get_clean();

		return array(
			'html' => $html,
		);
	}

	public function get_edit_features_form() {
		$features    = apply_filters( 'mvl_listing_manager_get_group_features', array() );
		$fields_html = array();

		foreach ( $features as $tab ) {
			$option = array(
				'slug'        => sanitize_title( $tab['tab_title_single'] ),
				'plural_name' => $tab['tab_title_single'],
			);
			ob_start();
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/field-item',
				array(
					'option'    => $option,
					'page'      => 'features',
					'data_attr' => array(
						'confirmation-title'           => __( 'Are you sure you want to delete this features list?', 'stm_vehicles_listing' ),
						'confirmation-message'         => __( 'Deleting it will remove this list from all listings permanently. This action cannot be undone.', 'stm_vehicles_listing' ),
						'confirmation-accept'          => __( 'Cancel', 'stm_vehicles_listing' ),
						'confirmation-cancel'          => __( 'Delete', 'stm_vehicles_listing' ),
						'confirmation-delete-btn-icon' => '',
						'confirmation-slug'            => $option['slug'],
					),
				)
			);
			$fields_html[] = ob_get_clean();
		}

		return array(
			'fields_html' => $fields_html,
		);
	}

	public function save_edit_features_form(): array {
		if ( ! isset( $_POST['order'] ) || ! is_array( $_POST['order'] ) ) {
			return array(
				'success' => false,
				'message' => __( 'Invalid order data.', 'stm_vehicles_listing' ),
			);
		}

		$features  = apply_filters( 'mvl_listing_manager_get_group_features', array() );
		$new_order = array();

		foreach ( $_POST['order'] as $item ) {
			if ( ! isset( $item['slug'] ) || ! isset( $item['order'] ) ) {
				continue;
			}

			foreach ( $features as $key => $feature ) {
				if ( isset( $feature['tab_title_single'] ) && sanitize_title( $feature['tab_title_single'] ) === $item['slug'] ) {
					$new_order[ $item['order'] ] = $feature;
					break;
				}
			}
		}

		ksort( $new_order );
		$new_order = array_values( $new_order );

		$settings                     = get_option( 'mvl_listing_details_settings', array() );
		$settings['fs_user_features'] = $new_order;

		if ( update_option( 'mvl_listing_details_settings', $settings ) ) {
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

	public function get_modal_form() {
		$features = apply_filters( 'mvl_listing_manager_get_group_features', array() );

		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );
		$option_id  = isset( $_POST['option_id'] ) ? sanitize_title( $_POST['option_id'] ) : '';

		$current_tab = null;
		if ( ! empty( $option_id ) ) {
			foreach ( $features as $tab ) {
				if ( isset( $tab['tab_title_single'] ) && sanitize_title( $tab['tab_title_single'] ) === $option_id ) {
					$current_tab = $tab;
					break;
				}
			}
		}

		$title   = empty( $option_id ) ? __( 'Add Features List', 'stm_vehicles_listing' ) : __( 'Edit Features List', 'stm_vehicles_listing' );
		$buttons = array(
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

		if ( ! empty( $option_id ) ) {
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
				'title'       => $title,
				'modal_name'  => 'features',
				'template'    => 'listing-manager/parts/modals/features',
				'features'    => $features,
				'current_tab' => $current_tab,
				'listing_id'  => $listing_id,
				'buttons'     => $buttons,
			)
		);
		$html = ob_get_clean();

		return array(
			'html' => $html,
		);
	}

	public function save_term_features_form() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mvl_listing_manager' ) ) {
			return array(
				'success' => false,
				'message' => __( 'Invalid nonce.', 'stm_vehicles_listing' ),
			);
		}

		if ( ! isset( $_POST['term_name'] ) || empty( $_POST['term_name'] ) ) {
			return array(
				'success' => false,
				'message' => __( 'Term name is required.', 'stm_vehicles_listing' ),
			);
		}

		$term_name     = urldecode( $_POST['term_name'] );
		$parent_id     = isset( $_POST['parent_id'] ) ? intval( $_POST['parent_id'] ) : 0;
		$term_slug     = sanitize_title( $term_name );
		$existing_term = get_term_by( 'slug', $term_slug, 'stm_additional_features' );

		if ( $existing_term && ! is_wp_error( $existing_term ) ) {
			$response = array(
				'success'   => false,
				'message'   => __( 'Term with this name already exists.', 'stm_vehicles_listing' ),
				'term_id'   => $existing_term->term_id,
				'term_name' => $existing_term->name,
				'term_slug' => $existing_term->slug,
			);
			return $response;
		}

		$result = wp_insert_term(
			$term_name,
			'stm_additional_features',
			array(
				'parent' => $parent_id,
			)
		);

		if ( is_wp_error( $result ) ) {
			return array(
				'success' => false,
				'message' => $result->get_error_message(),
			);
		}

		$term = get_term( $result['term_id'], 'stm_additional_features' );

		return array(
			'success'   => true,
			'message'   => __( 'Term added successfully.', 'stm_vehicles_listing' ),
			'term_id'   => $result['term_id'],
			'term_name' => $term_name,
			'term_slug' => urldecode( $term->slug ),
		);
	}

	public function save_save_features_form() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mvl_listing_manager' ) ) {
			return array(
				'success' => false,
				'message' => __( 'Invalid nonce.', 'stm_vehicles_listing' ),
			);
		}

		if ( ! isset( $_POST['title'] ) || empty( $_POST['title'] ) ) {
			return array(
				'success' => false,
				'message' => __( 'Title is required.', 'stm_vehicles_listing' ),
			);
		}

		$title           = sanitize_text_field( $_POST['title'] );
		$selected_values = isset( $_POST['selected_values'] ) ? array_map( 'sanitize_text_field', $_POST['selected_values'] ) : array();
		$option_id       = isset( $_POST['option_id'] ) ? sanitize_title( $_POST['option_id'] ) : '';

		if ( empty( $selected_values ) ) {
			return array(
				'success' => false,
				'message' => __( 'You need to select at least one option.', 'stm_vehicles_listing' ),
			);
		}

		$settings = get_option( 'mvl_listing_details_settings', array() );
		$features = isset( $settings['fs_user_features'] ) ? $settings['fs_user_features'] : array();

		foreach ( $features as $feature ) {
			if (
				isset( $feature['tab_title_single'] ) &&
				sanitize_title( $feature['tab_title_single'] ) === sanitize_title( $title ) &&
				sanitize_title( $title ) !== $option_id
			) {
				return array(
					'success'        => false,
					'message'        => __( 'Feature group with this title already exists', 'stm_vehicles_listing' ),
					'already_exists' => true,
				);
			}
		}

		$selected_labels = array();
		foreach ( $selected_values as $value ) {
			$term = get_term_by( 'slug', $value, 'stm_additional_features' );
			if ( $term && ! is_wp_error( $term ) ) {
				$selected_labels[] = array(
					'value' => $value,
					'label' => $term->name,
				);
			} else {
				$new_term = wp_insert_term( $value, 'stm_additional_features' );
				if ( ! is_wp_error( $new_term ) ) {
					$term = get_term( $new_term['term_id'], 'stm_additional_features' );
					if ( $term && ! is_wp_error( $term ) ) {
						$selected_labels[] = array(
							'value' => $term->slug,
							'label' => $term->name,
						);
					}
				}
			}
		}

		$new_feature = array(
			'tab_title_single'          => $title,
			'tab_title_selected_labels' => $selected_labels,
		);

		if ( ! empty( $option_id ) ) {
			foreach ( $features as $key => $feature ) {
				if ( isset( $feature['tab_title_single'] ) && sanitize_title( $feature['tab_title_single'] ) === $option_id ) {
					$features[ $key ] = $new_feature;
					break;
				}
			}
		} else {
			$features[] = $new_feature;
		}

		$settings['fs_user_features'] = $features;

		if ( update_option( 'mvl_listing_details_settings', $settings ) ) {
			return array(
				'success' => true,
				'message' => __( 'Features saved successfully.', 'stm_vehicles_listing' ),
			);
		}

		return array(
			'success' => false,
			'message' => __( 'Failed to save features.', 'stm_vehicles_listing' ),
		);
	}

	public function delete_feature_group_form() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mvl_listing_manager_get_form' ) ) {
			return array(
				'success' => false,
				'message' => __( 'Invalid nonce.', 'stm_vehicles_listing' ),
			);
		}

		$option_id = isset( $_POST['option_id'] ) ? sanitize_title( $_POST['option_id'] ) : '';
		$settings  = get_option( 'mvl_listing_details_settings', array() );
		$features  = isset( $settings['fs_user_features'] ) ? $settings['fs_user_features'] : array();

		foreach ( $features as $key => $feature ) {
			if ( isset( $feature['tab_title_single'] ) ) {
				$feature_slug = sanitize_title( $feature['tab_title_single'] );
				$option_slug  = sanitize_title( $option_id );

				if ( $feature_slug === $option_slug ) {
					unset( $features[ $key ] );
					break;
				}
			}
		}

		$settings['fs_user_features'] = array_values( $features );

		if ( update_option( 'mvl_listing_details_settings', $settings ) ) {
			return array(
				'success' => true,
				'message' => __( 'Feature group deleted successfully.', 'stm_vehicles_listing' ),
			);
		}

		return array(
			'success' => false,
			'message' => __( 'Failed to delete feature group.', 'stm_vehicles_listing' ),
		);
	}
}

