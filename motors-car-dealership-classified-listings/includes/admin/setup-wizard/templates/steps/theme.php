<?php
	$settings            = apply_filters( 'mvl_setup_wizard_data', array() );
	$next_step_btn_class = ( ! $settings['use_starter'] ) ? 'hidden' : '';
?>
<div class="mvl-welcome-content-banner">
	<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/admin/setup-wizard/images/' ); ?>theme.png" width="720" height="300" alt="" />
</div>
<div class="mvl-welcome-content-body">
	<div class="heading-block-inline">
		<h2>1. Motors starter theme</h2>
		<?php $status_class = ( $settings['use_starter'] ) ? 'done' : ''; ?>
		<span class="status-badge <?php echo esc_attr( $status_class ); ?>" id="starter-status-badge" data-label="Not Installed" data-label-processing="Installing..." data-label-done="Installed" data-label-error="Failed"></span>
	</div>
	<p>A free, ready-to-use starter theme for Motors Plugin. Easily customizable with Elementor, no technical skills required. Optimized for all devices and built for speed, ensuring smooth performance and high scores on speed tests.</p>

	<div class="install-progress hidden">
		<div class="install-progress-status">
			<div class="install-progress-status-label">Installation in progress...</div>
			<div class="install-progress-status-amount">5%</div>
		</div>
		<div class="install-progress-bar">
			<div class="install-progress-bar-inside" style="width: 5%"></div>
		</div>
		<div class="install-progress-notice">
			Please don’t reload the page
		</div>
	</div>
</div>
<div class="mvl-welcome-nav-actions">
	<div>

	</div>
	<div>
		<?php if ( ! $settings['use_starter'] ) : ?>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button button-secondary mvl-skip-btn" id="mvl-use-default-theme" data-step="plugins">Use current theme</a>
			<button class="button button-primary" id="mvl-starter-install-btn" data-default-label="Install Motors starter theme">Install Motors starter theme</button>
		<?php else : ?>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button button-secondary mvl-skip-btn" id="mvl-use-default-theme" data-step="plugins">Use another theme</a>
		<?php endif; ?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button button-primary <?php echo esc_attr( $next_step_btn_class ); ?>" id="mvl-next-step-link" data-step="plugins">Next Step</a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
