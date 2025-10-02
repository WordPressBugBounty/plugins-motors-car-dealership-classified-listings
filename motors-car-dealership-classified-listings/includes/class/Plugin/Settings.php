<?php
namespace MotorsVehiclesListing\Plugin;

require_once STM_LISTINGS_PATH . '/includes/class/Plugin/settings_patch.php';

class Settings {

	private $assets_url = STM_LISTINGS_URL . '/includes/class/Plugin/assets/img/';

	public static $disabled_pro_text = '';
	public static $pro_plans_url     = '';

	public function __construct() {

		add_action( 'init', array( $this, 'init_strings' ) );
		add_action( 'init', array( $this, 'mvl_plugin_conf_autoload' ) );
		if ( apply_filters( 'stm_disable_settings_setup', true ) ) {
			add_action( 'wpcfto_after_settings_saved', array( $this, 'mvl_save_featured_as_term' ), 50, 2 );
			add_filter( 'wpcfto_options_page_setup', array( $this, 'mvl_settings' ) );
			add_action( 'stm_importer_done', array( $this, 'mlv_save_settings' ), 20, 1 );
			add_filter( 'wpcfto_icons_set', array( $this, 'icons_set_icon_picker' ) );
			add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', array( $this, 'mvl_add_submenus' ), 10, 1 );
			add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', array( $this, 'mvl_add_submenu_settings' ), 1000, 1 );
			add_action( 'wpcfto_screen_motors_vehicles_listing_plugin_settings_added', array( $this, 'mvl_add_submenu_upgrade' ), 1001, 1 );
			add_action( 'wp_ajax_mvl_ajax_add_feedback', array( $this, 'ajax_add_feedback' ) );
			if ( isset( $_GET['page'] ) && 'mvl_plugin_settings' === $_GET['page'] && ! get_option( 'mvl_feedback_added', false ) ) {
				add_action( 'wpcfto_after_tab_nav', array( $this, 'mvl_add_feedback_button' ) );
				add_action( 'wpcfto_after_tab_nav', array( $this, 'mvl_add_feedback_assets' ) );
				add_action( 'admin_footer', array( $this, 'render_feedback_popup' ) );
			}
			if ( isset( $_GET['page'] ) && 'stm-support-page-motors' === $_GET['page'] ) {
				add_action( 'admin_footer', array( $this, 'mvl_add_feedback_assets' ) );
			}
			add_action( 'wpcfto_after_tab_nav', array( $this, 'mvl_add_version' ) );
		}
	}

	public function init_strings() {
		self::$disabled_pro_text = esc_html__( 'Please enable Motors Pro Plugin', 'stm_vehicles_listing' );
		self::$pro_plans_url     = 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wp-admin&utm_medium=push&utm_campaign=motors&utm_content=gopro';
	}

	public function icons_set_icon_picker( $icon_set ) {
		$_icons = stm_get_cat_icons( 'motors-icons', true );

		if ( ! empty( $_icons ) ) {
			return array_merge( $icon_set, $_icons );
		}

		return $icon_set;
	}

