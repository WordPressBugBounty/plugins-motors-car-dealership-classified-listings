<?php
$settings           = apply_filters( 'mvl_setup_wizard_data', array() );
$pro_class          = ( ! $settings['use_pro'] ) ? 'disabled' : '';
$templates_imported = ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'single_listing_template' ) || apply_filters( 'mvl_get_template_id_by_slug', 'listing-template-1' ) );
$check_default      = ( ! $templates_imported ) ? ' checked' : '';
$input_name         = ( $templates_imported ) ? 'single_listing_template' : 'single_listing_template_name';
$template_ids       = array(
	'classic'          => $templates_imported ? apply_filters( 'mvl_get_template_id_by_slug', 'listing-template-1' ) : 'listing-template-1',
	'modern'           => $templates_imported ? apply_filters( 'mvl_get_template_id_by_slug', 'modern' ) : 'modern',
	'mosaic-gallery'   => $templates_imported ? apply_filters( 'mvl_get_template_id_by_slug', 'mosaic-gallery' ) : 'mosaic-gallery',
	'carousel-gallery' => $templates_imported ? apply_filters( 'mvl_get_template_id_by_slug', 'carousel-gallery' ) : 'carousel-gallery',
);
?>
	<div class="mvl-welcome-content-body">
		<h2><?php echo esc_html__( '6. Listing details page', 'stm_vehicles_listing' ); ?></h2>
		<p><?php echo esc_html__( 'Choose a template to customize the view of your listing. This will define how your listing information is presented to users.', 'stm_vehicles_listing' ); ?></p>
		<p><em><?php echo esc_html__( 'You can change these settings anytime after setup.', 'stm_vehicles_listing' ); ?></em></p>

		<form class="mvl-settings-form" id="mvl-settings-form">

			<div class="mvl-settings-form-radio wide">

				<div class="mvl-settings-form-radio-item">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="<?php echo esc_attr( $input_name ); ?>"
								value="<?php echo esc_attr( $template_ids['classic'] ); ?>"
								<?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['classic'] ); ?>
								<?php echo esc_attr( $check_default ); ?>
							>
							<span class="label"><?php echo esc_html__( 'Classic', 'stm_vehicles_listing' ); ?></span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-default.png" />
								<span class="option-preview-links">
									<a href="https://motors-plugin.stylemixthemes.com/listings/ferrari-f40/" target="_blank" class="button">Preview</a>
								</span>
							</span>
						</span>
					</label>
				</div>

				<div class="mvl-settings-form-radio-item <?php echo esc_attr( $pro_class ); ?>">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $template_ids['modern'] ); ?>" <?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['modern'] ); ?>>
							<span class="label">
								<?php echo esc_html__( 'Modern', 'stm_vehicles_listing' ); ?>
								<span class="badge-pro"><?php echo esc_html__( 'PRO', 'stm_vehicles_listing' ); ?></span>
							</span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-modern.png" />
								<span class="option-preview-links">
									<a href="https://motors-plugin.stylemixthemes.com/listings/mercedes-benz-e63/?mvl=pro" target="_blank" class="button">Preview</a>
								</span>
							</span>
						</span>
					</label>
				</div>

				<div class="mvl-settings-form-radio-item <?php echo esc_attr( $pro_class ); ?>">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $template_ids['mosaic-gallery'] ); ?>" <?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['mosaic-gallery'] ); ?>>
							<span class="label">
								<?php echo esc_html__( 'Mosaic', 'stm_vehicles_listing' ); ?>
								<span class="badge-pro"><?php echo esc_html__( 'PRO', 'stm_vehicles_listing' ); ?></span>
							</span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-mosaic.png" />
								<span class="option-preview-links">
									<a href="https://motors-plugin.stylemixthemes.com/listings/nissan-gtr-r34/?mvl=pro" target="_blank" class="button">Preview</a>
								</span>
							</span>
						</span>
					</label>
				</div>

				<div class="mvl-settings-form-radio-item <?php echo esc_attr( $pro_class ); ?>">
					<label>
						<span class="option-with-preview">
							<input type="radio" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $template_ids['carousel-gallery'] ); ?>" <?php do_action( 'mvl_check_if', 'single_listing_template', $template_ids['carousel-gallery'] ); ?>>
							<span class="label">
								<?php echo esc_html__( 'Carousel', 'stm_vehicles_listing' ); ?>
								<span class="badge-pro"><?php echo esc_html__( 'PRO', 'stm_vehicles_listing' ); ?></span>
							</span>
							<span class="option-preview">
								<img src="<?php echo esc_url( STM_LISTINGS_URL ); ?>/includes/admin/setup-wizard/images/template-carousel.png" />
								<span class="option-preview-links">
									<a href="https://motors-plugin.stylemixthemes.com/listings/bmw-m5-f90/?mvl=pro" target="_blank" class="button">Preview</a>
								</span>
							</span>
						</span>
					</label>
				</div>

			</div>

		</form>
	</div>
	<div class="mvl-welcome-nav-actions">
		<div>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'plugins' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="plugins">
				<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
			</a>
		</div>
		<div>
			<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'demo-content' ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="demo-content">
				<?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?>
			</a>
		</div>
	</div>

<?php
do_action( 'mvl_setup_wizard_data_fields', $settings );
