<div class="stm-sort-by-options clearfix">
	<i class="motors-icons-switch-vertical-ico"></i>
	<div class="stm-select-sorting">
		<select name="sort_order">
			<?php echo wp_kses_post( apply_filters( 'stm_get_sort_options_html', '' ) ); ?>
		</select>
	</div>
</div>
