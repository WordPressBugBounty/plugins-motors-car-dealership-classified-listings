<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! isset( $input_name ) ) {
	$input_name = $id;
}
?>
<div class="mvl-listing-manager-field mvl-listing-manager-select-field <?php echo ( isset( $disabled ) && $disabled ) ? ' disabled' : ''; ?>" data-field-id="<?php echo esc_attr( $id ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
	<div class="mvl-listing-manager-field-info">
		<div class="mvl-listing-manager-field-title">
			<?php echo esc_html( $label ); ?>
			<?php if ( isset( $required ) && $required ) : ?>
				<span class="mvl-listing-manager-field-required">*</span>
			<?php endif; ?>
		</div>
		<?php if ( isset( $description ) && ! empty( $description ) ) : ?>
			<div class="mvl-listing-manager-field-info-icon" mvl-tooltip-text="<?php echo esc_attr( $description ); ?>" mvl-tooltip-position="top" mvl-tooltip-toggle="mvl-listing-manager-field-description">
				<i class="motors-icons-mvl-info"></i>
			</div>
		<?php endif; ?>
	</div>
	<select
		name="<?php echo esc_attr( $input_name ); ?>"
		id="<?php echo esc_attr( $id ); ?>"
		class="mvl-listing-manager-field-select mvl-select-field<?php echo ( isset( $dropdown ) && 'checkboxes' === $dropdown ) ? ' mvl-select-field-checkboxes' : ''; ?>"
		<?php if ( isset( $multiple ) && $multiple ) : ?>
			data-multiple="true"
			multiple="multiple"
		<?php endif; ?>
	>
		<?php if ( isset( $placeholder ) && ! empty( $placeholder ) ) : ?>
			<option value="" class="mvl-select-field-placeholder">
				<?php echo esc_html( $placeholder ); ?>
			</option>
		<?php endif; ?>
		<?php if ( ! empty( $options ) ) : ?>
			<?php foreach ( $options as $option ) : ?>
				<?php
				$slug        = isset( $option['slug'] ) ? $option['slug'] : '';
				$name        = isset( $option['name'] ) ? $option['name'] : '';
				$is_selected = false;
				if ( isset( $multiple ) && $multiple && is_array( $value ) ) {
					$is_selected = in_array( $slug, $value, true );
				} else {
					$is_selected = ( $value === $slug );
				}
				?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $is_selected, true ); ?>>
					<?php echo esc_html( $name ); ?>
				</option>
			<?php endforeach; ?>
		<?php endif; ?>
	</select>
</div>
