(function ($) {
  "use strict";

  $(document).ready(function () {
    var _mvl_pro_addons;
    var addons = JSON.parse((_mvl_pro_addons = mvl_addons) === null || _mvl_pro_addons === void 0 ? void 0 : _mvl_pro_addons.enabled_addons);
    $('.addon-install .addon-checkbox__label').on('click', function () {
      var addon = $(this).data('key');
      var addon_item = $(this);
      var addon_settings = $(this).closest('.addon-install').find('.addon-settings');
      addons[addon] = typeof addons[addon] === 'undefined' || addons[addon] === '' ? 'on' : '';
      $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'mvl_pro_addons',
                mvl_addons_nonce: mvl_addons['mvl_addons_nonce'],
                addons: JSON.stringify(addons),
            },
            beforeSend: function beforeSend() {
                $(addon_item).parents('.mvl-addon').addClass('loading')
            },
            success: function success() {
                $(addon_item)
                    .find('.addon-checkbox__wrapper')
                    .toggleClass('addon-checkbox__wrapper_active')
                addon_settings.toggleClass('active')
                $(addon_item).find('.addon-settings').toggleClass('active')
            },
            complete: function complete() {
                $(addon_item).parents('.mvl-addon').removeClass('loading')
            },
        })
    });
  });
})(jQuery);