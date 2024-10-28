<?php
$settings = apply_filters( 'mvl_setup_wizard_data', array() );
?>
	<div class="mvl-welcome-content-body">
	<div class="heading-block-inline">
		<h2>3. Import demo content</h2>
		<?php if ( $settings['data_imported'] ) : ?>
			<span class="status-badge done" id="elementor-status-badge" data-label="" data-label-processing="Importing..." data-label-done="Imported" data-label-error=""></span>
		<?php endif; ?>
	</div>

		<p>Import demo content to quickly set up your site. You can customize or delete it anytime.</p>

		<div class="mvl-welcome-todo">

			<div class="mvl-welcome-todo-item" data-key="tax" data-action="mvl_setup_wizard_starter_import_fields">
				<div class="mvl-welcome-todo-item-heading">
					<strong>1. Custom fields</strong>
				</div>
				<div class="mvl-welcome-todo-item-description">
					<p>Custom fields for listings posts.</p>
				</div>
				<div class="mvl-welcome-todo-item-status">
					<span class="status-badge" data-label="" data-label-processing="Importing..." data-label-done="Done" data-label-error="Failed"></span>
				</div>
			</div>

			<div class="mvl-welcome-todo-item" data-key="listings" data-action="mvl_setup_wizard_starter_import_content">
				<div class="mvl-welcome-todo-item-heading">
					<strong>2. Sample listings</strong>
				</div>
				<div class="mvl-welcome-todo-item-description">
					<p>Sample listings posts.</p>
				</div>
				<div class="mvl-welcome-todo-item-status">
					<span class="status-badge" data-label="" data-label-processing="Importing..." data-label-done="Done" data-label-error="Failed"></span>
				</div>
			</div>
			<?php
			$action = ( ! $settings['use_starter'] ) ? esc_js( '["mvl_setup_wizard_starter_import_settings","mvl_setup_wizard_generate_pages"]' ) : 'mvl_setup_wizard_starter_import_settings';
			?>
			<div class="mvl-welcome-todo-item" data-key="pages" data-action="<?php echo esc_attr( $action ); ?>">
				<div class="mvl-welcome-todo-item-heading">
					<strong>3. Page generation</strong>
				</div>
				<div class="mvl-welcome-todo-item-description">
					<p>Motors Plugin has essential pages:</p>
					<ul>
						<li>Listings page;</li>
						<li>Compare page;</li>
						<li>Profile page;</li>
						<li>Listing creation page.</li>
					</ul>
				</div>
				<div class="mvl-welcome-todo-item-status">
					<span class="status-badge" data-label="" data-label-processing="Importing..." data-label-done="Done" data-label-error="Failed"></span>
				</div>
			</div>

		</div>

	</div>

<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="plugins">Back</a>
	</div>
	<div>
		<button class="button button-primary" id="mvl-import-demo-btn">Import</button>
		<?php $next_btn_class = ( $settings['data_imported'] ) ? '' : 'hidden'; ?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'search-results' ) ); ?>" class="button button-primary <?php echo esc_attr( $next_btn_class ); ?>" id="mvl-next-step-link" data-step="search-results">Next Step</a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
