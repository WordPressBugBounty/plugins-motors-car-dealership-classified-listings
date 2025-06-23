<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="mvl-listing-manager-field mvl-listing-manager-field-switch" data-label="<?php echo esc_attr( $label ); ?>">
	<input type="checkbox" class="mvl-listing-manager-field-switch-input" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="1" <?php echo $value ? 'checked' : ''; ?>>
	<label class="mvl-listing-manager-field-switch-label" for="<?php echo esc_attr( $id ); ?>">
		<span class="mvl-listing-manager-field-switch-slider"></span>
		<div class="mvl-listing-manager-field-switch-label-text-wrapper">
			<div class="mvl-listing-manager-field-info">
				<span class="mvl-listing-manager-field-switch-label-text">
					<?php echo esc_html( $label ); ?>
				</span>
				<?php if ( isset( $description ) && ! empty( $description ) ) : ?>
					<div class="mvl-listing-manager-field-info-icon" mvl-tooltip-text="<?php echo esc_attr( $description ); ?>" mvl-tooltip-position="top" mvl-tooltip-toggle="mvl-listing-manager-field-description">
						<i class="motors-icons-mvl-info"></i>
					</div>
				<?php endif; ?>
				<?php if ( isset( $preview ) && ! empty( $preview ) ) : ?>
					<div class="mvl-listing-manager-content-body-page-preview-wrapper" mvl-tooltip-image="<?php echo esc_url( $preview ); ?>" mvl-tooltip-position="bottom" mvl-tooltip-toggle="mvl-listing-manager-content-body-page-preview-img">
						<div class="mvl-listing-manager-content-body-page-preview">
							<i class="motors-icons-mvl-eye"></i>
							<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( isset( $description_bottom ) && $description_bottom ) : ?>
			<div class="mvl-listing-manager-field-description">
				<?php echo esc_html( $description_bottom ); ?>
			</div>
			<?php endif; ?>
		</div>
	</label>
</div>
