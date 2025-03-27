<?php
$special_car    = get_post_meta( $post->ID, 'special_car', true );
$badge_color    = get_post_meta( $post->ID, 'badge_bg_color', true );
$badge_text     = get_post_meta( $post->ID, 'badge_text', true );
$special_text   = get_post_meta( $post->ID, 'special_text', true );
$special_img_id = get_post_meta( $post->ID, 'special_image', true );

if ( ! empty( $special_img_id ) ) {
	$special_img = wp_get_attachment_url( $special_img_id );
}

?>

<div class="mvl-checkbox-metabox">
	<label for="special_car" class="special-car">
		<input type="checkbox" name="special_car" id="special_car" value="on" <?php checked( $special_car, 'on' ); ?> />
		<span class="checkbox-custom"></span>
		<?php esc_html_e( 'Mark as', 'stm_vehicles_listing' ); ?>
		<span class="meta-box-checkbox-label"><?php esc_html_e( 'Featured', 'stm_vehicles_listing' ); ?></span>
	</label>
	<div class="special-badge-settings">
		<h4><?php esc_html_e( 'Badge Text', 'stm_vehicles_listing' ); ?></h4>
		<input type="text" name="badge_text" value="<?php echo esc_attr( $badge_text ); ?>" />
		<h4><?php esc_html_e( 'Badge Color', 'stm_vehicles_listing' ); ?></h4>
		<input type="text" name="badge_bg_color" id="badge_bg_color" class="color-field" value="<?php echo esc_attr( $badge_color ); ?>" />
		<?php if ( apply_filters( 'is_mvl_pro', false ) ) : ?>
			<div class="special-offer-wrapper">
				<hr>
				<h4><?php esc_html_e( 'Special Offer Text', 'stm_vehicles_listing' ); ?></h4>
				<input type="text" name="special_text" value="<?php echo esc_attr( $special_text ); ?>" />
				<div class="metabox-description">
					<p>
						<span>i</span>
						<?php esc_html_e( 'Enter the promo message for your featured listing. It will be displayed in the Listing Carousel widget.', 'stm_vehicles_listing' ); ?>
					</p>
				</div>
				<h4><?php esc_html_e( 'Special Offer Banner', 'stm_vehicles_listing' ); ?></h4>
				<div class="special-image-wrapper">
					<img id="special_image_preview" src="<?php echo esc_url( $special_img ); ?>" style="max-width:100%; <?php echo empty( $special_img ) ? 'display:none;' : ''; ?>" />
					<input type="hidden" name="special_image" id="special_image" value="<?php echo esc_attr( $special_img_id ); ?>" />
					<div class="special-image-wrapper-actions">
						<button type="button" class="button" id="special_image_upload"><?php esc_html_e( 'Add Image', 'stm_vehicles_listing' ); ?></button>
						<button type="button" class="button" id="special_image_remove" style="<?php echo empty( $special_img ) ? 'display:none;' : ''; ?>"><?php esc_html_e( 'Remove Image', 'stm_vehicles_listing' ); ?></button>
					</div>
				</div>
				<div class="metabox-description">
					<p>
						<span>i</span>
						<?php esc_html_e( 'Upload an image to display on the featured listing, which will be shown in the Listing Carousel widget.', 'stm_vehicles_listing' ); ?>
					</p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
