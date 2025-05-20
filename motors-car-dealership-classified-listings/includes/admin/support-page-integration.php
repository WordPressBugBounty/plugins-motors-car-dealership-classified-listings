<?php
require_once STM_LISTINGS_PATH . '/includes/lib/support-page/support-page.php';

add_action(
	'admin_init',
	function() {
		/**
		 * Add Support Page
		 */
		STM_Support_Page::set_api_urls(
			'stm_vehicles_listing',
			array(
				'promo'    => 'https://promo-dashboard.stylemixthemes.com/wp-content/dashboard-promo/motors_posts.json',
				'freemius' => array(
					'plugin_slug' => 'motors-car-dealership-classified-listings-pro',
					'item_id'     => 43,
				),
			)
		);
		STM_Support_Page::set_data(
			'stm_vehicles_listing',
			array(
				'header'     => array(
					array(
						'title' => __( 'Motors Help Center', 'stm_vehicles_listing' ),
					),
				),
				'help_items' => array(
					'documentation' => array(
						'buttons' => array(
							array(
								'href' => 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing',
							),
						),
					),
					'ticket'        => array(
						'has-pro'      => '',
						'has-pro-plus' => defined( 'STM_LISTINGS_PRO_FILE' ) ? true : false,
					),
					'video'         => array(
						'buttons' => array(
							array(
								'href' => 'https://www.youtube.com/playlist?list=PL3Pyh_1kFGGD0Z7F5Ad7LT6xv5LLYFpWU',
							),
						),
					),
					'requests'      => array(
						'buttons' => array(
							array(
								'href' => 'https://stylemixthemes.cnflx.io/boards/motors-car-dealer-rental-classifieds',
							),
						),
					),
					'community'     => array(
						'buttons' => array(
							array(
								'href' => 'https://www.facebook.com/groups/motorstheme',
							),
						),
					),
					'customization' => array(
						'buttons' => array(
							array(
								'href' => 'https://stylemix.net/ticket-form/?utm_source=wpadmin&utm_medium=help_center&utm_campaign=motors_get_quotes',
							),
						),
					),
					'features'      => array(
						'title'        => __( 'Get Motors and Enjoy PRO Features', 'stm_vehicles_listing' ),
						'description'  => __( 'Upgrade now and unlock advanced inventory management, custom search filters, and powerful lead capture tools. List vehicles faster, help buyers find exactly what they need, and keep your sales process organized.', 'stm_vehicles_listing' ),
						'buttons'      => array(
							array(
								'href' => 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wpadmin&utm_medium=help_center&utm_campaign=motors_promo_banner',
							),
							array(
								'href' => 'https://stylemixthemes.com/car-dealer-plugin/?utm_source=wpadmin&utm_medium=help_center&utm_campaign=motors_promo_banner',
							),
						),
						'image'        => STM_LISTINGS_URL . '/includes/lib/support-page/assets/images/feature-bg-motors.jpg',
						'has-pro'      => '',
						'has-pro-plus' => defined( 'STM_LISTINGS_PRO_FILE' ) ? true : false,
					),
					'expert'        => array(
						'buttons' => array(
							array(
								'href' => 'https://stylemix.net/?utm_source=wpadmin&utm_medium=help_center&utm_campaign=motors_hire_us',
							),
						),
					),
				),
				'review'     => array(
					'review_form' => array(
						'has_review' => get_option( 'mvl_feedback_added', false ),
						'buttons'    => array(
							array(
								'href' => 'https://wordpress.org/support/plugin/motors-car-dealership-classified-listings/reviews/?filter=5#new-post',
							),
						),
					),
				),
				'news'       => array(
					'blog_list' => array(
						'category_id' => '395',
						'buttons'     => array(
							array(
								'href' => 'https://stylemixthemes.com/wp/category/motors/',
							),
						),
					),
				),
			)
		);
	}
);
