(function ($) {
    let initialized = {};
    let swipers = document.querySelectorAll('[data-swiper]');

    swipers.forEach(swiper => {
        let swiperID = swiper.dataset.swiper;
        let selector = '[data-swiper="' + swiperID + '"]';
        let itemList = document.querySelectorAll(selector + ' > .swiper-wrapper > .swiper-slide');
        let settings = {
            slidesPerView: swiper.dataset.slides ? swiper.dataset.slides : 1,
            spaceBetween: 0,
            loop: true,
            autoplay: false,
            breakpoints: {},
            on: {
                init: function () {
                    requestAnimationFrame(function () {
                        if (initialized[swiperID] && itemList.length <= initialized[swiperID].params.slidesPerView) {
                            let navs = document.querySelectorAll(selector + ' > .swiper-wrapper > [data-next-of="' + swiperID + '"], ' + selector + ' > .swiper-wrapper > [data-prev-of="' + swiperID + '"], ' + selector + ' > [data-next-of="' + swiperID + '"], ' + selector + ' > [data-prev-of="' + swiperID + '"]');
                            let clones = document.querySelectorAll(selector + ' > .swiper-wrapper > .swiper-slide-duplicate');
                            initialized[swiperID].params.loop = false;
                            initialized[swiperID].update();
                            clones.forEach(clone => clone.remove());
                            navs.forEach(nav => nav.remove());
                            initialized[swiperID].update();
                        }
                    })
                }
            }
        };
        let paginationSelector = '[data-pagination-of="' + swiperID + '"]';
        let pagination = document.querySelector(paginationSelector);

        if (swiper.dataset.mobileSlides) {
            settings.slidesPerView = swiper.dataset.mobileSlides * 1;
        }

        if (swiper.dataset.tabletSlides) {
            settings.breakpoints[767] = {
                slidesPerView: swiper.dataset.tabletSlides * 1,
            };
        }

        if (swiper.dataset.slides) {
            settings.breakpoints[1024] = {
                slidesPerView: swiper.dataset.slides * 1,
            };
        }


        if (document.querySelector('[data-next-of="' + swiperID + '"]') && document.querySelector('[data-prev-of="' + swiperID + '"]')) {
            settings.navigation = {
                nextEl: '[data-next-of="' + swiperID + '"]',
                prevEl: '[data-prev-of="' + swiperID + '"]',
            };
        }

        if (pagination) {
            let paginationSettings = {
                slidesPerView: parseInt(swiper.dataset.paginationMobileSlides),
                spaceBetween: 0,
                loop: true,
                autoplay: false,
                breakpoints: {
                    767: {
                        slidesPerView: parseInt(swiper.dataset.paginationTabletSlides),
                    },
                    1024: {
                        slidesPerView: parseInt(swiper.dataset.paginationSlides),
                    }
                }
            };

            settings.on = {
                slideChange: function () {
                    if (initialized[swiperID] && initialized['pagination_of_' + swiperID]) {
                        let activeIndex = initialized[swiperID].realIndex * 1;
                        let paginationItems = pagination.querySelectorAll('[data-pagination-item-of="' + swiperID + '"][data-index="' + activeIndex + '"]');
                        let oldPaginationItems = pagination.querySelectorAll('[data-pagination-item-of="' + swiperID + '"].active');
                        let slidesPerView = initialized['pagination_of_' + swiperID].params.slidesPerView * 1;

                        initialized['pagination_of_' + swiperID].slideTo(slidesPerView + activeIndex);

                        for (let item of oldPaginationItems) {
                            item.classList.remove('active');
                        }

                        if (paginationItems.length > 0) {
                            for (let item of paginationItems) {
                                item.classList.add('active');
                            }
                        }
                    }
                }
            };

            initialized['pagination_of_' + swiperID] = new Swiper(paginationSelector, paginationSettings);

            jQuery('body').on('click', '[data-pagination-item-of="' + swiperID + '"]', function () {
                let index = $(this).data('index') * 1 + 1;
                initialized[swiperID].slideTo(index);
            });
        }

        initialized[swiperID] = new Swiper(selector, settings);


    });
})(jQuery);
