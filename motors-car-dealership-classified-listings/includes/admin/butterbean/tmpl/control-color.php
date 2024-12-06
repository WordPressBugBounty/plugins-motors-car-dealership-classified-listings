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

	<input {{{ data.attr }}} value="<# if ( data.value ) { #>#{{ data.value }}<# } #>" />
</label>
