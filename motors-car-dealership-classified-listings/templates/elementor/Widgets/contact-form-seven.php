<?php
$unique_id = 'single_contact_form_' . wp_rand( 1, 99999 );
?>

<div class="stm-elementor-contact-form-seven <?php echo ( ! empty( $form_wide ) && 'yes' === $form_wide ) ? 'stm_listing_car_form' : ''; ?>" id="<?php echo esc_attr( $unique_id ); ?>">
	<div class="icon-title">
		<?php
		if ( ! empty( $icon ) && ! empty( $icon['value'] ) ) :
			if ( 'svg' === $icon['library'] && ! empty( $icon['value']['url'] ) ) :
				?>
				<img src="<?php echo esc_attr( $icon['value']['url'] ); ?>" class="svg-icon" alt="<?php esc_html_e( 'SVG icon', 'stm_vehicles_listing' ); ?>">
				<?php else : ?>
				<i class="stm-elementor-icon <?php echo esc_attr( $icon['value'] ); ?>"></i>
					<?php
			endif;
		endif;

		if ( $title ) :
			?>
			<<?php echo esc_attr( $title_heading ); ?> class="heading-font title">
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( $title_heading ); ?>>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $form_wide ) && 'yes' === $form_wide ) : ?>
		<div class="stm-single-car-contact">
	<?php endif; ?>

	<?php
	if ( ! empty( $form_id ) && 'none' !== $form_id ) {
		$cf7 = get_post( $form_id );

		if ( ! empty( $cf7 ) && is_object( $cf7 ) ) {
			echo( do_shortcode( '[contact-form-7 id="' . $cf7->ID . '" title="' . ( $cf7->post_title ) . '"]' ) );
		}
	}
	?>
	<?php if ( ! empty( $form_wide ) && 'yes' === $form_wide ) : ?>
		</div>
	<?php endif; ?>
</div>

<?php
$user_added_by = get_post_meta( get_the_id(), 'stm_car_user', true );
if ( ! empty( $user_added_by ) ) :
	$user_data = get_userdata( $user_added_by );
	if ( $user_data ) :
		?>
		<script>
			jQuery(document).ready(function(){
				var inputAuthor = '<input type="hidden" value="<?php echo intval( $user_added_by ); ?>" name="stm_changed_recepient" />';
				jQuery('#<?php echo esc_js( $unique_id ); ?> form').append(inputAuthor);

				// replace privacy policy consent label
				if(jQuery('#<?php echo esc_js( $unique_id ); ?> .consent .wpcf7-list-item-label').length > 0) {
					<?php
					$text   = esc_html__( 'I accept the', 'stm_vehicles_listing' );
					$policy = esc_html__( 'privacy policy', 'stm_vehicles_listing' );
					?>
					var consent_link = '<?php echo esc_html( $text ); ?> <a href="<?php echo ( get_privacy_policy_url() ) ? esc_url( get_privacy_policy_url() ) : '#'; ?>" target="_blank"><?php echo esc_html( $policy ); ?></a>';
					jQuery('#<?php echo esc_js( $unique_id ); ?> .consent .wpcf7-list-item-label').html(consent_link);
				}
			});
		</script>
		<?php
	endif;
endif;
