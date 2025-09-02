<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! isset( $input_name ) ) {
	$input_name = $id;
}
if ( ! isset( $slug ) ) {
	$slug = $id;
}

if ( ! isset( $class ) ) {
	$class = '';
}

if ( ! isset( $tooltip_position ) ) {
	$tooltip_position = 'top';
}

$value = isset( $value ) ? $value : '';

?>
<div class="mvl-listing-manager-field mvl-listing-manager-field-input mvl-listing-manager-field-input-<?php echo esc_attr( $id ); ?>" data-field-id="<?php echo esc_attr( $id ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
	<div class="mvl-listing-manager-field-info">
		<div class="mvl-listing-manager-field-title">
			<?php echo esc_html( $label ); ?>
			<?php if ( isset( $required ) && $required ) : ?>
				<span class="mvl-listing-manager-field-required">*</span>
			<?php endif; ?>
		</div>
		<?php if ( isset( $description ) && ! empty( $description ) ) : ?>
			<div class="mvl-listing-manager-field-info-icon" mvl-tooltip-text="<?php echo esc_attr( $description ); ?>" mvl-tooltip-position="<?php echo esc_attr( $tooltip_position ); ?>" mvl-tooltip-toggle="mvl-listing-manager-field-description">
				<i class="motors-icons-mvl-info"></i>
			</div>
		<?php endif; ?>
	</div>
	<div class="mvl-listing-manager-field-input-wrapper <?php echo esc_attr( $class ); ?>">
		<input
			type="<?php echo esc_attr( isset( $type ) && $type && 'date' !== $type ? $type : 'text' ); ?>"
			class="mvl-listing-manager-field-input mvl-input-field"
			name="<?php echo esc_attr( $input_name ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo isset( $numeric ) && $numeric ? 'step="' . esc_attr( 'any' ) . '"' : ''; ?>
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			<?php if ( isset( $slug ) ) : ?>
				data-slug="<?php echo esc_attr( $slug ); ?>"
			<?php endif; ?>
			<?php echo isset( $required ) && $required ? 'required' : ''; ?>
			<?php echo isset( $autocomplete ) && $autocomplete ? 'autocomplete="' . esc_attr( $autocomplete ) . '"' : ''; ?>
			<?php if ( isset( $attributes ) && is_array( $attributes ) ) : ?>
				<?php foreach ( $attributes as $attribute => $value ) : ?>
					<?php echo esc_attr( $attribute ); ?>="<?php echo esc_attr( $value ); ?>"
				<?php endforeach; ?>
			<?php endif; ?>
		/>
		<?php if ( isset( $hidden_field ) && $hidden_field ) : ?>
			<input type="hidden" name="<?php echo esc_attr( $hidden_field ); ?>" value="<?php echo esc_attr( $hidden_field_value ); ?>">
		<?php endif; ?>
		<?php if ( isset( $type ) && 'date' === $type ) : ?>
			<input type="date" id="mvl-listing-manager-field-input-<?php echo esc_attr( $id ); ?>-date" class="mvl-listing-manager-field-date-input" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo isset( $required ) && $required ? 'required' : ''; ?>>
		<?php endif; ?>
		<?php if ( isset( $button ) && $button ) : ?>
			<?php
			$buttons = is_array( $button ) && isset( $button['text'] ) ? array( $button ) : $button;
			foreach ( $buttons as $btn ) :
				?>
				<button class="mvl-listing-manager-field-button <?php echo esc_attr( $btn['class'] ); ?>" id="<?php echo esc_attr( $btn['id'] ); ?>">
					<?php if ( isset( $btn['icon'] ) ) : ?>
						<i class="<?php echo esc_attr( $btn['icon'] ); ?>"></i>
					<?php endif; ?>
					<?php echo esc_html( $btn['text'] ); ?>
				</button>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
