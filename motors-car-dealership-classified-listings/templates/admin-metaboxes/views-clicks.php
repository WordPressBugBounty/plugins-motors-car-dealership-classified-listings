<?php
$car_views   = get_post_meta( $post->ID, 'stm_car_views', true );
$phone_views = get_post_meta( $post->ID, 'stm_phone_reveals', true );
$car_views   = ( ! empty( $car_views ) ) ? $car_views : 0;
$phone_views = ( ! empty( $phone_views ) ) ? $phone_views : 0;
?>

<div class="stm-views-metabox">
	<div class="stm-views-metabox__item">
		<label for="stm_car_views"><?php esc_html_e( 'Total Views:', 'stm_vehicles_listing' ); ?></label>
		<span class="stm_car_views"><?php echo esc_attr( $car_views ); ?></span>
		<input type="hidden" value="<?php echo esc_attr( $car_views ); ?>" name="stm_car_views" id="stm_car_views">
		<a href="#" type="button" class="reset-counter" data-target="stm_car_views"><?php esc_html_e( 'Reset counter', 'stm_vehicles_listing' ); ?></a>
	</div>
	<div class="stm-views-metabox__item">
		<label for="stm_phone_views"><?php esc_html_e( 'Phone Views:', 'stm_vehicles_listing' ); ?></label>
		<span class="stm_phone_views"><?php echo esc_attr( $phone_views ); ?></span>
		<input type="hidden" value="<?php echo esc_attr( $phone_views ); ?>" name="stm_phone_reveals" id="stm_phone_views">
		<a href="#" type="button" class="reset-counter" data-target="stm_phone_views"><?php esc_html_e( 'Reset counter', 'stm_vehicles_listing' ); ?></a>
	</div>
	<div class="metabox-description">
		<p>
			<span>i</span>
			<?php esc_html_e( 'Info visible only to listing author on profile page', 'stm_vehicles_listing' ); ?>
		</p>
	</div>
</div>

