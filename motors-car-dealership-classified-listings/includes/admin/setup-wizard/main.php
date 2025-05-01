<?php

define( 'MVL_SETUP_WIZARD_TEMPLATES_PATH', STM_LISTINGS_PATH . '/includes/admin/setup-wizard/templates/' );

function mvl_setup_wizard_assets_enqueue() {
	wp_enqueue_style( 'mvl-setup-wizard', STM_LISTINGS_URL . '/includes/admin/setup-wizard/assets/setup-wizard.css', null, 1.0, 'all' );
	wp_enqueue_script( 'mvl-setup-wizard', STM_LISTINGS_URL . '/includes/admin/setup-wizard/assets/setup-wizard.js', 'jquery', 1.0, true );
	wp_localize_script( 'mvl-setup-wizard', 'security', array( 'ajax_nonce' => wp_create_nonce( 'stm_mvl_setup_wizard_nonce' ) ) );
}
add_action( 'admin_enqueue_scripts', 'mvl_setup_wizard_assets_enqueue' );

function mvl_setup_wizard_sub_menu_admin_page_contents() {
	require_once MVL_SETUP_WIZARD_TEMPLATES_PATH . 'index.php';
}
function mvl_setup_wizard_admin_menu() {
	add_submenu_page(
		'null',
		__( 'Welcome', 'stm_vehicles_listing' ),
		__( 'Hidden', 'stm_vehicles_listing' ),
		'manage_options',
		'mvl-welcome-setup',
		'mvl_setup_wizard_sub_menu_admin_page_contents'
	);
}
add_action( 'admin_menu', 'mvl_setup_wizard_admin_menu' );

function mvl_plugin_activation() {
	add_option( 'mvl_plugin_do_activation_redirect', true );
}
add_action( 'admin_init', 'mvl_plugin_redirect' );

function mvl_plugin_redirect() {
	if ( get_option( 'mvl_plugin_do_activation_redirect', false ) ) {
		delete_option( 'mvl_plugin_do_activation_redirect' );
		if ( ! isset( $_GET['activate-multi'] ) && ! defined( 'MOTORS_THEME' ) ) {
			wp_safe_redirect( 'admin.php?page=mvl-welcome-setup' );
		}
	}
}
register_activation_hook( STM_LISTINGS_FILE, 'mvl_plugin_activation' );

require_once 'includes/actions.php';

require_once 'includes/ajax_actions.php';
