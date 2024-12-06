<div class="mvl-repeater video-repeater">
	<# _.each( data.values, function( value, key) { #>
		<div class="mvl-repeater-item video-repeater-item">
			<span class="mvl-repeater-item-delete">
				<i class="fas fa-times butterbean-delete-field" data-delete="{{key}}"></i>
			</span>
			<h4 class="mvl-repeater-item-heading"><?php echo esc_html__( 'Gallery Video (Embed video URL)', 'stm_vehicles_listing' ); ?></h4>
			<input class="video-repeater-link-input" type="text" name="video_links[]" value="{{value.link}}" data-key="{{key}}" {{{ data.attr }}} placeholder="<?php echo esc_attr__( 'Enter embed video URL', 'stm_vehicles_listing' ); ?>" />
			<input type="hidden" class="butterbean-attachment-id" name="video_image[]" value="{{ value.img }}" data-key="{{key}}" {{{ data.attr }}} />
			<div class="mvl-repeater-item-heading-video-wrapper">
				<h4 class="mvl-repeater-item-heading"><?php echo esc_html__( 'Video Preview Image', 'stm_vehicles_listing' ); ?></h4>
				<# if ( value.img_url ) { #>
					<div class="car-manager-small-thumb-image">
						<div class="thumb-image-inner">
							<img src="{{ value.img_url }}" alt="Image" class="selected-image" />
						</div>
					</div>
				<# } #>
			</div>
			<# if ( value.img_url ) { #>
				<button type="button" class="button button-secondary butterbean-change-media" data-key="{{ key }}"><?php echo esc_html__( 'Change Image', 'stm_vehicles_listing' ); ?></button>
				<button type="button" class="button button-secondary butterbean-remove-media" data-key="{{ key }}"><?php echo esc_html__( 'Remove Image', 'stm_vehicles_listing' ); ?></button>
			<# } else { #>
				<button type="button" class="button select-image-button" data-key="{{ key }}"><?php echo esc_html__( 'Add Image', 'stm_vehicles_listing' ); ?></button>
			<# } #>
		</div>
	<# } ) #>
</div>

<div>
	<button type="button" class="button button-secondary butterbean-add-field video-repeater"><?php echo esc_html__( 'Add Videos', 'stm_vehicles_listing' ); ?></button>
<div>

<# if ( data.description ) { #>
	<span class="butterbean-description"><span class="stm-info-icon">i</span>{{{ data.description }}}</span>
<# } #>
