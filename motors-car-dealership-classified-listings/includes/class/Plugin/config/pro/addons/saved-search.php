<?php
add_filter(
	'me_search_and_filter_settings_conf',
	function ( $global_conf ) {
		$is_pro = apply_filters( 'is_mvl_pro', false );

		$is_saved_search_enabled = is_mvl_addon_enabled( 'saved_search' );

		if ( $is_pro && $is_saved_search_enabled ) {
			$conf = array(
				'saved_search_send_email' => array(
					'label'       => esc_html__( 'Send Email', 'stm_vehicles_listing' ),
					'description' => __( 'Send periodic email alerts to customers with saved searches about new matching items. Set the alert frequency in days. <br /> Leave empty if you don’t want send email alerts.', 'stm_vehicles_listing' ),
					'type'        => 'number',
					'value'       => '1',
				),
				'saved_searches_amount'   => array(
					'label'       => esc_html__( 'Saved Searches Amount', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Set the maximum number of active saved searches per user.', 'stm_vehicles_listing' ),
					'type'        => 'number',
					'value'       => 5,
				),
				'saved_searches_desc'     => array(
					'type'        => 'notification_message',
					'label'       => '',
					'description' => __( 'To allow users to save searches and receive email alerts for new matching listings, add the Save Search widget<br />on the Search Results page. <a href="https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/saved-searches#adding-save-search-widget-to-the-page" target="_blank">Learn more</a>', 'stm_vehicles_listing' ),
				),
			);
		} else {
			$conf = array(
				'pro_banner_saved_search' => array(
					'type'            => 'pro_banner',
					'label'           => esc_html__( 'Saved Searches', 'stm_vehicles_listing' ),
					'img'             => STM_LISTINGS_URL . '/assets/addons/img/saved-search.png',
					'desc'            => esc_html__( 'Allow your visitors to create saved searches and get email notifications for new items that match their criteria. So they will stay updated with the latest posts in real-time.', 'stm_vehicles_listing' ),
					'is_enable'       => ! $is_saved_search_enabled,
					'search'          => esc_html__( 'Saved Searches', 'stm_vehicles_listing' ),
					'second_btn_text' => esc_html__( 'Learn More', 'stm_vehicles_listing' ),
					'second_btn_link' => 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/saved-searches',
				),
			);
		}

		$conf = array(
			'name'   => esc_html__( 'Saved Searches', 'stm_vehicles_listing' ),
			'fields' => $conf,
		);

		$global_conf['saved_search'] = $conf;

		return $global_conf;
	},
	30,
	1
);
