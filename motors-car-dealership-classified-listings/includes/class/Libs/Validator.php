<?php
namespace MotorsVehiclesListing\Libs;

class Validator {

	protected array $data = array();
	protected array $inputs_rules;
	protected array $errors    = array();
	protected array $validated = array();

	public function __construct( array $inputs_rules, array $data ) {
		$this->data         = $data;
		$this->inputs_rules = $inputs_rules;
	}

	public function validate(): self {
		foreach ( $this->inputs_rules as $input_name => $rules ) {
			foreach ( explode( '|', $rules ) as $rule ) {
				$exploded_rule = explode( ':', $rule );
				$rule_name     = $exploded_rule[0];
				$rule_values   = isset( $exploded_rule[1] ) ? explode( ',', $exploded_rule[1] ) : array();

				if ( $this->$rule_name( $input_name, $rule_values ) ) {
					$this->validated[ $input_name ] = $this->data[ $input_name ];
				} else {
					if ( count( $rule_values ) > 0 ) {
						foreach ( $rule_values as $rule_value ) {
							$this->errors[ $input_name ][] = $this->get_message( $input_name, $rule_name, $rule_value );
						}
					} else {
						$this->errors[ $input_name ][] = $this->get_message( $input_name, $rule_name );
					}
				}
			}
		}

		return $this;
	}

	// Rules List
	protected function required( string $key, array $rule_values ): bool {
		return isset( $this->data[ $key ] ) && strlen( $this->data[ $key ] );
	}

	protected function phone( string $key, array $rule_values ): bool {
		if ( isset( $this->data[ $key ] ) ) {
			$this->data[ $key ] = strtr(
				$this->data[ $key ],
				array(
					'+' => '',
					')' => '',
					'(' => '',
				)
			);
		}

		return ! isset( $this->data[ $key ] ) || preg_match( '/^7 \d{3}\s\d{3}-\d{2}-\d{2}$/', $this->data[ $key ] );
	}

	protected function numeric( string $key, array $rule_values ): bool {
		return ! isset( $this->data[ $key ] ) || is_numeric( $this->data[ $key ] );
	}

	protected function text( string $key, array $rule_values ): bool {
		if ( isset( $this->data[ $key ] ) ) {
			$this->data[ $key ] = sanitize_text_field( $this->data[ $key ] );
		}

		return true;
	}

	protected function textarea( string $key, array $rule_values ): bool {
		if ( isset( $this->data[ $key ] ) ) {
			$this->data[ $key ] = sanitize_textarea_field( $this->data[ $key ] );
		}

		return true;
	}

	protected function email( string $key, array $rule_values ): bool {
		if ( isset( $this->data[ $key ] ) ) {
			$this->data[ $key ] = sanitize_email( $this->data[ $key ] );
		}

		return ! isset( $this->data[ $key ] ) || preg_match( '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $this->data[ $key ] );
	}

	protected function minlength( string $key, array $rule_values ): bool {
		return ! isset( $this->data[ $key ] ) || strlen( $this->data[ $key ] ) >= $rule_values[0];
	}

	protected function maxlength( string $key, array $rule_values ): bool {
		return ! isset( $this->data[ $key ] ) || strlen( $this->data[ $key ] ) <= $rule_values[0];
	}

	protected function length( string $key, array $rule_values ): bool {
		return ! isset( $this->data[ $key ] ) || strlen( $this->data[ $key ] ) === intval( $rule_values[0] );
	}

	protected function possiblevalues( string $key, array $rule_values ): bool {
		return ! isset( $this->data[ $key ] ) || in_array( (string) $this->data[ $key ], $rule_values, true );
	}

	public function error( string $input_name ): string {
		return isset( $this->errors[ $input_name ] ) ? '<span class="input-error">' . $this->errors[ $input_name ][0] . '</span>' : '';
	}

	public function success(): bool {
		return count( $this->errors ) === 0;
	}

	public function fail(): bool {
		return count( $this->errors ) > 0;
	}

	public function validated( string $key = '' ) {
		if ( $key ) {
			return isset( $this->validated[ $key ] ) ? $this->validated[ $key ] : false;
		} else {
			return $this->validated;
		}
	}

	public function errors(): array {
		return $this->errors;
	}

	public function value( string $input_name, string $default = '' ): string {
		return isset( $this->data[ $input_name ] ) ? $this->data[ $input_name ] : $default;
	}

	protected function get_message( string $input_name, string $rule_name, string $rule_value = '' ): string {
		$messages = array(
			// translators: %s: field name
			'required'       => __( '%s is required', 'motors-car-dealership-classified-listings' ),
			// translators: %s: field name
			'text'           => __( '%s must be a text', 'motors-car-dealership-classified-listings' ),
			// translators: %s: field name
			'numeric'        => __( '%s must be numeric', 'motors-car-dealership-classified-listings' ),
			// translators: %s: field name
			'email'          => __( '%s is invalid', 'motors-car-dealership-classified-listings' ),
			// translators: %s: field name
			'phone'          => __( '%s is invalid', 'motors-car-dealership-classified-listings' ),
			// translators: 1: field name, 2: minimum length
			'minlength'      => __( '%1$s must be at least %2$s characters long', 'motors-car-dealership-classified-listings' ),
			// translators: 1: field name, 2: maximum length
			'maxlength'      => __( '%1$s must be at most %2$s characters long', 'motors-car-dealership-classified-listings' ),
			// translators: 1: field name, 2: exact length
			'length'         => __( '%1$s must be exactly %2$s characters long', 'motors-car-dealership-classified-listings' ),
			// translators: 1: field name, 2: allowed values
			'possiblevalues' => __( '%1$s must be one of the following values: %2$s', 'motors-car-dealership-classified-listings' ),
		);

		return sprintf( $messages[ $rule_name ], $input_name, $rule_value );
	}
}
