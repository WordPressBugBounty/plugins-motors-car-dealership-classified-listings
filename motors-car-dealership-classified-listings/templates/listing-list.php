<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$listing_id = get_the_ID();

$skins = array(
	'skin_2' => array(
		'width'  => 580,
		'height' => 520,
	),
	'skin_3' => array(
		'width'  => 645,
		'height' => 550,
	),
	'skin_4' => array(
		'width'  => 615,
		'height' => 500,
	),
);

$image_sizes = $skins[ $skin ] ?? array(
	'width'  => 580,
	'height' => 460,
);

$data = array(
	'price'                  => get_post_meta( $listing_id, 'price', true ),
	'sale_price'             => get_post_meta( $listing_id, 'sale_price', true ),
	'car_price_form_label'   => get_post_meta( $listing_id, 'car_price_form_label', true ),
	'special_price_label'    => get_post_meta( $listing_id, 'special_price_label', true ),
	'regular_price_label'    => get_post_meta( $listing_id, 'regular_price_label', true ),
	'post_type'              => get_post_type(),
	'show_logo'              => '',
	'columns'                => isset( $columns ) ? $columns : 4,
	'listing_id'             => $listing_id,
	'skin'                   => $skin,
	'image_sizes'            => $image_sizes,
	'certificates'           => array(
		'certificate_1' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_certified_logo_1' ),
		'certificate_2' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_certified_logo_2' ),
	),
	'special_car_data'       => array(
		'special_car'    => get_post_meta( $listing_id, 'special_car', true ),
		'badge_text'     => get_post_meta( $listing_id, 'badge_text', true ),
		'badge_bg_color' => get_post_meta( $listing_id, 'badge_bg_color', true ),
	),
	'sold_label_data'        => array(
		'show_sold_label'  => apply_filters( 'motors_vl_get_nuxy_mod', '', 'show_sold_listings' ),
		'asSold'           => get_post_meta( $listing_id, 'car_mark_as_sold', true ),
		'sold_badge_color' => apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' ),
	),
	'list_action_buttons'    => array(
		'listing_test_drive' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive_as_btn' ),
		'listing_share'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share_as_btn' ),
		'listing_pdf'        => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_pdf_as_btn' ),
		'listing_quote'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote_as_btn' ),
		'listing_trade'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade_as_btn' ),
	),
	'list_action_popup_btns' => array(
		'listing_test_drive' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive' ),
		'listing_share'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share' ),
		'listing_pdf'        => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_pdf' ),
		'listing_quote'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote' ),
		'listing_trade'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade' ),
	),
);

if ( ! isset( $data['skin'] ) ) {
	$data['skin'] = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'list_card_skin' );
}

if ( 'default' !== $data['skin'] && apply_filters( 'is_mvl_pro', false ) && defined( 'STM_LISTINGS_PRO_PATH' ) ) {
	$data['show_logo'] = apply_filters( 'motors_vl_get_nuxy_mod', '', 'list_skin_show_logo' );

	if ( function_exists( 'mvl_pro_enqueue_header_scripts_styles' ) ) {
		mvl_pro_enqueue_header_scripts_styles( $data['skin'], 'listing-card/list' );
	}

	do_action( 'stm_listings_load_template', '/listing-cars/list/' . esc_attr( $data['skin'] ) . '.php', $data );
} else {
	?>
	<div class="listing-list-loop stm-isotope-listing-item stm-listing-directory-list-loop" data-listing-id="<?php echo esc_attr( $data['listing_id'] ); ?>" data-post-type="<?php echo esc_attr( $data['post_type'] ); ?>">

		<?php do_action( 'stm_listings_load_template', 'loop/list/image', $data ); ?>

		<div class="content">
			<div class="meta-top">
				<!--Price-->
				<?php do_action( 'stm_listings_load_template', 'loop/list/price', $data ); ?>
				<!--Title-->
				<?php do_action( 'stm_listings_load_template', 'loop/list/title', $data ); ?>
			</div>

			<!--Item parameters-->
			<div class="meta-middle">
				<?php do_action( 'stm_listings_load_template', 'loop/list/options', $data ); ?>
			</div>

			<!--Item options-->
			<div class="meta-bottom">
				<?php do_action( 'stm_listings_load_template', 'loop/list/features', $data ); ?>
			</div>
		</div>
	</div>
	<?php
}
?>
