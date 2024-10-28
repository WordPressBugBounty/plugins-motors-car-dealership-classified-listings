<?php
add_action(
	'mvl_setup_wizard_nav_steps',
	function() {
		require_once MVL_SETUP_WIZARD_TEMPLATES_PATH . 'nav-steps.php';
	}
);

add_filter(
	'mvl_setup_wizard_steps_data',
	function() {
		return array(
			'theme'          => array(
				'title'    => 'Choose Theme',
				'template' => '',
			),
			'plugins'        => array(
				'title'    => 'Install plugins',
				'template' => '',
			),
			'demo-content'   => array(
				'title'    => 'Demo content',
				'template' => '',
			),
			'search-results' => array(
				'title'    => 'Search results',
				'template' => '',
			),
			'single-listing' => array(
				'title'    => 'Single Listing',
				'template' => '',
			),
			'profile'        => array(
				'title'    => 'Profile',
				'template' => '',
			),
			'finish'         => array(
				'title'    => 'Finish',
				'template' => '',
			),
		);
	},
);

add_filter(
	'mvl_setup_wizard_steps_data',
	function( $steps ) {
		if ( ! defined( 'MOTORS_STARTER_THEME_VERSION' ) && ! defined( 'ELEMENTOR_VERSION' ) ) {
			unset( $steps['single-listing'] );
		}
		return $steps;
	},
	12
);

add_filter(
	'mvl_setup_wizard_steps_data',
	function( $steps ) {
		foreach ( $steps as $slug => $step ) {
			$steps[ $slug ]['url'] = apply_filters( 'mvl_setup_wizard_step_url', $slug );
		}
		return $steps;
	},
	15
);

add_action(
	'mvl_setup_wizard_load_step',
	function( $step, $data = array() ) {
		if ( empty( $step ) ) {
			if ( ! empty( $_GET['step'] ) ) {
				$step = sanitize_file_name( $_GET['step'] );
			} else {
				$step = 'welcome';
			}
		}

		include MVL_SETUP_WIZARD_TEMPLATES_PATH . 'steps/' . $step . '.php';

	}
);

add_filter(
	'mvl_setup_wizard_data',
	function() {
		$default_fields = array(
			'use_starter'   => intval( defined( 'MOTORS_STARTER_THEME_VERSION' ) ),
			'use_elementor' => intval( defined( 'ELEMENTOR_VERSION' ) ),
			'use_pro'       => intval( defined( 'STM_LISTINGS_PRO_V' ) ),
			'data_imported' => 0,
		);
		$settings = get_option( 'mvl_setup_wizard_data', array() );
		if ( ! is_array( $settings ) ) {
			$settings = unserialize( $settings );
		}
		$settings = array_merge( $default_fields, $settings );

		return $settings;
	}
);

add_action(
	'mvl_setup_wizard_data_fields',
	function() {
		$settings = apply_filters( 'mvl_setup_wizard_data', array() );
		?>
		<div id="mvl-setup-wizard-data">
			<form>
				<?php foreach ( $settings as $name => $value ) : ?>
					<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
				<?php endforeach; ?>
			</form>
		</div>
		<?php
	}
);

add_filter( 'mvl_setup_wizard_step_url', 'get_url_to_step', 10, 1 );
function get_url_to_step( $step ) {
	return add_query_arg( array( 'step' => esc_attr( $step ) ), admin_url( 'admin.php?page=mvl-welcome-setup' ) );
}

add_action(
	'mvl_setup_wizard_plugins_recommended',
	function() {
		return array(
			array(
				'slug'      => 'contact-form-7',
				'title'     => 'Contact Form 7',
				'required'  => true,
				'installed' => false,
				'active'    => false,
			),
			array(
				'slug'      => 'elementor',
				'title'     => 'Elementor',
				'required'  => true,
				'installed' => false,
				'active'    => false,
			),
			array(
				'slug'      => 'header-footer-elementor',
				'title'     => 'Elementor Header & Footer Builder',
				'required'  => true,
				'installed' => false,
				'active'    => false,
			),
		);
	}
);

