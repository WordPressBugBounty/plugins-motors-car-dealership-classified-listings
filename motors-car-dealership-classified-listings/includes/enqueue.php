<?php
use MotorsVehiclesListing\Stilization\Colors;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'stm_google_places_enable_script' ) ) {
	function stm_google_places_enable_script( $status = 'registered', $only_google_load = false ) {
		$status         = empty( $status ) ? 'registered' : $status;
		$google_api_key = apply_filters( 'motors_vl_get_nuxy_mod', '', 'google_api_key' );

		if ( ! empty( $google_api_key ) ) {
			$google_api_map = 'https://maps.googleapis.com/maps/api/js';
			$google_api_map = add_query_arg(
				array(
					'key'       => $google_api_key,
					'libraries' => 'places',
					'loading'   => 'async',
					'language'  => get_bloginfo( 'language' ),
					'callback'  => 'stm_gmap_lib_loaded',
				),
				$google_api_map
			);

			if ( ! wp_script_is( 'stm_gmap', 'registered' ) ) {
				wp_register_script( 'stm_gmap', $google_api_map, null, '1.0', true );
				wp_add_inline_script(
					'stm_gmap',
					'function stm_gmap_lib_loaded(){ var stmGmap = new CustomEvent( \'stm_gmap_api_loaded\', { bubbles: true } ); 
						jQuery( document ).ready( function(){
							document.body.dispatchEvent( stmGmap ); 
						} );
					}',
					'after'
				);
			}

			if ( ! wp_script_is( 'stm-google-places' ) && ! $only_google_load ) {
				wp_register_script( 'stm-google-places', STM_LISTINGS_URL . '/assets/js/frontend/stm-google-places.js', array( 'jquery', 'stm_gmap', 'listings-filter' ), STM_LISTINGS_V, true );
			}

			if ( 'enqueue' === $status ) {
				wp_enqueue_script( 'stm_gmap' );

				if ( ! $only_google_load ) {
					wp_enqueue_script( 'stm-google-places' );
				}
			}
		}
	}
}

add_action( 'stm_google_places_script', 'stm_google_places_enable_script' );

function stm_listings_add_car_script() {
	wp_register_style( 'motors-add-listing', STM_LISTINGS_URL . '/assets/css/frontend/add-listing.css', null, STM_LISTINGS_V );
	wp_register_script( 'motors-add-listing', STM_LISTINGS_URL . '/assets/js/frontend/add-listing.js', array( 'jquery', 'jquery-ui-droppable' ), STM_LISTINGS_V, true );

	$max_file_size = apply_filters( 'stm_listing_media_upload_size', 1024 * 4000 ); /* 4mb is the highest media upload here */
	$limits        = apply_filters(
		'stm_get_post_limits',
		array(
			'premoderation' => true,
			'posts_allowed' => 0,
			'posts'         => 0,
			'images'        => 0,
			'role'          => 'user',
		),
		get_current_user_id()
	);
	$crop          = apply_filters( 'motors_vl_get_nuxy_mod', false, 'user_image_crop_checkbox' );
	$width         = apply_filters( 'motors_vl_get_nuxy_mod', 800, 'user_image_crop_width' );
	$height        = apply_filters( 'motors_vl_get_nuxy_mod', 600, 'user_image_crop_height' );

	$_image_upload_script = "
        var stm_image_upload_settings = {
            messages: {
                ajax_error: '" . esc_html__( 'Some error occurred, try again later', 'stm_vehicles_listing' ) . "',
                wait_upload: '" . sprintf(
					/* translators: %s: uploading image dotted */
					esc_html__( 'Wait, uploading image%s', 'stm_vehicles_listing' ),
					'<strong class="stm-progress-bar__dotted"><span>.</span><span>.</span><span>.</span></strong>'
				) . "',
                format: '" . esc_html__( 'Sorry, you are trying to upload the wrong image format:', 'stm_vehicles_listing' ) . "',
                large: '" . esc_html__( 'Sorry, image is too large:', 'stm_vehicles_listing' ) . "',
                rendering: '" . sprintf(
					/* translators: %s: rendering image dotted */
					esc_html__( 'Wait, rendering image%s', 'stm_vehicles_listing' ),
					'<strong class="stm-progress-bar__dotted"><span>.</span><span>.</span><span>.</span></strong>'
				) . "',
                optimizing_image: '" . sprintf(
					/* translators: %s: optimized image dotted */
					esc_html__( 'Wait, the image is being optimized%s', 'stm_vehicles_listing' ),
					'<strong class="stm-progress-bar__dotted"><span>.</span><span>.</span><span>.</span></strong>'
				) . "',
                limit: '" . sprintf(
					/* translators: %d: images limit */
					esc_html__( 'Sorry, you can upload only %d images per add', 'stm_vehicles_listing' ),
					$limits['images']
				) . "'
            },
            size: '" . $max_file_size . "',
            upload_limit: {
                max: '" . absint( $limits['images'] ) . "',
            },
            cropping: {
                enable: '" . $crop . "',
                width: '" . $width . "',
                height: '" . $height . "',
            }
        }
    ";

	wp_add_inline_script( 'motors-add-listing', $_image_upload_script, 'before' );

	//Progressbar
	wp_register_style( 'progress', STM_LISTINGS_URL . '/assets/css/progress.css', array( 'motors-add-listing' ), STM_LISTINGS_V );
	wp_register_script( 'progressbar-layui', STM_LISTINGS_URL . '/assets/js/progressbar/layui.min.js', array( 'jquery', 'motors-add-listing' ), STM_LISTINGS_V, true );
	wp_register_script( 'progressbar', STM_LISTINGS_URL . '/assets/js/progressbar/jquery-progress-lgh.js', array( 'progressbar-layui' ), STM_LISTINGS_V, true );
}

