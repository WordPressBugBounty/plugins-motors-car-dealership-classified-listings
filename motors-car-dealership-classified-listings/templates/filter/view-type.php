<?php
$view_type = apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );

if ( ! empty( $_GET['view_type'] ) && in_array( $_GET['view_type'], array( 'grid', 'list' ), true ) ) {
	$view_type = sanitize_text_field( $_GET['view_type'] );
}

$view_list = ( 'list' === $view_type ) ? 'active' : '';
$view_grid = ( 'list' !== $view_type ) ? 'active' : '';
?>

<div class="stm-view-by">
	<a href="#" class="view-grid view-type <?php echo esc_attr( $view_grid ); ?>" data-view="grid">
		<i class="motors-icons-grid-view-ico"></i>
	</a>
	<a href="#" class="view-list view-type <?php echo esc_attr( $view_list ); ?>" data-view="list">
		<i class="motors-icons-list-view-ico"></i>
	</a>
</div>
