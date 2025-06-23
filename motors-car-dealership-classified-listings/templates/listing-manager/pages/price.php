<?php
$default_featured_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '#1280DF', 'spec_badge_color' );

if ( $listing_manager_page->get_listing_id() ) {
	$featured_badge_color = get_post_meta(
		$listing_manager_page->get_listing_id(),
		'badge_bg_color',
		true
	);
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
			<div class="mvl-listing-manager-content-body-page-preview-wrapper" mvl-tooltip-image="<?php echo esc_url( $listing_manager_page->get_preview_url() ); ?>" mvl-tooltip-position="bottom" mvl-tooltip-toggle="mvl-listing-manager-content-body-page-preview-img">
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
		<div class="mvl-listing-manager-content-body-page-fields-group wrapped">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'price',
					'label'       => __( 'Regular Price', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter regular price', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[price]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'price', true ) : '',
					'type'        => 'number',
				)
			);
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'regular_price_label',
					'label'       => __( 'Regular Price Label', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter regular price label', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[regular_price_label]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'regular_price_label', true ) : '',
					'type'        => 'text',
				)
			);
			?>
		</div>
		<div class="mvl-listing-manager-content-body-page-fields-group full-width">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'regular_price_description',
					'label'       => __( 'Regular Price Description', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter regular price description', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[regular_price_description]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'regular_price_description', true ) : '',
					'type'        => 'text',
				)
			);
			?>
		</div>
		<div class="mvl-listing-manager-content-body-page-fields-group wrapped">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'sale_price',
					'label'       => __( 'Sale Price', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter sale price', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[sale_price]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'sale_price', true ) : '',
					'type'        => 'number',
				)
			);
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'special_price_label',
					'label'       => __( 'Sale Price Label', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter sale price label', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[special_price_label]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'special_price_label', true ) : '',
					'type'        => 'text',
				)
			);
			?>
		</div>
		<div class="mvl-listing-manager-content-body-page-fields-group full-width">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'instant_savings_label',
					'label'       => __( 'Instant Savings Label', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter instant savings label', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[instant_savings_label]',
					'class'       => 'column',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'instant_savings_label', true ) : '',
					'type'        => 'text',
					'description' => __( 'Show the difference between the regular price and sale price', 'stm_vehicles_listing' ),
				)
			);
			?>
		</div>
		<div class="mvl-listing-manager-content-body-page-fields-group full-width">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/switch',
				array(
					'id'                 => 'car_price_form',
					'label'              => __( 'Request a Price Option', 'stm_vehicles_listing' ),
					'input_name'         => $listing_manager_page->get_id() . '[car_price_form]',
					'value'              => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'car_price_form', true ) : '',
					'description_bottom' => __( 'Show the request a price form', 'stm_vehicles_listing' ),
					'data_name'          => $listing_manager_page->get_id() . '[car_price_form]',
				)
			);
			?>
		</div>
		<div class="mvl-listing-manager-content-body-page-fields-group full-width" 
			data-depends-on="<?php echo esc_attr( $listing_manager_page->get_id() . '[car_price_form]' ); ?>" 
			data-depend-values="<?php echo esc_attr( '1' ); ?>" 
			data-depend-action="show"
			style="display: none;">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'car_price_form_label',
					'label'       => __( 'Request a Price Form Label', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter request a price form label', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[car_price_form_label]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'car_price_form_label', true ) : '',
					'type'        => 'text',
				)
			);
			?>
		</div>
	</div>
</div>
