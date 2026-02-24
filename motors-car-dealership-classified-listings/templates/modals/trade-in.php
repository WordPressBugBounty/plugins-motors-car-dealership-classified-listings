<?php
$form_html = apply_filters( 'mvl_get_form_html', '', 'trade_in' );

// Check if FormsEditor is enabled and form exists
$is_forms_editor       = apply_filters( 'mvl_is_addon_enabled', false, 'forms_editor' );
$use_template_directly = $is_forms_editor && ! empty( $form_html );
?>
<?php if ( ! $use_template_directly ) : ?>
<div class="modal" id="trade-in" tabindex="-1" role="dialog" aria-labelledby="myModalLabelTradeIn">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div id="request-trade-in-offer">
				<div class="modal-header modal-header-iconed">
					<i class="modal-close motors-icons-close-times" data-dismiss="modal"></i>
					<i class="motors-icons-trade"></i>
					<h3 class="modal-title" id="myModalLabelTradeIn">
						<?php esc_html_e( 'Trade in', 'stm_vehicles_listing' ); ?>
					</h3>
					<div class="test-drive-car-name">
						<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_queried_object_id() ), get_queried_object_id() ) ); ?>
					</div>
					<div class="mobile-close-modal" data-dismiss="modal" aria-label="Close">
					</div>
				</div>
				<div class="modal-body">
					<?php do_action( 'stm_listings_load_template', 'trade-in', array( 'is_modal' => true ) ); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function($) {
		"use strict";

		$(document).ready(function(){
			if(window.location.hash === '#error-fields') {
				$('#trade-in').modal('show');
				history.pushState("", document.title, window.location.pathname + window.location.search);
			}
		});

	})(jQuery);
</script>
<?php else : ?>
	<?php
	// Load template directly to preserve scripts (reCAPTCHA, etc.)
	if ( class_exists( '\MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config' ) && class_exists( '\MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig' ) ) {
		/** @var \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig|null $form_config */
		$form_config = \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config::instance_of( 'trade_in' );
		if ( $form_config ) {
			$form_data    = $form_config->data();
			$saved_values = $form_config->get_values();
			$fields       = $form_data['fields'] ?? array();

			// Extract steps from steps_wizard field
			$steps = array();
			foreach ( $fields as $field_id => $field_config ) {
				if ( isset( $field_config['type'] ) && 'steps_wizard' === $field_config['type'] ) {
					if ( isset( $saved_values[ $field_id ]['steps'] ) ) {
						$steps = $saved_values[ $field_id ]['steps'];
					} elseif ( isset( $field_config['steps'] ) ) {
						$steps = $field_config['steps'];
					}
					break;
				}
			}

			$template_data = array(
				'form_slug'    => 'trade_in',
				'form_config'  => $form_config,
				'args'         => array(
					'is_modal' => true,
				),
				'fields'       => $fields,
				'saved_values' => $saved_values,
				'steps'        => $steps,
			);

			do_action( 'stm_listings_load_template', 'addons/forms-editor/page/partials/forms/trade-in', $template_data );
		}
	}
	?>
<?php endif; ?>
