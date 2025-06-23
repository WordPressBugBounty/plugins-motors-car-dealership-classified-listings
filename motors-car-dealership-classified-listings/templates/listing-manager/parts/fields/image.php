<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! isset( $placeholder ) ) {
	if ( isset( $label ) ) {
		$placeholder = $label;
	}
}

$container_classes = array(
	'mvl-listing-manager-field',
	'mvl-listing-manager-field-image',
	'mvl-listing-manager-field-image-' . $id,
);

if ( ! isset( $value ) || ! $value ) {
	$value = array();
} elseif ( ! is_array( $value ) ) {
	$value = array( $value );
}

if ( ! isset( $files_limit ) ) {
	$files_limit = 1;
}

if ( ! isset( $file_size_limit ) ) {
	$file_size_limit    = 10 * 1024 * 1024;
	$file_size_limit_mb = floor( $file_size_limit / ( 1024 * 1024 ) );
}

if ( ! isset( $draggable ) ) {
	$draggable = false;
}

if ( $files_limit > 1 ) {
	$input_name          = $input_name . '[]';
	$container_classes[] = 'multiple';
} else {
	$draggable = false;
}

if ( ! isset( $dropzone ) ) {
	$dropzone = false;
}

if ( ! empty( $value ) ) {
	$container_classes[] = 'has-files';

	if ( count( $value ) >= $files_limit ) {
		$container_classes[] = 'limit-exceeded';
	}
}

if ( ! isset( $accept ) ) {
	$accept = '.jpg,.jpeg,.png,.webp';
}

if ( ! $dropzone ) {
	$container_classes[] = 'no-dropzone';
}
if ( ! isset( $add_button_class ) ) {
	$add_button_class = 'mvl-primary-btn';
}

$label = isset( $label ) ? $label : '';
?>
<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>" 
	data-accept="<?php echo esc_attr( $accept ); ?>" 
	data-filesize="<?php echo esc_attr( $file_size_limit ); ?>" 
	data-fileslimit="<?php echo esc_attr( $files_limit ); ?>"
	data-filesizemb="<?php echo esc_attr( $file_size_limit_mb ); ?>"
	data-inputname="<?php echo esc_attr( $input_name ); ?>"
	data-multiple="<?php echo esc_attr( $files_limit > 1 ? true : false ); ?>"
	data-allowedtype="image"
	data-label="<?php echo esc_attr( $label ); ?>"
	>
	<?php if ( isset( $label ) ) : ?>
	<div class="mvl-listing-manager-field-title">
		<?php echo esc_html( $label ); ?>
	</div>
	<?php endif; ?>
	<?php if ( $dropzone ) : ?>
	<div class="mvl-listing-manager-field-image-dropzone">
		<i class="motors-icons-mvl_file_select"></i>
		<span class="mvl-listing-manager-field-image-upload-text">
			<?php
			if ( apply_filters( 'mvl_listing_manager_is_admin', false ) ) {
				// translators: %1$s - maximum file size in megabytes
				printf(
					esc_html__( 'Drag an image here or choose from library (JPG, PNG) %1$sMB', 'stm_vehicles_listing' ),
					esc_html( $file_size_limit_mb )
				);
			} else {
				// translators: %1$s - maximum file size in megabytes
				printf(
					esc_html__( 'Drag an image here or choose from library (JPG, PNG) max %1$sMB', 'stm_vehicles_listing' ),
					esc_html( $file_size_limit_mb )
				);
			}
			?>
		</span>
		<div class="mvl-primary-btn mvl-listing-manager-field-image-upload-btn mvl-plus-icon">
			<?php echo esc_html__( 'Select Image', 'stm_vehicles_listing' ); ?>
		</div>
		<input type="file" <?php echo $files_limit > 1 ? 'multiple' : ''; ?> class="mvl-listing-manager-field-image-upload-input" accept="<?php echo esc_attr( $accept ); ?>" />
	</div>
	<?php endif; ?>
	<div class="mvl-listing-manager-field-image-list<?php echo $draggable ? ' draggable' : ''; ?>" data-fileslimit="<?php echo esc_attr( $files_limit ); ?>">
		<?php foreach ( $value as $image ) : ?>
		<div class="mvl-listing-manager-field-image-list-item<?php echo $draggable ? ' draggable-item' : ''; ?>">
			<div class="mvl-listing-manager-field-image-list-item-content">
				<img class="mvl-listing-manager-field-image-list-item-image " src="<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>" />
				<span class="mvl-listing-manager-field-image-list-item-delete"></span>
				<input type="hidden" class="mvl-listing-manager-field-image-list-item-input" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $image ); ?>" />
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="mvl-listing-manager-field-image-list-actions">
		<div class="mvl-listing-manager-field-image-upload-btn mvl-plus-icon <?php echo esc_attr( $add_button_class ); ?>">
			<?php echo esc_html__( 'Add Image', 'stm_vehicles_listing' ); ?>
		</div>
		<?php if ( $files_limit <= 1 ) : ?>
		<div class="mvl-primary-btn mvl-listing-manager-field-image-change-btn">
			<?php echo esc_html__( 'Change Image', 'stm_vehicles_listing' ); ?>
		</div>
		<?php endif; ?>
		<div class="mvl-delete-btn mvl-listing-manager-field-image-list-clear-btn">
			<i class="motors-icons-mvl-trash"></i>
			<?php echo $files_limit > 1 ? esc_html__( 'Remove Images', 'stm_vehicles_listing' ) : esc_html__( 'Remove Image', 'stm_vehicles_listing' ); ?>
		</div>
	</div>
	<?php if ( isset( $description ) && $description ) : ?>
	<div class="mvl-listing-manager-field-image-description">
		<i class="motors-icons-mvl-info"></i>
		<?php
		echo esc_html( $description );
		?>
	</div>
	<?php endif; ?>
	<?php if ( ! $dropzone ) : ?>
		<input type="file" <?php echo $files_limit > 1 ? 'multiple' : ''; ?> class="mvl-listing-manager-field-image-upload-input" accept="<?php echo esc_attr( $accept ); ?>" />
	<?php endif; ?>
</div>
