(function ($) {
	"use strict";
	if (typeof $.uniform === "object") {
		let uniform_selectors = ':checkbox:not(#createaccount, [data-uniform="false"]), :radio:not(.input-radio, [data-uniform="false"])';

		$(uniform_selectors, $('#request-trade-in-offer')).not('#make_featured').uniform({});
	}
})(jQuery);
