<?php
$image      = false;
$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );
if ( $listing_id ) {
	$image = get_the_post_thumbnail_url( apply_filters( 'mvl_listing_manager_item_id', 0 ), 'medium' );
}
$badge_color = apply_filters( 'mvl_listing_manager_item_badge_color', '' );
$is_rental_price_enabled = (bool) apply_filters( 'mvl_listing_manager_use_rental_price', false, $listing_id );
$is_rental_business_type = (bool) apply_filters( 'mvl_is_rental_business_type', false );
$rental_price            = $listing_id ? get_post_meta( $listing_id, 'price', true ) : '';
$rental_price_display    = apply_filters( 'mvl_listing_manager_item_price', '-' );

if ( $is_rental_price_enabled && $listing_id ) {
	$current_class_id = get_post_meta( $listing_id, 'mvl_rental_class_id', true );

	foreach ( apply_filters( 'mvl_listing_manager_rental_price_options', array(), $listing_id ) as $rental_class ) {
		if ( isset( $rental_class['id'], $rental_class['daily_rate'] ) && $current_class_id === $rental_class['id'] ) {
			$rental_price = $rental_class['daily_rate'];
			break;
		}
	}

	if ( '' !== $rental_price ) {
		$currency_symbol   = apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency' );
		$rental_price_display = 'right' === apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'price_currency_position' )
			? $rental_price . $currency_symbol
			: $currency_symbol . $rental_price;
	}
}
?>

