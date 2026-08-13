"use strict";
(function ($) {
    function formatOption(data) {
        if (!data.id) {
            return $('<span class="mvl-placeholder">' + data.text + '</span>');
        }
        return data.text;
    }

    function formatLocationOption(data, withIcon) {
        if (!data.id) {
            return $('<span class="mvl-placeholder">' + data.text + '</span>');
        }

        var text = $('<span class="mvl-listing-manager-select2-result__text"></span>').text(data.text);
        var $item = $('<span class="mvl-listing-manager-select2-result-with-icon"></span>');

        if (withIcon) {
            $item.append('<i class="motors-icons-location-pin mvl-listing-manager-select2-location-icon" aria-hidden="true"></i>');
        }

        return $item.append(text);
    }

    function initSelect2() {
        function dispatchNativeChange(select) {
            var event;

            if (typeof Event === 'function') {
                event = new Event('change', { bubbles: true });
            } else {
                event = document.createEvent('HTMLEvents');
                event.initEvent('change', true, false);
            }

            select.dispatchEvent(event);
        }

        $('select.mvl-listing-manager-field-select').each(function() {
            var $select = $(this);
            var $dropdownParent = $select.closest('.mvl-listing-manager-content-body-page-option-wrapper');

            if (!$dropdownParent.length) {
                $dropdownParent = $select.closest('.mvl-listing-manager-content-body-page');
            }

            if (!$dropdownParent.length) {
                $dropdownParent = $('body');
            }

            var isRentalLocation = $select.is('[data-mvl-rental-location-select]');

            $select.select2({
                width: '100%',
                minimumResultsForSearch: 10,
                dropdownParent: $dropdownParent,
                placeholder: function() {
                    return $(this).find('option:first').text();
                },
                allowClear: false,
                templateResult: isRentalLocation ? function (data) {
                    return formatLocationOption(data, true);
                } : formatOption,
                templateSelection: isRentalLocation ? function (data) {
                    return formatLocationOption(data, true);
                } : formatOption
            });
        }).on('select2:open', function() {
            $(this).parent().find('.select2-selection__arrow').addClass('select2-selection__arrow--open');
        }).on('select2:close', function() {
            $(this).parent().find('.select2-selection__arrow').removeClass('select2-selection__arrow--open');
        }).on('select2:select select2:unselect select2:clear', function() {
            dispatchNativeChange(this);
        });

        $('.mvl-options-popup-container select').select2({
            width: '100%',
            minimumResultsForSearch: 10,
            dropdownParent: $('.mvl-options-popup-container'),
            placeholder: function() {
                return $(this).find('option:first').text();
            },
            allowClear: false,
            templateResult: formatOption,
            templateSelection: formatOption
        });
    }

    window.initSelect2 = initSelect2;

    $(document).ready(function () {
        initSelect2();
    });
})(jQuery);
