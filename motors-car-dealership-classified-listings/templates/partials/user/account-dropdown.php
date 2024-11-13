<?php
if ( ! is_user_logged_in() ) :
	?>
	<div class="lOffer-account-dropdown stm-login-form-unregistered">
		<span class="saved-search-close-popup-button">
			<i class="fa-solid fa-times"></i>
		</span>
		<form method="post">
			<?php do_action( 'stm_before_signin_form' ); ?>
			<div class="form-group">
				<h4><?php esc_html_e( 'Login or E-mail', 'stm_vehicles_listing' ); ?></h4>
				<input type="text" name="stm_user_login" autocomplete="off"
					placeholder="<?php esc_attr_e( 'Enter login or E-mail', 'stm_vehicles_listing' ); ?>"/>
			</div>
			<div class="form-group">
				<h4><?php esc_html_e( 'Password', 'stm_vehicles_listing' ); ?></h4>
				<input type="password" name="stm_user_password" autocomplete="off"
					placeholder="<?php esc_attr_e( 'Enter password', 'stm_vehicles_listing' ); ?>"/>
			</div>
			<div class="form-group form-checker">
				<label>
					<input type="checkbox" name="stm_remember_me"/>
					<span><?php esc_html_e( 'Remember me', 'stm_vehicles_listing' ); ?></span>
				</label>
			</div>
			<?php if ( class_exists( 'SitePress' ) ) : ?>
				<input type="hidden" name="current_lang" value="<?php echo esc_attr( ICL_LANGUAGE_CODE ); ?>"/>
			<?php endif; ?>
			<div class="stm-login-form-actions">
				<input type="submit" value="<?php esc_attr_e( 'Login', 'stm_vehicles_listing' ); ?>"/>
				<span class="stm-listing-loader"><i class="fa-solid fa-circle-notch"></i></span>
				<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'register' ) ); ?>" class="stm_label">
					<?php esc_html_e( 'Sign Up', 'stm_vehicles_listing' ); ?>
				</a>
				<div class="stm-validation-message"></div>
			</div>

			<?php do_action( 'stm_after_signin_form' ); ?>
		</form>
	</div>
<?php endif; ?>
