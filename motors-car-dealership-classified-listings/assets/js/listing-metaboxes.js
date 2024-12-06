jQuery(document).ready(function ($) {
	$('.reset-counter').on('click', function (event) {
		event.preventDefault()

		const targetId = $(this).data('target')
		const $input = $('#' + targetId)
		const $span = $('.' + targetId)

		$input.val(0)
		$span.text(0)
	})

	$('#listing_author_select').on('change', function () {
		var selectedValue = $(this).val()

		var $hiddenInput = $('#stm_car_user')
		if ($hiddenInput.length) {
			$hiddenInput.val(selectedValue)
		}
	})

	$('#car_mark_as_sold').on('change', function () {
		if ($(this).is(':checked')) {
			$('.sell-car-online-settings').slideUp()
			$('input[name="car_mark_woo_online"], input[name="stm_car_stock"]').val(
				''
			)
		} else {
			$('.sell-car-online-settings').slideDown()
		}
	})

	if ($('#car_mark_as_sold').is(':checked')) {
		$('.sell-car-online-settings').hide()
		$('input[name="car_mark_woo_online"], input[name="stm_car_stock"]').val('')
	}

	$('#car_mark_woo_online').on('change', function () {
		if ($(this).is(':checked')) {
			$('.sell-car-online-stock').slideDown()
		} else {
			$('.sell-car-online-stock').slideUp()
		}
	})

	if ($('#car_mark_woo_online').is(':checked')) {
		$('.sell-car-online-stock').show()
	} else {
		$('.sell-car-online-stock').hide()
	}


	$('.color-field').wpColorPicker()

	var mediaUploader

	$('#special_image_upload').click(function (e) {
		e.preventDefault()

		if (mediaUploader) {
			mediaUploader.open()
			return
		}

		mediaUploader = wp.media({
			multiple: false,
		})

		mediaUploader.on('select', function () {
			var attachment = mediaUploader.state().get('selection').first().toJSON()
			$('#special_image').val(attachment.id)
			$('#special_image_preview').attr('src', attachment.url).show()
			$('#special_image_remove').show()
			$('#special_image_upload').text(listing_metaboxes.choose_image)
		})

		mediaUploader.open()
	})

	$('#special_image_remove').click(function (e) {
		e.preventDefault()
		$('#special_image').val('')
		$('#special_image_preview').hide()
		$(this).hide()

		$('#special_image_upload').text(listing_metaboxes.add_image)
	})

	$('#special_car').on('change', function () {
		if ($(this).is(':checked')) {
			$('.special-badge-settings').slideDown()
		} else {
			$('.special-badge-settings').slideUp()
			$('[name="special_offer"]').val('')
			$('[name="special_image"]').val('')
			$('[name="badge_text"]').val('')
			$('[name="badge_color"]').val('')
		}
	})

	if ($('#special_car').is(':checked')) {
		$('.special-badge-settings').slideDown(0)
	} else {
		$('.special-badge-settings').slideUp(0)
	}
})
