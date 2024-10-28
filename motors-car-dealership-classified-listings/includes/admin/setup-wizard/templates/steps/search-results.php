<?php
$settings        = apply_filters( 'mvl_setup_wizard_data', array() );
$pro_class       = ( ! $settings['use_pro'] ) ? 'disabled' : '';
$plugin_settings = array(
	'listing_filter_position'   => apply_filters( 'motors_vl_get_nuxy_mod', 'left', 'listing_filter_position' ),
	'listing_view_type'         => apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' ),
	'show_listing_compare'      => apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_compare' ),
	'enable_favorite_items'     => apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_favorite_items' ),
	'price_currency_name'       => apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency_name' ),
	'price_currency'            => apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency' ),
	'price_currency_position'   => apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'price_currency_position' ),
	'price_delimeter'           => apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_delimeter' ),
	'gallery_hover_interaction' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' ),
);
?>
<div class="mvl-welcome-content-body">
	<h2>4. Search results page settings</h2>
	<p>Customize filter bar position, default view (grid/list), and options for compare and favorites buttons. Adjust currency settings and image sliding on hover (Pro) to suit your needs.</p>
	<p><em>You can change these settings anytime after setup.</em></p>

	<form class="mvl-settings-form" id="mvl-settings-form">

		<?php if ( ! is_plugin_active( 'elementor/elementor.php' ) ) : ?>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3>Filter bar position</h3>
				</div>
				<p>Set the position of the filter bar to optimize user experience on your listings page.</p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-radio">
					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_filter_position" value="left" <?php do_action( 'mvl_check_if', 'listing_filter_position', 'left' ); ?>>
								<span class="label">Left</span>
								<span class="option-preview">
									<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/filter-left.jpg" />
								</span>
							</span>
						</label>
					</div>
					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_filter_position" value="right" <?php do_action( 'mvl_check_if', 'listing_filter_position', 'right' ); ?>>
								<span class="label">Right</span>
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
					<h3>Default desktop view for the listing page</h3>
				</div>
				<p>Choose how you want to display your listing page by default on desktop.</p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-radio">

					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_view_type" value="grid" <?php do_action( 'mvl_check_if', 'listing_view_type', 'grid' ); ?>>
								<span class="label">Grid</span>
								<span class="option-preview">
									<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/grid.png" />
								</span>
							</span>
						</label>
					</div>

					<div class="mvl-settings-form-radio-item">
						<label>
							<span class="option-with-preview">
								<input type="radio" name="listing_view_type" value="list" <?php do_action( 'mvl_check_if', 'listing_view_type', 'list' ); ?>>
								<span class="label">List</span>
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
					<h3>Compare button</h3>
					<span class="setting-preview">
						Preview
						<span class="preview-tooltip">
							<div class="image">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-compare.png" width="200" height="209" alt="" />
							</div>
						</span>
					</span>
				</div>
				<p>The listing will have a separate button so that users can compare the separate vehicles.</p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-toggle">
					<label>
						<input type="checkbox" name="show_listing_compare" <?php do_action( 'mvl_check_if', 'show_listing_compare' ); ?>/>
						<span><i></i></span>
					</label>
				</div>

			</div>

		</div>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3>Add to favorites button</h3>
					<span class="setting-preview">
						Preview
						<span class="preview-tooltip">
							<div class="image">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-favorite.png" width="200" height="209" alt="" />
							</div>
						</span>
					</span>
				</div>
				<p>When hovering over the listing image users will have the option to save the listing to their favorites.</p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-toggle">
					<label>
						<input type="checkbox" name="enable_favorite_items" <?php do_action( 'mvl_check_if', 'enable_favorite_items' ); ?>/>
						<span><i></i></span>
					</label>
				</div>

			</div>

		</div>

		<div class="mvl-settings-form-group">

			<div class="mvl-settings-form-group-aside">
				<div class="setting-heading">
					<h3>Currency settings</h3>
				</div>
				<p>Configure the currency options for your listings to match your preferred format and locale.</p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-row">
					<div class="mvl-settings-form-field">
						<label>Currency Name</label>
						<div class="form-input">
							<input type="text" name="price_currency_name" value="<?php echo esc_attr( $plugin_settings['price_currency_name'] ); ?>" />
						</div>
					</div>
					<div class="mvl-settings-form-field">
						<label>Currency Symbol</label>
						<div class="form-input">
							<input type="text" name="price_currency" value="<?php echo esc_attr( $plugin_settings['price_currency'] ); ?>" />
						</div>
					</div>
				</div>

				<div class="mvl-settings-form-row">
					<div class="mvl-settings-form-field">
						<label>Currency Position</label>
						<div class="mvl-settings-form-radio">
							<div class="mvl-settings-form-radio-item">
								<label>
									<input type="radio" name="price_currency_position" value="left" <?php do_action( 'mvl_check_if', 'price_currency_position', 'left' ); ?>>
									<span class="">Left</span>
								</label>
							</div>
							<div class="mvl-settings-form-radio-item">
								<label>
									<input type="radio" name="price_currency_position" value="right" <?php do_action( 'mvl_check_if', 'price_currency_position', 'right' ); ?>>
									<span class="">Right</span>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="mvl-settings-form-row">
					<div class="mvl-settings-form-field">
						<label>Decimal & thousands separators</label>
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
					<h3>Image sliding on Hover</h3>
					<span class="badge-pro">PRO</span>
					<span class="setting-preview">
						Preview
						<span class="preview-tooltip">
							<div class="image">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/preview-carousel.png" width="200" height="209" alt="" />
							</div>
						</span>
					</span>
				</div>
				<p>When hovering the listing images will be previewed as a slider.</p>
			</div>

			<div class="mvl-settings-form-group-content">

				<div class="mvl-settings-form-toggle">
					<label>
						<input type="checkbox" name="gallery_hover_interaction" <?php do_action( 'mvl_check_if', 'gallery_hover_interaction' ); ?>/>
						<span><i></i></span>
					</label>
				</div>

			</div>

		</div>

	</form>

</div>
<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'demo-content' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="demo-content">Back</a>
	</div>
	<div>
		<?php
		$next_step_slug = 'single-listing';
		if ( ! defined( 'MOTORS_STARTER_THEME_VERSION' ) && ! defined( 'ELEMENTOR_VERSION' ) ) {
			$next_step_slug = 'profile';
		}
		?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', $next_step_slug ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="<?php echo esc_attr( $next_step_slug ); ?>">Next Step</a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields', $settings );
