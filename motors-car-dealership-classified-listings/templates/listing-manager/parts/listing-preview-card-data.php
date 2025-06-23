<?php
$car_data = apply_filters( 'stm_get_car_listings', array() );

foreach ( $car_data as $data ) :
	if ( empty( $data['slug'] ) || empty( $data['single_name'] ) ) {
		continue;
	}

	if ( ( isset( $data['field_type'] ) && in_array( $data['field_type'], array( 'price', 'location' ), true ) ) || in_array( $data['slug'], array( 'price', 'location' ), true ) ) {
		continue;
	}

	$car_data_meta = get_post_meta( $listing_id, $data['slug'], true );
	$affix         = '';

	if ( ! empty( $data['numeric'] ) ) {
		$car_data_meta = floatval( $car_data_meta );
		$car_data_meta = number_format( abs( $car_data_meta ), 0, '', '' );
		if ( ! empty( $data['number_field_affix'] ) ) {
			$affix = $data['number_field_affix'];
		}
	}

	$output_value = $car_data_meta;
	if ( empty( $data['numeric'] ) ) {
		$label = get_term_by( 'slug', $car_data_meta, $data['slug'] );
		if ( $label && ! is_wp_error( $label ) && ! empty( $label->name ) ) {
			$output_value = $label->name;
		}
	}
	?>
	<div class="mvl-listing-preview-card-data" data-field-id="<?php echo esc_attr( $data['slug'] ); ?>">
		<div class="mvl-listing-preview-card-data-key">
			<?php echo esc_html( $data['single_name'] ); ?>
		</div>
		<div class="mvl-listing-preview-card-data-value-wrapper">
			<div class="mvl-listing-preview-card-data-value">
				<?php
				if ( ! empty( $output_value ) ) {
					echo esc_html( $output_value );
				} else {
					echo esc_html( '-' );
				}
				?>
			</div>
			<?php if ( ! empty( $affix ) ) : ?>
				<span class="mvl-listing-preview-card-data-affix">
					<?php echo esc_html( $affix ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
	<?php
endforeach;
