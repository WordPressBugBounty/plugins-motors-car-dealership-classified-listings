<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$_categories = apply_filters( 'mvl_listings_attributes', array() );

if ( stm_is_multilisting() && isset( $_GET['post_type'] ) && apply_filters( 'stm_listings_post_type', 'listings' ) !== $_GET['post_type'] ) { //phpcs:ignore
	$_categories = apply_filters( 'stm_get_listings_filter', array(), $_GET['post_type'], array(), false ); //phpcs:ignore
}

if ( ! empty( $_categories ) ) {
	foreach ( $_categories as $_category ) {
		/** Add Custom Field To Form */
		add_action( $_category['slug'] . '_add_form_fields', 'stm_taxonomy_listing_add_field', 10 );
		add_action( $_category['slug'] . '_edit_form_fields', 'stm_taxonomy_listing_edit_field', 10, 2 );
		/** Save Custom Field Of Form */
		add_action( 'wp_update_term_data', 'stm_taxonomy_listing_image_save', 10, 3 );
		add_action( 'create_term', 'stm_taxonomy_listing_image_save', 10, 3 );
	}
}

/*Add field*/
if ( ! function_exists( 'stm_taxonomy_listing_add_field' ) ) {
	function stm_taxonomy_listing_add_field() {
		wp_enqueue_style( 'stm_admin_categories_option', STM_LISTINGS_URL . '/assets/css/admin/listing-categories.css', array(), STM_LISTINGS_V );

		$default_image = STM_LISTINGS_URL . '/assets/images/default_170x50.gif';
		?>
		<div class="form-field">
			<label for="stm_taxonomy_listing_image">
				<?php esc_html_e( 'Category Image', 'stm_vehicles_listing' ); ?>
			</label>
			<div class="stm-choose-listing-image">
				<input
					type="hidden"
					name="stm_taxonomy_listing_image"
					id="stm_taxonomy_listing_image"
					value=""
					size="40"
					aria-required="true"/>

				<img class="stm_taxonomy_listing_image_chosen" style="display: none;" src="<?php echo esc_url( $default_image ); ?>" alt="<?php esc_attr_e( 'Category Image', 'stm_vehicles_listing' ); ?>"/>

				<input type="button" class="button-secondary button-image-add" value="<?php esc_attr_e( 'Choose image', 'stm_vehicles_listing' ); ?>"/>
				<input type="button" class="button-secondary button-image-delete" style="display: none;" value="<?php esc_attr_e( 'Remove image', 'stm_vehicles_listing' ); ?>"/>
			</div>
			<script type="text/javascript">
				jQuery(function ($) {
					let parent = '.stm-choose-listing-image';

					$( '.button-image-add', parent ).click(function () {
						let $parent = $( this ).parents( parent );

						let custom_uploader = wp.media({
							title: "Select image",
							button: {
								text: "Attach"
							},
							multiple: false
						}).on("select", function () {
							let attachment = custom_uploader.state().get( "selection" ).first().toJSON();
							$( '#stm_taxonomy_listing_image', $parent ).val( attachment.id );
							$( '.stm_taxonomy_listing_image_chosen', $parent ).attr( 'src', attachment.url ).show();
							$( '.button-image-delete', $parent ).show();
						}).open();
					});

					$( '.button-image-delete', parent ).click( function ( e ) {
						e.preventDefault();

						let $parent = $( this ).parents( parent );

						$( '#stm_taxonomy_listing_image', $parent ).val( '' );
						$( '.stm_taxonomy_listing_image_chosen', $parent ).attr( 'src', '' ).hide();
						$( '.button-image-delete', $parent ).hide();
					} );
				});
			</script>
		</div>
		<?php
	}
}

