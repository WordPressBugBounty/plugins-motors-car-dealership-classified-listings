<?php
namespace MotorsVehiclesListing\MenuPages;

use MotorsVehiclesListing\Plugin\MVL_Const;

class SkinSettings extends MenuBase {

	public function __construct() {
		$this->nuxy_option_name = MVL_Const::SKIN_SETTINGS_OPT_NAME;
		$this->nuxy_title       = esc_html__( 'THEME OPTIONS', 'stm_vehicles_listing' );
		$this->nuxy_subtitle    = esc_html__( 'by StylemixThemes', 'stm_vehicles_listing' );
		$this->nuxy_title       = esc_html__( 'Theme Options', 'stm_vehicles_listing' );
		$this->nuxy_menu_slug   = MVL_Const::SKIN_SETTINGS_OPT_NAME;
		$this->menu_position    = 7;

		parent::__construct();
	}

	public function mvl_init_page() {
		$this->nuxy_opts = apply_filters( 'mst_skin_settings_conf', array() );

		parent::mvl_init_page();
	}
}
