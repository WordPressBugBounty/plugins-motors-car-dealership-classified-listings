class DealersList extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                filter_dealer: '.stm_dynamic_listing_dealer_filter_form',
            }
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        return {
            $filter_dealer: this.$element.find(selectors.filter_dealer),
        };
    }

    onInit() {
        super.onInit();

        let data = this.elements.$filter_dealer.data(),
            options = data.options,
            $ = jQuery

        let $el = $(this.elements.$filter_dealer);

        var selects = [];

        $el.find('.stm-filter-tab-selects.dealer-filter .row').each(function () {
            $(this).find('select').each(function () {
                selects.push($(this).attr('name'));
            });

            new STMCascadingSelect(this, options);
        });

        selects.forEach(function (sel) {
            const urlParams = new URLSearchParams(window.location.search);
            const myParam = urlParams.get(sel);

            if (myParam != null) {
                $el.find('.stm-filter-tab-selects.dealer-filter .row select[name=' + sel + ']').select2().val(myParam).trigger('change');
            }
        });

        $('.stm-load-more-dealers' ).on(
            'click',
            function (e) {
                e.preventDefault();

                if ($( this ).hasClass( 'not-clickable' )) {
                    return false;
                }

                var offset = $( this ).attr( 'data-offset' );

                $.ajax(
                    {
                        url: mvl_current_ajax_url,
                        dataType: 'json',
                        context: this,
                        data: $( '.stm_dynamic_listing_dealer_filter form' ).serialize() + '&offset=' + offset + '&ajax_action=stm_load_dealers_list&security=' + stm_security_nonce,
                        beforeSend: function () {
                            $( this ).addClass( 'not-clickable' );
                        },
                        success: function (data) {
                            $( this ).removeClass( 'not-clickable' );
                            if (data.user_html) {
                                $( '.dealer-search-results table tbody' ).append( data.user_html );
                            }
                            if (data.new_offset) {
                                $( '.stm-load-more-dealers' ).attr( 'data-offset', data.new_offset );
                            }
                            if (data.remove && data.remove === 'hide') {
                                $( this ).remove();
                            }
                        }
                    }
                );
            }
        );

        $( '.dealer-search-title select' ).on(
            'change',
            function () {
                $( 'input[name="stm_sort_by"]' ).val( $( this ).val() );
                $( "#stm_all_listing_tab" ).find( "form" ).trigger( 'submit' );
            }
        );
    }

}

jQuery(window).on('elementor/frontend/init', () => {
    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(DealersList, {
            $element,
        });
    };
    elementorFrontend.hooks.addAction('frontend/element_ready/motors-dealers-list.default', addHandler);
});