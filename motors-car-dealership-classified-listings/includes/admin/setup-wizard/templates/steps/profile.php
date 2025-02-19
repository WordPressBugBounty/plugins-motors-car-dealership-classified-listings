<?php
$settings         = apply_filters( 'mvl_setup_wizard_data', array() );
$settings_temp    = get_option( 'mvl_setup_wizard_settings_temp', array() );
$profile_settings = array(
	'new_user_registration'     => ( ! empty( $settings_temp['new_user_registration'] ) ) ? $settings_temp['new_user_registration'] : apply_filters( 'motors_vl_get_nuxy_mod', false, 'new_user_registration', true ),
	'enable_email_confirmation' => ( ! empty( $settings_temp['enable_email_confirmation'] ) ) ? $settings_temp['enable_email_confirmation'] : apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_email_confirmation', true ),
	'free_listing_submission'   => ( ! empty( $settings_temp['free_listing_submission'] ) ) ? $settings_temp['free_listing_submission'] : apply_filters( 'motors_vl_get_nuxy_mod', true, 'free_listing_submission', true ),
	'user_post_limit'           => ( ! empty( $settings_temp['user_post_limit'] ) ) ? $settings_temp['user_post_limit'] : apply_filters( 'motors_vl_get_nuxy_mod', 10, 'user_post_limit', true ),
	'user_post_images_limit'    => ( ! empty( $settings_temp['user_post_images_limit'] ) ) ? $settings_temp['user_post_images_limit'] : apply_filters( 'motors_vl_get_nuxy_mod', 12, 'user_post_images_limit', true ),
	'user_premoderation'        => ( ! empty( $settings_temp['user_premoderation'] ) ) ? $settings_temp['user_premoderation'] : apply_filters( 'motors_vl_get_nuxy_mod', false, 'user_premoderation', true ),
);
?>
	<div class="mvl-welcome-content-body">
		<h2><?php echo esc_html__( '3. Profile settings', 'stm_vehicles_listing' ); ?></h2>
		<p><?php echo esc_html__( 'Set up new user registrations, email confirmations, free submissions, listing and image limits, and premoderation.', 'stm_vehicles_listing' ); ?></p>
		<p><em><?php echo esc_html__( 'You can change these settings anytime after setup.', 'stm_vehicles_listing' ); ?></em></p>

		<form class="mvl-settings-form" id="mvl-settings-form">

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3><?php echo esc_html__( 'New user registration', 'stm_vehicles_listing' ); ?></h3>
					</div>
					<p><?php echo esc_html__( 'The setting allows new users to sign up', 'stm_vehicles_listing' ); ?></p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="new_user_registration" <?php do_action( 'mvl_check_if', $profile_settings['new_user_registration'] ); ?> />
							<span class=""><i></i></span>
						</label>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3><?php echo esc_html__( 'Email confirmation', 'stm_vehicles_listing' ); ?></h3>
					</div>
					<p><?php echo esc_html__( 'This option lets all new registered users get an email confirmation to verify their account', 'stm_vehicles_listing' ); ?></p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="enable_email_confirmation" <?php do_action( 'mvl_check_if', $profile_settings['enable_email_confirmation'] ); ?> />
							<span class=""><i></i></span>
						</label>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3><?php echo esc_html__( 'Free listing submission', 'stm_vehicles_listing' ); ?></h3>
					</div>
					<p><?php echo esc_html__( 'It enables users to post listings for free', 'stm_vehicles_listing' ); ?></p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="free_listing_submission" <?php do_action( 'mvl_check_if', $profile_settings['free_listing_submission'] ); ?> />
							<span class=""><i></i></span>
						</label>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3><?php echo esc_html__( 'Listing publication limit', 'stm_vehicles_listing' ); ?></h3>
					</div>
					<p><?php echo esc_html__( 'This setting allows you to set the maximum number of images that can be uploaded for each listing', 'stm_vehicles_listing' ); ?></p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-row">
						<div class="mvl-settings-form-field">
							<div class="form-input">
								<input type="text" name="user_post_limit" value="<?php echo esc_attr( $profile_settings['user_post_limit'] ); ?>" />
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3><?php echo esc_html__( 'Image limit per listing', 'stm_vehicles_listing' ); ?></h3>
					</div>
					<p><?php echo esc_html__( 'Specify the maximum number of images that can be uploaded for each free listing', 'stm_vehicles_listing' ); ?></p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-row">
						<div class="mvl-settings-form-field">
							<div class="form-input">
								<input type="text" name="user_post_images_limit" value="<?php echo esc_attr( $profile_settings['user_post_images_limit'] ); ?>" />
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3><?php echo esc_html__( 'Listing premoderation', 'stm_vehicles_listing' ); ?></h3>
					</div>
					<p><?php echo esc_html__( 'The listing will need an admin approvement before publication', 'stm_vehicles_listing' ); ?></p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="user_premoderation" <?php do_action( 'mvl_check_if', $profile_settings['user_premoderation'] ); ?> />
							<span class=""><i></i></span>
						</label>
					</div>

				</div>

			</div>

		</form>
	</div>
	<div class="mvl-welcome-nav-actions">
		<div>
			<?php
			$prev_step_slug = 'single-listing';
			if ( ! defined( 'MOTORS_STARTER_THEME_VERSION' ) && ! defined( 'ELEMENTOR_VERSION' ) ) {
				$prev_step_slug = 'search-results';
			}
			?>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', $prev_step_slug ) ); ?>" class="button" id="mvl-prev-step-link" data-step="<?php echo esc_attr( $prev_step_slug ); ?>">
				<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
			</a>
		</div>
		<div>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'theme' ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="theme">
				<?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?>
			</a>
		</div>
	</div>

<?php
do_action( 'mvl_setup_wizard_data_fields', $settings );
