<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! isset( $placeholder ) ) {
	if ( isset( $label ) ) {
		$placeholder = $label;
	}
}
?>
<label for="<?php echo esc_attr( $id ); ?>" class="mvl-listing-manager-field mvl-listing-manager-field-input" data-label="<?php echo esc_attr( $label ); ?>">
	<div class="mvl-listing-manager-field-title">
		<?php echo esc_html( $label ); ?>
	</div>
	<input
		id="<?php echo esc_attr( $id ); ?>"
		type="text"
		class="mvl-listing-manager-field-input mvl-input-field"
		name="<?php echo esc_attr( $input_name ); ?>" 
		placeholder="<?php echo isset( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
		value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>"
		<?php echo isset( $required ) && $required ? 'required' : ''; ?>
	/>
</label>
