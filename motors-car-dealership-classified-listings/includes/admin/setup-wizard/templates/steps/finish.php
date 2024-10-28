<?php
$settings = apply_filters( 'mvl_setup_wizard_data', array() );
?>
	<div class="mvl-welcome-content-body">
		<div class="finish-block">
			<div class="finish-block-logo">
				<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/class/Plugin/assets/img/logo.png" width="72" height="72" />
			</div>
			<h2>You’re ready to go!</h2>
			<p>You’ve successfully set up the Motors plugin. Now you can start creating and managing your listings with ease.</p>
			<div class="finish-block-actions">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=listings' ) ); ?>" class="button button-primary">Add listing</a>
			</div>
			<div class="finish-block-actions">
				<a href="<?php echo esc_url( site_url() ); ?>" class="button button-secondary">View your website</a>
				<a href="https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing" class="button button-secondary" target="_blank" rel="nofollow">Documentation</a>
			</div>
			<div class="finish-block-return">
				<a href="<?php echo esc_url( admin_url() ); ?>" class="button button-link">Return to the dashboard</a>
			</div>
		</div>
	</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
