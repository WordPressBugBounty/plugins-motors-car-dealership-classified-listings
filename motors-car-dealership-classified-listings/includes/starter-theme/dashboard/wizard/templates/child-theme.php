<div class="mst-starter-wizard__wrapper-content">
	<div class="mst-starter-wizard__child-theme">
		<div class="mst-starter-wizard__title">
			<?php echo esc_html__( 'Install child theme', 'motors-starter-theme' ); ?>
		</div>
		<div class="mst-starter-wizard__description">
			<?php echo esc_html__( 'Let\'s set up a child theme to easily customize your website without losing changes during theme updates.', 'motors-starter-theme' ); ?>
		</div>
		<?php
		$items = array(
			array(
				'icon'        => 'mst-icon-tabler_shield-check',
				'title'       => 'Update Safety',
				'description' => 'Changes in a child theme are not lost when the parent theme is updated.',
			),
			array(
				'icon'        => 'mst-icon-tabler_adjustments-horizontal',
				'title'       => 'Easy Customization',
				'description' => 'Make changes to the look and functionality without editing the main theme.',
			),
			array(
				'icon'        => 'mst-icon-tabler_code',
				'title'       => 'Original Code Safe',
				'description' => 'The parent theme stays untouched, so you can always revert back if needed.',
			),
			array(
				'icon'        => 'mst-icon-tabler_bug',
				'title'       => 'Simplified Debugging',
				'description' => 'Easily fix issues since your changes are stored separately in the child theme.',
			),
			array(
				'icon'        => 'mst-icon-tabler_checklist',
				'title'       => 'Best Practices',
				'description' => 'Using a child theme ensures flexibility, reliability, and follows WordPress standards.',
			),
		);
		?>
		<ul>
			<?php foreach ( $items as $item ) : ?>
			<li class="mst-starter-wizard__child-theme__icon-box">
				<div class="mst-starter-wizard__child-theme__icon-box__icon">
					<span class="<?php echo esc_attr( $item['icon'] ); ?>"></span>
				</div>
				<div class="mst-starter-wizard__child-theme__icon-box__info">
					<div class="mst-starter-wizard__child-theme__icon-box__info-title"><?php echo esc_html( $item['title'] ); ?></div>
					<div class="mst-starter-wizard__child-theme__icon-box__info-description"><?php echo esc_html( $item['description'] ); ?></div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="mst-starter-wizard__progress-wrap">
			<div class="mst-starter-wizard__progress-bar">
				<div class="mst-starter-wizard__progress-bar-fill"></div>
			</div>
		</div>
		<div class="mst-starter-wizard__button-box">
			<div class="mst-starter-wizard__button mst-starter-wizard__button-skip" data-template="<?php echo esc_attr( 'finish' ); ?>">
				<?php echo esc_html__( 'Skip', 'motors-starter-theme' ); ?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-install-child">
				<?php echo esc_html__( 'Install', 'motors-starter-theme' ); ?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-next" data-template="<?php echo esc_attr( 'finish' ); ?>">
				<?php echo esc_html__( 'Continue', 'motors-starter-theme' ); ?>
			</div>
		</div>
	</div>
</div>
