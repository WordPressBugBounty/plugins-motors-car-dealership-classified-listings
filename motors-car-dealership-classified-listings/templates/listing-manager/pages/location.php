<?php
// phpcs:ignoreFile
?>

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
		$listing_id                   = $listing_manager_page->get_listing_id();
		$is_rental_locations_enabled = (bool) apply_filters( 'mvl_listing_manager_use_rental_locations', false, $listing_id );
		$rental_locations            = $is_rental_locations_enabled ? apply_filters( 'mvl_listing_manager_rental_location_options', array(), $listing_id ) : array();
		$current_location_id         = $listing_id ? get_post_meta( $listing_id, 'mvl_rental_location_id', true ) : '';
		$current_location_address    = $listing_id ? get_post_meta( $listing_id, 'stm_car_location', true ) : '';

		if ( $is_rental_locations_enabled ) :
			if ( empty( $rental_locations ) ) {
				$saved_rental_locations = get_option( 'mvl_rental_locations', array() );

				if ( is_array( $saved_rental_locations ) ) {
					foreach ( $saved_rental_locations as $saved_rental_location ) {
						if ( ! is_array( $saved_rental_location ) || empty( $saved_rental_location['name'] ) ) {
							continue;
						}

						$location_name    = sanitize_text_field( $saved_rental_location['name'] );
						$location_address = isset( $saved_rental_location['address'] ) ? sanitize_text_field( $saved_rental_location['address'] ) : '';
						$location_value   = $location_address ?: $location_name;

						$rental_locations[] = array(
							'id'      => isset( $saved_rental_location['id'] ) ? sanitize_key( $saved_rental_location['id'] ) : sanitize_key( $location_name ),
							'name'    => $location_name,
							'address' => $location_address,
							'value'   => $location_value,
							'label'   => $location_address ? $location_name . ' - ' . $location_address : $location_name,
						);
					}
				}
			}

			$selected_location_address = '';

			foreach ( $rental_locations as $rental_location ) {
				$rental_location_value = $rental_location['value'] ?? $rental_location['address'];
				$rental_location_label = $rental_location['label'] ?? $rental_location_value;

				if ( $current_location_id && $current_location_id === $rental_location['id'] ) {
					$selected_location_address = $rental_location_label;
					break;
				}

				if ( ! $current_location_id && $current_location_address && in_array( $current_location_address, array( $rental_location_value, $rental_location_label ), true ) ) {
					$current_location_id       = $rental_location['id'];
					$selected_location_address = $rental_location_label;
					break;
				}
			}
			?>
				<div class="mvl-listing-manager-field mvl-listing-manager-select-field mvl-listing-manager-rental-location-field" data-field-id="mvl_rental_location_id" data-label="<?php esc_attr_e( 'Pickup location', 'stm_vehicles_listing' ); ?>">
					<div class="mvl-listing-manager-field-info">
						<div class="mvl-listing-manager-field-title">
							<?php esc_html_e( 'Pickup location', 'stm_vehicles_listing' ); ?>
						</div>
						<button type="button" class="mvl-listing-manager-rental-location-add-button" data-mvl-rental-location-add-open>
							<i class="motors-icons-plus" aria-hidden="true"></i>
							<?php esc_html_e( 'Add Location', 'stm_vehicles_listing' ); ?>
						</button>
					</div>
					<div class="mvl-listing-manager-rental-location-select">
						<select
							name="<?php echo esc_attr( $listing_manager_page->get_id() . '[mvl_rental_location_id]' ); ?>"
							id="mvl_rental_location_id"
							class="mvl-listing-manager-field-select mvl-select-field"
						data-mvl-rental-location-select
					>
						<option value="" data-address=""><?php esc_html_e( 'Select pickup location', 'stm_vehicles_listing' ); ?></option>
						<?php foreach ( $rental_locations as $rental_location ) : ?>
							<option
								value="<?php echo esc_attr( $rental_location['id'] ); ?>"
								data-address="<?php echo esc_attr( $rental_location['label'] ?? $rental_location['value'] ?? $rental_location['address'] ); ?>"
								<?php selected( $current_location_id, $rental_location['id'] ); ?>
							>
								<?php echo esc_html( $rental_location['label'] ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php if ( empty( $rental_locations ) ) : ?>
					<div class="mvl-listing-manager-rental-location-empty">
						<?php esc_html_e( 'No rental pickup locations found. Add locations in Rental Locations first.', 'stm_vehicles_listing' ); ?>
					</div>
				<?php endif; ?>
			</div>
			<input type="hidden" id="stm_car_location" name="<?php echo esc_attr( $listing_manager_page->get_id() . '[stm_car_location]' ); ?>" value="<?php echo esc_attr( $selected_location_address ?: $current_location_address ); ?>">
			<input type="hidden" name="location[stm_lat_car_admin]" value="<?php echo esc_attr( $listing_id ? get_post_meta( $listing_id, 'stm_lat_car_admin', true ) : '' ); ?>">
			<input type="hidden" name="location[stm_lng_car_admin]" value="<?php echo esc_attr( $listing_id ? get_post_meta( $listing_id, 'stm_lng_car_admin', true ) : '' ); ?>">
			<div class="mvl-listing-manager-rental-location-modal" data-mvl-rental-location-add-modal hidden>
				<div class="mvl-listing-manager-rental-location-modal__backdrop" data-mvl-rental-location-add-close></div>
				<div class="mvl-listing-manager-rental-location-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="mvl-listing-manager-rental-location-modal-title">
					<div class="mvl-listing-manager-rental-location-modal__header">
						<div>
							<h3 id="mvl-listing-manager-rental-location-modal-title"><?php esc_html_e( 'Add Location', 'stm_vehicles_listing' ); ?></h3>
							<p><?php esc_html_e( 'Create a pickup or return office without leaving Listing Manager.', 'stm_vehicles_listing' ); ?></p>
						</div>
						<button type="button" class="mvl-listing-manager-rental-location-modal__close" data-mvl-rental-location-add-close aria-label="<?php esc_attr_e( 'Close', 'stm_vehicles_listing' ); ?>">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="mvl-listing-manager-rental-location-modal__body">
						<label>
							<span><?php esc_html_e( 'Location name', 'stm_vehicles_listing' ); ?></span>
							<input type="text" data-mvl-rental-location-add-field="name" placeholder="<?php esc_attr_e( 'Downtown HQ', 'stm_vehicles_listing' ); ?>">
						</label>
						<label>
							<span><?php esc_html_e( 'Address', 'stm_vehicles_listing' ); ?></span>
							<input type="text" data-mvl-rental-location-add-field="address" placeholder="<?php esc_attr_e( 'Street, city, state', 'stm_vehicles_listing' ); ?>">
						</label>
						<div class="mvl-listing-manager-rental-location-modal__row">
							<label>
								<span><?php esc_html_e( 'Phone', 'stm_vehicles_listing' ); ?></span>
								<input type="text" data-mvl-rental-location-add-field="phone" placeholder="<?php esc_attr_e( '(000) 000-0000', 'stm_vehicles_listing' ); ?>">
							</label>
							<label>
								<span><?php esc_html_e( 'Email', 'stm_vehicles_listing' ); ?></span>
								<input type="email" data-mvl-rental-location-add-field="email" placeholder="<?php esc_attr_e( 'office@example.com', 'stm_vehicles_listing' ); ?>">
							</label>
						</div>
						<label>
							<span><?php esc_html_e( 'Location type', 'stm_vehicles_listing' ); ?></span>
							<select data-mvl-rental-location-add-field="type">
								<option value="both"><?php esc_html_e( 'Pickup & Return', 'stm_vehicles_listing' ); ?></option>
								<option value="pickup"><?php esc_html_e( 'Pickup only', 'stm_vehicles_listing' ); ?></option>
								<option value="return"><?php esc_html_e( 'Return only', 'stm_vehicles_listing' ); ?></option>
							</select>
						</label>
						<div class="mvl-listing-manager-rental-location-modal__hours">
							<div class="mvl-listing-manager-rental-location-modal__hours-title">
								<?php esc_html_e( 'Opening hours', 'stm_vehicles_listing' ); ?>
							</div>
							<?php
							$rental_location_days = array(
								'mon' => array( 'label' => __( 'Monday', 'stm_vehicles_listing' ), 'open' => true, 'from' => '08:00', 'to' => '20:00' ),
								'tue' => array( 'label' => __( 'Tuesday', 'stm_vehicles_listing' ), 'open' => true, 'from' => '08:00', 'to' => '20:00' ),
								'wed' => array( 'label' => __( 'Wednesday', 'stm_vehicles_listing' ), 'open' => true, 'from' => '08:00', 'to' => '20:00' ),
								'thu' => array( 'label' => __( 'Thursday', 'stm_vehicles_listing' ), 'open' => true, 'from' => '08:00', 'to' => '20:00' ),
								'fri' => array( 'label' => __( 'Friday', 'stm_vehicles_listing' ), 'open' => true, 'from' => '08:00', 'to' => '20:00' ),
								'sat' => array( 'label' => __( 'Saturday', 'stm_vehicles_listing' ), 'open' => true, 'from' => '09:00', 'to' => '18:00' ),
								'sun' => array( 'label' => __( 'Sunday', 'stm_vehicles_listing' ), 'open' => false, 'from' => '09:00', 'to' => '18:00' ),
							);
							?>
							<?php foreach ( $rental_location_days as $day_key => $day ) : ?>
								<div class="mvl-listing-manager-rental-location-modal__hours-row" data-mvl-rental-location-add-hours-day="<?php echo esc_attr( $day_key ); ?>">
									<label class="mvl-listing-manager-rental-location-modal__hours-toggle">
										<input type="checkbox" data-mvl-rental-location-add-hours-open <?php checked( $day['open'] ); ?>>
										<span><?php echo esc_html( $day['label'] ); ?></span>
									</label>
									<div class="mvl-listing-manager-rental-location-modal__hours-time">
										<input type="time" value="<?php echo esc_attr( $day['from'] ); ?>" data-mvl-rental-location-add-hours-from <?php disabled( ! $day['open'] ); ?>>
										<input type="time" value="<?php echo esc_attr( $day['to'] ); ?>" data-mvl-rental-location-add-hours-to <?php disabled( ! $day['open'] ); ?>>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="mvl-listing-manager-rental-location-modal__error" data-mvl-rental-location-add-error hidden></div>
					</div>
					<div class="mvl-listing-manager-rental-location-modal__footer">
						<button type="button" class="mvl-secondary-btn" data-mvl-rental-location-add-close><?php esc_html_e( 'Cancel', 'stm_vehicles_listing' ); ?></button>
						<button type="button" class="mvl-primary-btn" data-mvl-rental-location-add-submit><?php esc_html_e( 'Add Location', 'stm_vehicles_listing' ); ?></button>
					</div>
				</div>
			</div>
		<?php else : ?>
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/text',
				array(
					'id'          => 'stm_car_location',
					'label'       => __( 'Address', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter address', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[stm_car_location]',
					'value'       => $current_location_address,
				)
			);
			?>
			<input type="hidden" name="location[stm_lat_car_admin]" value="<?php echo esc_attr( $listing_id ? get_post_meta( $listing_id, 'stm_lat_car_admin', true ) : '' ); ?>">
			<input type="hidden" name="location[stm_lng_car_admin]" value="<?php echo esc_attr( $listing_id ? get_post_meta( $listing_id, 'stm_lng_car_admin', true ) : '' ); ?>">
		<?php endif; ?>
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
