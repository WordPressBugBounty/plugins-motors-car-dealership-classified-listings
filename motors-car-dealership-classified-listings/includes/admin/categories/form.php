<?php
/**
 * @var string $form_type
 * */

/* Get options to show */
$groups       = motors_page_options_groups();
$dependencies = $groups['dependency'];
unset( $groups['dependency'] );
$groups       = array_values( $groups );
$_title       = __( 'Add new field', 'stm_vehicles_listing' );
$save_button  = __( 'Save', 'stm_vehicles_listing' );
$form_classes = 'stm_custom_fields__form';
$button_type  = 'add_new';
$button_attr  = 'disabled';
$prefix_id    = '';

if ( 'edit' === $form_type ) {
	$_title        = __( 'Edit', 'stm_vehicles_listing' );
	$form_classes .= ' stm_custom_fields__form--edit';
	$save_button   = __( 'Save changes', 'stm_vehicles_listing' );
	$button_type   = 'save';
	$prefix_id     = $form_type . '-';
}
?>

<form class="<?php echo esc_attr( $form_classes ); ?>" data-form="<?php echo esc_html( $form_type ); ?>" method="POST">
	<div class="stm_custom_fields__form--top">
		<h3><?php echo esc_html( $_title ); ?></h3>
		<?php if ( 'edit' === $form_type ) : ?>
			<?php if ( 'yes' !== get_user_meta( get_current_user_id(), 'stm_edit_field_disable_notification', true ) ) : ?>
				<div class="stm-admin-notification" id="stm-admin-notification">
					<div class="stm-admin-notification__body">
						<div class="stm-admin-notification__title">
							<?php esc_html_e( 'Configuration Required', 'stm_vehicles_listing' ); ?>
						</div>
						<div class="stm-admin-notification__content">
							<?php esc_html_e( 'Please configure the options to ensure each field works correctly. You can edit or remove existing taxonomies.', 'stm_vehicles_listing' ); ?>
							<a href="https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/listing-management/custom-fields#how-to-deal-with-taxonomies-on-the-custom-fields-listing-categories"
								target="_blank" class="stm-admin-notification__link">
								<?php esc_html_e( 'Learn more', 'stm_vehicles_listing' ); ?>
							</a>
						</div>
						<div class="stm-admin-notification__bottom">
							<button type="button"
									class="stm-admin-button stm-admin-button-primary stm-admin-notification__button">
								<?php esc_html_e( 'Got it', 'stm_vehicles_listing' ); ?>
							</button>
							<div class="stm_custom_fields__checkbox--wrapper">
								<input type="checkbox" id="dont-show-again" class="stm_custom_fields__checkbox">
								<label for="dont-show-again">
									<span class="stm_custom_fields__checkbox--box">
										<i class="stm-admin-icon-check"></i>
									</span>
									<?php esc_html_e( 'Don’t show again', 'stm_vehicles_listing' ); ?>
								</label>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<a href="#" target="_blank" class="stm-admin-button stm-admin-button__configure">
				<?php esc_html_e( 'Manage Options', 'stm_vehicles_listing' ); ?>
				<i class="stm-admin-icon-next"></i>
			</a>
		<?php endif; ?>
	</div>
	<div class="stm_custom_fields__accordion">
		<?php
		foreach ( $groups as $group_key => $group ) :
			$accordion_classes = 'stm_custom_fields__accordion--item';
			$content_attr      = '';

			if ( 0 === $group_key ) {
				$accordion_classes .= ' opened';
				$content_attr       = 'style="display: flex;"';
			} else {
				$content_attr = 'style="display: none;"';
			}
			?>
			<div class="<?php echo esc_attr( $accordion_classes ); ?>">
				<div class="stm_custom_fields__accordion--top">
					<h4><?php echo esc_html( $group['label'] ); ?></h4>
					<button class="stm_custom_fields__accordion--button">
						<i class="stm-admin-icon-arrow-top"></i>
					</button>
				</div>
				<div class="stm_custom_fields__accordion--content" <?php echo wp_kses_post( $content_attr ); ?>>
					<?php
					foreach ( $group['items'] as $field_key => $field ) :
						require STM_LISTINGS_PATH . '/includes/admin/categories/field.php';

						if ( array_key_exists( $field_key, $dependencies ) ) :
							?>
							<div class="stm_custom_fields__dependency" data-slug="<?php echo esc_attr( $field_key ); ?>" style="display: none;">
								<?php
								foreach ( $dependencies[ $field_key ] as $_field_key => $_field ) {
									$field_key = $_field_key;
									$field     = $_field;
									require STM_LISTINGS_PATH . '/includes/admin/categories/field.php';
								}
								?>
							</div>
							<?php
						endif;
					endforeach;
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="stm_custom_fields__form--bottom">
		<div class="stm_custom_fields__message hide"></div>
		<button type="button"
				data-action="<?php echo esc_attr( $button_type ); ?>" <?php echo wp_kses_post( $button_attr ); ?>
				class="stm-admin-button stm-admin-button-primary stm-admin-button-save">
			<?php echo esc_html( $save_button ); ?>
			<i class="lnr lnr-sync"></i>
		</button>
		<button type="button" data-action="cancel" class="stm-admin-button stm-admin-button-cancel">
			<?php esc_html_e( 'Cancel', 'stm_vehicles_listing' ); ?>
		</button>
		<?php if ( 'edit' === $form_type ) : ?>
			<button type="button" data-action="delete" class="stm-admin-button stm-admin-button-important">
				<?php esc_html_e( 'Delete', 'stm_vehicles_listing' ); ?>
			</button>
		<?php endif; ?>
	</div>
</form>
