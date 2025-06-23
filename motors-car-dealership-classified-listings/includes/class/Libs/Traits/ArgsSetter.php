<?php
namespace MotorsVehiclesListing\Libs\Traits;

trait ArgsSetter {
	protected function set_args( array $args = array(), bool $deep_parsing = false ): void {
		foreach ( $args as $key => $value ) {
			$method = 'set_' . $key;

			if ( method_exists( $this, $method ) ) {
				$this->$method( $value );
			} else {
				if ( isset( $this->$key ) ) {
					if ( is_array( $value ) && $deep_parsing ) {
						$this->$key = wp_parse_args( $this->$key, $value );
					} else {
						$this->$key = $value;
					}
				}
			}
		}
	}
}
