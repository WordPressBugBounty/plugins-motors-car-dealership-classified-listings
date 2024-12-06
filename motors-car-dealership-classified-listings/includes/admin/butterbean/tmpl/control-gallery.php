<?php
// Output JavaScript template.
?>
<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
<# } #>

<input type="hidden" class="butterbean-attachment-id" name="{{ data.field_name }}" value="{{ data.value }}" />

<# if ( data.values[0] ) { #>
	<div class="stm_mini_thumbs">
		<# _.each( data.values, function( img, id) { #>
			<div class="thumbs">
				<div class="inner" data-thumb="{{id}}">
					<img src="{{img.thumb}}" />
					<div class="inner-hover">
						<i class="fas fa-times" data-delete="{{id}}"></i>
					</div>
				</div>
			</div>
		<# } ) #>
	</div>
<# } #>

<p>
	<# if ( data.values[0] ) { #>
		<button type="button" class="button button-secondary butterbean-change-media">{{ data.l10n.change }}</button>
		<button type="button" class="button button-secondary butterbean-remove-media">{{ data.l10n.remove }}</button>
	<# } else { #>
		<button type="button" class="button button-secondary butterbean-add-media">{{ data.l10n.upload }}</button>
	<# } #>
</p>

<# if ( data.description ) { #>
	<span class="butterbean-description"><span class="stm-info-icon">i</span>{{{ data.description }}}</span>
<# } #>
