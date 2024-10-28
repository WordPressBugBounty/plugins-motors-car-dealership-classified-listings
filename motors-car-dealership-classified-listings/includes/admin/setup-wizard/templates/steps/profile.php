<?php
$settings = apply_filters( 'mvl_setup_wizard_data', array() );
?>
	<div class="mvl-welcome-content-body">
		<h2>
			<?php if ( ! defined( 'MOTORS_STARTER_THEME_VERSION' ) && ! defined( 'ELEMENTOR_VERSION' ) ) : ?>
				5.
			<?php else : ?>
				6.
			<?php endif; ?>
			Profile settings
		</h2>
		<p>Set up new user registrations, email confirmations, free submissions, listing and image limits, and premoderation.</p>
		<p><em>You can change these settings anytime after setup.</em></p>

		<form class="mvl-settings-form" id="mvl-settings-form">

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3>New user registration</h3>
					</div>
					<p>The setting allows new users to sign up</p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="new_user_registration" <?php do_action( 'mvl_check_if', 'new_user_registration' ); ?> />
							<span class=""><i></i></span>
						</label>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3>Email confirmation</h3>
					</div>
					<p>This option lets all new registered users get an email confirmation to verify their account</p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="enable_email_confirmation" <?php do_action( 'mvl_check_if', 'enable_email_confirmation' ); ?> />
							<span class=""><i></i></span>
						</label>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3>Free listing submission</h3>
					</div>
					<p>It enables users to post listings for free</p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="free_listing_submission" <?php do_action( 'mvl_check_if', 'free_listing_submission' ); ?> />
							<span class=""><i></i></span>
						</label>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3>Listing publication limit</h3>
					</div>
					<p>This setting allows you to set the maximum number of images that can be uploaded for each listing</p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-row">
						<div class="mvl-settings-form-field">
							<div class="form-input">
								<input type="text" name="user_post_limit" value="<?php echo esc_attr( apply_filters( 'motors_vl_get_nuxy_mod', '', 'user_post_limit' ) ); ?>" />
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3>Image limit per listing</h3>
					</div>
					<p>Specify the maximum number of images that can be uploaded for each free listing</p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-row">
						<div class="mvl-settings-form-field">
							<div class="form-input">
								<input type="text" name="user_post_images_limit" value="<?php echo esc_attr( apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_delimeter' ) ); ?>" />
							</div>
						</div>
					</div>

				</div>

			</div>

			<div class="mvl-settings-form-group">

				<div class="mvl-settings-form-group-aside">
					<div class="setting-heading">
						<h3>Listing premoderation</h3>
					</div>
					<p>The listing will need an admin approvement before publication</p>
				</div>

				<div class="mvl-settings-form-group-content">

					<div class="mvl-settings-form-toggle">
						<label>
							<input type="checkbox" name="user_premoderation" <?php do_action( 'mvl_check_if', 'user_premoderation' ); ?> />
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
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', $prev_step_slug ) ); ?>" class="button" id="mvl-prev-step-link" data-step="<?php echo esc_attr( $prev_step_slug ); ?>">Back</a>
		</div>
		<div>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'finish' ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="finish">Next Step</a>
		</div>
	</div>

<?php
do_action( 'mvl_setup_wizard_data_fields', $settings );
