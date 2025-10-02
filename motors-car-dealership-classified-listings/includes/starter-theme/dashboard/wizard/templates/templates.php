<div class="mst-starter-wizard__wrapper-header">
	<div class="mst-starter-wizard__wrapper-header__title">
		<?php echo esc_html__( 'Choose Skin', 'motors-starter-theme' ); ?>
	</div>
</div>
<div class="mst-starter-wizard__wrapper-content">
	<?php
	$templates = array(
		array(
			'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/demo-1.png',
			'status'      => 'Free',
			'title'       => 'Classic',
			'slug'        => 'free', //demo folder name
			'demo_status' => 'available',
			'builder'     => 'elementor',
			'old_builder' => 'elementor-builder',
			'preview'     => 'https://motors-plugin.stylemixthemes.com/',
		),
		array(
			'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/demo-2.png',
			'status'      => 'Pro',
			'title'       => 'Luxury',
			'slug'        => 'luxury',
			'demo_status' => 'available',
			'builder'     => 'elementor',
			'old_builder' => 'elementor-builder',
			'preview'     => 'https://motors-plugin.stylemixthemes.com/',
		),
		array(
			'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/demo-3.png',
			'status'      => 'Pro',
			'title'       => 'MotoVibe',
			'slug'        => '',
			'demo_status' => 'pending',
			'builder'     => 'elementor',
			'old_builder' => 'elementor-builder',
			'preview'     => 'https://motors-plugin.stylemixthemes.com/',
		),
		array(
			'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/demo-4.png',
			'status'      => 'Pro',
			'title'       => 'Aircraft',
			'slug'        => '',
			'demo_status' => 'pending',
			'builder'     => 'elementor',
			'old_builder' => 'elementor-builder',
			'preview'     => 'https://motors-plugin.stylemixthemes.com/',
		),
		array(
			'image'       => STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/demo-5.png',
			'status'      => 'Pro',
			'title'       => 'Caravans',
			'slug'        => '',
			'demo_status' => 'pending',
			'builder'     => 'elementor',
			'old_builder' => 'elementor-builder',
			'preview'     => 'https://motors-plugin.stylemixthemes.com/',
		),
	);
	?>
	<ul class="mst-starter-wizard__templates">
		<?php if ( ! apply_filters( 'is_mvl_pro', false ) ) : ?>
			<li class="mst-starter-wizard__template-elementor">
				<div class="mst-starter-wizard__template">
					<div class="mst-starter-wizard__template-image get-pro-wrap">
						<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/get-pro.jpg' ); ?>" width="" height=""/>
						<div class="pro-text-wrap">
							<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/starter-theme/dashboard/assets/images/pro-banner-text.svg' ); ?>" width="" height=""/>
							<a href="<?php echo esc_url( 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wp-admin&utm_medium=push&utm_campaign=motors&utm_content=gopro' ); ?>" class="mst-starter-wizard__button"
								target="_blank"><?php echo esc_html__( 'Get Now', 'motors-starter-theme' ); ?><i class="mst-icon-arrow-right1"></i> </a>
						</div>
					</div>
					<div class="mst-starter-wizard__template-title"><?php echo esc_html__( 'Upgrade to MOTORS PRO', 'motors-starter-theme' ); ?></div>
				</div>
			</li>
		<?php endif; ?>
		<?php
		foreach ( $templates as $template ) :
			$activated_demo = get_option( 'mvl_motors_starter_demo_activated' );
			$active_builder = get_option( 'mst-starter-theme-builder' );

			$is_installed = ( $activated_demo === $template['slug'] ) || ( empty( $activated_demo ) && 'Starter' === $template['title'] && $active_builder === $template['old_builder'] );
			?>
			<li class="mst-starter-wizard__template-<?php echo esc_attr( $template['builder'] ); ?>">
				<div class="mst-starter-wizard__template
			<?php
				echo esc_attr( $is_installed ? 'installed' : '' );
				echo esc_attr( ( in_array( $template['demo_status'], array( 'pending', 'available' ), true ) && apply_filters( 'is_mvl_pro', false ) ) ? 'pending' : '' );
			?>
			">
					<div class="mst-starter-wizard__template-image">
						<div class="mst-starter-wizard__wrapper-content-preloader disabled"></div>
						<img src="<?php echo esc_url( $template['image'] ); ?>" width="" height=""
							alt="<?php echo esc_attr( $template['title'] ); ?>">
						<?php if ( $is_installed ) : ?>
							<div class="mst-starter-wizard__template-installed">
								<?php echo esc_html__( 'Active', 'motors-starter-theme' ); ?>
							</div>
						<?php endif; ?>
						<?php if ( ( 'Pro' === $template['status'] && apply_filters( 'is_mvl_pro', false ) && 'pending' !== $template['demo_status'] ) || 'Free' === $template['status'] ) : ?>
							<div class="mst-status-btns-container">
								<div class="mst-starter-wizard__button mst-starter-wizard__button-continue <?php echo ( ! empty( get_option( 'mvl_motors_starter_demo_activated' ) ) ) ? 'demo-activated' : ''; ?>"
									data-template="<?php echo esc_attr( 'plugins' ); ?>"
									data-builder="<?php echo esc_attr( $template['builder'] ); ?>"
									data-demo="<?php echo esc_attr( $template['slug'] ); ?>">
									<?php ( $is_installed ) ? esc_html_e( 'Reinstall Skin', 'motors-starter-theme' ) : esc_html_e( 'Install Skin', 'motors-starter-theme' ); ?>
								</div>
								<a href="<?php echo esc_url( $template['preview'] ); ?>"
								class="mst-starter-wizard__button mst-starter-wizard__button-preview"
								target="_blank"><?php echo esc_html__( 'preview', 'motors-starter-theme' ); ?></a>
							</div>
						<?php endif; ?>
						<?php if ( 'Pro' === $template['status'] && ! apply_filters( 'is_mvl_pro', false ) || ( apply_filters( 'is_mvl_pro', false ) && 'pending' === $template['demo_status'] ) ) : ?>
							<div class="mst-starter-wizard__template-commig-soon">
								<span><?php ( 'pending' === $template['demo_status'] ) ? esc_html_e( 'Coming soon', 'motors-starter-theme' ) : esc_html_e( 'Available in PRO', 'motors-starter-theme' ); ?></span>
							</div>
						<?php endif; ?>
					</div>
					<div class="mst-starter-wizard__template-title">
						<?php echo esc_html( $template['title'] ); ?>
						<div class="mst-starter-wizard__template-status <?php echo ( 'Pro' === $template['status'] ) ? 'premium' : ''; ?>">
							<?php echo esc_attr( $template['status'] ); ?>
						</div>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<div style="display: none;" class="mst-starter-wizard__template-popup">
		<div class="mst-starter-wizard__template-popup__content">
			<span class="mst-starter-wizard__button-close"></span>
			<h2><?php echo esc_html__( 'Clean your WordPress database before installing a new pre-built site.', 'motors-starter-theme' ); ?></h2>
			<p><?php echo __( 'Installing a new skin will delete existing content, including pages, custom fields, and listings. Your user accounts, passwords, and server files will remain untouched. Proceed only if you\'re ready to reset your site’s content.', 'motors-starter-theme' );// phpcs:ignore ?></p>
			<div class="mst-starter-wizard__demo-checkbox">
				<label><span class="demo-checkbox" data-checked="false"><span
								class="mst-icon-check"></span></span><?php echo esc_html__( 'I understand that this action cannot be undone', 'motors-starter-theme' ); ?>
				</label>
			</div>
			<div class="mst-starter-wizard__progress-wrap">
				<div class="mst-starter-wizard__progress-bar">
					<div class="mst-starter-wizard__progress-bar-fill"></div>
				</div>
			</div>
			<div class="mst-starter-wizard__button-box">
				<div class="mst-starter-wizard__button mst-starter-wizard__button-reset disabled" data-template="<?php echo esc_attr( 'plugins' ); ?>">
					<?php echo esc_html__( 'Reset now', 'motors-starter-theme' ); ?>
				</div>
				<div class="mst-starter-wizard__button mst-starter-wizard__button-skip"
					data-template="<?php echo esc_attr( 'plugins' ); ?>">
					<?php echo esc_html__( 'Skip', 'motors-starter-theme' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
