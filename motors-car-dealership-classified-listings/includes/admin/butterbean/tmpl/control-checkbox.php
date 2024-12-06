<?php
// Output JavaScript template.
?>
<label class="toggle-checkbox">
	<label class="switch">
		<input type="checkbox" value="true" {{{ data.attr }}} <# if ( data.value ) { #> checked="checked" <# } #> />
		<span class="slider round"></span>
	</label>
</label>
<div class="butturbean-checkbox-info">
	<# if ( data.label ) { #>
	<div class="butterbean-label">
		{{ data.label }}
		<# if ( data.preview ) { #>
		<div class="image_preview">

			<i class="motors-icons-ico_mag_eye"></i>
			<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg">Preview</span>

		</div>
	<# } #>
		</div>
	<# } #>

	<# if ( data.description ) { #>
		<span id='butterbean-checkbox-description' class="butterbean-description">{{{ data.description }}}</span>
	<# } #>
</div>


