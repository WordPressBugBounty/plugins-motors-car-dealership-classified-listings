<?php
namespace MotorsVehiclesListing\Stilization;

class Color {

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $css_id = '';

	/**
	 * @var string
	 */
	protected $elementor_id = '';

	/**
	 * @var string
	 */
	protected $value = '';

	/**
	 * @var float
	 */
	protected $alphachannel = 1.0;

	/**
	 * @var string
	 */
	protected $default_value = '';

	/**
	 * @var array
	 */
	protected $children = array();

	protected function __construct( array $args ) {
		$this->parse_args( $args );

		foreach ( $this->children as $css_id => $child ) {
			$this->children[ $css_id ] = new static(
				array(
					'name'         => $child['name'],
					'id'           => $this->id,
					'value'        => $this->value,
					'alphachannel' => $child['alphachannel'],
					'elementor_id' => $child['id'],
					'css_id'       => $css_id,
				)
			);

			$this->children[ $css_id ]->default_value = $this->children[ $css_id ]->value;
		}
	}

	/**
	 * @return bool
	 */
	public function is_include_in_elementor() {
		return $this->elementor_id && $this->name ? true : false;
	}

	/**
	 * @return array
	 */
	public function get_children() {
		return $this->children;
	}

	/**
	 * @return array
	 */
	public function get_in_format_of_elementor_settings() {
		return array(
			'_id'   => $this->get_elementor_id(),
			'title' => $this->name,
			'color' => $this->get_value(),
		);
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_elementor_id() {
		return 'motors_' . $this->elementor_id;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param float $alphachannel
	 * @return string
	 */
	public function get_value( $alphachannel = -1.0 ) {
		if ( $alphachannel <= 0 ) {
			return $this->default_value;
		} else {
			$default = $this->alphachannel;
			$this->set_alphachannel( $alphachannel );
			$value = apply_filters( 'stm_color_value', $this->value, $this->id, $alphachannel );
			$this->set_alphachannel( $default );
			return $value;
		}
	}

	/**
	 * @return string
	 */
	public function get_plugin_css_var_name() {
		return '--motors-' . str_replace( '_', '-', $this->css_id );
	}

	/**
	 * @return string
	 */
	public function get_elementor_css_var_name() {
		return '--e-global-color-' . $this->get_elementor_id();
	}

	/**
	 * @param float $alphachannel
	 * @return void
	 */
	protected function set_alphachannel( $alphachannel ) {
		if ( $alphachannel >= 0 && $alphachannel <= 1 ) {
			$this->hex_to_rgb();
			$exploded_color = explode( ',', $this->value );

			// Convert color value to rgba if rgb or to hsla if hsl
			if ( strchr( $exploded_color[0], 'rgb' ) ) {
				$exploded_color[0] = str_replace( 'rgb(', 'rgba(', $exploded_color[0] );
			} else {
				$exploded_color[0] = str_replace( 'hsl(', 'hsla(', $exploded_color[0] );
			}

			// Remove bracket from last exploded by "," color item if rgb or hsl
			if ( count( $exploded_color ) === 3 ) {
				$exploded_color[2] = str_replace( ')', '', $exploded_color[2] );
			}

			// Add alpha channel
			$exploded_color[3] = $alphachannel . ')';
			$this->value       = implode( ',', $exploded_color );
		}
	}

	/**
	 * @return void
	 */
	protected function hex_to_rgb() {
		if ( strchr( $this->value, '#' ) ) {
			$color = str_replace( '#', '', $this->value );
			if ( strlen( $color ) === 3 ) {
				$r = hexdec( substr( $color, 0, 1 ) . substr( $color, 0, 1 ) );
				$g = hexdec( substr( $color, 1, 1 ) . substr( $color, 1, 1 ) );
				$b = hexdec( substr( $color, 2, 1 ) . substr( $color, 2, 1 ) );
			} else {
				$r = hexdec( substr( $color, 0, 2 ) );
				$g = hexdec( substr( $color, 2, 2 ) );
				$b = hexdec( substr( $color, 4, 2 ) );
			}
			$this->value = 'rgb(' . $r . ', ' . $g . ', ' . $b . ')';
		}
	}

	/**
	 * @param array $args
	 */
	protected function parse_args( array $args = array() ) {
		foreach ( $args as $key => $value ) {
			$method = 'set_' . $key;

			if ( method_exists( $this, $method ) ) {
				$this->$method( $value );
			} else {
				if ( isset( $this->$key ) ) {
					$this->$key = $value;
				}
			}
		}
	}

	/**
	 * @param string $id
	 * @param string $active_skin
	 * @return self
	 */
	public static function load( $id, $active_skin = 'free' ) {
		$color_data = Colors::data_for_elementor( $id );
		$value      = apply_filters( 'motors_vl_get_nuxy_mod', Colors::DEFAULT[ $active_skin ][ $id ], $id );

		return new static(
			array(
				'id'            => $id,
				'name'          => isset( $color_data['name'] ) ? $color_data['name'] : '',
				'value'         => $value,
				'default_value' => $value,
				'elementor_id'  => isset( $color_data['id'] ) ? $color_data['id'] : '',
				'children'      => isset( $color_data['children'] ) ? $color_data['children'] : array(),
				'css_id'        => $id,
			)
		);
	}
}
