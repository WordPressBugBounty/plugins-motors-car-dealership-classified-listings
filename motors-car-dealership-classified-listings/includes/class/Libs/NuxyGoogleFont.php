<?php
namespace MotorsVehiclesListing\Libs;

use MotorsVehiclesListing\Libs\Traits\ArgsSetter;

class NuxyGoogleFont {
	use ArgsSetter;

	protected string $family   = '';
	protected string $version  = '';
	protected string $category = '';
	protected string $kind     = '';
	protected array $variants  = array();
	protected array $subsets   = array();
	protected array $files     = array();

	public function __construct( array $args = array() ) {
		$this->set_args( $args );
	}

	public function is_correct(): bool {
		return $this->family && is_array( $this->variants ) && ! empty( $this->variants );
	}

	public function get_roman_weights(): array {
		$weights = array();
		foreach ( $this->variants as $variant ) {
			if ( 'regular' === $variant ) {
				$weights[] = 400;
			} elseif ( is_numeric( $variant ) ) {
				$weights[] = intval( $variant );
			}
		}
		usort( $weights, fn( $a, $b) => $a <=> $b );
		return $weights;
	}

	public function is_has_italic(): bool {
		return ! empty( $this->get_italic_weights() );
	}

	public function get_italic_weights(): array {
		$weights = array();
		foreach ( $this->variants as $variant ) {
			if ( 'italic' === $variant ) {
				$weights[] = 400;
			} elseif ( strchr( $variant, 'italic' ) ) {
				$weights[] = intval( str_replace( 'italic', '', $variant ) );
			}
		}
		usort( $weights, fn( $a, $b) => $a <=> $b );
		return $weights;
	}

	public function get_url(): string {
		return 'https://fonts.googleapis.com/css2?family=' . str_replace( ' ', '+', $this->family ) . ':' . $this->get_wght_string() . '&display=swap';
	}

	protected function get_wght_string(): string {
		if ( $this->is_has_italic() ) {
			return 'ital,wght@0,' . implode( ';0,', $this->get_italic_weights() ) . ';1,' . implode( ';1,', $this->get_italic_weights() );
		} else {
			return 'wght@' . implode( ';', $this->get_roman_weights() );
		}
	}
}
