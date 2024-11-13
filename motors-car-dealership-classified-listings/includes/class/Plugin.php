<?php

namespace MotorsVehiclesListing;

use MotorsVehiclesListing\Addons\Addon;
use MotorsVehiclesListing\Addons\Addons;

final class Plugin {

	private array $enabled_addons;

	public function __construct() {
		$this->enabled_addons = Addons::enabled_addons();
	}

	public function init(): void {}

	public function load_file( string $file ): void {
		( function ( $plugin ) use ( $file ) {
			require $file;
		} )( $this );
	}

	/**
	 * @param array<Addon> $addons
	 */
	public function register_addons( array $addons ): void {
		foreach ( $addons as $addon ) {
			if ( $addon instanceof Addon && ( $this->enabled_addons[ $addon->get_name() ] ?? false ) ) {
				$addon->register( $this );
			}
		}
	}
}