<div class="mvl-listing-preview-card">
	<div class="mvl-listing-preview-card-wrapper">
		<div class="mvl-listing-preview-card-inner">
			<div class="mvl-listing-preview-card-badge <?php echo apply_filters( 'mvl_listing_manager_item_is_special', false ) ? 'active' : ''; ?>" style="<?php echo $badge_color ? 'background-color: ' . esc_attr( $badge_color ) : ''; ?>" data-checked="special_car">
				<i class="motors-icons-flame-skin"></i>
				<div class="mvl-listing-preview-card-badge-text"><?php echo esc_html( apply_filters( 'mvl_listing_manager_item_badge_text', __( 'Special', 'stm_vehicles_listing' ) ) ); ?></div>
			</div>
			<div class="mvl-listing-preview-card-image">
				<?php if ( $image ) : ?>
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( 'Listing Image', 'stm_vehicles_listing' ); ?>" />
				<?php else : ?>
					<div class="mvl-listing-preview-card-image-placeholder">
						<i class="motors-icons-mvl_file_select"></i>
					</div>
				<?php endif; ?>
			</div>
			<div class="mvl-listing-preview-card-data-wrapper">
				<?php if ( $is_rental_price_enabled ) : ?>
				<div class="mvl-listing-preview-card-rental-tariff">
					<div class="mvl-listing-preview-card-rental-tariff-label"><?php esc_html_e( 'Rental Tariff', 'stm_vehicles_listing' ); ?></div>
					<div class="mvl-listing-preview-card-rental-tariff-price">
						<span data-mvl-rental-tariff-value><?php echo esc_html( $rental_price ? $rental_price_display : '-' ); ?></span>
						<span data-mvl-rental-tariff-unit><?php echo esc_html( $rental_price ? __( '/ day', 'stm_vehicles_listing' ) : '' ); ?></span>
					</div>
				</div>
				<?php else : ?>
				<div class="mvl-listing-preview-card-data">
					<div class="mvl-listing-preview-card-data-key"><?php esc_html_e( 'Price', 'stm_vehicles_listing' ); ?></div>
					<div class="mvl-listing-preview-card-data-value mvl-listing-preview-card-price-value"><?php echo esc_html( apply_filters( 'mvl_listing_manager_item_price', '-' ) ); ?></div>
				</div>
				<div class="mvl-listing-preview-card-data">
					<div class="mvl-listing-preview-card-data-key"><?php esc_html_e( 'Sale price', 'stm_vehicles_listing' ); ?></div>
					<div class="mvl-listing-preview-card-data-value mvl-listing-preview-card-sale-price-value"><?php echo esc_html( apply_filters( 'mvl_listing_manager_item_sale_price', '-' ) ); ?></div>
				</div>
				<?php endif; ?>
				<div class="mvl-listing-preview-card-data">
					<div class="mvl-listing-preview-card-data-key"><?php esc_html_e( 'Title', 'stm_vehicles_listing' ); ?></div>
					<div class="mvl-listing-preview-card-data-value mvl-listing-preview-card-title-value"><?php echo esc_html( apply_filters( 'mvl_listing_manager_item_title', __( 'Untitled', 'stm_vehicles_listing' ) ) ); ?></div>
				</div>
				<div class="mvl-listing-preview-card-created">
					<span class="mvl-listing-preview-card-created-text">
						<?php esc_html_e( 'Created on', 'stm_vehicles_listing' ); ?>
					</span>
					<span class="mvl-listing-preview-card-created-date">
						<?php echo esc_html( apply_filters( 'mvl_listing_manager_item_created_date', wp_date( 'd/m/Y' ) ) ); ?>
					</span>
				</div>
				<?php
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/listing-preview-card-data',
					array(
						'listing_id' => $listing_id,
					)
				);
				?>
			</div>
			<div class="mvl-listing-preview-card-actions">	
				<button type="submit" class="mvl-listing-preview-card-action mvl-thirdary-btn <?php echo ! $listing_id || $listing_id && get_post_status( $listing_id ) === 'draft' ? 'disabled' : ''; ?>" data-status="draft">
					<?php if ( $listing_id && get_post_status( $listing_id ) === 'draft' ) : ?>
						<?php esc_html_e( 'Drafted', 'stm_vehicles_listing' ); ?>
					<?php else : ?>
						<?php esc_html_e( 'Save Draft', 'stm_vehicles_listing' ); ?>
					<?php endif; ?>
				</button>
				<button type="submit" class="mvl-listing-preview-card-action mvl-delete-btn <?php echo ! $listing_id || $listing_id && get_post_status( $listing_id ) === 'trash' ? 'disabled' : ''; ?>" data-status="trash" data-confirmation-title="<?php esc_html_e( 'Trash', 'stm_vehicles_listing' ); ?>" data-confirmation-message="<?php esc_html_e( 'Are you sure you want to trash this listing?', 'stm_vehicles_listing' ); ?>" data-confirmation-cancel="<?php esc_html_e( 'Trash', 'stm_vehicles_listing' ); ?>" data-confirmation-accept="<?php esc_html_e( 'Cancel', 'stm_vehicles_listing' ); ?>" data-confirmation-delete-btn-icon="" data-confirmation-slug="trash">
					<i class="motors-icons-mvl-trash"></i>
					<?php if ( $listing_id && get_post_status( $listing_id ) === 'trash' ) : ?>
						<?php esc_html_e( 'Trashed', 'stm_vehicles_listing' ); ?>
					<?php else : ?>
						<?php esc_html_e( 'Trash', 'stm_vehicles_listing' ); ?>
					<?php endif; ?>
				</button>
				<?php if ( ! $is_rental_business_type ) : ?>
					<a href="<?php echo $listing_id && get_post_status( $listing_id ) === 'publish' ? esc_url( get_the_permalink( $listing_id ) ) : esc_url( get_preview_post_link( $listing_id ) ); ?>" target="_blank" class="mvl-listing-preview-card-action mvl-secondary-btn <?php echo ! $listing_id || get_post_status( $listing_id ) === 'trash' ? 'disabled' : ''; ?> mvl-listing-preview-button">
						<i class="motors-icons-mvl-eye"></i>
						<?php if ( $listing_id && get_post_status( $listing_id ) === 'publish' ) : ?>
							<?php esc_html_e( 'View', 'stm_vehicles_listing' ); ?>
						<?php else : ?>
							<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
						<?php endif; ?>
					</a>
				<?php endif; ?>
				<button type="submit" class="mvl-listing-preview-card-action mvl-primary-btn <?php echo $listing_id && get_post_status( $listing_id ) === 'publish' ? 'disabled' : ''; ?>" data-status="publish">
					<?php if ( $listing_id && get_post_status( $listing_id ) === 'publish' ) : ?>
						<?php esc_html_e( 'Update', 'stm_vehicles_listing' ); ?>
					<?php else : ?>
						<?php esc_html_e( 'Publish', 'stm_vehicles_listing' ); ?>
					<?php endif; ?>
				</button>
			</div>
		</div>
	</div>
</div>
