<?php
	$price = mvlGetAnnualPriceFromAPI();
?>
<div class="mst-starter-wizard__wrapper-content">
	<div class="mst-starter-wizard__plans">
		<div class="mst-starter-wizard__title">
			<?php echo esc_html__( 'Select plan', 'motors-starter-theme' ); ?>
		</div>
		<div class="mst-starter-wizard__price-box">
			<div class="mst-starter-wizard__price-button selected-price annual">
				<small></small>
				<?php
				echo sprintf(
					'<strong>%s</strong> <span>%s / %s</span>',
					esc_html__( 'Annual', 'motors-starter-theme' ),
					wp_kses_post( $price['annual_price'] ),
					esc_html__( 'year', 'motors-starter-theme' ),
				);
				?>
			</div>
			<div class="mst-starter-wizard__price-button lifetime">
				<small></small>
				<?php
				echo sprintf(
					'<strong>%s</strong> <span>%s</span>',
					esc_html__( 'Lifetime', 'motors-starter-theme' ),
					wp_kses_post( $price['lifetime_price'] ),
				);
				?>
			</div>
			<div class="mst-starter-wizard__button mst-starter-wizard__button-pro">
				<?php echo esc_html__( 'Continue', 'motors-starter-theme' ); ?>
			</div>
		</div>
	</div>
</div>
