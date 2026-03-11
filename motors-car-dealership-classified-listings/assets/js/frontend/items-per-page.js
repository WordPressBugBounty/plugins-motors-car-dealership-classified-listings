(function ($) {
	'use strict';

	$(document).on('click', '.stm_per_page', function (e) {
		e.stopPropagation();

		var $trigger = $(this);
		var $list    = $trigger.find('ul');
		var open     = ! $trigger.hasClass('active');

		if ( open ) {
			$trigger.removeClass('drop-up');
			$list.addClass('activated');

			var list_height = $list.outerHeight();
			var offset      = $trigger.offset();
			var top         = offset ? offset.top - $(window).scrollTop() : 0;
			var space_below = window.innerHeight - top - $trigger.outerHeight();

			$list.removeClass('activated');

			if ( space_below < list_height ) {
				$trigger.addClass('drop-up');
			}
		} else {
			$trigger.removeClass('drop-up');
		}

		$trigger.toggleClass('active');
		$list.toggleClass('activated');
	});

	$(document).on('click', function () {
		$('.stm_per_page').removeClass('active drop-up');
		$('.stm_per_page ul').removeClass('activated');
	});
})(jQuery);