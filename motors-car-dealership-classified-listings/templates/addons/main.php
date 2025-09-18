<?php
$is_pro = apply_filters( 'is_mvl_pro', false );
?>
<div class="mvl-addons">
	<?php
	if ( ! $is_pro ) {
		?>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=mvl-go-pro&source=get-now-button-addons-banner' ) ); ?>" target="_blank" class="mvl-addon-banner">
	</a>
		<?php
	}

	foreach ( $all_features as $key => $feature ) {
		$addon_enabled = ! empty( $enabled_addons[ $key ] );
		?>
		<div class="mvl-addon <?php echo $addon_enabled ? 'active' : ''; ?>">
			<div class="addon-image">
				<img src="<?php echo esc_url( $feature['url'] ); ?>"/>
			</div>
			<div class="addon-install">
				<div class="addon-title">
					<h4 class="addon-name"><?php echo wp_kses( $feature['name'], array() ); ?></h4>
					<a class="addon-settings <?php echo esc_attr( ( $is_pro && $addon_enabled ) || ( $is_pro && ! $feature['toggle'] ) ? 'active' : '' ); ?>" href="<?php echo esc_attr( $feature['settings'] ); ?>">
						<img src="<?php echo esc_attr( STM_LISTINGS_URL . '/assets/addons/img/gear.svg' ); ?>" alt="Motors addon settings">
					</a>
				</div>
				<div class="addon-description">
					<?php echo wp_kses( $feature['description'], array() ); ?>
				</div>
				<div class="addon-settings-wrapper">
					<?php if ( $feature['toggle'] ) { ?>
						<div class="addon-checkbox section_2-enable_courses_filter">
							<?php if ( ! $is_pro ) { ?>
								<div class="addon-checkbox__overlay"></div>
							<?php } ?>
							<label class="addon-checkbox__label" data-key="<?php echo esc_attr( $key ); ?>">
								<div class="addon-checkbox__wrapper <?php echo esc_attr( $is_pro && $addon_enabled ? 'addon-checkbox__wrapper_active' : '' ); ?>">
									<div class="addon-checkbox__switcher"></div>
									<input type="checkbox" name="enable_courses_filter" id="section_2-enable_courses_filter">
								</div>
							</label>
							<span class="addon-checkbox__status">
								<?php esc_html_e( 'Enable', 'stm_vehicles_listing' ); ?>
							</span>
						</div>
						<?php if ( ! $is_pro && $feature['toggle'] ) { ?>
							<div class="addon-checkbox__locked">
								<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/locked-icon.svg' ); ?>" class="addon-checkbox__locked-img">
								<div class="addon-checkbox__locked-dropdown">
									<?php esc_html_e( 'This addon available in Pro version', 'stm_vehicles_listing' ); ?>
								</div>
							</div>
							<?php
						}
					}
					if ( ! empty( $feature['documentation'] ) ) {
						?>
						<div class="addon-documentation <?php echo esc_attr( $feature['toggle'] ? '' : 'link-align-left' ); ?>">
							<a href="<?php echo esc_url( $feature['documentation'] ); ?>" target="_blank">
								<?php esc_html_e( 'How it works', 'stm_vehicles_listing' ); ?>
							</a>
							<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/info.svg' ); ?>" alt="Info">
						</div>
				<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

