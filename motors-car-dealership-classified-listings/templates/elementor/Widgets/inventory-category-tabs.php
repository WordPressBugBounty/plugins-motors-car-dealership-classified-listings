<?php
/**
 * Inventory category tabs.
 *
 * @var string $taxonomy
 * @var string $show_all_tab
 * @var string $all_tab_label
 * @var string $hide_empty
 * @var string $show_count
 */

if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
	return;
}

$terms = get_terms(
	array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => ! empty( $hide_empty ) && 'yes' === $hide_empty,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	return;
}

$current = apply_filters( 'stm_listings_input', '', $taxonomy );

if ( is_array( $current ) ) {
	$current = reset( $current );
}

$current       = is_string( $current ) ? $current : '';
$show_all      = ! empty( $show_all_tab ) && 'yes' === $show_all_tab;
$show_counts   = ! empty( $show_count ) && 'yes' === $show_count;
$all_label     = ! empty( $all_tab_label ) ? $all_tab_label : __( 'All Types', 'stm_vehicles_listing' );
$all_url       = apply_filters( 'stm_filter_listing_link', '' );
$is_all_active = $show_all && empty( $current );
?>
<div class="motors-elementor-widget stm-inventory-category-tabs-wrap">
	<ul class="stm-inventory-category-tabs">
		<?php if ( $show_all ) : ?>
			<li class="stm-inventory-category-tabs__item<?php echo $is_all_active ? ' is-active' : ''; ?>">
				<a class="stm-inventory-category-tabs__link" href="<?php echo esc_url( $all_url ); ?>">
					<span><?php echo esc_html( $all_label ); ?></span>
				</a>
			</li>
		<?php endif; ?>

		<?php foreach ( $terms as $term ) : ?>
			<?php
			$is_active = ( $term->slug === $current );
			$term_url  = apply_filters( 'stm_filter_listing_link', '', array( $taxonomy => $term->slug ) );
			$label     = $term->name;

			if ( $show_counts ) {
				$label .= ' (' . intval( $term->count ) . ')';
			}
			?>
			<li class="stm-inventory-category-tabs__item<?php echo $is_active ? ' is-active' : ''; ?>">
				<a class="stm-inventory-category-tabs__link" href="<?php echo esc_url( $term_url ); ?>">
					<span><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
