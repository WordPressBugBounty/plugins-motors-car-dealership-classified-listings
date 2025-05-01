<?php

namespace MotorsStarterTheme;

use MotorsVehiclesListing\ThemesInstaller;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Installer extends ThemesInstaller {
	/**
	 * Get install themes list
	 *
	 * @return array
	 */
	protected static function get_install_themes_list(): array {
		return array(
			array(
				'slug' => 'motors-starter-theme',
				'name' => __( 'Motors Starter Theme', 'stm_vehicles_listing' ),
				'type' => 'theme',
				'src'  => 'https://stylemixthemes-public.s3.us-west-2.amazonaws.com/motors-starter-theme.zip',
			),
		);
	}
}
