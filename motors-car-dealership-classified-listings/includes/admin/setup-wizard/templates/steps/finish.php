<?php
$settings = apply_filters( 'mvl_setup_wizard_data', array() );
?>
	<div class="mvl-welcome-content-body">
		<div class="finish-block">
			<div class="finish-block-logo">
				<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/class/Plugin/assets/img/logo.png" width="72" height="72" />
			</div>
			<h2><?php echo esc_html__( 'You’re ready to go!', 'stm_vehicles_listing' ); ?></h2>
			<p><?php echo esc_html__( 'You’ve successfully set up the Motors plugin. Now you can start creating and managing your listings with ease.', 'stm_vehicles_listing' ); ?></p>
			<div class="finish-block-actions">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=listings' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Add listing', 'stm_vehicles_listing' ); ?></a>
			</div>
			<div class="finish-block-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=listing_categories' ) ); ?>" class="button button-secondary"><?php echo esc_html__( 'Add Custom Field', 'stm_vehicles_listing' ); ?></a>
			</div>
			<div class="finish-block-actions">
				<a href="<?php echo esc_url( site_url() ); ?>" class="button button-secondary"><?php echo esc_html__( 'View your website', 'stm_vehicles_listing' ); ?></a>
				<a href="https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing" class="button button-secondary" target="_blank" rel="nofollow"><?php echo esc_html__( 'Documentation', 'stm_vehicles_listing' ); ?></a>
			</div>
			<div class="finish-block-return">
				<a href="<?php echo esc_url( admin_url() ); ?>" class="button button-link"><?php echo esc_html__( 'Return to the dashboard', 'stm_vehicles_listing' ); ?></a>
			</div>
		</div>
	</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
delete_option( 'mvl_setup_wizard_settings_temp' );
