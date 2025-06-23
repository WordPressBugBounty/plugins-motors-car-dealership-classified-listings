<?php
$listing_id       = $listing_manager_page->get_listing_id();
$current_settings = get_option( 'mvl_listing_details_settings', array() );
$features         = isset( $current_settings['fs_user_features'] ) ? $current_settings['fs_user_features'] : array();
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
		<button class="mvl-secondary-btn mvl-lm-edit-features-btn" data-listing-page-id="<?php echo esc_attr( $listing_manager_page->get_id() ); ?>" data-template="edit-features" data-confirmation-title="<?php esc_html_e( 'You have unsaved changes.', 'stm_vehicles_listing' ); ?>" data-confirmation-message="<?php esc_html_e( 'To avoid losing them, please save before adding or editing items.', 'stm_vehicles_listing' ); ?>" data-confirmation-accept="<?php esc_html_e( 'Cancel', 'stm_vehicles_listing' ); ?>" data-confirmation-cancel="<?php esc_html_e( 'Discard', 'stm_vehicles_listing' ); ?>">
			<i class="motors-icons-mvl-pencil"></i>
			<?php esc_html_e( 'Edit Fields', 'stm_vehicles_listing' ); ?>
		</button>
		<button class="mvl-primary-btn mvl-lm-add-feature-btn" data-listing-page-id="<?php echo esc_attr( $listing_manager_page->get_id() ); ?>" data-template="features">
			<i class="fa fa-plus"></i>
			<?php esc_html_e( 'Add Features List', 'stm_vehicles_listing' ); ?>
		</button>
	</div>
</div>
<div class="mvl-listing-manager-content-body-page-features-wrapper" data-listing-id="<?php echo esc_attr( $listing_id ); ?>">
</div>