add_action(
	'mvl_setup_wizard_plugins_recommended',
	function( $plugins ) {

		foreach ( $plugins as $key => $plugin ) {
			if ( is_plugin_active( apply_filters( 'mvl_setup_wizard_plugin_main_file', $plugin['slug'] ) ) ) {
				$plugins[ $key ]['active']    = true;
				$plugins[ $key ]['installed'] = true;
			}
			$plugins[ $key ]['downloaded'] = file_exists( WP_PLUGIN_DIR . '/' . $plugin['slug'] . '/' );
		}

		return $plugins;
	},
	15
);

function mvl_plugin_main_file( $plugin ) {
	$plugin_file_data = ( is_dir( WP_PLUGIN_DIR . '/' . $plugin ) ) ? get_plugins( '/' . $plugin ) : array();

	if ( ! empty( $plugin_file_data ) ) {
		$plugin_file = array_keys( $plugin_file_data );
		$plugin_path = $plugin . '/' . $plugin_file[0];
	} else {
		$plugin_path = false;
	}

	return $plugin_path;
}
add_filter( 'mvl_setup_wizard_plugin_main_file', 'mvl_plugin_main_file' );

add_action(
	'mvl_settings_option_names',
	function() {
		return array(
			\MotorsVehiclesListing\Plugin\MVL_Const::MVL_PLUGIN_OPT_NAME,
			\MotorsVehiclesListing\Plugin\MVL_Const::ADD_CAR_FORM_OPT_NAME,
			\MotorsVehiclesListing\Plugin\MVL_Const::FILTER_OPT_NAME,
			\MotorsVehiclesListing\Plugin\MVL_Const::LISTING_DETAILS_OPT_NAME,
			\MotorsVehiclesListing\Plugin\MVL_Const::SEARCH_RESULTS_OPT_NAME,
			\MotorsVehiclesListing\Plugin\MVL_Const::LISTING_TEMPLATE_OPT_NAME,
		);
	},
);

add_action(
	'mvl_check_if',
	function( $setting_name, $value = true ) {
		$current_value = apply_filters( 'motors_vl_get_nuxy_mod', false, $setting_name );
		if ( $current_value == $value ) {
			echo esc_attr( ' checked' );
		}
	},
	10,
	2,
);

add_filter(
	'mvl_import_data_url',
	function( $file = '' ) {
		if ( defined( 'STM_DEV_MODE' ) && STM_DEV_MODE && defined( 'MOTORS_STARTER_THEME_TEMPLATE_URI' ) ) {
			$subfolder = ( 'starter_import.xml' === $file ) ? 'elementor/' : '';
			return MOTORS_STARTER_THEME_TEMPLATE_URI . '/includes/demo/' . $subfolder . $file;
		}
		return 'https://motors-plugin.stylemixthemes.com/starter-theme-demo/' . $file;
	},
	10,
	1,
);

