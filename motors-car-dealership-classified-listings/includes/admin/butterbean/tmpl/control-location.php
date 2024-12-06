<?php
// Output JavaScript template.
?>
<label>
	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<span class="butterbean-description"><span class="stm-info-icon">i</span>{{{ data.description }}}</span>
	<# } #>

	<div class="stm_car_location_admin">
		<input type="text" value="{{ data.value }}" {{{ data.attr }}} placeholder="<?php esc_attr_e( 'Enter', 'stm_vehicles_listing' ); ?> {{ data.label }}"/>
	</div>
</label>
