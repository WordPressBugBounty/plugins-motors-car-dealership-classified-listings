<?php
use MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config;
use MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig;

mvl_enqueue_header_scripts_styles( 'motors-datetimepicker' );
$listing_id    = get_queried_object_id();
$listing_title = apply_filters( 'stm_generate_title_from_slugs', get_the_title( $listing_id ), $listing_id );

$form_html = apply_filters(
	'mvl_get_form_html',
	'',
	'test_drive',
	array(
		'listing_id'    => $listing_id,
		'listing_title' => $listing_title,
		'is_modal'      => true,
	)
);

$is_forms_editor       = apply_filters( 'mvl_is_addon_enabled', false, 'forms_editor' );
$use_template_directly = $is_forms_editor && ! empty( $form_html );
?>
<?php if ( ! $use_template_directly ) : ?>
<div class="modal" id="test-drive" tabindex="-1" role="dialog" aria-labelledby="myModalLabelTestDrive">
	<form id="request-test-drive-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header modal-header-iconed">
					<i class="motors-icons-steering_wheel"></i>
					<i class="modal-close motors-icons-close-times" data-dismiss="modal"></i>
					<h3 class="modal-title"
						id="myModalLabelTestDrive"><?php esc_html_e( 'Schedule a Test Drive', 'stm_vehicles_listing' ); ?></h3>
					<div class="test-drive-car-name"><?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?></div>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="form-modal-label"><?php esc_html_e( 'Name', 'stm_vehicles_listing' ); ?></div>
								<input name="name" type="text"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="form-modal-label"><?php esc_html_e( 'Email', 'stm_vehicles_listing' ); ?></div>
								<input name="email" type="email"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="form-modal-label"><?php esc_html_e( 'Phone', 'stm_vehicles_listing' ); ?></div>
								<input name="phone" type="tel"/>
							</div>
						</div>
						<div class="col-md-6 col-sm-6">
							<div class="form-group">
								<div class="form-modal-label" id="motors-best-time"><?php esc_html_e( 'Best time', 'stm_vehicles_listing' ); ?></div>
								<div class="stm-datepicker-input-icon">
									<input type="text" name="date" aria-label="<?php esc_attr_e( 'Best time', 'stm_vehicles_listing' ); ?>" aria-labelledby="motors-best-time" class="stm-date-timepicker" autocomplete="Off"/>
								</div>
							</div>
						</div>
					</div>
					<div class="mg-bt-25px"></div>
					<div class="row">
						<div class="col-md-7 col-sm-7"></div>
						<div class="col-md-5 col-sm-5">
							<button type="submit" class="stm-request-test-drive">
								<?php esc_html_e( 'Request', 'stm_vehicles_listing' ); ?>
							</button>
							<div class="stm-ajax-loader" style="margin-top:10px;">
								<i class="motors-icons-load1"></i>
							</div>
						</div>
					</div>
					<div class="mg-bt-25px"></div>
					<input name="vehicle_id" type="hidden" value="<?php echo esc_attr( get_queried_object_id() ); ?>" />
					<input name="vehicle_name" type="hidden" value="<?php echo esc_attr( get_the_title( get_queried_object_id() ) ); ?>" />
					<div class="modal-body-message"></div>
				</div>
			</div>
		</div>
	</form>
</div>
<?php else : ?>
	<?php
	// Load template directly to preserve scripts (reCAPTCHA, etc.)
	if ( class_exists( '\MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config' ) && class_exists( '\MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig' ) ) {
		/** @var \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig|null $form_config */
		$form_config = \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config::instance_of( 'test_drive' );
		if ( $form_config ) {
			$form_data    = $form_config->data();
			$saved_values = $form_config->get_values();
			$fields       = $form_data['fields'] ?? array();

			$template_data = array(
				'form_slug'    => 'test_drive',
				'args'         => array(
					'listing_id'    => $listing_id,
					'listing_title' => $listing_title,
					'is_modal'      => true,
				),
				'fields'       => $fields,
				'saved_values' => $saved_values,
			);

			do_action( 'stm_listings_load_template', 'addons/forms-editor/page/partials/forms/test-drive', $template_data );
		}
	}
	?>
<?php endif; ?>