add_filter(
	'mvl_essential_pages',
	function() {
		return array(
			'inventory'     => array(
				'title'          => 'Inventory',
				'shortcode'      => '[motors_listing_inventory]',
				'elementor-data' => '[{"id":"5e6894b","elType":"section","settings":{"structure":"21","margin":{"unit":"px","top":"40","right":0,"bottom":"40","left":0,"isLinked":true},"margin_tablet":{"unit":"px","top":"0","right":0,"bottom":"30","left":0,"isLinked":false},"margin_mobile":{"unit":"px","top":"0","right":0,"bottom":"30","left":0,"isLinked":false}},"elements":[{"id":"77b7a34","elType":"column","settings":{"_column_size":33,"_inline_size":26,"_inline_size_tablet":100},"elements":[{"id":"00dc825","elType":"widget","settings":{"isf_title":"Search Options","reset_btn_label":"Reset All","isf_box_padding":{"unit":"px","top":"28","right":"28","bottom":"28","left":"28","isLinked":true},"isf_bg":"","isf_icon_position":"right","isf_price_single":"yes","reset_btn_icon":{"value":"fas fa-undo","library":"fa-solid"},"isf_general_box_shadow_box_shadow_type":"yes","isf_general_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":15,"spread":2,"color":"rgba(0, 0, 0, 0.27)"},"isf_general_select_color":"#ECEFF3","isf_general_bg_background":"classic","isf_general_bg_color":"#35475A","isf_btn_bg":"#02010100","isf_btn_box_shadow_box_shadow_type":"yes","isf_btn_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":0,"spread":0,"color":"rgba(0,0,0,0.5)"},"isf_btn_bg_hover":"","isf_btn_typography_typography":"custom","isf_btn_typography_line_height":{"unit":"px","size":34,"sizes":[]},"text_align":"flex-end","isf_button_padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":false},"_padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"isf_reset_icon_size":{"unit":"px","size":13,"sizes":[]},"isf_btn_typography_font_weight":"400","isf_btn_typography_text_transform":"capitalize","isf_second_button_padding":{"unit":"px","top":"15","right":"15","bottom":"15","left":"15","isLinked":true},"isf_mobile_btn_bg":"#1280DF","isf_mobile_btn_box_shadow_box_shadow_type":"yes","isf_mobile_btn_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":0,"spread":0,"color":"rgba(0,0,0,0.5)"},"isf_mobile_btn_typography_typography":"custom","isf_mobile_filter_heading_text_color":"#FFFFFF","isf_mobile_filter_close_btn":"#FFFFFF","isf_mobile_filter_bg":"#35475A","isf_mobile_reset_btn_border_border":"solid","isf_mobile_reset_border_radius":{"unit":"px","top":"4","right":"4","bottom":"4","left":"4","isLinked":true},"isf_mobile_reset_btn_bg":"#FFFFFF","isf_mobile_reset_btn_text_color":"#000000","isf_mobile_reset_icon_size":{"unit":"px","size":14,"sizes":[]},"isf_mobile_reset_btn_typography_typography":"custom","isf_mobile_reset_btn_typography_font_size_mobile":{"unit":"px","size":14,"sizes":[]},"isf_mobile_reset_btn_typography_font_weight":"400","isf_mobile_reset_btn_typography_text_transform":"capitalize","text_mobile_reset_align":"right","isf_mobile_reset_button_padding":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_mobile_second_filter_border_color":"#1BC744","isf_mobile_checkbox_label_color":"#090909","isf_mobile_pal_icon_color":"#1BC744","isf_mobile_pal_link_color":"#131313","isf_mobile_pal_amount_color":"#1BC744","isf_mobile_second_btn_bg":"#1BC744","isf_mobile_secondary_filter_bg":"#FFFFFF","isf_show_cars_btn_box_shadow_box_shadow_type":"yes","isf_show_cars_btn_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":0,"spread":0,"color":"rgba(0,0,0,0.5)"},"isf_mobile_reset_btn_border_width_mobile":{"unit":"px","top":"1","right":"1","bottom":"1","left":"1","isLinked":true},"isf_mobile_reset_btn_border_color":"#DBDBDB","isf_mobile_results_btn_text":"Show {{total}} Cars","isf_select_text_color":"#000000","isf_slider_text_color":"#FFFFFF","search_options_icon":{"value":"fas fa-search","library":"fa-solid"},"isf_slider_range-color_tablet":"","isf_slider_range-color_mobile":"","isf_general_select_color_tablet":"","isf_general_select_color_mobile":"","isf_general_input_color_tablet":"","isf_general_input_color_mobile":"","isf_select_text_color_tablet":"","isf_select_text_color_mobile":"","isf_slider_text_color_tablet":"","isf_slider_text_color_mobile":"","isf_field_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"isf_general_select_color_active_tablet":"","isf_general_select_color_active_mobile":"","isf_general_input_color_active_tablet":"","isf_general_input_color_active_mobile":"","isf_select_text_color_active_tablet":"","isf_select_text_color_active_mobile":"","isf_field_border_radius_active_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_field_border_radius_active_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_box_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_box_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_bg_tablet":"","isf_bg_mobile":"","isf_icon_size_tablet":{"unit":"px","size":"","sizes":[]},"isf_icon_size_mobile":{"unit":"px","size":"","sizes":[]},"isf_icon_color_tablet":"","isf_icon_color_mobile":"","isf_title_text_color_tablet":"","isf_title_text_color_mobile":"","isf_btn_border_radius_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_btn_border_radius_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_btn_bg_tablet":"","isf_btn_bg_mobile":"","isf_btn_text_color_tablet":"","isf_btn_text_color_mobile":"","isf_btn_border_radius_hover_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_btn_border_radius_hover_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_btn_bg_hover_tablet":"","isf_btn_bg_hover_mobile":"","isf_btn_text_color_hover_tablet":"","isf_btn_text_color_hover_mobile":"","isf_reset_icon_size_tablet":{"unit":"px","size":"","sizes":[]},"isf_reset_icon_size_mobile":{"unit":"px","size":"","sizes":[]},"isf_reset_icon_margin_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_reset_icon_margin_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"text_align_tablet":"","text_align_mobile":"","isf_button_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_button_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_filter_border_color_tablet":"","isf_second_filter_border_color_mobile":"","isf_second_label_color_tablet":"","isf_second_label_color_mobile":"","isf_second_label_bg_color_tablet":"","isf_second_label_bg_color_mobile":"","isf_collapse_indicator_bg_tablet":"","isf_collapse_indicator_bg_mobile":"","isf_collapse_indicator_hover_bg_tablet":"","isf_collapse_indicator_hover_bg_mobile":"","isf_checkbox_label_color_tablet":"","isf_checkbox_label_color_mobile":"","isf_second_box_shadow_box_shadow_type":"","isf_second_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"isf_second_box_shadow_box_shadow_position":" ","isf_pal_icon_color_tablet":"","isf_pal_icon_color_mobile":"","isf_pal_link_color_tablet":"","isf_pal_link_color_mobile":"","isf_pal_amount_color_tablet":"","isf_pal_amount_color_mobile":"","isf_pal_icon_color_hover_tablet":"","isf_pal_icon_color_hover_mobile":"","isf_pal_link_color_hover_tablet":"","isf_pal_link_color_hover_mobile":"","isf_pal_amount_color_hover_tablet":"","isf_pal_amount_color_hover_mobile":"","isf_secondary_field_color":"#eceff3","isf_secondary_field_color_tablet":"","isf_secondary_field_color_mobile":"","isf_secondary_field_text_color_tablet":"","isf_secondary_field_text_color_mobile":"","isf_secondary_field_border_radius_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_secondary_field_border_radius_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_secondary_field_color_active_tablet":"","isf_secondary_field_color_active_mobile":"","isf_secondary_field_text_color_active_tablet":"","isf_secondary_field_text_color_active_mobile":"","isf_secondary_field_border_radius_active":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_secondary_field_border_radius_active_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_secondary_field_border_radius_active_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_btn_bg_tablet":"","isf_second_btn_bg_mobile":"","isf_second_btn__border_border":"","isf_second_btn__border_width":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_btn__border_width_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_btn__border_width_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_btn__border_color":"","isf_second_btn_box_shadow_box_shadow_type":"","isf_second_btn_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"isf_second_btn_box_shadow_box_shadow_position":" ","isf_second_btn_text_color_tablet":"","isf_second_btn_text_color_mobile":"","isf_second_btn_bg_hover_tablet":"","isf_second_btn_bg_hover_mobile":"","isf_second_btn_border_hover_border":"","isf_second_btn_border_hover_width":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_btn_border_hover_width_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_btn_border_hover_width_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_btn_border_hover_color":"","isf_second_btn_box_shadow_hover_box_shadow_type":"","isf_second_btn_box_shadow_hover_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0,0,0,0.5)"},"isf_second_btn_box_shadow_hover_box_shadow_position":" ","isf_second_btn_text_color_hover_tablet":"","isf_second_btn_text_color_hover_mobile":"","isf_second_button_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_second_button_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_mobile_btn_typography_font_size":{"unit":"px","size":14,"sizes":[]},"isf_mobile_btn_typography_font_weight":"700","isf_mobile_btn_typography_text_transform":"uppercase","isf_mobile_btn_typography_line_height":{"unit":"px","size":14,"sizes":[]},"isf_mobile_sticky_wrapper_bg":"#fffff","isf_mobile_second_btn_typography_font_size":{"unit":"px","size":14,"sizes":[]},"isf_mobile_second_btn_typography_font_weight":"700","isf_mobile_second_btn_typography_text_transform":"uppercase","isf_mobile_second_btn_typography_line_height":{"unit":"px","size":14,"sizes":[]},"isf_mobile_sticky_panel_btn_bg":"#EC0000","isf_mobile_sticky_panel_btn_bg_tablet":"","isf_mobile_sticky_panel_btn_bg_mobile":"","isf_show_cars_text_color_tablet":"","isf_show_cars_text_color_mobile":"","isf_show_cars_button_padding_tablet":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_show_cars_button_padding_mobile":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"border":{"top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"isf_title_typography_typography":"custom","isf_title_typography_font_size":{"unit":"px","size":20,"sizes":[]},"isf_title_typography_font_family":"Montserrat","isf_title_typography_font_weight":"700","isf_btn_border_radius":{"unit":"px","top":"","right":"","bottom":"","left":"","isLinked":true},"isf_mobile_btn_typography_font_family":"Montserrat","isf_mobile_select_text_color":"#000000","isf_mobile_slider_text_color":"#FFFFFF","isf_mobile_show_cars_btn_bg":"#1280DF","isf_mobile_show_cars_border_radius":{"unit":"px","top":"4","right":"4","bottom":"4","left":"4","isLinked":true},"isf_mobile_show_cars_btn_border_border":"none","isf_show_cars_typography_typography":"custom","isf_show_cars_typography_font_size_mobile":{"unit":"px","size":14,"sizes":[]},"isf_show_cars_text_color":"#FFFFFF"},"elements":[],"widgetType":"motors-inventory-search-filter"}],"isInner":false},{"id":"5608ae4","elType":"column","settings":{"_column_size":66,"_inline_size":73.665,"_inline_size_tablet":100,"margin_mobile":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":false},"margin_tablet":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":false}},"elements":[{"id":"3555dcc","elType":"section","settings":{"structure":"30","border_border":"solid","border_width":{"unit":"px","top":"0","right":"0","bottom":"1","left":"0","isLinked":false},"border_color":"#D5D4D4","border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":false},"margin":{"unit":"px","top":"0","right":0,"bottom":"30","left":0,"isLinked":false},"padding":{"unit":"px","top":"0","right":"0","bottom":"20","left":"0","isLinked":false}},"elements":[{"id":"5b149e7","elType":"column","settings":{"_column_size":33,"_inline_size":24,"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_tablet":25},"elements":[],"isInner":true},{"id":"0b3200c","elType":"column","settings":{"_column_size":33,"_inline_size":65.333,"text_align":"right","margin":{"unit":"px","top":"0","right":"10","bottom":"0","left":"0","isLinked":false},"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_tablet":63,"_inline_size_mobile":70,"_inline_size_tablet_extra":50,"text_align_mobile":"left"},"elements":[{"id":"7bfc745","elType":"widget","settings":{"_padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true}},"elements":[],"widgetType":"motors-inventory-sort-by"}],"isInner":true},{"id":"0d1f0cf","elType":"column","settings":{"_column_size":33,"_inline_size":10,"text_align":"left","padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_tablet":12,"_inline_size_mobile":26,"content_position_mobile":"bottom","_inline_size_tablet_extra":15,"text_align_mobile":"right"},"elements":[{"id":"f9bb171","elType":"widget","settings":[],"elements":[],"widgetType":"motors-inventory-view-type"}],"isInner":true}],"isInner":true},{"id":"1de5f1c","elType":"widget","settings":{"grid_thumb_height_tablet":{"unit":"px","size":"","sizes":[]},"grid_thumb_height_mobile":{"unit":"px","size":"","sizes":[]},"isr_pagination_item_bg_tablet":"","isr_pagination_item_bg_mobile":"","isr_pagination_active_item_bg_tablet":"","isr_pagination_active_item_bg_mobile":"","isr_pagination_item_bg":"#1280DF","isr_pagination_active_item_bg":"#DBDBDB","ppp_on_list":6,"ppp_on_grid":6,"grid_thumb_height":{"unit":"px","size":150,"sizes":[]}},"elements":[],"widgetType":"motors-inventory-search-results"}],"isInner":false}],"isInner":false}]',
			),
			'compare'       => array(
				'title'          => 'Compare',
				'shortcode'      => '[motors_compare_page]',
				'elementor-data' => '[{"id":"c5cc32c","elType":"section","settings":[],"elements":[{"id":"67431a2","elType":"column","settings":{"_column_size":100,"_inline_size":null},"elements":[{"id":"b4a7f7b","elType":"widget","settings":{"compare_title":"Compare vehicles","add_item_label":"Add Car To Compare","title_typography_typography":"custom"},"elements":[],"widgetType":"motors-listings-compare"}],"isInner":false}],"isInner":false}]',
			),
			'add-car'       => array(
				'title'          => 'Add Listing',
				'shortcode'      => '[motors_add_listing_form]',
				'elementor-data' => '[{"id":"0dffa33","elType":"section","settings":{"margin":{"unit":"px","top":"30","right":0,"bottom":"0","left":0,"isLinked":false}},"elements":[{"id":"87e0892","elType":"column","settings":{"_column_size":100,"_inline_size":null,"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true}},"elements":[{"id":"4428823","elType":"widget","settings":[],"elements":[],"widgetType":"motors-add-listing"}],"isInner":false}],"isInner":false}]',
			),
			'authorization' => array(
				'title'          => 'Authorization',
				'shortcode'      => '[motors_login_page]',
				'elementor-data' => '[{"id":"e10df82","elType":"section","settings":{"margin":{"unit":"px","top":"30","right":0,"bottom":"30","left":0,"isLinked":true}},"elements":[{"id":"8e3730f","elType":"column","settings":{"_column_size":100,"_inline_size":null,"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true}},"elements":[{"id":"f50a46d","elType":"widget","settings":{"terms_label":"I accept the terms of the","link_text":"service","button_background_color":"#1280DF","button_text_color":"#FFFFFF","background_color_hover":"#1280DFC7","button_text_color_hover":"#FFFFFF","terms_page":"3","sign_in_background_color":"#35475A","sign_up_text_color":"#010101","labels_color":"#010101"},"elements":[],"widgetType":"motors-login-register"}],"isInner":false}],"isInner":false}]',
			),
		);
	}
);

