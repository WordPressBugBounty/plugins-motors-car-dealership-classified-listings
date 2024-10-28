<?php
$theme_v            = wp_get_theme()->parent()->version ?? wp_get_theme()->version;
$active_theme       = wp_get_theme()->get( 'TextDomain' );
$start_theme_notice = get_transient( 'stm_starter_theme_notice_setting' );

$theme_exists = array(
	'motors-starter-theme',
	'motors-child',
	'motors-starter-theme-child',
	'motors',
);

$init_data = array();

if ( apply_filters( 'stm_is_motors_theme', false ) && version_compare( $theme_v, STM_THEME_V_NEED, '<' ) ) {
	$init_data['motors-theme-update'] = array(
		'notice_type'          => 'animate-triangle-notice',
		'notice_title'         => 'Your current theme version is incompatible with the Motors - Car Dealer, Classifieds & Listing plugin ' . STM_LISTINGS_V,
		'notice_logo'          => 'attent_triangle.svg',
		'notice_desc'          => 'The current theme version is not compatible with the Motors plugin. Update the theme to version ' . STM_THEME_V_NEED . ' to get improved performance and prevent any issues.',
		'notice_btn_one'       => admin_url( 'themes.php' ),
		'notice_btn_one_title' => 'Update Theme',
	);
}
if ( ! in_array( $active_theme, $theme_exists, true ) && empty( $start_theme_notice ) ) {
	$init_data['starter-theme-notice'] = array(
		'notice_type'            => 'starter-theme-notice',
		'notice_logo'            => 'motors_plugin.svg',
		'notice_title'           => '',
		'notice_desc'            => '<h4>' . esc_html__( 'For the best experience with the Motors plugin, install the ', 'stm_vehicles_listing' ) . '<a href="' . esc_url( 'https://motors-plugin.stylemixthemes.com/' ) . '" target="_blank">' . esc_html__( 'Motors Starter Theme!', 'stm_vehicles_listing' ) . '</a></h4>',
		'notice_btn_one_title'   => esc_html__( 'Install', 'stm_vehicles_listing' ),
		'notice_btn_one_class'   => 'ms_start_theme_install',
		'notice_btn_one'         => esc_url( get_site_url() . '/wp-admin/admin.php?page=motors_starter_demo_installer' ),
		'notice_btn_two_title'   => esc_html__( 'Live Demo', 'stm_vehicles_listing' ),
		'notice_btn_two_class'   => 'ms_start_theme_live_demo light-bg',
		'notice_btn_two'         => esc_url( 'https://motors-plugin.stylemixthemes.com/' ),
		'notice_btn_two_attrs'   => 'target=_blank',
		'notice_btn_three_title' => esc_html__( 'No Thanks', 'stm_vehicles_listing' ),
		'notice_btn_three_class' => 'no-bg',
		'notice_btn_three'       => '#',
		'notice_btn_three_attrs' => 'data-type=discard data-key=starter_theme',
	);
}

foreach ( $init_data as $item ) {
	stm_admin_notices_init( $item );
}
