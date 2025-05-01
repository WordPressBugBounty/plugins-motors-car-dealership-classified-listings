<?php
namespace MotorsStarterTheme\Services;

use MotorsVehiclesListing\Stilization\Color;

class OutputCSS {
	public const TYPOGRAPHY = array(
		'font-family'    => '{{font-family}}',
		'font-style'     => '{{font-style}}',
		'color'          => '{{color}}',
		'font-size'      => '{{font-size}}px',
		'line-height'    => '{{line-height}}px',
		'text-align'     => '{{text-align}}',
		'word-spacing'   => '{{word-spacing}}px',
		'letter-spacing' => '{{letter-spacing}}px',
		'font-weight'    => '{{font-weight}}',
		'text-transform' => '{{text-transform}}',
	);

	public const PADDING = array(
		'padding-top'    => '{{top}}{{unit}}',
		'padding-right'  => '{{right}}{{unit}}',
		'padding-bottom' => '{{bottom}}{{unit}}',
		'padding-left'   => '{{left}}{{unit}}',
	);

	public const MARGIN = array(
		'margin-top'    => '{{top}}{{unit}}',
		'margin-right'  => '{{right}}{{unit}}',
		'margin-bottom' => '{{bottom}}{{unit}}',
		'margin-left'   => '{{left}}{{unit}}',
	);

	protected const BREAKPOITNS = array(
		'desktop' => 'min-width: 1025px',
		'mobile'  => 'max-width: 1024px',
	);

	protected $output_css = array();
	protected $value;
	protected $replacement = array();

	public function __construct( $output_css, $value ) {
		$this->output_css = $output_css;
		$this->value      = $value;

		if ( is_array( $this->value ) ) {
			foreach ( $this->value as $k => $v ) {
				if ( $v || 0 === $v ) {
					$this->replacement[ '{{' . $k . '}}' ] = $v;
				}
			}
		} else {
			$this->replacement['{{value}}'] = $this->value;
		}
	}

	public function get_css_data() {
		$styles = $this->output_css;
		$css    = array();

		if ( ! empty( $styles ) ) {
			foreach ( $styles as $style ) {
				$css_data  = array();
				$selectors = array();

				foreach ( $style as $key => $value ) {
					if ( is_int( $key ) ) {
						//If is tag data
						$selectors[] = $value;
					} else {
						//If is css data
						if ( strchr( $value, '::' ) ) {
							$value_data        = explode( '::', $value );
							$value_data['key'] = str_replace( '{{', '', $value_data[0] );
							$value_data['arg'] = str_replace( '}}', '', $value_data[1] );

							if ( 'value' === $value_data['key'] ) {
								$this->replacement[ $value ] = Color::create( $this->value )->get_value( $value_data['arg'] );
							} else {
								if ( is_array( $this->value ) && isset( $this->value[ $value_data['key'] ] ) && $this->value[ $value_data['key'] ] ) {
									$this->replacement[ $value ] = Color::create( $this->value[ $value_data['key'] ] )->get_value( $value_data['arg'] );
								}
							}
						}

						$value = strtr( $value, $this->replacement );

						if ( ! strchr( $value, '{{' ) ) {
							$css_data[ $key ] = strtr( $value, $this->replacement );
						}
					}
				}

				$selectors         = implode( ',', $selectors );
				$css[ $selectors ] = isset( $css[ $selectors ] ) ? array_merge( $css[ $selectors ], $css_data ) : $css_data;
			}
		}

		return $css;
	}
}
