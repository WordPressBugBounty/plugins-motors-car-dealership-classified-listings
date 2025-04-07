<?php
$data = array(
	'price'                  => get_post_meta( get_the_ID(), 'price', true ),
	'sale_price'             => get_post_meta( get_the_ID(), 'sale_price', true ),
	'car_price_form_label'   => get_post_meta( get_the_ID(), 'car_price_form_label', true ),
	'special_price_label'    => get_post_meta( get_the_ID(), 'special_price_label', true ),
	'regular_price_label'    => get_post_meta( get_the_ID(), 'regular_price_label', true ),
	'post_type'              => get_post_type(),
	'show_logo'              => '',
	'columns'                => isset( $columns ) ? $columns : 4,
	'listing_id'             => get_the_ID(),
	'skin'                   => isset( $skin ) ? $skin : apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'grid_card_skin' ),
	'action_buttons_hover'   => isset( $__vars['action_buttons_hover'] ) ? $__vars['action_buttons_hover'] : apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_actions_button_on_hover_grid' ),
	'image_sizes'            => array(
		'width'  => 380,
		'height' => 260,
	),
	'certificates'           => array(
		'certificate_1' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_certified_logo_1' ),
		'certificate_2' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_certified_logo_2' ),
	),
	'special_car_data'       => array(
		'special_car'    => get_post_meta( get_the_ID(), 'special_car', true ),
		'badge_text'     => get_post_meta( get_the_ID(), 'badge_text', true ),
		'badge_bg_color' => get_post_meta( get_the_ID(), 'badge_bg_color', true ),
	),
	'sold_label_data'        => array(
		'show_sold_label'  => apply_filters( 'motors_vl_get_nuxy_mod', '', 'show_sold_listings' ),
		'asSold'           => get_post_meta( get_the_ID(), 'car_mark_as_sold', true ),
		'sold_badge_color' => apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' ),
	),
	'grid_action_buttons'    => isset( $grid_action_buttons ) ? $grid_action_buttons : array(),
	'grid_action_popup_btns' => isset( $grid_action_popup_btns ) ? $grid_action_popup_btns : array(),
	'veiw_details_grid'      => isset( $veiw_details_grid ) ? $veiw_details_grid : array(),
	'veiw_details_list'      => isset( $veiw_details_list ) ? $veiw_details_list : array(),
	'listing_options_list'   => isset( $listing_options_list ) ? $listing_options_list : array(),
	'listing_options_grid'   => isset( $listing_options_grid ) ? $listing_options_grid : array(),
);

if ( ! isset( $data['skin'] ) ) {
	$data['skin'] = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'grid_card_skin' );
}

$data['data_price'] = ! empty( $data['sale_price'] ) ? $data['sale_price'] : ( ! empty( $data['price'] ) ? $data['price'] : 0 );

if ( isset( $custom_img_size ) ) {
	$data['custom_img_size'] = $custom_img_size;
}

$taxonomies = apply_filters( 'stm_get_taxonomies', array() );
foreach ( $taxonomies as $val ) {
	$tax_data = stm_get_taxonomies_with_type( $val );
	if ( ! empty( $tax_data['numeric'] ) && ! empty( $tax_data['slider'] ) ) {
		$replace_val                    = str_replace( '-', '__', $val );
		$value                          = get_post_meta( get_the_ID(), $val, true );
		$data[ 'data_' . $replace_val ] = $value;
		$data['atts'][]                 = $replace_val;
	}
}

if ( 'default' !== $data['skin'] && apply_filters( 'is_mvl_pro', false ) && defined( 'STM_LISTINGS_PRO_PATH' ) ) {
	if ( function_exists( 'mvl_pro_enqueue_header_scripts_styles' ) ) {
		mvl_pro_enqueue_header_scripts_styles( $data['skin'], 'listing-card/grid' );
	}
	$data['show_logo'] = apply_filters( 'motors_vl_get_nuxy_mod', '', 'grid_skin_show_logo' );

	do_action( 'stm_listings_load_template', '/listing-cars/grid/' . $data['skin'] . '.php', $data );
} else {
	?>

	<?php do_action( 'stm_listings_load_template', 'loop/grid/start', $data ); ?>
	<?php do_action( 'stm_listings_load_template', 'loop/grid/image', $data ); ?>

	<div class="listing-car-item-meta">
		<?php
		do_action(
			'stm_listings_load_template',
			'loop/grid/title_price',
			array(
				'price'                => $data['price'],
				'sale_price'           => $data['sale_price'],
				'car_price_form_label' => $data['car_price_form_label'],
			)
		);

		do_action( 'stm_listings_load_template', 'loop/grid/data' );
		?>
	</div>
	</a>
	</div>

<?php } ?>
