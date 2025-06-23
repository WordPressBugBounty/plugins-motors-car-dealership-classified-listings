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
	'mvl-listing-manager-field-file',
	'mvl-listing-manager-field-file-' . $id,
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
	$file_size_limit    = 20485760;
	$file_size_limit_mb = floor( $file_size_limit / ( 1024 * 1024 ) );
}

if ( ! isset( $accept ) ) {
	$accept = '.pdf';
}

if ( $files_limit > 1 ) {
	$input_name          = $input_name . '[]';
	$container_classes[] = 'multiple';
} else {
	$draggable = false;
}

if ( ! empty( $value ) ) {
	$container_classes[] = 'has-files';

	if ( count( $value ) >= $files_limit ) {
		$container_classes[] = 'limit-exceeded';
	}
}

if ( ! isset( $list_item_image_src ) ) {
	$list_item_image_src = STM_LISTINGS_URL . '/assets/images/pdf-icon.svg';
}

if ( ! isset( $list_item_delete_text ) ) {
	$list_item_delete_text = esc_html__( 'Remove Image', 'stm_vehicles_listing' );
}

?>
<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>" 
	data-accept="<?php echo esc_attr( $accept ); ?>" 
	data-filesize="<?php echo esc_attr( $file_size_limit ); ?>" 
	data-fileslimit="<?php echo esc_attr( $files_limit ); ?>"
	data-inputname="<?php echo esc_attr( $input_name ); ?>"
	data-itemimage="<?php echo esc_url( $list_item_image_src ); ?>"
	data-allowedtype="pdf"
	data-label="<?php echo esc_attr( $label ); ?>"
>
	<?php if ( isset( $label ) ) : ?>
	<div class="mvl-listing-manager-field-title">
		<?php echo esc_html( $label ); ?>
	</div>
	<?php endif; ?>
	<div class="mvl-listing-manager-field-file-list" data-fileslimit="<?php echo esc_attr( $files_limit ); ?>" data-itemimage="<?php echo esc_url( $list_item_image_src ); ?>">
		<?php foreach ( $value as $attachment_id ) : ?>
		<div class="mvl-listing-manager-field-file-list-item">
			<div class="mvl-listing-manager-field-file-list-item-content">
				<img class="mvl-listing-manager-field-file-list-item-image" src="<?php echo esc_url( $list_item_image_src ); ?>" alt="" />
				<span class="mvl-listing-manager-field-file-list-item-name"><?php echo esc_html( basename( wp_get_attachment_url( $attachment_id ) ) ); ?></span>
				<input type="hidden" class="mvl-listing-manager-field-file-list-item-input" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $attachment_id ); ?>" />
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="mvl-listing-manager-field-file-list-actions">
		<div class="mvl-primary-btn mvl-listing-manager-field-file-upload-btn mvl-plus-icon">
			<?php echo esc_html__( 'Add File', 'stm_vehicles_listing' ); ?>
		</div>
		<?php if ( $files_limit <= 1 ) : ?>
		<div class="mvl-primary-btn mvl-listing-manager-field-file-change-btn">
			<?php echo esc_html__( 'Change File', 'stm_vehicles_listing' ); ?>
		</div>
		<?php endif; ?>
		<div class="mvl-delete-btn mvl-listing-manager-field-file-list-clear-btn">
			<i class="motors-icons-mvl-trash"></i>
			<?php echo $files_limit > 1 ? esc_html__( 'Remove Files', 'stm_vehicles_listing' ) : esc_html__( 'Remove File', 'stm_vehicles_listing' ); ?>
		</div>
	</div>
	<input type="file" <?php echo $files_limit > 1 ? 'multiple' : ''; ?> class="mvl-listing-manager-field-file-upload-input" accept="<?php echo esc_attr( $accept ); ?>" />
</div>
