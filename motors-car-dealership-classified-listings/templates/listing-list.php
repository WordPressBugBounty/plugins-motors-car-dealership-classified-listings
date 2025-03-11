<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$listing_id           = get_the_ID();
$post_type            = get_post_type();
$regular_price_label  = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label  = get_post_meta( get_the_ID(), 'special_price_label', true );
$price                = get_post_meta( get_the_id(), 'price', true );
$sale_price           = get_post_meta( get_the_id(), 'sale_price', true );
$car_price_form_label = get_post_meta( get_the_ID(), 'car_price_form_label', true );

if ( ! isset( $skin ) ) {
	$skin = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'list_card_skin' );
}

if ( 'default' !== $skin ) {

	$show_logo = apply_filters( 'motors_vl_get_nuxy_mod', '', 'list_skin_show_logo' );

	$args = array(
		'listing_id'           => $listing_id,
		'post_type'            => $post_type,
		'show_logo'            => $show_logo,
		'regular_price_label'  => $regular_price_label,
		'special_price_label'  => $special_price_label,
		'price'                => $price,
		'sale_price'           => $sale_price,
		'car_price_form_label' => $car_price_form_label,
		'skin'                 => $skin,
	);
	if ( function_exists( 'mvl_pro_enqueue_header_scripts_styles' ) ) {
		mvl_pro_enqueue_header_scripts_styles( $skin, 'listing-card/list' );
	}

	do_action( 'stm_listings_load_template', '/listing-cars/list/' . $skin . '.php', $args );
} else {
	?>

<div class="listing-list-loop stm-isotope-listing-item stm-listing-directory-list-loop" data-listing-id="<?php echo esc_attr( $listing_id ); ?>" data-post-type="<?php echo esc_attr( $post_type ); ?>">

	<?php do_action( 'stm_listings_load_template', 'loop/list/image' ); ?>

	<div class="content">
		<div class="meta-top">
			<!--Price-->
			<?php do_action( 'stm_listings_load_template', 'loop/list/price' ); ?>
			<!--Title-->
			<?php do_action( 'stm_listings_load_template', 'loop/list/title' ); ?>
		</div>

		<!--Item parameters-->
		<div class="meta-middle">
			<?php do_action( 'stm_listings_load_template', 'loop/list/options' ); ?>
		</div>

		<!--Item options-->
		<div class="meta-bottom">
			<?php do_action( 'stm_listings_load_template', 'loop/list/features' ); ?>
		</div>
	</div>
</div>
<?php } ?>