/*Edit field*/
if ( ! function_exists( 'stm_taxonomy_listing_edit_field' ) ) {
	function stm_taxonomy_listing_edit_field( $tag, $taxonomy ) {
		wp_enqueue_style( 'stm_admin_categories_option', STM_LISTINGS_URL . '/assets/css/admin/listing-categories.css', array(), STM_LISTINGS_V );

		$current_image = get_term_meta( $tag->term_id, 'stm_image', true );
		$default_image = STM_LISTINGS_URL . '/assets/images/default_170x50.gif';
		$_selected     = false;

		if ( ! empty( $current_image ) ) {
			$default_image = wp_get_attachment_image_src( $current_image );
			if ( ! empty( $default_image[0] ) ) {
				$default_image = $default_image[0];
				$_selected     = true;
			}
		}

		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="stm_taxonomy_listing_image">
					<?php esc_html_e( 'Category Image', 'stm_vehicles_listing' ); ?>
				</label>
			</th>
			<td>
				<div class="stm-choose-listing-image">
					<input
						type="hidden"
						name="stm_taxonomy_listing_image"
						id="stm_taxonomy_listing_image"
						value="<?php echo esc_attr( $current_image ); ?>"
						size="40"
						aria-required="true"/>

					<img
						class="stm_taxonomy_listing_image_chosen"
						src="<?php echo esc_url( $default_image ); ?>"
						<?php echo ! $_selected ? 'style="display: none;"' : ''; ?>
						alt="<?php esc_attr_e( 'Category Image', 'stm_vehicles_listing' ); ?>"
					/>

					<input type="button" class="button-secondary button-image-add" value="<?php esc_attr_e( 'Choose image', 'stm_vehicles_listing' ); ?>"/>
					<input
						type="button"
						class="button-secondary button-image-delete"
						value="<?php esc_attr_e( 'Remove image', 'stm_vehicles_listing' ); ?>"
						<?php echo ! $_selected ? 'style="display: none;"' : ''; ?>
					/>
				</div>
			</td>
			<script type="text/javascript">
				jQuery(function ($) {
					let parent = '.stm-choose-listing-image';

					$( '.button-image-add', parent ).click(function () {
						let $parent = $( this ).parents( parent );

						let custom_uploader = wp.media({
							title: "Select image",
							button: {
								text: "Attach"
							},
							multiple: false
						}).on("select", function () {
							let attachment = custom_uploader.state().get("selection").first().toJSON();

							$( '#stm_taxonomy_listing_image', $parent ).val( attachment.id );
							$( '.stm_taxonomy_listing_image_chosen', $parent ).attr( 'src', attachment.url ).show();
							$( '.button-image-delete', $parent ).show();
						}).open();
					});

					$( '.button-image-delete', parent ).click(function ( e ) {
						e.preventDefault();

						let $parent = $( this ).parents( parent );

						$( '#stm_taxonomy_listing_image', $parent ).val( '' );
						$( '.stm_taxonomy_listing_image_chosen', $parent ).attr( 'src', '' ).hide();
						$( '.button-image-delete', $parent ).hide();
					})
				});
			</script>
		</tr>
		<?php
	}
}

/*Save value*/
if ( ! function_exists( 'stm_taxonomy_listing_image_save' ) ) {
	function stm_taxonomy_listing_image_save( $update_data, $term_id, $taxonomy ) {
		if ( isset( $_POST['stm_taxonomy_listing_image'] ) ) { //phpcs:ignore
			update_term_meta( $term_id, 'stm_image', intval( $_POST['stm_taxonomy_listing_image'] ) ); //phpcs:ignore
		}

		return $update_data;
	}
}

/*Parent tax*/
$stm_get_car_parent_exist = stm_get_car_parent_exist();

