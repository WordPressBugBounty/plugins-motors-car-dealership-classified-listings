<?php
	$settings            = apply_filters( 'mvl_setup_wizard_data', array() );
	$next_step_btn_class = ( ! $settings['use_starter'] ) ? 'hidden' : '';
?>
<div class="mvl-welcome-content-banner">
	<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/admin/setup-wizard/images/' ); ?>theme.png" width="720" height="300" alt="" />
</div>
<div class="mvl-welcome-content-body">
	<div class="heading-block-inline">
		<h2><?php echo esc_html__( '4. Motors Skins theme', 'stm_vehicles_listing' ); ?></h2>
		<?php $status_class = ( $settings['use_starter'] ) ? 'done' : ''; ?>
		<span class="status-badge <?php echo esc_attr( $status_class ); ?>" id="starter-status-badge" data-label="<?php esc_attr_e( 'Not Installed', 'stm_vehicles_listing' ); ?>" data-label-processing="<?php esc_attr_e( 'Installing...', 'stm_vehicles_listing' ); ?>" data-label-done="<?php esc_attr_e( 'Installed', 'stm_vehicles_listing' ); ?>" data-label-error="<?php esc_attr_e( 'Failed', 'stm_vehicles_listing' ); ?>"></span>
	</div>
	<p><?php echo esc_html__( 'A free, ready-to-use theme for Motors Plugin. Easily customizable with Elementor, no technical skills required. Optimized for all devices and built for speed, ensuring smooth performance and high scores on speed tests.', 'stm_vehicles_listing' ); ?></p>

	<div class="install-progress hidden">
		<div class="install-progress-status">
			<div class="install-progress-status-label"><?php echo esc_html__( 'Installation in progress...', 'stm_vehicles_listing' ); ?></div>
			<div class="install-progress-status-amount">5%</div>
		</div>
		<div class="install-progress-bar">
			<div class="install-progress-bar-inside" style="width: 5%"></div>
		</div>
		<div class="install-progress-notice">
			<?php echo esc_html__( 'Please don’t reload the page', 'stm_vehicles_listing' ); ?>
		</div>
	</div>
</div>
<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'profile' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="profile">
			<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
	<div>
		<?php if ( ! $settings['use_starter'] ) : ?>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button button-secondary mvl-skip-btn" id="mvl-use-default-theme" data-step="plugins"><?php echo esc_html__( 'Use current theme', 'stm_vehicles_listing' ); ?></a>
			<button class="button button-primary" id="mvl-starter-install-btn" data-default-label="Install Motors starter theme"><?php echo esc_html__( 'Install Motors starter theme', 'stm_vehicles_listing' ); ?></button>
		<?php else : ?>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button button-secondary mvl-skip-btn" id="mvl-use-default-theme" data-step="plugins"><?php echo esc_html__( 'Use another theme', 'stm_vehicles_listing' ); ?></a>
		<?php endif; ?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button button-primary <?php echo esc_attr( $next_step_btn_class ); ?>" id="mvl-next-step-link" data-step="plugins"><?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?></a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
