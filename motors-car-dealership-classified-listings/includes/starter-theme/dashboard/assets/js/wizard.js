(function ($) {
  $(document).ready(function () {
    $(document).on('click', '.mst-starter-wizard__wrapper-content li', function () {
      $('.mst-starter-wizard__wrapper-content li').removeClass('mst-starter-wizard__show-buttons');
      $(this).addClass('mst-starter-wizard__show-buttons');
    });

    $(document).on('click', '.mst-starter-wizard__button-continue', function (event) {
      const $button = $(this);
      $('.mst-starter-wizard__wrapper-content').addClass('disabled');
      $('.mst-starter-wizard__wrapper-content-preloader').removeClass('disabled');
      $.ajax({
        url: mst_starter_theme_data.mst_admin_ajax_url,
        type: 'POST',
        data: {
          action: 'mvl_motors_starter_demo_options',
          nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
          demo: $button.data('demo'),
          builder: $button.data('builder'),
        },
        success: function (response) {
          if ($button.hasClass('demo-activated')) {
            $('.mst-starter-wizard__template-popup').addClass('reinstallation');
          } else {
            motors_demo_steps($button, event);
          }
          $('.mst-starter-wizard__wrapper-content').removeClass('disabled');
          $('.mst-starter-wizard__wrapper-content-preloader').addClass('disabled');
        },
        error: function () {
          console.error('404 error');
        },
      });

      $(window).on('beforeunload', function (e) {
        const confirmationMessage =
          'If you reload the page, the installation process may be interrupted and something may go wrong. Are you sure you want to reload?'

        e.returnValue = confirmationMessage
        return confirmationMessage
      });
    });

    $('body').on('click', '.mst-starter-wizard__button-close', function (event) {
      $('.mst-starter-wizard__template-popup').attr('style', 'display: none;').removeClass('reinstallation');
    });

    $(document).on('click', '.mst-starter-wizard__button-reset', function (event) {
      if ($(this).hasClass('disabled')) {
        event.preventDefault();

        return;
      }

      motors_demo_reset($(this), event);
    });

    $(document).on('click', '.mst-starter-wizard__button', function (event) {
      if (!$(this).hasClass('mst-starter-wizard__button-continue') &&
        !$(this).hasClass('mst-starter-wizard__button-reset')) {
        motors_demo_steps($(this), event);
      }
      if ($(this).data('template') === 'finish' ||
        $(this).hasClass('mst-starter-wizard__button-install-child')) {
        $(window).off('beforeunload');
      }
      if ($(this).data('template') === 'activation' || $(this).data('template') === 'plugins') {
        $('html, body').animate({ scrollTop: 0 }, 200);
      }
    });

    $(document).on('click', '.mst-starter-wizard__button-box a', function (event) {
      if (typeof $(this).attr('href') !== 'undefined') {
        $(window).off('beforeunload');
      }
    });
  });

  function motors_demo_steps(button, event) {
    if (typeof button.attr('href') === 'undefined') {
      event.preventDefault();
    }

    let template = button.data('template');
    const steps = ['templates', 'plugins', 'demo-content', 'child-theme']
    const currentStepIndex = steps.indexOf(template);

    if (template) {
      if (currentStepIndex !== -1) {
        steps.forEach((step, index) => {
          const $stepElement = $(`.progress-step-${step}`);
          if (index < currentStepIndex) {
            $stepElement.removeClass('active').addClass('progress-step-done');
          } else if (index === currentStepIndex) {
            $stepElement.addClass('active').removeClass('progress-step-done');
          } else {
            $stepElement.removeClass('active progress-step-done');
          }
        });
      } else {
        $('.mst-starter-wizard__navigation').hide();
      }

      $('.mst-starter-wizard__wrapper').empty();

      $.ajax({
        url: mst_starter_theme_data.mst_admin_ajax_url,
        type: 'POST',
        data: {
          action: 'mvl_motors_starter_template',
          nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
          template: button.data('template'),
        },
        success: function (response) {
          $('.mst-starter-wizard__wrapper').html(response);

          if ($('.mst-starter-wizard__template-popup').length > 0) {
            $('.mst-starter-wizard__template-popup').attr('style', 'display: none;');
          }

          if (template !== 'templates' && template !== 'plugins') {
            $('.mst-starter-wizard__button-back').hide();
          } else {
            $('.mst-starter-wizard__button-back').show();
          }

          if (template === 'plugins') {
            let allActivated = true;

            $('.mst-starter-wizard__plugin').each(function () {
              if ($(this).data('status') !== 'Activated') {
                allActivated = false;
                return false;
              }
            });

            if (allActivated) {
              $('.mst-starter-wizard__button-box').addClass('hide-install');
            }
          }
        },
        error: function () {
          console.error('404 error');
        },
      });
    }
  }

  function motors_demo_reset(button, event) {
    if (typeof button.attr('href') === 'undefined') {
      event.preventDefault();

      $('.mst-starter-wizard__demo-checkbox').addClass('disable-check');
      $('.mst-starter-wizard__progress-wrap').show();
      $('.mst-starter-wizard__progress-bar-fill').css('width', '0%');
      $('.mst-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 10);
      $('.mst-starter-wizard__button-box').hide();

      $.ajax({
        url: mst_starter_theme_data.mst_admin_ajax_url,
        type: 'POST',
        data: {
          action: 'mvl_motors_starter_template_reset',
          nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
        },
        success: function (response) {
          $('.mst-starter-wizard__progress-bar-fill').animate({ width: '100%' }, 10, function () {
            $.ajax({
              url: mst_starter_theme_data.mst_admin_ajax_url,
              type: 'POST',
              data: {
                action: 'mvl_motors_starter_template',
                nonce: mst_starter_theme_data.mvl_motors_starter_plugins_nonce,
                template: 'plugins',
              },
              success: function (response) {
                $('.mst-starter-wizard__wrapper').html(response);
                let allActivated = true;

                $('.mst-starter-wizard__plugin').each(function () {
                  if ($(this).data('status') !== 'Activated') {
                    allActivated = false;
                    return false;
                  }
                });

                if (allActivated) {
                  $('.mst-starter-wizard__button-box').addClass('hide-install');
                }
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

      motors_demo_steps(button, event);
    }

  }
})(jQuery);