	public function mvl_plugin_conf_autoload() {
		$config_map = array(
			'listing-settings',
			'listing-settings/general',
			'listing-settings/currency',
			'listing-settings/listing-card',
			'listing-settings/listing-card-certified-logo',
			'search-settings',
			'search-settings/filter-position',
			'search-settings/sorting',
			'search-settings/filter-features',
			'search-settings/general',
			'single-listing',
			'single-listing/general',
			'single-listing/single-listing-layout',
			'appearance',
			'user-main',
			'user-settings/user-settings',
			'monetization',
			'pages',
			'pages-settings/pages-settings',
			'google-services',
			'google-services/recaptcha-settings',
		);

		if ( ! defined( 'STM_MOTORS_EXTENDS_PLUGIN_VERSION' ) || version_compare( STM_MOTORS_EXTENDS_PLUGIN_VERSION, '2.3.7' ) > 0 ) {
			$config_map = array_merge(
				$config_map,
				array(
					'pro/listing-settings/general',
					'pro/listing-settings/currency',
					'pro/listing-settings/listing-card',
					'pro/search-settings/inventory-templates',
					'pro/search-settings/filter-position',
					'pro/search-settings/sorting',
					'pro/search-settings/search',
					'pro/search-settings/listing-card',
					'pro/single-listing/general',
					'pro/single-listing/single-listing-layout',
					'pro/single-listing/loan-calculator',
					'pro/single-listing/single-listing-skins',
					'pro/user-settings/user-main',
					'pro/user-settings/dealer-settings',
					'pro/pages-settings/pages-settings',
					'pro/monetization/subscription-model',
					'pro/monetization/become-a-dealer',
					'pro/monetization/paid-submission',
					'pro/monetization/featured-settings',
					'pro/monetization/paypal-options',
					'pro/monetization/sell-online',
					'pro/google-services/google-maps',
				),
			);
		}

		$config_map = array_merge(
			$config_map,
			array(
				'pro/search-settings/filter-location',
			),
		);

		if ( ! stm_is_motors_theme() ) {
			$config_map = array_merge(
				$config_map,
				array(
					'shortcodes',
					'shortcodes/shortcodes-list',
				)
			);
		}

		if ( defined( 'STM_LISTINGS_PRO_PATH' ) || ! stm_is_motors_theme() ) {
			$config_map = array_merge(
				$config_map,
				array(
					'pro/addons/social-login',
					'pro/addons/saved-search',
				)
			);
		}

		foreach ( $config_map as $file_name ) {
			require_once STM_LISTINGS_PATH . '/includes/class/Plugin/config/' . $file_name . '.php';
		}
	}

	public function mvl_settings( $setup ) {
		$opts = apply_filters( 'mvl_get_all_nuxy_config', array() );

		$motors_favicon = $this->assets_url . 'icon.png';
		$motors_logo    = $this->assets_url . 'logo.png';

		$setup[] = array_merge(
			array(
				'option_name'     => MVL_Const::MVL_PLUGIN_OPT_NAME,
				'title'           => esc_html__( 'Motors Plugin', 'stm_vehicles_listing' ),
				'sub_title'       => esc_html__( 'by StylemixThemes', 'stm_vehicles_listing' ),
				'logo'            => $motors_logo,

				'additional_link' => array(
					'text'   => esc_html__( 'Feature Request', 'stm_vehicles_listing' ),
					'icon'   => 'fa-regular fa-star',
					'url'    => esc_url( 'https://stylemixthemes.cnflx.io/boards/motors-car-dealer-rental-classifieds' ),
					'target' => true,
				),

				'page'            => array(
					'page_title' => 'Motors Plugin',
					'menu_title' => 'Motors Plugin',
					'menu_slug'  => 'mvl_plugin_settings',
					'icon'       => $motors_favicon,
					'position'   => 4,
				),

				'fields'          => $opts,
			),
			apply_filters( 'mvl_get_conf_header_links', array() )
		);

		return $setup;
	}

	public function mvl_add_submenus() {

		$options = get_option( 'stm_post_types_options' );

		$post_types = array(
			'listings',
		);

		foreach ( $post_types as $post_type ) {
			$post_type_data = get_post_type_object( $post_type );

			if ( empty( $post_type_data ) ) {
				continue;
			}

			add_submenu_page(
				'mvl_plugin_settings',
				$post_type_data->label,
				$post_type_data->label,
				'manage_options',
				'/edit.php?post_type=' . $post_type,
				'',
				1
			);

			add_filter(
				'mvl_submenu_positions',
				function ( $positions ) use ( $post_type_data ) {
					$positions[ '/edit.php?post_type=' . $post_type_data->name ] = 3;

					return $positions;
				}
			);
		}

		//Add submenu for test_drive_request
		$test_drive_data = get_post_type_object( 'test_drive_request' );
		if ( ! empty( $test_drive_data ) ) {
			add_submenu_page(
				'mvl_plugin_settings',
				ucwords( $test_drive_data->label ),
				ucwords( $test_drive_data->label ),
				'manage_options',
				'/edit.php?post_type=' . $test_drive_data->name,
				'',
				10
			);

			add_filter(
				'mvl_submenu_positions',
				function ( $positions ) use ( $test_drive_data ) {
					$positions[ 'edit.php?post_type=' . $test_drive_data->name ] = 20;

					return $positions;
				}
			);
		}

		$dealer_reviews_data = get_post_type_object( 'dealer_review' );
		if ( ! empty( $dealer_reviews_data ) && apply_filters( 'is_mvl_pro', false ) && ! apply_filters( 'stm_is_motors_theme', false ) ) {
			add_submenu_page(
				'mvl_plugin_settings',
				ucwords( $dealer_reviews_data->label ),
				ucwords( $dealer_reviews_data->label ),
				'manage_options',
				'/edit.php?post_type=' . $dealer_reviews_data->name,
				'',
				10
			);

			add_filter(
				'mvl_submenu_positions',
				function ( $positions ) use ( $dealer_reviews_data ) {
					$positions[ 'edit.php?post_type=' . $dealer_reviews_data->name ] = 20;

					return $positions;
				}
			);
		}
	}

