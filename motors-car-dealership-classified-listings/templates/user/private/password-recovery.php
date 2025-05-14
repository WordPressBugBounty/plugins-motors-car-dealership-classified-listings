<?php
$user_id_get     = intval( $_GET['user_id'] );
$user_hash_check = sanitize_text_field( $_GET['hash_check'] );
$message         = '';
$error           = false;

$user_exist = get_user_by( 'id', $user_id_get );
if ( ! $user_exist ) {
	$error = true;
}

$user_hash = get_the_author_meta( 'stm_lost_password_hash', $user_id_get );

if ( empty( $user_hash ) ) {
	$error = true;
	wp_die();
}

if ( ! hash_equals( $user_hash, $user_hash_check ) ) {
	$error = true;
	wp_die();
}

if ( ! empty( $_POST['stm_new_password'] ) && ! $error ) {
	$new_password = sanitize_text_field( $_POST['stm_new_password'] );
	wp_set_password( $new_password, $user_id_get );
	update_user_meta( $user_id_get, 'stm_lost_password_hash', '' );
	update_user_meta( $user_id_get, 'stm_lost_password_time', '' );
	$message = esc_html__( 'Password changed successfully', 'motors-car-dealership-classified-listings-pro' );
}

if ( ! $error ) :
	?>

	<div class="row">
		<div class="col-md-4">
			<h3><?php esc_html_e( 'Password Recovery', 'motors-car-dealership-classified-listings-pro' ); ?></h3>
			<div class="stm-login-form">
				<form method="post" class="stm_password_recovery" action="" id="password-recovery-form">
					<?php wp_nonce_field( 'password_recovery_action', 'password_recovery_nonce' ); ?>
					<div class="form-group">
						<h4><?php esc_html_e( 'New password', 'motors-car-dealership-classified-listings-pro' ); ?></h4>
						<input type="password" name="stm_new_password" id="stm_new_password" placeholder="<?php esc_attr_e( 'Enter new password', 'motors-car-dealership-classified-listings-pro' ); ?>" required/>
						<input type="submit" value="<?php esc_attr_e( 'Set new password', 'motors-car-dealership-classified-listings-pro' ); ?>"/>
						<div class="stm-validation-message" <?php echo empty( $message ) ? 'style="display: none;"' : ''; ?>><?php echo esc_html( $message ); ?></div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php endif; ?>
