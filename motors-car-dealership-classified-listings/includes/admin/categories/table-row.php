<?php
	/**
	 * @var int $option_key
	 * @var array $option
	 * */

	$icon = ( ! empty( $option['font'] ) ) ? $option['font'] : '';
?>
<tr class="stm_custom_fields__table--tr" data-tr="<?php echo esc_attr( $option_key ); ?>">
	<td class="stm_custom_fields__table--icon" data-column="font">
		<div class="stm_custom_fields__icon--wrapper">
			<span class="stm_custom_fields__table--drag">
				<i class="stm-admin-icon-drag"></i>
			</span>
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
		</div>
	</td>
	<td class="stm_custom_fields__table--title" data-column="single_name">
		<?php echo esc_html( $option['single_name'] ); ?>
	</td>
	<td class="stm_custom_fields__table--slug" data-column="slug">
		<?php echo esc_html( $option['slug'] ); ?>
	</td>
	<td class="stm_custom_fields__table--type" data-column="type">
		<?php ( ! empty( $option['numeric'] ) && $option['numeric'] ) ? esc_html_e( 'Number', 'stm_vehicles_listing' ) : esc_html_e( 'Dropdown', 'stm_vehicles_listing' ); ?>
	</td>
	<td class="stm_custom_fields__table--edit">
		<i class="stm-admin-icon-edit"></i>
	</td>
</tr>
