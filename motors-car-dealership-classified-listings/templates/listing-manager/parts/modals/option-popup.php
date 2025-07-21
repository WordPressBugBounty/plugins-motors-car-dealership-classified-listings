<?php
$slug             = isset( $option['slug'] ) ? sanitize_text_field( $option['slug'] ) : '';
$taxonomies       = get_option( 'stm_vehicle_listing_options' );
$terms_by_option  = array();
$selected_options = array();

if ( ! isset( $option ) ) {
	$option = array(
		'slug'                            => '',
		'single_name'                     => '',
		'plural_name'                     => '',
		'listing_taxonomy_parent'         => '',
		'field_type'                      => '',
		'required_field'                  => '',
		'number_field_affix'              => '',
		'use_delimiter'                   => '',
		'slider_in_tabs'                  => '',
		'slider'                          => '',
		'font'                            => '',
		'use_on_car_listing_page'         => '',
		'use_on_car_archive_listing_page' => '',
		'use_on_single_car_page'          => '',
		'show_in_admin_column'            => '',
	);
}

if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
	foreach ( $taxonomies as $taxonomy ) {
		if ( isset( $taxonomy['field_type'] ) && 'price' === $taxonomy['field_type'] ) {
			continue;
		}
		$args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'fields'     => 'all',
			'pad_counts' => false,
		);
		$slug = isset( $taxonomy['slug'] ) ? sanitize_text_field( $taxonomy['slug'] ) : '';
		if ( ! empty( $slug ) ) {
			$selected_options[ $slug ] = get_post_meta( $listing_id, $slug, true );
			$terms_by_option[ $slug ]  = get_terms( $slug, $args );
		}
	}
}
?>

