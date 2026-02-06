<?php
	/**
	 * @var $step_title
	 * @var $step_number
	 * */

$show_car_info_auto_complete = apply_filters( 'motors_vl_get_nuxy_mod', false, 'allow_car_info_auto_complete' );
?>
<div class="stm-car-listing-data-single stm-border-top-unit">
	<div class="title heading-font"><?php echo esc_html( $step_title ); ?></div>
	<?php if ( $show_car_info_auto_complete && apply_filters( 'is_mvl_pro', false ) ) : ?>
		<?php do_action( 'stm_listings_load_template', 'addons/CarInfoAutoComplite/car-autocomplete-button', array() ); ?>
	<?php endif; ?>
	<?php if ( defined( 'WPB_VC_VERSION' ) ) : ?>
		<span class="step_number heading-font <?php /* translators: %d step number */ printf( 'step_number_%d', esc_attr( $step_number ) ); ?>">
			<?php
			/* translators: %d step number */
			printf( esc_html__( 'step %d', 'stm_vehicles_listing' ), esc_html( $step_number ) );
			?>
		</span>
	<?php endif; ?>
</div>
