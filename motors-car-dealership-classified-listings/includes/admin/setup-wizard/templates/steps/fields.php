<?php
$settings = apply_filters( 'mvl_setup_wizard_data', array() );
?>

<div class="mvl-welcome-content-body">
	<h2><?php echo esc_html__( '1. Add custom fields', 'stm_vehicles_listing' ); ?></h2>
	<p><?php echo esc_html__( 'Set up custom fields for your listings and search filters here. You can always fine-tune or customize these fields later in the Custom Fields section.', 'stm_vehicles_listing' ); ?></p>

	<div class="field-notice" id="field-notice">
		<div class="field-notice-message"></div>
		<span class="close"></span>
	</div>

	<div class="field-editor">
		<div class="field-editor-main">
			<div class="field-editor-properties">

				<form id="mvl-custom-field-form">

					<div class="control">
						<div class="control-label">
							<span>
								<label><?php echo esc_html__( 'Singular name', 'stm_vehicles_listing' ); ?></label>
							</span>
						</div>
						<div class="control-input">
							<input type="text" placeholder="<?php echo esc_html__( 'e.g. Make', 'stm_vehicles_listing' ); ?>" name="single_name" />
						</div>
					</div>

					<div class="control">
						<div class="control-label">
							<span>
								<label><?php echo esc_html__( 'Plural name', 'stm_vehicles_listing' ); ?></label>
								<span class="control-tooltip">
									<span class="tooltip-message"><?php echo esc_html__( 'Used to display the name for multiple items in this field.', 'stm_vehicles_listing' ); ?></span>
								</span>
							</span>
						</div>
						<div class="control-input">
							<input type="text" placeholder="<?php echo esc_html__( 'e.g. Makes', 'stm_vehicles_listing' ); ?>" name="plural_name" />
						</div>
					</div>


					<div class="control">
						<div class="control-label">
							<span>
								<label><?php echo esc_html__( 'Field type', 'stm_vehicles_listing' ); ?></label>
								<span class="control-tooltip">
									<span class="tooltip-message"><?php echo esc_html__( 'This setting allows you to choose the input format: "Dropdown" accepts both text and numbers, while "Number" is for numerical values only.', 'stm_vehicles_listing' ); ?></span>
								</span>
							</span>
						</div>
						<div class="control-input">
							<select name="field_type">
								<option value="dropdown"><?php echo esc_html__( 'Dropdown select', 'stm_vehicles_listing' ); ?></option>
								<option value="numeric"><?php echo esc_html__( 'Number', 'stm_vehicles_listing' ); ?></option>
							</select>
						</div>
					</div>

					<div class="control">
						<div class="control-label">
							<span>
								<label><?php echo esc_html__( 'Choose icon', 'stm_vehicles_listing' ); ?></label>
								<span class="control-tooltip">
									<span class="tooltip-message"><?php echo esc_html__( 'Select an icon to represent the field.', 'stm_vehicles_listing' ); ?></span>
								</span>
							</span>
							<span class="control-preview">
								<?php echo esc_html__( 'PREVIEW', 'stm_vehicles_listing' ); ?>
								<span class="preview-tooltip">
									<div class="image">
										<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-icons.png" width="428" height="109" alt="">
									</div>
								</span>
							</span>
						</div>
						<div class="control-input">
							<div class="icon-control">
								<input type="hidden" name="font" value="" id="choose-icon-input">
								<span class="icon-preview sho" id="choose-icon-preview">
									<i class=""></i>
									<span class="close" title="Remove icon"></span>
								</span>
								<button class="button button-secondary" id="choose-icon-btn"><?php echo esc_html__( 'Add icon', 'stm_vehicles_listing' ); ?></button>
							</div>
						</div>
					</div>

					<div class="control">

						<div class="control-input">

							<div class="control-toggle">
								<label>
									<input type="checkbox" name="use_on_car_filter" />
									<span class="toggle"><i></i></span>
									<span class=""><?php echo esc_html__( 'Show in filter', 'stm_vehicles_listing' ); ?></span>
									<span class="control-preview">
										<?php echo esc_html__( 'PREVIEW', 'stm_vehicles_listing' ); ?>
										<span class="preview-tooltip">
											<div class="image">
												<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-filter.png" width="300" height="255" alt="">
											</div>
										</span>
									</span>
								</label>
							</div>

						</div>
					</div>

				</form>

			</div>

			<div class="field-editor-options">

				<div class="control">
					<div class="control-label">
						<label><?php echo esc_html__( 'Manage options', 'stm_vehicles_listing' ); ?></label>
					</div>

					<div class="control-options">
						<div class="control-options-terms">
							<div class="no-terms">
								<?php echo esc_html__( 'No options added', 'stm_vehicles_listing' ); ?>
							</div>
						</div>
						<div class="control-options-search">
							<input type="text" value="" name="" id="new-term-name" placeholder="<?php echo esc_html__( 'Enter option title', 'stm_vehicles_listing' ); ?>">
							<button class="button button-secondary button-small" id="new-term-add">Add</button>
						</div>
					</div>

				</div>

			</div>

		</div>
		<div class="field-editor-actions">
			<button class="button button-primary" id="mvl-custom-field-save-btn"><?php echo esc_html__( 'Save', 'stm_vehicles_listing' ); ?></button>
			<button class="button button-secondary" id="mvl-custom-field-clear-btn"><?php echo esc_html__( 'Clear', 'stm_vehicles_listing' ); ?></button>
		</div>
	</div>

</div>

<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'welcome' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="welcome">
			<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'search-results' ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="search-results"><?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?></a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );

if ( function_exists( 'stm_vehicles_listing_get_icons_html' ) ) {
	stm_vehicles_listing_get_icons_html();
}
