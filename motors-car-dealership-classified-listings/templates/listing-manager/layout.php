<?php
$listing_id                     = apply_filters( 'mvl_listing_manager_item_id', 0 );
$is_admin                       = apply_filters( 'mvl_listing_manager_is_admin', false );
$css_files                      = apply_filters( 'mvl_listing_manager_css', array() );
$js_files                       = apply_filters( 'mvl_listing_manager_js', array() );
$ymmt                           = apply_filters( 'motors_vl_get_nuxy_mod', '', 'make_model_auto_complete_enabled' );
$vin                            = apply_filters( 'motors_vl_get_nuxy_mod', '', 'vin_search_auto_complete_enabled' );
$car_info_auto_complete_enabled = false;
if ( $ymmt || $vin ) {
	$car_info_auto_complete_enabled = true;
}
?>
<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<title><?php echo esc_html( __( 'Listing Manager', 'stm_vehicles_listing' ) ) . ' - ' . esc_html( apply_filters( 'mvl_listing_manager_item_title', __( 'Untitled', 'stm_vehicles_listing' ) ) ); ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php foreach ( $css_files as $id => $css_url ) : // phpcs:disable ?>
			<link rel="stylesheet" href="<?php echo esc_url( $css_url ); ?>" type="text/css" media="all">
		<?php endforeach; // phpcs:enable ?>
	</head>
	<body <?php body_class( 'mvl-listing-manager-body wp-admin wp-core-ui no-js' ); ?>>
		<div id="mvl-listing-manager">
			<div class="mvl-listing-manager-page">
				<div class="mvl-listing-manager-wrapper">
					<div class="mvl-listing-manager-sidebar">
						<div class="mvl-listing-manager-sidebar-header">
							<a class="mvl-listing-manager-sidebar-back-link" href="<?php echo esc_url( admin_url( 'edit.php?post_type=listings' ) ); ?>">
								<i class="fa-solid fa-arrow-left"></i>
							</a> 
							<div class="mvl-listing-manager-sidebar-title">
								<?php echo esc_html( apply_filters( 'mvl_listing_manager_item_title', __( 'Untitled', 'stm_vehicles_listing' ) ) ); ?>
							</div>
						</div>
						<div class="mvl-listing-manager-sidebar-content">
							<div class="mvl-listing-manager-sidebar-menu-wrapper">
								<div class="mvl-listing-manager-sidebar-menu">
									<ul class="mvl-mvl-listing-manager-sidebar-menu-list">
										<?php foreach ( apply_filters( 'mvl_listing_manager_pages', array() ) as $listing_manager_page ) : ?>
										<li data-pageid="<?php echo esc_attr( $listing_manager_page->get_id() ); ?>" class="mvl-listing-manager-sidebar-menu-item<?php echo $listing_manager_page->is_active() ? ' active' : ''; ?> <?php echo esc_attr( $listing_manager_page->get_id() ); ?>">
											<a href="<?php echo esc_url( $listing_manager_page->get_url() ); ?>">
												<i class="<?php echo esc_attr( $listing_manager_page->get_icon() ); ?>"></i>
												<?php echo esc_html( $listing_manager_page->get_menu_name() ); ?>
											</a>
										</li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
						</div>
						<?php if ( is_user_logged_in() && ! apply_filters( 'is_mvl_pro', false ) && $is_admin ) : ?>
							<div class="mvl-listing-manager-sidebar-upgrade-pro">
								<div class="mvl-listing-manager-sidebar-upgrade-pro-wrapper">
									<div class="mvl-listing-manager-sidebar-upgrade-pro-title">
										<?php esc_html_e( 'Upgrade to ', 'stm_vehicles_listing' ); ?>
										<span><?php esc_html_e( 'MOTORS ', 'stm_vehicles_listing' ); ?></span>
										<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/pro/mvl_pro_badge.svg' ); ?>" alt="MOTORS PRO PLUGIN" class="mvl-listing-manager-sidebar-upgrade-pro-logo">
									</div>
									<div class="mvl-listing-manager-sidebar-upgrade-pro-text">
										<?php esc_html_e( 'Advanced features, seamless integrations, and premium support.', 'stm_vehicles_listing' ); ?>
									</div>
									<a href="<?php echo esc_url( 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wp-admin&utm_medium=push&utm_campaign=motors&utm_content=gopro' ); ?>" class="mvl-primary-btn mvl-listing-manager-sidebar-upgrade-pro-btn">
										<?php esc_html_e( 'Upgrate to PRO', 'stm_vehicles_listing' ); ?>
									</a>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<form class="mvl-listing-manager-content" action="" method="post">
						<div class="mvl-listing-manager-content-header">
							<div class="mvl-listing-manager-content-header-inner">
								<div class="mvl-listing-manager-content-header-search-field">
									<input type="text" class="mvl-lm-search-field-input mvl-input-field" placeholder="<?php esc_html_e( 'Search', 'stm_vehicles_listing' ); ?>">
								</div>
								<div class="mvl-listing-manager-content-header-actions">
									<?php if ( apply_filters( 'is_mvl_pro', false ) && apply_filters( 'mvl_is_addon_enabled', false, 'car_info_auto_complete' ) && $car_info_auto_complete_enabled ) : ?>
									<a href="#" id="mvl-car-info-auto-complete-btn" class="mvl-primary-btn mvl-listing-manager-content-header-action-btn">
										<i class="motors-icons-add_car1"></i>
										<?php esc_html_e( 'Import Car Details', 'stm_vehicles_listing' ); ?>
									</a>
									<?php endif; ?>
									<a class="mvl-listing-manager-content-header-action-btn mvl-thirdary-btn mvl-plus-icon" href="<?php echo esc_url( apply_filters( 'mvl_listing_manager_url', '' ) ); ?>">
										<?php esc_html_e( 'Create Another', 'stm_vehicles_listing' ); ?>
									</a>
									<a href="<?php echo $listing_id && get_post_status( $listing_id ) === 'publish' ? esc_url( get_the_permalink( $listing_id ) ) : esc_url( get_preview_post_link( $listing_id ) ); ?>" target="_blank" class="mvl-secondary-btn mvl-listing-manager-content-header-action-btn <?php echo ! apply_filters( 'mvl_listing_manager_item_id', false ) || get_post_status( apply_filters( 'mvl_listing_manager_item_id', 0 ) ) === 'trash' ? 'disabled' : ''; ?> mvl-listing-preview-button">
										<i class="motors-icons-mvl-eye"></i>
										<?php if ( $listing_id && get_post_status( $listing_id ) === 'publish' ) : ?>
											<?php esc_html_e( 'View', 'stm_vehicles_listing' ); ?>
										<?php else : ?>
											<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
										<?php endif; ?>
									</a>
									<button type="submit" class="mvl-primary-btn mvl-listing-manager-content-header-action-btn <?php echo $listing_id && get_post_status( $listing_id ) === 'publish' ? 'disabled' : ''; ?>" data-status="publish">
									<?php if ( $listing_id && get_post_status( $listing_id ) === 'publish' ) : ?>
										<?php esc_html_e( 'Update', 'stm_vehicles_listing' ); ?>
									<?php else : ?>
										<?php esc_html_e( 'Publish', 'stm_vehicles_listing' ); ?>
									<?php endif; ?>
									</button>
								</div>
							</div>
						</div>
						<div class="mvl-listing-manager-content-body">
							<div class="mvl-listing-manager-content-body-inner">
								<?php foreach ( apply_filters( 'mvl_listing_manager_pages', array() ) as $listing_manager_page ) : ?>
									<div class="mvl-listing-manager-content-body-page <?php echo $listing_manager_page->is_active() ? 'active ' : ''; ?><?php echo esc_attr( $listing_manager_page->get_id() ); ?>"  data-pageid="<?php echo esc_attr( $listing_manager_page->get_id() ); ?>">
										<?php $listing_manager_page->render(); ?>
									</div>
								<?php endforeach; ?>
								<div class="mvl-listing-manager-content-body-page-actions">
									<div class="mvl-fourthary-btn mvl-listing-manager-content-body-page-action-prev<?php echo apply_filters( 'mvl_listing_manager_is_first_page', false ) ? ' disabled' : ''; ?>">
										<i class="fas fa-arrow-left"></i>
										<?php esc_html_e( 'Previous', 'stm_vehicles_listing' ); ?>
									</div>
									<div class="mvl-primary-btn mvl-listing-manager-content-body-page-action-next<?php echo apply_filters( 'mvl_listing_manager_is_last_page', false ) ? ' disabled' : ''; ?>">
										<?php esc_html_e( 'Next', 'stm_vehicles_listing' ); ?>
										<i class="fas fa-arrow-right"></i>
									</div>
								</div>
								<div class="mvl-listing-manager-content-body-card">
									<?php do_action( 'stm_listings_load_template', 'listing-manager/parts/listing-preview-card' ); ?>
								</div>
							</div>
						</div>
						<input type="hidden" name="action" value="mvl_listing_manager_save">
						<input type="hidden" name="nonce" value="<?php echo esc_html( wp_create_nonce( 'mvl_listing_manager' ) ); ?>">
						<input type="hidden" name="listing_id" value="<?php echo esc_attr( apply_filters( 'mvl_listing_manager_item_id', 0 ) ); ?>">
					</form>
				</div>
			</div>
		</div>
		<div class="mvl-listing-manager-overlay" data-show="false"></div>
		<div class="mvl-listing-manager-save-progress" data-status="progress" data-show="false">
			<span class="loader"></span>
			<span class="mvl-listing-manager-save-progress-text"><?php esc_html_e( 'Save in progress...', 'stm_vehicles_listing' ); ?></span>
		</div>
		<?php do_action( 'stm_listings_load_template', 'listing-manager/parts/modals/confirmation' ); ?>
		<script>
			var listingManager = {
				text: {
					untitled: '<?php echo esc_js( __( 'Untitled', 'stm_vehicles_listing' ) ); ?>',
					special: '<?php echo esc_js( __( 'Special', 'stm_vehicles_listing' ) ); ?>',
					save_in_progress: '<?php echo esc_js( __( 'Save in progress...', 'stm_vehicles_listing' ) ); ?>',
					save_draft_success: '<?php echo esc_js( __( 'Listing save in draft.', 'stm_vehicles_listing' ) ); ?>',
					save_publish_success: '<?php echo esc_js( __( 'Listing published.', 'stm_vehicles_listing' ) ); ?>',
					save_error: '<?php echo esc_js( __( 'Error saving listing.', 'stm_vehicles_listing' ) ); ?>',
					save_success: '<?php echo esc_js( __( 'Changes saved.', 'stm_vehicles_listing' ) ); ?>',
					error_popup_title: '<?php echo esc_js( __( 'Error', 'stm_vehicles_listing' ) ); ?>',
					error_popup_cancel: '<?php echo esc_js( __( 'Close', 'stm_vehicles_listing' ) ); ?>',
					trash_button: '<?php echo esc_js( __( 'Trash', 'stm_vehicles_listing' ) ); ?>',
					trashed_button: '<?php echo esc_js( __( 'Trashed', 'stm_vehicles_listing' ) ); ?>',
					publish_button: '<?php echo esc_js( __( 'Publish', 'stm_vehicles_listing' ) ); ?>',
					draft_button: '<?php echo esc_js( __( 'Save Draft', 'stm_vehicles_listing' ) ); ?>',
					published_button: '<?php echo esc_js( __( 'Update', 'stm_vehicles_listing' ) ); ?>',
					drafted_button: '<?php echo esc_js( __( 'Drafted', 'stm_vehicles_listing' ) ); ?>',
					view: '<?php echo esc_js( __( 'View', 'stm_vehicles_listing' ) ); ?>',
					preview: '<?php echo esc_js( __( 'Preview', 'stm_vehicles_listing' ) ); ?>',
				},
				currency_symbol: '<?php echo esc_js( apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency' ) ); ?>',
				colorpicker_swatches: [
					'<?php echo esc_js( \MotorsVehiclesListing\Stilization\Colors::value( 'accent_color' ) ); ?>',
					'<?php echo esc_js( \MotorsVehiclesListing\Stilization\Colors::value( 'bg_contrast' ) ); ?>',
					'<?php echo esc_js( \MotorsVehiclesListing\Stilization\Colors::value( 'spec_badge_color' ) ); ?>',
					'<?php echo esc_js( \MotorsVehiclesListing\Stilization\Colors::value( 'sold_badge_color' ) ); ?>'
				],
				post_status: '<?php echo esc_js( $listing_id ? get_post_status( $listing_id ) : '' ); ?>',
				currency_position: '<?php echo esc_js( apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'price_currency_position' ) ); ?>',
				url: '<?php echo esc_js( apply_filters( 'mvl_listing_manager_url', '', 0 ) ); ?>'
			};

			var listingManagerFileMediaArgs = {
				title: '<?php echo esc_js( __( 'Upload', 'stm_vehicles_listing' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Use selection', 'stm_vehicles_listing' ) ); ?>'
				},
				multiple: false,
				library: {
					type: ['application/pdf']
				}
			};

			var listingManagerImageMediaArgs = {
				title: '<?php echo esc_js( __( 'Upload', 'stm_vehicles_listing' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Use selection', 'stm_vehicles_listing' ) ); ?>'
				},
				multiple: false,
				library: {
					type: 'image'
				}
			};

			var save_nonce = '<?php echo esc_html( wp_create_nonce( 'mvl_listing_manager_save' ) ); ?>';
			var nonce_get_form = '<?php echo esc_html( wp_create_nonce( 'mvl_listing_manager_get_form' ) ); ?>';
			var nonce = '<?php echo esc_html( wp_create_nonce( 'mvl_listing_manager' ) ); ?>';
			var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
			var stm_vehicles_listing = {
				edit_fields: '<?php esc_html_e( 'Edit Fields', 'stm_vehicles_listing' ); ?>',
				delete: '<?php esc_html_e( 'Delete', 'stm_vehicles_listing' ); ?>',
				save_changes: '<?php esc_html_e( 'Save Changes', 'stm_vehicles_listing' ); ?>',
				cancel: '<?php esc_html_e( 'Cancel', 'stm_vehicles_listing' ); ?>',
				add_icon: '<?php esc_html_e( 'Add Icon', 'stm_vehicles_listing' ); ?>',
				select_image: '<?php esc_html_e( 'Select Image', 'stm_vehicles_listing' ); ?>',
				use_this_image: '<?php esc_html_e( 'Use This Image', 'stm_vehicles_listing' ); ?>',
				no_image: '<?php esc_html_e( 'No Image', 'stm_vehicles_listing' ); ?>',
				no_image_selected: '<?php esc_html_e( 'No Image Selected', 'stm_vehicles_listing' ); ?>',
				delete_image: '<?php esc_html_e( 'Delete Image', 'stm_vehicles_listing' ); ?>',
				add_image: '<?php esc_html_e( 'Add Image', 'stm_vehicles_listing' ); ?>',
				search_placeholder: '<?php esc_html_e( 'Search', 'stm_vehicles_listing' ); ?>',
				add_new_option: '<?php esc_html_e( 'Add New Option', 'stm_vehicles_listing' ); ?>',
				clear_all: '<?php esc_html_e( 'Clear All', 'stm_vehicles_listing' ); ?>',
				done: '<?php esc_html_e( 'Done', 'stm_vehicles_listing' ); ?>',
				select_feature: '<?php esc_html_e( 'Select feature', 'stm_vehicles_listing' ); ?>',
				listing_id: '<?php echo esc_js( $listing_id ); ?>',
			};
			var stm_vehicles_listing_errors = {
				no_allowed_type: '<?php esc_html_e( 'No allowed type', 'stm_vehicles_listing' ); ?>',
				large_file_size: '<?php esc_html_e( 'File size is too large, max ', 'stm_vehicles_listing' ); ?>',
				not_allowed_file_type: '<?php esc_html_e( 'File type is not allowed', 'stm_vehicles_listing' ); ?>',
				files_limit: '<?php esc_html_e( 'Max files ', 'stm_vehicles_listing' ); ?>',
				same_file: '<?php esc_html_e( 'Same file already exists', 'stm_vehicles_listing' ); ?>',
			};
			<?php if ( apply_filters( 'mvl_is_addon_enabled', false, 'car_info_auto_complete' ) ) : ?>
			var car_info_auto_complete_vars = {
				ajaxurl: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
				nonce: '<?php echo esc_html( wp_create_nonce( 'mvl_listing_manager' ) ); ?>',
				select_colors: '<?php esc_html_e( 'Select Colors', 'stm_vehicles_listing' ); ?>',
				back_to_trims: '<?php esc_html_e( 'Back to Trims', 'stm_vehicles_listing' ); ?>',
				exterior_color: '<?php esc_html_e( 'Exterior Color', 'stm_vehicles_listing' ); ?>',
				interior_color: '<?php esc_html_e( 'Interior Color', 'stm_vehicles_listing' ); ?>',
			};
			<?php endif; ?>
		</script>
		<?php // phpcs:disable
			do_action( 'custom_print_media_scripts' );
			wp_print_media_templates();
		?>
		<?php foreach ( $js_files as $id => $js_url ) : ?>
			<script src="<?php echo esc_url( $js_url ); ?>"></script>
		<?php endforeach; // phpcs:enable ?>
	</body>
</html>
