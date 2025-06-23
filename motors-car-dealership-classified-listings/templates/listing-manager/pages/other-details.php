<div class="mvl-listing-manager-content-body-page-header">
	<div class="mvl-listing-manager-content-body-page-title-wrapper">
		<div class="mvl-listing-manager-content-body-page-title">
			<?php echo esc_html( $listing_manager_page->get_title() ); ?>
		</div>
		<?php if ( $listing_manager_page->has_preview() ) : ?>
		<div class="mvl-listing-manager-content-body-page-preview-wrapper">
			<div class="mvl-listing-manager-content-body-page-preview">
				<i class="motors-icons-mvl-eye"></i>
				<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="mvl-listing-manager-content-body-page-text">
	<div class="mvl-listing-manager-content-body-page-fields-row">
		<?php
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/input',
			array(
				'id'          => 'vin_number',
				'type'        => 'text',
				'label'       => __( 'VIN Number', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter VIN number', 'stm_vehicles_listing' ),
				'input_name'  => $listing_manager_page->get_id() . '[vin_number]',
				'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'vin_number', true ) : '',
			)
		);

		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/input',
			array(
				'id'          => 'stock_number',
				'type'        => 'text',
				'label'       => __( 'Stock Number', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter stock number', 'stm_vehicles_listing' ),
				'input_name'  => $listing_manager_page->get_id() . '[stock_number]',
				'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'stock_number', true ) : '',
			)
		);

		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/input',
			array(
				'id'           => 'manufacture_date',
				'type'         => 'date',
				'label'        => __( 'Manufacture Date', 'stm_vehicles_listing' ),
				'placeholder'  => __( 'dd/mm/yyyy', 'stm_vehicles_listing' ),
				'autocomplete' => 'off',
				'input_name'   => $listing_manager_page->get_id() . '[registration_date]',
				'value'        => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'registration_date', true ) : '',
			)
		);

		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/input',
			array(
				'id'          => 'city_fuel_efficiency',
				'type'        => 'number',
				'label'       => __( 'City Fuel Efficiency', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter city fuel efficiency', 'stm_vehicles_listing' ),
				'input_name'  => $listing_manager_page->get_id() . '[city_mpg]',
				'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'city_mpg', true ) : '',
			)
		);
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/input',
			array(
				'id'          => 'highway_fuel_efficiency',
				'type'        => 'text',
				'label'       => __( 'Highway Fuel Efficiency', 'stm_vehicles_listing' ),
				'placeholder' => __( 'Enter highway fuel efficiency', 'stm_vehicles_listing' ),
				'input_name'  => $listing_manager_page->get_id() . '[highway_mpg]',
				'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'highway_mpg', true ) : '',
			)
		);
		?>
	</div>
	<div class="mvl-listing-manager-content-body-page-title include-margin" data-label="<?php echo esc_html__( 'Certificates', 'stm_vehicles_listing' ); ?>">
		<?php echo esc_html__( 'Certificates', 'stm_vehicles_listing' ); ?>
	</div>
	<?php foreach ( $listing_manager_page->get_certificates( $listing_manager_page->get_listing_id() ) as $certificate ) : ?>
	<div class="mvl-listing-manager-content-body-page-fields-group mvl-listing-manager-certificate-fields">
		<div class="mvl-listing-manager-content-body-page-fields-row">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'certificate_name',
					'label'       => __( 'Certificate Name', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Certificate Name', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[certificate_media_file_name][]',
					'value'       => $certificate['name'],
				)
			);
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'certificate_link',
					'label'       => __( 'Certificate Link', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Certificate Link', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[certificate_media_links][]',
					'value'       => $certificate['link'],
				)
			);
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/image',
				array(
					'id'         => 'certificate_image',
					'dropzone'   => false,
					'label'      => __( 'Certificate Logo', 'stm_vehicles_listing' ),
					'input_name' => $listing_manager_page->get_id() . '[certificate_media_image][]',
					'value'      => $certificate['image'],
				)
			);
			?>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="mvl-primary-btn mvl-listing-manager-add-certificate mvl-plus-icon repeater-add-btn"
	data-groupclass="mvl-listing-manager-certificate-fields"
	data-templateclass="mvl-listing-manager-certificate-template"
	data-limit="2"
	<?php if ( $listing_manager_page->get_listing_id() && count( $listing_manager_page->get_certificates( $listing_manager_page->get_listing_id() ) ) >= 2 ) : ?>
		style="display: none;"
	<?php endif; ?>
	>
		<?php echo esc_html__( 'Add Certificate', 'stm_vehicles_listing' ); ?>
	</div>
	<div class="mvl-listing-manager-content-body-page-fields-row">
		<div class="mvl-listing-manager-content-body-page-fields-group">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/file',
				array(
					'id'         => 'vehicle_info_pdf',
					'label'      => __( 'Vehicle Info PDF', 'stm_vehicles_listing' ),
					'input_name' => $listing_manager_page->get_id() . '[car_brochure]',
					'value'      => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'car_brochure', true ) : '',
				)
			);
			?>
		</div>
	</div>
</div>
<template class="mvl-listing-manager-certificate-template">
	<div class="mvl-listing-manager-content-body-page-fields-group mvl-listing-manager-certificate-fields">
		<div class="repeater-delete-btn"
		data-groupclass="mvl-listing-manager-certificate-fields"
		data-templateclass="mvl-listing-manager-certificate-template"
		data-limit="2"
		>
		</div>
		<div class="mvl-listing-manager-content-body-page-fields-row">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'certificate_name',
					'label'       => __( 'Certificate Name', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter Certificate Name', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[certificate_media_file_name][]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'certificate_name', true ) : '',
				)
			);
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/input',
				array(
					'id'          => 'certificate_link',
					'label'       => __( 'Certificate link', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter Certificate Link', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[certificate_media_links][]',
					'value'       => $listing_manager_page->get_listing_id() ? get_post_meta( $listing_manager_page->get_listing_id(), 'certificate_name', true ) : '',
				)
			);
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/image',
				array(
					'id'         => 'certificate_image',
					'dropzone'   => false,
					'label'      => __( 'Certificate Logo', 'stm_vehicles_listing' ),
					'input_name' => $listing_manager_page->get_id() . '[certificate_media_image][]',
				)
			);
			?>
		</div>
	</div>
</template>
