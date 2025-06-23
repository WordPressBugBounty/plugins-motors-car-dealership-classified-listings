<?php
// Output JavaScript template.
?>
<# if (data.heading ) { #>
	<div class="butterbean-section-heading-wrapper">
		<div class="butterbean-section-heading-wrapper-inner-one">
			<h2 class="butterbean-section-heading">{{{ data.heading }}}</h2>
			<# if ( data.preview ) { #>
			<div class="image_preview">

				<i class="motors-icons-ico_mag_eye"></i>
				<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg"><?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?></span>

			</div>
			<# } #>
		</div>
	</div>
<# } #>
