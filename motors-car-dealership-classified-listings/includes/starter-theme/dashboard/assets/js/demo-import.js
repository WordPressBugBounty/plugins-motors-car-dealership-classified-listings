(function ($) {
  function isDemoCheckboxChecked($checkbox) {
    let checked = $checkbox.data('checked');

    if (typeof checked === 'undefined') {
      checked = $checkbox.attr('data-checked');
    }

    if (typeof checked === 'undefined') {
      return true;
    }

    return checked === true || checked === 'true' || checked === 1 || checked === '1';
  }

  function setDemoInstallButtonState($button, isInstalling) {
    let label = $button.data('install-label');

    if (isInstalling) {
      label = $button.data('installing-label');
      $button.addClass('disabled');
      $button.attr('aria-disabled', 'true');
    } else {
      $button.removeClass('disabled');
      $button.removeAttr('aria-disabled');
    }

    $button.text(label);
  }

  $(document).ready(function () {
    let isInstalling = false;

    $(document).on('click', '.mst-starter-wizard__demo-checkbox label', function (e) {
      if ($(this).closest('.mst-starter-wizard__demo, .mst-starter-wizard__demo-checkbox').hasClass('disable-check')) {
        return;
      }

      e.preventDefault();

      const $checkbox = $(this).find('.demo-checkbox');
      const isChecked = isDemoCheckboxChecked($checkbox);

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
      if (isInstalling) {
        return;
      }

      isInstalling = true;

      const $installButton = $(this);
      const $buttonBox = $installButton.closest('.mst-starter-wizard__button-box');

      $buttonBox.removeClass('hide-install has-demo-error');
      $buttonBox.attr('aria-busy', 'true');
      setDemoInstallButtonState($installButton, true);

      $(window).off('beforeunload.mstDemoImport').on('beforeunload.mstDemoImport', function () {
        if (isInstalling) {
          return 'Demo content is still being installed. Are you sure you want to leave?';
        }
      });

      $('.mst-starter-wizard__demo').addClass('disable-check');
      $(document).off('click', '.mst-starter-wizard__demo-checkbox label');
      $buttonBox.addClass('mst-starter-wizard__button-box__hide');

      const steps = [
        {
          type: 'demo_taxonomy',
          checked: isDemoCheckboxChecked($('.mst-starter-wizard__demo[data-demo="demo-taxonomy"] .demo-checkbox')),
          animationClass: '.mst-starter-wizard__demo[data-demo="demo-taxonomy"]',
        },
        {
          type: 'demo_content',
          checked: isDemoCheckboxChecked($('.mst-starter-wizard__demo[data-demo="demo-content"] .demo-checkbox')),
          animationClass: '.mst-starter-wizard__demo[data-demo="demo-content"]',
        },
        {
          type: 'theme_settings',
          checked: isDemoCheckboxChecked($('.mst-starter-wizard__demo[data-demo="theme-settings"] .demo-checkbox')),
          animationClass: '.mst-starter-wizard__demo[data-demo="theme-settings"]',
        },
        {
          type: 'mst_options',
          checked: isDemoCheckboxChecked($('.mst-starter-wizard__demo[data-demo="mvl_plugin_settings"] .demo-checkbox')),
          animationClass: '.mst-starter-wizard__demo[data-demo="mvl_plugin_settings"]',
        },
        {
          type: 'generate_pages',
          checked: isDemoCheckboxChecked($('.mst-starter-wizard__demo[data-demo="generate-pages"] .demo-checkbox')),
          animationClass: '.mst-starter-wizard__demo[data-demo="generate-pages"]',
        },
      ];

      let currentStep = 0;

      function processNextStep() {
        let hasError = false;

        function processNext() {
          if (currentStep >= steps.length) {
            isInstalling = false;

            $(window).off('beforeunload.mstDemoImport');
            $buttonBox.removeClass('mst-starter-wizard__button-box__hide');
            $buttonBox.removeAttr('aria-busy');
            setDemoInstallButtonState($installButton, false);

            if (hasError) {
              $buttonBox.addClass('has-demo-error');
            } else {
              $buttonBox.addClass('hide-install');
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
              } else {
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
