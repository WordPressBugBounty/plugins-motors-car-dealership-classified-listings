<?php
// Start of Selection
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$options    = isset( $options ) ? $options : array();
$value      = isset( $value ) ? $value : '';
$input_name = isset( $input_name ) ? $input_name : '';
$id         = isset( $id ) ? $id : '';
$label      = isset( $label ) ? $label : '';
$image      = isset( $image ) ? $image : '';
$class      = isset( $class ) ? $class : '';
$disabled   = isset( $disabled ) ? $disabled : false;
?>
<div class="mvl-listing-manager-field-radio-container <?php echo esc_attr( $class ); ?>" data-label="<?php echo esc_attr( $label ); ?>">
	<div class="mvl-listing-manager-field-title">
		<?php echo esc_html( $label ); ?>
	</div>
	<div class="mvl-listing-manager-field-radio-items <?php echo esc_attr( $class ); ?>">
		<?php if ( ! empty( $options ) ) : ?>
			<?php foreach ( $options as $option_value => $option_data ) : ?>
				<?php
				$option_label      = is_array( $option_data ) ? $option_data['label'] : $option_data;
				$option_image      = is_array( $option_data ) && isset( $option_data['image'] ) ? $option_data['image'] : '';
				$option_value_attr = ( isset( $bool ) && $bool && is_array( $option_data ) && isset( $option_data['value'] ) ) ? $option_data['value'] : $option_value;
				?>
				<div class="mvl-listing-manager-field mvl-listing-manager-field-radio">
					<input type="radio" class="mvl-listing-manager-field-radio-input"
							id="<?php echo esc_attr( $id . '_' . $option_value ); ?>"
							name="<?php echo esc_attr( $input_name ); ?>"
							value="<?php echo esc_attr( $option_value_attr ); ?>"
							<?php checked( $value, $option_value_attr ); ?>>
					<label class="mvl-listing-manager-field-radio-label" for="<?php echo esc_attr( $id . '_' . $option_value ); ?>">
						<?php if ( ! empty( $option_image ) ) : ?>
							<span class="mvl-listing-manager-field-radio-image">
								<img src="<?php echo esc_url( $option_image ); ?>" alt="<?php echo esc_attr( $option_label ); ?>">
							</span>
							<?php if ( $disabled ) : ?>
								<span class="mvl-listing-manager-field-radio-disabled-label">
									<?php echo esc_html( $option_label ); ?>
								</span>
							<?php endif; ?>
						<?php endif; ?>
						<div class="mvl-listing-manager-field-radio-checker">
							<span class="mvl-listing-manager-field-radio-slider"></span>
							<?php echo esc_html( $option_label ); ?>
						</div>
					</label>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="mvl-listing-manager-field mvl-listing-manager-field-radio">
				<?php if ( ! empty( $image ) ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $label ); ?>">
				<?php endif; ?>
				<input type="radio" class="mvl-listing-manager-field-radio-input"
						id="<?php echo esc_attr( $id ); ?>"
						name="<?php echo esc_attr( $input_name ); ?>"
						value="1"
						<?php checked( intval( $value ), 1 ); ?>>
				<label class="mvl-listing-manager-field-radio-label" for="<?php echo esc_attr( $id ); ?>">
					<span class="mvl-listing-manager-field-radio-slider"></span>
					<?php echo esc_html( $label ); ?>
				</label>
			</div>
		<?php endif; ?>
	</div>
</div>