add_filter(
	'mvl_elementor_listing_templates',
	function() {
		$templates = array(
			'listing-template-1' => array(
				'title'          => 'Classic',
				'elementor-data' => '[{"id":"ca0b156","elType":"section","settings":{"structure":"22","margin":{"unit":"px","top":"30","right":0,"bottom":"0","left":0,"isLinked":false},"margin_mobile":{"unit":"px","top":"10","right":0,"bottom":"10","left":0,"isLinked":true}},"elements":[{"id":"1abe6da","elType":"column","settings":{"_column_size":66,"_inline_size":75,"margin":{"unit":"px","top":"0","right":"20","bottom":"50","left":"0","isLinked":false},"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_tablet":100,"space_between_widgets_mobile":0,"margin_tablet":{"unit":"px","top":"0","right":"20","bottom":"50","left":"20","isLinked":false},"margin_mobile":{"unit":"px","top":"0","right":"0","bottom":"30","left":"0","isLinked":false},"padding_mobile":{"unit":"px","top":"0","right":"20","bottom":"0","left":"20","isLinked":false}},"elements":[{"id":"e49611c","elType":"section","settings":{"structure":"20","padding":{"unit":"px","top":"0","right":"35","bottom":"0","left":"0","isLinked":false},"padding_tablet":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":false}},"elements":[{"id":"5344489","elType":"column","settings":{"_column_size":50,"_inline_size":75,"margin":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_mobile":70,"margin_mobile":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":false},"padding_mobile":{"unit":"px","top":"0","right":"20","bottom":"0","left":"0","isLinked":false}},"elements":[{"id":"b464aa7","elType":"widget","settings":{"added_date":"","title_typography_typography":"custom","title_typography_font_size_mobile":{"unit":"px","size":20,"sizes":[]},"title_typography_line_height_mobile":{"unit":"px","size":23,"sizes":[]}},"elements":[],"widgetType":"motors-single-listing-classified-title"}],"isInner":true},{"id":"3a3581e","elType":"column","settings":{"_column_size":50,"_inline_size":24.999,"margin":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_mobile":30},"elements":[{"id":"de2d80c","elType":"widget","settings":{"detailed_view":"yes"},"elements":[],"widgetType":"motors-single-listing-classified-price"}],"isInner":true}],"isInner":true},{"id":"eee64c6","elType":"widget","settings":{"_margin":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_margin_mobile":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true}},"elements":[],"widgetType":"motors-single-listing-gallery"},{"id":"56c4070","elType":"widget","settings":{"icon_color":"#1280DF","_margin":{"unit":"px","top":"0","right":"0","bottom":"20","left":"0","isLinked":false},"_padding":{"unit":"px","top":"0","right":"0","bottom":"15","left":"0","isLinked":false},"_border_border":"solid","_border_width":{"unit":"px","top":"0","right":"0","bottom":"04","left":"0","isLinked":false},"_border_color":"#35475A"},"elements":[],"widgetType":"motors-single-listing-classified-listing-data"},{"id":"a33fc7d","elType":"widget","settings":{"title":"Features","title_color":"#010101","typography_typography":"custom","typography_font_family":"Montserrat","typography_font_size":{"unit":"px","size":20,"sizes":[]},"typography_font_weight":"600","typography_text_transform":"uppercase"},"elements":[],"widgetType":"heading"},{"id":"b786ad4","elType":"widget","settings":{"features_icon":{"value":"fas fa-check-circle","library":"fa-solid"},"features_icon_color":"#1280DF","_margin":{"unit":"px","top":"0","right":"0","bottom":"20","left":"0","isLinked":false},"_padding":{"unit":"px","top":"0","right":"0","bottom":"025","left":"0","isLinked":false},"_border_border":"solid","_border_width":{"unit":"px","top":"0","right":"0","bottom":"4","left":"0","isLinked":false}},"elements":[],"widgetType":"motors-single-listing-features"},{"id":"f0dab77","elType":"widget","settings":{"title":"SELLER\'S NOTES","title_color":"#010101","typography_typography":"custom","typography_font_family":"Montserrat","typography_font_size":{"unit":"px","size":20,"sizes":[]},"typography_font_weight":"700"},"elements":[],"widgetType":"heading"},{"id":"dea687f","elType":"widget","settings":{"description_typo_typography":"custom","description_typo_font_family":"Montserrat","description_typo_font_size":{"unit":"px","size":14,"sizes":[]},"description_typo_line_height":{"unit":"px","size":24,"sizes":[]}},"elements":[],"widgetType":"motors-single-listing-description"}],"isInner":false},{"id":"ad45d7b","elType":"column","settings":{"_column_size":33,"_inline_size":24.332,"margin":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_tablet":100,"margin_tablet":{"unit":"px","top":"0","right":"20","bottom":"30","left":"20","isLinked":false},"margin_mobile":{"unit":"px","top":"0","right":"0","bottom":"30","left":"0","isLinked":false},"padding_mobile":{"unit":"px","top":"0","right":"20","bottom":"0","left":"20","isLinked":false}},"elements":[{"id":"77744eb","elType":"widget","settings":[],"elements":[],"widgetType":"motors-single-listing-user-data-simple"},{"id":"0852bbf","elType":"widget","settings":{"dpn_label":"Call Us","dpn_show_number":"Number","dpn_typography_title_typography":"custom","dpn_typography_title_font_weight":"700","dpn_btn_padding":{"unit":"px","top":"8","right":"17","bottom":"8","left":"17","isLinked":false}},"elements":[],"widgetType":"motors-single-listing-dealer-phone"},{"id":"cd0cdfb","elType":"widget","settings":{"de_label":"Message Us","de_icon_color":"#1280DF","de_typography_typography":"custom","de_typography_font_weight":"700","de_icon_color_hover":"#1280DF"},"elements":[],"widgetType":"motors-single-listing-dealer-email"},{"id":"9b0bee8","elType":"widget","settings":{"opb_btn_label":"Make an Offer Price","opb_btn_bg":"#F3F7FC","opb_icon_color":"#1280DF","opb_text_color":"#0E0E0E","opb_btn_bg_hover":"#1280DF","opb_icon_color_hover":"#FFFFFF","opb_typography_typography":"custom","opb_typography_font_size":{"unit":"px","size":14,"sizes":[]},"opb_typography_font_weight":"400","opb_text_color_hover":"#FFFFFF"},"elements":[],"widgetType":"motors-single-listing-offer-price"},{"id":"298d4bf","elType":"widget","settings":{"similar_title":"Similar listing","similar_taxonomies":["condition","make","body"],"similar_typography_title_typography":"custom","features_icon_color":"#010101","label_border_color":"#35475A"},"elements":[],"widgetType":"motors-single-listing-similar-listings"}],"isInner":false}],"isInner":false},{"id":"d4396da","elType":"section","settings":{"structure":"21","background_background":"classic","background_color":"#E5E8EB","padding":{"unit":"px","top":"30","right":"0","bottom":"0","left":"0","isLinked":false}},"elements":[{"id":"4d956ab","elType":"column","settings":{"_column_size":33,"_inline_size":null,"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_tablet":100,"margin_tablet":{"unit":"px","top":"0","right":"20","bottom":"30","left":"20","isLinked":false}},"elements":[{"id":"860993d","elType":"section","settings":{"border_border":"solid","border_width":{"unit":"px","top":"5","right":"0","bottom":"0","left":"0","isLinked":false},"border_color":"#35475A"},"elements":[{"id":"707a5fd","elType":"column","settings":{"_column_size":100,"_inline_size":null,"background_background":"classic","background_color":"#FFFFFF","padding":{"unit":"px","top":"5","right":"25","bottom":"35","left":"25","isLinked":false}},"elements":[{"id":"3022eda","elType":"widget","settings":[],"elements":[],"widgetType":"motors-single-listing-user-data-simple"},{"id":"8ba8995","elType":"widget","settings":{"dpn_label":"Call Us","dpn_show_number":"Number","dpn_typography_title_typography":"custom","dpn_typography_title_font_weight":"700","dpn_btn_padding":{"unit":"px","top":"8","right":"17","bottom":"8","left":"17","isLinked":false}},"elements":[],"widgetType":"motors-single-listing-dealer-phone"}],"isInner":true}],"isInner":true}],"isInner":false},{"id":"8c18c09","elType":"column","settings":{"_column_size":66,"_inline_size":null,"margin":{"unit":"px","top":"0","right":"0","bottom":"0","left":"20","isLinked":false},"padding":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"_inline_size_tablet":100,"margin_tablet":{"unit":"px","top":"0","right":"20","bottom":"0","left":"20","isLinked":false},"margin_mobile":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":false},"padding_mobile":{"unit":"px","top":"0","right":"20","bottom":"0","left":"20","isLinked":false}},"elements":[{"id":"30ff32d","elType":"widget","settings":{"title":"MESSAGE TO DEALER","form_id":"2605","form_wide":"yes","title_typography_typography":"custom","title_typography_font_family":"Montserrat","title_typography_font_size":{"unit":"px","size":16,"sizes":[]},"title_typography_font_weight":"700"},"elements":[],"widgetType":"motors-contact-form-seven"}],"isInner":false}],"isInner":false}]',
			),
		);

		return $templates;
	}
);

add_filter(
	'mvl_get_template_id_by_slug',
	function( $slug ) {
		$templates = query_posts(
			array(
				'post_type'   => 'listing_template',
				'name'        => strtolower( trim( $slug ) ),
				'post_status' => 'publish',
			)
		);
		if ( ! empty( $templates ) ) {
			return $templates[0]->ID;
		}
		return false;
	}
);
