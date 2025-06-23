<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $placeholder ) ) {
	if ( isset( $label ) ) {
		$placeholder = $label;
	}
}

$is_tinymce = isset( $tinymce ) && $tinymce;

?>
<label for="<?php echo esc_attr( $id ); ?>" class="mvl-listing-manager-field mvl-listing-manager-field-textarea<?php echo $is_tinymce ? ' mvl-listing-manager-field-tinymce' : ''; ?>" data-label="<?php echo esc_attr( $label ); ?>">
	<div class="mvl-listing-manager-field-title"><?php echo esc_html( $label ); ?></div>
	<?php if ( $is_tinymce ) : ?>
		<div class="mvl-listing-manager-field-tinymce-wrapper">
			<textarea 
				id="<?php echo esc_attr( $id ); ?>"
				class="mvl-listing-manager-field-textarea mvl-textarea-field" 
				name="<?php echo esc_attr( $input_name ); ?>" 
				placeholder="<?php echo isset( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
				<?php echo isset( $required ) && $required ? 'required' : ''; ?>
			><?php echo isset( $value ) ? wp_kses_post( $value ) : ''; ?></textarea>
		</div>
	<?php else : ?>
		<textarea 
			id="<?php echo esc_attr( $id ); ?>"
			class="mvl-listing-manager-field-textarea mvl-textarea-field" 
			name="<?php echo esc_attr( $input_name ); ?>" 
			placeholder="<?php echo isset( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
			<?php echo isset( $required ) && $required ? 'required' : ''; ?>
		><?php echo isset( $value ) ? esc_html( $value ) : ''; ?></textarea>
	<?php endif; ?>
</label>
