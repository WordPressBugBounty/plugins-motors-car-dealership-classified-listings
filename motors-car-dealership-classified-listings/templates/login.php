<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$can_register = apply_filters( 'motors_vl_get_nuxy_mod', false, 'new_user_registration' );

// Check if form editor is enabled for sign_up form (same as widget — load template to preserve scripts).
$has_form_editor = false;
if ( class_exists( '\MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config' ) ) {
	$form_config            = \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config::instance_of( 'sign_up' );
	$addons_editor_enabled  = apply_filters( 'mvl_is_addon_enabled', false, 'forms_editor' );
	$has_form_editor        = $addons_editor_enabled && ! empty( $form_config );
}

$login_form_class    = '';
$register_form_class = '';

if ( $has_form_editor ) {
	$login_form_class    = 'mvl-forms-editor-login-form';
	$register_form_class = 'mvl-forms-editor-register-form';
}

?>

	<div class="stm-login-register-form motors-alignwide">
		<div class="row">
			<div class="col-md-4">
				<h3><?php esc_html_e( 'Sign In', 'stm_vehicles_listing' ); ?></h3>
				<div class="stm-login-form <?php echo esc_attr( $login_form_class ); ?>">
					<form method="post">
						<div class="form-group">
							<h4><?php esc_html_e( 'Login or E-mail', 'stm_vehicles_listing' ); ?></h4>
							<input type="text" class="form-control" name="stm_user_login"
								placeholder="<?php esc_html_e( 'Enter login or E-mail', 'stm_vehicles_listing' ); ?>"/>
						</div>
						<div class="form-group">
							<h4><?php esc_html_e( 'Password', 'stm_vehicles_listing' ); ?></h4>
							<input type="password" class="form-control" name="stm_user_password"
								placeholder="<?php esc_html_e( 'Enter password', 'stm_vehicles_listing' ); ?>"/>
						</div>
						<div class="form-group form-checker">
							<label>
								<input type="checkbox" name="stm_remember_me"/>
								<span><?php esc_html_e( 'Remember me', 'stm_vehicles_listing' ); ?></span>
							</label>
						</div>
						<?php if ( class_exists( 'SitePress' ) ) : ?>
							<input type="hidden" name="current_lang" value="<?php echo ICL_LANGUAGE_CODE; //phpcs:ignore ?>"/>
						<?php endif; ?>
						<input type="submit" class="button" value="<?php esc_html_e( 'Login', 'stm_vehicles_listing' ); ?>"/>
						<span class="stm-listing-loader"><i class="fas fa-spinner"></i></span>
						<?php
						if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {
							echo do_shortcode( '[motors_social_login action="sign-in"]' );
						}
						?>
						<div class="stm-validation-message"></div>
					</form>
				</div>
			</div>
			<div class="col-md-8">
				<?php if ( $can_register ) : ?>
					<h3><?php esc_html_e( 'Sign Up', 'stm_vehicles_listing' ); ?></h3>
					<div class="stm-register-form <?php echo esc_attr( $register_form_class ); ?>">
						<?php if ( $has_form_editor ) : ?>
							<?php
							$form_config = \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config::instance_of( 'sign_up' );
							if ( $form_config ) {
								$form_data    = $form_config->data();
								$saved_values = $form_config->get_values();
								$fields       = $form_data['fields'] ?? array();

								$template_data = array(
									'form_slug'    => $form_config->snake_case_class_name(),
									'args'         => array(
										'is_modal'          => true,
										'__link_of_terms__' => $__link_of_terms__ ?? '',
									),
									'fields'       => $fields,
									'saved_values' => $saved_values,
								);

								do_action( 'stm_listings_load_template', 'addons/forms-editor/page/partials/forms/sign-up', $template_data );
							}
							?>
						<?php else : ?>
						<form method="post">
							<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'new_user_registration' ) ) : ?>
								<input type="hidden" name="stm_custom_register_nonce" value="<?php echo esc_attr( wp_create_nonce( 'stm_custom_register' ) ); ?>">
							<?php endif; ?>
							<div class="row form-group">
								<div class="col-md-6">
									<h4><?php esc_html_e( 'First Name', 'stm_vehicles_listing' ); ?></h4>
									<input class="user_validated_field form-control" type="text" name="stm_user_first_name"
										placeholder="<?php esc_html_e( 'Enter First name', 'stm_vehicles_listing' ); ?>"/>
								</div>
								<div class="col-md-6">
									<h4><?php esc_html_e( 'Last Name', 'stm_vehicles_listing' ); ?></h4>
									<input class="user_validated_field form-control" type="text" name="stm_user_last_name"
										placeholder="<?php esc_html_e( 'Enter Last name', 'stm_vehicles_listing' ); ?>"/>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-6">
									<h4><?php esc_html_e( 'Phone number', 'stm_vehicles_listing' ); ?></h4>
									<input class="user_validated_field form-control" type="tel" name="stm_user_phone"
										placeholder="<?php esc_html_e( 'Enter Phone', 'stm_vehicles_listing' ); ?>"/>
								</div>
								<div class="col-md-6">
									<h4><?php esc_html_e( 'Email *', 'stm_vehicles_listing' ); ?></h4>
									<input class="user_validated_field form-control" type="email" name="stm_user_mail"
										placeholder="<?php esc_html_e( 'Enter E-mail', 'stm_vehicles_listing' ); ?>"/>
								</div>
							</div>

							<div class="row form-group">
								<div class="col-md-6">
									<h4><?php esc_html_e( 'Login *', 'stm_vehicles_listing' ); ?></h4>
									<input class="user_validated_field form-control" type="text" name="stm_nickname"
										placeholder="<?php esc_html_e( 'Enter Login', 'stm_vehicles_listing' ); ?>"/>
								</div>
								<div class="col-md-6">
									<h4><?php esc_html_e( 'Password *', 'stm_vehicles_listing' ); ?></h4>
									<div class="stm-show-password">
										<i class="far fa-eye-slash"></i>
										<input class="user_validated_field form-control" type="password" name="stm_user_password"
											placeholder="<?php esc_html_e( 'Enter Password', 'stm_vehicles_listing' ); ?>"/>
									</div>
								</div>
							</div>
							<?php
							if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_term_service' ) ) :
								$link = apply_filters( 'motors_vl_get_nuxy_mod', '', 'terms_service' );
								?>
								<div class="form-group form-checker">
									<label>
										<input type="checkbox" name="stm_accept_terms"/>
										<span>
										<?php esc_html_e( 'I accept the terms of the', 'stm_vehicles_listing' ); ?>
										<?php if ( ! empty( $link ) ) : ?>
											<a href="<?php echo esc_url( get_the_permalink( $link ) ); ?>" target="_blank"><?php echo esc_html( get_the_title( $link ) ); ?></a>
										<?php endif; ?>
										</span>
									</label>
								</div>
							<?php endif; ?>
							<?php if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'allow_user_register_as_dealer' ) && apply_filters( 'is_mvl_pro', false ) ) : ?>
								<div class="stm-register-as-dealer form-group form-checker">
									<label>
										<input type="checkbox" name="register_as_dealer" value="1"/>
										<span><?php esc_html_e( 'Sign Up as a Dealer', 'stm_vehicles_listing' ); ?></span>
									</label>
								</div>
							<?php endif; ?>
							<div class="form-group form-group-submit clearfix">
								<input type="submit" class="button" <?php echo ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_term_service' ) ) ? 'disabled=1' : ''; ?> value="<?php esc_html_e( 'Sign up now!', 'stm_vehicles_listing' ); ?>"/>
								<span class="stm-listing-loader"><i class="fas fa-spinner"></i></span>
							</div>
							<?php
							if ( apply_filters( 'mvl_is_addon_enabled', false, 'social_login' ) ) {
								echo do_shortcode( '[motors_social_login action="sign-up"]' );
							}
							?>
							<div class="stm-validation-message"></div>
						</form>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php //phpcs:disable ?>
	<script type="text/javascript">
        jQuery(document).ready(function () {
            var $ = jQuery;

            $( 'input[name="stm_accept_terms"]' ).on(
                'click',
                function () {
                    if ($( this ).is( ':checked' )) {
                        $( '.stm-login-register-form .stm-register-form form input[type="submit"]' ).removeAttr( 'disabled' );
                    } else {
                        $( '.stm-login-register-form .stm-register-form form input[type="submit"]' ).attr( 'disabled', '1' );
                    }
                }
            );

            $('.stm-show-password .fa').mousedown(function () {
                $(this).closest('.stm-show-password').find('input').attr('type', 'text');
                $(this).addClass('fa-eye');
                $(this).removeClass('fa-eye-slash');
            });

            $(document).mouseup(function () {
                $('.stm-show-password').find('input').attr('type', 'password');
                $('.stm-show-password .fa').addClass('fa-eye-slash');
                $('.stm-show-password .fa').removeClass('fa-eye');
            });

            $("body").on('touchstart', '.stm-show-password .fa', function () {
                $(this).closest('.stm-show-password').find('input').attr('type', 'text');
                $(this).addClass('fa-eye');
                $(this).removeClass('fa-eye-slash');
            });
        });
	</script>
<?php //phpcs:enable ?>
