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
		<h2><?php echo esc_html__( '5. Install plugins', 'stm_vehicles_listing' ); ?></h2>
		<p><?php echo esc_html__( 'Let’s install the required plugins from the plugins library to ensure everything works correctly.', 'stm_vehicles_listing' ); ?></p>

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
							<span class="heading-label"><?php echo esc_html__( '(required)', 'stm_vehicles_listing' ); ?></span>
						<?php endif; ?>
					</div>
					<div class="mvl-welcome-todo-item-description">

					</div>
					<div class="mvl-welcome-todo-item-status">
						<span class="status-badge <?php echo esc_attr( $status_class ); ?>"
								data-label="<?php esc_attr_e( 'Not Installed', 'stm_vehicles_listing' ); ?>"
								data-label-processing="<?php esc_attr_e( 'Installing...', 'stm_vehicles_listing' ); ?>"
								data-label-done="<?php esc_attr_e( 'Active', 'stm_vehicles_listing' ); ?>"
								data-label-error="<?php esc_attr_e( 'Failed', 'stm_vehicles_listing' ); ?>"></span>
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
			<h2><?php echo esc_html__( '2. Install Elementor plugin', 'stm_vehicles_listing' ); ?></h2>
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
			<span class="status-badge <?php echo esc_attr( $status_class ); ?>" id="elementor-status-badge"
					data-label="<?php esc_attr_e( 'Not Installed', 'stm_vehicles_listing' ); ?>"
					data-label-processing="<?php esc_attr_e( 'Installing...', 'stm_vehicles_listing' ); ?>"
					data-label-done="<?php esc_attr_e( 'Active', 'stm_vehicles_listing' ); ?>"
					data-label-error="<?php esc_attr_e( 'Failed', 'stm_vehicles_listing' ); ?>"></span>
		</div>
		<p><?php echo esc_html__( 'We recommend installing the Elementor plugin to enhance functionality and customize your website. Install Elementor now or skip this step and continue.', 'stm_vehicles_listing' ); ?></p>
	</div>
<?php endif; ?>

<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'theme' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="theme">
			<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
	<div>
		<?php if ( $install ) : ?>
			<?php if ( $settings['use_starter'] ) : ?>
				<button class="button button-primary" id="mvl-install-plugins-btn">
					<?php echo esc_html__( 'Install Plugins', 'stm_vehicles_listing' ); ?>
				</button>
			<?php else : ?>
				<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'demo-content' ) ); ?>" class="button button-secondary mvl-skip-btn" data-step="demo-content" id="mvl-skip-elementor-install">
					<?php echo esc_html__( 'Skip this step', 'stm_vehicles_listing' ); ?>
				</a>
				<button class="button button-primary" id="mvl-install-elementor">
					<?php echo esc_html__( 'Install Plugin', 'stm_vehicles_listing' ); ?>
				</button>
			<?php endif; ?>
		<?php endif; ?>
		<?php
		$next_btn_class = ( $free_next ) ? '' : 'hidden';
		?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'single-listing' ) ); ?>" class="button button-primary <?php echo esc_attr( $next_btn_class ); ?>" id="mvl-next-step-link" data-step="single-listing">
			<?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
