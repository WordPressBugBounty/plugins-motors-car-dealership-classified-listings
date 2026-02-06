<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

mvl_enqueue_header_scripts_styles( 'listing-search-empty-results' );

$__vars = apply_filters( 'mvl_add_grid_settings_to_array', $__vars );
$__vars = apply_filters( 'mvl_add_list_settings_to_array', $__vars );

$inventory_view = apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );

if ( wp_is_mobile() ) {
	$inventory_view = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type_mobile' );
}

if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() && wp_is_mobile() ) {
	$inventory_view = apply_filters( 'stm_me_get_nuxy_mod', 'grid', get_query_var( 'post_type' ) . '_view_type_mobile' );
}

$inventory_view = apply_filters( 'stm_listings_input', $inventory_view, 'view_type' );

if ( is_mvl_pro() ) {
	$skin_key = 'listings_' . $inventory_view . '_view_skin';
	$skin     = isset( $__vars[ $skin_key ] ) && $__vars[ $skin_key ] ? $__vars[ $skin_key ] : apply_filters( 'motors_vl_get_nuxy_mod', 'default', "{$inventory_view}_card_skin" );
} else {
	$skin = 'default';
}

if ( ! in_array( $skin, array( 'default', 'classic' ), true ) && wp_is_mobile() ) {
	$inventory_view = 'grid';
}

$__vars['skin'] = $skin;

if ( have_posts() ) :

	?>
	<div class="stm-isotope-sorting stm-isotope-sorting-<?php echo esc_attr( $inventory_view ); ?> motors-alignwide <?php echo esc_attr( $skin ); ?>">

		<?php
		do_action( 'stm_listings_load_template', 'filter/featured', $__vars );
		do_action( 'stm_inventory_loop_items_before', $inventory_view );

		while ( have_posts() ) :
			the_post();
			do_action( 'stm_listings_load_template', 'listing-' . $inventory_view, $__vars );
		endwhile;

		do_action( 'stm_inventory_loop_items_after', $inventory_view );
		?>

	</div>
<?php else : ?>
	<div class="stm-listings-empty">
		<span class="motors-icons-search-list"></span>
		<span class="stm-listings-empty__not-found"><?php esc_html_e( 'Not found any vehicle based on your filter', 'stm_vehicles_listing' ); ?></span>
		<span class="stm-listings-empty__another"><?php esc_html_e( 'Try another filter, location or keywords', 'stm_vehicles_listing' ); ?></span>
		<?php
		$reset_url = ( ! apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ) ) ? strtok( $_SERVER['REQUEST_URI'], '?' ) : apply_filters( 'stm_inventory_page_url', '', get_query_var( 'post_type' ) );
		?>
		<a href="<?php echo esc_url( $reset_url ); ?>" class="stm-listings-empty__button">
			<span><?php esc_html_e( 'Reset filters', 'stm_vehicles_listing' ); ?></span>
		</a>
	</div>

	<?php
	if ( ! apply_filters( 'motors_vl_get_nuxy_mod', true, 'enable_distance_search' ) && apply_filters( 'motors_vl_get_nuxy_mod', false, 'recommend_items_empty_result' ) ) {
		do_action( 'stm_listings_load_template', 'filter/inventory/items-empty' );
	}

	endif;
?>

<?php stm_listings_load_pagination( $__vars['posts_per_page'] ); ?>
