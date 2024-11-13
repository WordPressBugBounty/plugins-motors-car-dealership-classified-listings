<?php

namespace MotorsVehiclesListing\Addons;

use MotorsVehiclesListing\Plugin;

interface Addon {
	public function get_name(): string;
	public function register( Plugin $plugin ): void;
}
