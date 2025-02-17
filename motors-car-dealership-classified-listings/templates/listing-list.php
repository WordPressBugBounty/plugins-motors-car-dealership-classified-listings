<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$listing_id = get_the_ID();
$post_type  = get_post_type();
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
