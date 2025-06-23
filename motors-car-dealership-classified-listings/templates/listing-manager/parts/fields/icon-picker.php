<div class="mvl-listing-manager-field" data-label="<?php echo esc_attr( $label ); ?>">
	<div class="mvl-listing-manager-field-info">
		<div class="mvl-listing-manager-field-title">
			<?php echo esc_html( $label ); ?>
			<?php if ( ! empty( $required ) ) : ?>
				<span class="mvl-listing-manager-field-required">*</span>
			<?php endif; ?>
		</div>
		<?php if ( isset( $description ) && ! empty( $description ) ) : ?>
			<div class="mvl-listing-manager-field-info-icon" mvl-tooltip-text="<?php echo esc_attr( $description ); ?>" mvl-tooltip-position="top" mvl-tooltip-toggle="mvl-listing-manager-field-description">
				<i class="motors-icons-mvl-info"></i>
			</div>
		<?php endif; ?>
	</div>
	<div class="mvl-icon-picker-container-inner">
		<button
			class="mvl-choose-icon mvl-secondary-btn<?php echo ! empty( $icon ) ? ' mvl-short-btn' : ''; ?>"
			type="button"
		>
			<i class="<?php echo ! empty( $icon ) ? esc_attr( $icon ) : 'fa fa-plus'; ?>"></i>
			<?php
			if ( empty( $icon ) ) {
				esc_html_e( 'Add Icon', 'stm_vehicles_listing' );
			}
			?>
		</button>
	</div>
	<input
		type="hidden"
		name="<?php echo esc_attr( $input_name ); ?>"
		value="<?php echo esc_attr( $icon ); ?>"
	/>
</div>
