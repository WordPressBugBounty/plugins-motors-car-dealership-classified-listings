<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! isset( $placeholder ) ) {
	if ( isset( $label ) ) {
		$placeholder = $label;
	}
}

// Устанавливаем значение по умолчанию для $label если не задано
if ( ! isset( $label ) ) {
	$label = '';
}
?>
<div class="mvl-listing-manager-field mvl-listing-manager-field-color">
	<?php if ( ! empty( $label ) ) : ?>
	<div class="mvl-listing-manager-field-title">
		<?php echo esc_html( $label ); ?>
	</div>
	<?php endif; ?>
	<div class="mvl-listing-manager-field-color-input-wrapper">
		<div
			type="color"
			class="mvl-listing-manager-field-color-input"
		></div>
		<input type="text" name="<?php echo esc_attr( $input_name ); ?>" id="<?php echo esc_attr( $id ); ?>" class="mvl-listing-manager-field-input mvl-input-field" value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>" />
	</div>
</div>
