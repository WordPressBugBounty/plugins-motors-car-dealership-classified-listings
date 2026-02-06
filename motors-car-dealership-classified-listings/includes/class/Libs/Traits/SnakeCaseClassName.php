<?php
namespace MotorsVehiclesListing\Libs\Traits;

use MotorsVehiclesListing\Libs\Str;

trait SnakeCaseClassName {
	protected string $snake_case_class_name = '';

	public function get_snake_case_class_name(): string {
		if ( empty( $this->snake_case_class_name ) ) {
			$this->snake_case_class_name = static::snake_case_class_name();
		}
		return $this->snake_case_class_name;
	}

	public static function snake_case_class_name(): string {
		return ( new Str( static::class ) )->end( '\\' )->camel_case_to_snake_case();
	}
}
