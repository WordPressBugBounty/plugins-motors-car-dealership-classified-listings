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
			data-depend-action="show">
			<?php if ( ! empty( $term->image_url ) ) : ?>
				<img src="<?php echo esc_url( $term->image_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
			<?php else : ?>
				<div class="mvl-listing-manager-term-item-image-placeholder">
					<i class="motors-icons-image-plus"></i>
				</div>
			<?php endif; ?>
		</div>
		<div class="mvl-listing-manager-term-item-color"
			data-depends-on="field_type"
			data-depend-values="color"
			data-depend-action="show">
			<span class="term-item-color" style="background: <?php echo esc_attr( $term->color ); ?>!important;"></span>
		</div>
		<div class="mvl-listing-manager-term-item-name">
			<input type="text" class="mvl-listing-manager-term-item-name-input" value="<?php echo esc_attr( $term->name ); ?>" readonly>
		</div>
	</div>
</div>
