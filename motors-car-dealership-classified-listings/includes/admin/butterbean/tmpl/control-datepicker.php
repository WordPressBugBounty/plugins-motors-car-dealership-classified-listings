<label>
	<div class="butterbean-label-wrapp">
		<# if ( data.label ) { #>
			<span class="butterbean-label">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="butterbean-description">
				{{{ data.description }}}
				<# if ( data.preview ) { #>
					<div class="image_preview">

						<i class="motors-icons-ico_mag_eye"></i>
						<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg">Preview</span>

					</div>
				<# } #>
			</span>
		<# } else { #>
			<span class="butterbean-description butterbean-no-info">
				<# if ( data.preview ) { #>
					<div class="image_preview dede">
						<i class="motors-icons-ico_mag_eye"></i>
						<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg">Preview</span>
					</div>
				<# } #>
			</span>
		<# } #>
	</div>

	<input type="text" value="{{ data.value }}" {{{ data.attr }}} placeholder="<?php echo esc_html__( 'Enter' ) . ' '; ?>{{ data.label }}" />
</label>
