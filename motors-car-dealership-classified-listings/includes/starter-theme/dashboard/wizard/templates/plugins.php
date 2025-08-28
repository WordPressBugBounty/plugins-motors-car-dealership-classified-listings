<div class="mst-starter-wizard__wrapper-content">
	<div class="mst-starter-wizard__plugins">
		<div class="mst-starter-wizard__title">
			<?php echo esc_html__( 'Install plugins', 'motors-starter-theme' ); ?>
		</div>
		<div class="mst-starter-wizard__description">
			<?php echo esc_html__( 'Let’s install some essential plugins from WordPress plugins page.', 'motors-starter-theme' ); ?>
		</div>
		<ul>
		<?php
		$plugins = apply_filters( 'mvl_motors_starter_theme_plugins', array() );

		foreach ( $plugins as $plugin ) :
			?>
			<li
				class="mst-starter-wizard__plugin for-elementor <?php echo ( 'Activated' === $plugin['description'] ) ? 'mst-starter-wizard__plugin-loaded' : ''; ?>"
				data-plugin="<?php echo esc_attr( $plugin['slug'] ); ?>"
				data-status="<?php echo esc_attr( $plugin['description'] ); ?>"
			>
				<div class="mst-starter-wizard__plugin-image">
					<img src="<?php echo esc_url( $plugin['image'] ); ?>" alt="<?php echo esc_html( $plugin['title'] ); ?>">
				</div>
				<div class="mst-starter-wizard__plugin-info">
					<div class="mst-starter-wizard__plugin-info__title"><?php echo esc_html( $plugin['title'] ); ?></div>
					<div class="mst-starter-wizard__plugin-info__description"><?php echo esc_html( $plugin['description'] ); ?></div>
				</div>
				<div class="mst-starter-wizard__plugin-indicator">
					<div class="active"><?php echo esc_html__( 'Active', 'motors-starter_theme' ); ?></div>
					<div class="installing">
						<?php echo esc_html__( 'Installing', 'motors-starter_theme' ); ?>
						<span></span>
					</div>
					<div class="not-installed"><?php echo esc_html__( 'Not Installed', 'motors-starter_theme' ); ?></div>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<div class="mst-starter-wizard__button-box">
			<div class="mst-starter-wizard__button mst-starter-wizard__button-back" data-template="<?php echo esc_attr( 'templates' ); ?>">
				<?php echo esc_html__( 'Back', 'motors-starter-theme' ); ?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-skip" data-template="<?php echo esc_attr( 'demo-content' ); ?>">
				<?php echo esc_html__( 'Skip', 'motors-starter-theme' ); ?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-install">
				<?php echo esc_html__( 'Install and activate', 'motors-starter-theme' ); ?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-next" data-template="<?php echo esc_attr( 'demo-content' ); ?>">
				<?php echo esc_html__( 'Continue', 'motors-starter-theme' ); ?>
			</div>
		</div>
	</div>
</div>
