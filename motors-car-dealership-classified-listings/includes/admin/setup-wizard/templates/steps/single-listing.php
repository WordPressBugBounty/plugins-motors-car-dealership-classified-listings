<?php
$settings     = apply_filters( 'mvl_setup_wizard_data', array() );
$pro_class    = ( ! $settings['use_pro'] ) ? 'disabled' : '';
$template_ids = array(
	'classic'          => apply_filters( 'mvl_get_template_id_by_slug', 'listing-template-1' ),
	'modern'           => apply_filters( 'mvl_get_template_id_by_slug', 'modern' ),
	'mosaic-gallery'   => apply_filters( 'mvl_get_template_id_by_slug', 'mosaic-gallery' ),
	'carousel-gallery' => apply_filters( 'mvl_get_template_id_by_slug', 'carousel-gallery' ),
);
?>
	<div class="mvl-welcome-content-body">
		<h2>5. Listing details page</h2>
		<p>Choose a template to customize the view of your listing. This will define how your listing information is presented to users.</p>
		<p><em>You can change these settings anytime after setup.</em></p>

		<form class="mvl-settings-form" id="mvl-settings-form">

			<div class="mvl-settings-form-radio wide">

				<div class="mvl-settings-form-radio-item">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="single_listing_template" value="<?php echo esc_attr( $template_ids['classic'] ); ?>" <?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['classic'] ); ?>>
							<span class="label">Classic</span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-default.png" />
							</span>
						</span>
					</label>
				</div>

				<div class="mvl-settings-form-radio-item <?php echo esc_attr( $pro_class ); ?>">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="single_listing_template" value="<?php echo esc_attr( $template_ids['modern'] ); ?>" <?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['modern'] ); ?>>
							<span class="label">Modern <span class="badge-pro">PRO</span></span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-modern.png" />
							</span>
						</span>
					</label>
				</div>

				<div class="mvl-settings-form-radio-item <?php echo esc_attr( $pro_class ); ?>">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="single_listing_template" value="<?php echo esc_attr( $template_ids['mosaic-gallery'] ); ?>" <?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['mosaic-gallery'] ); ?>>
							<span class="label">Mosaic <span class="badge-pro">PRO</span></span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-mosaic.png" />
							</span>
						</span>
					</label>
				</div>

				<div class="mvl-settings-form-radio-item <?php echo esc_attr( $pro_class ); ?>">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="single_listing_template" value="<?php echo esc_attr( $template_ids['carousel-gallery'] ); ?>" <?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['carousel-gallery'] ); ?>>
							<span class="label">Carousel <span class="badge-pro">PRO</span></span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-carousel.png" />
							</span>
						</span>
					</label>
				</div>

			</div>

		</form>
	</div>
	<div class="mvl-welcome-nav-actions">
		<div>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'search-results' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="search-results">Back</a>
		</div>
		<div>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'profile' ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="profile">Next Step</a>
		</div>
	</div>

<?php
do_action( 'mvl_setup_wizard_data_fields', $settings );
