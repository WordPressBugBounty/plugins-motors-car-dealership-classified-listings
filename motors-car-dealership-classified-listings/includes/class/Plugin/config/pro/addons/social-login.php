<?php

use MotorsNuxy\MotorsNuxyHelpers;

add_filter(
	'mvl_get_all_nuxy_config',
	function ( $global_conf ) {
		$is_pro = apply_filters( 'is_mvl_pro', false );

		$is_social_login_enabled = is_mvl_addon_enabled( 'social_login' );

		if ( $is_pro && $is_social_login_enabled ) {
			$social_login_fields = array(
				/*GROUP STARTED*/
				'social_login_google_enabled'        => array(
					'group'       => 'started',
					'group_title' => esc_html__( 'Google', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'label'       => esc_html__( 'Login via Google', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Allow users to sign in using their Google accounts', 'stm_vehicles_listing' ),
					'value'       => false,
				),
				'social_login_google_client_id'      => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Client ID', 'stm_vehicles_listing' ),
					'description' => sprintf(
						'%1$s <a href="%2$s" target="_blank">%3$s</a>',
						esc_html__( 'You can get Client ID from Google APIs console.', 'stm_vehicles_listing' ),
						'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/social-login',
						esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					),
					'placeholder' => esc_html__( 'Enter your Google Client ID here', 'stm_vehicles_listing' ),
					'value'       => '',
					'dependency'  => array(
						'key'   => 'social_login_google_enabled',
						'value' => 'not_empty',
					),
				),
				'social_login_google_client_secret'  => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Client secret', 'stm_vehicles_listing' ),
					'description' => sprintf(
						'%1$s <a href="%2$s" target="_blank">%3$s</a>',
						esc_html__( 'You can get Secret Key from Google APIs console.', 'stm_vehicles_listing' ),
						'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/social-login',
						esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					),
					'placeholder' => esc_html__( 'Enter your Google Client Secret key here', 'stm_vehicles_listing' ),
					'value'       => '',
					'dependency'  => array(
						'key'   => 'social_login_google_enabled',
						'value' => 'not_empty',
					),
				),
				'social_login_google_redirect_url'   => array(
					'group'       => 'ended',
					'type'        => 'text',
					'label'       => esc_html__( 'Redirect URL', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Copy and paste the redirect URL to the Authorized redirect URL in the Google API Console', 'stm_vehicles_listing' ),
					'value'       => site_url( '/?addon=social_login&provider=google' ),
					'dependency'  => array(
						'key'   => 'social_login_google_enabled',
						'value' => 'not_empty',
					),
					'readonly'    => true,
				),
				/*GROUP STARTED*/
				'social_login_facebook_enabled'      => array(
					'group'       => 'started',
					'group_title' => esc_html__( 'Facebook', 'stm_vehicles_listing' ),
					'type'        => 'checkbox',
					'label'       => esc_html__( 'Login via Facebook', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Allow users to sign in using their Facebook accounts', 'stm_vehicles_listing' ),
					'value'       => false,
				),
				'social_login_facebook_app_id'       => array(
					'type'        => 'text',
					'label'       => esc_html__( 'App ID', 'stm_vehicles_listing' ),
					'description' => sprintf(
						'%1$s <a href="%2$s" target="_blank">%3$s</a>',
						esc_html__( 'You can get App ID from Facebook Developers Page.', 'stm_vehicles_listing' ),
						'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/social-login',
						esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					),
					'placeholder' => esc_html__( 'Enter your Facebook Client Token here', 'stm_vehicles_listing' ),
					'value'       => '',
					'dependency'  => array(
						'key'   => 'social_login_facebook_enabled',
						'value' => 'not_empty',
					),
				),
				'social_login_facebook_app_secret'   => array(
					'type'        => 'text',
					'label'       => esc_html__( 'App Secret', 'stm_vehicles_listing' ),
					'description' => sprintf(
						'%1$s <a href="%2$s" target="_blank">%3$s</a>',
						esc_html__( 'You can get App Secret from Facebook Developers Page.', 'stm_vehicles_listing' ),
						'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/social-login',
						esc_html__( 'Learn more', 'stm_vehicles_listing' ),
					),
					'placeholder' => esc_html__( 'Enter your Facebook Access Token here', 'stm_vehicles_listing' ),
					'value'       => '',
					'dependency'  => array(
						'key'   => 'social_login_facebook_enabled',
						'value' => 'not_empty',
					),
				),
				'social_login_facebook_redirect_url' => array(
					'group'       => 'ended',
					'type'        => 'text',
					'label'       => esc_html__( 'Redirect URL', 'stm_vehicles_listing' ),
					'description' => esc_html__( 'Copy and paste the redirect URL to the Site URL in the Facebook Developers Page', 'stm_vehicles_listing' ),
					'value'       => site_url( '/?addon=social_login&provider=facebook' ),
					'dependency'  => array(
						'key'   => 'social_login_facebook_enabled',
						'value' => 'not_empty',
					),
					'readonly'    => true,
				),
			);
		}

		if ( ! $is_pro || ( $is_pro && ! $is_social_login_enabled ) ) {
			$social_login_fields = array(
				'pro_banner_social_login' => array(
					'type'            => 'pro_banner',
					'label'           => esc_html__( 'Social Login', 'stm_vehicles_listing' ),
					'img'             => STM_LISTINGS_URL . '/assets/addons/img/social-login.png',
					'desc'            => esc_html__( 'Let users log in to your site quickly using their existing Google or Facebook accounts. Forget about remembering passwords – it’s just one click to get started!', 'stm_vehicles_listing' ),
					'is_enable'       => ! $is_social_login_enabled,
					'search'          => esc_html__( 'Social Login', 'stm_vehicles_listing' ),
					'second_btn_text' => esc_html__( 'Learn More', 'stm_vehicles_listing' ),
					'second_btn_link' => 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/motors-pro-addons/social-login',
				),
			);
		}

		$conf = array(
			'name'   => esc_html__( 'Social Login', 'stm_vehicles_listing' ),
			'fields' => $social_login_fields,
		);

		$global_conf[ mvl_modify_key( $conf['name'] ) ] = $conf;

		return $global_conf;
	},
	40,
	1
);
