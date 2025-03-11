<?php
$regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label = get_post_meta( get_the_ID(), 'special_price_label', true );

$price      = get_post_meta( get_the_id(), 'price', true );
$sale_price = get_post_meta( get_the_id(), 'sale_price', true );

$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

$data = array(
	'data_price' => 0,
);

if ( ! empty( $price ) ) {
	$data['data_price'] = $price;
}

if ( ! empty( $sale_price ) ) {
	$data['data_price'] = $sale_price;
}

if ( ! empty( $custom_img_size ) ) {
	$data['custom_img_size'] = $custom_img_size;
}

$columns         = ( empty( $columns ) ) ? 4 : $columns;
$data['columns'] = $columns;

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price = $sale_price;
}

$taxonomies = apply_filters( 'stm_get_taxonomies', array() );
foreach ( $taxonomies as $val ) {
	$taxData = stm_get_taxonomies_with_type( $val );
	if ( ! empty( $taxData['numeric'] ) && ! empty( $taxData['slider'] ) ) {
		$replace_val                    = str_replace( '-', '__', $val );
		$value                          = get_post_meta( get_the_id(), $val, true );
		$data[ 'data_' . $replace_val ] = $value;
		$data['atts'][]                 = $replace_val;
	}
}

if ( ! isset( $skin ) ) {
	$skin = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'grid_card_skin' );
}

if ( 'default' !== $skin ) {
	if ( function_exists( 'mvl_pro_enqueue_header_scripts_styles' ) ) {
		mvl_pro_enqueue_header_scripts_styles( $skin, 'listing-card/grid' );
	}
	$show_logo = apply_filters( 'motors_vl_get_nuxy_mod', '', 'grid_skin_show_logo' );

	$args = array(
		'regular_price_label'  => $regular_price_label,
		'special_price_label'  => $special_price_label,
		'price'                => $price,
		'sale_price'           => $sale_price,
		'car_price_form_label' => $car_price_form_label,
		'data'                 => $data,
		'show_logo'            => $show_logo,
		'skin'                 => $skin,
		'columns'              => $columns,
	);

	do_action( 'stm_listings_load_template', '/listing-cars/grid/' . $skin . '.php', $args );
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
				'price'                => $price,
				'sale_price'           => $sale_price,
				'car_price_form_label' => $car_price_form_label,
			)
		);

		do_action( 'stm_listings_load_template', 'loop/grid/data' );
		?>

	</div>
	</a>
	</div>

<?php } ?>
