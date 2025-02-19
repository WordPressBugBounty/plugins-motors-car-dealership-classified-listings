<?php
$settings        = apply_filters( 'mvl_setup_wizard_data', array() );
$pro_class       = ( ! $settings['use_pro'] ) ? 'disabled' : '';
$settings_temp   = get_option( 'mvl_setup_wizard_settings_temp', array() );
$plugin_settings = array(
	'listing_filter_position'   => ( ! empty( $settings_temp['listing_filter_position'] ) ) ? $settings_temp['listing_filter_position'] : apply_filters( 'motors_vl_get_nuxy_mod', 'left', 'listing_filter_position', true ),
	'listing_view_type'         => ( ! empty( $settings_temp['listing_view_type'] ) ) ? $settings_temp['listing_view_type'] : apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type', true ),
	'show_listing_compare'      => ( ! empty( $settings_temp['show_listing_compare'] ) ) ? $settings_temp['show_listing_compare'] : apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare', true ),
	'enable_favorite_items'     => ( ! empty( $settings_temp['enable_favorite_items'] ) ) ? $settings_temp['enable_favorite_items'] : apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_favorite_items', true ),
	'price_currency_name'       => ( ! empty( $settings_temp['price_currency_name'] ) ) ? $settings_temp['price_currency_name'] : apply_filters( 'motors_vl_get_nuxy_mod', 'USD', 'price_currency_name', true ),
	'price_currency'            => ( ! empty( $settings_temp['price_currency'] ) ) ? $settings_temp['price_currency'] : apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency', true ),
	'price_currency_position'   => ( ! empty( $settings_temp['price_currency_position'] ) ) ? $settings_temp['price_currency_position'] : apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'price_currency_position', true ),
	'price_delimeter'           => ( ! empty( $settings_temp['price_delimeter'] ) ) ? $settings_temp['price_delimeter'] : apply_filters( 'motors_vl_get_nuxy_mod', '.', 'price_delimeter' ),
	'gallery_hover_interaction' => ( ! empty( $settings_temp['gallery_hover_interaction'] ) ) ? $settings_temp['gallery_hover_interaction'] : apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction', true ),
);
?>
<div class="mvl-welcome-content-body">
	<h2><?php echo esc_html__( '2. Search results page settings', 'stm_vehicles_listing' ); ?></h2>
	<p><?php echo esc_html__( 'Customize filter bar position, default view (grid/list), and options for compare and favorites buttons. Adjust currency settings and image sliding on hover (Pro) to suit your needs.', 'stm_vehicles_listing' ); ?></p>
	<p><em><?php echo esc_html__( 'You can change these settings anytime after setup.', 'stm_vehicles_listing' ); ?></em></p>

	<form class="mvl-settings-form" id="mvl-settings-form">

		<?php if ( ! is_plugin_active( 'elementor/elementor.php' ) ) : ?>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3><?php echo esc_html__( 'Filter bar position', 'stm_vehicles_listing' ); ?></h3>
				</div>
				<p><?php echo esc_html__( 'Set the position of the filter bar to optimize user experience on your listings page.', 'stm_vehicles_listing' ); ?></p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-radio">
					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_filter_position" value="left" <?php do_action( 'mvl_check_if', $plugin_settings['listing_filter_position'], 'left' ); ?>>
								<span class="label"><?php echo esc_html__( 'Left', 'stm_vehicles_listing' ); ?></span>
								<span class="option-preview">
									<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/filter-left.jpg" />
								</span>
							</span>
						</label>
					</div>
					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_filter_position" value="right" <?php do_action( 'mvl_check_if', $plugin_settings['listing_filter_position'], 'right' ); ?>>
								<span class="label"><?php echo esc_html__( 'Right', 'stm_vehicles_listing' ); ?></span>
								<span class="option-preview">
									<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/filter-right.jpg" />
								</span>
							</span>
						</label>
					</div>
				</div>

			</div>

		</div>

		<?php endif; ?>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3><?php echo esc_html__( 'Default desktop view for the listing page', 'stm_vehicles_listing' ); ?></h3>
				</div>
				<p><?php echo esc_html__( 'Choose how you want to display your listing page by default on desktop.', 'stm_vehicles_listing' ); ?></p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-radio">

					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_view_type" value="grid" <?php do_action( 'mvl_check_if', $plugin_settings['listing_view_type'], 'grid' ); ?>>
								<span class="label"><?php echo esc_html__( 'Grid', 'stm_vehicles_listing' ); ?></span>
								<span class="option-preview">
									<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/grid.png" />
								</span>
							</span>
						</label>
					</div>

					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_view_type" value="list" <?php do_action( 'mvl_check_if', $plugin_settings['listing_view_type'], 'list' ); ?>>
								<span class="label"><?php echo esc_html__( 'List', 'stm_vehicles_listing' ); ?></span>
								<span class="option-preview">
									<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/list.png" />
								</span>
							</span>
						</label>
					</div>

				</div>

			</div>

		</div>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3><?php echo esc_html__( 'Compare button', 'stm_vehicles_listing' ); ?></h3>
					<span class="setting-preview">
						<?php echo esc_html__( 'Preview', 'stm_vehicles_listing' ); ?>
						<span class="preview-tooltip">
							<div class="image">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-compare.png" width="200" height="209" alt="" />
							</div>
						</span>
					</span>
				</div>
				<p><?php echo esc_html__( 'The listing will have a separate button so that users can compare the separate vehicles.', 'stm_vehicles_listing' ); ?></p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-toggle">
					<label>
						<input type="checkbox" name="show_listing_compare" <?php do_action( 'mvl_check_if', $plugin_settings['show_listing_compare'] ); ?>/>
						<span><i></i></span>
					</label>
				</div>

			</div>

		</div>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3><?php echo esc_html__( 'Add to favorites button', 'stm_vehicles_listing' ); ?></h3>
					<span class="setting-preview">
						<?php echo esc_html__( 'Preview', 'stm_vehicles_listing' ); ?>
						<span class="preview-tooltip">
							<div class="image">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-favorite.png" width="200" height="209" alt="" />
							</div>
						</span>
					</span>
				</div>
				<p><?php echo esc_html__( 'When hovering over the listing image users will have the option to save the listing to their favorites.', 'stm_vehicles_listing' ); ?></p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-toggle">
					<label>
						<input type="checkbox" name="enable_favorite_items" <?php do_action( 'mvl_check_if', $plugin_settings['enable_favorite_items'] ); ?>/>
						<span><i></i></span>
					</label>
				</div>

			</div>

		</div>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3><?php echo esc_html__( 'Currency settings', 'stm_vehicles_listing' ); ?></h3>
				</div>
				<p><?php echo esc_html__( 'Configure the currency options for your listings to match your preferred format and locale.', 'stm_vehicles_listing' ); ?></p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-row">
					<div class="mvl-settings-form-field">
						<label><?php echo esc_html__( 'Currency Name', 'stm_vehicles_listing' ); ?></label>
						<div class="form-input">
							<input type="text" name="price_currency_name" value="<?php echo esc_attr( $plugin_settings['price_currency_name'] ); ?>" />
						</div>
					</div>
					<div class="mvl-settings-form-field">
						<label><?php echo esc_html__( 'Currency Symbol', 'stm_vehicles_listing' ); ?></label>
						<div class="form-input">
							<input type="text" name="price_currency" value="<?php echo esc_attr( $plugin_settings['price_currency'] ); ?>" />
						</div>
					</div>
				</div>

				<div class="mvl-settings-form-row">
					<div class="mvl-settings-form-field">
						<label><?php echo esc_html__( 'Currency Position', 'stm_vehicles_listing' ); ?></label>
						<div class="mvl-settings-form-radio">
							<div class="mvl-settings-form-radio-item">
								<label>
									<input type="radio" name="price_currency_position" value="left" <?php do_action( 'mvl_check_if', $plugin_settings['price_currency_position'], 'left' ); ?>>
									<span class=""><?php echo esc_html__( 'Left', 'stm_vehicles_listing' ); ?></span>
								</label>
							</div>
							<div class="mvl-settings-form-radio-item">
								<label>
									<input type="radio" name="price_currency_position" value="right" <?php do_action( 'mvl_check_if', $plugin_settings['price_currency_position'], 'right' ); ?>>
									<span class=""><?php echo esc_html__( 'Right', 'stm_vehicles_listing' ); ?></span>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="mvl-settings-form-row">
					<div class="mvl-settings-form-field">
						<label><?php echo esc_html__( 'Decimal & thousands separators', 'stm_vehicles_listing' ); ?></label>
						<div class="form-input">
							<input type="text" name="price_delimeter" value="<?php echo esc_attr( $plugin_settings['price_delimeter'] ); ?>" />
						</div>
					</div>
				</div>

			</div>

		</div>

		<div class="mvl-settings-form-group <?php echo esc_attr( $pro_class ); ?>">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3><?php echo esc_html__( 'Image sliding on Hover', 'stm_vehicles_listing' ); ?></h3>
					<span class="badge-pro"><?php echo esc_html__( 'PRO', 'stm_vehicles_listing' ); ?></span>
					<span class="setting-preview">
						<?php echo esc_html__( 'Preview', 'stm_vehicles_listing' ); ?>
						<span class="preview-tooltip">
							<div class="image">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-carousel.png" width="200" height="209" alt="" />
							</div>
						</span>
					</span>
				</div>
				<p><?php echo esc_html__( 'When hovering the listing images will be previewed as a slider.', 'stm_vehicles_listing' ); ?></p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-toggle">
					<label>
						<input type="checkbox" name="gallery_hover_interaction" <?php do_action( 'mvl_check_if', $plugin_settings['gallery_hover_interaction'] ); ?>/>
						<span><i></i></span>
					</label>
				</div>

			</div>

		</div>

	</form>

</div>
<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'demo-content' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="demo-content">
			<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
	<div>
		<?php
		$next_step_slug = 'profile';
		?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', $next_step_slug ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="<?php echo esc_attr( $next_step_slug ); ?>">
			<?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields', $settings );