	public function mvl_add_submenu_settings() {
		add_submenu_page(
			'mvl_plugin_settings',
			esc_html__( 'Settings', 'stm_vehicles_listing' ),
			'<span class="mvl-settings-menu-title">' . esc_html__( 'Settings', 'stm_vehicles_listing' ) . '</span>',
			'manage_options',
			'mvl_plugin_settings',
			'',
			100
		);

		add_submenu_page(
			'mvl_plugin_settings',
			esc_html__( 'Help Center', 'stm_vehicles_listing' ),
			'<span style="color: #FC7B40; font-weight: 700;">' . esc_html__( 'Help Center', 'stm_vehicles_listing' ) . '</span>',
			'manage_options',
			'stm-support-page-motors',
			function () {
				\STM_Support_Page::render_support_page( 'stm_vehicles_listing' );
			}
		);

		/* phpcs:disable */
		/*add_submenu_page(
			'mvl_plugin_settings',
			__( 'Pro Addons', 'stm_vehicles_listing' ),
			'<span class="mvl-addons-menu"><span class="mvl-addons-pro">PRO</span> <span class="mvl-addons-text">'
			. __( 'Addons', 'stm_vehicles_listing' ) . '</span></span>',
			'manage_options',
			'stm-addons',
			array( $this, 'addons_page' ),
		);*/
		/* phpcs:enable */
	}

	public function mvl_add_submenu_upgrade() {

		if ( apply_filters( 'stm_hide_pro_if_is_premium_theme', false ) ) {
			return;
		}

		if ( ! is_mvl_pro() ) {
			add_submenu_page(
				'mvl_plugin_settings',
				__( 'Upgrade', 'stm_vehicles_listing' ),
				'<span class="mvl-unlock-pro-btn"><span class="mvl-unlock-wrap-span">' . __( 'Unlock PRO', 'stm_vehicles_listing' ) . '</span></span>',
				'manage_options',
				'mvl-go-pro',
				array( $this, 'mvl_render_go_pro' ),
				5000
			);
		}
	}

	public function mvl_render_go_pro() {
		wp_enqueue_style( 'mvl_go_pro', STM_LISTINGS_URL . 'assets/css/admin_button_gopro.css', null, STM_LISTINGS_V );

		require_once STM_LISTINGS_PATH . '/templates/button-go-pro.php';
	}

	public function addons_page() {

	}

	public function mvl_save_featured_as_term( $id, $settings ) {

		if ( array_key_exists( 'addl_user_features', $settings ) ) {
			foreach ( $settings['addl_user_features'] as $addl_user_feature ) {
				if ( ! empty( $addl_user_feature['tab_title_labels'] ) ) {
					$feature_list = explode( ',', $addl_user_feature['tab_title_labels'] );

					foreach ( $feature_list as $item ) {
						wp_insert_term( trim( $item ), 'stm_additional_features' );
					}
				}
			}
		}
	}

