<?php
// Output JavaScript template.
?>
<label>
	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
	<# } #>

	<textarea {{{ data.attr }}}>{{{ data.value }}}</textarea>

	<# if ( data.description ) { #>
		<span class="butterbean-description"><span class="stm-info-icon">i</span>{{{ data.description }}}</span>
	<# } #>
</label>
