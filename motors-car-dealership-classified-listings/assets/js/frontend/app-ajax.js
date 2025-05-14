(function($) {
    $(document).on('click', '#buy-car-online', function(e) {
        e.preventDefault();

        var thisBtn = $(this);

        var carId = $(this).data('id');
        var price = $(this).data('price');

        $.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            context: this,
            data: 'car_id=' + carId + '&price=' + price + '&action=stm_ajax_buy_car_online&security=' + stm_security_nonce,
            beforeSend: function () {
                thisBtn.addClass('buy-online-load');
            },
            success: function (data) {

                thisBtn.removeClass('buy-online-load');

                if (data.status == 'success') {
                    window.location = data.redirect_url;
                }
            }
        });
    });


    function loadMoreDealerCars() {
		$('body').on('click', '.stm-load-more-dealer-cars a', function (e) {
			e.preventDefault()

			if (
				$(this)
					.closest('.stm-load-more-dealer-cars')
					.hasClass('not-clickable')
			) {
				return false
			}

			var offset = $(this).attr('data-offset')
			var user_id = $(this).data('user')
			var popular = $(this).data('popular')
			var profile_page = $(this).data('profile');
			var view_type = $('#stm_user_dealer_view_type').val()
			if (!view_type) {
				view_type = 'list';
			}

			$.ajax({
				url: ajaxurl,
				data: {
					action: 'mvl_ajax_dealer_load_cars',
					offset: offset,
					user_id: user_id,
					popular: popular,
					view_type: view_type,
					profile_page: profile_page,
					security: stm_security_nonce,
				},
				method: 'POST',
				dataType: 'json',
				context: this,
				beforeSend: function () {
					$(this)
						.closest('.stm-load-more-dealer-cars')
						.addClass('not-clickable')
				},
				success: function (data) {
					$(this)
						.closest('.stm-load-more-dealer-cars')
						.removeClass('not-clickable')
					if (data.html) {
						$(this)
							.closest('.tab-pane')
							.find('.car-listing-row')
							.append(data.html)
						$(this)
							.closest('.stm-user-public-listing')
							.find('.car-listing-row')
							.append(data.html)
						$(this)
							.closest('.stm-user-private-main')
							.find('.car-listing-row')
							.append(data.html)
					}
					if (data.new_offset) {
						$(this).attr('data-offset', data.new_offset)
					}
					if (data.button == 'hide') {
						$(this).closest('.stm-load-more-dealer-cars').slideUp()
						$(this)
							.closest('.tab-pane')
							.find('.row-no-border-last')
							.removeClass('row-no-border-last')
					}
				},
			})
		})
	}
	loadMoreDealerCars();
    if (window.location.search.includes('add-to-cart')) {
        var newUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }

	$('#sort_by_select').on('change', function () {
		var sortBy = $(this).val()
		var user_id = $(this).data('user')
		var posts_per_page = $(this).data('posts-per-page')
		var data = {
			action: 'stm_sort_listings',
			sort_by: sortBy,
			user_id: user_id,
			page: 1,
			posts_per_page: posts_per_page,
		}
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: data,
			success: function (response) {
				if (response.success) {
					$('.car-listing-row').html(response.data.listings_html)
					$('.pagination-container').html(response.data.pagination_html)
					$('#sort_by_select').data('page', 1)

					if (response.data.show_more_button) {
						$('.stm-load-more-dealer-cars').show()
					} else {
						$('.stm-load-more-dealer-cars').hide()
					}
				}
			},
		})
	})

	$(document).on('click', '.pagination-container a', function (e) {
		e.preventDefault()
		var page = $(this).attr('href').split('page=')[1]
		var sortBy = $('#sort_by_select').val()
		var user_id = $('#sort_by_select').data('user')
		var posts_per_page = $('#sort_by_select').data('posts-per-page')
		var data = {
			action: 'stm_sort_listings',
			sort_by: sortBy,
			user_id: user_id,
			page: page,
			posts_per_page: posts_per_page,
		}
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: data,
			success: function (response) {
				if (response.success) {
					$('.car-listing-row').html(response.data.listings_html)
					$('.pagination-container').html(response.data.pagination_html)
					$('#sort_by_select').data('page', page)
				}
			},
		})
	})

    $(document).on('submit', '#stm_new_password', function() {
        var password = $(this).val();
        var messageDiv = $(this).closest('.form-group').find('.stm-validation-message');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'stm_validate_password',
                password: password,
                security: stm_security_nonce
            },
            success: function(response) {
                if (!response.valid) {
                    messageDiv.text(response.message).show();
                } else {
                    messageDiv.hide();
                }
            }
        });
    });

    $(document).on('submit', '.stm_password_recovery', function(e) {
        var password = $('#stm_new_password').val();
		var messageDiv = $(this).find('.stm-validation-message');
        
        if (password.length < 8) {
            e.preventDefault();
            messageDiv.text(stm_i18n.mvl_password_validation).show();
            return false;
        }
        
        return true;
    });

})(jQuery)