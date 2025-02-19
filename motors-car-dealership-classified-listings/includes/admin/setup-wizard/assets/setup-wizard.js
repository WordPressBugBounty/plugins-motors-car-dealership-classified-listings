jQuery(function($){

	let stepNav          = $('.mvl-welcome-nav');
	let slideContentBox  = $('#mvl-welcome-content');
	let navLinksSelector = '.mvl-welcome-nav li a, #mvl-prev-step-link, #mvl-next-step-link, .mvl-skip-btn';

	/*-------------------------------------------------*/

	$('body').on('click', '#mvl-starter-install-btn', function(e) {
		$('.install-progress').removeClass('hidden');
		$('.mvl-welcome-nav-actions').addClass('processing');
		$(this).html('Installing...');
		$('#starter-status-badge').addClass('processing');

		ajaxPromise('mvl_setup_wizard_install_starter_theme')
			.then((response) => {
				$(this).html('Installed');
				$('#mvl-setup-wizard-data input[name="use_starter"]').val('1');
				$('#starter-status-badge').addClass('done');
				$('.install-progress-bar-inside').css({width: '100%'});
				$('.install-progress-status-amount').html('100%');
				$('.install-progress-status-label').html('Installation complete');
				$('.install-progress-notice').html('');
				setTimeout(() => {
					$(this).addClass('hidden');
					$('#mvl-next-step-link').removeClass('hidden');
					$('#mvl-use-default-theme').addClass('hidden');
					$('.mvl-welcome-nav-actions').removeClass('processing');
				},1000);
			})
			.catch((error) => {
				slideContentBox.append('<p>Something went wrong.</p>');
				$(this).html($(this).attr('data-default-label'));
			})
			.finally(() => {
				$('#starter-status-badge').removeClass('processing');
			});

		return false;
	});

	$('body').on('click', '#mvl-use-default-theme', function(e) {
		$('#mvl-setup-wizard-data input[name="use_starter"]').val(0);
		return false;
	});

	/*$('body').on('click', '#mvl-skip-elementor-install', function(e) {
		$('#mvl-setup-wizard-data input[name="use_elementor"]').val(0);
		return false;
	});*/

	async function demoContentChain(){
		$('.mvl-welcome-nav-actions').addClass('processing');
		for ( i=0; i < $('.mvl-welcome-todo-item').length; i++ ) {
			const item = $('.mvl-welcome-todo-item:eq(' + i + ')');
			if ( item.hasClass('done') )
				continue;

			let action = ( item.attr('data-action') ) ? item.attr('data-action') : 'mvl_setup_wizard_mock_event';

			try {
				action = JSON.parse(action);
			} catch(e) {}

			if ( typeof action == 'string' ) {
				action = [action];
			}

			for ( j=0; j < action.length; j++ ) {
				item.removeClass('done failed').addClass('processing').find('.status-badge').removeClass('done error').addClass('processing');
				await ajaxPromise(action[j])
					.then((response) => {
						item.removeClass('processing').find('.status-badge').removeClass('processing');
						if (response.success) {
							item.addClass('done').find('.status-badge').addClass('done');
						} else {
							item.addClass('failed').find('.status-badge').addClass('error');
							$('.mvl-welcome-nav-actions').removeClass('processing');
							$('#mvl-import-demo-btn').addClass('retry-mode');
						}
						if ( $('.mvl-welcome-todo-item').not('.done, .processing, .error').length === 0 ) {
							item.find('.status-badge').removeClass('processing');
							$('.mvl-welcome-nav-actions').removeClass('processing');
							$('#mvl-import-demo-btn').removeClass('retry-mode');
						}
					})
					.catch((error) => {
						item.addClass('failed').find('.status-badge').removeClass('processing').addClass('error');
						$('.mvl-welcome-nav-actions').removeClass('processing');
						$('#mvl-import-demo-btn').addClass('retry-mode');
					});
			}

		}
	}
	$('body').on('click', '#mvl-import-demo-btn', function(e) {
		demoContentChain().then(() => {
			if ( $('.mvl-welcome-todo-item.failed').length ) {
				$('#mvl-import-demo-btn').addClass('retry-mode');
			} else {
				$('#mvl-import-demo-btn').addClass('hidden');
				$('#mvl-next-step-link').removeClass('hidden');
			}

			$('#mvl-setup-wizard-data input[name="data_imported"]').val(1);
		});
		return false;
	});

	async function installPluginsChain(){
		$('.mvl-welcome-nav-actions').addClass('processing');
		for ( i=0; i < $('.mvl-welcome-todo-item').length; i++ ) {
			const item = $('.mvl-welcome-todo-item:eq(' + i + ')');
			if ( item.hasClass('done') )
				continue;
			item.addClass('processing').find('.status-badge').removeClass('done error').addClass('processing');
			const params = ( item.attr('data-params') ) ? JSON.parse(item.attr('data-params')) : {};
			const action = ( item.attr('data-action') ) ? item.attr('data-action') : 'mvl_setup_wizard_mock_event';
			await ajaxPromise(action, params)
				.then((response) => {
					item.removeClass('processing').find('.status-badge').removeClass('processing');
					if (response.success) {
						item.addClass('done').find('.status-badge').addClass('done');
					} else {
						item.find('.status-badge').addClass('error');
						$('.mvl-welcome-nav-actions').removeClass('processing');
						$('#mvl-install-plugins-btn').addClass('retry-mode');
					}
					if ( $('.mvl-welcome-todo-item').not('.done, .processing, .error').length === 0 ) {
						$('#mvl-install-plugins-btn').addClass('hidden');
						$('#mvl-next-step-link').removeClass('hidden');
						$('.mvl-welcome-nav-actions').removeClass('processing');
						item.find('.status-badge').removeClass('processing');
						$('#mvl-setup-wizard-data input[name="use_elementor"]').val(1);
						$('.mvl-welcome-nav li a[data-step="single-listing"]').parent().removeClass('hidden');
					}
				})
				.catch((error) => {
					item.find('.status-badge').removeClass('processing').addClass('error');
					$('.mvl-welcome-nav-actions').removeClass('processing');
					$('#mvl-install-plugins-btn').addClass('retry-mode');
				});
		}
	}
	$('body').on('click', '#mvl-install-plugins-btn', function(e) {
		installPluginsChain();
		return false;
	});

	function installElementor() {
		$('#elementor-status-badge').addClass('processing');
		$('.mvl-welcome-nav-actions').addClass('processing');

		ajaxPromise( 'mvl_setup_wizard_install_plugin', {plugin: 'elementor'})
			.then((response) => {
				$('.mvl-welcome-nav-actions').removeClass('processing');
				$('#elementor-status-badge').removeClass('processing');
				if (response.success) {
					$('#elementor-status-badge').addClass('done');
					$('#mvl-install-elementor').addClass('hidden');
					$('#mvl-skip-elementor-install').addClass('hidden');
					$('#mvl-next-step-link').removeClass('hidden');
					$('#mvl-setup-wizard-data input[name="use_elementor"]').val(1);
					$('.mvl-welcome-nav li a[data-step="single-listing"]').parent().removeClass('hidden');
				}
				if (response.error) {
					$('#elementor-status-badge').addClass('error');
				}
			})
			.catch((error) => {});
	}
	$('body').on('click', '#mvl-install-elementor', function(e) {
		installElementor();
		return false;
	});

	/*----------------------------------------------------*/

	$('body').on('click', '#choose-icon-btn', function(){
		$('.stm_vehicles_listing_icons').addClass('visible');
		return false;
	});

	$('body').on('click', '.stm_vehicles_listing_icons .overlay', function(){
		$('.stm_vehicles_listing_icons').removeClass('visible');
	});

	$('body').on('click','.stm_vehicles_listing_icons .inner .stm_font_nav a',function(e){
		e.preventDefault();
		$('.stm_vehicles_listing_icons .inner .stm_font_nav a').removeClass('active');
		$(this).addClass('active');
		var tabId = $(this).attr('href');
		$('.stm_theme_font').removeClass('active');
		$(tabId).addClass('active');
	});

	$('body').on('click', '.stm_vehicles_listing_icons .inner td.stm-listings-pick-icon i', function(){
		var stmClass = $(this).attr('class').replace(' big_icon', '');
		$('#choose-icon-input').val(stmClass).trigger('change');
		$('#choose-icon-preview i').attr('class', stmClass);
		$('#choose-icon-preview').addClass('show');

		$('.stm_vehicles_listing_icons').removeClass('visible');
	});

	$('body').on('click', '.icon-preview .close', function(){
		let control = $(this).closest('.icon-control');
		control.find('#choose-icon-input').val('');
		control.find('#choose-icon-preview i').attr('class', '');
		control.find('#choose-icon-preview').removeClass('show');
	});

	/*----------------------------------------------------*/

	$('body').on('click', '#mvl-custom-field-clear-btn', customFieldFormClear);

	function customFieldFormClear(){
		document.getElementById('mvl-custom-field-form').reset();
		$('#choose-icon-input').val('');
		$('#choose-icon-preview i').attr('class', '');
		$('#choose-icon-preview').removeClass('show');
		$('.control-options-terms .term').remove();
	}

	$('body').on('click', '#mvl-custom-field-save-btn', async function(e) {
		let fieldData = $('#mvl-custom-field-form').serialize();
		let terms = [];
		$('.control-options-terms .term').each(function(){
			terms.push($(this).find('.label').text());
		});

		let message = '';
		let success = false;
		let taxonomy = null;

		if (fieldData.length) {
			await saveNewTaxonomy(fieldData).then((data) => {
				if (data.option && data.option.name) {
					message = '<strong>' + data.option.name + '</strong> field has been added successfully. You can edit it later in the <strong>Custom Fields</strong> section.';
					taxonomy = data.option.slug;
					success = true;
				} else if (data.error && data.message) {
					message = data.message;
					success = false;
				}
			});
		}

		if (terms.length && success && taxonomy) {
			let data = [];
			data['terms'] = terms;
			data['taxonomy'] = taxonomy;
			await ajaxPromise('mvl_setup_wizard_create_term', data)
				.then((response) => {
					success = !!response.success;
				});
		}

		$('#field-notice').removeClass('success error').find('.field-notice-message').html(message);

		if (success) {
			customFieldFormClear(terms);
			$('#field-notice').addClass('success');
		} else {
			$('#field-notice').addClass('error');
		}

	});

	$('body').on('click', '.field-notice .close', function(e) {
		$(e.target).parent().removeClass('success error');
	});

	$('body').on('click', '.term .del', function(e) {
		$(e.target).parent().remove();
	});

	$('body').on('click', '#new-term-add', addTermListItem);

	$('body').on('keyup', '#new-term-name', function(e) {
		if (e.which === 13) {
			addTermListItem();
		}
	});

	function addTermListItem() {
		let name = $('#new-term-name').val();
		if (name.length) {
			$('#new-term-name').val('');
			let newItem = $('<div class="term">' +
				'<span class="label">' + name + '</span>' +
				'<span class="del"></span>' +
				'</div>');
			newItem.insertBefore('.control-options-terms .no-terms');
		}
	}

	async function saveNewTaxonomy(fieldData) {
		return new Promise((resolve, reject) => {

			let action = 'stm_listings_add_new_option';

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: fieldData + '&action=' + action + '&security=' + addOpt,
				context: this,
				beforeSend: function() {

				},
				success: function(data) {
					resolve(data);
				},
				error: function(error) {
					reject(error);
				}
			});

		});
	}

	/*----------------------------------------------*/

	async function toStep(step, target){

		const saveBeforeLoad = true;

		let data = {step: step};

		if (saveBeforeLoad) {
			let stepData = collectStepData();
			data = {...data, ...stepData};
		}

		slideContentBox.addClass('loading');
		stepNav.addClass('loading');

		ajaxPromise('mvl_setup_wizard_load_step', data)
			.then((response) => {
				if ( response['output'] ) {
					slideContentBox.html(response['output']);
					let activeIndex = $('.mvl-welcome-nav ul li a[data-step="' + step + '"]').parent().index();
					if (activeIndex > -1) {
						$('.mvl-welcome-nav ul li').each(function () {
							let _item = $(this);
							_item.removeClass('active done');
							if (_item.index() < activeIndex) {
								_item.addClass('done');
							} else if (_item.index() === activeIndex) {
								_item.addClass('active');
							}
						});
					}
					if (target) {
						history.pushState('', '', target);
					}
				}
			})
			.catch((error) => {
				slideContentBox.append('<p>Something went wrong.</p>');
			})
			.finally(() => {
				slideContentBox.removeClass('loading').addClass('loaded');
				stepNav.removeClass('loading');
			});

	}

	function collectStepData() {
		let result = {};

		if ( $('#mvl-setup-wizard-data form').length ) {
			let data = $('#mvl-setup-wizard-data form').serializeArray();
			result = data.reduce(function (newResult, next) {
				newResult['mvl_data_' + next.name] = next.value;
				return newResult;
			}, {});
		}

		if ( $('#mvl-settings-form').length ) {
			let settings = $('#mvl-settings-form').serializeArray();
			settings = settings.reduce(function(newResult, next){
				newResult['mvl_setting_' + next.name] = next.value;
				return newResult;
			}, {});
			$('#mvl-settings-form input[type="checkbox"]').each(function(){
				settings['mvl_setting_' + this.name] = this.checked;
			});

			result = {...result, ...settings};
		}

		return result;
	}

	$('body').on('click', navLinksSelector, function(e) {
		if ( $(this).parent().hasClass('active') )
			return false;

		if ( ! this.hasAttribute('data-step') )
			return true;

		let _step = this.getAttribute('data-step');
		let _target = ( this.hasAttribute('href') && this.getAttribute('href') !== '#' ) ? this.getAttribute('href') : null;
		toStep(_step, _target);

		return false;
	});

	async function ajaxPromise(action, data = []) {
		let data_params = '';
		data_params = {
			action: action,
			security: security.ajax_nonce,
			...data,
		}

		return new Promise((resolve, reject) => {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: data_params,
				dataType: 'json',
				success: (response) => resolve(response),
				error: (error) => {
					reject(error);
				},
			});
		});
	}

	window.addEventListener('popstate', function(e){
		console.log('popstate', e.currentTarget.location.href, e.currentTarget.location.search);
	});

});

