;(function ($) {
	function initSelect2() {
		$('select[data-elementor-widget-class="inventory-sort-by"]')
			.select2({
				minimumResultsForSearch: Infinity,
			})
			.on('select2:open', function () {
				widgetClass($(this))
			})
			.on('select2:close', function () {
				removeWidgetClass($(this))
			})
	}

	function widgetClass(selectElement) {
		var parentContainer = selectElement.closest('[data-elementor-widget-class]')
		var widgetClass = parentContainer.data('elementor-widget-class')
		if (widgetClass) {
			$('.select2-dropdown--below .select2-results').addClass(widgetClass)
			$('.select2-dropdown--below').addClass(widgetClass)
		}
	}

	function removeWidgetClass(selectElement) {
		var parentContainer = selectElement.closest('[data-elementor-widget-class]')
		var widgetClass = parentContainer.data('elementor-widget-class')
		if (widgetClass) {
			$('.select2-dropdown--below .select2-results').removeClass(widgetClass)
			$('.select2-dropdown--below').removeClass(widgetClass)
		}
	}

	$(document).ready(function () {
		initSelect2()
	})

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/global',
			function () {
				initSelect2()
			}
		)
	})
})(jQuery)
