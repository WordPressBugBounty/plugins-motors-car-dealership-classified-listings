<div class="mvl-listing-manager-field-item" data-field-id="<?php echo esc_attr( $option['slug'] ); ?>">
	<div class="mvl-listing-manager-field-item-actions">
		<i class="fa-solid fa-grip-vertical"></i>
		<div class="mvl-listing-manager-field-item-placeholder">
			<?php
			echo esc_html( $option['plural_name'] );
			?>
		</div>
		<div class="mvl-listing-manager-field-item-btns">
			<button id="edit-<?php echo esc_attr( $page ); ?>" class="mvl-secondary-btn mvl-lm-edit-option-btn mvl-short-btn" data-option-id="<?php echo esc_attr( $option['slug'] ); ?>">
				<i class="motors-icons-mvl-pencil"></i>
			</button>
			<button id="delete-<?php echo esc_attr( $page ); ?>" class="mvl-delete-btn mvl-lm-delete-option-btn mvl-short-btn" data-option-id="<?php echo esc_attr( $option['slug'] ); ?>"
				<?php
				if ( ! empty( $data_attr ) && is_array( $data_attr ) ) {
					foreach ( $data_attr as $attr_key => $attr_value ) {
						echo 'data-' . esc_attr( $attr_key ) . '="' . esc_attr( $attr_value ) . '" ';
					}
				}
				?>
			>
				<i class="motors-icons-mvl-trash"></i>
			</button>
		</div>
	</div>
</div>
