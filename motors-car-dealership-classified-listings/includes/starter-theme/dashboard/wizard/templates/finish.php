<div class="mst-starter-wizard__wrapper-content">
	<div class="mst-starter-wizard__finish">
		<lottie-player src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/json/all-done-animation.json' ); ?>" background="transparent" speed="1"  style="width: 110px; height: 110px;" autoplay></lottie-player>
		<div class="mst-starter-wizard__title"><?php echo esc_html__( 'All done. Have fun!', 'motors-starter-theme' ); ?></div>
		<div class="mst-starter-wizard__description"><?php echo esc_html__( 'You\'ve successfully set up the Motors plugin. Now you can start creating and managing your listings with ease.', 'motors-starter-theme' ); ?></div>
		<div class="mst-starter-wizard__button-box">
			<a href="<?php echo esc_url( home_url() ); ?>" class="mst-starter-wizard__button"><?php echo esc_html__( 'View your website', 'motors-starter-theme' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=mst-starter-options' ) ); ?>"><?php echo esc_html__( 'Back to dashboard', 'motors-starter-theme' ); ?></a>
		</div>
	</div>
</div>
