<?php
$_id               = apply_filters( 'stm_listings_input', null, 'item_id' );
$is_video_required = apply_filters( 'motors_vl_get_nuxy_mod', false, 'addl_video_required' );

if ( $custom_listing_type && $listing_types_options && isset( $listing_types_options[ $custom_listing_type . '_addl_video_content' ] ) ) {
	$content = $listing_types_options[ $custom_listing_type . '_addl_video_content' ];
} else {
	$content = apply_filters( 'motors_vl_get_nuxy_mod', '', 'addl_video_content' );
}

if ( empty( $_id ) ) :
	?>
	<div class="stm-form-4-videos clearfix">
		<div class="stm-car-listing-data-single stm-border-top-unit ">
			<div class="title heading-font">
				<?php esc_html_e( 'Add Videos', 'stm_vehicles_listing' ); ?>
				<?php echo $is_video_required ? esc_html( '*' ) : ''; ?>
			</div>
		</div>
		<div class="stm-add-videos-unit">
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="stm-video-units">
						<div class="stm-video-link-unit-wrap">
							<div class="heading-font">
								<span class="video-label"><?php esc_html_e( 'Video link', 'stm_vehicles_listing' ); ?></span> <span
										class="count">1</span></div>
							<div class="stm-video-link-unit">
								<input type="text" name="stm_video[]"/>
								<div class="stm-after-video"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12">
					<div class="stm-simple-notice">
						<i class="fas fa-info-circle"></i>
						<?php echo wp_kses_post( $content ); ?>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" data-video-field="<?php echo esc_attr( $is_video_required ) ? 'true' : 'false'; ?>">
	</div>

<?php else : ?>
	<?php $video = get_post_meta( $_id, 'gallery_video', true ); ?>

	<div class="stm-form-4-videos clearfix">
		<div class="stm-car-listing-data-single stm-border-top-unit ">
			<div class="title heading-font"><?php esc_html_e( 'Add Videos', 'stm_vehicles_listing' ); ?></div>
			<span class="step_number step_number_4 heading-font"><?php esc_html_e( 'step', 'stm_vehicles_listing' ); ?> 4</span>
		</div>
		<?php $has_videos = false; ?>
		<div class="stm-add-videos-unit">
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="stm-video-units">
						<div class="stm-video-link-unit-wrap">
							<div class="heading-font">
								<span class="video-label"><?php esc_html_e( 'Video link', 'stm_vehicles_listing' ); ?></span> <span
										class="count">1</span>
							</div>
							<?php
							$video = get_post_meta( $_id, 'gallery_video', true );
							if ( empty( $video ) ) {
								$video = '';
							} else {
								$has_videos = true;
							}
							?>
							<div class="stm-video-link-unit">
								<input type="text" name="stm_video[]" value="<?php echo esc_url( $video ); ?>"/>
								<div class="stm-after-video active"></div>
							</div>
							<?php
							if ( $has_videos ) :
								$gallery_videos = get_post_meta( $_id, 'gallery_videos', true );
								if ( ! empty( $gallery_videos ) ) :
									foreach ( $gallery_videos as $gallery_video ) :
										?>
										<div class="stm-video-link-unit">
											<input type="text" name="stm_video[]" value="<?php echo esc_url( $gallery_video ); ?>"/>
											<div class="stm-after-video active"></div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12">
					<div class="stm-simple-notice">
						<i class="fas fa-info-circle"></i>
						<?php echo wp_kses_post( $content ); ?>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" data-video-field="<?php echo esc_attr( $is_video_required ) ? 'true' : 'false'; ?>">
	</div>
<?php endif; ?>
