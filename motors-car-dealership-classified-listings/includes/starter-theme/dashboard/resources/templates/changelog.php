<div class="mst-starter-wizard mst-starter-change-log" style="display: none;">
	<div class="mst-starter-change-log__title">Changelog</div>
	<?php
	$changelog = MotorsVehiclesListing\StarterTheme\Dashboard\Motors_Templates_Changelog::get_theme_changelog();
	if ( ! empty( $changelog ) ) {
		for ( $i = 0; $i <= 2; $i ++ ) :
			$changelog_item = $changelog[ $i ];
			?>
			<div class="mst-starter-change-log__header">
				<div class="mst-starter-change-log__version">Version: <?php echo esc_html( $changelog_item['heading'] ); ?></div>
				<div class="mst-starter-change-log__date"><?php echo esc_html( $changelog_item['date'] ); ?></div>
			</div>
			<ul class="mst-starter-change-log__list">
				<?php foreach ( $changelog_item['list'] as $item ) : ?>
				<li><?php echo wp_kses_post( $item ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
		endfor;
	}
	?>
	<a href="<?php echo esc_url( 'https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/changelog/changelog-starter-theme' ); ?>" target="_blank" class="mst-starter-change-log__button">See More</a>
</div>
