<?php
	/**
	 * @var $id
	 * @var $items
	 * */

	defined( 'ABSPATH' ) || exit;

	$user_features        = apply_filters( 'motors_vl_get_nuxy_mod', array(), 'fs_user_features' );
	$is_required_features = apply_filters( 'motors_vl_get_nuxy_mod', false, 'addl_required_featured' );

if ( ! empty( $user_features ) ) :
	?>
<div class="stm-form-2-features clearfix">
	<?php
	$vars = array(
		'step_title'    => __( 'Select Your Car Features', 'stm_vehicles_listing' ) . ( $is_required_features ? '*' : '' ),
		'step_number'   => 2,
		'user_features' => $user_features,
	);
	do_action( 'stm_listings_load_template', 'add_car/step-title', $vars );

	do_action( 'stm_listings_load_template', 'add_car/step_2_items', $vars );
	?>
	<input type="hidden" data-features-required="<?php echo esc_attr( $is_required_features ) ? 'true' : 'false'; ?>">
</div>
	<?php
endif;
