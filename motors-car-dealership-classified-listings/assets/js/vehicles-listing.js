(function($) {
	'use strict'

	$( document ).ready(
		function ($) {
			$( '.stm_edit_item' ).on(
				'click',
				function(e){
					e.preventDefault();
					var edit_item_id                               = $( this ).attr( 'data-id' );
					var edit_item_name                             = $( this ).attr( 'data-name' );
					var edit_item_plural                           = $( this ).attr( 'data-plural' );
					var edit_item_slug                             = $( this ).attr( 'data-slug' );
					var edit_item_numeric                          = $( this ).attr( 'data-numeric' );
					var edit_item_slider                           = $( this ).attr( 'data-slider' );
					var edit_item_use_on_listing                   = $( this ).attr( 'data-use-on-listing' );
					var edit_item_use_on_car_listing               = $( this ).attr( 'data-use-on-car-listing' );
					var edit_item_use_on_car_archive_listing       = $( this ).attr( 'data-use-on-car-archive-listing' );
					var edit_item_use_on_single_car_page           = $( this ).attr( 'data-use-on-single-car-page' );
					var edit_item_use_on_filter                    = $( this ).attr( 'data-use-on-car-filter' );
					var edit_item_use_on_tabs                      = $( this ).attr( 'data-use-on-tabs' );
					var edit_item_use_on_modern_filter             = $( this ).attr( 'data-use-on-car-modern-filter' );
					var edit_item_use_on_modern_filter_view_images = $( this ).attr( 'data-use-on-car-modern-filter-view-images' );
					var edit_item_use_on_filter_links              = $( this ).attr( 'data-use-on-car-filter-links' );
					var use_on_directory_filter_title              = $( this ).attr( 'data-use-on-directory-filter-title' );
					var data_number_field_affix                    = $( this ).attr( 'data-number-field-affix' );
					var edit_font                                  = $( this ).attr( 'data-font' );
					var listing_rows                               = $( this ).attr( 'data-listing-rows-numbers' );
					var listing_taxonomy_parent                    = $( this ).attr( 'data-use-listing_taxonomy_parent' );
					var enable_checkbox_button                     = $( this ).attr( 'data-enable-checkbox-button' );
					var use_in_footer_search                       = $( this ).attr( 'data-use-in-footer-search' );

					$( '#listing_taxonomy_parent' ).val( listing_taxonomy_parent );
					$( '#listing_cols_per_row' ).val( listing_rows );

					$( '#stm_edit_item_single_name' ).val( edit_item_name );
					$( '#stm_edit_item_plural_name' ).val( edit_item_plural );
					$( '#stm_edit_item_slug' ).val( edit_item_slug );

					$( '#stm_number_field_affix' ).val( data_number_field_affix );
					$( '#stm_old_slug' ).val( edit_item_slug );
					$( '.stm_edit_item_wrap #stm_edit_item_id' ).val( edit_item_id );

					if ( edit_item_numeric == 1 ) {
						$( '#stm_edit_item_numeric' ).prop( 'checked', true );
					} else {
						$( '#stm_edit_item_numeric' ).prop( 'checked', false );
					}

					if ( edit_item_slider == 1 ) {
						$( '#stm_edit_item_slider' ).prop( 'checked', true );
					} else {
						$( '#stm_edit_item_slider' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_listing == 1 ) {
						$( '#use_on_single_listing_page' ).prop( 'checked', true );
					} else {
						$( '#use_on_single_listing_page' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_car_listing == 1 ) {
						$( '#use_on_car_listing_page' ).prop( 'checked', true );
					} else {
						$( '#use_on_car_listing_page' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_car_listing == 1 ) {
						$( '#use_on_car_listing_page' ).prop( 'checked', true );
					} else {
						$( '#use_on_car_listing_page' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_car_archive_listing == 1 ) {
						$( '#use_on_car_archive_listing_page' ).prop( 'checked', true );
					} else {
						$( '#use_on_car_archive_listing_page' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_single_car_page == 1 ) {
						$( '#use_on_single_car_page' ).prop( 'checked', true );
					} else {
						$( '#use_on_single_car_page' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_filter == 1 ) {
						$( '#use_on_car_filter' ).prop( 'checked', true );
					} else {
						$( '#use_on_car_filter' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_tabs == 1 ) {
						$( '#use_on_tabs' ).prop( 'checked', true );
					} else {
						$( '#use_on_tabs' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_modern_filter == 1 ) {
						$( '#use_on_car_modern_filter' ).prop( 'checked', true );
					} else {
						$( '#use_on_car_modern_filter' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_modern_filter_view_images == 1 ) {
						$( '#use_on_car_modern_filter_view_images' ).prop( 'checked', true );
					} else {
						$( '#use_on_car_modern_filter_view_images' ).prop( 'checked', false );
					}

					if ( edit_item_use_on_filter_links == 1 ) {
						$( '#use_on_car_filter_links' ).prop( 'checked', true );
					} else {
						$( '#use_on_car_filter_links' ).prop( 'checked', false );
					}

					if ( use_on_directory_filter_title == 1 ) {
						$( '#use_on_directory_filter_title' ).prop( 'checked', true );
					} else {
						$( '#use_on_directory_filter_title' ).prop( 'checked', false );
					}

					if (typeof edit_font != 'undefined') {
						$( '#stm-edit-picked-font-icon' ).val( edit_font );
						$( '.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview' ).html( '<i class="' + edit_font + '"></i>' )
					} else {
						$( '.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview i' ).remove();
					}

					if ( enable_checkbox_button == 1 ) {
						$( '#enable_checkbox_button' ).prop( 'checked', true );
					} else {
						$( '#enable_checkbox_button' ).prop( 'checked', false );
					}

					if ( use_in_footer_search == 1 ) {
						$( '#use_in_footer_search' ).prop( 'checked', true );
					} else {
						$( '#use_in_footer_search' ).prop( 'checked', false );
					}

					$( '.stm_edit_item_wrap' ).slideDown();

					$( '.stm-new-filter-category' ).slideUp();
				}
			);

			$( '.stm_delete_item' ).on(
				'click',
				function(e){
					var confirm_delete = confirm( 'Are you sure?' );
					if ( ! confirm_delete) {
						e.preventDefault();
					}
				}
			);

			$( '.stm_close_edit_item' ).on(
				'click',
				function(e){
					e.preventDefault();
					$( '.stm_edit_item_wrap' ).slideUp();
					$( '.stm-new-filter-category' ).slideDown();
				}
			);

			//Sort
			$(
				function() {
					let ui_sortable = $( ".stm-ui-sortable" );

					ui_sortable.sortable();
					ui_sortable.disableSelection();
				}
			);

			$( ".stm-ui-sortable" ).sortable(
				{
					update: function () {
						let r = $( this ).sortable( "toArray" );
						$( '#stm_new_posts_order' ).val( r );
					}
				}
			).disableSelection();

			$( '.stm_theme_pick_font' ).on(
				'click',
				function(e){
					e.preventDefault();
					$( this ).closest( '.stm_theme_font_pack_holder' ).find( '.stm_theme_icon_font_table' ).slideToggle();
				}
			);

			$( '.stm-new-filter-category .stm-pick-icon' ).on(
				'click',
				function(e){
					e.preventDefault();
					var font = $( this ).find( 'i' ).attr( 'class' );
					$( '.stm-new-filter-category #stm-picked-font-icon' ).val( font );
					$( '.stm-new-filter-category .stm_theme_cat_chosen_icon_preview' ).html( '<i class="' + font + '"></i>' )
				}
			);

			$( '.stm_edit_item_wrap .stm-pick-icon' ).on(
				'click',
				function(e){
					e.preventDefault();
					var font = $( this ).find( 'i' ).attr( 'class' );
					$( '.stm_edit_item_wrap #stm-edit-picked-font-icon' ).val( font );
					$( '.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview' ).html( '<i class="' + font + '"></i>' )
				}
			);

			$( ".source .item" ).draggable(
				{
					revert: "invalid", appendTo: 'body', helper: 'clone',
					start: function(ev, ui){
						ui.helper.width( $( this ).width() );
					}
				}
			);

			$( ".target .empty" ).droppable(
				{
					tolerance: 'pointer',
					hoverClass: 'highlight',
					drop: function(ev, ui){
						var item = ui.draggable;
						if ( ! ui.draggable.closest( '.empty' ).length) {
							item = item.clone().draggable();
						}
						this.innerHTML = '';
						item.css( { top: 0, left: 0 } ).appendTo( this );
						$( item ).closest( '.target-unit' ).find( 'input' ).val( ui.draggable[0].dataset.key );
					},
					out: function(ev, ui) {
						var item = ui.draggable;
						$( item ).closest( '.target-unit' ).find( 'input' ).val( '' );
					},
					activate: function(ev, ui) {
					},
					create: function(ev, ui) {
					},
					deactivate: function(ev, ui) {
					},
					over: function(ev, ui) {
					}
				}
			);

			$( ".target" ).on(
				'click',
				'.closer',
				function(){
					var item = $( this ).closest( '.item' );
					item.fadeTo( 200, 0, function(){ item.remove(); } )
					$( this ).closest( '.target-unit' ).find( 'input' ).val( '' );
				}
			);

			$( document ).on(
				'click',
				'.stm-has-preview-image .stm_custom_fields__preview[data-image]',
				function(e){
					e.preventDefault();
					let $this = $( this ),
						image = $this.attr( 'data-image' );

					$( '.stm_custom_fields__image-preview' ).addClass( 'visible' ).append( '<img src="' + image + '" alt="' + $this.text() + '" />' );
				}
			);

			$( document ).on(
				'click',
				'.stm_custom_fields__image-preview .overlay',
				function(){
					$( '.stm_custom_fields__image-preview' ).removeClass( 'visible' ).find( 'img' ).remove();
				}
			);

			var noFileLabel = $( '.stm_admin_listings_fake .fake_text' ).text();

			$( '.stm_admin_listings_fake input' ).on(
				'change',
				function(){
					var file = $( this )[0].files[0];

					if ( typeof file == 'undefined' ) {
						$( '.stm_admin_listings_fake .fake_text' ).text( noFileLabel );
						$( '.stm_admin_listings_fake' ).removeClass( 'active' );
						$( '.stm_vehicles_listing_categories .stm_import_export .export_settings form button' ).attr( 'disabled', 'disabled' );
					} else {
						$( '.stm_admin_listings_fake .fake_text' ).text( file.name );
						$( '.stm_admin_listings_fake' ).addClass( 'active' );
						$( '.stm_vehicles_listing_categories .stm_import_export .export_settings form button' ).removeAttr( 'disabled' );
					}
				}
			);

			listing_categories();

		}
	);

	function listing_categories() {
		let wrapper   = '.stm_custom_fields',
			Form      = form(),
			Field     = field(),
			Accordion = accordion(),
			Modal     = modal(),
			Table     = table();

		function table() {
			let data = {
				tr: '.stm_custom_fields__table--tr',
				tbody: '.stm_custom_fields__table tbody',
				wrapper: '.stm_custom_fields__content',
				empty: '.stm_custom_fields__empty',
				per_page: '.stm_custom_fields__pagination--select',
				next: '.stm_custom_fields__button--next',
				prev: '.stm_custom_fields__button--prev',
				current: '.stm_custom_fields__button--current',
				pagination: '.stm_custom_fields__pagination',
				currentPerPage: 10,
				searchValue: '',
				page: 1,
				actions: {
					save_order: 'stm_listings_save_option_order',
					per_page: 'stm_listings_change_per_page',
					change_page: 'stm_listings_change_page',
					search: 'stm_listings_category_search',
				}
			};

			function change_per_page() {
				$( data.per_page ).on(
					'change',
					function () {
						let $wrapper = $( data.wrapper ),
							per_page = $( this ).val();

						$.ajax(
							{
								url: ajaxurl,
								type: 'POST',
								dataType: 'json',
								data: 'per_page=' + per_page + '&search=' + data.searchValue + '&action=' + data.actions.per_page + '&security=' + perPageOptions,
								context: this,
								beforeSend: function () {
									$wrapper.addClass( 'loading' );
								},
								success: function ( response ) {
									$wrapper.removeClass( 'loading' );

									data.currentPerPage = per_page;
									data.page           = 1;

									$( data.current ).text( response.page );

									pagination_show( data.page, response.pages, response.found );

									$( data.tbody ).html( response.rows );
								}
							}
						);
					}
				);
			}

			function pagination_show( $page, $pages, $found = '' ) {
				$( data.current ).text( $page );

				if ( $page === $pages ) {
					$( data.next ).addClass( 'disabled' );
				}

				if ( $page === 1 ) {
					$( data.prev ).addClass( 'disabled' );
				}

				if ( $page > 1 ) {
					$( data.prev ).removeClass( 'disabled' );
				}

				if ( $pages > $page ) {
					$( data.next ).removeClass( 'disabled' );
				}

				if ( $pages > 1 ) {
					$( data.pagination ).removeClass( 'hide' );
					$( data.per_page ).removeClass( 'hide' );
				}

				if ( 'all' === data.currentPerPage ) {
					if ( $found > 10 ) {
						$( data.per_page ).removeClass( 'hide' );
					}

					$( data.pagination ).addClass( 'hide' );
				} else if ( $found <= data.currentPerPage || $pages === 1 ) {
					$( data.pagination ).addClass( 'hide' );
				}
			}

			function pagination( $page ) {
				let $wrapper = $( data.wrapper );

				data.page = $page;

				$.ajax(
					{
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: 'page=' + $page + '&search=' + data.searchValue + '&per_page=' + data.currentPerPage + '&action=' + data.actions.change_page + '&security=' + changePageOptions,
						context: this,
						beforeSend: function () {
							$wrapper.addClass( 'loading' );
						},
						success: function ( response ) {
							$wrapper.removeClass( 'loading' );

							pagination_show( response.page, response.pages, response.found );

							$( data.tbody ).html( response.rows );
						}
					}
				);
			}

			function next_page() {
				$( data.next ).on(
					'click',
					function () {
						pagination( data.page + 1 );
					}
				)
			}

			function prev_page() {
				$( data.prev ).on(
					'click',
					function () {
						if ( data.page > 1 ) {
							pagination( data.page - 1 );
						}
					}
				)
			}

			function sort() {
				let number = 0;

				$( data.tr ).each(
					function () {
						$( this ).attr( 'data-tr', number );
						number++;
					}
				);
			}

			function dragging_init() {
				let $list = $( data.tbody );

				$list.sortable( { items: data.tr } );

				$list.disableSelection();

				$list.sortable(
					{
						stop: function ( event, ui ) {
							let $wrapper  = $( data.wrapper ),
								prevOrder = $( ui.item ).attr( 'data-tr' ),
								newOrder  = $( ui.item ).index();

							$.ajax(
								{
									url: ajaxurl,
									type: 'POST',
									dataType: 'json',
									data: 'new_order=' + Number( newOrder ) + '&prev_order=' + Number( prevOrder ) + '&action=' + data.actions.save_order + '&security=' + saveOpt,
									context: this,
									beforeSend: function () {
										$wrapper.addClass( 'loading' );
									},
									success: function () {
										$wrapper.removeClass( 'loading' );
										sort();
									}
								}
							);
						}
					}
				).disableSelection();
			}

			function dragging_refresh() {
				$( data.tbody ).sortable( "refresh" );
			}

			function search() {
				function callOnce(func, within=300, timerId=null){
					window.callOnceTimers = window.callOnceTimers || {};
					if (timerId == null) {
						timerId = func;
					}
					let timer = window.callOnceTimers[timerId];
					clearTimeout( timer );
					timer                          = setTimeout( () => func(), within );
					window.callOnceTimers[timerId] = timer;
				}

				$( '.stm_custom_fields__search--form' ).on(
					'submit',
					function ( e ) {
						e.preventDefault();
					}
				);

				$( '.stm_custom_fields__search--input' ).on(
					'input',
					function () {
						let $input   = $( this ),
							$wrapper = $( data.wrapper );

						data.page        = 1;
						data.searchValue = $input.val();

						callOnce(
							function() {
								$.ajax(
									{
										url: ajaxurl,
										type: 'POST',
										dataType: 'json',
										data: 'search=' + $input.val() + '&per_page=' + data.currentPerPage + '&action=' + data.actions.search + '&security=' + searchCategory,
										context: this,
										beforeSend: function () {
											$wrapper.addClass( 'loading' );
										},
										success: function ( response ) {
											$wrapper.removeClass( 'loading' );

											pagination_show( response.page, response.pages, response.found );

											$( data.tbody ).html( response.rows );
										}
									}
								);
							},
							1000,
							this
						);
					}
				);
			}

			return {
				data: data,
				methods: {
					dragging_init,
					dragging_refresh,
					change_per_page,
					next_page,
					prev_page,
					search,
					pagination_show,
				}
			};
		}

		function field() {
			let data = {
				el: '.stm_custom_fields__field',
				deleteEl: '[data-field-action="delete"]',
				currentIndex: '',
				currentSlug: '',
				openFormType: '',
				openFormId: '',
				openConfirm: '[data-action="delete"]',
				message: '.stm_custom_field__message',
				selectEl: '.stm_custom_fields__select',
				actions: {
					delete: 'stm_listings_delete_single_option_row',
					get: 'stm_listings_get_option',
					add: 'stm_listings_add_new_option',
					save: 'stm_listings_save_single_option_row'
				},
				methods: {
					delete: function () {
						if ( '' !== data.currentSlug ) {
							let $button = $( this );

							$.ajax(
								{
									url: ajaxurl,
									type: 'POST',
									dataType: 'json',
									data: 'slug=' + data.currentSlug + '&page=' + Table.data.page + '&per_page=' + Table.data.currentPerPage + '&action=' + data.actions.delete + '&security=' + deleteSingleOpt,
									context: this,
									beforeSend: function () {
										$button.addClass( 'loading' );
									},
									success: function ( response ) {
										$button.removeClass( 'loading' );
										Modal.methods.close();

										if ( ! response.error ) {
											$( '[data-action="cancel"]', Form.data.editForm ).trigger( 'click' );
											Form.methods.clear_form_edit();

											if ( response.rows ) {
												data.edited = false;
												if ( response.found === 0 ) {
													$( Table.data.wrapper ).addClass( 'hide' );
													$( Table.data.empty ).removeClass( 'hide' );
												}
												Table.data.page = response.page;
												Table.methods.pagination_show( response.page, response.pages, response.found );
												$( Table.data.tbody ).html( response.rows );
												Table.methods.dragging_refresh();
											}
										} else {
											$( '.stm_custom_fields__message', Form.data.editForm ).text( response.message ).removeClass( 'hide' );
										}
									}
								}
							);
						}
					},
					open: function ( $form_type ) {
						$( Form.data.empty ).fadeOut( 400 );
						$( Form.data.el + '[data-form="' + $form_type + '"]' ).slideDown(
							{
								duration: 800,
								start: function () {
									$( this ).css(
										{
											display: "flex",
											opacity: 1
										}
									)
								},
								complete: function () {
									if ( 'edit' === $form_type && Form.data.notification.display ) {
										$( Form.data.notification.el ).addClass( Form.data.notification.active );
										Form.data.notification.display = false;
									}
								}
							}
						);

						let form_type;

						if ( 'edit' === $form_type ) {
							form_type = 'add';
						} else {
							form_type = 'edit';
						}

						$( Form.data.el + '[data-form="' + form_type + '"]' ).slideUp(
							{
								duration: 400,
								start: function () {
									$( this ).css(
										{
											opacity: 0
										}
									)
								},
							}
						);
					}
				}
			};

			function bypass_select( $form ) {
				let fields = $( data.selectEl );

				if ( '' !== $form ) {
					fields = $( data.selectEl, $form );
				}

				fields.each(
					function () {
						let $this          = $( this ), list,
							parent         = $this.parents( data.el ),
							selected_key   = '',
							selected_value = '',
							wrapper_exists = $( '.stm_custom_fields__select--wrapper', parent ).length;

						if ( ! wrapper_exists ) {
							list = $(
								'<div/>',
								{
									class: 'stm_custom_fields__select--wrapper',
									'data-name': $this.attr( 'name' )
								}
							).append(
								$(
									'<div/>',
									{
										class: 'stm_custom_fields__select--selected'
									}
								)
							).append(
								$(
									'<ul/>',
									{
										class: 'stm_custom_fields__select--list'
									}
								)
							);
						}

						$( 'option', $this ).each(
							function () {
								let key  = $( this ).attr( 'value' ),
									text = $( this ).text();

								if ( key === $this.val() ) {
									selected_key   = key;
									selected_value = text;
								}

								if ( ! wrapper_exists ) {
									let selected = false;
									if ( $this.val() === key ) {
										selected = true;
									}

									$( '.stm_custom_fields__select--list', list ).append(
										$( '<li/>' ).attr( 'data-selected', selected ).attr( 'data-value', key ).append(
											text + '<i class="stm-admin-icon-check"></i>'
										)
									);
								}
							}
						);

						if ( wrapper_exists ) {
							let li = $( '.stm_custom_fields__select--list li[data-value="' + selected_key + '"]', parent );

							$( '.stm_custom_fields__select--selected', parent ).text( selected_value );
							li.attr( 'data-selected', true );
							li.siblings().attr( 'data-selected', false );
						} else {
							$( '.stm_custom_fields__select--selected', list ).text( selected_value );
							$this.after( list );
						}
					}
				);
			}

			bypass_select();

			$( document ).on(
				'click',
				'.stm_custom_fields__select--selected',
				function () {
					let $this  = $( this ),
						parent = $this.parents( '.stm_custom_fields__select--wrapper' );

					if ( ! parent.hasClass( 'show' ) ) {
						parent.addClass( 'show' );
					} else {
						parent.removeClass( 'show' );
					}
				}
			);

			$( document ).on(
				'click',
				'.stm_custom_fields__select--list li',
				function () {
					let $this   = $( this ),
						wrapper = $this.parents( '.stm_custom_fields__select--wrapper' );

					$this.attr( 'data-selected', true );
					$this.siblings().removeAttr( 'data-selected' );
					wrapper.removeClass( 'show' );
					$( '.stm_custom_fields__select[name="' + wrapper.attr( 'data-name' ) + '"]' ).val( $this.attr( 'data-value' ) ).trigger( 'change' );
					$( '.stm_custom_fields__select--selected', wrapper ).text( $this.text() );
				}
			);

			function tooltip() {
				let tooltipTriggerList        = [].slice.call( document.querySelectorAll( '[data-bs-toggle="tooltip"]' ) ),
					tooltipTriggerListPreview = [].slice.call( document.querySelectorAll( '[data-bs-toggle="tooltip-preview"]' ) );

				tooltipTriggerList.map(
					function ( tooltipTriggerEl ) {
						return new bootstrap.Tooltip( tooltipTriggerEl, { html: true, customClass: 'motors-cf-tooltip', animated: 'fade' } );
					}
				);

				tooltipTriggerListPreview.map(
					function ( tooltipTriggerEl ) {
						return new bootstrap.Tooltip( tooltipTriggerEl, { html: true, customClass: 'motors-cf-tooltip tooltip-preview', animated: 'fade' } );
					}
				);
			}

			function open_delete_confirm() {
				data.currentSlug = $( 'input[name="slug"]', $( this ).closest( 'form' ) ).val();

				Modal.methods.open( '#' + Modal.data.delete_id );
			}

			function delete_item() { /* Delete field (Category) */
				$( document ).on( 'click', data.openConfirm, open_delete_confirm );

				$( data.deleteEl, Modal.data.el ).on( 'click', data.methods.delete );
			}

			function open_add_form() { /* Open form add new field(Category) */
				data.methods.open( 'add' );
			}

			function open_edit_form( tr ) {
				let formWrapper = $( '.stm_custom_fields__form--wrapper' );

				data.currentIndex = tr.attr( 'data-tr' );

				$.ajax(
					{
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: 'index=' + data.currentIndex + '&action=' + data.actions.get + '&security=' + getOpt,
						context: this,
						beforeSend: function () {
							Form.methods.clear_form_edit();
							Form.methods.clear_form_add();
							formWrapper.addClass( 'loading' );
						},
						success: function ( response ) {
							formWrapper.removeClass( 'loading' );

							let fieldEl = $( data.el, Form.data.editForm );

							for ( let field_key in response.option ) {
								let field = $( 'input[name="' + field_key + '"], select[name="' + field_key + '"]', fieldEl );

								if ( 'checkbox' === field.attr( 'type' ) ) {
									field.attr( 'checked', ( response.option[ field_key ] === 1 ) );
								} else if ( 'select' === field.attr( 'tagName' )  ) {
									field.val( response.option[ field_key ] );
								} else if ( 'numeric' === field_key ) {
									if ( response.option[ field_key ] ) {
										$( '.stm_custom_fields__select[name="field_type"]', Form.data.editForm ).val( 'numeric' );
									} else {
										$( '.stm_custom_fields__select[name="field_type"]', Form.data.editForm ).val( 'dropdown' );
									}
								} else if ( 'radio' === field.attr( 'type' ) ) {
									let item_checked = false;

									field.each(
										function () {
											if ( $( this ).val() === response.option[ field_key ] ) {
												item_checked = true;
												$( this ).attr( 'checked', true ).trigger( 'change' );
											} else {
												$( this ).attr( 'checked', false ).trigger( 'change' );
											}
										}
									);

									if ( ! item_checked ) {
										field.eq( 0 ).attr( 'checked', true );
									}
								} else {
									field.val( response.option[ field_key ] );
								}

								if ( 'font' === field_key ) {
									let field_icon = $( '.stm_vehicles_listing_icon', fieldEl );

									if ( response.option[ field_key ] ) {
										field_icon.removeClass( 'stm_vehicles_listing_icon__empty' ).addClass( 'stm_icon_given' );
										$( '.icon i', field_icon ).attr( 'class', response.option[ field_key ] );
									} else {
										field_icon.removeClass( 'stm_icon_given' ).addClass( 'stm_vehicles_listing_icon__empty' );
										$( '.icon i', field_icon ).removeAttr( 'class' );
									}
								} else if ( 'index' === field_key ) {
									$( 'input[name="stm_vehicle_listing_row_position"]', formWrapper ).val( response.option[ field_key ] );
								} else if ( 'link' === field_key ) {
									$( '.stm-admin-button__configure', formWrapper ).attr( 'href', response.option[ field_key ] );
								}

								let dependency, dependencyField, slug = field_key, $field = field;

								if ( 'numeric' === field_key ) {
									slug   = 'field_type';
									$field = $( '.stm_custom_fields__select[name="' + slug + '"]', Form.data.editForm );
								}

								dependency      = $( '.stm_custom_fields__dependency[data-slug="' + slug + '"]', Form.data.editForm );
								dependencyField = $( Field.data.el + '[data-slug="' + slug + '"]', Form.data.editForm );

								if ( dependency.length ) {
									Form.methods.hideUseless( $field, dependency );
								}

								if ( dependencyField.length ) {
									Form.methods.hideUseless( $field, dependencyField );
								}
							}

							bypass_select( Form.data.editForm );
							data.methods.open( 'edit' );
						}
					}
				);
			}

			function open_add_confirm() {
				$( document ).on(
					'click',
					wrapper + ' [data-action="open"]',
					function () {
						if ( ! Form.data.edited ) {
							open_add_form();
						} else {
							data.openFormType = 'add';
							Modal.methods.open( '#' + Modal.data.confirm_id );
						}
					}
				);
			}

			function open_edit_confirm() { /* Open form edit field(Category) */
				$( document ).on(
					'click',
					Table.data.tr,
					function () {
						if ( ! Form.data.edited ) {
							open_edit_form( $( this ) );
						} else {
							data.openFormId   = $( this ).attr( 'data-tr' );
							data.openFormType = 'edit';
							Modal.methods.open( '#' + Modal.data.confirm_id );
						}
					}
				);
			}

			function confirmation() {
				$( document ).on(
					'click',
					'[data-field-action="confirm"]',
					function () {
						if ( 'edit' === data.openFormType ) {
							open_edit_form( $( Table.data.tr + '[data-tr="' + data.openFormId + '"]' ) );
						} else if ( 'add' === data.openFormType ) {
							open_add_form();
						}

						Form.data.edited = false;

						Modal.methods.close();
					}
				);
			}

			return {
				data: data,
				methods: {
					tooltip,
					open_edit_form,
					open_edit_confirm,
					open_add_confirm,
					confirmation,
					delete_item
				}
			};
		}

		function accordion() {
			let data = {
				item: '.stm_custom_fields__accordion--item',
				opened: 'opened',
				top: '.stm_custom_fields__accordion--top',
				content: '.stm_custom_fields__accordion--content',
				empty: '.stm_custom_fields__form--empty',
			}

			function close( item ) {
				item.removeClass( data.opened );
				$( data.content, item ).slideUp();
			}

			function open( item ) {
				item.addClass( data.opened );

				$( data.content, item ).slideDown(
					{
						start: function () {
							$( this ).css(
								{
									display: "flex"
								}
							)
						}
					}
				);
			}

			function init() {
				$( document ).on(
					'click',
					data.top,
					function () {
						let parent = $( this ).parents( data.item );

						if ( parent.hasClass( data.opened ) ) {
							close( parent );
						} else {
							open( parent );
						}
					}
				);
			}

			return {
				data: data,
				methods: {
					init,
					open,
					close,
				}
			};
		}

		function modal() {
			let data = {
				el: '.stm-admin-modal',
				overlay: '.stm-admin-modal-overlay',
				opened: 'show',
				cancel: '[data-modal-action="close"]',
				content: '.stm-admin-modal__content',
				delete_id: 'stm-admin-modal-delete-item',
				confirm_id: 'stm-admin-modal-confirm-edit',
			}

			function open( target ) {
				$( target + ', ' + data.overlay ).addClass( data.opened );
			}

			function close() {
				Field.data.openFormId   = '';
				Field.data.openFormType = '';

				$( data.el + ', ' + data.overlay ).removeClass( data.opened );
			}

			function close_init() {
				$( data.cancel ).on( 'click', close );

				$( document ).on(
					'click',
					data.el + '.' + data.opened,
					function ( event ) {
						if ( ! $( event.target ).closest( data.content ).length ) {
							close();
						}
					}
				);
			}

			return {
				data: data,
				methods: {
					close_init,
					close,
					open,
				}
			};
		}

		function form() {
			let data = {
				el: ".stm_custom_fields__form",
				empty: '.stm_custom_fields__form--empty',
				validationError: 'validation-error',
				editForm: '[data-form="edit"]',
				addForm: '[data-form="add"]',
				actions: {
					notification: 'stm_listings_form_edit_disable_notification',
				},
				notification: {
					el: '.stm-admin-notification',
					button: '.stm-admin-notification__button',
					active: 'show',
					display: false,
				},
				edited: false,
			};

			$( data.el ).on(
				'submit',
				function ( event ) {
					event.preventDefault();
					event.stopPropagation();
				}
			);

			$( data.notification.button ).on(
				'click',
				function () {
					let parentEl = $( this ).parents( data.notification.el );

					parentEl.removeClass( data.notification.active );

					if ( $( '.stm_custom_fields__checkbox', data.notification.el ).is( ':checked' ) ) {
						$.ajax(
							{
								url: ajaxurl,
								type: 'POST',
								dataType: 'json',
								data: 'disable=yes&action=' + data.actions.notification + '&security=' + disableNotification,
								context: this,
							}
						);
					}
				}
			);

			function add_field() {
				$( document ).on(
					'click',
					'.stm_custom_fields [data-action="add_new"]',
					function(){
						let button         = $( this ),
							$form          = button.closest( 'form' ),
							new_slug_input = $( 'input[name="slug"]', $form ), /* validate slug */
							slug           = new_slug_input.val(),
							regexp         = /^\s*([0-9a-zA-Z\-]*)\s*$/; /* only english letters, numbers and a hyphen is allowed */

						if ( ! regexp.test( slug )) {
							new_slug_input.parents( Field.data.el ).addClass( data.validationError );
							return false;
						} else {
							new_slug_input.parents( Field.data.el ).removeClass( data.validationError );
						}

						$.ajax(
							{
								url: ajaxurl,
								type: 'POST',
								dataType: 'json',
								data: $form.serialize() + '&per_page=' + Table.data.currentPerPage + '&action=' + Field.data.actions.add + '&security=' + addOpt,
								context: this,
								beforeSend: function () {
									button.addClass( 'loading' );

									$( Field.data.el ).removeClass( data.validationError );
									$( Field.data.message, $( Field.data.el ) ).text( '' );
								},
								success: function ( response ) {
									button.removeClass( 'loading' );

									if ( response.message ) {
										for ( let field_key in response.error ) {
											let field = $( 'input[id="' + field_key + '"], select[id="' + field_key + '"]' ).parents( Field.data.el );

											field.addClass( data.validationError );
											$( Field.data.message, field ).text( response.error[ field_key ] );
										}
									}

									if ( response.rows ) {
										data.edited = false;
										if ( response.found > 0 ) {
											$( Table.data.wrapper ).removeClass( 'hide' );
											$( Table.data.empty ).addClass( 'hide' );
										}
										Table.data.page = response.page;
										Table.methods.pagination_show( response.page, response.pages, response.found );
										$( Table.data.tbody ).html( response.rows );
										Table.methods.dragging_refresh();
										Form.methods.clear_form_add();
										$( '[data-action="cancel"]', $form ).trigger( 'click' );

										if ( typeof response.notification !== "undefined" && response.notification ) {
											Form.data.notification.display = response.notification;
										}

										if ( typeof response.added_index !== "undefined" && response.added_index >= 0 ) {
											Field.methods.open_edit_form( $( Table.data.tr + '[data-tr="' + response.added_index + '"]' ) );
										}
									}
								}
							}
						);
					}
				);
			}

			function clear_form( formEl ) {
				Field.data.currentIndex = '';

				let $form = $( formEl );

				$form.trigger( 'reset' );
				$( '.stm_vehicles_listing_icon', $form ).addClass( 'stm_vehicles_listing_icon__empty' ).removeClass( 'stm_icon_given' );
				$( '.stm-admin-button-save', $form ).prop( 'disabled', true );
				$( '.stm_custom_fields__message', $form ).text( '' ).addClass( 'hide' );
			}

			function clear_form_edit() {
				Field.data.currentIndex = '';

				let $form = $( data.editForm );

				$form.trigger( 'reset' );
				$( '.stm_vehicles_listing_icon', $form ).addClass( 'stm_vehicles_listing_icon__empty' ).removeClass( 'stm_icon_given' );
				$( '.stm-admin-button-save', $form ).prop( 'disabled', true );

				Accordion.methods.close( $( Accordion.data.item + ':not(:first-child)', $form ) );
				Accordion.methods.open( $( Accordion.data.item + ':first-child', $form ) );

				clear_form( data.editForm );
			}

			function clear_form_add() {
				clear_form( data.addForm );

				Accordion.methods.close( $( Accordion.data.item + ':not(:first-child)', data.addForm ) );
				Accordion.methods.open( $( Accordion.data.item + ':first-child', data.addForm ) );
			}

			function cancel() {
				$( document ).on(
					'click',
					'.stm_custom_fields [data-action="cancel"]',
					function () {
						$( this ).parents( data.el ).slideUp(
							{
								duration: 400,
								start: function () {
									$( this ).css(
										{
											opacity: 0
										}
									)
								}
							}
						);

						Form.data.edited = false;

						$( data.empty ).fadeIn( 800 );

						clear_form_edit();
					}
				);
			}

			function hideUseless( field, dependent ) {
				let depType  = $( field ).attr( 'type' ),
					depValue = '';

				if ( 'checkbox' === depType ) {
					depValue = $( field ).prop( 'checked' );
				} else if ( depType && 'select' === $( field ).prop( 'tagName' ) ) {
					depValue = $( field ).val();
				} else if ( 'radio' === depType ) {
					$( field ).each(
						function() {
							if ( $( this ).is( ':checked' ) ) {
								depValue = $( this ).val();
							}
						}
					);
				} else {
					depValue = $( field ).val();
				}

				if ( 'SELECT' === $( field ).prop( 'tagName' ) ) {
					if ( 'numeric' === depValue ) {
						dependent.show();
					} else {
						dependent.hide();
					}
				} else if ( depValue && 'radio' === depType ) {
					dependent.each(
						function() {
							if ( $( this ).data( 'slug' ).split( ',' ).includes( depValue ) ) {
								$( this ).show();
							} else {
								$( this ).hide();
							}
						}
					)
				} else {
					if ( depValue ) {
						dependent.show();
					} else {
						dependent.hide();
					}
				}
			}

			function watcher() {
				$( 'input, select', Form.data.el ).on(
					'change',
					function() {
						let $field           = $( this ),
							$field_name      = $field.attr( 'name' ),
							$form            = $field.closest( Form.data.el ),
							dependencyBlock  = $( '.stm_custom_fields__dependency[data-slug="' + $field_name + '"]', $form ),
							dependencyFields = $( Field.data.el + '[data-slug]', $form );

						if ( dependencyFields.length ) {
							dependencyFields.each(
								function () {
									if ( $( this ).attr( 'data-slug' ).split( ',' ).includes( $field_name ) ) {
										hideUseless( $field, $( this ) );
									}
								}
							);
						}

						if ( ! $field.closest( data.notification.el ).length ) {
							$( '.stm-admin-button-save', $form ).prop( 'disabled', false );
							data.edited = true;
						}

						if ( dependencyBlock.length ) {
							hideUseless( $field, dependencyBlock );
						}
					}
				);

				$( '.stm_vehicles_listing_icons .inner .stm_font_nav a' ).on(
					'click',
					function(e){
						e.preventDefault();
						$( '.stm_vehicles_listing_icons .inner .stm_font_nav a' ).removeClass( 'active' );
						$( this ).addClass( 'active' );
						let tabId = $( this ).attr( 'href' );
						$( '.stm_theme_font' ).removeClass( 'active' );
						$( tabId ).addClass( 'active' );
					}
				);

				/*Open/Delete icons*/
				$( document ).on(
					'click',
					'.stm_vehicles_listing_icon .stm_delete_icon',
					function(e){
						let $wrapper = $( this ).closest( '.stm_form_wrapper_icon' );

						$wrapper.find( 'input[name="font"]' ).val( '' );

						$( 'i', $wrapper ).removeAttr( 'class' );
						$( 'img', $wrapper ).removeAttr( 'class' );
						$( 'img', $wrapper ).addClass( 'stm-default-icon_' );
						$( this ).closest( '.stm_vehicles_listing_icon' ).addClass( 'stm_vehicles_listing_icon__empty' ).removeClass( 'stm_icon_given' );

						$( '.stm-admin-button-save' ).prop( 'disabled', false );
						data.edited = true;

						e.preventDefault();
						e.preventDefault();
						e.stopPropagation();
						return false;
					}
				);

				let currentTarget = '';
				$( document ).on(
					'click',
					'.stm_vehicles_listing_icon',
					function(e){
						e.preventDefault();
						$( '.stm_vehicles_listing_icons' ).addClass( 'visible' );
						currentTarget = $( this ).closest( '.stm_custom_fields__field--icon' );

						let currentVal = '.' + currentTarget.find( 'input[name="font"]' ).val().replace( ' ', '.' );
						if ( currentVal === '.' ) {
							return;
						}
						$( '.stm-listings-pick-icon' ).removeClass( 'chosen' );
						$( '.stm_vehicles_listing_icons ' + currentVal ).closest( '.stm-listings-pick-icon' ).addClass( 'chosen' );
					}
				);

				$( '.stm_vehicles_listing_icons .inner td.stm-listings-pick-icon i' ).on(
					'click',
					function(){
						let stmClass = $( this ).attr( 'class' ).replace( ' big_icon', '' );
						currentTarget.find( 'input[name="font"]' ).val( stmClass );
						currentTarget.find( '.icon i' ).attr( 'class', stmClass );

						currentTarget.find( '.stm_vehicles_listing_icon' ).removeClass( 'stm_vehicles_listing_icon__empty' ).addClass( 'stm_icon_given' );

						$( '.stm-admin-button-save' ).prop( 'disabled', false );
						data.edited = true;

						close_icons();
					}
				);

				$( '.stm_vehicles_listing_icons .overlay' ).on(
					'click',
					function(){
						close_icons();
					}
				);
			}

			function close_icons() {
				$( '.stm_vehicles_listing_icons' ).removeClass( 'visible' );
			}

			function save() {
				$( document ).on(
					'click',
					'.stm_custom_fields [data-action="save"]',
					function() {
						let $form = $( this ).closest( 'form' );

						$.ajax(
							{
								url: ajaxurl,
								type: 'POST',
								dataType: 'json',
								data: $form.serialize() + '&action=' + Field.data.actions.save + '&security=' + saveSingleOpt,
								context: this,
								beforeSend: function () {
									$form.addClass( 'loading' );
								},
								success: function ( response ) {
									$form.removeClass( 'loading' );

									if ( ! response.error ) {
										for ( let field_key in response.data ) {
											let tr = $( Table.data.tr + '[data-tr="' + response.data[ 'index' ] + '"] td[data-column="' + field_key + '"]' );

											if ( 'font' === field_key ) {
												$( '.stm_custom_fields__icon--wrapper > i', tr ).attr( 'class', response.data[ field_key ] );
											} else {
												tr.text( response.data[ field_key ] );
											}
										}

										Form.data.edited = false;
										clear_form_edit();
										$( '[data-action="cancel"]', $form ).trigger( 'click' );
									} else {
										$( '.stm_custom_fields__message', $form ).text( response.message ).removeClass( 'hide' );
									}
								}
							}
						);
					}
				);
			}

			return {
				data: data,
				methods: {
					add_field,
					cancel,
					hideUseless,
					watcher,
					save,
					clear_form_edit,
					clear_form_add,
				}
			};
		}

		Field.methods.tooltip();
		Field.methods.open_edit_confirm();
		Field.methods.open_add_confirm();
		Field.methods.confirmation();
		Field.methods.delete_item();

		Accordion.methods.init();

		Modal.methods.close_init();

		Form.methods.add_field();
		Form.methods.cancel();
		Form.methods.watcher();
		Form.methods.save();

		Table.methods.dragging_init();
		Table.methods.change_per_page();
		Table.methods.next_page();
		Table.methods.prev_page();
		Table.methods.search();
	}

}(jQuery));
