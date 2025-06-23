<?php
$fa_icons         = stm_get_cat_icons( 'fa' );
$custom_icons     = stm_get_cat_icons( 'external' );
$motors_icons_set = stm_get_cat_icons( 'motors-icons' );

if ( ! empty( $default_icons_set ) ) {
	$custom_icons = array_merge( $custom_icons, $default_icons_set );
}
?>

<div class="mvl-options-popup-container mlv-options-popup-icons">
	<div class="mvl-options-popup-container-inner">
		<div class="mvl-options-settings-tabs">
			<div class="mvl-options-settings-tab active" data-tab="font-awesome">
				<span>
					<?php esc_html_e( 'Font Awesome Icons', 'stm_vehicles_listing' ); ?>
				</span>
			</div>
			<div class="mvl-options-settings-tab" data-tab="motors-icons">
				<span>
					<?php esc_html_e( 'Motors Icons', 'stm_vehicles_listing' ); ?>
				</span>
			</div>
		</div>
		<div class="mvl-options-settings-tab-content">
			<div class="mvl-options-settings-tab-content-item active" data-tab="font-awesome">
				<div class="mvl-icons-grid">
					<?php foreach ( $fa_icons as $icon ) : ?>
						<div class="mvl-icon-item">
							<i class="<?php echo esc_attr( $icon['main_class'] . ' fa-' . $icon['icon_class'] ); ?>"></i>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="mvl-options-settings-tab-content-item" data-tab="motors-icons">
				<div class="mvl-icons-grid">
					<?php foreach ( $motors_icons_set as $icon ) : ?>
						<div class="mvl-icon-item">
							<i class="<?php echo esc_attr( $icon ); ?>"></i>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