	public function mlv_save_settings() {
		$layout         = get_option( 'stm_motors_chosen_template', '' );
		$theme_settings = get_option( 'wpcfto_motors_' . $layout . '_settings', array() );

		update_option( 'motors_vehicles_listing_plugin_settings_updated', true );
		update_option( 'motors_vehicles_listing_section_settings_updated', true );
		update_option( MVL_Const::MVL_PLUGIN_OPT_NAME, $theme_settings );

		if ( ! empty( $theme_settings ) ) {
			$add_car_form_settings_map     = wpcfto_get_settings_map( 'settings', MVL_Const::ADD_CAR_FORM_OPT_NAME );
			$filter_settings_map           = wpcfto_get_settings_map( 'settings', MVL_Const::FILTER_OPT_NAME );
			$listing_details_settings_map  = wpcfto_get_settings_map( 'settings', MVL_Const::LISTING_DETAILS_OPT_NAME );
			$search_result_settings_map    = wpcfto_get_settings_map( 'settings', MVL_Const::SEARCH_RESULTS_OPT_NAME );
			$listing_template_settings_map = wpcfto_get_settings_map( 'settings', MVL_Const::LISTING_TEMPLATE_OPT_NAME );

			if ( ! empty( $add_car_form_settings_map ) ) {
				update_option( MVL_Const::ADD_CAR_FORM_OPT_NAME, array_intersect_key( $theme_settings, $add_car_form_settings_map['add_listing']['fields'] ) );
			}

			if ( ! empty( $filter_settings_map ) ) {
				update_option( MVL_Const::FILTER_OPT_NAME, array_intersect_key( $theme_settings, $filter_settings_map['search_settings']['fields'] ) );
			}

			if ( ! empty( $listing_details_settings_map ) ) {
				update_option( MVL_Const::LISTING_DETAILS_OPT_NAME, array_intersect_key( $theme_settings, $listing_details_settings_map['single_listing']['fields'] ) );
			}

			if ( ! empty( $search_result_settings_map ) ) {
				update_option( MVL_Const::SEARCH_RESULTS_OPT_NAME, array_intersect_key( $theme_settings, $search_result_settings_map['listing_settings']['fields'] ) );
			}

			if ( ! empty( $listing_template_settings_map ) ) {
				update_option( MVL_Const::LISTING_TEMPLATE_OPT_NAME, array_intersect_key( $theme_settings, $listing_template_settings_map['single_listing_template']['fields'] ) );
			}

			update_option( 'motors_vehicles_listing_section_settings_updated', true );
		}
	}

	public function mvl_add_version() {
		$output = '<div class="mvl-plugin-version">' . esc_html__( 'Version ', 'stm_vehicle_listings' ) . esc_html( STM_LISTINGS_V ) . '</div>';
		echo wp_kses_post( $output );
	}

	public function mvl_add_feedback_assets() {
		wp_enqueue_style( 'mvl-feedback', STM_LISTINGS_URL . '/assets/css/feedback.css', array(), STM_LISTINGS_V );
		wp_enqueue_script( 'mvl-feedback', STM_LISTINGS_URL . '/assets/js/admin/feedback.js', array(), STM_LISTINGS_V, true );
	}

	public function mvl_add_feedback_button() {
		echo '<div class="mvl-feedback"><a href="#" class="mvl-feedback-button">' . esc_html__( 'Feedback', 'stm_vehicle_listings' ) . '<img src="' . esc_url( STM_LISTINGS_URL . '/includes/class/Plugin/assets/img/feedback.svg' ) . '" alt="Feedback Icon"></a></div>';
	}

	public function render_feedback_popup() {
		$feedback_template = STM_LISTINGS_PATH . '/includes/class/Plugin/feedback.php';
		if ( file_exists( $feedback_template ) ) {
			require_once $feedback_template;
		}
	}

	public static function get_ticket_url() {
		$type = defined( 'STM_LISTINGS_PATH' ) ? 'support' : 'pre-sale';
		return "https://support.stylemixthemes.com/tickets/new/{$type}?item_id=43";
	}

	public function ajax_add_feedback() {
		update_option( 'mvl_feedback_added', true );
	}


	public static function update( $new_settings ) {
		$settings = get_option( MVL_Const::MVL_PLUGIN_OPT_NAME, array() );
		update_option( MVL_Const::MVL_PLUGIN_OPT_NAME, wp_parse_args( $new_settings, $settings ) );
	}

	public static function update_one( $setting, $value ) {
		static::update( array( $setting => $value ) );
	}
}
