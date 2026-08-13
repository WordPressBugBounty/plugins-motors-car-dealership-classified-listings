<?php
$default_featured_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '#1280DF', 'spec_badge_color' );
$listing_id                   = $listing_manager_page->get_listing_id();
$is_rental_price_enabled      = (bool) apply_filters( 'mvl_listing_manager_use_rental_price', false, $listing_id );

if ( $listing_id ) {
	$featured_badge_color = get_post_meta(
		$listing_id,
		'badge_bg_color',
		true
	);
	if ( empty( $featured_badge_color ) ) {
		$featured_badge_color = $default_featured_badge_color;
	}
} else {
	$featured_badge_color = $default_featured_badge_color;
}

$currency_symbol = apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' );
$currency_symbol = $currency_symbol ? $currency_symbol : '$';
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
		<?php if ( $is_rental_price_enabled ) : ?>
			<?php
			$current_price    = $listing_id ? get_post_meta( $listing_id, 'price', true ) : '';
			$pay_later_price  = $listing_id ? get_post_meta( $listing_id, 'mvl_rental_pay_later_price', true ) : '';
			$pay_now_label    = $listing_id ? get_post_meta( $listing_id, 'mvl_rental_pay_now_label', true ) : '';
			$pay_later_label  = $listing_id ? get_post_meta( $listing_id, 'mvl_rental_pay_later_label', true ) : '';
			$pay_later_enabled  = true;
			$pay_later_meta_key = 'mvl_rental_pay_later_enabled';

			if ( $listing_id && metadata_exists( 'post', $listing_id, $pay_later_meta_key ) ) {
				$pay_later_enabled = ! empty( get_post_meta( $listing_id, $pay_later_meta_key, true ) );
			}

			if ( '' === $pay_later_price && $listing_id ) {
				$pay_later_price = get_post_meta( $listing_id, 'mvl_rental_deposit', true );
			}

			if ( '' === $pay_now_label ) {
				$pay_now_label = __( 'Pay now', 'stm_vehicles_listing' );
			}

			if ( '' === $pay_later_label ) {
				$pay_later_label = __( 'Pay later', 'stm_vehicles_listing' );
			}
			?>
			<div class="mvl-listing-manager-rental-price-field mvl-listing-manager-rental-price-field--simple">
				<div class="mvl-listing-manager-rental-price-custom is-open">
					<label>
						<span><?php printf( esc_html__( 'Pay now price (%s)', 'stm_vehicles_listing' ), esc_html( $currency_symbol ) ); ?></span>
						<input type="number" min="0" step="1" name="<?php echo esc_attr( $listing_manager_page->get_id() . '[price]' ); ?>" value="<?php echo esc_attr( $current_price ); ?>" placeholder="<?php echo esc_attr( $currency_symbol . ' 0.00' ); ?>" data-slug="price">
					</label>
					<label>
						<span><?php printf( esc_html__( 'Pay later price (%s)', 'stm_vehicles_listing' ), esc_html( $currency_symbol ) ); ?></span>
						<input type="number" min="0" step="1" name="<?php echo esc_attr( $listing_manager_page->get_id() . '[mvl_rental_pay_later_price]' ); ?>" value="<?php echo esc_attr( $pay_later_price ); ?>" placeholder="<?php echo esc_attr( $currency_symbol . ' 0.00' ); ?>">
					</label>
				</div>
				<div class="mvl-listing-manager-rental-price-labels">
					<label>
						<span><?php esc_html_e( 'Pay now button label', 'stm_vehicles_listing' ); ?></span>
						<input type="text" name="<?php echo esc_attr( $listing_manager_page->get_id() . '[mvl_rental_pay_now_label]' ); ?>" value="<?php echo esc_attr( $pay_now_label ); ?>" placeholder="<?php esc_attr_e( 'Pay now', 'stm_vehicles_listing' ); ?>">
					</label>
					<label>
						<span><?php esc_html_e( 'Pay later button label', 'stm_vehicles_listing' ); ?></span>
						<input type="text" name="<?php echo esc_attr( $listing_manager_page->get_id() . '[mvl_rental_pay_later_label]' ); ?>" value="<?php echo esc_attr( $pay_later_label ); ?>" placeholder="<?php esc_attr_e( 'Pay later', 'stm_vehicles_listing' ); ?>">
					</label>
				</div>
				<?php
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/switch',
					array(
						'id'                 => 'mvl_rental_pay_later_enabled',
						'label'              => __( 'Show Pay Later option', 'stm_vehicles_listing' ),
						'input_name'         => $listing_manager_page->get_id() . '[mvl_rental_pay_later_enabled]',
						'value'              => $pay_later_enabled,
						'description_bottom' => __( 'Display Pay Later pricing and allow Pay Later bookings for this listing.', 'stm_vehicles_listing' ),
					)
				);
				?>
			</div>
		<?php else : ?>
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
					'value'       => $listing_id ? get_post_meta( $listing_id, 'price', true ) : '',
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
					'value'       => $listing_id ? get_post_meta( $listing_id, 'regular_price_label', true ) : '',
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
					'value'       => $listing_id ? get_post_meta( $listing_id, 'regular_price_description', true ) : '',
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
					'value'       => $listing_id ? get_post_meta( $listing_id, 'sale_price', true ) : '',
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
					'value'       => $listing_id ? get_post_meta( $listing_id, 'special_price_label', true ) : '',
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
					'value'       => $listing_id ? get_post_meta( $listing_id, 'instant_savings_label', true ) : '',
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
					'value'              => $listing_id ? get_post_meta( $listing_id, 'car_price_form', true ) : '',
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
					'value'       => $listing_id ? get_post_meta( $listing_id, 'car_price_form_label', true ) : '',
					'type'        => 'text',
				)
			);
			?>
		</div>
		<?php endif; ?>
	</div>
</div>
