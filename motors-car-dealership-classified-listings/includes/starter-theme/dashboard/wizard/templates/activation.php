<div class="mst-starter-wizard__wrapper-content">
	<div class="mst-starter-wizard__activations">
		<div class="mst-starter-wizard__title">
			<?php echo esc_html__( 'Do you have an activation key?', 'motors-starter-theme' ); ?>
		</div>
		<div class="mst-starter-wizard__description">
			<?php echo esc_html__( 'If you have purchased an activation key from our site, please select "Yes." If you haven\'t, click "No" to explore our available templates.', 'motors-starter-theme' ); ?>
		</div>
		<div class="mst-starter-wizard__button-box">
			<div class="mst-starter-wizard__button mst-starter-wizard__button-skip" data-template="plans">
				<?php echo esc_html__( 'No, i don’t have an activation key', 'motors-starter-theme' ); ?>
			</div>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=motors-starter-freemius' ) ); ?>" class="mst-starter-wizard__button">
				<?php echo esc_html__( 'Yes, i have an activation key', 'motors-starter-theme' ); ?>
			</a>
		</div>
	</div>
</div>
