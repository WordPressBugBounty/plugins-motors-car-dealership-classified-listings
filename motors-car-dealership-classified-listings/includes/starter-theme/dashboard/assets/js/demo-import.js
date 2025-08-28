(function ($) {
  $(document).ready(function () {
    let isInstalling = true;

    $(document).on('click', '.mst-starter-wizard__demo-checkbox label', function (e) {
      if ($(this).closest('.mst-starter-wizard__demo, .mst-starter-wizard__demo-checkbox').hasClass('disable-check')) {
        return;
      }

      e.preventDefault();

      const $checkbox = $(this).find('.demo-checkbox');
      const isChecked = $checkbox.data('checked');

      $checkbox.data('checked', !isChecked);
      $checkbox.attr('data-checked', !isChecked);

      const $resetButton = $('.mst-starter-wizard__button-reset');
      if (isChecked) {
        $resetButton.addClass('disabled');
      } else {
        $resetButton.removeClass('disabled');
      }
    });

    $(document).on('click', '.mst-starter-wizard__button-install-demo', function () {
      isInstalling = true;

      $(window).on('beforeunload', function () {
        if (isInstalling) {
          return 'Demo content is still being installed. Are you sure you want to leave?';
        }
      });

      $('.mst-starter-wizard__demo').addClass('disable-check');
      $(document).off('click', '.mst-starter-wizard__demo-checkbox label');
      $('.mst-starter-wizard__button-box').addClass('mst-starter-wizard__button-box__hide');

      const steps = [
        {
          type: 'demo_taxonomy',
          checked: $('.mst-starter-wizard__demo[data-demo="demo-taxonomy"] .demo-checkbox').data('checked'),
          animationClass: '.mst-starter-wizard__demo[data-demo="demo-taxonomy"]',
        },
        {
          type: 'demo_content',
          checked: $('.mst-starter-wizard__demo[data-demo="demo-content"] .demo-checkbox').data('checked'),
          animationClass: '.mst-starter-wizard__demo[data-demo="demo-content"]',
        },
        {
          type: 'theme_settings',
          checked: $('.mst-starter-wizard__demo[data-demo="theme-settings"] .demo-checkbox').data('checked'),
          animationClass: '.mst-starter-wizard__demo[data-demo="theme-settings"]',
        },
        {
          type: 'mst_options',
          checked: $('.mst-starter-wizard__demo[data-demo="mvl_plugin_settings"] .demo-checkbox').data('checked'),
          animationClass: '.mst-starter-wizard__demo[data-demo="mvl_plugin_settings"]',
        },
        {
          type: 'generate_pages',
          checked: $('.mst-starter-wizard__demo[data-demo="generate-pages"] .demo-checkbox').data('checked'),
          animationClass: '.mst-starter-wizard__demo[data-demo="generate-pages"]',
        },
      ];

      let currentStep = 0;

      function processNextStep() {
        let hasError = false;

        function processNext() {
          if (currentStep >= steps.length) {
            isInstalling = false;

            $('.mst-starter-wizard__button-box').removeClass('mst-starter-wizard__button-box__hide');

            if (hasError) {
              $('.mst-starter-wizard__button-box').addClass('has-demo-error');
            }

            return;
          }

          const step = steps[currentStep];
          if (!step.checked) {
            currentStep++;
            processNext();
            return;
          }

          $(step.animationClass).addClass('mst-starter-wizard__demo-load');

          $.ajax({
            url: mst_starter_theme_data.mst_admin_ajax_url,
            type: 'POST',
            data: {
              action: 'mvl_motors_starter_demo_install',
              nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
              type: step.type,
            },
            success: function (response) {
              if (response.success) {
                $(step.animationClass)
                  .removeClass('mst-starter-wizard__demo-load')
                  .addClass('mst-starter-wizard__demo-loaded')
              } else if (response.data === false) {
                $(step.animationClass)
                  .removeClass(
                    'mst-starter-wizard__demo-load mst-starter-wizard__demo-loaded'
                  )
                  .addClass('mst-starter-wizard__demo-error')
                hasError = true
              }
              currentStep++
              processNext()
            },
            error: function () {
              console.error('404 error');
              $(step.animationClass)
                .removeClass('mst-starter-wizard__demo-load')
                .addClass('mst-starter-wizard__demo-error')
              hasError = true
              currentStep++
              processNext();
            },
          });
        }

        processNext();
      }

      processNextStep();
    });
  });
})(jQuery);
