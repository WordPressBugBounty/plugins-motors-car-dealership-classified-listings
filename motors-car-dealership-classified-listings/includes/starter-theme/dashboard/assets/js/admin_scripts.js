(function ($) {
    let adminAjaxUrl = null;

    $(document).ready(function () {
        /** Set ajax url value **/
        if( typeof mst_starter_theme_data.mst_admin_ajax_url !== 'undefined'
            &&  mst_starter_theme_data.hasOwnProperty('mst_admin_ajax_url') ) {
            adminAjaxUrl = mst_starter_theme_data.mst_admin_ajax_url;
        }
        /** show step 2 **/
        $('#loader').on('click', function (e) {
            e.preventDefault();
            $('#loader .installing').css('display','inline-block');
            $('#loader span').html('Updating ');
            $('#loader').addClass("updating");
            $.ajax({
                url: adminAjaxUrl,
                dataType: 'json',
                context: this,
                method: 'POST',
                data: {
                    action: 'stm_update_starter_theme',
                    slug: 'mst-starter-theme',
                    type: 'theme',
                    nonce: starter_theme_nonces['stm_update_starter_theme'],
                },
                complete: function (data) {
                    $('#loader .installing').css('display','none');
                    $('#loader .downloaded').css('display','inline-block');
                    $('#loader span').html('Successfully Updated');
                    $('#loader').css('pointer-events','none');
                    $('#loader').css('cursor','default');
                }
            });
        });
    });

    //Wizard scroll to contacts
    $('.mst-starter-info-box-tabs a').click(function(e) {
      const href = $(this).attr('href')

      if (href.startsWith('#') && href.length > 1) {
        const target = $(href);
        if (target.length) {
          e.preventDefault();
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 500);
        }
      }
    });

    //Wizard tabs
    $('.mst-starter-system-status, .mst-starter-change-log').hide();

    $('.mst-starter-info-box-tabs .mst-starter-templates-tab').click(function(e) {
      const href = $(this).attr('href');

      if (href === '#') {
        e.preventDefault();
      }

      $('.mst-starter-info-box-tabs a').removeClass('active');
      $('.mst-starter-templates, .mst-starter-system-status, .mst-starter-change-log').hide();
      $(this).addClass('active');

      if ($(this).hasClass('m_s_t-starter-templates')) {
        $('.mst-starter-templates').show();
      } else if ($(this).hasClass('m_s_t-starter-system-status')) {
        $('.mst-starter-system-status').show();
      } else if ($(this).hasClass('m_s_t-starter-changelog')) {
        $('.mst-starter-change-log').show();
      }
    });

    $('.mst-starter-accordion-content.active').show()

    $('.mst-starter-accordion-header').click(function () {
      if ($(this).hasClass('active')) {
        return;
      }

      $('.mst-starter-accordion-header.active').removeClass('active');
      $('.mst-starter-accordion-content.active').removeClass('active').slideUp();

      $(this).addClass('active');
      $(this).next('.mst-starter-accordion-content').addClass('active').slideDown();
    });
})(jQuery);