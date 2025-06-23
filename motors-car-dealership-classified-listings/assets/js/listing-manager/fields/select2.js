"use strict";
(function ($) {
    function formatOption(data) {
        if (!data.id) {
            return $('<span class="mvl-placeholder">' + data.text + '</span>');
        }
        return data.text;
    }

    function initSelect2() {
        $('select.mvl-listing-manager-field-select').select2({
            width: '100%',
            minimumResultsForSearch: 10,
            dropdownParent: $('.mvl-listing-manager-content-body-page-option-wrapper'),
            placeholder: function() {
                return $(this).find('option:first').text();
            },
            allowClear: false,
            templateResult: formatOption,
            templateSelection: formatOption
        }).on('select2:open', function() {
            $(this).parent().find('.select2-selection__arrow').addClass('select2-selection__arrow--open');
        }).on('select2:close', function() {
            $(this).parent().find('.select2-selection__arrow').removeClass('select2-selection__arrow--open');
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
