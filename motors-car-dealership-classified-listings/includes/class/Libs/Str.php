<?php
namespace MotorsVehiclesListing\Libs;

class Str {
	protected string $value;

	public function __construct( string $value = '' ) {
		$this->value = $value;
	}

	public function __toString(): string {
		return $this->value;
	}

	public function is_empty(): bool {
		return '' === $this->value || null === $this->value;
	}

	public function equals( string $string ): bool {
		return $this->value === (string) $string;
	}

	public function length(): int {
		return mb_strlen( $this->value );
	}

	public function to_upper(): self {
		return new static( mb_strtoupper( $this->value ) );
	}

	public function to_lower(): self {
		return new static( mb_strtolower( $this->value ) );
	}

	public function camel_case_to_snake_case(): self {
		return new static( strtolower( preg_replace( '/(?<!^)([A-Z])/', '_$1', $this->value ) ) );
	}

	public function snake_case_to_camel_case(): self {
		return new static( str_replace( '_', '', ucwords( $this->value, '_' ) ) );
	}

	public function end( string $sep ): self {
		$exploded = explode( $sep, $this->value );
		return new static( end( $exploded ) );
	}
}
