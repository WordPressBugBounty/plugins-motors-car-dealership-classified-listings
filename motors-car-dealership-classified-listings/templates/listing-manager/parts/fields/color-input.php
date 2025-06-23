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
<div class="mvl-listing-manager-field mvl-listing-manager-field-color mvl-color-input">
	<div class="mvl-listing-manager-field-title">
		<?php echo esc_html( $label ); ?>
	</div>
	<div class="mvl-listing-manager-field-color-input-wrapper">
		<div
			type="color"
			class="mvl-listing-manager-field-color-input"
			name="<?php echo esc_attr( $input_name ); ?>" 
			value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>"
			<?php echo isset( $required ) && $required ? 'required' : ''; ?>
		></div>
		<input type="hidden" 
			name="<?php echo esc_attr( $term_meta ); ?>" 
			class="mvl-listing-manager-field-input mvl-input-field" 
			value="<?php echo isset( $value ) ? esc_attr( $value ) : ''; ?>"
			data-slug="<?php echo esc_attr( $slug ); ?>"
		/>
		<input
			type="<?php echo esc_attr( isset( $type ) && $type ? $type : 'text' ); ?>"
			class="mvl-listing-manager-field-input mvl-input-field"
			name="<?php echo esc_attr( $id ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo isset( $numeric ) && $numeric ? 'step="' . esc_attr( 'any' ) . '"' : ''; ?>
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			data-slug="<?php echo esc_attr( $slug ); ?>"
			autocomplete="off"
			<?php echo isset( $required ) && $required ? 'required' : ''; ?>
		/>
		<?php if ( isset( $button ) && $button ) : ?>
			<button class="mvl-listing-manager-field-button <?php echo esc_attr( $button['class'] ); ?>" id="<?php echo esc_attr( $button['id'] ); ?>">
				<?php echo esc_html( $button['text'] ); ?>
				<i class="<?php echo esc_attr( $button['icon'] ); ?>"></i>
			</button>
		<?php endif; ?>
	</div>
</div>
