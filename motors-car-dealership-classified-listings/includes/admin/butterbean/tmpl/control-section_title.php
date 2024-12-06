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
		<# if (data.link ) { #>
		<div class="butterbean-section-heading-wrapper-inner-two">
			<a class="button button-secondary" href="{{data.link}}" target="_blank">
				<i class="fa-solid fa-arrow-up-right-from-square"></i>
				<?php esc_html_e( 'Manage Features List', 'stm_vehicles_listing' ); ?>
			</a>
		</div>
		<# } #>
	</div>
<# } #>
