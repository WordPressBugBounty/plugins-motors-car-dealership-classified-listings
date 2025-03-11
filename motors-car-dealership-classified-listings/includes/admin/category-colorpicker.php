<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$_categories = apply_filters( 'mvl_listings_attributes', array() );

if ( stm_is_multilisting() && isset( $_GET['post_type'] ) && apply_filters( 'stm_listings_post_type', 'listings' ) !== $_GET['post_type'] ) { //phpcs:ignore
	$_categories = apply_filters( 'stm_get_listings_filter', array(), $_GET['post_type'], array(), false ); //phpcs:ignore
}

if ( ! empty( $_categories ) ) {
	foreach ( $_categories as $_category ) {
		if ( ! empty( $_category['field_type'] ) && 'color' === $_category['field_type'] ) {
			if ( wp_script_is( 'wp-color-picker' ) ) {
				wp_add_inline_script( 'wp-color-picker', 'jQuery(document).ready(function($){$(".colorpicker").wpColorPicker();});' );
			}

			add_action( $_category['slug'] . '_add_form_fields', 'mvl_taxonomy_listing_add_color', 10 );
			add_action( $_category['slug'] . '_edit_form_fields', 'mvl_taxonomy_listing_edit_color', 10, 2 );
			add_action( 'edited_term', 'mvl_taxonomy_listing_color_save', 10, 3 );
			add_action( 'create_term', 'mvl_taxonomy_listing_color_save', 10, 3 );
		}
	}
}

function mvl_taxonomy_listing_add_color( $taxonomy ) {
	?>
	<div class="form-field term-colorpicker-wrap">
		<label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'stm_vehicles_listing' ); ?></label>
		<input name="_category_color" value="#ffffff" class="colorpicker" id="term-colorpicker"/>
	</div>
	<?php
}

function mvl_taxonomy_listing_edit_color( $term ) {
	$color = get_term_meta( $term->term_id, '_category_color', true );
	$color = ( ! empty( $color ) ) ? "#{$color}" : '#ffffff';
	?>
	<tr class="form-field term-colorpicker-wrap">
		<th scope="row">
			<label for="term-colorpicker"><?php esc_html_e( 'Category Color', 'stm_vehicles_listing' ); ?></label>
		</th>
		<td>
			<input name="_category_color" value="<?php echo esc_attr( $color ); ?>" class="colorpicker" id="term-colorpicker"/>
		</td>
	</tr>
	<?php
}

function mvl_taxonomy_listing_color_save( $term_id ) {
	if ( isset( $_POST['_category_color'] ) && ! empty( $_POST['_category_color'] ) ) {
		update_term_meta( $term_id, '_category_color', sanitize_hex_color_no_hash( $_POST['_category_color'] ) );
	} else {
		delete_term_meta( $term_id, '_category_color' );
	}
}
