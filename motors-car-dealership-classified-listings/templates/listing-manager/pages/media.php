<?php
$videos       = $listing_manager_page->get_videos();
$videos_limit = 2;
?>
<div class="mvl-listing-manager-content-body-page-header">
	<div class="mvl-listing-manager-content-body-page-title-wrapper">
		<div class="mvl-listing-manager-content-body-page-title">
			<?php echo esc_html( $listing_manager_page->get_title() ); ?>
		</div>
		<?php if ( $listing_manager_page->has_preview() ) : ?>
		<div class="mvl-listing-manager-content-body-page-preview-wrapper" mvl-tooltip-image="<?php echo esc_url( $listing_manager_page->get_preview_url() ); ?>" mvl-tooltip-position="bottom" mvl-tooltip-toggle="mvl-listing-manager-content-body-page-preview-img">
			<div class="mvl-listing-manager-content-body-page-preview">
				<i class="motors-icons-mvl-eye"></i>
				<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<div class="mvl-listing-manager-content-body-page-text">
	<div class="mvl-listing-manager-content-body-page-title" data-label="<?php echo esc_html__( 'Image Gallery', 'stm_vehicles_listing' ); ?>">
		<?php echo esc_html__( 'Image Gallery', 'stm_vehicles_listing' ); ?>
	</div>
	<div class="mvl-listing-manager-content-body-page-fields-row">
		<?php
		do_action(
			'stm_listings_load_template',
			'listing-manager/parts/fields/image',
			array(
				'id'          => 'images',
				'input_name'  => $listing_manager_page->get_id() . '[gallery]',
				'value'       => $listing_manager_page->get_listing_images_ids(),
				'draggable'   => true,
				'files_limit' => 10,
				'dropzone'    => true,
				'description' => esc_html__( 'Add images to your listing item. The first image will be featured. Drag and drop to change the order.', 'stm_vehicles_listing' ),
			)
		);
		?>
	</div>
	<div class="mvl-listing-manager-content-body-page-title include-margin" data-label="<?php echo esc_html__( 'Video Gallery', 'stm_vehicles_listing' ); ?>">
		<?php echo esc_html__( 'Video Gallery', 'stm_vehicles_listing' ); ?>
	</div>
	<?php
	if ( empty( $videos ) ) {
		?>
	<div class="mvl-listing-manager-content-body-page-fields-group mvl-listing-manager-video-fields" data-label="<?php echo esc_html__( 'Video', 'stm_vehicles_listing' ); ?>">
		<div class="mvl-listing-manager-content-body-page-fields-row">
			<?php
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/text',
					array(
						'id'          => 'video_url',
						'label'       => __( 'Video URL', 'stm_vehicles_listing' ),
						'placeholder' => __( 'Enter video URL', 'stm_vehicles_listing' ),
						'input_name'  => $listing_manager_page->get_id() . '[video][url][]',
					)
				);
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/fields/image',
					array(
						'id'         => 'video_poster',
						'dropzone'   => true,
						'label'      => __( 'Video Preview', 'stm_vehicles_listing' ),
						'input_name' => $listing_manager_page->get_id() . '[video][image][]',
					)
				);
			?>
		</div>
	</div>
		<?php
	} else {
		$is_first = true;
		foreach ( $videos as $video ) {
			?>
			<div class="mvl-listing-manager-content-body-page-fields-group mvl-listing-manager-video-fields">
			<div class="mvl-listing-manager-content-body-page-fields-row">
				<?php if ( ! $is_first ) : ?>
					<div class="repeater-delete-btn"
					data-groupclass="mvl-listing-manager-video-fields"
					data-templateclass="mvl-listing-manager-video-fields-template"
					data-limit="<?php echo esc_attr( $videos_limit ); ?>"
					>
					</div>
				<?php endif; ?>
				<?php
					do_action(
						'stm_listings_load_template',
						'listing-manager/parts/fields/text',
						array(
							'id'          => 'video_url',
							'label'       => __( 'Video URL', 'stm_vehicles_listing' ),
							'placeholder' => __( 'Enter video URL', 'stm_vehicles_listing' ),
							'input_name'  => $listing_manager_page->get_id() . '[video][url][]',
							'value'       => $video['url'],
						)
					);
					do_action(
						'stm_listings_load_template',
						'listing-manager/parts/fields/image',
						array(
							'id'         => 'video_poster',
							'dropzone'   => true,
							'label'      => __( 'Video Preview', 'stm_vehicles_listing' ),
							'input_name' => $listing_manager_page->get_id() . '[video][image][]',
							'value'      => $video['poster_id'],
						)
					);
				?>
			</div>
		</div>
			<?php
			$is_first = false;
		}
	}
	?>
	<div class="mvl-primary-btn repeater-add-btn mvl-plus-icon"
	data-groupclass="mvl-listing-manager-video-fields"
	data-templateclass="mvl-listing-manager-video-fields-template"
	data-limit="<?php echo esc_attr( $videos_limit ); ?>"
	<?php echo count( $videos ) >= $videos_limit ? 'style="display: none;"' : ''; ?>>
		<?php esc_html_e( 'Add Video', 'stm_vehicles_listing' ); ?>
	</div>
</div>
<template class="mvl-listing-manager-video-fields-template">
	<div class="mvl-listing-manager-content-body-page-fields-group mvl-listing-manager-video-fields">
		<div class="repeater-delete-btn"
		data-groupclass="mvl-listing-manager-video-fields"
		data-templateclass="mvl-listing-manager-video-fields-template"
		data-limit="<?php echo esc_attr( $videos_limit ); ?>"
		>
		</div>
		<div class="mvl-listing-manager-content-body-page-fields-row">
			<?php
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/text',
				array(
					'id'          => 'video_url',
					'label'       => __( 'Video URL', 'stm_vehicles_listing' ),
					'placeholder' => __( 'Enter video URL', 'stm_vehicles_listing' ),
					'input_name'  => $listing_manager_page->get_id() . '[video][url][]',
					'value'       => '',
					'label'       => __( 'Video URL', 'stm_vehicles_listing' ),
				)
			);
			do_action(
				'stm_listings_load_template',
				'listing-manager/parts/fields/image',
				array(
					'id'         => 'video_poster',
					'dropzone'   => true,
					'label'      => __( 'Video Preview', 'stm_vehicles_listing' ),
					'input_name' => $listing_manager_page->get_id() . '[video][image][]',
					'label'      => __( 'Video Preview', 'stm_vehicles_listing' ),
				)
			);
			?>
		</div>
	</div>
</template>
