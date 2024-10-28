<?php
$settings  = apply_filters( 'mvl_setup_wizard_data', array() );
$plugins   = apply_filters( 'mvl_setup_wizard_plugins_recommended', array() );
$free_next = true;
$install   = false;
?>
<?php if ( $settings['use_starter'] ) : ?>
	<div class="mvl-welcome-content-banner">
		<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/admin/setup-wizard/images/' ); ?>plugins-full.png" width="720" height="300" alt="" />
	</div>
	<div class="mvl-welcome-content-body">
		<h2>2. Install plugins</h2>
		<p>Let’s install the required plugins from the plugins library to ensure everything works correctly.</p>

		<div class="mvl-welcome-todo">
			<?php
			foreach ( $plugins as $key => $plugin ) :
				$status_class = ( $plugin['active'] ) ? 'done' : '';
				$free_next    = ( ! $plugin['active'] && $plugin['required'] ) ? false : $free_next;
				$install      = ( ! $plugin['active'] ) ? true : $install;
				?>
				<div class="mvl-welcome-todo-item <?php echo esc_attr( $status_class ); ?>" data-action="mvl_setup_wizard_install_plugin" data-params="<?php echo esc_attr( json_encode( array( 'plugin' => $plugin['slug'] ) ) ); ?>">
					<div class="mvl-welcome-todo-item-heading">
						<strong>
							<?php echo esc_html( $key + 1 ); ?>.
							<?php echo esc_html( $plugin['title'] ); ?>
						</strong>
						<?php if ( $plugin['required'] ) : ?>
							<span class="heading-label">(required)</span>
						<?php endif; ?>
					</div>
					<div class="mvl-welcome-todo-item-description">

					</div>
					<div class="mvl-welcome-todo-item-status">
						<span class="status-badge <?php echo esc_attr( $status_class ); ?>" data-label="Not Installed" data-label-processing="Installing..." data-label-done="Active" data-label-error="Failed"></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
<?php else : ?>
	<div class="mvl-welcome-content-banner">
		<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/admin/setup-wizard/images/' ); ?>plugins.png" width="720" height="300" alt="" />
	</div>
	<div class="mvl-welcome-content-body">
		<div class="heading-block-inline">
			<h2>2. Install Elementor plugin</h2>
			<?php
			$status_class = '';
			if ( is_plugin_active( 'elementor/elementor.php' ) ) {
				$status_class = 'done';
				$install      = false;
				$free_next    = true;
			} else {
				$install      = true;
				$free_next    = false;
			}
			?>
			<span class="status-badge <?php echo esc_attr( $status_class ); ?>" id="elementor-status-badge" data-label="Not Installed" data-label-processing="Installing..." data-label-done="Active" data-label-error="Failed"></span>
		</div>
		<p>We recommend installing the Elementor plugin to enhance functionality and customize your website. Install Elementor now or skip this step and continue.</p>
	</div>
<?php endif; ?>

<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'theme' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="theme">Back</a>
	</div>
	<div>
		<?php if ( $install ) : ?>
			<?php if ( $settings['use_starter'] ) : ?>
				<button class="button button-primary" id="mvl-install-plugins-btn">Install Plugins</button>
			<?php else : ?>
				<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'demo-content' ) ); ?>" class="button button-secondary mvl-skip-btn" data-step="demo-content" id="mvl-skip-elementor-install">Skip this step</a>
				<button class="button button-primary" id="mvl-install-elementor">Install Plugin</button>
			<?php endif; ?>
		<?php endif; ?>
		<?php $next_btn_class = ( $free_next ) ? '' : 'hidden'; ?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'demo-content' ) ); ?>" class="button button-primary <?php echo esc_attr( $next_btn_class ); ?>" id="mvl-next-step-link" data-step="demo-content">Next Step</a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
