<?php

use MotorsVehiclesListing\Addons\Addons;

$banner = Addons::list()[ $addon ];
?>
<div class="motors-unlock-addons-wrapper">
	<div class="unlock-addons-inner-wrapper">
		<div class="unlock-wrapper-content">
			<h2>
				<?php echo esc_html__( 'Unlock', 'stm_vehicles_listing' ); ?>
				<span class="unlock-addon-name">
					<?php echo esc_html( $banner['name'] ?? '' ); ?>
				</span>
				<?php echo esc_html__( 'addon', 'stm_vehicles_listing' ); ?>
				<div class="unlock-pro-logo-wrapper">
					<?php echo esc_html__( 'with', 'stm_vehicles_listing' ); ?>&nbsp;
					<span class="unlock-pro-logo">
						<?php echo esc_html__( ' Motors', 'stm_vehicles_listing' ); ?>
					</span>
					<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/pro/unlock-pro-logo.svg' ); ?>">
				</div>
			</h2>
			<p><?php echo esc_html( ( $banner['description'] ?? 'default' ) ); ?> </p>
			<div class="unlock-addons-buttons">
				<a href="<?php echo esc_url( admin_url( "admin.php?page=mvl-go-pro&source={$banner['img_url']}" ) ); ?>" target="_blank" class="primary button btn">
					<?php echo esc_html__( 'Upgrade to PRO', 'stm_vehicles_listing' ); ?>
				</a>
				<a href="<?php echo esc_url( $banner['documentation'] ); ?>" target="_blank" class="secondary button btn">
					<?php echo esc_html__( 'Learn more', 'stm_vehicles_listing' ); ?>
				</a>
			</div>
		</div>
		<div class="unlock-wrapper-illustration">
			<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/addons/img/' . ( $banner['img_url'] ?? 'default' ) . '.png' ); ?>">
		</div>
	</div>
</div>
