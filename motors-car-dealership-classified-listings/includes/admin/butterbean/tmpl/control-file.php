<?php
// Output JavaScript template.
?>
<# if ( data.label ) { #>
	<span class="butterbean-label butterbean-label-inline">{{ data.label }}</span>
	<# if ( data.preview ) { #>
		<span class="image_preview">

			<i class="motors-icons-ico_mag_eye"></i>
			<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg">Preview</span>

		</span>
		<# if ( data.src ) { #>
			<div class="butterbean-pdf-file">
				<span class="stm_pdf_input">
					<i class="far fa-file-pdf"></i><span>{{data.src}}</span>
				</span>
			</div>
		<# } #>
	<# } #>
<# } #>

<input type="hidden" class="butterbean-attachment-id" name="{{ data.field_name }}" value="{{ data.value }}" {{{ data.attr }}} />

<p>
	<# if ( data.src ) { #>
		<button type="button" class="button button-secondary butterbean-change-media">{{ data.l10n.change }}</button>
		<button type="button" class="button button-secondary butterbean-remove-media">{{ data.l10n.remove }}</button>
	<# } else { #>
		<button type="button" class="button button-secondary butterbean-add-media">{{ data.l10n.upload }}</button>
	<# } #>
</p>
