<?php
$sold_car         = get_post_meta( $post->ID, 'car_mark_as_sold', true );
$sell_car_online  = get_post_meta( $post->ID, 'car_mark_woo_online', true );
$car_stock_number = get_post_meta( $post->ID, 'stm_car_stock', true );
?>
<?php if ( apply_filters( 'is_mvl_pro', false ) || apply_filters( 'stm_is_motors_theme', false ) ) : ?>

<div class="mvl-checkbox-metabox">
	<label for="car_mark_as_sold" class="car-mark-as-sold">
		<input type="checkbox" name="car_mark_as_sold" id="car_mark_as_sold" value="on" <?php checked( $sold_car, 'on' ); ?> />
		<span class="checkbox-custom"></span>
		<?php esc_html_e( 'Mark as', 'stm_vehicles_listing' ); ?>
		<span class="meta-box-checkbox-label"><?php esc_html_e( 'Sold', 'stm_vehicles_listing' ); ?></span>
	</label>
</div>

<?php endif; ?>

<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_woo_online' ) ) : ?>
	<div class="mvl-checkbox-metabox sell-car-online-settings">
		<label for="car_mark_woo_online" class="sell-car-online">
			<input type="checkbox" name="car_mark_woo_online" id="car_mark_woo_online" value="on" <?php checked( $sell_car_online, 'on' ); ?> />
			<span class="checkbox-custom"></span>
			<?php esc_html_e( 'Sell Listing', 'stm_vehicles_listing' ); ?>
			<span class="meta-box-checkbox-label"><?php esc_html_e( 'Online', 'stm_vehicles_listing' ); ?></span>
		</label>
		<div class="sell-car-online-stock">
			<h4><?php esc_html_e( 'Stock', 'stm_vehicles_listing' ); ?></h4>
			<input type="text" name="stm_car_stock" value="<?php echo esc_attr( $car_stock_number ); ?>" />
		</div>
		<div class="metabox-description">
			<p>
				<span>i</span>  
				<?php esc_html_e( 'Sell listing online and manage the stock', 'stm_vehicles_listing' ); ?>
			</p>
		</div>
	</div>
<?php endif; ?>