<div class="mvl-options-popup-container mlv-options-popup-content">
	<div class="mvl-options-popup-container-inner">
		<div class="mvl-options-settings-tabs">
			<div class="mvl-options-settings-tab active" data-tab="general">
				<span><?php esc_html_e( 'General', 'stm_vehicles_listing' ); ?></span>
			</div>
			<div class="mvl-options-settings-tab" data-tab="display">
				<span><?php esc_html_e( 'Display Settings', 'stm_vehicles_listing' ); ?></span>
			</div>
			<div class="mvl-options-settings-tab" data-tab="filter">
				<span><?php esc_html_e( 'Filter Settings', 'stm_vehicles_listing' ); ?></span>
			</div>
			<div class="mvl-options-settings-tab" data-tab="terms" data-depends-on="field_type" data-depend-values="location" data-depend-action="hide">
				<span><?php esc_html_e( 'Terms', 'stm_vehicles_listing' ); ?></span>
			</div>
			<?php if ( apply_filters( 'is_mvl_pro', false ) && defined( 'STM_LISTINGS_PRO_PATH' ) ) : ?>
				<div class="mvl-options-settings-tab" data-tab="skins">
					<span><?php esc_html_e( 'Skins', 'stm_vehicles_listing' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<form
			class="mvl-options-settings-tab-content"
			action="<?php echo isset( $_POST['form_action'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['form_action'] ) ) ) : ( isset( $option['slug'] ) ? esc_attr( 'edit-field' ) : esc_attr( 'add-new-field' ) ); ?>"
			method="POST"
			data-slug="<?php echo isset( $option['slug'] ) ? esc_attr( $option['slug'] ) : ''; ?>"
		>
			<div class="mvl-options-settings-tab-content-item active" data-tab="general">
				<div class="mvl-options-settings-tab-content-item-field-group-wrapper">
					<div class="mvl-options-settings-tab-content-item-field-group">
						<div class="mvl-options-settings-tab-content-item-field" data-slug="<?php echo esc_attr( $option['slug'] ); ?>">
							<?php
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/input',
								array(
									'id'          => $option['slug'],
									'input_name'  => 'single_name',
									'label'       => esc_html__( 'Singular Name', 'stm_vehicles_listing' ),
									'placeholder' => sprintf( esc_html__( 'Enter %s', 'stm_vehicles_listing' ), esc_html( $option['single_name'] ) ),
									'value'       => isset( $option['single_name'] ) ? $option['single_name'] : '',
									'type'        => 'text',
									'required'    => true,
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="<?php echo esc_attr( $option['slug'] ); ?>">
							<?php
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/input',
								array(
									'id'          => $option['slug'],
									'input_name'  => 'plural_name',
									'label'       => esc_html__( 'Plural Name', 'stm_vehicles_listing' ),
									'placeholder' => sprintf( esc_html__( 'Enter %s', 'stm_vehicles_listing' ), esc_html( $option['plural_name'] ) ),
									'value'       => isset( $option['plural_name'] ) ? $option['plural_name'] : '',
									'type'        => 'text',
									'required'    => true,
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="listing_taxonomy_parent">
							<?php
							$selected = isset( $option['listing_taxonomy_parent'] ) ? $option['listing_taxonomy_parent'] : '';
							$terms    = array();
							if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
								foreach ( $taxonomies as $taxonomy ) {
									if ( isset( $taxonomy['slug'] ) && ! empty( $taxonomy['slug'] ) ) {
										$terms[] = array(
											'slug' => $taxonomy['slug'],
											'name' => isset( $taxonomy['single_name'] ) ? $taxonomy['single_name'] : $taxonomy['slug'],
										);
									}
								}
							}
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/select',
								array(
									'id'          => 'listing_taxonomy_parent',
									'label'       => esc_html__( 'Select Parent Field', 'stm_vehicles_listing' ),
									'description' => esc_html__( 'This setting allows you to choose the main field that this custom field will be associated with.', 'stm_vehicles_listing' ),
									'placeholder' => esc_html__( 'No parent', 'stm_vehicles_listing' ),
									'options'     => $terms,
									'value'       => $selected,
									'input_name'  => 'listing_taxonomy_parent',
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="field_type">
							<?php
							$options = apply_filters(
								'mvl_field_type_choices',
								array(
									'dropdown' => esc_html__( 'Dropdown select', 'stm_vehicles_listing' ),
									'numeric'  => esc_html__( 'Number', 'stm_vehicles_listing' ),
								)
							);

							$formatted_options = array();
							foreach ( $options as $key => $value ) {
								$formatted_options[] = array(
									'slug' => $key,
									'name' => $value,
								);
							}
							$field_type = ( ! empty( $option['numeric'] ) ) ? 'numeric' : 'dropdown';

							if ( isset( $option['field_type'] ) && ! empty( $option['field_type'] ) ) {
								$field_type = $option['field_type'];
							}

							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/select',
								array(
									'id'          => 'field_type',
									'label'       => esc_html__( 'Field Type', 'stm_vehicles_listing' ),
									'options'     => $formatted_options,
									'value'       => $field_type,
									'type'        => 'select',
									'description' => esc_html__( 'Choose the input format for this field', 'stm_vehicles_listing' ),
									'placeholder' => esc_html__( 'Select Field Type', 'stm_vehicles_listing' ),
									'input_name'  => 'field_type',
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field switch-field" data-slug="required_field">
							<?php
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/switch',
								array(
									'id'         => 'required_field',
									'label'      => esc_html__( 'Make required', 'stm_vehicles_listing' ),
									'value'      => isset( $option['required_field'] ) ? $option['required_field'] : '',
									'input_name' => 'required_field',
								)
							);
							?>
						</div>

						<div class="mvl-options-settings-tab-content-item-field-group">
							<div class="mvl-options-settings-tab-content-item-field switch-field" data-slug="number_field_affix" data-depends-on="field_type" data-depend-values="numeric" data-depend-action="show">
								<?php
								do_action(
									'stm_listings_load_template',
									'/listing-manager/parts/fields/input',
									array(
										'id'          => 'number_field_affix',
										'label'       => esc_html__( 'Field unit:', 'stm_vehicles_listing' ),
										'value'       => isset( $option['number_field_affix'] ) ? $option['number_field_affix'] : '',
										'type'        => 'text',
										'placeholder' => esc_html__( 'Enter unit', 'stm_vehicles_listing' ),
										'description' => esc_html__( 'This setting allows you to attach a unit to a number field for clarity, such as &quot;100 km&quot; instead of just &quot;100.” The unit will appear on all pages.', 'stm_vehicles_listing' ),
									)
								);
								?>
							</div>
							<div class="mvl-options-settings-tab-content-item-field switch-field" data-slug="use_delimiter" data-depends-on="field_type" data-depend-values="numeric" data-depend-action="show">
								<?php
								do_action(
									'stm_listings_load_template',
									'/listing-manager/parts/fields/switch',
									array(
										'id'         => 'use_delimiter',
										'label'      => esc_html__( 'Add separator', 'stm_vehicles_listing' ),
										'value'      => isset( $option['use_delimiter'] ) ? $option['use_delimiter'] : '',
										'input_name' => 'use_delimiter',
									)
								);
								?>
							</div>
							<div class="mvl-options-settings-tab-content-item-field switch-field" data-slug="display_type" data-depends-on="field_type" data-depend-values="numeric" data-depend-action="show">
								<?php
								do_action(
									'stm_listings_load_template',
									'/listing-manager/parts/fields/radio',
									array(
										'id'         => 'slider_in_tabs',
										'label'      => esc_html__( 'Field display options in tabs:', 'stm_vehicles_listing' ),
										'input_name' => 'slider_in_tabs',
										'bool'       => true,
										'options'    => array(
											'dropdown' => array(
												'label' => esc_html__( 'Dropdown with range', 'stm_vehicles_listing' ),
												'value' => '',
											),
											'slider'   => array(
												'label' => esc_html__( 'Slider range', 'stm_vehicles_listing' ),
												'value' => isset( $option['slider_in_tabs'] ) ? $option['slider_in_tabs'] : '',
											),
										),
									)
								);
								?>
							</div>
							<div class="mvl-options-settings-tab-content-item-field switch-field" data-slug="display_type" data-depends-on="field_type" data-depend-values="numeric" data-depend-action="show">
								<?php
								do_action(
									'stm_listings_load_template',
									'/listing-manager/parts/fields/radio',
									array(
										'id'         => 'slider',
										'label'      => esc_html__( 'Field display options in filters:', 'stm_vehicles_listing' ),
										'input_name' => 'slider',
										'bool'       => true,
										'options'    => array(
											'dropdown' => array(
												'label' => esc_html__( 'Dropdown with range', 'stm_vehicles_listing' ),
												'value' => '',
											),
											'slider'   => array(
												'label' => esc_html__( 'Slider range', 'stm_vehicles_listing' ),
												'value' => isset( $option['slider'] ) ? $option['slider'] : '',
											),
										),
									)
								);
								?>
							</div>
						</div>
					</div>
					<div class="mvl-options-settings-tab-content-item-field-group column">
						<div class="mvl-options-settings-tab-content-item-field" data-slug="icon_picker">
							<?php
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/icon-picker',
								array(
									'id'          => 'icon_picker',
									'label'       => esc_html__( 'Choose icon', 'stm_vehicles_listing' ),
									'icon'        => isset( $option['font'] ) ? $option['font'] : '',
									'description' => esc_html__( 'This setting allows you to choose the icon that will be displayed in the field.', 'stm_vehicles_listing' ),
									'input_name'  => 'font',
								)
							);
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="mvl-options-settings-tab-content-item" data-tab="display">
				<div class="mvl-options-settings-tab-content-item-field-group column">
					<div class="mvl-options-settings-tab-content-item-field" data-slug="use_on_car_listing_page">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'use_on_car_listing_page',
								'label'       => esc_html__( 'Show on grid view', 'stm_vehicles_listing' ),
								'value'       => isset( $option['use_on_car_listing_page'] ) ? $option['use_on_car_listing_page'] : '',
								'input_name'  => 'use_on_car_listing_page',
								'description' => esc_html__( 'Check if you want to see this category on car listing page (machine card)', 'stm_vehicles_listing' ),
								'preview'     => esc_url( STM_LISTINGS_URL . '/assets/images/tmp/grid.jpg' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="use_on_car_archive_listing_page">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'use_on_car_archive_listing_page',
								'label'       => esc_html__( 'Show on list view', 'stm_vehicles_listing' ),
								'value'       => isset( $option['use_on_car_archive_listing_page'] ) ? $option['use_on_car_archive_listing_page'] : '',
								'input_name'  => 'use_on_car_archive_listing_page',
								'description' => esc_html__( 'Check if you want to see this category on car listing archive page with icon', 'stm_vehicles_listing' ),
								'preview'     => esc_url( STM_LISTINGS_URL . '/assets/images/tmp/list.jpg' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="use_on_single_car_page">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'use_on_single_car_page',
								'label'       => esc_html__( 'Show on listing page', 'stm_vehicles_listing' ),
								'value'       => isset( $option['use_on_single_car_page'] ) ? $option['use_on_single_car_page'] : '',
								'input_name'  => 'use_on_single_car_page',
								'description' => esc_html__( 'Check if you want to see this category on single car page', 'stm_vehicles_listing' ),
								'preview'     => esc_url( STM_LISTINGS_URL . '/assets/images/tmp/single_car_page.jpg' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="show_in_admin_column" data-depends-on="field_type" data-depend-values="dropdown,numeric" data-depends-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'show_in_admin_column',
								'label'       => esc_html__( 'Show in admin column table', 'stm_vehicles_listing' ),
								'value'       => isset( $option['show_in_admin_column'] ) ? $option['show_in_admin_column'] : '',
								'input_name'  => 'show_in_admin_column',
								'description' => esc_html__( 'This column will be shown in admin', 'stm_vehicles_listing' ),
								'preview'     => esc_url( STM_LISTINGS_URL . '/assets/images/tmp/admin_table.png' ),
							)
						);
						?>
					</div>
				</div>
			</div>
			<div class="mvl-options-settings-tab-content-item" data-tab="filter">
				<div class="mvl-options-settings-tab-content-item-field-group column">
					<div class="mvl-options-settings-tab-content-item-field" data-slug="terms_filters_sort_by">
						<?php
						$sort_by_options   = array(
							'count_asc'  => esc_html__( 'Count Low to High', 'stm_vehicles_listing' ),
							'count_desc' => esc_html__( 'Count High to Low', 'stm_vehicles_listing' ),
							'name_asc'   => esc_html__( 'Name A to Z', 'stm_vehicles_listing' ),
							'name_desc'  => esc_html__( 'Name Z to A', 'stm_vehicles_listing' ),
						);
						$formatted_options = array();
						foreach ( $sort_by_options as $key => $value ) {
							$formatted_options[] = array(
								'slug' => $key,
								'name' => $value,
							);
						}
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/select',
							array(
								'id'          => 'terms_filters_sort_by',
								'label'       => esc_html__( 'Sort custom field options', 'stm_vehicles_listing' ),
								'options'     => $formatted_options,
								'value'       => isset( $option['terms_filters_sort_by'] ) ? $option['terms_filters_sort_by'] : '',
								'placeholder' => esc_html__( 'Count Low to High', 'stm_vehicles_listing' ),
								'input_name'  => 'terms_filters_sort_by',
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="is_multiple_select" data-depends-on="field_type" data-depend-values="dropdown,numeric" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'is_multiple_select',
								'label'       => esc_html__( 'Multiple filter select', 'stm_vehicles_listing' ),
								'value'       => isset( $option['is_multiple_select'] ) ? $option['is_multiple_select'] : '',
								'input_name'  => 'is_multiple_select',
								'description' => esc_html__( 'Allow users to select multiple filter options for this custom field when searching listings.', 'stm_vehicles_listing' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="use_on_car_filter">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'         => 'use_on_car_filter',
								'label'      => esc_html__( 'Show in filters', 'stm_vehicles_listing' ),
								'value'      => isset( $option['use_on_car_filter'] ) ? $option['use_on_car_filter'] : '',
								'input_name' => 'use_on_car_filter',
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="use_count" data-depends-on="field_type" data-depend-values="numeric,price,location" data-depend-action="hide">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'use_count',
								'label'       => esc_html__( 'Show listings count', 'stm_vehicles_listing' ),
								'value'       => isset( $option['use_count'] ) ? $option['use_count'] : '',
								'input_name'  => 'use_count',
								'description' => esc_html__( 'Show the number of listings next to each custom field option during search, so users can see how many results match their filters.', 'stm_vehicles_listing' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="use_on_car_filter_links" data-depends-on="field_type" data-depend-values="dropdown,numeric" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'use_on_car_filter_links',
								'label'       => esc_html__( 'Show as a block with links', 'stm_vehicles_listing' ),
								'value'       => isset( $option['use_on_car_filter_links'] ) ? $option['use_on_car_filter_links'] : '',
								'input_name'  => 'use_on_car_filter_links',
								'description' => esc_html__( 'Enable this setting to display the field as clickable options in filters.', 'stm_vehicles_listing' ),
								'preview'     => esc_url( STM_LISTINGS_URL . '/assets/images/tmp/car-filter-as-block-with-links.jpg' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="use_on_directory_filter_title">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'use_on_directory_filter_title',
								'label'       => esc_html__( 'Show in generated filter title', 'stm_vehicles_listing' ),
								'value'       => isset( $option['use_on_directory_filter_title'] ) ? $option['use_on_directory_filter_title'] : '',
								'input_name'  => 'use_on_directory_filter_title',
								'description' => esc_html__( 'Enable this setting to include the field in the title of search results.', 'stm_vehicles_listing' ),
								'preview'     => esc_url( STM_LISTINGS_URL . '/assets/images/tmp/title.jpg' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="listing_rows_numbers_enable" data-depends-on="field_type" data-depend-values="dropdown,numeric" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'          => 'listing_rows_numbers_enable',
								'label'       => esc_html__( 'Show as checkboxes in the inventory filter', 'stm_vehicles_listing' ),
								'value'       => isset( $option['listing_rows_numbers_enable'] ) ? $option['listing_rows_numbers_enable'] : '',
								'input_name'  => 'listing_rows_numbers_enable',
								'description' => esc_html__( 'Display as checkboxes with images in 1 or 2 columns', 'stm_vehicles_listing' ),
								'preview'     => esc_url( STM_LISTINGS_URL . '/assets/images/tmp/column.png' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="enable_distance_search" data-depends-on="field_type" data-depend-values="location" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'         => 'enable_distance_search',
								'label'      => esc_html__( 'Sort by distance', 'stm_vehicles_listing' ),
								'value'      => isset( $option['enable_distance_search'] ) ? $option['enable_distance_search'] : '',
								'input_name' => 'enable_distance_search',
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="recommend_items_empty_result" data-depends-on="field_type" data-depend-values="location" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/switch',
							array(
								'id'         => 'recommend_items_empty_result',
								'label'      => esc_html__( 'Recommend vehicles in other locations in case of empty result', 'stm_vehicles_listing' ),
								'value'      => isset( $option['recommend_items_empty_result'] ) ? $option['recommend_items_empty_result'] : '',
								'input_name' => 'recommend_items_empty_result',
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="distance_measure_unit" data-depends-on="field_type" data-depend-values="location" data-depend-action="show">
						<?php
						$options           = array(
							'miles'      => esc_html__( 'Miles', 'stm_vehicles_listing' ),
							'kilometers' => esc_html__( 'Kilometers', 'stm_vehicles_listing' ),
						);
						$formatted_options = array();
						foreach ( $options as $key => $value ) {
							$formatted_options[] = array(
								'slug' => $key,
								'name' => $value,
							);
						}
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/select',
							array(
								'id'          => 'distance_measure_unit',
								'label'       => esc_html__( 'Unit of measurement:', 'stm_vehicles_listing' ),
								'options'     => $formatted_options,
								'value'       => isset( $option['distance_measure_unit'] ) ? $option['distance_measure_unit'] : '',
								'placeholder' => esc_html__( 'Select unit', 'stm_vehicles_listing' ),
							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="distance_search" data-depends-on="field_type" data-depend-values="location" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/input',
							array(
								'id'          => 'distance_search',
								'label'       => esc_html__( 'Max search radius:', 'stm_vehicles_listing' ),
								'placeholder' => esc_html__( 'Enter radius', 'stm_vehicles_listing' ),
							)
						);
						?>
					</div>
				</div>
			</div>

			<div class="mvl-options-settings-tab-content-item" data-tab="terms">
				<div class="mvl-options-settings-tab-content-item-field-group column mvl-terms-list-wrapper">
					<div class="mvl-listing-manager-content-header-search-field mvl-search-terms">
						<input type="text" class="mvl-lm-search-field-input mvl-input-field" placeholder="<?php esc_attr_e( 'Search', 'stm_vehicles_listing' ); ?>">
					</div>
					<div class="terms-list-container">
						<div class="mvl-listing-manager-terms-list-inner">
							<?php
							$taxonomy_slug = $option['slug'];
							if ( taxonomy_exists( $taxonomy_slug ) ) {
								$terms = get_terms(
									array(
										'taxonomy'   => $taxonomy_slug,
										'hide_empty' => false,
									)
								);

								if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
									foreach ( $terms as $term ) {
										$image_id  = get_term_meta( $term->term_id, 'stm_image', true );
										$color     = get_term_meta( $term->term_id, '_category_color', true );
										$image_url = '';

										if ( $image_id ) {
											$image_url = wp_get_attachment_image_url( $image_id, 'full' );
										}

										do_action(
											'stm_listings_load_template',
											'listing-manager/parts/fields/term-item',
											array(
												'term' => (object) array(
													'term_id' => $term->term_id,
													'name' => $term->name,
													'image_url' => $image_url,
													'image_id' => $image_id,
													'color' => $color,
												),
											)
										);
									}
								}
							}
							?>
							<div class="mvl-no-terms-found <?php echo empty( $terms ) ? 'active' : ''; ?>">
								<?php esc_html_e( 'No terms found', 'stm_vehicles_listing' ); ?>
							</div>
						</div>
					</div>
					<div class="mvl-options-settings-tab-content-item-field add-term-wrapper" data-slug="add_new_term" data-depends-on="field_type" data-depend-values="dropdown,checkbox,numeric,price" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/input',
							array(
								'id'                 => 'add_new_term',
								'label'              => esc_html__( 'Add new term', 'stm_vehicles_listing' ),
								'placeholder'        => esc_html__( 'Enter term', 'stm_vehicles_listing' ),
								'type'               => ( 'number' !== $option['field_type'] && 'price' !== $option['field_type'] ) ? 'text' : 'number',
								'hidden_field_value' => '',
								'button'             => array(
									array(
										'id'    => 'add_new_term_button',
										'text'  => '',
										'class' => 'mvl-primary-btn mvl-short-btn',
										'icon'  => 'fa fa-plus',
									),
								),
								'slug'               => $option['slug'],
							)
						);
						do_action(
							'stm_listings_load_template',
							'/listing-manager/parts/fields/image',
							array(
								'id'               => 'add_term_image_button',
								'input_name'       => 'stm_image',
								'dropzone'         => false,
								'draggable'        => false,
								'files_limit'      => 1,
								'add_button_class' => 'mvl-secondary-btn',

							)
						);
						?>
					</div>
					<div class="mvl-options-settings-tab-content-item-field" data-slug="add_new_term" data-depends-on="field_type" data-depend-values="color" data-depend-action="show">
						<?php
						do_action(
							'stm_listings_load_template',
							'listing-manager/parts/fields/color-input',
							array(
								'id'          => 'add_new_term',
								'label'       => esc_html__( 'Add new term', 'stm_vehicles_listing' ),
								'placeholder' => esc_html__( 'Enter Term', 'stm_vehicles_listing' ),
								'input_name'  => 'add_new_term',
								'color_value' => '#000',
								'value'       => '',
								'term_meta'   => '_category_color',
								'button'      => array(
									'id'    => 'add_new_term_button',
									'text'  => '',
									'class' => 'mvl-primary-btn mvl-short-btn',
									'icon'  => 'fa fa-plus',
								),
								'slug'        => $option['slug'],
							)
						);
						?>
					</div>
				</div>
			</div>
			<?php if ( apply_filters( 'is_mvl_pro', false ) && defined( 'STM_LISTINGS_PRO_PATH' ) ) : ?>
				<div class="mvl-options-settings-tab-content-item" data-tab="skins">
					<div class="mvl-options-settings-tab-content-item-field-group column">
						<div class="mvl-options-settings-tab-content-item-field" data-slug="dropdown_skins" data-depends-on="field_type" data-depend-values="dropdown" data-depend-action="show">
							<?php
							$dropdown_skins = array(
								'skin_1' => array(
									'label' => esc_html__( 'Classic', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/dropdown-1.png',
								),
								'skin_2' => array(
									'label' => esc_html__( 'Modern', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/dropdown-2.png',
								),
							);
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/radio',
								array(
									'id'         => 'dropdown_skins',
									'options'    => $dropdown_skins,
									'input_name' => 'dropdown_skins',
									'value'      => isset( $option['dropdown_skins'] ) ? $option['dropdown_skins'] : 'skin_1',
									'class'      => 'mvl-row',
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="numeric_skins" data-depends-on="field_type" data-depend-values="numeric" data-depend-action="show">
							<?php
							$numeric_skins = array(
								'skin_1' => array(
									'label' => esc_html__( 'Classic', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/numeric-1.png',
								),
								'skin_2' => array(
									'label' => esc_html__( 'Range with Dropdowns', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/numeric-2.png',
								),
								'skin_3' => array(
									'label' => esc_html__( 'Range with Inputs', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/numeric-3.png',
								),
							);
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/radio',
								array(
									'id'         => 'numeric_skins',
									'options'    => $numeric_skins,
									'input_name' => 'numeric_skins',
									'value'      => isset( $option['numeric_skins'] ) ? $option['numeric_skins'] : 'skin_1',
									'class'      => 'mvl-row',
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="checkbox_skins" data-depends-on="field_type" data-depend-values="checkbox" data-depend-action="show">
							<?php
							$checkbox_skins = array(
								'skin_1' => array(
									'label' => esc_html__( 'Classic', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/checkbox-1.png',
								),
								'skin_2' => array(
									'label' => esc_html__( 'Classic (Two Columns)', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/checkbox-2.png',
								),
								'skin_3' => array(
									'label' => esc_html__( 'Buttons', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/checkbox-3.png',
								),
								'skin_4' => array(
									'label' => esc_html__( 'Iconed', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/checkbox-4.png',
								),
							);
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/radio',
								array(
									'id'         => 'checkbox_skins',
									'options'    => $checkbox_skins,
									'input_name' => 'checkbox_skins',
									'value'      => isset( $option['checkbox_skins'] ) ? $option['checkbox_skins'] : 'skin_1',
									'class'      => 'mvl-row',
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="price_skins" data-depends-on="field_type" data-depend-values="price" data-depend-action="show">
							<?php
							$price_skins = array(
								'skin_1' => array(
									'label' => esc_html__( 'Classic', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/price-1.png',
								),
								'skin_2' => array(
									'label' => esc_html__( 'Modern', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/price-2.png',
								),
							);
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/radio',
								array(
									'id'         => 'price_skins',
									'options'    => $price_skins,
									'input_name' => 'price_skins',
									'value'      => isset( $option['price_skins'] ) ? $option['price_skins'] : 'skin_1',
									'class'      => 'mvl-row',
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="color_skins" data-depends-on="field_type" data-depend-values="color" data-depend-action="show">
							<?php
							$color_skins = array(
								'skin_1' => array(
									'label' => esc_html__( 'Color Swatches', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/color-1.png',
								),
							);
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/radio',
								array(
									'id'         => 'color_skins',
									'options'    => $color_skins,
									'input_name' => 'color_skins',
									'value'      => isset( $option['color_skins'] ) ? $option['color_skins'] : 'skin_1',
									'class'      => 'mvl-row',
								)
							);
							?>
						</div>
						<div class="mvl-options-settings-tab-content-item-field" data-slug="location_skins" data-depends-on="field_type" data-depend-values="location" data-depend-action="show">
							<?php
							$location_skins = array(
								'skin_1' => array(
									'label' => esc_html__( 'Location Search Field', 'stm_vehicles_listing' ),
									'image' => STM_LISTINGS_PRO_ASSETS_URL . '/images/custom_field_skins/location-2.png',
								),
							);
							do_action(
								'stm_listings_load_template',
								'/listing-manager/parts/fields/radio',
								array(
									'id'         => 'location_skins',
									'options'    => $location_skins,
									'input_name' => 'location_skins',
									'value'      => isset( $option['location_skins'] ) ? $option['location_skins'] : 'skin_1',
									'class'      => 'mvl-row',
								)
							);
							?>
						</div>

					</div>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>
<?php
do_action( 'stm_listings_load_template', '/listing-manager/parts/modals/icons' );
?>
