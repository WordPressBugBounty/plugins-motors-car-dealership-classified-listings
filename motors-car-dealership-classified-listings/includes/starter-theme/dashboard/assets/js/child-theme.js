(function ($) {
  $(document).ready(function () {
    $(document).on('click', '.mst-starter-wizard__button-install-child', function () {
      $('.mst-starter-wizard__progress-wrap').show();
      $('.mst-starter-wizard__progress-bar-fill').css('width', '0%');
      $('.mst-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 500);
      $('.mst-starter-wizard__button-box').hide();

      $.ajax({
        url: mst_starter_theme_data.mst_admin_ajax_url,
        type: 'POST',
        data: {
          action: 'mvl_motors_starter_child_theme_install',
          nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
        },
        success: function (response) {
          $('.mst-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 500, function () {
            $.ajax({
              url: mst_starter_theme_data.mst_admin_ajax_url,
              type: 'POST',
              data: {
                action: 'mvl_motors_starter_template',
                nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
                template: 'finish',
              },
              success: function (response) {
                $('.mst-starter-wizard__wrapper').html(response);
              },
              error: function () {
                console.error('404 error');
              },
            });
          });
        },
        error: function () {
          $('.mst-starter-wizard__progress-bar-fill').css('width', '100%');
          $('.mst-starter-wizard__progress-wrap').fadeOut();
          console.error('404 error');
        },
      });
    });
  });
}
)(jQuery);
