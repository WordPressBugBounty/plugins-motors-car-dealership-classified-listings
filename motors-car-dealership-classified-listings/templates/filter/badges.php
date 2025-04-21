<?php
$filter_badges = apply_filters( 'stm_get_filter_badges', array() );
?>

<div class="stm-filter-chosen-units">
	<ul class="stm-filter-chosen-units-list">
		<?php foreach ( $filter_badges as $badge => $badge_info ) : ?>
			<li>
				<?php if ( ! empty( $badge_info['name'] ) ) : ?>
					<span class="stm-filter-chosen-units-list-name"><?php echo esc_html( apply_filters( 'stm_listings_dynamic_string_translation', $badge_info['name'], 'Filter Badge Name' ) ); ?>: </span>
				<?php endif; ?>
				<span class="stm-filter-chosen-units-list-value"><?php echo wp_kses_post( str_replace( '\\', '', $badge_info['value'] ) ); ?></span>
				<i data-url="<?php echo esc_url( $badge_info['url'] ); ?>"
				data-type="<?php echo esc_attr( $badge_info['type'] ); ?>"
				data-slug="<?php echo esc_attr( $badge_info['slug'] ); ?>"
				data-multiple="<?php echo ! empty( $badge_info['multiple'] ) ? esc_attr( $badge_info['multiple'] ) : ''; ?>"
				class="motors-icons-cross-ico stm-clear-listing-one-unit stm-clear-listing-one-unit-classic"></i>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
