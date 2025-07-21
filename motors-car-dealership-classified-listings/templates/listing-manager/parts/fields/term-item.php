<?php
if ( ! isset( $term ) || ! is_object( $term ) ) {
	return;
}
?>
<div class="mvl-listing-manager-field mvl-listing-manager-field-term-item" data-term-id="<?php echo esc_attr( $term->term_id ); ?>">
	<div class="mvl-listing-manager-term-item">
		<div class="mvl-listing-manager-term-item-image"
			data-depends-on="field_type"
			data-depend-values="dropdown,checkbox,numeric,price"
			data-depend-action="show"
			data-image-id="<?php echo esc_attr( $term->image_id ?? '' ); ?>">
			<?php if ( ! empty( $term->image_url ) ) : ?>
				<img src="<?php echo esc_url( $term->image_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
			<?php else : ?>
				<div class="mvl-listing-manager-term-item-image-placeholder">
					<i class="motors-icons-image-plus"></i>
				</div>
			<?php endif; ?>
			<div class="mvl-listing-manager-term-item-image-edit">
				<i class="motors-icons-image-plus"></i>
			</div>
		</div>
		<div class="mvl-listing-manager-term-item-color"
			data-depends-on="field_type"
			data-depend-values="color"
			data-depend-action="show">
			<span class="term-item-color" style="background: <?php echo esc_attr( $term->color ); ?>!important;"></span>
		</div>
		<div class="mvl-listing-manager-term-item-edit-color" data-depends-on="field_type" data-depend-values="color" data-depend-action="show">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/color',
				array(
					'id'         => 'term_color_' . $term->term_id,
					'input_name' => $term->term_id . '_category_color',
					'value'      => $term->color,
				)
			);
			?>
		</div>
		<div class="mvl-listing-manager-term-item-name">
			<input type="text" class="mvl-listing-manager-term-item-name-input" value="<?php echo esc_attr( $term->name ); ?>" readonly>
			<div class="mvl-listing-manager-term-item-actions">
				<button id="mvl-lm-edit-term-btn" class="mvl-secondary-btn mvl-lm-edit-option-btn mvl-short-btn">
					<i class="motors-icons-mvl-pencil"></i>
				</button>
				<button id="mvl-lm-delete-term-btn" class="mvl-delete-btn mvl-lm-delete-option-btn mvl-short-btn">
					<i class="motors-icons-mvl-trash"></i>
				</button>
			</div>
		</div>
	</div>
</div>
