<?php
$input_name = isset( $input_name ) ? $input_name : $id;
?>
<div class="mvl-listing-manager-field mvl-listing-manager-field-checkboxes <?php echo esc_attr( $class ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
	<div class="mvl-listing-manager-field-title">
		<?php echo esc_html( $label ); ?>
		<?php if ( isset( $required ) && $required ) : ?>
			<span class="mvl-listing-manager-field-required">*</span>
		<?php endif; ?>
	</div>
	<div class="mvl-listing-manager-field-checkboxes-wrapper">
		<?php
		foreach ( $options as $option ) :
			$is_selected = false;
			if ( ! empty( $selected ) && is_array( $selected ) ) {
				foreach ( $selected as $selected_option ) {
					if ( isset( $selected_option['value'] ) && $selected_option['value'] === $option['value'] ) {
						$is_selected = true;
						break;
					}
				}
			}
			?>
			<label class="mvl-listing-manager-field-checkbox">
				<input type="checkbox" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $is_selected, true ); ?>>
				<span><?php echo esc_html( $option['label'] ); ?></span>
			</label>
		<?php endforeach; ?>
	</div>
</div>
