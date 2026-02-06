<?php
$view_type                 = ( ! empty( $view_type ) ) ? $view_type : apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );
$image_size                = ( ! apply_filters( 'stm_is_motors_theme', false ) ) ? 'medium' : ( ( 'grid' === $view_type ) ? 'stm-img-255' : 'stm-img-280' );
$thumb_width               = ( 'grid' === $view_type ) ? 255 : 280;
$grid_col_w                = ( 'grid' === $view_type ) ? '(max-width: 1023px) 33vw, ' : '';
$placeholder_path          = ( apply_filters( 'stm_is_motors_theme', false ) ) ? get_stylesheet_directory_uri() . '/assets/images/plchldr255.png' : STM_LISTINGS_URL . '/assets/images/plchldr255.png';
$gallery_hover_interaction = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$thumbs                    = ( $gallery_hover_interaction ) ? apply_filters( 'stm_get_hoverable_thumbs', array(), get_the_ID(), $image_size ) : array();
$img_attrs                 = array(
	'sizes'   => '(max-width: 767px) 100vw, ' . esc_attr( $grid_col_w ) . esc_attr( $thumb_width ) . 'px',
	'class'   => 'img-responsive',
	'alt'     => get_the_title(),
	'loading' => 'lazy',
);
?>

<?php if ( $gallery_hover_interaction && count( $thumbs['gallery'] ) > 1 ) : ?>
	<!-- "interactive-hoverable" -->
	<?php do_action( 'stm_listing_image_hover_gallery', $thumbs, $image_size, $img_attrs ); ?>

<?php elseif ( has_post_thumbnail( get_the_ID() ) ) : ?>

	<?php echo wp_kses_post( wp_get_attachment_image( get_post_thumbnail_id( get_the_ID() ), $image_size, false, $img_attrs ) ); ?>

<?php else : ?>

	<img src="<?php echo esc_url( $placeholder_path ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'stm_vehicles_listing' ); ?>" class="img-responsive" loading="lazy"/>

<?php endif; ?>
