<?php
$listing_id       = $listing_manager_page->get_listing_id();
$options          = get_option( 'stm_vehicle_listing_options' );
$terms_by_option  = array();
$selected_options = array();

if ( ! empty( $options ) && is_array( $options ) ) {
	foreach ( $options as $option ) {
		if ( isset( $option['field_type'] ) && 'price' === $option['field_type'] ) {
			continue;
		}
		$args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
			'fields'     => 'all',
			'pad_counts' => false,
		);
		$slug = isset( $option['slug'] ) ? $option['slug'] : '';
		if ( ! empty( $slug ) ) {
			$selected_options[ $slug ] = get_post_meta( $listing_id, $slug, true );
			$terms_by_option[ $slug ]  = get_terms( $slug, $args );
		}
	}
}
?>
<div class="mvl-listing-manager-content-body-page-header">
	<div class="mvl-listing-manager-content-body-page-title-wrapper">
		<div class="mvl-listing-manager-content-body-page-title">
			<?php echo esc_html( $listing_manager_page->get_title() ); ?>
		</div>
		<?php if ( $listing_manager_page->has_preview() ) : ?>
			<div class="mvl-listing-manager-content-body-page-preview-wrapper" mvl-tooltip-image="<?php echo esc_url( $listing_manager_page->get_preview_url() ); ?>" mvl-tooltip-position="bottom" mvl-tooltip-toggle="mvl-listing-manager-content-body-page-preview-img">
				<div class="mvl-listing-manager-content-body-page-preview">
					<i class="motors-icons-mvl-eye"></i>
					<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="mvl-listing-manager-content-body-page-header-actions">
		<button class="mvl-secondary-btn mvl-lm-edit-options-btn" data-listing-page-id="<?php echo esc_attr( $listing_manager_page->get_id() ); ?>" data-template="edit-options" data-confirmation-title="<?php esc_html_e( 'You have unsaved changes.', 'stm_vehicles_listing' ); ?>" data-confirmation-message="<?php esc_html_e( 'To avoid losing them, please save before adding or editing items.', 'stm_vehicles_listing' ); ?>" data-confirmation-accept="<?php esc_html_e( 'Cancel', 'stm_vehicles_listing' ); ?>" data-confirmation-cancel="<?php esc_html_e( 'Discard', 'stm_vehicles_listing' ); ?>">
			<i class="motors-icons-mvl-pencil"></i>
			<?php esc_html_e( 'Edit Fields', 'stm_vehicles_listing' ); ?>
		</button>
		<button class="mvl-primary-btn mvl-lm-add-options-btn" data-listing-page-id="<?php echo esc_attr( $listing_manager_page->get_id() ); ?>" data-template="options">
			<i class="fa fa-plus"></i>
			<?php esc_html_e( 'Add Custom Field', 'stm_vehicles_listing' ); ?>
		</button>
	</div>
</div>
<div class="mvl-listing-manager-content-body-page-option-wrapper mvl-listing-manager-content-body-page-text " data-listing-id="<?php echo esc_attr( $listing_id ); ?>">
</div>
