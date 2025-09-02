<?php

?>

<div class="mvl-listing-manager-modal mvl-modal-<?php echo esc_attr( $modal_name ); ?>-popup">
	<div class="mvl-listing-manager-modal-header">
		<div class="mvl-listing-manager-modal-header-title">
			<h4><?php echo esc_html( $title ); ?></h4>
		</div>
		<div
			class="mvl-listing-manager-close-modal"
			id="mvl-cancel-btn-<?php echo esc_attr( $modal_name ); ?>"
			<?php
			if ( ! empty( $buttons['mvl-cancel-btn']['data_attr'] ) ) {
				foreach ( $buttons['mvl-cancel-btn']['data_attr'] as $attr_key => $attr_value ) {
					printf(
						'data-%s="%s" ',
						esc_attr( $attr_key ),
						esc_attr( $attr_value )
					);
				}
			}
			?>
		>
			<i class="motors-icons-close-times"></i>
		</div>
	</div>

	<div class="mvl-listing-manager-modal-content">
		<div class="mvl-listing-manager-option-item">
			<?php do_action( 'stm_listings_load_template', $template, $__vars ); ?>
		</div>
	</div>
	<div class="mvl-listing-manager-modal-footer">
		<?php
		$delete_button = null;
		if ( isset( $buttons['delete'] ) ) {
			$delete_button = $buttons['delete'];
		}
		?>
		<div class="mvl-listing-manager-modal-btns">
			<?php if ( $delete_button ) : ?>
				<div class="mvl-listing-manager-modal-delete-btn">
					<button
						class="mvl-listing-manager-modal-btn <?php echo esc_attr( $delete_button['class'] ); ?>"
						id="delete-<?php echo esc_attr( $modal_name ); ?>"
						<?php
						if ( ! empty( $delete_button['data_attr'] ) ) {
							foreach ( $delete_button['data_attr'] as $attr_key => $attr_value ) {
								printf(
									'data-%s="%s" ',
									esc_attr( $attr_key ),
									esc_attr( $attr_value )
								);
							}
						}
						?>
					>
						<?php if ( ! empty( $delete_button['icon'] ) ) : ?>
							<i class="<?php echo esc_attr( $delete_button['icon'] ); ?>"></i>
						<?php endif; ?>
						<span class="mvl-btn-text">
							<?php echo esc_html( $delete_button['text'] ); ?>
						</span>
					</button>
				</div>
			<?php endif; ?>
			<div class="mvl-listing-manager-modal-btn-container">
				<?php foreach ( $buttons as $key => $button ) : ?>
					<?php
					if ( 'delete' === $key ) {
						continue;
					}
					?>
					<button
						class="mvl-listing-manager-modal-btn <?php echo esc_attr( $button['class'] ); ?>"
						id="<?php echo esc_attr( $key ); ?>-<?php echo esc_attr( $modal_name ); ?>"
						<?php
						if ( ! empty( $button['data_attr'] ) ) {
							foreach ( $button['data_attr'] as $attr_key => $attr_value ) {
								printf(
									'data-%s="%s" ',
									esc_attr( $attr_key ),
									esc_attr( $attr_value )
								);
							}
						}
						?>
					>
						<?php if ( ! empty( $button['icon'] ) ) : ?>
							<i class="<?php echo esc_attr( $button['icon'] ); ?>"></i>
						<?php endif; ?>
						<span class="mvl-btn-text">
							<?php echo esc_html( $button['text'] ); ?>
						</span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