if ( ! empty( $stm_get_car_parent_exist ) ) {
	foreach ( $stm_get_car_parent_exist as $stm_get_car_parent_exist_single ) {
		/** Add Custom Field To Form */
		add_action( $stm_get_car_parent_exist_single['slug'] . '_add_form_fields', 'stm_taxonomy_listing_add_field_parent', 10 );
		add_action( $stm_get_car_parent_exist_single['slug'] . '_edit_form_fields', 'stm_taxonomy_listing_edit_field_parent', 10, 2 );
		/** Save Custom Field Of Form */
		add_action( 'created_' . $stm_get_car_parent_exist_single['slug'], 'stm_taxonomy_listing_parent_save', 10, 2 );
		add_action( 'edited_' . $stm_get_car_parent_exist_single['slug'], 'stm_taxonomy_listing_parent_save', 10, 2 );
	}
}

/*Add field*/
if ( ! function_exists( 'stm_taxonomy_listing_add_field_parent' ) ) {
	function stm_taxonomy_listing_add_field_parent( $taxonomy ) {
		$taxonomy             = apply_filters( 'stm_vl_get_all_by_slug', array(), $taxonomy );
		$taxonomy_parent_slug = $taxonomy['listing_taxonomy_parent'];
		$taxonomy_parent      = apply_filters( 'stm_get_category_by_slug_all', array(), $taxonomy_parent_slug, true );
		?>
		<div class="form-field">
			<label for="stm_parent_taxonomy"><?php esc_html_e( 'Choose parent taxonomy' ); ?></label>
			<select multiple name="stm_parent_taxonomy[]" size="10">
				<option value=""><?php esc_html_e( 'No parent' ); ?></option>
				<?php if ( ! empty( $taxonomy_parent ) ) : ?>
					<?php foreach ( $taxonomy_parent as $term ) : ?>
						<option value="<?php echo esc_attr( $term->slug ); ?>">
							<?php echo esc_html( apply_filters( 'stm_parent_taxonomy_option', $term->name, $term, $taxonomy ) ); ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<?php
	}
}

if ( ! function_exists( 'stm_taxonomy_listing_edit_field_parent' ) ) {
	function stm_taxonomy_listing_edit_field_parent( $tag, $taxonomy ) {
		$values   = get_term_meta( $tag->term_id, 'stm_parent' );
		$taxonomy = apply_filters( 'stm_vl_get_all_by_slug', array(), $taxonomy );
		$parents  = apply_filters( 'stm_get_category_by_slug_all', array(), $taxonomy['listing_taxonomy_parent'], true );
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label
					for="stm_parent_taxonomy"><?php esc_html_e( 'Parent category' ); ?></label>
			</th>
			<td>
				<select multiple name="stm_parent_taxonomy[]" size="10">
					<option value=""><?php esc_html_e( 'No parent' ); ?></option>
					<?php if ( ! empty( $parents ) ) : ?>
						<?php foreach ( $parents as $term ) : ?>
							<?php
							if ( ! is_object( $term ) || empty( $term->slug ) || empty( $term->name ) ) {
								continue;
							}
							?>
							<option value="<?php echo esc_attr( $term->slug ); ?>"
								<?php selected( in_array( $term->slug, $values, true ) ); ?>>
								<?php echo esc_html( apply_filters( 'stm_parent_taxonomy_option', $term->name, $term, $taxonomy ) ); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
		</tr>
		<?php
	}
}

if ( ! function_exists( 'stm_taxonomy_listing_parent_save' ) ) {
	function stm_taxonomy_listing_parent_save( $term_id, $tt_id ) {
		/** add parent to term meta if it was created on post page via butterbean */
		if ( array_key_exists( 'stm_parent', $_GET ) ) {
			$parent = sanitize_title( $_GET['stm_parent'] );

			delete_term_meta( $term_id, 'stm_parent' );
			add_term_meta( $term_id, 'stm_parent', $parent );
		}

		if ( array_key_exists( 'stm_parent_taxonomy', $_POST ) ) { //phpcs:ignore
			delete_term_meta( $term_id, 'stm_parent' );
			foreach ( (array) $_POST['stm_parent_taxonomy'] as $slug ) { //phpcs:ignore
				add_term_meta( $term_id, 'stm_parent', $slug );
			}
		}
	}
}
