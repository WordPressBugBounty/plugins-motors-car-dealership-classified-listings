<?php
$settings = apply_filters( 'mvl_setup_wizard_data', array() );
?>
	<div class="mvl-welcome-content-body">
	<div class="heading-block-inline">
		<h2>
			<?php if ( ! defined( 'MOTORS_STARTER_THEME_VERSION' ) && ! defined( 'ELEMENTOR_VERSION' ) ) : ?>
				6.
			<?php else : ?>
				7.
			<?php endif; ?>
			<?php echo esc_html__( 'Import demo content', 'stm_vehicles_listing' ); ?>
		</h2>
		<?php if ( $settings['data_imported'] ) : ?>
			<span class="status-badge done" id="elementor-status-badge"
					data-label=""
					data-label-processing="<?php esc_attr_e( 'Importing...', 'stm_vehicles_listing' ); ?>"
					data-label-done="<?php esc_attr_e( 'Imported', 'stm_vehicles_listing' ); ?>"
					data-label-error=""></span>
		<?php endif; ?>
	</div>

	<p><?php echo esc_html__( 'Import demo content to quickly set up your site. You can customize or delete it anytime.', 'stm_vehicles_listing' ); ?></p>

	<div class="mvl-welcome-todo">

		<div class="mvl-welcome-todo-item" data-key="tax" data-action="mvl_setup_wizard_starter_import_fields">
			<div class="mvl-welcome-todo-item-heading">
				<strong><?php echo esc_html__( '1. Custom fields', 'stm_vehicles_listing' ); ?></strong>
			</div>
			<div class="mvl-welcome-todo-item-description">
				<p><?php echo esc_html__( 'Custom fields for listings posts.', 'stm_vehicles_listing' ); ?></p>
			</div>
			<div class="mvl-welcome-todo-item-status">
				<span class="status-badge"
						data-label=""
						data-label-processing="<?php esc_attr_e( 'Importing...', 'stm_vehicles_listing' ); ?>"
						data-label-done="<?php esc_attr_e( 'Done', 'stm_vehicles_listing' ); ?>"
						data-label-error="<?php esc_attr_e( 'Failed', 'stm_vehicles_listing' ); ?>"></span>
			</div>
		</div>

		<div class="mvl-welcome-todo-item" data-key="listings" data-action="mvl_setup_wizard_starter_import_content">
			<div class="mvl-welcome-todo-item-heading">
				<strong><?php echo esc_html__( '2. Sample listings', 'stm_vehicles_listing' ); ?></strong>
			</div>
			<div class="mvl-welcome-todo-item-description">
				<p><?php echo esc_html__( 'Sample listings posts.', 'stm_vehicles_listing' ); ?></p>
			</div>
			<div class="mvl-welcome-todo-item-status">
				<span class="status-badge"
						data-label=""
						data-label-processing="<?php esc_attr_e( 'Importing...', 'stm_vehicles_listing' ); ?>"
						data-label-done="<?php esc_attr_e( 'Done', 'stm_vehicles_listing' ); ?>"
						data-label-error="<?php esc_attr_e( 'Failed', 'stm_vehicles_listing' ); ?>"></span>
			</div>
		</div>
		<?php
		$action = ( ! $settings['use_starter'] ) ? esc_js( '["mvl_setup_wizard_starter_import_settings","mvl_setup_wizard_generate_pages"]' ) : 'mvl_setup_wizard_starter_import_settings';
		?>
		<div class="mvl-welcome-todo-item" data-key="pages" data-action="<?php echo esc_attr( $action ); ?>">
			<div class="mvl-welcome-todo-item-heading">
				<strong><?php echo esc_html__( '3. Page generation', 'stm_vehicles_listing' ); ?></strong>
			</div>
			<div class="mvl-welcome-todo-item-description">
				<p><?php echo esc_html__( 'Motors Plugin has essential pages:', 'stm_vehicles_listing' ); ?></p>
				<ul>
					<li><?php echo esc_html__( 'Listings page;', 'stm_vehicles_listing' ); ?></li>
					<li><?php echo esc_html__( 'Compare page;', 'stm_vehicles_listing' ); ?></li>
					<li><?php echo esc_html__( 'Profile page;', 'stm_vehicles_listing' ); ?></li>
					<li><?php echo esc_html__( 'Listing creation page.', 'stm_vehicles_listing' ); ?></li>
				</ul>
			</div>
			<div class="mvl-welcome-todo-item-status">
				<span class="status-badge"
						data-label=""
						data-label-processing="<?php esc_attr_e( 'Importing...', 'stm_vehicles_listing' ); ?>"
						data-label-done="<?php esc_attr_e( 'Done', 'stm_vehicles_listing' ); ?>"
						data-label-error="<?php esc_attr_e( 'Failed', 'stm_vehicles_listing' ); ?>"></span>
			</div>
		</div>

	</div>

</div>

<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="plugins">
			<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
	<div>
		<button class="button button-primary" id="mvl-import-demo-btn">
			<?php
			if ( ! $settings['data_imported'] ) {
				echo esc_html__( 'Import', 'stm_vehicles_listing' );
			} else {
				echo esc_html__( 'Reimport', 'stm_vehicles_listing' );
			}
			?>
		</button>
		<?php $next_btn_class = ( $settings['data_imported'] ) ? '' : 'hidden'; ?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'finish' ) ); ?>" class="button button-primary <?php echo esc_attr( $next_btn_class ); ?>" id="mvl-next-step-link" data-step="finish">
			<?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
</div>

<?php if ( ! $settings['data_imported'] ) : ?>
	<script>
		jQuery('#mvl-import-demo-btn').click();
	</script>
<?php endif; ?>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
