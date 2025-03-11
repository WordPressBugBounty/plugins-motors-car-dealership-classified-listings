<?php

/**
 * Data Select field template.
 *
 * @var $field
 * @var $field_id
 * @var $field_value
 * @var $field_label
 * @var $field_name
 * @var $section_name
 *
 */

wp_enqueue_script( 'nuxy-radio-component', STM_LISTINGS_URL . '/includes/nuxy/custom-fields/js_components/nuxy-radio.js', null, get_bloginfo( 'version' ), true );
wp_enqueue_style( 'nuxy-radio-css', STM_LISTINGS_URL . '/includes/nuxy/custom-fields/css/nuxy-radio.css', null, get_bloginfo( 'version' ), 'all' );

$field = "data['{$section_name}']['fields']['{$field_name}']";

?>

<wpcfto_data_select :fields="<?php echo esc_attr( $field ); ?>"
					:field_label="<?php echo esc_attr( $field_label ); ?>"
					:field_name="'<?php echo esc_attr( $field_name ); ?>'"
					:field_id="'<?php echo esc_attr( $field_id ); ?>'"
					:field_value="<?php echo esc_attr( $field_value ); ?>"
					@wpcfto-get-value="<?php echo esc_attr( $field_value ); ?> = $event">
</wpcfto_data_select>
