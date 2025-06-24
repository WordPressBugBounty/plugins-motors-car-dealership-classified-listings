<?php
namespace MotorsVehiclesListing\ListingManager;

use MotorsVehiclesListing\Libs\Traits\ProtectedHooks;
use MotorsVehiclesListing\Libs\Traits\ProtectedActivationHook;
use MotorsVehiclesListing\Libs\Traits\Instance;
use MotorsVehiclesListing\Stilization\Colors;

class Bootstrap {
	use Instance;
	use ProtectedHooks;
	use ProtectedActivationHook;

	protected string $endpoint       = 'listing-manager';
	protected string $layout_path    = '/templates/listing-manager/layout.php';
	protected array $admin_buttons   = array();
	protected array $pages           = array();
	protected array $listing_options = array();

	protected function __construct() {
		$this->admin_buttons = array(
			'add_item'  => __( 'Add with Listing Manager', 'stm_vehicles_listing' ),
			'edit_item' => __( 'Edit with Listing Manager', 'stm_vehicles_listing' ),
		);

		//WP Hooks for initialization of Listing Manager
		$this->add_action( 'init' );
		$this->add_action( 'custom_print_media_scripts' );
		$this->add_action( 'admin_footer', 100, 1 );
		$this->add_action( 'admin_bar_menu', 100, 1 );
		$this->add_filter( 'template_include' );
		$this->add_filter( 'query_vars' );
		$this->add_filter( 'post_row_actions', 10, 2 );
		$this->add_filter( 'enter_title_here', 10, 2 );
		$this->register_activation_hook( STM_LISTINGS_FILE );
		$this->register_update_hook( STM_LISTINGS_FILE );

		//Listing Manager Template Filters
		$this->add_filter( 'mvl_listing_manager_is_admin' );
		$this->add_filter( 'mvl_listing_manager_pages' );
		$this->add_filter( 'mvl_listing_manager_current_page' );
		$this->add_filter( 'mvl_listing_manager_first_page' );
		$this->add_filter( 'mvl_listing_manager_last_page' );
		$this->add_filter( 'mvl_listing_manager_item_id' );
		$this->add_filter( 'mvl_listing_manager_item_title' );
		$this->add_filter( 'mvl_listing_manager_item_badge_text' );
		$this->add_filter( 'mvl_listing_manager_item_badge_color' );
		$this->add_filter( 'mvl_listing_manager_item_is_special' );
		$this->add_filter( 'mvl_listing_manager_item_price' );
		$this->add_filter( 'mvl_listing_manager_item_sale_price' );
		$this->add_filter( 'mvl_listing_manager_item_is_sold' );
		$this->add_filter( 'mvl_listing_manager_url' );
		$this->add_filter( 'mvl_listing_manager_js' );
		$this->add_filter( 'mvl_listing_manager_css' );
		$this->add_filter( 'mvl_listing_manager_get_group_features' );
		$this->add_filter( 'mvl_listing_manager_is_first_page' );
		$this->add_filter( 'mvl_listing_manager_is_last_page' );
		$this->add_filter( 'mvl_listing_manager_item_created_date' );
		$this->add_filter( 'mvl_listing_manager_current_user_can_edit' );

		//Ajax Actions
		$this->add_action( 'wp_ajax_mvl_listing_manager_save' );
		$this->add_action( 'wp_ajax_nopriv_mvl_listing_manager_save' );
		$this->add_action( 'wp_ajax_listing_manager_get_form' );
		$this->add_action( 'wp_ajax_listing_manager_save_form' );
		$this->add_action( 'wp_ajax_listing_manager_delete_form' );
	}

	protected function enter_title_here( string $title = '' ): string {
		if ( 'listings' === get_post_type() ) {
			?>
			<style>
			.mvl-listing-manager-edit-form-after-title {
				display: inline-block;
				text-align: center;
				padding: 15px 30px;
				background-color: #0073aa;
				color: #fff !important;
				border-radius: 4px;
				font-size: 14px;
				margin: 0 0 15px;
				font-weight: bold;
			}
			</style>
			<a class="mvl-listing-manager-edit-form-after-title" href="<?php echo esc_url( $this->mvl_listing_manager_url( '', get_the_ID() ) ); ?>"><?php esc_html_e( 'Edit in Listing Manager', 'stm_vehicles_listing' ); ?></a>
			<?php
		}

		return $title;
	}

