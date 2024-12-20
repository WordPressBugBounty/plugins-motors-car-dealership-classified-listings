<?php
mvl_enqueue_header_scripts_styles( 'sell-a-car-form' );

$trade_in_nonce = wp_create_nonce( 'stm_trade_in_nonce' );
$action_hash    = ( isset( $args['is_modal'] ) && $args['is_modal'] ) ? '#error-fields' : '';
$uniqid         = uniqid();
$listing_title  = apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_queried_object_id() ), get_queried_object_id() );

?>
<div class="stm-sell-a-car-form stm-sell-a-car-form-<?php echo esc_attr( $uniqid ); ?>" data-form-id="<?php echo esc_attr( $uniqid ); ?>">
	<div class="form-navigation">
		<div class="row">
			<div class="col-md-4 col-sm-4">
				<a href="#step-one" class="form-navigation-unit active" data-tab="step-one">
					<div class="number heading-font">1.</div>
					<div class="title heading-font"><?php esc_html_e( 'Car Information', 'stm_vehicles_listing' ); ?></div>
					<div class="sub-title"><?php esc_html_e( 'Add your vehicle details', 'stm_vehicles_listing' ); ?></div>
				</a>
			</div>
			<div class="col-md-4 col-sm-4">
				<a href="#step-two" class="form-navigation-unit" data-tab="step-two">
					<div class="number heading-font">2.</div>
					<div class="title heading-font"><?php esc_html_e( 'Vehicle Condition', 'stm_vehicles_listing' ); ?></div>
					<div class="sub-title"><?php esc_html_e( 'Add your vehicle details', 'stm_vehicles_listing' ); ?></div>
				</a>
			</div>
			<div class="col-md-4 col-sm-4">
				<a href="#step-three" class="form-navigation-unit" data-tab="step-three">
					<div class="number heading-font">3.</div>
					<div class="title heading-font"><?php esc_html_e( 'Contact details', 'stm_vehicles_listing' ); ?></div>
					<div class="sub-title"><?php esc_html_e( 'Your contact details', 'stm_vehicles_listing' ); ?></div>
				</a>
			</div>
		</div>
	</div>
	<div class="form-content">
		<form method="POST" action="<?php echo esc_attr( $action_hash ); ?>" id="trade-in-default" enctype="multipart/form-data">
			<!-- STEP ONE -->
			<div class="form-content-unit active" id="step-one">
				<input type="hidden" name="trade_in_wpnonce" value="<?php echo esc_attr( $trade_in_nonce ); ?>"/>
				<input type="hidden" name="sell_a_car" value="filled"/>
				<input type="hidden" name="car" value="<?php echo esc_attr( $listing_title ); ?>"/>
				<?php
				$post_make_value = '';
				if ( ! empty( $_POST['make'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_make_value = sanitize_text_field( wp_unslash( $_POST['make'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}

				$post_model_value = '';
				if ( ! empty( $_POST['model'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_model_value = sanitize_text_field( wp_unslash( $_POST['model'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}

				$post_stm_year_value = '';
				if ( ! empty( $_POST['stm_year'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_stm_year_value = sanitize_text_field( wp_unslash( $_POST['stm_year'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}

				$post_transmission_value = '';
				if ( ! empty( $_POST['transmission'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_transmission_value = sanitize_text_field( wp_unslash( $_POST['transmission'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}

				$post_mileage_value = '';
				if ( ! empty( $_POST['mileage'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_mileage_value = sanitize_text_field( wp_unslash( $_POST['mileage'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}

				$post_vin_value = '';
				if ( ! empty( $_POST['vin'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					$post_vin_value = sanitize_text_field( wp_unslash( $_POST['vin'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				}
				?>
				<div class="row">
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'Make', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_make_value ); ?>" name="make" data-need="true" required/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'Model', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_model_value ); ?>" name="model" data-need="true" required/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'Year', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_stm_year_value ); ?>" name="stm_year"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'Transmission', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_transmission_value ); ?>" name="transmission"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label">
								<?php esc_html_e( 'Mileage', 'stm_vehicles_listing' ); ?>
							</div>
							<input type="text" value="<?php echo esc_attr( $post_mileage_value ); ?>" name="mileage"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'VIN', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_vin_value ); ?>" name="vin"/>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 col-sm-12">
						<?php
						$post_video_url_value = '';
						if ( ! empty( $_POST['video_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_video_url_value = sanitize_text_field( $_POST['video_url'] ); // phpcs:ignore WordPress.Security
						}

						$post_exterior_color_value = '';
						if ( ! empty( $_POST['exterior_color'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_exterior_color_value = sanitize_text_field( wp_unslash( $_POST['exterior_color'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}

						$post_interior_color_value = '';
						if ( ! empty( $_POST['interior_color'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_interior_color_value = sanitize_text_field( wp_unslash( $_POST['interior_color'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}

						$post_owner_value = '';
						if ( ! empty( $_POST['owner'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
							$post_owner_value = sanitize_text_field( wp_unslash( $_POST['owner'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
						}
						?>
						<div class="form-upload-files">
							<div class="clearfix">
								<div class="stm-unit-photos">
									<h5 class="stm-label-type-2"><?php esc_html_e( 'Upload your car Photos', 'stm_vehicles_listing' ); ?></h5>
									<div class="upload-photos">
										<div class="stm-pseudo-file-input" data-placeholder="<?php esc_html_e( 'Choose file...', 'stm_vehicles_listing' ); ?>">
											<div class="stm-filename"><?php esc_html_e( 'Choose file...', 'stm_vehicles_listing' ); ?></div>
											<div class="stm-plus"></div>
											<input class="stm-file-realfield" type="file" name="gallery_images_0"/>
										</div>
									</div>
								</div>
								<div class="stm-unit-url">
									<h5 class="stm-label-type-2">
										<?php esc_html_e( 'Provide a hosted video url of your car', 'stm_vehicles_listing' ); ?>
									</h5>
									<input type="text" value="<?php echo esc_attr( $post_video_url_value ); ?>" name="video_url"/>
								</div>
							</div>
						</div>
						<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/radio.png' ); ?>" style="opacity:0; width:0; height:0;" alt="" />

					</div>
				</div>

				<div class="row">
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'Exterior color', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_exterior_color_value ); ?>" name="exterior_color"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'Interior color', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_interior_color_value ); ?>" name="interior_color"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<div class="contact-us-label"><?php esc_html_e( 'Owner', 'stm_vehicles_listing' ); ?></div>
							<input type="text" value="<?php echo esc_attr( $post_owner_value ); ?>" name="owner"/>
						</div>
					</div>
				</div>

				<a href="#" class="button sell-a-car-proceed" data-step="2">
					<?php esc_html_e( 'Save and continue', 'stm_vehicles_listing' ); ?>
				</a>
			</div>

			<!-- STEP TWO -->
			<div class="form-content-unit" id="step-two">
				<div class="vehicle-condition">
					<div class="vehicle-condition-unit">
						<div class="icon"><i class="motors-icons-car-relic"></i></div>
						<div class="title h5"><?php esc_html_e( 'What is the Exterior Condition?', 'stm_vehicles_listing' ); ?></div>
						<label>
							<input type="radio" name="exterior_condition" value="<?php esc_attr_e( 'Extra clean', 'stm_vehicles_listing' ); ?>" checked/>
							<?php esc_html_e( 'Extra clean', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="exterior_condition" value="<?php esc_attr_e( 'Clean', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'Clean', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="exterior_condition" value="<?php esc_attr_e( 'Average', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'Average', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="exterior_condition" value="<?php esc_attr_e( 'Below Average', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'Below Average', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="exterior_condition" value="<?php esc_attr_e( 'I don\'t know', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'I don\'t know', 'stm_vehicles_listing' ); ?>
						</label>
					</div>
					<div class="vehicle-condition-unit">
						<div class="icon buoy"><i class="motors-icons-buoy"></i></div>
						<div class="title h5"><?php esc_html_e( 'What is the Interior Condition?', 'stm_vehicles_listing' ); ?></div>
						<label>
							<input type="radio" name="interior_condition" value="<?php esc_attr_e( 'Extra clean', 'stm_vehicles_listing' ); ?>" checked/>
							<?php esc_html_e( 'Extra clean', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="interior_condition" value="<?php esc_attr_e( 'Clean', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'Clean', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="interior_condition" value="<?php esc_attr_e( 'Average', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'Average', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="interior_condition" value="<?php esc_attr_e( 'Below Average', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'Below Average', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="interior_condition" value="<?php esc_attr_e( 'I don\'t know', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'I don\'t know', 'stm_vehicles_listing' ); ?>
						</label>
					</div>
					<div class="vehicle-condition-unit">
						<div class="icon buoy-2"><i class="motors-icons-buoy-2"></i></div>
						<div class="title h5"><?php esc_html_e( 'Has vehicle been in accident', 'stm_vehicles_listing' ); ?></div>
						<label>
							<input type="radio" name="accident" value="<?php esc_attr_e( 'Yes', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'Yes', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="accident" value="<?php esc_attr_e( 'No', 'stm_vehicles_listing' ); ?>" checked/>
							<?php esc_html_e( 'No', 'stm_vehicles_listing' ); ?>
						</label>
						<label>
							<input type="radio" name="accident" value="<?php esc_attr_e( 'I don\'t know', 'stm_vehicles_listing' ); ?>"/>
							<?php esc_html_e( 'I don\'t know', 'stm_vehicles_listing' ); ?>
						</label>
					</div>
				</div>
				<a href="#" class="button sell-a-car-proceed" data-step="3">
					<?php esc_html_e( 'Save and continue', 'stm_vehicles_listing' ); ?>
				</a>
			</div>

			<!-- STEP THREE -->
			<div class="form-content-unit" id="step-three">
				<div class="contact-details">
					<?php
					$post_first_name_value = '';
					if ( ! empty( $_POST['first_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_first_name_value = sanitize_text_field( wp_unslash( $_POST['first_name'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_last_name_value = '';
					if ( ! empty( $_POST['last_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_last_name_value = sanitize_text_field( wp_unslash( $_POST['last_name'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_email_value = '';
					if ( ! empty( $_POST['email'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_email_value = sanitize_text_field( wp_unslash( $_POST['email'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_phone_value = '';
					if ( ! empty( $_POST['phone'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_phone_value = sanitize_text_field( wp_unslash( $_POST['phone'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}

					$post_comments_value = '';
					if ( ! empty( $_POST['comments'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
						$post_comments_value = sanitize_text_field( wp_unslash( $_POST['comments'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
					}
					?>
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'First name', 'stm_vehicles_listing' ); ?>
									*
								</div>
								<input type="text" value="<?php echo esc_attr( $post_first_name_value ); ?>" name="first_name"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Last name', 'stm_vehicles_listing' ); ?>
									*
								</div>
								<input type="text" value="<?php echo esc_attr( $post_last_name_value ); ?>" name="last_name"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Email Address', 'stm_vehicles_listing' ); ?>
									*
								</div>
								<input type="email" value="<?php echo esc_attr( $post_email_value ); ?>" name="email"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Phone number', 'stm_vehicles_listing' ); ?>
									*
								</div>
								<input type="tel" value="<?php echo esc_attr( $post_phone_value ); ?>" name="phone"/>
							</div>
						</div>
						<div class="col-md-12 col-sm-12">
							<div class="form-group">
								<div class="contact-us-label"><?php esc_html_e( 'Comments', 'stm_vehicles_listing' ); ?></div>
								<textarea name="comments"><?php echo esc_attr( $post_comments_value ); ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix">
					<div class="pull-left">
						<?php
						if ( ! empty( $recaptcha_enabled ) && ! empty( $recaptcha_public_key ) && ! empty( $recaptcha_secret_key ) ) {
							wp_enqueue_script( 'stm_grecaptcha' );
							?>
							<script>
								function onSubmit(token) {
									jQuery("form#trade-in-default").trigger('submit');
								}
							</script>
						<input class="g-recaptcha" data-sitekey="<?php echo esc_attr( $recaptcha_public_key ); ?>" data-callback='onSubmit' type="submit" value="<?php esc_html_e( 'Save and finish', 'stm_vehicles_listing' ); ?>"/>
						<?php } else { ?>
						<input type="submit" value="<?php esc_attr_e( 'Save and finish', 'stm_vehicles_listing' ); ?>"/>
						<?php } ?>
					</div>
					<div class="disclaimer">
						<?php
						esc_html_e(
							'By submitting this form, you will be requesting trade-in value at no obligation and will be contacted within 48 hours by a sales representative.',
							'stm_vehicles_listing'
						);
						?>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="stm-sell-a-car-loader">
	<div class="stm-loader">
		<div class="stm-loader-inner">
			<div class="stm-loader circle">
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
	</div>
</div>

<div class="wpcf7-response-output wpcf7-mail-sent-ok hidden" id="error-fields">
	<?php esc_html_e( 'Mail successfully sent', 'stm_vehicles_listing' ); ?>
</div>

<div class="wpcf7-response-output wpcf7-validation-errors hidden" id="error-fields">
</div>
