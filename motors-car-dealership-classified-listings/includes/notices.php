<?php
add_action(
	'current_screen',
	function () {
		if ( is_admin() ) {
			if ( apply_filters( 'stm_is_motors_theme', false ) ) {
				$theme_v = wp_get_theme()->parent()->version ?? wp_get_theme()->version;

				if ( version_compare( $theme_v, STM_THEME_V_NEED, '<' ) ) {
					$init_data = array(
						'notice_type'          => 'animate-triangle-notice',
						'notice_title'         => 'Your current theme version is incompatible with the Motors - Car Dealer, Classifieds & Listing plugin ' . STM_LISTINGS_V,
						'notice_logo'          => 'attent_triangle.svg',
						'notice_desc'          => 'The current theme version is not compatible with the Motors plugin. Update the theme to version ' . STM_THEME_V_NEED . ' to get improved performance and prevent any issues.',
						'notice_btn_one'       => admin_url( 'themes.php' ),
						'notice_btn_one_title' => 'Update Theme',
					);

					stm_admin_notices_init( $init_data );
				}
			}
		}
	},
	10,
	1
);
