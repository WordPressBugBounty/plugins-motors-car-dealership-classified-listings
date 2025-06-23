<?php
namespace MotorsVehiclesListing\Libs\Traits;

trait SnakeCaseClassName {
	public function get_snake_case_class_name(): string {
		return static::snake_case_class_name();
	}

	public static function snake_case_class_name(): string {
		$reflection = new \ReflectionClass( static::class );
		return strtolower( preg_replace( '/(?<!^)([A-Z])/', '_$1', $reflection->getShortName() ) );
	}
}
