<?php
add_filter(
	'monetization_settings',
	function ( $conf_for_merge ) {

		if ( apply_filters( 'disable_monetization_featured', false ) ) {
			return $conf_for_merge;
		}

		if ( ! apply_filters( 'is_mvl_pro', false ) ) {
			$conf = array(
				'monetization_settings_banner' => array(
					'type'            => 'pro_banner',
					'label'           => esc_html__( 'Monetization Tools', 'stm_vehicles_listing' ),
					'img'             => STM_LISTINGS_URL . '/assets/images/pro/monetization_settings_banner.png',
					'desc'            => esc_html__( 'Set up payments for listing submissions, charge users to highlight their listings as featured, and monetize dealership registrations on your site. Create multiple income streams and turn your platform into a profitable business!', 'stm_vehicles_listing' ),
					'submenu'         => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
					'second_btn_text' => esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					'second_btn_link' => esc_url( 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-plugin-settings/monetization-pro-feature' ),
				),
			);
		} else {
			$conf = array(
				'dealer_payments_for_featured_listing' => array(
					'label'       => esc_html__( 'Monetize featured listings', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Offer featured listing options to users to appear at the top of search results and highlight', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
				),
				'featured_listing_default_badge'       => array(
					'label'       => esc_html__( 'Featured listing label', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Define the label applied to featured listings', 'stm_vehicles_listing' ),
					'type'        => 'text',
					'value'       => '0',
					'dependency'  => array(
						'key'   => 'dealer_payments_for_featured_listing',
						'value' => 'not_empty',
					),
					'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
				),
				'featured_listing_price'               => array(
					'label'       => esc_html__( 'Price', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Set the price for featuring a listing', 'stm_vehicles_listing' ),
					'type'        => 'text',
					'value'       => '0',
					'dependency'  => array(
						'key'   => 'dealer_payments_for_featured_listing',
						'value' => 'not_empty',
					),
					'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
				),
				'featured_listing_period'              => array(
					'label'       => esc_html__( 'Duration', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Specify the duration for which a listing will be featured in days.', 'stm_vehicles_listing' ),
					'type'        => 'text',
					'value'       => '30',
					'dependency'  => array(
						'key'   => 'dealer_payments_for_featured_listing',
						'value' => 'not_empty',
					),
					'submenu'     => esc_html__( 'Monetization', 'stm_vehicles_listing' ),
				),
			);
		}

		return array_merge( $conf_for_merge, $conf );
	},
	30,
	1
);
