<?php
namespace MotorsVehiclesListing\Libs\Traits;

trait Instance {
	protected static array $instances = array();

	public static function instance( array $args = array() ): self {
		$called_class = get_called_class();

		if ( ! isset( static::$instances[ $called_class ] ) ) {
			static::$instances[ $called_class ] = new static( $args );
		}
		return static::$instances[ $called_class ];
	}

	public static function load( array $args = array() ): void {
		static::instance( $args );
	}
}