add_action( 'stm_listings_add_car_script', 'stm_listings_add_car_script' );

function stm_listings_enqueue_scripts_styles() {

	if ( defined( 'STM_WPCFTO_URL' ) ) {
		$v      = time();
		$assets = STM_WPCFTO_URL . 'metaboxes/assets';

		wp_enqueue_style( 'font-awesome-min', $assets . '/vendors/font-awesome.min.css', null, $v );
		wp_enqueue_script( 'wpcfto_metaboxes.js', $assets . 'js/metaboxes.js', array( 'vue.js' ), $v, true );
	}

	wp_enqueue_style( 'motors-icons', STM_LISTINGS_URL . '/assets/css/frontend/icons.css', array(), STM_LISTINGS_V );
	wp_enqueue_style( 'owl.carousel', STM_LISTINGS_URL . '/assets/css/frontend/owl.carousel.min.css', array(), STM_LISTINGS_V );
	wp_enqueue_style( 'bootstrap-grid', STM_LISTINGS_URL . '/assets/css/frontend/grid.css', array(), STM_LISTINGS_V );
	wp_enqueue_style( 'listings-frontend', STM_LISTINGS_URL . '/assets/css/frontend/frontend_styles.css', array(), STM_LISTINGS_V );
	wp_enqueue_style( 'light-gallery', STM_LISTINGS_URL . '/assets/css/frontend/lightgallery.min.css', array(), STM_LISTINGS_V );
	wp_enqueue_style( 'modal-bootstrap', STM_LISTINGS_URL . '/assets/css/bootstrap/bootstrap.min.css', array(), STM_LISTINGS_V );
	wp_register_style( 'motors-datetimepicker', STM_LISTINGS_URL . '/assets/css/motors-datetimepicker.css', null, STM_LISTINGS_V );
	wp_enqueue_style( 'jquery-ui', STM_LISTINGS_URL . '/assets/css/jquery-ui.css', null, STM_LISTINGS_V );
	wp_enqueue_style( 'modal-style', STM_LISTINGS_URL . '/assets/css/modal-style.css', array(), STM_LISTINGS_V );
	wp_enqueue_style( 'stm-icon-font', STM_LISTINGS_URL . '/assets/css/frontend/stm-ico-style.css', array(), STM_LISTINGS_V );
	wp_enqueue_style( 'horizontal-filter', STM_LISTINGS_URL . '/assets/css/frontend/horizontal-filter.css', null, STM_LISTINGS_V );
	wp_enqueue_style( 'motors-style', STM_LISTINGS_URL . '/assets/css/style.css', null, STM_LISTINGS_V );
	wp_register_style( 'stmselect2', STM_LISTINGS_URL . '/assets/css/frontend/select2.min.css', null, STM_LISTINGS_V );
	wp_register_style( 'bootstrap', STM_LISTINGS_URL . '/assets/css/bootstrap/main.css', null, STM_LISTINGS_V );
	wp_register_style( 'swiper', STM_LISTINGS_URL . '/assets/css/swiper-carousel/swiper-bundle.min.css', null, STM_LISTINGS_V );
	wp_register_style( 'app-select2', STM_LISTINGS_URL . '/assets/css/frontend/app-select2.css', null, STM_LISTINGS_V );
	wp_register_style( 'items-per-page', STM_LISTINGS_URL . '/assets/css/frontend/items-per-page.css', null, STM_LISTINGS_V );
	wp_register_style( 'inventory-view-type', STM_LISTINGS_URL . '/assets/css/frontend/inventory-view-type.css', null, STM_LISTINGS_V );
	wp_register_style( 'loop-list', STM_LISTINGS_URL . '/assets/css/frontend/loop-list.css', null, STM_LISTINGS_V );
	wp_register_style( 'loop-grid', STM_LISTINGS_URL . '/assets/css/frontend/loop-grid.css', null, STM_LISTINGS_V );
	wp_register_style( 'sell-a-car-form', STM_LISTINGS_URL . '/assets/css/frontend/sell-a-car-form.css', null, STM_LISTINGS_V );
	wp_register_style( 'listing-icon-filter', STM_LISTINGS_URL . '/assets/css/frontend/listing_icon_filter.css', null, STM_LISTINGS_V );
	wp_register_style( 'listings-tabs', STM_LISTINGS_URL . '/assets/css/frontend/listings-tabs.css', null, STM_LISTINGS_V );
	wp_register_style( 'listing-search-empty-results', STM_LISTINGS_URL . '/assets/css/frontend/components/inventory/results-empty.css', null, STM_LISTINGS_V );
	wp_register_style( 'listing-search', STM_LISTINGS_URL . '/assets/css/frontend/listing-search.css', null, STM_LISTINGS_V );
	wp_register_style( 'motors-single-listing', STM_LISTINGS_URL . '/assets/css/frontend/single-listing.css', null, STM_LISTINGS_V );
	wp_register_style( 'inventory', STM_LISTINGS_URL . '/assets/css/frontend/inventory.css', null, STM_LISTINGS_V );
	wp_register_style( 'motors-tinymce', STM_LISTINGS_URL . '/assets/css/frontend/tinymce.css', null, STM_LISTINGS_V );

	wp_enqueue_script( 'jquery', false, array(), STM_LISTINGS_V, false );
	wp_enqueue_script( 'jquery-migrate', false, array(), STM_LISTINGS_V, false );
	wp_enqueue_script( 'jquery-ui-effect', STM_LISTINGS_URL . '/assets/js/jquery-ui-effect.min.js', array(), STM_LISTINGS_V, false );
	wp_register_script( 'stm-cascadingdropdown', STM_LISTINGS_URL . '/assets/js/frontend/jquery.cascadingdropdown.js', array(), STM_LISTINGS_V, false );
	wp_enqueue_script( 'bootstrap-tab', STM_LISTINGS_URL . '/assets/js/bootstrap/tab.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'bootstrap', STM_LISTINGS_URL . '/assets/js/bootstrap/bootstrap.min.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'jquery-cookie', STM_LISTINGS_URL . '/assets/js/frontend/jquery.cookie.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'lazyload', STM_LISTINGS_URL . '/assets/js/frontend/lazyload.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'swiper', STM_LISTINGS_URL . '/assets/js/swiper-carousel/swiper-bundle.min.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'stmselect2', STM_LISTINGS_URL . '/assets/js/frontend/select2.full.min.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'app-select2', STM_LISTINGS_URL . '/assets/js/frontend/app-select2.js', 'stmselect2', STM_LISTINGS_V, true );
	wp_register_script( 'listing-icon-filter', STM_LISTINGS_URL . '/assets/js/frontend/listing_icon_filter.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'listings-tabs', STM_LISTINGS_URL . '/assets/js/frontend/listings-tabs.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'listing-search', STM_LISTINGS_URL . '/assets/js/frontend/listing-search.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'owl.carousel', STM_LISTINGS_URL . '/assets/js/frontend/owl.carousel.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'light-gallery', STM_LISTINGS_URL . '/assets/js/frontend/lightgallery-all.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'chart-js', STM_LISTINGS_URL . '/assets/js/frontend/chart.min.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'uniform', STM_LISTINGS_URL . '/assets/js/frontend/jquery.uniform.min.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'motors-datetimepicker', STM_LISTINGS_URL . '/assets/js/motors-datetimepicker.js', array( 'jquery' ), STM_LISTINGS_V, true );

	wp_enqueue_script(
		'listings-init',
		STM_LISTINGS_URL . '/assets/js/frontend/init.js',
		array(
			'jquery',
			'jquery-ui-slider',
		),
		STM_LISTINGS_V,
		true
	);
	wp_enqueue_script( 'mlv-plugin-scripts', STM_LISTINGS_URL . '/assets/js/frontend/plugin.js', array( 'listings-init' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'mvl-user-sidebar', STM_LISTINGS_URL . '/assets/js/frontend/app-user-sidebar.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'listings-filter', STM_LISTINGS_URL . '/assets/js/frontend/filter.js', array( 'listings-init', 'stmselect2' ), STM_LISTINGS_V, true );
	wp_enqueue_script( 'app-ajax', STM_LISTINGS_URL . '/assets/js/frontend/app-ajax.js', array( 'jquery' ), STM_LISTINGS_V, true );
	$inline_script_recaptcha = "var onloadRecaptchaCallback = function() {
        var submitButton = document.querySelector('.stm-login-register-form .stm-register-form form input[type=\"submit\"]');
        if (submitButton) {
            submitButton.setAttribute('disabled', '1');
        }
    };";
	wp_add_inline_script( 'app-ajax', $inline_script_recaptcha );
	wp_enqueue_script( 'isotope', STM_LISTINGS_URL . '/assets/js/isotope.pkgd.min.js', array( 'jquery', 'imagesloaded' ), STM_LISTINGS_V, true );
	wp_register_script( 'items-per-page', STM_LISTINGS_URL . '/assets/js/frontend/items-per-page.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'inventory-view-type', STM_LISTINGS_URL . '/assets/js/frontend/inventory-view-type.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'sell-a-car-form', STM_LISTINGS_URL . '/assets/js/sell-a-car-form.js', array( 'jquery' ), STM_LISTINGS_V, true );
	wp_register_script( 'motors-single-listing', STM_LISTINGS_URL . '/assets/js/frontend/single-listing.js', null, STM_LISTINGS_V, true );

	if ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' ) ) {
		wp_enqueue_style( 'brazzers-carousel', STM_LISTINGS_URL . '/assets/css/frontend/brazzers-carousel.min.css', array(), STM_LISTINGS_V );
		wp_enqueue_script( 'brazzers-carousel', STM_LISTINGS_URL . '/assets/js/frontend/brazzers-carousel.min.js', array( 'jquery' ), STM_LISTINGS_V, true );
		wp_enqueue_script( 'hoverable-gallery', STM_LISTINGS_URL . '/assets/js/frontend/hoverable-gallery.js', array( 'jquery' ), STM_LISTINGS_V, true );
		wp_enqueue_style( 'hoverable-gallery', STM_LISTINGS_URL . '/assets/css/frontend/hoverable-gallery.css', array(), STM_LISTINGS_V );
	}

	wp_localize_script(
		'listings-init',
		'stm_i18n',
		array(
			'stm_label_add'                     => __( 'Add to compare', 'stm_vehicles_listing' ),
			'stm_label_remove'                  => __( 'Remove from compare', 'stm_vehicles_listing' ),
			'stm_label_remove_list'             => __( 'Remove from list', 'stm_vehicles_listing' ),
			'stm_label_in_compare'              => __( 'In compare list', 'stm_vehicles_listing' ),
			'remove_from_compare'               => __( 'Remove from compare', 'stm_vehicles_listing' ),
			'stm_already_added_to_compare_text' => __( 'You have already added 3 cars', 'stm_vehicles_listing' ),
			'remove_from_favorites'             => __( 'Remove from favorites', 'stm_vehicles_listing' ),
			'add_to_favorites'                  => __( 'Add to favorites', 'stm_vehicles_listing' ),
			'motors_vl_config'                  => array(
				'enable_friendly_urls' => apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ),
			),
			'required_fields'                   => __( 'Please enter required fields', 'stm_vehicles_listing' ),
			'image_upload_required'             => __( 'Please upload a photo to create a listing', 'stm_vehicles_listing' ),
			'seller_notes_required'             => __( 'Please leave a seller’s note to create a listing', 'stm_vehicles_listing' ),
			'features_required'                 => __( 'Please choose at least one feature to create a listing', 'stm_vehicles_listing' ),
			'video_required'                    => __( 'Please share a video URL to create a listing', 'stm_vehicles_listing' ),
			'car_price_required'                => __( 'Please add item price', 'stm_vehicles_listing' ),
			'mvl_current_page_url'              => apply_filters( 'stm_listings_current_url', '' ),
			'mvl_search_placeholder'            => __( 'Search', 'stm_vehicles_listing' ),
		)
	);

	/* Add a car */
	do_action( 'stm_listings_add_car_script' );

	/* Google places */
	do_action( 'stm_google_places_script' );

	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		if ( Elementor\Plugin::$instance->editor->is_edit_mode() || Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			wp_add_inline_script( 'listings-init', 'var stm_elementor_editor_mode = true' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'stm_listings_enqueue_scripts_styles' );

if ( ! function_exists( 'init_motors_root_colors' ) ) {
	function init_motors_root_colors() {
		$primary_color        = '#cc6119';
		$secondary_color      = '#6c98e1';
		$secondary_color_dark = '#5a7db6';
		$third_color          = '#232628'; //'#153e4d';
		$fourth_color         = '#153e4d';
		$active_elementor_kit = get_option( 'elementor_active_kit' );

		$colors_css = '
			:root{
				--mvl-primary-color: ' . $primary_color . ';
				--mvl-secondary-color: ' . $secondary_color . ';
				--mvl-secondary-color-dark: ' . $secondary_color_dark . ';
				--mvl-third-color: ' . $third_color . ';
				--mvl-fourth-color: ' . $fourth_color . ';
				
				--motors-accent-color: ' . Colors::value( 'accent_color' ) . ';
				--motors-accent-color-alpha: ' . Colors::value( 'accent_color', 0.5 ) . ';
				--motors-accent-color-highalpha: ' . Colors::value( 'accent_color', 0.7 ) . ';
				--motors-accent-color-lowalpha: ' . Colors::value( 'accent_color', 0.3 ) . ';
				--motors-accent-color-super-lowalpha : ' . Colors::value( 'accent_color', 0.1 ) . ';
				--motors-bg-shade: ' . Colors::value( 'bg_shade' ) . ';
				--motors-bg-color: ' . Colors::value( 'bg_color' ) . ';
				--motors-bg-lowalpha-color: ' . Colors::value( 'bg_color', 0.3 ) . ';
				--motors-bg-alpha-color: ' . Colors::value( 'bg_color', 0.5 ) . ';
				--motors-bg-highalpha-color: ' . Colors::value( 'bg_color', 0.7 ) . ';
				--motors-bg-contrast: ' . Colors::value( 'bg_contrast' ) . ';
				--motors-bg-lowestalpha-contrast: ' . Colors::value( 'bg_contrast', 0.1 ) . ';
				--motors-bg-lowalpha-contrast: ' . Colors::value( 'bg_contrast', 0.3 ) . ';
				--motors-bg-alpha-contrast: ' . Colors::value( 'bg_contrast', 0.5 ) . ';
				--motors-bg-highalpha-contrast: ' . Colors::value( 'bg_contrast', 0.7 ) . ';
				--motors-text-color: ' . Colors::value( 'text_color' ) . ';
				--motors-contrast-text-color: ' . Colors::value( 'contrast_text_color' ) . ';
				--motors-text-highalpha-color: ' . Colors::value( 'text_color', 0.7 ) . ';
				--motors-text-alpha-color: ' . Colors::value( 'text_color', 0.5 ) . ';
				--motors-text-low-alpha-color: ' . Colors::value( 'text_color', 0.3 ) . ';
				--motors-text-super-low-alpha-color: ' . Colors::value( 'text_color', 0.1 ) . ';
				--motors-contrast-text-alpha-color: ' . Colors::value( 'contrast_text_color', 0.7 ) . ';
				--motors-border-color: ' . Colors::value( 'text_color', 0.15 ) . ';
				--motors-contrast-border-color: ' . Colors::value( 'contrast_text_color', 0.15 ) . ';
				--motors-spec-badge-color: ' . Colors::value( 'spec_badge_color' ) . ';
				--motors-sold-badge-color: ' . Colors::value( 'sold_badge_color' ) . ';
				--motors-error-bg-color: ' . Colors::value( 'error_bg_color' ) . ';
				--motors-notice-bg-color: ' . Colors::value( 'notice_bg_color' ) . ';
				--motors-success-bg-color: ' . Colors::value( 'success_bg_color' ) . ';
				--motors-error-text-color: ' . Colors::value( 'error_text_color' ) . ';
				--motors-notice-text-color: ' . Colors::value( 'notice_text_color' ) . ';
				--motors-success-text-color: ' . Colors::value( 'success_text_color' ) . ';
				--motors-filter-inputs-color: ' . Colors::value( 'filter_inputs_color' ) . ';

				--motors-card-bg-color: ' . Colors::value( 'card_bg_color', -1.0, '#ffffff' ) . ';
				--motors-card-bg-hover-color: ' . Colors::value( 'card_bg_color_hover', -1.0, '#ffffff' ) . ';
				--motors-card-title-color: ' . Colors::value( 'card_title_color', -1.0, '#111827' ) . ';
				--motors-card-price-color: ' . Colors::value( 'card_title_color', 0.4, '#11182706' ) . ';
				--motors-card-border-color: ' . Colors::value( 'card_title_color', 0.15, '#11182706' ) . ';
				--motors-card-options-color: ' . Colors::value( 'card_options_color', -1.0, '#4E5562' ) . ';
				--motors-card-options-color-super-low-alpha: ' . Colors::value( 'card_options_color', 0.15, '#4E5562' ) . ';
				--motors-card-btn-color: ' . Colors::value( 'card_btn_color', -1.0, '#1280DF' ) . ';
				--motors-card-popup-hover-bg-color: ' . Colors::value( 'card_popup_hover_bg_color', -1.0, '#f9f9f9' ) . ';
				--motors-card-popup-border-color: ' . Colors::value( 'card_title_color', 0.15, '#11182706' ) . ';
				--motors-card-btn-color-lowalpha: ' . Colors::value( 'card_btn_color', 0.6 ) . ';

				--motors-filter-bg-color: ' . Colors::value( 'filter_bg_color', -1.0, '#ffffff' ) . ';
				--motors-filter-text-color: ' . Colors::value( 'filter_text_color', -1.0, '#010101' ) . ';
				--motors-filter-text-color-lowalpha: ' . Colors::value( 'filter_text_color', 0.5 ) . ';
				--motors-filter-text-color-highalpha: ' . Colors::value( 'filter_text_color', 0.8 ) . ';
				--motors-filter-text-color-super-lowalpha: ' . Colors::value( 'filter_text_color', 0.2 ) . ';
				--motors-filter-field-bg-color: ' . Colors::value( 'filter_field_bg_color', -1.0, '#ffffff' ) . ';
				--motors-filter-field-text-color: ' . Colors::value( 'filter_field_text_color', -1.0, '#010101' ) . ';
				--motors-filter-field-text-color-lowalpha: ' . Colors::value( 'filter_field_text_color', 0.5 ) . ';
				--motors-filter-field-text-color-highalpha: ' . Colors::value( 'filter_field_text_color', 0.8 ) . ';
				--motors-filter-field-text-color-super-lowalpha: ' . Colors::value( 'filter_field_text_color', 0.2 ) . ';
				--motors-filter-field-text-color-secondary: ' . Colors::value( 'filter_text_color_secondary', -1.0, '#E9E9E9' ) . ';
				--motors-filter-field-text-color-secondary-lowalpha: ' . Colors::value( 'filter_text_color_secondary', 0.5 ) . ';
				--motors-filter-field-text-color-secondary-highalpha: ' . Colors::value( 'filter_text_color_secondary', 0.8 ) . ';
				--motors-filter-field-text-color-secondary-super-lowalpha: ' . Colors::value( 'filter_text_color_secondary', 0.2 ) . ';
				--motors-filter-field-border-color: ' . Colors::value( 'filter_field_text_color', 0.2 ) . ';
				--motors-filter-field-border-color-lowalpha: ' . Colors::value( 'filter_field_text_color', 0.1 ) . ';
				--motors-filter-field-link-color: ' . Colors::value( 'filter_field_link_color', -1.0, '#1280DF' ) . ';
				--motors-filter-field-link-color-lowalpha: ' . Colors::value( 'filter_field_link_color', 0.5 ) . ';
				--motors-filter-field-link-color-highalpha: ' . Colors::value( 'filter_field_link_color', 0.8 ) . ';
				--motors-filter-field-link-color-super-lowalpha: ' . Colors::value( 'filter_field_link_color', 0.2 ) . ';
			}
		';

		$colors_css .= ':root ';

		if ( $active_elementor_kit ) {
			$colors_css .= '.elementor-kit-' . $active_elementor_kit . ' ';
		}

		$colors_css .= '{' . PHP_EOL . Colors::elementor_global_vars_css() . PHP_EOL . '}';

		wp_add_inline_style( 'motors-style', $colors_css );
	}
}

add_action( 'wp_enqueue_scripts', 'init_motors_root_colors' );

if ( ! function_exists( 'mvl_enqueue_header_scripts_styles' ) ) {
	function mvl_enqueue_header_scripts_styles( $file_name ) {
		if ( is_array( $file_name ) ) {
			foreach ( $file_name as $id ) {
				if ( wp_style_is( $id, 'registered' ) && ! wp_style_is( $id, 'enqueued' ) ) {
					wp_enqueue_style( $id );
				}

				if ( wp_script_is( $id, 'registered' ) && ! wp_script_is( $id, 'enqueued' ) ) {
					wp_enqueue_script( $id );
				}
			}
		} else {
			if ( ! wp_style_is( $file_name, 'enqueued' ) ) {
				wp_enqueue_style( $file_name );
			}

			if ( ! wp_script_is( $file_name, 'enqueued' ) ) {
				wp_enqueue_script( $file_name );
			}
		}
	}
}

//Colors from settings for tinymce textarea on frontend
if ( ! function_exists( 'mvl_tinymce_custom_colors' ) ) {
	function mvl_tinymce_custom_colors( $init_array ) {
		if ( ! is_admin() ) {
			wp_enqueue_style( 'motors-tinymce' );

			$bg_color                    = Colors::value( 'bg_color' );
			$text_color                  = Colors::value( 'text_color' );
			$init_array['content_style'] = 'body { background-color: ' . $bg_color . '; color: ' . $text_color . '; }';
		}

		return $init_array;
	}
}

add_filter( 'teeny_mce_before_init', 'mvl_tinymce_custom_colors' );
add_filter( 'tiny_mce_before_init', 'mvl_tinymce_custom_colors' );
