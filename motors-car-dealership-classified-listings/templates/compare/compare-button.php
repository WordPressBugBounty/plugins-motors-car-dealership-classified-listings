<?php
	$compare_page_link = get_permalink( apply_filters( 'motors_vl_get_nuxy_mod', '', 'compare_page' ) );
?>
<a href="<?php echo esc_url( $compare_page_link ); ?>" class="motors-compare-button">
	<span class="compare-icon-wrapper">
		<i class="motors-icons-mvl-compare"></i>
	</span>
	<span class="compare-text"><?php esc_html_e( 'Compare', 'stm_vehicle_listings' ); ?></span>
	<span class="stm-current-cars-in-compare">
		<?php
			echo esc_html( count( apply_filters( 'stm_get_compared_items', array() ) ) );
		?>
	</span>
</a>
