
jQuery(document).ready(function ($) {
    var mediaUploader;

    $(document).on('click', '.mvl-add-media-btn', function (e) {
        e.preventDefault();

        var button = $(this);
        var inputField = button.closest('.mvl-listing-manager-field-input-wrapper').find('input[type="hidden"]');

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: stm_vehicles_listing.select_image,
            button: {
                text: stm_vehicles_listing.use_this_image
            },
            multiple: false
        });

        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();

            inputField.val(attachment.id);

            button.removeClass('mvl-add-media-btn').addClass('mvl-delete-btn')
                .attr('id', 'mvl-delete-img-term')
                .html('<i class="motors-icons-mvl-trash"></i> ' + stm_vehicles_listing.delete_image)
                .attr('title', attachment.filename);
        });

        mediaUploader.open();
    });

    $(document).on('click', '#mvl-delete-img-term', function (e) {
        e.preventDefault();
        var button = $(this);
        var inputField = button.closest('.mvl-listing-manager-field-input-wrapper').find('input[type="hidden"]');

        inputField.val('');

        button.removeClass('mvl-delete-btn').addClass('mvl-add-media-btn')
            .html('<i class="fas fa-plus"></i> ' + stm_vehicles_listing.add_image)
            .removeAttr('title');
    });

});