	protected function admin_bar_menu( \WP_Admin_Bar $wp_admin_bar ): void {
		if ( is_singular() && 'listings' === get_post_type() ) {
			$post = get_post();
			$wp_admin_bar->remove_node( 'edit' );
			$wp_admin_bar->add_node(
				array(
					'id'    => 'edit',
					'title' => __( 'Edit item', 'stm_vehicles_listing' ),
					'href'  => $this->mvl_listing_manager_url( '', $post->ID ),
				)
			);
		}
	}

	protected function mvl_listing_manager_item_is_sold( bool $is_sold = false ): bool {
		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$is_sold = get_post_meta( $listing_id, 'car_mark_as_sold', true ) === 'on';
		}

		return $is_sold;
	}

	protected function mvl_listing_manager_item_is_featured( bool $is_featured = false ): bool {
		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$is_featured = get_post_meta( $listing_id, 'special_car', true ) === 'on';
		}

		return $is_featured;
	}

	protected function mvl_listing_manager_item_sale_price( string $price = '-' ): string {
		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$listing_price = get_post_meta( $listing_id, 'sale_price', true );

			if ( $listing_price ) {
				$price = $listing_price . apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency' );
			}
		}

		return $price;
	}

	protected function mvl_listing_manager_item_price( string $price = '-' ): string {
		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$listing_price = get_post_meta( $listing_id, 'price', true );

			if ( $listing_price ) {
				if ( apply_filters( 'motors_vl_get_nuxy_mod', 'right', 'price_currency_position' ) === 'right' ) {
					$price = $listing_price . apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency' );
				} else {
					$price = apply_filters( 'motors_vl_get_nuxy_mod', '', 'price_currency' ) . $listing_price;
				}
			}
		}

		return $price;
	}

	protected function mvl_listing_manager_item_is_special( bool $is_special = false ): bool {
		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$is_special = get_post_meta( $listing_id, 'special_car', true ) === 'on';
		}

		return $is_special;
	}

	protected function mvl_listing_manager_item_badge_color( string $badge_color = '' ): string {
		$badge_color = Colors::value( 'spec_badge_color' );
		$listing_id  = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$listing_badge_color = get_post_meta( $listing_id, 'badge_bg_color', true );

			if ( $listing_badge_color ) {
				$badge_color = $listing_badge_color;
			}
		}

		return $badge_color;
	}

	protected function mvl_listing_manager_item_badge_text( string $badge_text = '' ): string {
		$badge_text = __( 'Special', 'stm_vehicles_listing' );
		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$listing_badge_text = get_post_meta( $listing_id, 'badge_text', true );

			if ( $listing_badge_text ) {
				$badge_text = $listing_badge_text;
			}
		}

		return $badge_text;
	}

	protected function mvl_listing_manager_item_title( string $listing_name = '' ): string {
		$listing_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $listing_id ) {
			$listing = get_post( $listing_id );

			if ( $listing->post_title ) {
				$listing_name = $listing->post_title;
			}
		}

		return $listing_name;
	}

	protected function mvl_listing_manager_item_created_date(): string {
		$listing_id   = apply_filters( 'mvl_listing_manager_item_id', 0 );
		$created_date = wp_date( 'd/m/Y' );

		if ( $listing_id ) {
			$created_date = wp_date( 'd/m/Y', strtotime( get_post_field( 'post_date', $listing_id ) ) );
		}

		return $created_date;
	}

	protected function mvl_listing_manager_first_page() {
		$pages = apply_filters( 'mvl_listing_manager_pages', array() );
		return reset( $pages );
	}

	protected function mvl_listing_manager_current_page( int $current_page = 0 ) {
		$current_page = apply_filters( 'mvl_listing_manager_first_page', false );

		foreach ( apply_filters( 'mvl_listing_manager_pages', array() ) as $page ) {
			if ( $page->is_active() ) {
				$current_page = $page;
				break;
			}
		}

		return $current_page;
	}

	protected function mvl_listing_manager_is_first_page(): bool {
		return apply_filters( 'mvl_listing_manager_current_page', 0 ) === apply_filters( 'mvl_listing_manager_first_page', 0 );
	}

	protected function mvl_listing_manager_is_last_page(): bool {
		return apply_filters( 'mvl_listing_manager_current_page', 0 ) === apply_filters( 'mvl_listing_manager_last_page', 0 );
	}

	protected function mvl_listing_manager_last_page(): object {
		$pages = apply_filters( 'mvl_listing_manager_pages', array() );
		return end( $pages );
	}

	protected function mvl_listing_manager_get_features_group() {
		$additional_features = get_terms(
			array(
				'taxonomy'   => 'stm_additional_features',
				'hide_empty' => false,
			)
		);

		$option = get_option( 'mvl_listing_details_settings', array() );

		$formatted_features = array();
		if ( ! is_wp_error( $additional_features ) && ! empty( $additional_features ) ) {
			foreach ( $additional_features as $feature ) {
				$formatted_features[] = array(
					'value' => $feature->slug,
					'label' => $feature->name,
				);
			}
		}
		$extra_features_group = array(
			array(
				'tab_title_single'          => 'Extra Features',
				'tab_title_selected_labels' => $formatted_features,
				'single_group'              => true,
			),
		);

		$option['fs_user_features'] = $extra_features_group;
		update_option( 'mvl_listing_details_settings', $option );
	}

	protected function mvl_listing_manager_get_group_features( array $features = array() ): array {
		$features = get_option( 'mvl_listing_details_settings', array() );
		if ( empty( $features['fs_user_features'] ) ) {
			$this->mvl_listing_manager_get_features_group();
		}
		return isset( $features['fs_user_features'] ) && is_array( $features['fs_user_features'] ) ? $features['fs_user_features'] : array();
	}

	protected function mvl_listing_manager_item_id(): int {

		$item_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

		if ( 0 === $item_id && wp_doing_ajax() ) {
			$item_id = isset( $_POST['listing_id'] ) ? intval( $_POST['listing_id'] ) : 0;
		}

		if ( ! apply_filters( 'mvl_listing_manager_current_user_can_edit', false, $item_id ) ) {
			$item_id = 0;
		}

		return $item_id;
	}

	protected function mvl_listing_manager_current_user_can_edit( bool $can_edit = false, int $item_id = 0 ): bool {
		$user_id = get_current_user_id();

		return $this->mvl_listing_manager_is_admin() || intval( $user_id ) === intval( get_post( $item_id )->post_author );
	}

	protected function mvl_listing_manager_is_admin( bool $is_admin = false ): bool {

		if ( is_user_logged_in() ) {
			$user      = wp_get_current_user();
			$user_role = $user->roles[0];

			if ( 'administrator' === $user_role || 'listing_manager' === $user_role ) {
				$is_admin = true;
			}

			if ( ! $is_admin && is_super_admin( $user->ID ) ) {
				$is_admin = true;
			}
		}

		return $is_admin;
	}

	protected function wp_ajax_listing_manager_save_form(): void {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'mvl_listing_manager' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid nonce.', 'stm_vehicles_listing' ),
				)
			);
		}

		$template                = $_POST['template'];
		$listing_manager_page_id = $_POST['listing_manager_page_id'];

		if ( $template && $listing_manager_page_id ) {
			$listing_manager_pages = $this->mvl_listing_manager_pages();
			$method                = 'save_' . str_replace( '-', '_', $template ) . '_form';

			if ( isset( $listing_manager_pages[ $listing_manager_page_id ] ) && method_exists( $listing_manager_pages[ $listing_manager_page_id ], $method ) ) {
				wp_send_json_success( $listing_manager_pages[ $listing_manager_page_id ]->$method() );
			}
		}
	}

	protected function wp_ajax_listing_manager_delete_form(): void {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'mvl_listing_manager_get_form' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid nonce.', 'stm_vehicles_listing' ),
				)
			);
		}

		$template                = isset( $_POST['template'] ) ? sanitize_text_field( $_POST['template'] ) : '';
		$listing_manager_page_id = isset( $_POST['listing_manager_page_id'] ) ? sanitize_text_field( $_POST['listing_manager_page_id'] ) : '';

		if ( $template && $listing_manager_page_id ) {
			$listing_manager_pages = $this->mvl_listing_manager_pages();
			$method                = 'delete_' . str_replace( '-', '_', $template ) . '_form';

			if (
				isset( $listing_manager_pages[ $listing_manager_page_id ] )
				&& method_exists( $listing_manager_pages[ $listing_manager_page_id ], $method )
			) {
				wp_send_json_success( $listing_manager_pages[ $listing_manager_page_id ]->$method() );
			}
		}

		wp_send_json_error(
			array(
				'message' => __( 'Invalid request.', 'stm_vehicles_listing' ),
			)
		);
	}

	protected function wp_ajax_listing_manager_get_form(): void {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'mvl_listing_manager_get_form' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid nonce.', 'stm_vehicles_listing' ),
				)
			);
		}

		$template                = sanitize_text_field( $_POST['template'] );
		$listing_manager_page_id = sanitize_text_field( $_POST['listing_manager_page_id'] );

		if ( $template && $listing_manager_page_id ) {
			$listing_manager_pages = $this->mvl_listing_manager_pages();
			$method                = 'get_' . str_replace( '-', '_', $template ) . '_form';

			if ( isset( $listing_manager_pages[ $listing_manager_page_id ] ) && method_exists( $listing_manager_pages[ $listing_manager_page_id ], $method ) ) {
				$result = $listing_manager_pages[ $listing_manager_page_id ]->$method();
				wp_send_json_success( $result );
			} elseif ( 'confirmation' === $template ) {
				$data = isset( $_POST['data'] ) ? $_POST['data'] : array();
				ob_start();
				do_action(
					'stm_listings_load_template',
					'listing-manager/parts/modals/confirmation',
					array(
						'data' => $data,
					)
				);
				$html = ob_get_clean();
				wp_send_json_success(
					array(
						'html' => $html,
					)
				);
			}
		}

		wp_send_json_error(
			array(
				'message' => __( 'Invalid request.', 'stm_vehicles_listing' ),
			)
		);
	}

	protected function wp_ajax_mvl_listing_manager_save(): void {
		if ( ! wp_doing_ajax() || ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'mvl_listing_manager' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid nonce.', 'stm_vehicles_listing' ),
				)
			);
		}

		$item_id = apply_filters( 'mvl_listing_manager_item_id', 0 );

		if ( $item_id && ! apply_filters( 'mvl_listing_manager_current_user_can_edit', false, $item_id ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Current user cannot edit this listing.', 'stm_vehicles_listing' ),
				)
			);
		}

		$has_errors = false;

		if ( ! isset( $_POST['post_status'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Post status is required.', 'stm_vehicles_listing' ),
				)
			);
		}

		$post_status   = sanitize_text_field( $_POST['post_status'] );
		$post_statuses = array( 'draft', 'publish', 'trash' );

		if ( ! in_array( $post_status, $post_statuses, true ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid post status.', 'stm_vehicles_listing' ),
				)
			);
		}

		if ( ! $item_id ) {
			$item_id = wp_insert_post(
				array(
					'post_title'  => __( 'Untitled Listing', 'stm_vehicles_listing' ),
					'post_type'   => isset( $_POST['general'] ) && isset( $_POST['general']['title'] ) ? sanitize_text_field( $_POST['general']['title'] ) : esc_html__( 'Untitled Listing', 'stm_vehicles_listing' ),
					'post_status' => $post_status,
					'post_name'   => isset( $_POST['general'] ) && isset( $_POST['general']['title'] ) ? sanitize_title( $_POST['general']['title'] ) : 'untitled',
				)
			);
		}

		$response = array(
			'post_id' => $item_id,
		);

		$progress_page = false;

		if ( isset( $_POST['progress_page'] ) && $_POST['progress_page'] ) {
			$progress_page = sanitize_text_field( $_POST['progress_page'] );
		}

		$progress_page_continued = false;

		foreach ( $this->mvl_listing_manager_pages() as $listing_manager_page ) {
			if ( $progress_page ) {
				if ( $listing_manager_page->get_id() === $progress_page ) {
					$progress_page_continued = true;
				} else {
					if ( ! $progress_page_continued ) {
						continue;
					}
				}
			}

			$id                  = $listing_manager_page->get_id();
			$data                = isset( $_POST[ $id ] ) && is_array( $_POST[ $id ] ) ? $_POST[ $id ] : array();
			$data['post_id']     = $item_id;
			$data['post_status'] = $post_status;

			if ( isset( $_FILES[ $id ] ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				$data['files'] = $_FILES[ $id ];
			}

			$response[ $id ] = $listing_manager_page->save( $data );

			if ( isset( $response[ $id ]['errors'] ) && count( $response[ $id ]['errors'] ) ) {
				$has_errors = true;
			}
		}

		$response['post_status'] = get_post_status( $item_id );
		$response['preview_url'] = 'publish' === $response['post_status'] ? get_the_permalink( $item_id ) : get_preview_post_link( $item_id );
		$response['back_link']   = esc_url( admin_url( 'edit.php?post_type=listings' ) );

		if ( $has_errors ) {
			wp_send_json_error( $response );
		} else {
			$response['message'] = __( 'Changes saved.', 'stm_vehicles_listing' );
			wp_send_json_success( $response );
		}
	}

	protected function wp_ajax_nopriv_mvl_listing_manager_save(): void {
		//Should return error if user is not logged in
		wp_send_json_error(
			array(
				'message' => __( 'You are not authorized to perform this action.', 'stm_vehicles_listing' ),
			)
		);
	}

	protected function mvl_listing_manager_js(): array {
		$scripts = array(
			'jquery'                       => includes_url( 'js/jquery/jquery.js' ),
			'jquery-ui-core'               => includes_url( 'js/jquery/ui/core.min.js' ),
			'jquery-ui-mouse'              => includes_url( 'js/jquery/ui/mouse.min.js' ),
			'jquery-ui-draggable'          => includes_url( 'js/jquery/ui/draggable.min.js' ),
			'jquery-ui-droppable'          => includes_url( 'js/jquery/ui/droppable.min.js' ),
			'jquery-ui-sortable'           => includes_url( 'js/jquery/ui/sortable.min.js' ),

			'mvl-select2'                  => STM_LISTINGS_URL . '/assets/js/frontend/select2.full.min.js',
			'mvl-color-picker'             => STM_LISTINGS_URL . '/assets/js/listing-manager/libs/color-picker.js',
			'mvl-draggable-items'          => STM_LISTINGS_URL . '/assets/js/listing-manager/libs/draggable-items.js',
			'mvl-dropzone'                 => STM_LISTINGS_URL . '/assets/js/listing-manager/libs/dropzone.js',
			'mvl-file-validator'           => STM_LISTINGS_URL . '/assets/js/listing-manager/libs/file-validator.js',
			'mvl-confirmation-popup'       => STM_LISTINGS_URL . '/assets/js/listing-manager/libs/confirmation-popup.js',
			'listing-manager-select2'      => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/select2.js',
			'listing-manager-color'        => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/color.js',
			'listing-manager-switch'       => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/switch.js',
			'listing-manager-file-list'    => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/file-list.js',
			'listing-manager-file'         => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/file.js',
			'listing-manager-image-list'   => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/image-list.js',
			'listing-manager-image'        => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/image.js',
			'listing-manager-date'         => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/date.js',
			'listing-manager-repeater'     => STM_LISTINGS_URL . '/assets/js/listing-manager/fields/repeater.js',
			'listing-manager-options'      => STM_LISTINGS_URL . '/assets/js/listing-manager/listing-manager-options.js',
			'listing-manager-features'     => STM_LISTINGS_URL . '/assets/js/listing-manager/listing-manager-features.js',
			'listing-manager-listing-card' => STM_LISTINGS_URL . '/assets/js/listing-manager/listing-manager-listing-card.js',
			'listing-manager'              => STM_LISTINGS_URL . '/assets/js/listing-manager/listing-manager.js',
			'tinymce'                      => STM_LISTINGS_URL . '/assets/libs/tinymce/js/tinymce/tinymce.min.js',
			'tinymce-init'                 => STM_LISTINGS_URL . '/assets/js/listing-manager/libs/tinymce.js',
			'mvl-tooltip'                  => STM_LISTINGS_URL . '/assets/js/listing-manager/libs/tooltip.js',
			'listing-manager-search'       => STM_LISTINGS_URL . '/assets/js/listing-manager/listing-manager-search.js',
		);

		if ( apply_filters( 'is_mvl_pro', false ) && apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_api_key' ) ) {
			$scripts['listing-manager-location'] = STM_LISTINGS_URL . '/assets/js/listing-manager/listing-manager-location.js';
			$scripts['google-maps']              = 'https://maps.googleapis.com/maps/api/js?key=' . apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_api_key' ) . '&language=' . get_locale() . '&libraries=places&callback=initMap';
		}

		if ( $this->mvl_listing_manager_is_admin() ) {
			$scripts['listing-manager-image-button'] = STM_LISTINGS_URL . '/assets/js/listing-manager/fields/image-button.js';
		}

		return $scripts;
	}

	protected function custom_print_media_scripts() {
		wp_enqueue_media();
		global $wp_scripts, $wp_styles;
		foreach ( $wp_scripts->queue as $handle ) {
			wp_print_scripts( $handle );
		}
		foreach ( $wp_styles->queue as $handle ) {
			wp_print_styles( $handle );
		}
	}

	protected function mvl_listing_manager_css(): array {
		return array(
			'font-awesome'    => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
			'motors-icons'    => STM_LISTINGS_URL . '/assets/css/frontend/icons.css',
			'google-fonts'    => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
			'select2'         => STM_LISTINGS_URL . '/assets/css/frontend/select2.min.css',
			'color-picker'    => 'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css',
			'listing-manager' => STM_LISTINGS_URL . '/assets/css/listing-manager/listing-manager.css',
			'mvl-tinymce'     => STM_LISTINGS_URL . '/assets/libs/tinymce/css/tinymce.css',
		);
	}

	protected function mvl_listing_manager_pages(): array {
		$pages_classnames = array(
			'MotorsVehiclesListing\\ListingManager\\Pages\\General',
			'MotorsVehiclesListing\\ListingManager\\Pages\\Media',
			'MotorsVehiclesListing\\ListingManager\\Pages\\Option',
			'MotorsVehiclesListing\\ListingManager\\Pages\\Price',
			'MotorsVehiclesListing\\ListingManager\\Pages\\Features',
			'MotorsVehiclesListing\\ListingManager\\Pages\\Location',
			'MotorsVehiclesListing\\ListingManager\\Pages\\OtherDetails',
		);

		if ( empty( $this->pages ) ) {
			foreach ( $pages_classnames as $page_classname ) {
				if ( class_exists( $page_classname ) ) {
					$page                           = new $page_classname();
					$this->pages[ $page->get_id() ] = $page;
				}
			}
		}

		return $this->pages;
	}

	//Methods of Listing Manager Template Filters
	protected function mvl_listing_manager_url( $default = '', int $post_id = 0 ): string {
		if ( $post_id ) {
			return home_url( $this->endpoint . '?id=' . $post_id );
		} else {
			return home_url( $this->endpoint );
		}
	}

	//Methods for WP Hooks for initialization Listing Manager Page
	protected function post_row_actions( array $actions, \WP_Post $post ): array {
		if ( ! isset( $_GET['post_status'] ) || 'trash' !== $_GET['post_status'] ) {
			if ( 'listings' === $post->post_type ) {
				$actions = array_merge(
					array(
						'edit_from_listing_manager' => '<a href="' . esc_url( $this->mvl_listing_manager_url( '', $post->ID ) ) . '">' . esc_html( $this->admin_buttons['edit_item'] ) . '</a>',
					),
					$actions
				);
			}
		}
		return $actions;
	}

	protected function admin_footer(): void {
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( 'listings' === $screen->post_type && in_array( $screen->base, array( 'edit', 'post' ), true ) ) {
				echo '<script>
				window.addEventListener("load", function() {
					var addNew = document.querySelector(".page-title-action");
					if (addNew) {
						var newBtn = addNew.cloneNode(true);
						newBtn.textContent = "' . esc_html( $this->admin_buttons['add_item'] ) . '";
						newBtn.setAttribute("href", "' . esc_url( home_url( $this->endpoint . '/' ) ) . '");
						addNew.parentNode.insertBefore(newBtn, addNew);
					}
				})
				</script>';
				$this->admin_buttons['add_item_loaded'] = true;
			}
		}
	}

	protected function init(): void {
		add_rewrite_rule(
			'^' . $this->endpoint . '/?$',
			'index.php?' . $this->endpoint . '=1',
			'top'
		);
	}

	protected function query_vars( array $vars ): array {
		$vars[] = $this->endpoint;
		return $vars;
	}

	protected function template_include( string $template ): string {
		if ( get_query_var( $this->endpoint ) ) {
			if ( apply_filters( 'mvl_listing_manager_is_admin', false ) ) {
				$template = STM_LISTINGS_PATH . $this->layout_path;
			} else {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				$template = get_query_template( '404' );
			}
		}

		return $template;
	}

	protected function on_activation(): void {
		$this->init();
		flush_rewrite_rules();
	}
}
