LightGallery = {
    settings: {
        selector: 'this',
        mode: 'lg-slide',
        cssEasing: 'ease',
        easing: 'linear',
        speed: 600,
        height: '100%',
        width: '100%',
        addClass: '',
        startClass: 'lg-start-zoom',
        backdropDuration: 150,
        hideBarsDelay: 6000,
        useLeft: false,
        closable: true,
        loop: true,
        escKey: true,
        keyPress: true,
        controls: true,
        slideEndAnimatoin: true,
        hideControlOnEnd: false,
        mousewheel: true,
        getCaptionFromTitleOrAlt: true,
        appendSubHtmlTo: '.lg-sub-html',
        subHtmlSelectorRelative: false,
        preload: 1,
        showAfterLoad: true,
        nextHtml: '',
        prevHtml: '',
        index: false,
        iframeMaxWidth: '100%',
        download: true,
        counter: true,
        appendCounterTo: '.lg-toolbar',
        swipeThreshold: 50,
        enableSwipe: true,
        enableDrag: true,
        dynamic: true,
        dynamicEl: [],

        videoMaxWidth: '100%',
        autoplayFirstVideo: false,

        thumbnail: true,
        animateThumb: true,
        currentPagerPosition: 'middle',
        thumbWidth: 100,
        thumbHeight: '80px',
        thumbContHeight: 100,
        thumbMargin: 5,
        exThumbImage: false,
        showThumbByDefault: true,
        toogleThumb: true,
        pullCaptionUp: true,
        enableThumbDrag: true,
        enableThumbSwipe: true,
        loadYoutubeThumbnail: true,
        youtubeThumbSize: 1,
        loadVimeoThumbnail: true,
        vimeoThumbSize: 'thumbnail_small',
        loadDailymotionThumbnail: true
    },
    galleries: {},
    init: function () {
        let $items = jQuery('[data-gallery]');
        if ($items.length) {
            $items.each(function () {
                let $item = jQuery(this);
                let galleryId = $item.data('gallery');
                let data = {
                    src: $item.data('lg-src'),
                    thumb: $item.data('lg-thumb'),
                    subHtml: $item.data('sub-html'),
                    index: $item.data('index')
                };

                if (LightGallery.galleries[galleryId]) {
                    if (!LightGallery.galleries[galleryId].includes(data)) {
                        LightGallery.galleries[galleryId].push(data);
                    }
                } else {
                    LightGallery.galleries[galleryId] = [data];
                }
            });

            jQuery('body').on('click', '[data-gallery]', function () {
                let $item = jQuery(this);
                let galleryId = $item.data('gallery');

                LightGallery.settings.dynamicEl = LightGallery.uniquize(LightGallery.galleries[galleryId]);
                LightGallery.settings.index = $item.data('index');
                $item.lightGallery(LightGallery.settings);
            });
        }
    },
    uniquize: function (array) {
        return array.filter((item, index, self) =>
            index === self.findIndex((t) => t.src === item.src && t.index === item.index)
        );
    }
};

LightGallery.init();