<?php
namespace MotorsStarterTheme\Services;

use MotorsVehiclesListing\Stilization\Color;

class SkinOption {
	protected static $cache = array();

	protected $id;
	protected $dependencies = array();
	protected $dependency   = '';
	protected $field;
	protected $value;

	protected function __construct( $id ) {
		$this->id    = $id;
		$this->field = SkinOptions::get_setting_data( $id );

		if ( $this->field ) {
			if ( isset( $this->field['dependency'] ) ) {
				if ( isset( $this->field['dependencies'] ) ) {
					$this->dependency = $this->field['dependencies'];
				}
				$this->dependencies = $this->field['dependency'];
			}
		}
	}

	protected function parse_selectors( $selectors ) {
		if ( is_array( $selectors ) ) {
			$selector = implode( ',', $selectors );
		} else {
			$selector = $selectors;
		}
		return $selector;
	}

	public function value( $key = '' ) {
		$value = null;

		if ( null === $this->value ) {
			$this->value = apply_filters( 'motors_vl_get_nuxy_mod', null, $this->id );
		}

		if ( $key && is_array( $this->value ) && isset( $this->value[ $key ] ) ) {
			$value = $this->value[ $key ];
		} else {
			$value = $this->value;
		}

		return $value;
	}

	public function check() {
		if ( ! $this->field ) {
			return false;
		}
		return $this->check_dependencies();
	}

	protected function check_dependencies() {
		if ( ! count( $this->dependencies ) ) {
			return true;
		}

		if ( $this->dependency ) {
			if ( '&&' === $this->dependency ) {
				$result = true;

				foreach ( $this->dependencies as $dependency ) {
					if ( ! $this->check_dependency( $dependency ) ) {
						$result = false;
						break;
					}
				}
			} else {
				$result = false;

				foreach ( $this->dependencies as $dependency ) {
					if ( $this->check_dependency( $dependency ) ) {
						$result = true;
						break;
					}
				}
			}
		} else {
			$result = $this->check_dependency( $this->dependencies );
		}

		return $result;
	}

	protected function check_dependency( $dep ) {
		$key     = $dep['key'];
		$compare = $dep['value'];
		$value   = apply_filters( 'motors_vl_get_nuxy_mod', '', $key );
		$result  = false;

		if ( 'not_empty' === $compare && $value ) {
			$result = true;
		} elseif ( 'empty' === $compare && '' === $value ) {
			$result = true;
		} elseif ( ! empty( $compare ) && strpos( $compare, '||' ) ) {
			$compare  = preg_replace( '/\s+/', '', $compare );
			$compares = explode( '||', $compare );

			foreach ( $compares as $compare ) {
				if ( $value === $compare ) {
					$result = true;
					break;
				}
			}
		} else {
			$result = $value === $compare;
		}

		return $result;
	}

	public static function get( $id, $conf_name = 'mst_skin_settings_conf' ) {
		if ( ! isset( self::$cache[ $id ] ) ) {
			self::$cache[ $id ] = new static( $id, $conf_name );
		}

		return self::$cache[ $id ];
	}
}
