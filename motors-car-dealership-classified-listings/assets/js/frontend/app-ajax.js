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
					action: 'stm_ajax_dealer_load_cars',
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
})(jQuery)