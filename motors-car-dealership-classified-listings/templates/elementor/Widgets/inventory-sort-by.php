<div class="stm-sort-by-options clearfix">
	<span><?php esc_html_e( 'Sort by:', 'stm_vehicles_listing' ); ?></span>
	<div class="stm-select-sorting">
		<select data-elementor-widget-class="inventory-sort-by">
			<?php echo wp_kses_post( apply_filters( 'stm_get_sort_options_html', '' ) ); ?>
		</select>
	</div>
</div>
