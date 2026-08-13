<?php

/**
 * Business type selector field.
 *
 * @var string $field
 * @var string $field_id
 * @var string $field_value
 * @var string $field_name
 */

$script_path    = STM_LISTINGS_PATH . '/includes/nuxy/custom-fields/js_components/business-type-selector.js';
$script_version = STM_LISTINGS_V;

if ( file_exists( $script_path ) ) {
	$script_version = filemtime( $script_path );
}

$style_path    = STM_LISTINGS_PATH . '/includes/nuxy/custom-fields/css/business-type-selector.css';
$style_version = STM_LISTINGS_V;

if ( file_exists( $style_path ) ) {
	$style_version = filemtime( $style_path );
}

wp_enqueue_script(
	'mvl-business-type-selector',
	STM_LISTINGS_URL . '/includes/nuxy/custom-fields/js_components/business-type-selector.js',
	array(),
	$script_version,
	true
);

wp_enqueue_style(
	'mvl-business-type-selector',
	STM_LISTINGS_URL . '/includes/nuxy/custom-fields/css/business-type-selector.css',
	array(),
	$style_version
);
?>

<wpcfto_business_type_selector
	:fields="<?php echo esc_attr( $field ); ?>"
	:field_name="'<?php echo esc_attr( $field_name ); ?>'"
	:field_id="'<?php echo esc_attr( $field_id ); ?>'"
	:field_value="<?php echo esc_attr( $field_value ); ?>"
	@wpcfto-get-value="<?php echo esc_attr( $field_value ); ?> = $event">
</wpcfto_business_type_selector>
