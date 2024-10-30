(function ($) {
    $(document).ready(function () {
        $('#loader').on('click', function (e) {
            e.preventDefault();
            $('#loader .installing').css('display','inline-block');
            $('#loader span').html('Updating ');
            $('#loader').addClass("updating");
            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                method: 'POST',
                data: {
                    action: 'stm_update_starter_theme',
                    slug: 'motors-starter-theme',
                    type: 'theme',
                    nonce: stm_lms_starter_theme_data['stm_update_starter_theme'],
                },
                complete: function (data) {
                    $('#loader .installing').css('display', 'none')
                    $('#loader .downloaded').css('display', 'inline-block')
                    $('#loader span').html('Successfully Updated')
                    $('#loader').css('pointer-events', 'none')
                    $('#loader').css('cursor', 'default')
                },
            })
        });
    });
}
)(jQuery);