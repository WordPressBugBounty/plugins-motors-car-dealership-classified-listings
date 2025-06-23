"use strict";
(function ($) {
    let $wrapper;              // Main wrapper element for the options page
    let listingId;             // Current listing ID
    let isEditMode = false;    // Flag to track if we're in edit mode
    let $editBtn;              // Edit button element
    let ajaxCache = {};        // Cache for AJAX requests to prevent duplicate calls
    let pendingRequests = {};  // Queue for pending AJAX requests
    let debounceTimer;         // Timer for debounce function
    let $document = $(document); // Cached document element
    let originalHtml;          // Original HTML content before edit mode
    let termsCache = {};       // Cache for terms
    let modalChanged = false;  // Flag to track if the modal has been changed
    let hasFieldChanged = false;  // Flag to track if the field has been changed

    function debounce(func, wait) {
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(debounceTimer);
                func(...args);
            };
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(later, wait);
        };
    }

    function throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }

    function updateDOM($element, html) {
        const fragment = document.createDocumentFragment();
        const temp = document.createElement('div');
        temp.innerHTML = html;

        while (temp.firstChild) {
            fragment.appendChild(temp.firstChild);
        }

        $element.empty().append(fragment);
    }

    /**
     * Extracts slug from name like option[slug], motors_listing[terms][slug], motors_listing[numeric][slug] and etc.
     * @param {string} name - name атрибут input/select
     * @returns {string|null} slug
     */
    function extractSlugFromName(name) {
        if (!name) return null;
        const match = name.match(/\[([^\]]+)\]$/);
        if (match && match[1]) {
            return match[1];
        }
        return name;
    }

    /**
     * Updates the preview card with new field value
     * @param {string} fieldId - ID of the field being updated
     * @param {string} value - New value for the field
     */
    function updatePreviewCard(fieldId, value) {
        if (!fieldId) return;

        const fieldName = extractSlugFromName(fieldId);
        if (!fieldName) return;
        const $previewCard = $('.mvl-listing-preview-card');
        if (!$previewCard.length) return;

        const $field = $previewCard.find('.mvl-listing-preview-card-data[data-field-id="' + fieldName + '"]');
        if (!$field.length) return;

        const $valueWrapper = $field.find('.mvl-listing-preview-card-data-value-wrapper');
        const $value = $valueWrapper.length
            ? $valueWrapper.find('.mvl-listing-preview-card-data-value')
            : $field.find('.mvl-listing-preview-card-data-value');

        if ($value.length) {
            $value.text(value);
        }
    }

    /**
     * Performs AJAX request with caching and request queue management
     * @param {string} url - AJAX endpoint URL
     * @param {object} data - Request data
     * @param {string} cacheKey - Key for caching the response
     * @returns {Promise} Promise with the AJAX response
     */
    function performAjaxRequest(url, data, cacheKey = null) {
        // Return cached data if available and caching is enabled
        if (cacheKey && ajaxCache[cacheKey] && !data.noCache) {
            return Promise.resolve(ajaxCache[cacheKey]);
        }

        // Return pending request if one exists
        if (pendingRequests[cacheKey]) {
            return pendingRequests[cacheKey];
        }

        // Create new request
        const request = new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response.success) {
                        if (cacheKey) {
                            ajaxCache[cacheKey] = response;
                        }
                        resolve(response);
                    } else {
                        reject(response);
                    }
                },
                error: function (xhr, status, error) {
                    reject({ error: error, xhr: xhr });
                }
            });
        });

        // Store request in queue
        if (cacheKey) {
            pendingRequests[cacheKey] = request;
            request.finally(() => {
                delete pendingRequests[cacheKey];
            });
        }

        return request;
    }

    /**
     * Clears the AJAX cache
     */
    function clearAjaxCache() {
        ajaxCache = {};
    }

    /**
     * Updates listing data in the preview card
     */
    function updateListingData() {
        if (!listingId) {
            return;
        }

        const cacheKey = `listing_data_${listingId}`;
        performAjaxRequest(ajaxurl, {
            action: 'listing_manager_get_form',
            nonce: nonce_get_form,
            template: 'preview-card-data',
            listing_id: listingId,
            listing_manager_page_id: 'option'
        }, cacheKey)
            .then(response => {
                if (response.data && response.data.html) {
                    const $previewCard = $('.mvl-listing-preview-card');
                    if ($previewCard.length) {
                        const $dataWrapper = $previewCard.find('.mvl-listing-preview-card-data-wrapper');
                        if ($dataWrapper.length) {
                            const $staticElements = $dataWrapper.find('.mvl-listing-preview-card-data:not([data-field-id])');
                            $dataWrapper.html(response.data.html);
                            $staticElements.each(function () {
                                $dataWrapper.prepend($(this));
                            });
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Failed to update preview card data:', error);
            });
    }

    /**
     * Initializes sortable functionality for field items
     */
    function initSortable() {
        if ($wrapper.data('ui-sortable')) {
            $wrapper.sortable('destroy');
        }

        $wrapper.sortable({
            items: '.mvl-listing-manager-field-item',
            cursor: 'move',
            opacity: 0.6,
            tolerance: 'pointer',
            start: function (e, ui) {
                ui.placeholder.height(ui.item.height());
                ui.item.addClass('dragging');
            },
            stop: function (e, ui) {
                ui.item.removeClass('dragging');
            },
            update: function (e, ui) {
                if (typeof MVL_Listing_Manager !== 'undefined') {
                    MVL_Listing_Manager.change();
                }
            }
        });
    }

    /**
     * Initializes Select2 for all select elements
     */
    function initSelect2() {
        $wrapper.find('select').each(function () {
            const $select = $(this);
            $select.select2({
                width: '100%',
                minimumResultsForSearch: 10,
                placeholder: function () {
                    return $(this).find('option:first').text();
                },
                templateResult: function (data) {
                    if (!data.id) {
                        return $('<span class="mvl-placeholder">' + data.text + '</span>');
                    }
                    return data.text;
                },
                templateSelection: function (data) {
                    if (!data.id) {
                        return $('<span class="mvl-placeholder">' + data.text + '</span>');
                    }
                    return data.text;
                }
            });
        });
    }

    /**
     * Returns base AJAX data object
     * @returns {object} Base AJAX data
     */
    function getBaseAjaxData() {
        return {
            action: 'listing_manager_get_form',
            nonce: nonce_get_form,
            listing_id: listingId,
            listing_manager_page_id: 'option'
        };
    }

    /**
     * Updates wrapper content and reinitializes necessary components
     * @param {string} html - New HTML content
     */
    function updateWrapperContent(html) {
        updateDOM($wrapper, html);
        initSortable();
        initSelect2();
        updateDependentFields();
    }

    /**
     * Loads options data from server
     */
    function getOptions() {
        const cacheKey = 'listing_options';
        performAjaxRequest(ajaxurl, Object.assign({}, getBaseAjaxData(), { template: 'options' }), cacheKey)
            .then(response => {
                if (!response.data.html || response.data.html.trim() === '') {
                    $('.mvl-lm-edit-options-btn').hide();
                } else {
                    $('.mvl-lm-edit-options-btn').show();
                }
                updateWrapperContent(response.data.html);
            })
            .catch(error => {
                console.error('Failed to get options:', error);
            });
    }

    /**
     * Deletes an option
     * @param {string} optionId - ID of the option to delete
     */
    function deleteOption(optionId) {
        performAjaxRequest(ajaxurl, {
            action: 'listing_manager_delete_form',
            nonce: nonce_get_form,
            template: 'option',
            listing_manager_page_id: 'option',
            option_id: optionId
        })
            .then(response => {
                if (response.success) {
                    $('.mvl-modal-option-popup, .mvl-confirmation-modal, .mvl-options-popup-container').remove();
                    $('body').removeClass('mvl-popup-overlay');
                    $('body').removeClass('mvl-popup-overlay-x2');

                    clearAjaxCache();

                    if (isEditMode) {
                        performAjaxRequest(ajaxurl, Object.assign({}, getBaseAjaxData(), { template: 'edit-option' }))
                            .then(editResponse => {
                                if (editResponse.success) {
                                    if (!editResponse.data.fields_html || editResponse.data.fields_html.length === 0) {
                                        $('.mvl-lm-edit-options-btn').hide();
                                    } else {
                                        $('.mvl-lm-edit-options-btn').show();
                                    }
                                    $wrapper.html(editResponse.data.fields_html.join(''));
                                    $editBtn
                                        .addClass('mvl-cancel-btn')
                                        .html(stm_vehicles_listing.cancel);
                                    initSortable();
                                }
                            });
                    } else {
                        getOptions();
                    }
                    updateListingData();
                }
            })
            .catch(error => {
                console.error('Failed to delete option:', error);
            });
    }

    /**
     * Initializes event handlers for the page
     */
    function initEventHandlers() {

        $document
            .on('input change', '.mvl-listing-manager-field-input', debounce(function (e) {
                const $input = $(e.target);
                const value = $input.val();
                const fieldId = $input.attr('name');

                if (fieldId) {
                    updatePreviewCard(fieldId, value);
                    hasFieldChanged = true;
                }
            }, 300))
            .on('change', 'select', function () {
                const $select = $(this);
                const fieldId = $select.attr('name');
                const value = $select.find('option:selected').text();

                if (fieldId) {
                    updatePreviewCard(fieldId, value);
                }
                MVL_Listing_Manager.change();
                MVL_Listing_Manager.initFormChangeTracking();
                hasFieldChanged = true;
            })
            .on('select2:select', 'select', function (e) {
                const $select = $(this);
                const fieldId = $select.attr('name');
                const value = e.params.data.text;

                if (fieldId) {
                    updatePreviewCard(fieldId, value);
                    hasFieldChanged = true;
                }
            })
            .on('input', '.mvl-search-terms input', function () {
                const searchText = $(this).val().toLowerCase().trim();
                const $tabContent = $(this).closest('.mvl-options-settings-tab-content');
                const $termsList = $tabContent.find('.mvl-listing-manager-terms-list-inner');
                const $noTermsFound = $tabContent.find('.mvl-no-terms-found');

                if ($termsList.length) {
                    let visibleCount = 0;
                    $termsList.find('.mvl-listing-manager-field-term-item').each(function () {
                        const $termItem = $(this);
                        const termName = $termItem.find('.mvl-listing-manager-term-item-name-input').val().toLowerCase();
                        const isVisible = termName.includes(searchText);
                        $termItem.toggle(isVisible);
                        if (isVisible) visibleCount++;
                    });
                    if (visibleCount === 0) {
                        $noTermsFound.addClass('active');
                    } else {
                        $noTermsFound.removeClass('active');
                    }
                }
            })
            .on('click', '#delete-option', function (e) {
                e.preventDefault();
                let confirmation = initConfirmationPopup(e.currentTarget);
                const optionId = $(this).data('confirmation-slug');
                confirmation.on({
                    accept: function () { },
                    cancel: function () {
                        deleteOption(optionId);
                    }
                });
                confirmation.open();
            })
            .on('click', '#discard-btn', function (e) {
                e.preventDefault();
                const $modal = $('.mvl-listing-manager-modal');
                const $body = $('body');

                $modal.removeClass('active');
                $body.removeClass('active');

                setTimeout(() => {
                    $('.mvl-modal-option-popup, .mvl-confirmation-modal, .mvl-options-popup-container').remove();
                    $body.removeClass('mvl-popup-overlay');
                    $body.removeClass('mvl-popup-overlay-x2');
                }, 300);
            })
            .on('click', '.mvl-lm-add-options-btn', function (e) {
                e.preventDefault();
                loadOptionPopup(null, 'add-new-field');
            });

        $wrapper
            .on('click', '.mvl-lm-edit-option-btn', function (e) {
                e.preventDefault();
                const optionId = $(this).data('option-id');
                loadOptionPopup(optionId);
            });

        $editBtn.on('click', handleEditButtonClick);
    }

    // Initialize on document ready
    $(document).ready(function () {
        $wrapper = $('.mvl-listing-manager-content-body-page-option-wrapper');
        $editBtn = $('.mvl-lm-edit-options-btn');
        listingId = $wrapper.data('listing-id');
        originalHtml = $wrapper.html();

        if ($wrapper.length) {
            getOptions();
            initSortable();
            initEventHandlers();
        }
    });

    const updateDependentFields = throttle(function ($scope = $document) {
        const $dependentFields = $scope.find('[data-depends-on]');
        const dependentsMap = {};

        $dependentFields.each(function () {
            const $dependent = $(this);
            const dependsOn = $dependent.data('depends-on');

            if (!dependentsMap[dependsOn]) {
                dependentsMap[dependsOn] = [];
            }
            dependentsMap[dependsOn].push($dependent);
        });

        Object.keys(dependentsMap).forEach(fieldName => {
            const $controller = $(`[name="${fieldName}"], #${fieldName.replace(/[\[\]]/g, '_')}`);

            if (!$controller.length) {
                return;
            }

            const updateDependents = () => {
                const currentValue = $controller.is(':checkbox') ? ($controller.is(':checked') ? '1' : '0') : $controller.val();

                dependentsMap[fieldName].forEach($dependent => {
                    const dependValues = $dependent.data('depend-values');
                    const values = typeof dependValues === 'string'
                        ? dependValues.split(',').map(v => v.trim())
                        : [String(dependValues)];

                    const action = $dependent.data('depend-action') || 'show';
                    const shouldShow = values.includes(String(currentValue));

                    if (action === 'show') {
                        $dependent.toggle(shouldShow);
                    } else if (action === 'hide') {
                        $dependent.toggle(!shouldShow);
                    } else if (typeof window[action] === 'function') {
                        window[action]($dependent, shouldShow);
                    }
                });
            };

            $controller.off('change input').on('change input', updateDependents);

            if ($controller.is(':checkbox, :radio')) {
                $controller.off('change').on('change', updateDependents);
            }
            updateDependents();
        });
    }, 100);

    function handleEditButtonClick(e) {
        e.preventDefault();

        if ($editBtn.hasClass('mvl-cancel-btn')) {
            handleCancelClick();
        } else {
            handleEditClick();
            isEditMode = true;
        }
    }

    function handleCancelClick() {
        const $items = $wrapper.find('.mvl-listing-manager-field-item');
        const newOrder = [];
        let hasChanges = false;

        $items.each(function (index) {
            const fieldId = $(this).data('field-id');
            const originalIndex = $(this).data('original-index');

            newOrder.push({ slug: fieldId, order: index });
            if (originalIndex !== index) hasChanges = true;
        });

        if (!hasChanges) {
            loadUpdatedHtml();
            return;
        }

        saveFieldOrder(newOrder);
    }

    function saveFieldOrder(newOrder) {
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'listing_manager_save_form',
                nonce: nonce,
                template: 'edit-option',
                listing_manager_page_id: 'option',
                order: newOrder
            },
            success: function (response) {
                if (response.success) {
                    loadUpdatedHtml();
                } else {
                    console.error('Failed to save order:', response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    function loadUpdatedHtml() {
        const ajaxData = Object.assign({}, getBaseAjaxData(), { template: 'options' });
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: ajaxData,
            success: function (response) {
                if (response.success) {
                    updateWrapperContent(response.data.html);
                    resetEditButton();
                } else {
                    console.error('Failed to load HTML:', response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    function resetEditButton() {
        $editBtn
            .removeClass('mvl-cancel-btn')
            .html(`
                <i class="motors-icons-mvl-pencil"></i>
                ${stm_vehicles_listing.edit_fields}
            `);
        isEditMode = false;
    }

    function handleEditClick() {
        isEditMode = true;
        $editBtn.prop('disabled', true);

        if (hasFieldChanged) {
            let confirmation = initConfirmationPopup($('.mvl-lm-edit-options-btn'));
            confirmation.on({
                accept: function () {
                    $editBtn.prop('disabled', false);
                    isEditMode = false;
                },
                cancel: function () {
                    performEditAjax();
                    hasFieldChanged = false;
                }
            });
            confirmation.open();
            return;
        }

        performEditAjax();
    }

    function performEditAjax() {
        const ajaxData = Object.assign({}, getBaseAjaxData(), { template: 'edit-option' });

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: ajaxData,
            success: function (response) {
                if (response.success) {
                    originalHtml = $wrapper.html();
                    $wrapper.html(response.data.fields_html.join(''));
                    $editBtn
                        .addClass('mvl-cancel-btn')
                        .html(stm_vehicles_listing.cancel);
                    initSortable();
                } else {
                    console.error(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            },
            complete: function () {
                $editBtn.prop('disabled', false);
            }
        });
    }

    function loadOptionPopup(optionId, formAction = 'edit-field') {
        const cacheKey = optionId ? `option_popup_${optionId}` : 'new_option_popup';
        performAjaxRequest(ajaxurl, Object.assign({}, getBaseAjaxData(), {
            template: 'modal',
            option_id: optionId,
            form_action: formAction,
            noCache: true
        }), cacheKey)
            .then(response => {
                if (response.success && response.data.html) {
                    $('body').prepend(response.data.html);
                    $('body').addClass('mvl-popup-overlay');

                    requestAnimationFrame(() => {
                        $('.mvl-listing-manager-modal').addClass('active');
                        $('body').addClass('active');
                    });

                    if (optionId) {
                        $('.mvl-options-popup-container').attr('data-option-id', optionId);
                    }
                    requestAnimationFrame(() => {
                        try {
                            const select2Config = {
                                width: '100%',
                                minimumResultsForSearch: 10,
                                placeholder: function () {
                                    return $(this).find('option:first').text();
                                },
                                templateResult: function (data) {
                                    if (!data.id) {
                                        return $('<span class="mvl-placeholder">' + data.text + '</span>');
                                    }
                                    return data.text;
                                },
                                templateSelection: function (data) {
                                    if (!data.id) {
                                        return $('<span class="mvl-placeholder">' + data.text + '</span>');
                                    }
                                    return data.text;
                                }
                            };
                            $('.mvl-options-popup-container select').each(function () {
                                const $select = $(this);
                                if (!$select.data('select2')) {
                                    $select.select2(select2Config);
                                }
                            });
                            $('.mvl-options-popup-container').on('select2:init', 'select', function () {
                                const $select = $(this);
                                if (!$select.data('select2')) {
                                    $select.select2(select2Config);
                                }
                            });
                        } catch (error) {
                            console.error('Error initializing Select2:', error);
                        }
                    });

                    mvlListingManagerColorPicker();

                    initTabs();
                    updateDependentFields($('.mvl-options-popup-container'));
                }
            })
            .catch(error => {
                console.error('Failed to load popup:', error);
            });

    }

    function initTabs() {
        const $tabs = $('.mvl-options-settings-tab');
        const $tabContents = $('.mvl-options-settings-tab-content-item');

        $tabs.removeClass('active');
        $tabContents.removeClass('active');

        $tabs.on('click', function () {
            const tabId = $(this).data('tab');
            $tabs.removeClass('active');
            $(this).addClass('active');
            $tabContents.removeClass('active');
            $tabContents.filter(`[data-tab="${tabId}"]`).addClass('active');
        });
        $tabs.first().trigger('click');

        $(document).on('click', '#add_new_term_button', function (e) {
            e.preventDefault();
            const $field = $(this).closest('.mvl-options-settings-tab-content-item-field');
            const $input = $field.find('input[type="text"]');
            const term = $input.val().trim();

            if (!term) {
                return;
            }

            const $hiddenInput = $field.find('input[type="hidden"]');
            const termMeta = $hiddenInput.length ? $hiddenInput.val() : '';
            const metaKey = $hiddenInput.length ? $hiddenInput.attr('name') : '';
            const taxonomy = $field.closest('.mvl-options-settings-tab-content').data('slug') || $field.closest('form').find('input[name="slug"]').val();

            const $imageContainer = $field.find('.mvl-listing-manager-field-image');
            const $imageInput = $imageContainer.find('.mvl-listing-manager-field-image-list-item-input');
            const imageId = $imageInput.length ? $imageInput.val() : '';

            const $select = $field.prev().find('select');
            const tempId = 'temp_' + Date.now();
            const newOption = new Option(term, tempId, true, true);
            $select.append(newOption).trigger('change');

            const $checkboxesWrapper = $field.closest('.mvl-options-settings-tab-content').find('.mvl-listing-manager-terms-list-inner');
            if ($checkboxesWrapper.length) {
                const fieldType = $field.closest('.mvl-options-settings-tab-content').find('select[name="field_type"]').val();
                let contentHtml = '';

                if (fieldType === 'color') {
                    const colorValue = $field.find('input[name="_category_color"]').val() || '#000000';
                    contentHtml = `
                        <div class="mvl-listing-manager-term-item-color" data-depends-on="field_type" data-depend-values="color" data-depend-action="show">
                            <span class="term-item-color" style="background: ${colorValue}!important;"></span>
                        </div>
                    `;
                } else {
                    let imageHtml = '';
                    if (imageId) {
                        const imageUrl = wp.media.attachment(imageId).get('url');
                        imageHtml = `
                            <div class="mvl-listing-manager-term-item-image" data-depends-on="field_type" data-depend-values="dropdown,checkbox,numeric,price" data-depend-action="show">
                                <img src="${imageUrl}" alt="${term}">
                            </div>
                        `;
                    } else {
                        imageHtml = `
                            <div class="mvl-listing-manager-term-item-image" data-depends-on="field_type" data-depend-values="dropdown,checkbox,numeric,price" data-depend-action="show">
                                <div class="mvl-listing-manager-term-item-image-placeholder">
                                    <i class="motors-icons-image-plus"></i>
                                </div>
                            </div>
                        `;
                    }
                    contentHtml = imageHtml;
                }

                const $termItem = $(`
                    <div class="mvl-listing-manager-field mvl-listing-manager-field-term-item" data-term-id="${tempId}">
                        <div class="mvl-listing-manager-term-item">
                            ${contentHtml}
                            <div class="mvl-listing-manager-term-item-name">
                                <input type="text" class="mvl-listing-manager-term-item-name-input" value="${term}" readonly>
                            </div>
                        </div>
                    </div>
                `);
                $checkboxesWrapper.append($termItem);
            }

            $input.val('');

            if ($imageContainer.length) {
                $imageContainer.find('.mvl-listing-manager-field-image-list').empty();
                $imageContainer.removeClass('has-files');
            }

            const $termsContainer = $field.closest('.mvl-options-settings-tab-content');
            let $newTermsInput = $termsContainer.find('input[name="new_terms"]');

            if (!$newTermsInput.length) {
                $newTermsInput = $('<input type="hidden" name="new_terms" value="">');
                $termsContainer.append($newTermsInput);
            }

            const currentTerms = $newTermsInput.val() ? JSON.parse($newTermsInput.val()) : [];
            const newTerm = {
                name: term,
                taxonomy: taxonomy,
                term_meta: termMeta,
                meta_key: metaKey,
                image_id: imageId,
                temp_id: tempId
            };

            currentTerms.push(newTerm);
            $newTermsInput.val(JSON.stringify(currentTerms));

            const $noTermsFound = $('.mvl-no-terms-found');
            $noTermsFound.removeClass('active');
        });

        $('.mvl-choose-icon').on('click', function (e) {
            e.preventDefault();
            $('.mlv-options-popup-content').hide();
            $('.mlv-options-popup-icons').show();

            const $header = $('.mvl-listing-manager-modal-header-title');
            $header.data('original-title', $header.html());

            const $activeTab = $('.mvl-options-settings-tab.active');
            if ($activeTab.length) {
                $header.data('original-tab', $activeTab.data('tab'));
            }

            const $iconTabs = $('.mlv-options-popup-icons .mvl-options-settings-tab');
            const $iconContents = $('.mlv-options-popup-icons .mvl-options-settings-tab-content-item');

            $iconTabs.removeClass('active');
            $iconContents.removeClass('active');

            $iconTabs.first().addClass('active');
            $iconContents.first().addClass('active');
            $header.html('<span class="mvl-back-to-fields"><i class="fa-solid fa-chevron-left"></i> ' + stm_vehicles_listing.add_icon + '</span>');
        });

        $(document).on('click', '.mvl-back-to-fields', function (e) {
            e.preventDefault();
            $('.mlv-options-popup-icons').hide();
            $('.mlv-options-popup-content').show();
            const $header = $('.mvl-listing-manager-modal-header-title');
            const originalTitle = $header.data('original-title');
            if (originalTitle) {
                $header.html(originalTitle);
            }
            const originalTab = $header.data('original-tab');
            if (originalTab) {
                const $tabs = $('.mvl-options-settings-tab');
                const $tabContents = $('.mvl-options-settings-tab-content-item');
                $tabs.removeClass('active');
                $tabContents.removeClass('active');
                $tabs.filter(`[data-tab="${originalTab}"]`).addClass('active');
                $tabContents.filter(`[data-tab="${originalTab}"]`).addClass('active');
            }
        });

        $(document).on('click', '.mvl-icon-item', function (e) {
            e.preventDefault();
            const $icon = $(this).find('i');
            const iconClass = $icon.attr('class');
            const $button = $('.mvl-choose-icon');
            const $input = $button.closest('.mvl-listing-manager-field').find('input[type="hidden"]');

            $button.find('i').attr('class', iconClass);
            $button.addClass('mvl-short-btn');

            $button.contents().filter(function () {
                return this.nodeType === 3;
            }).remove();

            $input.val(iconClass);
            $('.mlv-options-popup-icons').hide();
            $('.mlv-options-popup-content').show();
            const $header = $('.mvl-listing-manager-modal-header-title');
            const originalTitle = $header.data('original-title');
            if (originalTitle) {
                $header.html(originalTitle);
            }
            const originalTab = $header.data('original-tab');
            if (originalTab) {
                const $tabs = $('.mvl-options-settings-tab');
                const $tabContents = $('.mvl-options-settings-tab-content-item');
                $tabs.removeClass('active');
                $tabContents.removeClass('active');
                $tabs.filter(`[data-tab="${originalTab}"]`).addClass('active');
                $tabContents.filter(`[data-tab="${originalTab}"]`).addClass('active');
            }
        });

        $('.mvl-options-popup-container').on('input change', 'input, select, textarea', function (e) {
            if (e.type === 'input' && $(this).is('[type="hidden"]')) {
                return;
            }
            modalChanged = true;
        });
    }

    function cleanupModalOverlay() {
        const $body = $('body');
        $('.mvl-modal-option-popup').remove();
        $body.removeClass('mvl-popup-overlay');
        $body.removeClass('mvl-popup-overlay-x2');
    }

    function closeOptionModal() {
        const $modal = $('.mvl-modal-option-popup');
        const $body = $('body');

        $modal.removeClass('active');
        $body.removeClass('active');

        setTimeout(() => {
            cleanupModalOverlay();
        }, 300);
    }

    $(document).on('click', '#mvl-cancel-btn-option', function (e) {
        e.preventDefault();
        if (modalChanged) {
            let confirmation = initConfirmationPopup(e.currentTarget);
            confirmation.on({
                cancel: function () {
                    closeOptionModal();
                    modalChanged = false;
                }
            });
            confirmation.open();
        } else {
            closeOptionModal();
        }
    });

    $(document).on('click', '#delete-option', function (e) {
        e.preventDefault();
        let confirmation = initConfirmationPopup(e.currentTarget);
        const optionId = $(this).data('confirmation-slug');
        confirmation.on({
            accept: function () {
            },
            cancel: function () {
                deleteOption(optionId);
            }
        })
        confirmation.open();
    });

    $(document).on('click', '#save-option', function (e) {
        e.preventDefault();
        var $modal = $(this).closest('.mvl-listing-manager-modal');
        var $body = $('body');
        var $formFields = $modal.find('input, select, textarea');
        var formData = {
            action: 'listing_manager_save_form',
            nonce: nonce,
            template: 'modal',
            listing_manager_page_id: 'option'
        };

        var optionId = $modal.find('.mvl-options-popup-container').attr('data-option-id');
        if (optionId) {
            formData.option_id = optionId;
        }

        $formFields.each(function () {
            var $field = $(this);
            var name = $field.attr('name');
            if (!name) return;

            if (name === 'new_terms') {
                const terms = JSON.parse($field.val() || '[]');
                const filteredTerms = terms.filter(term => term.name && term.name.trim());
                $field.val(JSON.stringify(filteredTerms));
            }

            if ($field.is(':checkbox')) {
                formData[name] = $field.is(':checked') ? '1' : '0';
            } else if ($field.is(':radio')) {
                if ($field.is(':checked')) {
                    formData[name] = $field.val();
                }
            } else {
                formData[name] = $field.val();
            }
        });

        function showError(message) {
            const $footer = $modal.find('.mvl-listing-manager-modal-footer');
            let $errorMessage = $footer.find('.mvl-error-message');

            if (!$errorMessage.length) {
                $errorMessage = $('<div class="mvl-error-message"></div>');
                $footer.prepend($errorMessage);
            }

            $errorMessage.html(message).show();

            setTimeout(() => {
                $errorMessage.fadeOut(300, function () {
                    $(this).remove();
                });
            }, 3000);
        }

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.success) {
                    clearAjaxCache();

                    $modal.removeClass('active');
                    $body.removeClass('active');

                    setTimeout(() => {
                        $modal.remove();
                        $body.removeClass('mvl-popup-overlay');
                    }, 300);

                    const wasEditMode = isEditMode;

                    if (wasEditMode) {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: Object.assign({}, getBaseAjaxData(), { template: 'edit-option' }),
                            success: function (editResponse) {
                                if (editResponse.success) {
                                    if (!editResponse.data.fields_html || editResponse.data.fields_html.length === 0) {
                                        $('.mvl-lm-edit-options-btn').hide();
                                    } else {
                                        $('.mvl-lm-edit-options-btn').show();
                                    }
                                    $wrapper.html(editResponse.data.fields_html.join(''));
                                    $editBtn
                                        .addClass('mvl-cancel-btn')
                                        .html(stm_vehicles_listing.cancel);
                                    initSortable();
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: Object.assign({}, getBaseAjaxData(), { template: 'options' }),
                            success: function (optionsResponse) {
                                if (optionsResponse.success) {
                                    if (!optionsResponse.data.html || optionsResponse.data.html.trim() === '') {
                                        $('.mvl-lm-edit-options-btn').hide();
                                    } else {
                                        $('.mvl-lm-edit-options-btn').show();
                                    }
                                    updateWrapperContent(optionsResponse.data.html);
                                }
                            }
                        });
                    }
                    updateListingData();
                } else {
                    showError(response.data && response.data.message ? response.data.message : 'Error saving option');
                }
            },
            error: function (xhr, status, error) {
                showError('Server error occurred. Please try again.');
            }
        });
    });

    $(document).on('click', '.mvl-listing-manager-term-item-edit', function (e) {
        e.preventDefault();
        const $termItem = $(this).closest('.mvl-listing-manager-field-term-item');
        const termId = $termItem.data('term-id');
        const $nameInput = $termItem.find('.mvl-listing-manager-term-item-name-input');

        if ($nameInput.prop('readonly')) {
            $nameInput.prop('readonly', false).focus();
            $(this).find('i').removeClass('fa-pencil').addClass('fa-save');
        } else {
            const newName = $nameInput.val().trim();
            if (newName) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'listing_manager_update_term',
                        nonce: nonce,
                        term_id: termId,
                        name: newName
                    },
                    success: function (response) {
                        if (response.success) {
                            $nameInput.prop('readonly', true);
                            $(this).find('i').removeClass('fa-save').addClass('fa-pencil');
                        } else {
                            alert(response.data.message || 'Error updating term');
                        }
                    }.bind(this),
                    error: function () {
                        alert('Server error occurred');
                    }
                });
            }
        }
    });

    $(document).on('click', '.mvl-listing-manager-term-item-delete', function (e) {
        e.preventDefault();
        const $termItem = $(this).closest('.mvl-listing-manager-field-term-item');
        const termId = $termItem.data('term-id');

        if (confirm(stm_vehicles_listing.confirm_delete_term)) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'listing_manager_delete_term',
                    nonce: nonce,
                    term_id: termId
                },
                success: function (response) {
                    if (response.success) {
                        $termItem.fadeOut(300, function () {
                            $(this).remove();
                        });
                    } else {
                        alert(response.data.message || 'Error deleting term');
                    }
                },
                error: function () {
                    alert('Server error occurred');
                }
            });
        }
    });

})(jQuery); 