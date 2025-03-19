<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function motors_page_options() {
	$group_1 = array(
		'single_name'             => array(
			'label'      => esc_html__( 'Singular name', 'stm_vehicles_listing' ),
			'value'      => '',
			'type'       => 'text',
			'required'   => true,
			'group'      => 'general',
			'column'     => 2,
			'attributes' => array(
				'placeholder' => esc_html__( 'Enter singular name', 'stm_vehicles_listing' ),
			),
		),
		'plural_name'             => array(
			'label'       => esc_html__( 'Plural name', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Used to display the name for multiple items in this field.', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'text',
			'required'    => true,
			'group'       => 'general',
			'column'      => 2,
			'attributes'  => array(
				'placeholder' => esc_html__( 'Ener plural name', 'stm_vehicles_listing' ),
			),
		),
		'slug'                    => array(
			'label'       => esc_html__( 'Slug', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'This is a URL-friendly name, usually lowercase with letters, numbers, and hyphens.', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'text',
			'required'    => true,
			'readonly'    => true,
			'group'       => 'general',
			'column'      => 2,
			'attributes'  => array(
				'placeholder' => esc_html__( 'Enter slug', 'stm_vehicles_listing' ),
			),
		),
		'listing_taxonomy_parent' => array(
			'label'       => esc_html__( 'Select a parent field', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'This setting allows you to choose the main field that this custom field will be associated with.', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'select',
			'group'       => 'general',
			'column'      => 2,
			'choices'     => stm_listings_parent_choice(),
		),
		'field_type'              => array(
			'label'       => esc_html__( 'Field type', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'This setting allows you to choose the input format: "Dropdown" accepts both text and numbers, while "Number" is for numerical values only.', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'select',
			'group'       => 'general',
			'column'      => 2,
			'choices'     => array(
				'dropdown' => esc_html__( 'Dropdown select', 'stm_vehicles_listing' ),
				'numeric'  => esc_html__( 'Number', 'stm_vehicles_listing' ),
			),
		),
		'required_filed'          => array(
			'label'  => esc_html__( 'Make required', 'stm_vehicles_listing' ),
			'value'  => '',
			'type'   => 'checkbox',
			'group'  => 'general',
			'column' => 2,
		),
		'font'                    => array(
			'label'       => esc_html__( 'Choose icon', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Select an icon to represent the field.', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'icon',
			'group'       => 'general',
		),
		'number_field_affix'      => array(
			'label'       => esc_html__( 'Field unit', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'This setting allows you to attach a unit to a number field for clarity, such as "100 km" instead of just "100.” The unit will appear on all pages.', 'stm_vehicles_listing' ),
			'value'       => '',
			'dependency'  => array(
				'slug'  => 'field_type',
				'value' => 'numeric',
				'type'  => 'not_empty',
			),
			'attributes'  => array(
				'placeholder' => esc_html__( 'Enter field unit', 'stm_vehicles_listing' ),
			),
			'column'      => 2,
			'type'        => 'text',
			'group'       => 'general',
		),
		'use_delimiter'           => array(
			'label'      => esc_html__( 'Add separator', 'stm_vehicles_listing' ),
			'dependency' => array(
				'slug'  => 'field_type',
				'value' => 'numeric',
				'type'  => 'not_empty',
			),
			'value'      => '',
			'type'       => 'checkbox',
			'group'      => 'general',
			'column'     => 2,
		),
		'slider_in_tabs'          => array(
			'label'      => esc_html__( 'Field display options in tabs', 'stm_vehicles_listing' ),
			'dependency' => array(
				'slug'  => 'field_type',
				'value' => 'numeric',
				'type'  => 'not_empty',
			),
			'value'      => 'dropdown',
			'type'       => 'radio',
			'choices'    => array(
				'dropdown' => esc_html__( 'Dropdown with Range', 'stm_vehicles_listing' ),
				'slider'   => esc_html__( 'Slider Range', 'stm_vehicles_listing' ),
			),
			'group'      => 'general',
		),
		'slider'                  => array(
			'label'      => esc_html__( 'Field display options in filters', 'stm_vehicles_listing' ),
			'dependency' => array(
				'slug'  => 'field_type',
				'value' => 'numeric',
				'type'  => 'not_empty',
			),
			'value'      => 'dropdown',
			'type'       => 'radio',
			'choices'    => array(
				'dropdown' => esc_html__( 'Dropdown with Range', 'stm_vehicles_listing' ),
				'slider'   => esc_html__( 'Slider Range', 'stm_vehicles_listing' ),
			),
			'group'      => 'general',
		),
		'slider_step'             => array(
			'label'       => esc_html__( 'Step size', 'stm_vehicles_listing' ),
			'description' => '',
			'dependency'  => array(
				array(
					'slug'  => 'field_type',
					'value' => 'numeric',
					'type'  => 'not_empty',
				),
				array(
					'slug' => 'slider',
					'type' => 'not_empty',
				),
				array(
					'slug' => 'slider_in_tabs',
					'type' => 'not_empty',
				),
			),
			'value'       => 1,
			'attributes'  => array(
				'placeholder' => esc_html__( 'Enter step size', 'stm_vehicles_listing' ),
			),
			'type'        => 'text',
			'column'      => 2,
			'group'       => 'general',
		),
		'show_inputs'             => array(
			'label'      => esc_html__( 'Show inputs (slider for filter)', 'stm_vehicles_listing' ),
			'dependency' => array(
				array(
					'slug'  => 'field_type',
					'value' => 'numeric',
					'type'  => 'not_empty',
				),
				array(
					'slug' => 'slider',
					'type' => 'not_empty',
				),
				array(
					'slug' => 'slider_in_tabs',
					'type' => 'not_empty',
				),
			),
			'type'       => 'checkbox',
			'value'      => true,
			'group'      => 'general',
			'column'     => 2,
		),
		'listing_price_field'     => array(
			'label'       => esc_html__( 'Listing price field', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'This field will be determined as the price for listings. Only one field can be assigned as price field!', 'stm_vehicles_listing' ),
			'dependency'  => array(
				'slug'  => 'field_type',
				'value' => 'numeric',
				'type'  => 'not_empty',
			),
			'value'       => '',
			'type'        => 'checkbox',
			'group'       => 'general',
			'column'      => 2,
		),
	);

	$group_1 = apply_filters( 'stm_listings_page_options_group_1', $group_1 );

	$group_2 = array(
		'use_on_car_listing_page'         => array(
			'label'       => esc_html__( 'Show on grid view', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Check if you want to see this category on car listing page (machine card)', 'stm_vehicles_listing' ),
			'value'       => '',
			'preview'     => 'grid.jpg',
			'type'        => 'checkbox',
			'group'       => 'display',
		),
		'use_on_car_archive_listing_page' => array(
			'label'       => esc_html__( 'Show on list view', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Check if you want to see this category on car listing archive page with icon', 'stm_vehicles_listing' ),
			'value'       => '',
			'preview'     => 'list.jpg',
			'type'        => 'checkbox',
			'group'       => 'display',
		),
		'use_on_single_car_page'          => array(
			'label'       => esc_html__( 'Show on listing page', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Check if you want to see this category on single car page', 'stm_vehicles_listing' ),
			'value'       => '',
			'preview'     => 'single_car_page.jpg',
			'type'        => 'checkbox',
			'group'       => 'display',
		),
		'show_in_admin_column'            => array(
			'label'       => esc_html__( 'Show in admin column table', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'This column will be shown in admin', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'checkbox',
			'preview'     => 'admin_table.png',
			'group'       => 'display',
		),
	);

	$group_2 = apply_filters( 'stm_listings_page_options_group_2', $group_2 );

	$group_3 = array(
		'terms_filters_sort_by'         => array(
			'label'   => esc_html__( 'Sort custom field options', 'stm_vehicles_listing' ),
			'value'   => 'count_asc',
			'type'    => 'select',
			'choices' => array(
				'count_asc'  => esc_html__( 'Count Low to High', 'stm_vehicles_listing' ),
				'count_desc' => esc_html__( 'Count High to Low', 'stm_vehicles_listing' ),
				'name_asc'   => esc_html__( 'Name A to Z', 'stm_vehicles_listing' ),
				'name_desc'  => esc_html__( 'Name Z to A', 'stm_vehicles_listing' ),
			),
			'group'   => 'filter',
		),
		'is_multiple_select'            => array(
			'label'       => esc_html__( 'Multiple filter select', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Enable this setting to allow users to select multiple filter options for a custom field while searching listings.', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'checkbox',
			'group'       => 'filter',
		),
		'use_on_car_filter'             => array(
			'label' => esc_html__( 'Show in filters', 'stm_vehicles_listing' ),
			'value' => '',
			'type'  => 'checkbox',
			'group' => 'filter',
		),
		'use_on_car_filter_links'       => array(
			'label'       => esc_html__( 'Show as a block with links', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Enable this setting to display the field as clickable options in filters.', 'stm_vehicles_listing' ),
			'value'       => '',
			'preview'     => 'car-filter-as-block-with-links.jpg',
			'type'        => 'checkbox',
			'group'       => 'filter',
		),
		'filter_links_default_expanded' => array(
			'label'       => esc_html__( 'Layout Options', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Be aware of using both as filter option', 'stm_vehicles_listing' ),
			'value'       => 'open',
			'type'        => 'radio',
			'dependency'  => array(
				'slug' => 'use_on_car_filter_links',
				'type' => 'not_empty',
			),
			'choices'     => array(
				'open'  => esc_html__( 'Show box open by default', 'stm_vehicles_listing' ),
				'close' => esc_html__( 'Show box closed by default', 'stm_vehicles_listing' ),
			),
			'group'       => 'filter',
		),
	);

	$group_3 = apply_filters( 'stm_listings_page_options_group_3', $group_3 );

	$group_4 = array(
		'use_on_directory_filter_title'         => array(
			'label'       => esc_html__( 'Show in generated filter title', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Enable this setting to include the field in the title of search results.', 'stm_vehicles_listing' ),
			'value'       => '',
			'preview'     => 'title.jpg',
			'type'        => 'checkbox',
			'group'       => 'filter',
		),
		'listing_rows_numbers_enable'           => array(
			'label'       => esc_html__( 'Show as checkboxes in the inventory filter', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Use as checkboxes with images 1 or 2 columns', 'stm_vehicles_listing' ),
			'value'       => '',
			'preview'     => 'column.png',
			'type'        => 'checkbox',
			'group'       => 'filter',
		),
		'listing_rows_numbers'                  => array(
			'label'       => esc_html__( 'Layout Options', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Select how checkboxes are displayed on the page.', 'stm_vehicles_listing' ),
			'value'       => 'one_col',
			'type'        => 'radio',
			'dependency'  => array(
				'slug' => 'listing_rows_numbers_enable',
				'type' => 'not_empty',
			),
			'choices'     => array(
				'one_col'  => esc_html__( '1 column per row', 'stm_vehicles_listing' ),
				'two_cols' => esc_html__( '2 columns per row', 'stm_vehicles_listing' ),
			),
			'group'       => 'filter',
		),
		'listing_rows_numbers_default_expanded' => array(
			'label'      => esc_html__( 'Item Display Options', 'stm_vehicles_listing' ),
			'value'      => 'open',
			'type'       => 'radio',
			'dependency' => array(
				'slug' => 'listing_rows_numbers_enable',
				'type' => 'not_empty',
			),
			'choices'    => array(
				'open'  => esc_html__( 'Show box open by default', 'stm_vehicles_listing' ),
				'close' => esc_html__( 'Show box closed by default', 'stm_vehicles_listing' ),
			),
			'group'      => 'filter',
		),
		'enable_checkbox_button'                => array(
			'label'       => esc_html__( 'Add submit button to this checkbox area', 'stm_vehicles_listing' ),
			'description' => esc_html__( 'Clicking on this button will update the page and apply the filter to show the relevant listings.', 'stm_vehicles_listing' ),
			'value'       => '',
			'type'        => 'checkbox',
			'dependency'  => array(
				'slug' => 'listing_rows_numbers_enable',
				'type' => 'not_empty',
			),
			'group'       => 'filter',
		),
	);

	$group_4 = apply_filters( 'stm_listings_page_options_group_4', $group_4 );

	$options   = array_merge( $group_1, $group_2, $group_3, $group_4 );
	$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );

	// remove "Listing price field" if multilisting is deactivated OR current post type is the default one
	if ( ! stm_is_multilisting() || ( ! empty( $post_type ) && apply_filters( 'stm_listings_post_type', 'listings' ) === $post_type ) ) {
		unset( $options['listing_price_field'] );
	}

	// rename all "car"s to "listing"s if multilisting is active
	if ( stm_is_multilisting() || ( ! empty( $post_type ) && apply_filters( 'stm_listings_post_type', 'listings' ) !== $post_type ) ) {
		foreach ( $options as $slug => $arr ) {
			if ( ! empty( $arr['label'] ) && strpos( $arr['label'], 'car' ) !== false ) {
				$options[ $slug ]['label'] = esc_html( str_replace( 'car', 'listing', $arr['label'] ) );
			} elseif ( ! empty( $arr['description'] ) && strpos( $arr['description'], 'car' ) !== false ) {
				$options[ $slug ]['description'] = esc_html( str_replace( 'car', 'listing', $arr['description'] ) );
			}
		}
	}

	return apply_filters( 'stm_listings_page_options_filter', $options );
}

function motors_page_options_groups() {
	$options  = motors_page_options();
	$response = array();

	foreach ( $options as $option_key => $option ) {
		if ( ! empty( $option['dependency'] ) ) {
			if ( ! empty( $option['dependency']['slug'] ) ) {
				$response['dependency'][ $option['dependency']['slug'] ][ $option_key ] = $option;
			} elseif ( is_array( $option['dependency'] ) ) {
				foreach ( $option['dependency'] as $dependency ) {
					if ( ! empty( $dependency['slug'] ) ) {
						$response['dependency'][ $dependency['slug'] ][ $option_key ] = $option;
					}
				}
			}
		} else {
			$response[ $option['group'] ]['items'][ $option_key ] = $option;
		}
	}

	$response['general']['label'] = __( 'General', 'stm_vehicles_listing' );
	$response['display']['label'] = __( 'Display Settings', 'stm_vehicles_listing' );
	$response['filter']['label']  = __( 'Filter settings', 'stm_vehicles_listing' );

	return $response;
}

function stm_listings_categories_admin_enqueue( $hook ) {
	if ( ! wp_script_is( 'stm-theme-multiselect' ) ) {
		wp_enqueue_script( 'stm-theme-quicksearch' );
		wp_enqueue_script( 'stm-theme-multiselect' );
	}

	if ( ! wp_script_is( 'stm-listings-js' ) ) {
		wp_enqueue_script( 'stm-listings-js' );
	}

	if ( ! wp_script_is( 'stm-listings-old-js' ) ) {
		wp_enqueue_script( 'stm-listings-old-js' );
	}

	if ( 'motors-plugin_page_listing_categories' === $hook && ! wp_script_is( 'stm_admin_listing_categories' ) ) {
		wp_enqueue_style( 'stm_admin_listing_categories' );
	}
}

add_action( 'admin_enqueue_scripts', 'stm_listings_categories_admin_enqueue' );


function stm_add_listing_theme_menu_item() {
	add_submenu_page(
		'mvl_plugin_settings',
		__( 'Custom Fields', 'stm_vehicles_listing' ),
		__( 'Custom Fields', 'stm_vehicles_listing' ),
		'manage_options',
		'listing_categories',
		'stm_listings_vehicle_listing_settings_page',
		5
	);

	add_filter(
		'mvl_submenu_positions',
		function ( $positions ) {
			$positions['listing_categories'] = 15;

			return $positions;
		}
	);
}

add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', 'stm_add_listing_theme_menu_item', 12, 1 );

function stm_listings_vehicle_listing_settings_page() {
	require_once STM_LISTINGS_PATH . '/includes/admin/categories/main.php';

	if ( function_exists( 'stm_vehicles_listing_get_icons_html' ) ) {
		stm_vehicles_listing_get_icons_html();
	}
}

/**
 * @used-by stm_vehicles_listings_show_field
 *
 * @param $name
 * @param $settings
 * @param $values
 *
 * @return void
 * @uses    motors_custom_field_icon to get the html of the icon field
 */
function motors_custom_field_icon( $name, $settings, $values ) {
	$icon  = ( ! empty( $values[ $name ] ) ) ? $values[ $name ] : '';
	$value = ( ! empty( $values[ $name ] ) ) ? $values[ $name ] : '';
	?>
	<div class="stm_form_wrapper stm_form_wrapper_<?php echo esc_attr( $settings['type'] ); ?>">
		<label for="<?php echo esc_attr( $name ); ?>">
			<?php
			echo esc_html( $settings['label'] . ':' );
			if ( ! empty( $settings['description'] ) ) :
				?>
				<span class="stm_custom_fields__tooltip" data-bs-offset="10,15" data-bs-toggle="tooltip"
					data-bs-placement="top" data-bs-title="<?php echo esc_attr( $settings['description'] ); ?>">
				<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/admin/help.svg' ); ?>"
					alt="<?php esc_attr_e( 'Field description', 'stm_vehicles_listing' ); ?>">
			</span>
			<?php endif; ?>
		</label>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
		<div class="stm_vehicles_listing_icon <?php echo ( ! empty( $icon ) ) ? '' : 'stm_vehicles_listing_icon__empty'; ?>">
			<div class="icon">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
			</div>
			<div class="stm_add_icon" id="<?php echo esc_attr( $name ); ?>">
				<?php esc_html_e( 'Add icon', 'stm_vehicles_listing' ); ?>
			</div>
			<div class="stm_change_icon">
				<?php esc_html_e( 'Change icon', 'stm_vehicles_listing' ); ?>
			</div>
			<div class="stm_delete_icon">
				<?php esc_html_e( 'Delete icon', 'stm_vehicles_listing' ); ?>
			</div>
		</div>
	</div>
	<?php
}

function motors_custom_field_show_dependency( $settings ) {
	$attributes = '';

	if ( ! empty( $settings['dependency'] ) ) {
		$dependency = $settings['dependency'];
		$attributes = 'data-depended="true" ';
		$slugs      = array();
		$attr_list  = array();

		foreach ( $dependency as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $_key => $_value ) {
					if ( in_array( $_key, array( 'value', 'slug' ), true ) && in_array( $_value, array( 'numeric', 'field_type' ), true ) ) {
						continue;
					}

					if ( 'slug' === $_key ) {
						$slugs[] = $_value;
					} else {
						$attr_list[ $_key ] = 'data-' . $_key . '="' . esc_attr( $_value ) . '"';
					}
				}
			} else {
				$attributes .= 'data-' . $key . '="' . esc_attr( $value ) . '"';
			}
		}

		if ( ! empty( $slugs ) ) {
			$attributes .= ' data-slug="' . esc_attr( implode( ',', $slugs ) ) . '" ';
			$attributes .= implode( ' ', $attr_list );
		}

		$attributes .= ' style="display: none;"';
	}

	echo wp_kses_post( apply_filters( 'stm_vl_depends_filter', $attributes ) );
}

function stm_vehicles_listing_has_preview( $settings ) {
	$class = '';

	if ( ! empty( $settings['preview'] ) ) {
		$class = 'stm-has-preview-image';
	}

	return $class;
}

function motors_custom_field_preview( $settings ) {
	if ( ! empty( $settings['preview'] ) ) :
		$url = ( ! empty( $settings['preview_url'] ) ) ? $settings['preview_url'] : STM_LISTINGS_URL . '/assets/images/tmp/';
		?>
		<span
				class="stm_custom_fields__preview"
				data-bs-toggle="tooltip-preview"
				title="<img class='tooltip-image' src='<?php echo esc_url( $url . $settings['preview'] ); ?>' alt='<?php echo esc_attr( $settings['label'] ); ?>'>">
			<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
		</span>

		<?php
	endif;
}

// showing features in admin columns
$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );
if ( stm_is_multilisting() ) {
	$slugs = STMMultiListing::stm_get_listing_type_slugs();
	if ( ! empty( $slugs ) ) {
		$post_types = array_merge( $post_types, $slugs );
	}
}

foreach ( $post_types as $_post_type ) {
	add_action( 'manage_' . $_post_type . '_posts_custom_column', 'stm_listings_display_posts_stickiness', 10, 2 );
	add_filter( 'manage_' . $_post_type . '_posts_columns', 'stm_listings_add_sticky_column' );
}

function stm_listings_display_posts_stickiness( $column, $post_id ) {
	if ( 'stm_image' === $column ) {

		if ( has_post_thumbnail( $post_id ) ) {
			echo '<div class="attachment">';
			echo '<div class="attachment-preview">';
			echo '<div class="thumbnail">';
			echo '<div class="centered">';
			echo wp_kses_post( '<a href="' . get_edit_post_link( $post_id ) . '">' . get_the_post_thumbnail( $post_id, 'medium' ) . '</a>' );
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}

	$user_columns = stm_get_numeric_admin_fields();
	if ( ! empty( $user_columns[ $column ] ) ) {
		$col = str_replace( 'stm-column-', '', $column );
		if ( 'price' === $col ) {
			$col = 'stm_genuine_price';
		}
		$value = get_post_meta( $post_id, $col, true );
		if ( empty( $value ) ) {
			$value = '—';
		} else {
			if ( function_exists( 'stm_listing_price_view' ) ) {
				if ( 'stm_genuine_price' === $col ) {
					$value = apply_filters( 'stm_filter_price_view', '', $value );
				}
			}
		}
		echo esc_attr( apply_filters( 'stm_vl_price_view_filter', $value ) );
	}
}

/* Add custom column to post list */
function stm_listings_add_sticky_column( $columns ) {

	$column_date = $columns['date'];
	unset( $columns['author'], $columns['comments'], $columns['date'] );
	$_columns                 = array();
	$new_columns              = array();
	$new_columns['cb']        = '<input type="checkbox" />';
	$new_columns['stm_image'] = __( 'Image', 'stm_vehicles_listing' );

	$user_columns = stm_get_numeric_admin_fields();
	if ( ! empty( $user_columns ) ) {
		foreach ( $user_columns as $key => $value ) {
			$columns[ $key ] = $value;
		}
	}

	$columns['date'] = $column_date;

	return array_merge( $new_columns, $columns );
}

// need to make this multilisting ready
function stm_get_numeric_admin_fields() {
	$cols = array();

	$options = get_option( 'stm_vehicle_listing_options' );

	if ( get_post_type( get_the_ID() ) !== apply_filters( 'stm_listings_post_type', 'listings' ) ) {
		$post_type = get_post_type( get_the_ID() );
		$options   = get_option( "stm_{$post_type}_options" );
	}

	if ( ! empty( $options ) ) {
		foreach ( $options as $option ) {
			if ( ! empty( $option['numeric'] ) && ! empty( $option['show_in_admin_column'] ) ) {
				$cols[ 'stm-column-' . $option['slug'] ] = esc_html( $option['single_name'] );
			}
		}
	}

	return $cols;
}
