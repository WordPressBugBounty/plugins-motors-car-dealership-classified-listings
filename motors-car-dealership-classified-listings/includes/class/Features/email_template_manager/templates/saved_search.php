<?php
$template_val = ( '' !== get_option( 'saved_search_template', '' ) ) ? stripslashes( get_option( 'saved_search_template', '' ) ) : '
<p>' . esc_html__( 'Hello', 'stm_vehicles_listing' ) . ', [user_name]!</p>
<p>' . esc_html__( 'We have found new listings for your saved search:', 'stm_vehicles_listing' ) . '</p>
<p>[search_data_str]</p>
<p>
    ' . esc_html__( 'You can view them by following this link:', 'stm_vehicles_listing' ) . '
    <a href="[search_url]">' . esc_html__( 'View new listings', 'stm_vehicles_listing' ) . '</a>
</p>';

$subject_val = ( '' !== get_option( 'new_listings_subject', '' ) ) ? get_option( 'new_listings_subject', '' ) : esc_html__( 'New Listings Notification', 'stm_vehicles_listing' );

?>

<div class="etm-single-form">
	<h3><?php esc_html_e( 'New Listings Email Template', 'stm_vehicles_listing' ); ?></h3>

	<label for="new_listings_subject">
		<?php esc_html_e( 'Email Subject', 'stm_vehicles_listing' ); ?>
	</label>
	<input type="text" id="new_listings_subject" name="new_listings_subject" value="<?php echo esc_attr( $subject_val ); ?>" class="full_width"/>

	<div class="lr-wrap">
		<div class="left">
			<h4><?php esc_html_e( 'Edit Template', 'stm_vehicles_listing' ); ?></h4>
			<?php
			$editor_args = array(
				'textarea_rows' => apply_filters( 'etm_nl_sce_row', 10 ),
				'wpautop'       => true,
				'media_buttons' => apply_filters( 'etm_nl_sce_media_buttons', false ),
				'tinymce'       => apply_filters( 'etm_nl_sce_tinymce', true ),
			);

			wp_editor( $template_val, 'saved_search_template', $editor_args );
			?>
		</div>
		<div class="right">
			<h4><?php esc_html_e( 'Available Shortcodes', 'stm_vehicles_listing' ); ?></h4>
			<ul>
				<li><input type="text" value="[user_name]" class="auto_select" /></li>
				<li><input type="text" value="[search_data_str]" class="auto_select" /></li>
				<li><input type="text" value="[search_url]" class="auto_select" /></li>
			</ul>
		</div>
	</div>
</div>
