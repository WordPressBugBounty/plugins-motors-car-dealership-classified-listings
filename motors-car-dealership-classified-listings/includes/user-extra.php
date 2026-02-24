<?php
// Adding fields
add_action( 'show_user_profile', 'stm_show_user_extra_fields' );
add_action( 'edit_user_profile', 'stm_show_user_extra_fields' );

if ( ! function_exists( 'stm_show_user_extra_fields' ) ) {
	function stm_show_user_extra_fields( $user ) {
		$is_forms_editor      = apply_filters( 'mvl_is_addon_enabled', false, 'forms_editor' );
		$form_config          = null;
		$sign_up_form_config  = null;
		$form_data            = array();
		$sign_up_form_data    = array();
		$fields               = array();
		$sign_up_fields       = array();
		$saved_values         = array();
		$sign_up_saved_values = array();

		if ( $is_forms_editor ) {
			if ( class_exists( '\MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config' ) ) {
				$form_config         = \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config::instance_of( 'become_dealer' );
				$sign_up_form_config = \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\Config::instance_of( 'sign_up' );

				if ( $form_config instanceof \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig ) {
					$form_data    = $form_config->data();
					$fields       = $form_data['fields'] ?? array();
					$saved_values = $form_config->get_values();
				}

				if ( $sign_up_form_config instanceof \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig ) {
					$sign_up_form_data    = $sign_up_form_config->data();
					$sign_up_fields       = $sign_up_form_data['fields'] ?? array();
					$sign_up_saved_values = $sign_up_form_config->get_values();
				}
			}
		}
		?>

		<h3><?php esc_html_e( 'STM User/Dealer additional fields', 'stm_vehicles_listing' ); ?></h3>

		<table class="form-table">

			<?php if ( $is_forms_editor && $sign_up_form_config instanceof \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig && ! empty( $sign_up_fields ) ) : ?>
				<?php
				$excluded_slugs     = array( 'stm_nickname', 'stm_user_password', 'stm_user_first_name', 'stm_user_last_name', 'stm_user_phone', 'stm_user_mail' );
				$has_sign_up_fields = false;

				foreach ( $sign_up_fields as $field_id => $field_config ) {
					$field_slug = $field_config['slug'] ?? '';
					$field_type = $field_config['type'] ?? '';

					if ( in_array( $field_type, array( 'header', 'button' ), true ) ) {
						continue;
					}
					if ( ! empty( $field_slug ) && in_array( $field_slug, $excluded_slugs, true ) ) {
						continue;
					}
					$has_sign_up_fields = true;
					break;
				}
				if ( $has_sign_up_fields ) :
					foreach ( $sign_up_fields as $field_id => $field_config ) :
						$sign_up_form_config->render_field_admin_table(
							$field_id,
							$field_config,
							$sign_up_saved_values,
							array( 'header', 'button' ),
							array(
								'excluded_slugs' => $excluded_slugs,
								'user_meta'      => true,
								'user_id'        => $user->ID,
							)
						);
					endforeach;
					?>
				<?php endif; ?>
			<?php endif; ?>

			<tr>
				<th><label for="stm_show_email"><?php esc_html_e( 'Email visibility', 'stm_vehicles_listing' ); ?></label></th>
				<td>
					<?php
					$stm_show_email = get_the_author_meta( 'stm_show_email', $user->ID );
					?>
					<label for="stm_show_email">
						<input type="checkbox" name="stm_show_email" id="stm_show_email" <?php checked( ! empty( $stm_show_email ) ); ?> />
						<?php esc_html_e( 'Email address is visible to anyone', 'stm_vehicles_listing' ); ?>
					</label>
				</td>
			</tr>

			<tr>
				<th><label for="stm_phone"><?php esc_html_e( 'Phone', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="tel" name="stm_phone" id="stm_phone"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_phone', $user->ID ) ); ?>"
							class="regular-text"/><br/>
					<span class="description"><?php esc_html_e( 'User phone', 'stm_vehicles_listing' ); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="stm_whatsapp_number"><?php esc_html_e( 'WhatsApp Account', 'stm_vehicles_listing' ); ?></label></th>
				<td>
					<?php
					$stm_whatsapp_number = get_the_author_meta( 'stm_whatsapp_number', $user->ID );
					?>
					<label for="stm_whatsapp_number">
						<input type="checkbox" name="stm_whatsapp_number" id="stm_whatsapp_number" <?php checked( ! empty( $stm_whatsapp_number ) ); ?> />
						<?php esc_html_e( 'User has WhatApp account with this number', 'stm_vehicles_listing' ); ?>
					</label>
				</td>
			</tr>

			<tr>
				<th><label for="stm_user_avatar"><?php esc_html_e( 'User Avatar', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_user_avatar" id="stm_user_avatar"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_user_avatar', $user->ID ) ); ?>"
							class="regular-text"/><br/>
					<input type="text" name="stm_user_avatar_path" id="stm_user_avatar_path"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_user_avatar_path', $user->ID ) ); ?>"
							class="regular-text"/><br/>
					<span class="description"><?php esc_html_e( 'User avatar(stores URL and path to image)', 'stm_vehicles_listing' ); ?></span>
				</td>
			</tr>

			<tr>
				<th colspan="2"><h4><?php esc_html_e( 'STM User/Dealer additional fields (socials)', 'stm_vehicles_listing' ); ?></h4></th>
			</tr>

			<!--Socials-->
			<tr>
				<th><label for="stm_user_facebook"><?php esc_html_e( 'Facebook', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_user_facebook" id="stm_user_facebook"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_user_facebook', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_user_twitter"><?php esc_html_e( 'Twitter', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_user_twitter" id="stm_user_twitter"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_user_twitter', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_user_linkedin"><?php esc_html_e( 'Linked In', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_user_linkedin" id="stm_user_linkedin"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_user_linkedin', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_user_youtube"><?php esc_html_e( 'Youtube', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_user_youtube" id="stm_user_youtube"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_user_youtube', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_user_favourites"><?php esc_html_e( 'User favorite car ids', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_user_favourites" id="stm_user_favourites"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_user_favourites', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>


			<!--Dealer-->
			<tr>
				<th><h2><?php esc_html_e( 'Dealer Settings', 'stm_vehicles_listing' ); ?></h2></th>
				<td><h3><?php esc_html_e( 'This settings will only be filled by dealers, and shown only on dealer page.', 'stm_vehicles_listing' ); ?></h3></td>
			</tr>

			<?php if ( $is_forms_editor && $form_config instanceof \MotorsVehiclesListing\Pro\Addons\FormsEditor\Config\FormConfig && ! empty( $fields ) ) : ?>
				<?php
				$excluded_slugs    = array( 'stm_company_name', 'stm_website_url', 'stm_licence', 'stm_dealer_logo', 'stm_dealer_image', 'stm_location', 'stm_sales_hours', 'stm_seller_notes', 'stm_payment_status', 'stm_lost_password_hash', 'stm_notes', 'company_logo' );
				$has_dealer_fields = false;

				foreach ( $fields as $field_id => $field_config ) {
					$field_slug = $field_config['slug'] ?? '';
					$field_type = $field_config['type'] ?? '';

					if ( in_array( $field_type, array( 'header', 'button' ), true ) ) {
						continue;
					}
					if ( ! empty( $field_slug ) && in_array( $field_slug, $excluded_slugs, true ) ) {
						continue;
					}
					$has_dealer_fields = true;
					break;
				}

				if ( $has_dealer_fields ) :
					foreach ( $fields as $field_id => $field_config ) :
						$form_config->render_field_admin_table(
							$field_id,
							$field_config,
							$saved_values,
							array( 'header', 'button' ),
							array(
								'user_meta'      => true,
								'user_id'        => $user->ID,
								'excluded_slugs' => $excluded_slugs,
							)
						);
					endforeach;
					?>
				<?php endif; ?>
			<?php endif; ?>

			<tr>
				<th><label for="stm_message_to_user"><?php esc_html_e( 'Message to pending user', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_message_to_user" id="stm_message_to_user" value="<?php echo esc_attr( get_the_author_meta( 'stm_message_to_user', $user->ID ) ); ?>" class="regular-text"/>
					<div>
					<span class="description"><?php esc_html_e( 'In case a user has entered incorrect details in Dealer submission, you can reject the request and add a notice.', 'stm_vehicles_listing' ); ?></span>
					</div>
				</td>
			</tr>

			<tr>
				<th><label for="stm_company_name"><?php esc_html_e( 'Company name', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_company_name" id="stm_company_name"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_company_name', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_website_url"><?php esc_html_e( 'Website URL', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_website_url" id="stm_website_url"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_website_url', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_company_license"><?php esc_html_e( 'License', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_company_license" id="stm_company_license"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_company_license', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_dealer_logo"><?php esc_html_e( 'Dealer Logo', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_dealer_logo" id="stm_dealer_logo"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_dealer_logo', $user->ID ) ); ?>"
							class="regular-text"/><br/>
					<input type="text" name="stm_dealer_logo_path" id="stm_dealer_logo_path"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_dealer_logo_path', $user->ID ) ); ?>"
							class="regular-text"/><br/>
					<span class="description"><?php esc_html_e( 'Dealer logo(stores URL and path to image)', 'stm_vehicles_listing' ); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="stm_dealer_image"><?php esc_html_e( 'Dealer Image', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_dealer_image" id="stm_dealer_image"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_dealer_image', $user->ID ) ); ?>"
							class="regular-text"/><br/>
					<input type="text" name="stm_dealer_image_path" id="stm_dealer_image_path"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_dealer_image_path', $user->ID ) ); ?>"
							class="regular-text"/><br/>
					<span class="description"><?php esc_html_e( 'Dealer image(stores URL and path to image)', 'stm_vehicles_listing' ); ?></span>
				</td>
			</tr>

			<tr>
				<th><label for="stm_dealer_location"><?php esc_html_e( 'Dealer Location', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_dealer_location" id="stm_dealer_location"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_dealer_location', $user->ID ) ); ?>"
							class="regular-text"/>
					<div class="description"><?php esc_html_e( 'Dealer location address', 'stm_vehicles_listing' ); ?></div>
					<input type="text" name="stm_dealer_location_lat" id="stm_dealer_location_lat"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_dealer_location_lat', $user->ID ) ); ?>"
							class="regular-text"/>
					<div class="description"><?php esc_html_e( 'Dealer location latitude', 'stm_vehicles_listing' ); ?></div>
					<input type="text" name="stm_dealer_location_lng" id="stm_dealer_location_lng"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_dealer_location_lng', $user->ID ) ); ?>"
							class="regular-text"/>
					<div class="description"><?php esc_html_e( 'Dealer location longitude', 'stm_vehicles_listing' ); ?></div>
				</td>
			</tr>

			<tr>
				<th><label for="stm_sales_hours"><?php esc_html_e( 'Sales Hours', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_sales_hours" id="stm_sales_hours" value="<?php echo esc_attr( get_the_author_meta( 'stm_sales_hours', $user->ID ) ); ?>" class="regular-text"/>
				</td>
			</tr>

			<tr>
				<th><label for="stm_seller_notes"><?php esc_html_e( 'Seller Notes', 'stm_vehicles_listing' ); ?></label></th>
				<td>
					<textarea name="stm_seller_notes" id="stm_seller_notes" rows="5" cols="30"><?php echo esc_textarea( get_the_author_meta( 'stm_seller_notes', $user->ID ) ); ?></textarea>
				</td>
			</tr>

			<?php
			$is_editing_other_user = ( (int) get_current_user_id() !== (int) $user->ID );
			if ( $is_editing_other_user && current_user_can( 'edit_users' ) ) :
				?>
			<tr>
				<th><label for="stm_payment_status"><?php esc_html_e( 'Payment status', 'stm_vehicles_listing' ); ?></label></th>

				<td>
					<input type="text" name="stm_payment_status" id="stm_payment_status"
							value="<?php echo esc_attr( get_the_author_meta( 'stm_payment_status', $user->ID ) ); ?>"
							class="regular-text"/>
				</td>
			</tr>

			<tr>
				<td>
					<input type="hidden" name="stm_lost_password_hash" id="stm_lost_password_hash" value="<?php echo esc_attr( get_the_author_meta( 'stm_lost_password_hash', $user->ID ) ); ?>" class="regular-text"/>
				</td>
			</tr>
			<?php endif; ?>

		</table>
		<?php
	}
}

// Updating fields
add_action( 'personal_options_update', 'stm_save_user_extra_fields' );
add_action( 'edit_user_profile_update', 'stm_save_user_extra_fields' );

if ( ! function_exists( 'stm_save_user_extra_fields' ) ) {
	function stm_save_user_extra_fields( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		// Check nonce for security
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
			return false;
		}

		// Sanitize phone number
		$phone = '';
		if ( isset( $_POST['stm_phone'] ) ) {
			$phone = sanitize_text_field( preg_replace( '/[^0-9+]/', '', wp_unslash( $_POST['stm_phone'] ) ) );
		}
		update_user_meta( $user_id, 'stm_phone', $phone );

		// Handle WhatsApp checkbox
		$whatsapp_number = isset( $_POST['stm_whatsapp_number'] ) ? '1' : '';
		// Remove 'has whatsapp account' if no number is provided
		if ( empty( $phone ) ) {
			$whatsapp_number = '';
		}
		update_user_meta( $user_id, 'stm_whatsapp_number', $whatsapp_number );

		// Socials
		$stm_show_email = isset( $_POST['stm_show_email'] ) ? '1' : '';
		update_user_meta( $user_id, 'stm_show_email', $stm_show_email );

		// Avatar fields
		if ( isset( $_POST['stm_user_avatar'] ) ) {
			update_user_meta( $user_id, 'stm_user_avatar', esc_url_raw( wp_unslash( $_POST['stm_user_avatar'] ) ) );
		}
		if ( isset( $_POST['stm_user_avatar_path'] ) ) {
			update_user_meta( $user_id, 'stm_user_avatar_path', sanitize_text_field( wp_unslash( $_POST['stm_user_avatar_path'] ) ) );
		}

		// Social media URLs
		if ( isset( $_POST['stm_user_facebook'] ) ) {
			update_user_meta( $user_id, 'stm_user_facebook', esc_url_raw( wp_unslash( $_POST['stm_user_facebook'] ) ) );
		}
		if ( isset( $_POST['stm_user_twitter'] ) ) {
			update_user_meta( $user_id, 'stm_user_twitter', esc_url_raw( wp_unslash( $_POST['stm_user_twitter'] ) ) );
		}
		if ( isset( $_POST['stm_user_linkedin'] ) ) {
			update_user_meta( $user_id, 'stm_user_linkedin', esc_url_raw( wp_unslash( $_POST['stm_user_linkedin'] ) ) );
		}
		if ( isset( $_POST['stm_user_youtube'] ) ) {
			update_user_meta( $user_id, 'stm_user_youtube', esc_url_raw( wp_unslash( $_POST['stm_user_youtube'] ) ) );
		}

		// Favourites
		if ( isset( $_POST['stm_user_favourites'] ) ) {
			update_user_meta( $user_id, 'stm_user_favourites', sanitize_text_field( wp_unslash( $_POST['stm_user_favourites'] ) ) );
		}

		// Company fields
		if ( isset( $_POST['stm_company_name'] ) ) {
			update_user_meta( $user_id, 'stm_company_name', sanitize_text_field( wp_unslash( $_POST['stm_company_name'] ) ) );
		}
		if ( isset( $_POST['stm_website_url'] ) ) {
			update_user_meta( $user_id, 'stm_website_url', esc_url_raw( wp_unslash( $_POST['stm_website_url'] ) ) );
		}
		if ( isset( $_POST['stm_company_license'] ) ) {
			update_user_meta( $user_id, 'stm_company_license', sanitize_text_field( wp_unslash( $_POST['stm_company_license'] ) ) );
		}
		if ( isset( $_POST['stm_message_to_user'] ) ) {
			update_user_meta( $user_id, 'stm_message_to_user', sanitize_text_field( wp_unslash( $_POST['stm_message_to_user'] ) ) );
		}

		// Dealer images
		if ( isset( $_POST['stm_dealer_logo'] ) ) {
			update_user_meta( $user_id, 'stm_dealer_logo', esc_url_raw( wp_unslash( $_POST['stm_dealer_logo'] ) ) );
		}
		if ( isset( $_POST['stm_dealer_logo_path'] ) ) {
			update_user_meta( $user_id, 'stm_dealer_logo_path', sanitize_text_field( wp_unslash( $_POST['stm_dealer_logo_path'] ) ) );
		}
		if ( isset( $_POST['stm_dealer_image'] ) ) {
			update_user_meta( $user_id, 'stm_dealer_image', esc_url_raw( wp_unslash( $_POST['stm_dealer_image'] ) ) );
		}
		if ( isset( $_POST['stm_dealer_image_path'] ) ) {
			update_user_meta( $user_id, 'stm_dealer_image_path', sanitize_text_field( wp_unslash( $_POST['stm_dealer_image_path'] ) ) );
		}

		// Location fields
		if ( isset( $_POST['stm_dealer_location'] ) ) {
			update_user_meta( $user_id, 'stm_dealer_location', sanitize_text_field( wp_unslash( $_POST['stm_dealer_location'] ) ) );
		}
		if ( isset( $_POST['stm_dealer_location_lat'] ) ) {
			update_user_meta( $user_id, 'stm_dealer_location_lat', sanitize_text_field( wp_unslash( $_POST['stm_dealer_location_lat'] ) ) );
		}
		if ( isset( $_POST['stm_dealer_location_lng'] ) ) {
			update_user_meta( $user_id, 'stm_dealer_location_lng', sanitize_text_field( wp_unslash( $_POST['stm_dealer_location_lng'] ) ) );
		}

		// Sales hours and notes
		if ( isset( $_POST['stm_sales_hours'] ) ) {
			update_user_meta( $user_id, 'stm_sales_hours', sanitize_text_field( wp_unslash( $_POST['stm_sales_hours'] ) ) );
		}
		if ( isset( $_POST['stm_seller_notes'] ) ) {
			update_user_meta( $user_id, 'stm_seller_notes', sanitize_textarea_field( wp_unslash( $_POST['stm_seller_notes'] ) ) );
		}

		$can_edit_sensitive = current_user_can( 'edit_users' ) && ( (int) get_current_user_id() !== (int) $user_id );
		if ( $can_edit_sensitive ) {
			if ( isset( $_POST['stm_payment_status'] ) ) {
				update_user_meta( $user_id, 'stm_payment_status', sanitize_text_field( wp_unslash( $_POST['stm_payment_status'] ) ) );
			}
			if ( isset( $_POST['stm_lost_password_hash'] ) ) {
				update_user_meta( $user_id, 'stm_lost_password_hash', wp_unslash( $_POST['stm_lost_password_hash'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}
		}

		// Save form editor fields if forms editor is enabled
		$is_forms_editor = apply_filters( 'mvl_is_addon_enabled', false, 'forms_editor' );
		if ( $is_forms_editor && class_exists( '\MotorsVehiclesListing\Pro\Addons\FormsEditor\Helpers\FormFieldsHandler' ) ) {
			// Save become_dealer form fields
			\MotorsVehiclesListing\Pro\Addons\FormsEditor\Helpers\FormFieldsHandler::save_form_fields_to_user_meta(
				$user_id,
				'become_dealer',
				array( 'preserve_existing_files' => true )
			);

			// Save sign_up form fields (skip password and nickname)
			\MotorsVehiclesListing\Pro\Addons\FormsEditor\Helpers\FormFieldsHandler::save_form_fields_to_user_meta(
				$user_id,
				'sign_up',
				array(
					'skip_fields'             => array( 'stm_nickname', 'stm_user_password' ),
					'preserve_existing_files' => true,
				)
			);
		}
	}
}

if ( ! function_exists( 'stm_stop_access_profile' ) ) {
	add_action( 'admin_menu', 'stm_stop_access_profile' );
	function stm_stop_access_profile() {
		remove_menu_page( 'profile.php' );
		remove_submenu_page( 'users.php', 'profile.php' );
	}
}
