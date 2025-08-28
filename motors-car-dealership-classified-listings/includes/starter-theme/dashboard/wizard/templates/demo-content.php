<div class="mst-starter-wizard__wrapper-content">
	<div class="mst-starter-wizard__demos">
		<div class="mst-starter-wizard__title">
			<?php echo esc_html__( 'Import demo content', 'motors-starter-theme' ); ?>
		</div>
		<div class="mst-starter-wizard__description">
			<?php echo esc_html__( 'Import demo content to quickly set up your site. You can customize or delete it anytime.', 'motors-starter-theme' ); ?>
		</div>
		<?php
		$mst_settings = get_option( 'mst_settings' );
		$builder      = get_option( 'mst-starter-theme-builder' );

		$demos = array(
			array(
				'title'       => 'Demo taxonomy',
				'slug'        => 'demo-taxonomy',
				'description' => __( 'Sample categories for vehicle listings.', 'motors-starter-theme' ),
				'for_builder' => 'all',
			),
			array(
				'title'       => 'Demo Content',
				'slug'        => 'demo-content',
				'description' => __( 'Sample vehicle listings to help you see how your content will look.', 'motors-starter-theme' ),
				'for_builder' => 'all',
			),
			array(
				'title'       => 'Theme Settings',
				'slug'        => 'theme-settings',
				'description' => __( 'Uploading preset settings like colors, logos, and fonts.', 'motors-starter-theme' ),
				'for_builder' => 'all',
			),
			array(
				'title'       => 'Plugin Settings',
				'slug'        => 'mvl_plugin_settings',
				'description' => __( 'Plugin Settings', 'motors-starter-theme' ),
				'for_builder' => 'all',
			),
			array(
				'title'       => 'Generate pages',
				'slug'        => 'generate-pages',
				'description' => __( 'Motors Plugin has a few essential pages:<ul><li>Listings page;</li><li>Compare page;</li><li>Profile page;</li><li>Listing creation page.</li></ul>', 'motors-starter-theme' ),
				'for_builder' => 'all',
			),
		);

		?>
		<ul>
			<?php foreach ( $demos as $demo ) : ?>
				<li class="mst-starter-wizard__demo for-<?php echo esc_attr( $demo['for_builder'] ); ?>"
					data-demo="<?php echo esc_attr( $demo['slug'] ); ?>">
					<div class="mst-starter-wizard__demo-checkbox">
						<label>
							<span class="demo-checkbox" data-checked="true"><span
										class="mst-icon-check"></span></span><?php echo esc_html( $demo['title'] ); ?>
						</label>
						<div class="mst-starter-wizard__demo-checkbox-content"><?php echo wp_kses_post( $demo['description'] ); ?></div>
					</div>
					<div class="mst-starter-wizard__demo-indicator">
						<div class="active"><?php echo esc_html__( 'Done', 'motors-starter_theme' ); ?></div>
						<div class="installing">
							<?php echo esc_html__( 'Importing', 'motors-starter_theme' ); ?>
							<span></span>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="mst-starter-wizard__button-box">
			<div class="mst-starter-wizard__button-message">
				<?php echo esc_html__( 'An unexpected error occurred. Please try again.', 'motors-starter-theme' ); ?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-install-demo">
				<?php echo esc_html__( 'Install', 'motors-starter-theme' ); ?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-next"
				data-template="<?php echo esc_attr( 'child-theme' ); ?>">
				<?php echo esc_html__( 'Continue', 'motors-starter-theme' ); ?>
			</div>
		</div>
	</div>
</div>
