<?php
use MotorsVehiclesListing\Stilization\Colors;

$default_featured_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', Colors::value( 'spec_badge_color' ), 'spec_badge_color' );

if ( $listing_manager_page->get_listing_id() ) {
	$featured_badge_color = get_post_meta( $listing_manager_page->get_listing_id(), 'badge_bg_color', true );
	if ( empty( $featured_badge_color ) ) {
		$featured_badge_color = $default_featured_badge_color;
	}
} else {
	$featured_badge_color = $default_featured_badge_color;
}

?>
<div class="mvl-listing-manager-content-body-page-header">
	<div class="mvl-listing-manager-content-body-page-title-wrapper">
		<div class="mvl-listing-manager-content-body-page-title">
			<?php echo esc_html( $listing_manager_page->get_title() ); ?>
		</div>
		<?php if ( $listing_manager_page->has_preview() ) : ?>
		<div class="mvl-listing-manager-content-body-page-preview-wrapper">
			<div class="mvl-listing-manager-content-body-page-preview">
				<i class="motors-icons-mvl-eye"></i>
				<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="mvl-listing-manager-content-body-page-text">
	<div class="mvl-listing-manager-content-body-page-fields-row">
		<?php
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/text',
			array(
				'id'          => 'title',
				'label'       => __( 'Title', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter title', 'stm_vehicles_listing' ),
				'input_name'  => $listing_manager_page->get_id() . '[title]',
				'value'       => $listing_manager_page->get_listing_id() ? get_the_title( $listing_manager_page->get_listing_id() ) : '',
			)
		);

		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/textarea',
			array(
				'id'          => 'description',
				'label'       => __( 'Description', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter description', 'stm_vehicles_listing' ),
				'input_name'  => $listing_manager_page->get_id() . '[description]',
				'value'       => $listing_manager_page->get_listing_id() ? get_post( $listing_manager_page->get_listing_id() )->post_content : '',
				'tinymce'     => true,
			)
		);

		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/switch',
			array(
				'id'         => 'car_mark_as_sold',
				'label'      => __( 'Mark as Sold', 'stm_vehicles_listing' ),
				'input_name' => $listing_manager_page->get_id() . '[car_mark_as_sold]',
				'value'      => apply_filters( 'mvl_listing_manager_item_is_sold', false ),
			)
		);

		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/switch',
			array(
				'id'         => 'special_car',
				'label'      => __( 'Mark as Featured', 'stm_vehicles_listing' ),
				'input_name' => $listing_manager_page->get_id() . '[special_car]',
				'value'      => apply_filters( 'mvl_listing_manager_item_is_special', false ),
			)
		);
		?>
		<div class="mvl-listing-manager-content-body-page-fields-group <?php echo apply_filters( 'mvl_listing_manager_item_is_special', false ) ? 'active' : ''; ?>" data-checked="special_car">
			<div class="mvl-listing-manager-content-body-page-fields-row">
				<?php
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/text',
					array(
						'id'          => 'badge_text',
						'label'       => __( 'Badge Text', 'stm_vehicles_listing' ),
						'placeholder' => __( 'Add Text', 'stm_vehicles_listing' ),
						'input_name'  => $listing_manager_page->get_id() . '[badge_text]',
						'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'badge_text', true ) : '',
					)
				);
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/color',
					array(
						'id'         => 'badge_bg_color',
						'label'      => __( 'Badge Color', 'stm_vehicles_listing' ),
						'input_name' => $listing_manager_page->get_id() . '[badge_bg_color]',
						'value'      => $featured_badge_color,
					)
				);
				?>
			</div>
		</div>
	</div>
</div>
