<?php
$view_type = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type' );

if ( ! empty( $_GET['view_type'] ) && in_array( $_GET['view_type'], array( 'grid', 'list' ), true ) ) {
	$view_type = sanitize_text_field( $_GET['view_type'] );
}

$ppp = ${'ppp_on_' . $view_type};
if ( isset( $grid_thumb_img_size ) && isset( $list_thumb_img_size ) ) {
	$custom_img_size = ${$view_type . '_thumb_img_size'};
}

if ( ! isset( $post_type ) || empty( $post_type ) ) {
	$post_type = 'listings';
}

if ( 'default' === $listings_grid_view_skin ) {
	$listings_grid_view_skin = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'grid_card_skin' );
}
if ( 'default' === $listings_list_view_skin ) {
	$listings_list_view_skin = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'list_card_skin' );
}

?>
<div class="motors-elementor-inventory-search-results" id="listings-result" data-listings-grid-view-skin="<?php echo esc_attr( $listings_grid_view_skin ); ?>" data-listings-list-view-skin="<?php echo esc_attr( $listings_list_view_skin ); ?>" data-custom-img-size="<?php echo ! empty( $custom_img_size ) ? esc_attr( $custom_img_size ) : ''; ?>">
	<?php
	do_action(
		'stm_listings_load_results',
		array(
			'posts_per_page'          => $ppp,
			'post_type'               => $post_type,
			'custom_img_size'         => ( ! empty( $custom_img_size ) ) ? $custom_img_size : null,
			'view_type'               => $view_type,
			'listings_grid_view_skin' => $listings_grid_view_skin,
			'listings_list_view_skin' => $listings_list_view_skin,
		)
	);

	wp_reset_query(); //phpcs:ignore
	?>
</div>
