<?php
/**
 * @var array $field
 * @var string $form_type
 * @var string $field_key
 * @var string $prefix_id
 * */

$placeholder = '';
$field_class = stm_vehicles_listing_has_preview( $field );
$column      = ( ! empty( $field['column'] ) ) ? $field['column'] : 1;
$field_attr  = '';

if ( ! empty( $field['attributes'] ) ) {
	$placeholder = ( ! empty( $field['attributes']['placeholder'] ) ) ? $field['attributes']['placeholder'] : '';
}

if ( 'edit' === $form_type && 'slug' === $field_key ) {
	$field_attr = 'readonly="readonly"';
}

if ( 2 === $column ) {
	$field_class .= ' stm_custom_fields__field--one_half';
}
?>

<div class="stm_custom_fields__field <?php echo esc_attr( $field_class . ' stm_custom_fields__field--' . $field['type'] ); ?>" <?php motors_custom_field_show_dependency( $field ); ?>>
	<?php if ( in_array( $field['type'], array( 'text', 'select' ), true ) ) : ?>
		<label for="<?php echo esc_attr( $prefix_id . $field_key ); ?>">
			<?php
			echo esc_html( $field['label'] . ':' );

			if ( ! empty( $field['required'] ) ) :
				?>
				<span class="required">*</span>
				<?php
			endif;

			if ( ! empty( $field['description'] ) ) :
				?>
				<span class="stm_custom_fields__tooltip" data-bs-offset="10,15" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_attr( $field['description'] ); ?>">
					<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/admin/help.svg' ); ?>" alt="<?php esc_attr_e( 'Field description', 'stm_vehicles_listing' ); ?>">
				</span>
			<?php endif; ?>
			<?php motors_custom_field_preview( $field ); ?>
		</label>
		<?php if ( 'text' === $field['type'] ) : ?>
			<input type="text" id="<?php echo esc_attr( $prefix_id . $field_key ); ?>" value="<?php echo ( ! empty( $field['value'] ) ? esc_attr( $field['value'] ) : '' ); ?>" name="<?php echo esc_attr( $field_key ); ?>" <?php echo wp_kses_post( $field_attr ); ?> placeholder="<?php echo esc_attr( $placeholder ); ?>" class="stm_custom_fields__input">
		<?php else : ?>
			<select id="<?php echo esc_attr( $prefix_id . $field_key ); ?>" name="<?php echo esc_attr( $field_key ); ?>" class="stm_custom_fields__select">
				<?php
				if ( ! empty( $field['choices'] ) ) :
					foreach ( $field['choices'] as $choice_key => $choice ) :
						$selected = ( ! empty( $field['value'] ) ) ? selected( $choice_key, $field['value'], false ) : '';
						$is_pro   = is_array( $choice ) && isset( $choice['pro_field'] ) && $choice['pro_field'];
						$label    = is_array( $choice ) ? $choice['label'] : $choice;
						?>
						<option <?php echo wp_kses_post( $selected ); ?> value="<?php echo esc_attr( $choice_key ); ?>" data-class="<?php echo esc_attr( $is_pro ? 'stm-pro-field' : '' ); ?>">
							<?php echo esc_html( $label ); ?>
						</option>
						<?php
					endforeach;
				endif;
				?>
			</select>

		<?php endif; ?>
		<?php
	elseif ( 'icon' === $field['type'] ) :
		motors_custom_field_icon( $field_key, $field, array() );
	elseif ( 'checkbox' === $field['type'] ) :
		?>
		<input
			type="checkbox"
			id="<?php echo esc_attr( $prefix_id . $field_key ); ?>"
			name="<?php echo esc_attr( $field_key ); ?>"
			class="stm_custom_fields__checkbox"
		>
		<label for="<?php echo esc_attr( $prefix_id . $field_key ); ?>">
			<span class="stm_custom_fields__checkbox--switcher"></span>
			<span class="stm_custom_fields__checkbox--label"><?php echo esc_html( $field['label'] ); ?></span>
			<?php if ( ! empty( $field['description'] ) ) : ?>
				<span class="stm_custom_fields__tooltip" data-bs-offset="10,15" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_attr( $field['description'] ); ?>">
					<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/admin/help.svg' ); ?>" alt="<?php esc_attr_e( 'Field description', 'stm_vehicles_listing' ); ?>">
				</span>
			<?php endif; ?>
			<?php motors_custom_field_preview( $field ); ?>
		</label>
	<?php elseif ( 'radio' === $field['type'] ) : ?>
		<?php if ( ! empty( $field['label'] ) ) : ?>
			<label>
				<span class="stm_custom_fields__radio--label">
					<?php
					echo esc_html( $field['label'] );

					if ( ! empty( $field['description'] ) ) :
						?>
						<span class="stm_custom_fields__tooltip" data-bs-offset="10,15" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_attr( $field['description'] ); ?>">
							<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/admin/help.svg' ); ?>" alt="<?php esc_attr_e( 'Field description', 'stm_vehicles_listing' ); ?>">
						</span>
					<?php endif; ?>
				</span>
				<?php motors_custom_field_preview( $field ); ?>
			</label>
		<?php endif; ?>
		<div class="stm_custom_fields__radio--wrapper">
			<?php
			foreach ( $field['choices'] as $choice_value => $choice_label ) :
				$checked = ( ! empty( $field['value'] ) ) ? checked( $field['value'], $choice_value, false ) : '';
				?>
				<div class="stm_custom_fields__radio--inside">
					<input
						type="radio"
						id="<?php echo esc_attr( $prefix_id . $field_key . '-' . $choice_value ); ?>"
						value="<?php echo esc_attr( $choice_value ); ?>"
						name="<?php echo esc_attr( $field_key ); ?>"
						class="stm_custom_fields__radio"
						<?php echo wp_kses_post( $checked ); ?>
					>
					<label for="<?php echo esc_attr( $prefix_id . $field_key . '-' . $choice_value ); ?>">
						<span class="stm_custom_fields__radio--button"></span>
						<span class="stm_custom_fields__radio--label"><?php echo esc_html( $choice_label ); ?></span>
					</label>
				</div>
			<?php endforeach; ?>
		</div>
	<?php elseif ( 'radio-image' === $field['type'] ) : ?>
		<?php if ( ! empty( $field['label'] ) ) : ?>
			<label>
				<span class="stm_custom_fields__radio--label">
					<?php
					echo esc_html( $field['label'] );

					if ( ! empty( $field['description'] ) ) :
						?>
						<span class="stm_custom_fields__tooltip" data-bs-offset="10,15" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_attr( $field['description'] ); ?>">
							<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/admin/help.svg' ); ?>" alt="<?php esc_attr_e( 'Field description', 'stm_vehicles_listing' ); ?>">
						</span>
					<?php endif; ?>
				</span>
				<?php motors_custom_field_preview( $field ); ?>
			</label>
		<?php endif; ?>
		<div class="stm_custom_fields__radio--wrapper stm_custom_fields__radio-image-grid">
			<?php
			foreach ( $field['choices'] as $choice_value => $choice_args ) :
				$checked = ( ! empty( $field['value'] ) ) ? checked( $field['value'], $choice_value, false ) : '';
				?>
				<div class="stm_custom_fields__radio--inside <?php echo isset( $choice_args['pro_field'] ) && $choice_args['pro_field'] ? 'stm-pro-field' : ''; ?>">
				<?php if ( isset( $choice_args['pro_field'] ) && $choice_args['pro_field'] ) : ?>
					<div class="stm-pro-field-inner">
						<label for="<?php echo esc_attr( $prefix_id . $field_key . '-' . $choice_value ); ?>">
							<span class="stm_custom_fields__radio--image">
								<img src="<?php echo esc_html( $choice_args['url'] ); ?>" />
							</span>
						</label>
						<a href="https://stylemixthemes.com/car-dealer-plugin/pricing/" target="_blank" title="<?php esc_attr_e( 'This feature is only available in PRO version', 'motors-car-dealership-classified-listings' ); ?>">
							<span class="stm-pro-field-label-wrapper">
								<div class="icon-wrap">
									<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/pro/lock-pro.svg' ); ?>" alt="<?php esc_attr_e( 'This feature is only available in PRO version', 'motors-car-dealership-classified-listings' ); ?>">
								</div>
								<span class="stm-pro-field-label-text">
									<?php echo esc_html( $choice_args['label'] ); ?>
								</span>
								<span class="stm-pro-field-label-text-pro">
									<?php esc_html_e( 'PRO', 'motors-car-dealership-classified-listings' ); ?>
								</span>
							</span>
						</a>
					</div>
				<?php else : ?>
					<input
						type="radio"
						id="<?php echo esc_attr( $prefix_id . $field_key . '-' . $choice_value ); ?>"
						value="<?php echo esc_attr( $choice_value ); ?>"
						name="<?php echo esc_attr( $field_key ); ?>"
						class="stm_custom_fields__radio"
						<?php echo wp_kses_post( $checked ); ?>
					>
					<label for="<?php echo esc_attr( $prefix_id . $field_key . '-' . $choice_value ); ?>">
						<span class="stm_custom_fields__radio--label">
							<span class="stm_custom_fields__radio--button"></span>
							<?php echo esc_html( $choice_args['label'] ); ?>
						</span>
						<span class="stm_custom_fields__radio--image">
							<img src="<?php echo esc_html( $choice_args['url'] ); ?>" />
						</span>
					</label>

				<?php endif; ?>

				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<span class="stm_custom_field__message"></span>
</div>
