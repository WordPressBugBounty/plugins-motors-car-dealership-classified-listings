<?php
$settings = apply_filters( 'mvl_setup_wizard_data', array() ); //var_dump( $settings );
?>
<div class="mvl-welcome-content-body">
	<div class="welcome-block">
		<div class="welcome-block-logo">
			<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/class/Plugin/assets/img/logo.png" width="72" height="72" />
		</div>
		<h2>Welcome to Motors!</h2>
		<p>This setup wizard helps you quickly configure Motors so you can start creating listings. It’s optional, takes only a few minutes and you can return to it anytime.</p>
		<div class="welcome-block-actions">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mvl_plugin_settings' ) ); ?>" class="button button-secondary">Exit setup</a> &nbsp;
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'theme' ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="theme">Start plugin setup</a>
		</div>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
