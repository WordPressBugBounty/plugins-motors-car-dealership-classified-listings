<div class="mvl-listing-manager-content-body-page-header">
	<div class="mvl-listing-manager-content-body-page-title-wrapper">
		<div class="mvl-listing-manager-content-body-page-title">
			<?php echo esc_html( $listing_manager_page->get_title() ); ?>
		</div>
		<?php if ( $listing_manager_page->has_preview() ) : ?>
		<div class="mvl-listing-manager-content-body-page-preview-wrapper" mvl-tooltip-image="<?php echo esc_url( $listing_manager_page->get_preview_url() ); ?>" mvl-tooltip-position="bottom" mvl-tooltip-toggle="mvl-listing-manager-content-body-page-preview-img">
			<div class="mvl-listing-manager-content-body-page-preview">
				<i class="motors-icons-mvl-eye"></i>
				<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="mvl-listing-manager-content-body-page-text">
	<div class="mvl-listing-manager-content-body-page-fields-row">
		<?php
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/text',
			array(
				'id'          => 'stm_car_location',
				'label'       => __( 'Address', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter address', 'stm_vehicles_listing' ),
				'input_name'  => $listing_manager_page->get_id() . '[stm_car_location]',
				'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'stm_car_location', true ) : '',
				'label'       => __( 'Address', 'stm_vehicles_listing' ),
			)
		);

		?>
		<input type="hidden" name="location[stm_lat_car_admin]" value="<?php echo esc_attr( $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'stm_lat_car_admin', true ) : '' ); ?>">
		<input type="hidden" name="location[stm_lng_car_admin]" value="<?php echo esc_attr( $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'stm_lng_car_admin', true ) : '' ); ?>">
		<?php if ( apply_filters( 'is_mvl_pro', false ) && apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_api_key' ) ) : ?>
		<div class="mvl-listing-manager-field">
			<div id="mvl-listing-manager-map" class="mvl-listing-manager-map"></div>
			<div class="mvl-listing-manager-map-zoom">
				<div class="mvl-listing-manager-map-zoom-in">+</div>
				<div class="mvl-listing-manager-map-zoom-out">-</div>
			</div>
		</div>
		<?php endif; ?>
		<?php
		if ( apply_filters( 'mvl_listing_manager_is_admin', false ) ) :
			if ( ! apply_filters( 'is_mvl_pro', false ) ) :
				?>
				<div class="mvl-lm-notice upgrade-to-pro">
					<div class="mvl-lm-notice-message">
						<p class="mvl-lm-notice-message-title">
							<?php echo esc_html__( 'Upgrade to ', 'stm_vehicles_listing' ); ?> <strong><?php echo esc_html__( 'MOTORS', 'stm_vehicles_listing' ); ?></strong>
							<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/pro/mvl_pro_badge.svg' ); ?>" alt="<?php echo esc_attr__( 'MOTORS', 'stm_vehicles_listing' ); ?>">
							<?php echo esc_html__( 'to enable Google Maps functionality.', 'stm_vehicles_listing' ); ?>
						</p>
						<p class="mvl-lm-notice-message-description">
							<?php echo esc_html__( 'Display vehicle locations clearly with Google Maps.', 'stm_vehicles_listing' ); ?>
						</p>
					</div>
					<div class="mvl-lm-notice-actions">
						<a href="<?php echo esc_url( 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wp-admin&utm_medium=push&utm_campaign=motors&utm_content=gopro' ); ?>" class="mvl-primary-btn">
							<?php echo esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ); ?>
						</a>
					</div>
				</div>
			<?php elseif ( ! apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_api_key' ) ) : ?>
				<div class="mvl-lm-notice set-google-api-key">
					<div class="mvl-lm-notice-message">
						<p class="mvl-lm-notice-message-title">
							<?php echo esc_html__( 'Please set Google Maps API key in the', 'stm_vehicles_listing' ); ?> <strong><?php echo esc_html__( 'Motors Plugin', 'stm_vehicles_listing' ); ?></strong> <?php echo esc_html__( 'settings', 'stm_vehicles_listing' ); ?>
						</p>
						<p class="mvl-lm-notice-message-description">
							<?php echo esc_html__( 'This feature requires a Google Maps API key to work properly.', 'stm_vehicles_listing' ); ?>
						</p>
					</div>
					<div class="mvl-lm-notice-actions">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=mvl_plugin_settings#google_services_tab' ) ); ?>" class="mvl-primary-btn">
							<?php echo esc_html__( 'Set API Key', 'stm_vehicles_listing' ); ?>
						</a>
					</div>
				</div>
				<?php
			endif;
		endif;
		?>
	</div>
</div>
