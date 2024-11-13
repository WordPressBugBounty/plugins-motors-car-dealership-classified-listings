<?php
$is_pro = apply_filters( 'is_mvl_pro', false );
?>
<div class="mvl-addons">
	<?php
	if ( ! $is_pro ) {
		?>
	<div class="mvl-addon-banner">
		<div class="mvl-addon-banner__text">
			<h2>
				<strong class="mvl-addon-banner__text_primary"><?php echo esc_html__( 'Unlock all addons WITH MOTORS PRO!', 'stm_vehicles_listing' ); ?></strong>
			</h2>
			<ul>
				<li>
					<div class="mvl-addon-banner__wrapper">
						<div class="mvl-addon-banner__image">
							<img class="mvl-addon-banner__addons" src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/PremiumAddons.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( 'Premium addons', 'stm_vehicles_listing' ); ?>
					</div>
				</li>
				<li>
					<div class="mvl-addon-banner__wrapper">
						<div class="mvl-addon-banner__image">
							<img class="mvl-addon-banner__support" src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/Priority.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( 'Priority ticket support', 'stm_vehicles_listing' ); ?>
					</div>
				</li>
				<li>
					<div class="mvl-addon-banner__wrapper">
						<div class="mvl-addon-banner__image">
							<img class="mvl-addon-banner__updates" src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/FrequentUpdates.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( 'Frequent updates', 'stm_vehicles_listing' ); ?>
					</div>
				</li>
				<li>
					<div class="mvl-addon-banner__wrapper">
						<div class="mvl-addon-banner__image">
							<img class="mvl-addon-banner__starter" src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/StarterThemes.svg' ); ?>" alt="">
						</div>
						<?php echo esc_html__( 'Starter themes', 'stm_vehicles_listing' ); ?>
					</div>
				</li>
			</ul>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mvl-go-pro&source=get-now-button-addons-banner' ) ); ?>" class="mvl-addon-banner__button" target="_blank">
				<i class="fas fa-arrow-right"></i>
				<?php echo esc_html__( 'Get Now', 'stm_vehicles_listing' ); ?>
			</a>
		</div>
	</div>
		<?php
	}

	foreach ( $addons as $key => $addon ) {
		$addon_enabled = ! empty( $enabled_addons[ $key ] );
		?>
		<div class="mvl-addon <?php echo $addon_enabled ? 'active' : ''; ?>">
			<div class="addon-image">
				<img src="<?php echo esc_url( $addon['url'] ); ?>"/>
			</div>
			<div class="addon-install">
				<div class="addon-title">
					<h4 class="addon-name"><?php echo wp_kses( $addon['name'], array() ); ?></h4>
					<a class="addon-settings <?php echo esc_attr( $is_pro && $addon_enabled ? 'active' : '' ); ?>" href="<?php echo esc_attr( $addon['settings'] ); ?>">
						<img src="<?php echo esc_attr( STM_LISTINGS_URL . '/assets/addons/img/gear.svg' ); ?>" alt="Motors addon settings">
					</a>
				</div>
				<div class="addon-description">
					<?php echo wp_kses( $addon['description'], array() ); ?>
				</div>
				<div class="addon-settings-wrapper">
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
						<?php if ( ! $is_pro ) { ?>
							<div class="addon-checkbox__locked">
								<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/LockedIcon.svg' ); ?>" class="addon-checkbox__locked-img">
								<div class="addon-checkbox__locked-dropdown">
									<?php esc_html_e( 'This addon available in Pro version', 'stm_vehicles_listing' ); ?>
								</div>
							</div>
							<?php
						}
						if ( ! empty( $addon['documentation'] ) ) {
							?>
						<div class="addon-documentation">
							<a href="<?php echo esc_url( $addon['documentation'] ); ?>" target="_blank">
								<?php esc_html_e( 'How it works', 'stm_vehicles_listing' ); ?>
							</a>
							<i class="far fa-question-circle"></i> 
						</div>
				<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

