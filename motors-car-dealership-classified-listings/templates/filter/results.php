<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

mvl_enqueue_header_scripts_styles( 'listing-search-empty-results' );

$grid_action_buttons = array(
	'listing_test_drive' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive_grid_as_btn' ),
	'listing_share'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share_grid_as_btn' ),
	'listing_pdf'        => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_pdf_grid_as_btn' ),
	'listing_quote'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote_grid_as_btn' ),
	'listing_trade'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade_grid_as_btn' ),
);

$grid_action_popup_btns = array(
	'listing_test_drive' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive_grid' ),
	'listing_share'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share_grid' ),
	'listing_pdf'        => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_pdf_grid' ),
	'listing_quote'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote_grid' ),
	'listing_trade'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade_grid' ),
);

$list_action_buttons = array(
	'listing_test_drive' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive_as_btn' ),
	'listing_share'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share_as_btn' ),
	'listing_pdf'        => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_pdf_as_btn' ),
	'listing_quote'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote_as_btn' ),
	'listing_trade'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade_as_btn' ),
);

$list_action_popup_btns = array(
	'listing_test_drive' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive' ),
	'listing_share'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_share' ),
	'listing_pdf'        => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_pdf' ),
	'listing_quote'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote' ),
	'listing_trade'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade' ),
);

$action_buttons_hover = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_actions_button_on_hover_grid' );
$veiw_details_grid    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_view_details_button_grid' );
$veiw_details_list    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_view_details_button' );

$__vars['grid_action_buttons']    = isset( $grid_action_buttons ) ? $grid_action_buttons : array();
$__vars['grid_action_popup_btns'] = isset( $grid_action_popup_btns ) ? $grid_action_popup_btns : array();
$__vars['list_action_buttons']    = isset( $list_action_buttons ) ? $list_action_buttons : array();
$__vars['list_action_popup_btns'] = isset( $list_action_popup_btns ) ? $list_action_popup_btns : array();
$__vars['veiw_details_grid']      = isset( $veiw_details_grid ) ? $veiw_details_grid : false;
$__vars['veiw_details_list']      = isset( $veiw_details_list ) ? $veiw_details_list : false;
$__vars['action_buttons_hover']   = isset( $action_buttons_hover ) ? $action_buttons_hover : false;

$inventory_view = apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );

if ( wp_is_mobile() ) {
	$inventory_view = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type_mobile' );
}

if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() && wp_is_mobile() ) {
	$inventory_view = apply_filters( 'stm_me_get_nuxy_mod', 'grid', get_query_var( 'post_type' ) . '_view_type_mobile' );
}

$inventory_view = apply_filters( 'stm_listings_input', $inventory_view, 'view_type' );

if ( apply_filters( 'is_mvl_pro', false ) ) {
	$skin = apply_filters( 'motors_vl_get_nuxy_mod', 'default', "{$inventory_view}_card_skin" );
} else {
	$skin = 'default';
}

if ( 'default' !== $skin && wp_is_mobile() ) {
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
