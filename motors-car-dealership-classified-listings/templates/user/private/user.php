<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user        = wp_get_current_user();
$user_id     = $user->ID;
$user_fields = apply_filters( 'stm_get_user_custom_fields', $user_id );
$path        = 'user/private/';

$tpl = apply_filters( 'stm_account_current_page', '' );
?>

<div class="stm-user-private">
	<div class="container">
		<div class="row">

			<div class="col-md-3 col-sm-12 stm-sticky-user-sidebar">
				<?php
				do_action(
					'stm_listings_load_template',
					'user/private/sidebar',
					array(
						'user'        => $user,
						'user_fields' => $user_fields,
					)
				);
				?>
			</div>

			<div class="col-md-9 col-sm-12">
				<div class="stm-user-private-main">
					<?php
					if ( isset( $_GET['page'] ) ) {
						if ( apply_filters( 'get_saved_searches_page', sanitize_text_field( $_GET['page'] ) ) === 'saved-searches' ) {
							do_action( 'load_saved_searches_page' );
						} else {
							do_action( 'stm_listings_load_template', $path . $_GET['page'], array( 'user_id' => $user_id ) );
						}
					} else {
						do_action( 'stm_listings_load_template', $path . $tpl, array( 'user_id' => $user_id ) );
					}
					?>
				</div>
			</div>

		</div>
	</div>
</div>
