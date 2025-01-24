<?php
	wp_enqueue_script( 'bootstrap-bundle' );

	/*Get all stored options*/
	$_per_page     = 10;
	$options       = stm_listings_get_my_options_list();
	$options_count = count( $options );
	$_list         = array_chunk( $options, $_per_page, true );
	$options       = reset( $_list );
	$list_empty    = empty( $options );
?>
<div class="stm_vehicles_listing_categories stm_custom_fields">
	<div class="stm_custom_fields__image-preview">
		<div class="overlay"></div>
	</div>

	<div class="stm_custom_fields__top">
		<div class="stm_custom_fields__title">
			<?php echo esc_html( get_admin_page_title() ); ?>
		</div>
		<div class="stm_custom_fields__add">
			<button type="button" data-action="open" class="stm-admin-button">
				<?php esc_html_e( 'Add new field', 'stm_vehicles_listing' ); ?>
			</button>
		</div>
	</div>

	<div class="stm_import_export"><div class="export_settings"></div></div>

	<div class="stm_custom_fields__workspace">
		<div class="stm_custom_fields__list">
			<div class="stm_custom_fields__search">
				<h3><?php esc_html_e( 'Fields Overview', 'stm_vehicles_listing' ); ?></h3>
				<form method="POST" class="stm_custom_fields__search--form">
					<?php echo wp_kses_post( wp_nonce_field( 'stm_admin_security_nonce', 'admin_security' ) ); ?>
					<div class="stm_custom_fields__search--wrapper">
						<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/admin/search.svg' ); ?>" alt="<?php esc_attr_e( 'Search icon', 'stm_vehicles_listing' ); ?>">
						<input
							type="text"
							name="search"
							aria-label="<?php esc_attr_e( 'Search field', 'stm_vehicles_listing' ); ?>"
							placeholder="<?php esc_attr_e( 'Search...', 'stm_vehicles_listing' ); ?>"
							class="stm_custom_fields__search--input">
					</div>
				</form>
			</div>
			<div class="stm_custom_fields__content <?php echo $list_empty ? 'hide' : ''; ?>">
				<table class="wp-list-table listing_categories listing_categories_edit stm_custom_fields__table">
					<thead>
						<tr>
							<th class="stm_custom_fields__table--icon"></th>
							<th class="stm_custom_fields__table--title"><?php esc_html_e( 'Title', 'stm_vehicles_listing' ); ?></th>
							<th class="stm_custom_fields__table--slug"><?php esc_html_e( 'Slug', 'stm_vehicles_listing' ); ?></th>
							<th class="stm_custom_fields__table--type"><?php esc_html_e( 'Field Type', 'stm_vehicles_listing' ); ?></th>
							<th class="stm_custom_fields__table--edit"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $options as $option_key => $option ) {
							require STM_LISTINGS_PATH . '/includes/admin/categories/table-row.php';
						}
						?>
					</tbody>
				</table>
				<div class="stm_custom_fields__bottom">
					<ul class="stm_custom_fields__pagination <?php echo $options_count <= $_per_page ? 'hide' : ''; ?>">
						<li class="stm_custom_fields__button stm_custom_fields__button--prev disabled">
							<i class="stm-admin-icon-prev"></i>
						</li>
						<li class="stm_custom_fields__button stm_custom_fields__button--current">
							1
						</li>
						<li class=" stm_custom_fields__button stm_custom_fields__button--next">
							<i class="stm-admin-icon-next"></i>
						</li>
					</ul>
					<select name="per_page" aria-label="" class="stm_custom_fields__select stm_custom_fields__pagination--select <?php echo $options_count <= $_per_page ? 'hide' : ''; ?>">
						<option value="10"><?php esc_html_e( '10 per page', 'stm_vehicles_listing' ); ?></option>
						<option value="20"><?php esc_html_e( '20 per page', 'stm_vehicles_listing' ); ?></option>
						<option value="30"><?php esc_html_e( '30 per page', 'stm_vehicles_listing' ); ?></option>
						<option value="all"><?php esc_html_e( 'All', 'stm_vehicles_listing' ); ?></option>
					</select>
				</div>
			</div>
			<div class="stm_custom_fields__empty <?php echo ! $list_empty ? 'hide' : ''; ?>">
				<div class="stm_custom_fields__empty--image">
					<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/admin/list-empty.jpg' ); ?>" alt="<?php esc_attr_e( 'List empty', 'stm_vehicles_listing' ); ?>">
				</div>
				<div class="stm_custom_fields__empty--text">
					<?php esc_html_e( 'Your custom fields will be displayed here after you add them.', 'stm_vehicles_listing' ); ?>
				</div>
			</div>
		</div>
		<div class="stm_custom_fields__form--wrapper">
			<div class="stm_custom_fields__form--empty">
				<div class="stm_custom_fields__form--description">
					<?php esc_html_e( 'Select a field to view, edit details, or create a new one.', 'stm_vehicles_listing' ); ?>
				</div>
				<button type="button" data-action="open" class="stm-admin-button stm-admin-button-primary">
					<?php esc_html_e( 'Add new field', 'stm_vehicles_listing' ); ?>
				</button>
			</div>
			<?php
			$form_type = 'add';
			require STM_LISTINGS_PATH . '/includes/admin/categories/form.php';

			$form_type = 'edit';
			require STM_LISTINGS_PATH . '/includes/admin/categories/form.php';
			?>
		</div>
	</div>
</div>

<div class="stm-admin-modal-overlay"></div>
<div class="stm-admin-modal" id="stm-admin-modal-delete-item">
	<div class="stm-admin-modal__dialog stm-admin-modal__centered">
		<div class="stm-admin-modal__content">
			<div class="stm-admin-modal__header">
				<h3><?php esc_html_e( 'Delete Custom Field?', 'stm_vehicles_listing' ); ?></h3>
			</div>
			<div class="stm-admin-modal__body">
				<p>
					<?php esc_html_e( 'Are you sure you want to delete this custom field? This action cannot be undone.', 'stm_vehicles_listing' ); ?>
				</p>
			</div>
			<div class="stm-admin-modal__footer">
				<div class="stm-admin-modal__actions">
					<button class="stm-admin-button stm-modal-button__cancel" data-modal-action="close">
						<?php esc_html_e( 'Cancel', 'stm_vehicles_listing' ); ?>
					</button>
					<button type="button" class="stm-admin-button stm-admin-button-important-filled" data-field-action="delete">
						<?php esc_html_e( 'Delete', 'stm_vehicles_listing' ); ?>
						<i class="lnr lnr-sync"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="stm-admin-modal" id="stm-admin-modal-confirm-edit">
	<div class="stm-admin-modal__dialog stm-admin-modal__centered">
		<div class="stm-admin-modal__content">
			<div class="stm-admin-modal__header">
				<h3><?php esc_html_e( 'Unsaved Changes', 'stm_vehicles_listing' ); ?></h3>
			</div>
			<div class="stm-admin-modal__body">
				<p>
					<?php esc_html_e( 'Are you sure you want to leave? Any unsaved changes will be lost.', 'stm_vehicles_listing' ); ?>
				</p>
			</div>
			<div class="stm-admin-modal__footer">
				<div class="stm-admin-modal__actions">
					<button class="stm-admin-button stm-modal-button__cancel" data-modal-action="close">
						<?php esc_html_e( 'Go Back', 'stm_vehicles_listing' ); ?>
					</button>
					<button type="button" class="stm-admin-button stm-admin-button-important-filled" data-field-action="confirm">
						<?php esc_html_e( 'Leave Without Saving', 'stm_vehicles_listing' ); ?>
						<i class="lnr lnr-sync"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
