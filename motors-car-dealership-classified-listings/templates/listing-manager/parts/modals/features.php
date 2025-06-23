<?php
$stm_additional_features_terms = get_terms(
	array(
		'taxonomy'   => 'stm_additional_features',
		'hide_empty' => false,
	)
);

if ( empty( $current_tab ) ) {
	$current_tab = array(
		'tab_title_single'          => '',
		'tab_title_selected_labels' => array(),
	);
}

$all_features = array();
if ( ! empty( $stm_additional_features_terms ) && ! is_wp_error( $stm_additional_features_terms ) ) {
	foreach ( $stm_additional_features_terms as $term ) {
		$all_features[] = array(
			'slug' => urldecode( $term->slug ),
			'name' => $term->name,
		);
	}
}

$selected_values = array();
if ( ! empty( $current_tab['tab_title_selected_labels'] ) && is_array( $current_tab['tab_title_selected_labels'] ) ) {
	foreach ( $current_tab['tab_title_selected_labels'] as $item ) {
		if ( isset( $item['value'] ) ) {
			$selected_values[] = $item['value'];
		}
	}
}
?>
<div class="mvl-options-popup-container mvl-features-modal-content" data-option-id="<?php echo esc_attr( sanitize_title( $current_tab['tab_title_single'] ) ); ?>">
	<div class="mvl-listing-manager-features-modal-fields-group">
		<?php
		do_action(
			'stm_listings_load_template',
			'/listing-manager/parts/fields/input',
			array(
				'id'          => urldecode( $current_tab['tab_title_single'] ),
				'label'       => __( 'Title', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter title', 'stm_vehicles_listing' ),
				'value'       => isset( $current_tab['tab_title_single'] ) ? $current_tab['tab_title_single'] : '',
				'type'        => 'text',
			)
		);
		?>
		<?php
		do_action(
			'stm_listings_load_template',
			'/listing-manager/parts/fields/select',
			array(
				'id'       => urldecode( $current_tab['tab_title_single'] ),
				'options'  => $all_features,
				'label'    => __( 'Option', 'stm_vehicles_listing' ),
				'multiple' => true,
				'value'    => $selected_values,
				'dropdown' => 'checkboxes',
			)
		);
		?>
	</div>
</div>
