(function ($) {
  $(document).ready(function () {
    let isInstalling = false;

    $(document).on('click', '.mst-starter-wizard__button', function () {
      $('.mst-starter-wizard__button-box').addClass('mst-starter-wizard__button-box__hide');
      const $pluginItems = $('.mst-starter-wizard__plugin');
      let currentIndex = 0;

      isInstalling = true;

      function processNextPlugin() {
        if (currentIndex >= $pluginItems.length) {
          isInstalling = false;
          $('.mst-starter-wizard__button-box').removeClass('mst-starter-wizard__button-box__hide').addClass('hide-install');
          return;
        }
        console.log(3);
        const $currentPlugin = $pluginItems.eq(currentIndex);
        const pluginSlug = $currentPlugin.data('plugin');

        if (pluginSlug) {
          $currentPlugin.addClass('mst-starter-wizard__plugin-load');

          $.ajax({
            url: mst_starter_theme_data.mst_admin_ajax_url,
            type: 'POST',
            data: {
              action: 'mvl_motors_starter_plugins_install',
              nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
              plugin_slug: pluginSlug,
            },
            success: function (response) {
              $currentPlugin
                .removeClass('mst-starter-wizard__plugin-load')
                .addClass('mst-starter-wizard__plugin-loaded');
              $currentPlugin.find('.mst-starter-wizard__plugin-info__description').text('Activated');
              currentIndex++;
              processNextPlugin();
            },
            error: function () {
              console.error('404 error');
              currentIndex++;
              processNextPlugin();
            },
          });
        }
      }

      processNextPlugin();
    });

    $(window).on('beforeunload', function () {
      if (isInstalling) {
        return 'Plugins are still being installed. Are you sure you want to leave?';
      }
    });
  });
}
)(jQuery);
