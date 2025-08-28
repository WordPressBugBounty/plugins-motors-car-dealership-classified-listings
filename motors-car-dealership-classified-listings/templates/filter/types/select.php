<?php
/**
 * @var $name
 * @var $multiple
 * @var $placeholder
 * @var $theme
*/

$multiple = $multiple ?? false;
$sel_name = ( ( isset( $maxify ) && $maxify ) ) ? 'max_' . $name : $name;
$class    = 'filter-select';
$theme    = empty( $theme ) ? 'default' : $theme;
if ( isset( $multiple ) && true === boolval( $multiple ) ) {
	$sel_name         = $sel_name . '[]';
	$placeholder      = array_shift( $options );
	$data_placeholder = 'data-placeholder=" ' . esc_attr( $placeholder['label'] ) . '"';
	$class            = $class . ' stm-multiple-select';
}

$aria_label   = '';
$first_option = reset( $options );
unset( $options[''] );

if ( $multiple ) {
	$plshldr    = apply_filters( 'mvl_get_dynamic_string_translation', $placeholder['label'], 'Listing Category ' . $placeholder['label'] );
	$aria_label = $plshldr;
} elseif ( ! empty( $options ) ) {
	$aria_label = $first_option['label'];
}

$attr_data = stm_get_taxonomies_with_type( $name );

if ( ! empty( $attr_data['use_count'] ) ) {
	$show_count = true;
}

$sort       = $attr_data['terms_filters_sort_by'] ?? 'name_asc';
$is_numeric = apply_filters( 'mvl_is_numeric_listing_field_sort_type', false, $sel_name );
switch ( $sort ) {
	case 'name_desc':
		if ( $is_numeric ) {
			uasort(
				$options,
				function ( $a, $b ) {
					return abs( (int) $b['label'] ) <=> (int) abs( $a['label'] );
				}
			);
		} else {
			usort(
				$options,
				function ( $a, $b ) {
					return strcmp( $b['label'], $a['label'] );
				}
			);
		}
		break;
	case 'count_asc':
		uasort(
			$options,
			function ( $a, $b ) {
				$a['count'] = isset( $a['count'] ) ? $a['count'] : 0;
				$b['count'] = isset( $b['count'] ) ? $b['count'] : 0;

				return abs( (int) $a['count'] ) <=> abs( (int) $b['count'] );
			}
		);
		break;
	case 'count_desc':
		uasort(
			$options,
			function ( $a, $b ) {
				$a['count'] = isset( $a['count'] ) ? $a['count'] : 0;
				$b['count'] = isset( $b['count'] ) ? $b['count'] : 0;

				return abs( (int) $b['count'] ) <=> abs( (int) $a['count'] );
			}
		);
		break;
	default:
		if ( $is_numeric ) {
			uasort(
				$options,
				function ( $a, $b ) {
					return abs( (int) $a['label'] ) <=> abs( (int) $b['label'] );
				}
			);
		} else {
			usort(
				$options,
				function ( $a, $b ) {
					return strcmp( $a['label'], $b['label'] );
				}
			);
		}
		break;
}
$empty_options = array();

foreach ( $options as $k => $option ) {
	if ( ! isset( $option['count'] ) || $option['count'] < 1 ) {
		$empty_options[ $k ]['count'] = 0;
		$empty_options[ $k ]          = $option;

		unset( $options[ $k ] );
	}
}

$options = array_merge( array( '' => $first_option ), $options, $empty_options );

$aria_label = sprintf(
	/* translators: %s label */
	__( 'Select %s', 'stm_vehicles_listing' ),
	strtolower( $aria_label )
);
?>
<select aria-label="<?php echo esc_attr( $aria_label ); ?>" <?php echo $multiple ? 'multiple="multiple"' : ''; ?>
	<?php echo $multiple ? 'data-placeholder="' . esc_attr( $plshldr ) . '"' : ''; ?>
		name="<?php echo esc_attr( $sel_name ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		<?php if ( isset( $elementor_widget_class ) && ! empty( $elementor_widget_class ) ) : ?>
			data-elementor-widget-class="<?php echo esc_attr( $elementor_widget_class ); ?>"
		<?php endif; ?>
		>
	<?php
	if ( ! empty( $options ) ) :
		foreach ( $options as $value => $option ) :
			$parent_attr = ( ! empty( $option['parent'] ) ) ? $option['parent'] : '';
			$value_attr  = ( ! empty( $option['option'] ) ) ? $option['option'] : '';
			?>
			<option class="mvl-inventory-select" data-parent="<?php echo esc_attr( $parent_attr ); ?>" value="<?php echo esc_attr( $value_attr ); ?>" <?php selected( $option['selected'] ); ?> <?php disabled( $option['disabled'] ); ?>>
				<?php
				if ( apply_filters( 'stm_is_listing_price_field', false, $name ) ) {
					if ( ! empty( $option['option'] ) ) {
						echo esc_html( apply_filters( 'stm_filter_price_view', '', $option['option'] ) );
					} else {
						echo esc_html( apply_filters( 'mvl_get_dynamic_string_translation', $option['label'], 'Listing Category ' . $option['label'] ) );
					}
				} else {
					echo esc_html( apply_filters( 'mvl_get_dynamic_string_translation', $option['label'], 'Listing Category ' . $option['label'] ) );
					if ( ! empty( $show_count ) && ! empty( $option['count'] ) ) {
						echo wp_kses_post( ' (' . $option['count'] ) . ')';
					}
				}
				?>
			</option>
			<?php
		endforeach;
	endif;
	?>
</select>
