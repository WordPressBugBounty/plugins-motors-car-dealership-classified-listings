<div class="mvl-repeater media-repeater">
	<# _.each( data.values, function( value, key, counter) { #>
		<div class="mvl-repeater-item media-repeater-item">
			<# if ( key !== 0 ) { #>
			<span class="mvl-repeater-item-delete">
				<i class="fas fa-times butterbean-delete-field" data-delete="{{key}}"></i>
			</span>
			<# } #>
			<div class="mvl-repeater-item-heading-wrapper">
				<h4 class="mvl-repeater-item-heading">
					{{data.label}} <?php echo esc_html__( 'Name', 'stm_vehicles_listing' ); ?>
					<# if ( key > 0 ) { #>
						<span>{{counter = key + 1}}</span>
					<# } #>
				</h4>
			<# if ( data.preview && key !== 1 ) { #>
				<div class="image_preview dede">
					<i class="motors-icons-ico_mag_eye"></i>
					<span data-preview="{{data.preview_url}}{{ data.preview }}.jpg">Preview</span>
				</div>
			<# } #>
			</div>
			<input class="video-repeater-link-input" type="text" name="{{data.label}}_media_file_name[]" value="{{value.media_file_name}}" data-key="{{key}}" {{{ data.attr }}} placeholder="<?php echo esc_attr__( 'Enter' ); ?> {{data.label}} <?php echo esc_attr__( 'name', 'stm_vehicles_listing' ); ?>" />
			<h4 class="mvl-repeater-item-heading">
				{{data.label}} <?php echo esc_html__( 'Link', 'stm_vehicles_listing' ); ?>
				<# if ( key > 0 ) { #>
					<span>{{counter = key + 1}}</span>
				<# } #>
			</h4>
			<input class="video-repeater-link-input" type="text" name="{{data.label}}_media_links[]" value="{{value.media_link}}" data-key="{{key}}" {{{ data.attr }}} placeholder="<?php echo esc_attr__( 'Enter' ); ?> {{data.label}} <?php echo esc_attr__( 'link', 'stm_vehicles_listing' ); ?>" />
			<input type="hidden" class="butterbean-attachment-id" name="{{data.label}}_media_image[]" value="{{ value.media_img }}" data-key="{{key}}" {{{ data.attr }}} />
			<div class="mvl-repeater-item-heading-media-wrapper">
				<h4 class="mvl-repeater-item-heading">
					{{data.label}} <?php echo esc_html__( 'Logo', 'stm_vehicles_listing' ); ?>
					<# if ( key > 0 ) { #>
						<span>{{counter = key + 1}}</span>
					<# } #>
				</h4>
			<# if ( data.preview && key === 0 ) { #>
				<div class="image_preview dede">
					<i class="motors-icons-ico_mag_eye"></i>
					<span data-preview="{{data.second_preview.preview_url}}">Preview</span>
				</div>
			<# } else { #>
				<div class="image_preview dede">
					<i class="motors-icons-ico_mag_eye"></i>
					<span data-preview="{{data.second_preview.preview_url}}">Preview</span>
				</div>
				<# } #>
			</div>
			<# if ( value.media_img_url ) { #>
				<div class="car-manager-small-thumb-image">
					<div class="thumb-image-inner">
						<img src="{{ value.media_img_url }}" alt="Image" class="selected-image" />
					</div>
				</div>
			<# } #>
			<# if ( value.media_img_url ) { #>
				<button type="button" class="button button-secondary butterbean-change-media" data-key="{{ key }}"><?php echo esc_html__( 'Change Image', 'stm_vehicles_listing' ); ?></button>
				<button type="button" class="button button-secondary butterbean-remove-media" data-key="{{ key }}"><?php echo esc_html__( 'Remove Image', 'stm_vehicles_listing' ); ?></button>
			<# } else { #>
				<button type="button" class="button select-image-button" data-key="{{ key }}"><?php echo esc_html__( 'Add Image', 'stm_vehicles_listing' ); ?></button>
			<# } #>
		</div>
	<# } ) #>
</div>

<div>
	<button type="button" class="button button-secondary butterbean-add-field add-media-repeater"><?php echo esc_html__( 'Add', 'stm_vehicles_listing' ); ?> {{data.label}}</button>
<div>

<# if ( data.description ) { #>
	<span class="butterbean-description"><span class="stm-info-icon">i</span>{{{ data.description }}}</span>
<# } #>
