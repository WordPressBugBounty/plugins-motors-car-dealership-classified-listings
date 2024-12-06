<label>
	<div class="butterbean-label-wrapp">
		<# if ( data.label ) { #>
			<span class="butterbean-label">{{ data.label }}</span>
		<# } #>

		<# if ( data.preview ) { #>
			<span class="butterbean-no-info">
					<div class="image_preview dede">
						<i class="motors-icons-ico_mag_eye"></i>
						<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg"><?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?></span>
					</div>
			</span>
		<# } #>
	</div>

	<input type="{{ data.type }}" value="{{ data.value }}" {{{ data.attr }}} placeholder="<?php echo esc_attr__( 'Enter ', 'stm_vehicles_listing' ); ?>{{ data.label }}"/>

	<# if ( data.description ) { #>
		<span class="butterbean-description">
			<span class="stm-info-icon">i</span>
			{{{ data.description }}}
		</span>
	<# } #>
	<# if ( data.attr.indexOf('reset=') !== -1 ) { #>
		<a href="#" data-type="{{data.name}}" class="reset_field"><?php esc_html_e( 'Reset counter', 'stm_vehicles_listing' ); ?></a>
	<# } #>
</label>
