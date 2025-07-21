/**
 * Listing Manager Features Module
 * Handles features management functionality for vehicle listings
 */
"use strict";
(function ($) {
    $(document).ready(function () {
        const $wrapper = $('.mvl-listing-manager-content-body-page-features-wrapper');
        const $editBtn = $('.mvl-lm-edit-features-btn');
        const listingId = $wrapper.data('listing-id');
        const $editOptionBtn = $('.mvl-lm-edit-option-btn');
        let isEditMode = false;
        let originalHtml = $wrapper.html();
        let modalChanged = false;
        let hasFieldChanged = false;

        /**
         * Shows loading state for wrapper
         */
        function showWrapperLoading() {
            $wrapper.addClass('loading');
        }

        /**
         * Hides loading state for wrapper
         */
        function hideWrapperLoading() {
            $wrapper.removeClass('loading');
        }

        /**
         * Disables edit button
         */
        function disableEditButton() {
            $editBtn.addClass('disabled').prop('disabled', true);
        }

        /**
         * Enables edit button
         */
        function enableEditButton() {
            $editBtn.removeClass('disabled').prop('disabled', false);
        }

        $wrapper.on('change', '.mvl-listing-manager-field-checkbox input[type="checkbox"]', function () {
            hasFieldChanged = true;
        });

        /**
         * Get base AJAX data for requests
         * @returns {Object} Base AJAX data object
         */
        function getBaseAjaxData() {
            return {
                action: 'listing_manager_get_form',
                nonce: nonce_get_form,
                listing_id: listingId,
                listing_manager_page_id: 'features'
            };
        }

        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        const debouncedGetFeatures = debounce(getFeatures, 300);

        if ($wrapper.length) {
            debouncedGetFeatures();
            initSortable();
            bindEvents();
        }

        /**
         * Initialize sortable functionality for features
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
         * Bind event handlers to DOM elements
         */
        function bindEvents() {
            $editBtn.on('click', handleEditButtonClick);
            $(document).on('click', '.mvl-lm-add-feature-btn', function (e) {
                e.preventDefault();
                loadOptionPopup(null, 'add-field');
            });

            $(document).on('click', '#add_new_feature_option', function () {
                const $input = $('.mvl-listing-manager-field-input');
                const newValue = $input.val().trim();

                if (newValue) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'listing_manager_save_form',
                            nonce: nonce,
                            template: 'term-features',
                            listing_manager_page_id: 'features',
                            term_name: newValue
                        },
                        success: function (response) {
                            if (response.success) {
                                const $select = $('.mvl-options-popup-container select');
                                const newOption = new Option(response.data.term_name, response.data.term_slug, true, true);
                                $select.append(newOption).trigger('change');
                                $input.val('');
                                $select.select2('open');
                            } else {
                                console.error('Failed to add term:', response.data.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', error);
                        }
                    });
                }
            });

            $(document).on('keypress', '.mvl-listing-manager-field-input', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#add_new_feature_option').trigger('click');
                }
            });
        }

        /**
         * Handle edit button click events
         * @param {Event} e - Click event object
         */
        function handleEditButtonClick(e) {
            e.preventDefault();

            if ($editBtn.hasClass('mvl-cancel-btn')) {
                handleCancelClick();
            } else {
                handleEditClick();
            }
        }

        /**
         * Handle cancel button click
         * Saves new order if changes were made
         */
        function handleCancelClick() {
            showWrapperLoading();
            disableEditButton();
            
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

        /**
         * Save new field order to server
         * @param {Array} newOrder - Array of field order objects
         */
        function saveFieldOrder(newOrder) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'listing_manager_save_form',
                    nonce: nonce,
                    template: 'edit-features',
                    listing_manager_page_id: 'features',
                    order: newOrder
                },
                success: function (response) {
                    if (response.success) {
                        loadUpdatedHtml();
                    } else {
                        console.error('Failed to save order:', response.data.message);
                        hideWrapperLoading();
                        enableEditButton();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    hideWrapperLoading();
                    enableEditButton();
                }
            });
        }

        /**
         * Fetch features from server
         */
        function getFeatures() {
            showWrapperLoading();
            disableEditButton();
            
            const ajaxData = Object.assign({}, getBaseAjaxData(), { template: 'features' });
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: ajaxData,
                success: function (response) {
                    if (response.success) {
                        updateWrapperContent(response.data.html);
                        if (!response.data.html || response.data.html.trim() === '') {
                            $('.mvl-lm-edit-features-btn').hide();
                        } else {
                            $('.mvl-lm-edit-features-btn').show();
                        }
                    } else {
                        console.error(response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                },
                complete: function () {
                    hideWrapperLoading();
                    enableEditButton();
                }
            });
        }

        /**
         * Update wrapper content with new HTML
         * @param {string} html - New HTML content
         */
        function updateWrapperContent(html) {
            $wrapper.html(html);
            initSortable();
            resetEditButton();
        }

        /**
         * Load updated HTML from server
         */
        function loadUpdatedHtml() {
            const ajaxData = Object.assign({}, getBaseAjaxData(), { template: 'features' });
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: ajaxData,
                success: function (response) {
                    if (response.success) {
                        updateWrapperContent(response.data.html);
                    } else {
                        console.error('Failed to load HTML:', response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                },
                complete: function () {
                    hideWrapperLoading();
                    enableEditButton();
                }
            });
        }

        /**
         * Reset edit button to initial state
         */
        function resetEditButton() {
            $editBtn
                .removeClass('mvl-cancel-btn')
                .html(`
                    <i class="motors-icons-mvl-pencil"></i>
                    ${stm_vehicles_listing.edit_fields}
                `);
            isEditMode = false;
        }

        /**
         * Handle edit mode activation
         */
        function handleEditClick() {
            isEditMode = true;
            showWrapperLoading();
            disableEditButton();

            if (hasFieldChanged) {

                let confirmation = initConfirmationPopup($editBtn);
                confirmation.on({
                    accept: function () {
                        hideWrapperLoading();
                        enableEditButton();
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
            const ajaxData = Object.assign({}, getBaseAjaxData(), { template: 'edit-features' });

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: ajaxData,
                success: function (response) {
                    if (response.success) {
                        originalHtml = $wrapper.html();
                        $wrapper.html(response.data.fields_html.join(''));
                        setOriginalIndexes();
                        $editBtn
                            .addClass('mvl-cancel-btn')
                            .html(stm_vehicles_listing.cancel);
                        initSortable();
                        if (isEditMode) {
                            if (!response.data.fields_html || response.data.fields_html.length === 0) {
                                $('.mvl-lm-edit-features-btn').hide();
                            } else {
                                $('.mvl-lm-edit-features-btn').show();
                            }
                        } else {
                            if (!response.data.html || response.data.html.trim() === '') {
                                $('.mvl-lm-edit-features-btn').hide();
                            } else {
                                $('.mvl-lm-edit-features-btn').show();
                            }
                        }
                    } else {
                        console.error(response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                },
                complete: function () {
                    hideWrapperLoading();
                    enableEditButton();
                }
            });
        }

        /**
         * Set original indexes for sortable items
         */
        function setOriginalIndexes() {
            $wrapper.find('.mvl-listing-manager-field-item').each(function (index) {
                $(this).data('original-index', index);
            });
        }

        /**
         * Load option popup for editing
         * @param {string} optionId - ID of the option to edit
         * @param {string} formAction - Form action type
         */
        function loadOptionPopup(optionId, formAction = 'edit-field') {
            const ajaxData = Object.assign({}, getBaseAjaxData(), {
                template: 'modal',
                option_id: optionId,
                form_action: formAction
            });

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: ajaxData,
                success: function (response) {
                    if (response.success && response.data.html) {
                        initializePopup(response.data.html);
                    }
                }
            });
        }

        /**
         * Initialize popup with select2 and event handlers
         * @param {string} html - Popup HTML content
         */
        function initializePopup(html) {
            const $body = $('body');
            const $modal = $(html);

            $body.prepend($modal).addClass('mvl-popup-overlay');

            requestAnimationFrame(() => {
                $modal.addClass('active');
                $body.addClass('active');
            });

            const $select = $modal.find('.mvl-options-popup-container select');
            const multiple = $select.data('multiple') || false;

            const selectedValues = $select.find('option:selected').map(function () {
                return $(this).val();
            }).get();

            $('.mvl-listing-manager-field-input').on('input', function () {
                modalChanged = true;
            });

            $select.on('change', function () {
                modalChanged = true;
            });

            initializeSelect2($select, multiple, selectedValues);
            initializePopupEvents($select);
        }

        /**
         * Initialize select2 with custom configuration
         * @param {jQuery} $select - Select element
         * @param {boolean} multiple - Whether multiple selection is allowed
         * @param {Array} selectedValues - Array of selected values
         */
        function initializeSelect2($select, multiple, selectedValues) {
            const placeholderText = stm_vehicles_listing.select_feature;
            const $container = $select.closest('.mvl-options-popup-container');

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.prop('multiple', true);

            $select.select2({
                width: '100%',
                minimumResultsForSearch: 0,
                dropdownParent: $container,
                allowClear: false,
                placeholder: placeholderText,
                templateResult: function (data) {
                    const currentValues = $select.val() || [];
                    if (!data.id) {
                        return $('<span class="mvl-placeholder">' + data.text + '</span>');
                    }
                    const isSelected = currentValues.includes(data.id);
                    return $('<div class="mvl-select-option-with-checkbox">' +
                        '<input id="' + data.id + '" type="checkbox" class="mvl-select-checkbox" ' + (isSelected ? 'checked' : '') + '>' +
                        '<span class="mvl-select-option-text">' + data.text + '</span>' +
                        '</div>');
                },
                templateSelection: function (data) {
                    return data.text;
                },
                multiple: true,
                dropdownCssClass: 'mvl-dropdown-checkboxes',
                closeOnSelect: false,
                escapeMarkup: function (markup) {
                    return markup;
                }
            });

            function updateCustomPlaceholder() {
                const $selection = $container.find('.select2-selection--multiple');
                const hasValue = Array.isArray($select.val()) && $select.val().length > 0;
                $selection.find('.mvl-placeholder').remove();
                if (!hasValue) {
                    $selection.append('<span class="mvl-placeholder">' + placeholderText + '</span>');
                }
            }
            $select.on('change', updateCustomPlaceholder);
            updateCustomPlaceholder();
        }

        /**
         * Initialize popup event handlers
         * @param {jQuery} $select - Select element
         */
        function initializePopupEvents($select) {
            const $container = $select.closest('.mvl-options-popup-container');

            $container.on('click', '#add_new_feature_option', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const $input = $(this).closest('.mvl-listing-manager-field-input-wrapper')
                    .find('.mvl-listing-manager-field-input');
                const newValue = $input.val().trim();

                if (newValue) {
                    addNewFeatureOption($select, newValue, $input);
                }
            });

            $container.on('click', '#done_adding_features', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $select.select2('close');
            });

            $container.on('click', '#clear_all_features', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $select.val(null).trigger('change');
                $select.find('option').prop('selected', false);
                $select.next('.select2-container').find('.select2-selection__rendered').empty();
                updateSelect2Results($select, []);
            });

            $container.on('keypress', '.mvl-listing-manager-field-input', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).closest('.mvl-listing-manager-field-input-wrapper')
                        .find('#add_new_feature_option').trigger('click');
                }
            });

            $container.on('click', '.mvl-select-option-with-checkbox', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const $checkbox = $(this).find('input[type="checkbox"]');
                const optionId = $checkbox.attr('id');

                if (!optionId) return;

                const currentValues = $select.val() || [];
                const isChecked = !$checkbox.prop('checked');

                $checkbox.prop('checked', isChecked);

                let newValues = [...currentValues];
                if (isChecked) {
                    if (!newValues.includes(optionId)) {
                        newValues.push(optionId);
                    }
                } else {
                    newValues = newValues.filter(value => value !== optionId);
                }

                $select.val(newValues).trigger('change');

                $select.find('option').each(function () {
                    const value = $(this).val();
                    $(this).prop('selected', newValues.includes(value));
                });
            });

            $container.on('click', '.mvl-select-checkbox', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const $checkbox = $(this);
                const optionId = $checkbox.attr('id');

                if (!optionId) return;
            });

            $select.on('select2:open', function () {
                initializeSelect2Dropdown($select);
            });
        }

        /**
         * Update select2 results
         * @param {jQuery} $select - Select element
         * @param {Array} selectedValues - Array of selected values
         */
        function updateSelect2Results($select, selectedValues) {
            const $dropdown = $('.select2-dropdown');
            const $results = $dropdown.find('.select2-results__options');

            const fragment = document.createDocumentFragment();

            $select.find('option').each(function () {
                const $option = $(this);
                const optionValue = $option.val();
                const isSelected = selectedValues.includes(optionValue);

                const optionHtml = `
                    <li class="select2-results__option" data-id="${optionValue}">
                        <div class="mvl-select-option-with-checkbox">
                            <input id="${optionValue}" type="checkbox" class="mvl-select-checkbox" ${isSelected ? 'checked' : ''}>
                            <span class="mvl-select-option-text">${$option.text()}</span>
                        </div>
                    </li>
                `;
                fragment.appendChild($(optionHtml)[0]);
            });

            $results.empty().append(fragment);
        }

        /**
         * Add new feature option
         * @param {jQuery} $select - Select element
         * @param {string} newValue - New option value
         * @param {jQuery} $input - Input element
         */
        function addNewFeatureOption($select, newValue, $input) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'listing_manager_save_form',
                    nonce: nonce,
                    template: 'term-features',
                    listing_manager_page_id: 'features',
                    term_name: newValue
                },
                success: function (response) {
                    if (response.data.success) {
                        const newOption = new Option(response.data.term_name, response.data.term_slug, true, true);
                        $select.append(newOption);

                        const selectedValues = $select.val() || [];
                        if (!selectedValues.includes(response.data.term_slug)) {
                            selectedValues.push(response.data.term_slug);
                        }
                        $select.val(selectedValues);

                        $input.val('');
                        updateSelect2Results($select, selectedValues);
                        const $selection = $select.closest('.mvl-options-popup-container').find('.select2-selection--multiple');
                        const hasValue = Array.isArray($select.val()) && $select.val().length > 0;
                        $selection.find('.mvl-placeholder').remove();
                        if (!hasValue) {
                            $selection.append('<span class="mvl-placeholder">' + stm_vehicles_listing.select_feature + '</span>');
                        }
                    } else {
                        const $footer = $('.mvl-listing-manager-select-footer');
                        let $errorMessage = $footer.find('.mvl-error-message');

                        if (!$errorMessage.length) {
                            $errorMessage = $('<div class="mvl-error-message"></div>');
                            $footer.append($errorMessage);
                        }

                        $errorMessage.html(response.data.message).show();

                        setTimeout(() => {
                            $errorMessage.fadeOut(300, function () {
                                $(this).remove();
                            });
                        }, 3000);

                        $input.val('');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        /**
         * Initialize select2 dropdown with search and add new option functionality
         * @param {jQuery} $select - Select element
         */
        function initializeSelect2Dropdown($select) {
            const $dropdown = $('.select2-dropdown');

            if ($dropdown.find('.mvl-listing-manager-select-header').length === 0) {
                $dropdown.prepend(`
                    <div class="mvl-listing-manager-select-header">
                        <div class="mvl-listing-manager-content-header-search-field">
                            <input type="text" class="mvl-lm-search-field-input mvl-input-field" 
                                placeholder="${stm_vehicles_listing.search_placeholder}">
                        </div>
                    </div>
                `);

                $('.mvl-lm-search-field-input').on('keyup', function () {
                    const search = $(this).val().toLowerCase();
                    let visibleCount = 0;
                    const totalOptions = $('.select2-results__option:not(.select2-results__message)').length;

                    $('.select2-results__option:not(.select2-results__message)').each(function () {
                        const text = $(this).text().toLowerCase();
                        const isVisible = text.includes(search);
                        $(this).toggle(isVisible);
                        if (isVisible) visibleCount++;
                    });

                    let $noResults = $('.select2-results__options').find('.select2-results__message');

                    if (visibleCount === 0 || totalOptions === 0) {
                        if (!$noResults.length) {
                            $noResults = $('<li class="select2-results__option select2-results__message">No results found</li>');
                            $('.select2-results__options').append($noResults);
                        }
                        $noResults.show();
                    } else {
                        $noResults.hide();
                    }
                });
            }

            if ($dropdown.find('.mvl-listing-manager-select-footer').length === 0) {
                $dropdown.append(`
                    <div class="mvl-listing-manager-select-footer">
                        <div class="mvl-listing-manager-field-input-wrapper">
                            <input type="text" class="mvl-listing-manager-field-input mvl-input-field" 
                                placeholder="${stm_vehicles_listing.add_new_option}">
                            <button id="add_new_feature_option" type="button" 
                                class="mvl-listing-manager-field-button mvl-primary-btn mvl-short-btn">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="mvl-listing-manager-select-footer-btns">
                            <button id="clear_all_features" type="button" 
                                class="mvl-listing-manager-field-button mvl-delete-btn">
                                ${stm_vehicles_listing.clear_all}
                            </button>
                            <button id="done_adding_features" type="button" 
                                class="mvl-listing-manager-field-button mvl-primary-btn">
                                ${stm_vehicles_listing.done}
                            </button>
                        </div>
                    </div>
                `);
            }
        }

        $wrapper.on('click', '.mvl-lm-edit-option-btn', function (e) {
            e.preventDefault();
            const optionId = $(this).data('option-id');
            if (!optionId) {
                return;
            }
            loadOptionPopup(optionId, 'edit-field');
        });

        $(document).on('click', '#save-features', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $modal = $('.mvl-modal-features-popup');
            const $body = $('body');
            const $select = $modal.find('select');
            const selectedValues = $select.val() || [];
            const title = $modal.find('input[type="text"]').val();
            const optionId = $modal.find('.mvl-options-popup-container').data('option-id');

            if (!title) {
                showError($modal, 'Title is required');
                return;
            }

            function showError($modal, message) {
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
                data: {
                    action: 'listing_manager_save_form',
                    nonce: nonce,
                    template: 'save-features',
                    listing_manager_page_id: 'features',
                    title: title,
                    selected_values: selectedValues,
                    option_id: optionId
                },
                success: function (response) {
                    if (response.success && response.data && response.data.success === false) {
                        let errorMsg = response.data.message || 'Error saving features';
                        showError($modal, errorMsg);
                        return;
                    }
                    if (response.success && !response.data.already_exists) {
                        $modal.removeClass('active');
                        $body.removeClass('active');
                        modalChanged = false;

                        setTimeout(() => {
                            $('.mvl-modal-features-popup, .mvl-confirmation-modal, .mvl-options-popup-container').remove();
                            $body.removeClass('mvl-popup-overlay');
                            $body.removeClass('mvl-popup-overlay-x2');
                        }, 300);

                        if (isEditMode) {
                            showWrapperLoading();
                            const ajaxData = Object.assign({}, getBaseAjaxData(), { template: 'edit-features' });
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: ajaxData,
                                success: function (response) {
                                    if (response.success) {
                                        $wrapper.html(response.data.fields_html.join(''));
                                        setOriginalIndexes();
                                        initSortable();
                                        if (isEditMode) {
                                            if (!response.data.fields_html || response.data.fields_html.length === 0) {
                                                $('.mvl-lm-edit-features-btn').hide();
                                            } else {
                                                $('.mvl-lm-edit-features-btn').show();
                                                $('.mvl-lm-edit-features-btn').addClass('mvl-cancel-btn');
                                                $('.mvl-lm-edit-features-btn').html(stm_vehicles_listing.cancel);
                                            }
                                        } else {
                                            if (!response.data.html || response.data.html.trim() === '') {
                                                $('.mvl-lm-edit-features-btn').hide();
                                            } else {
                                                $('.mvl-lm-edit-features-btn').show();
                                            }
                                        }
                                    } else {
                                        console.error(response.data.message);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error('AJAX Error:', error);
                                },
                                complete: function () {
                                    hideWrapperLoading();
                                }
                            });
                        } else {
                            getFeatures();
                        }
                    } else {
                        let errorMsg = (response.data && response.data.message) ? response.data.message : (response.message ? response.message : 'Error saving features');
                        showError($modal, errorMsg);
                    }
                },
                error: function (xhr, status, error) {
                    showError($modal, 'Server error occurred. Please try again.');
                }
            });
        });

        function closeFeaturesModal() {
            const $modal = $('.mvl-listing-manager-modal');
            const $body = $('body');
            $modal.removeClass('active');
            $body.removeClass('active');
            setTimeout(() => {
                $('.mvl-modal-features-popup').remove();
                $body.removeClass('mvl-popup-overlay');
                $body.removeClass('mvl-popup-overlay-x2');
            }, 300);
        }

        $(document).on('click', '#mvl-cancel-btn-features', function (e) {
            e.preventDefault();
            if (modalChanged) {
                let confirmation = initConfirmationPopup(e.currentTarget);
                confirmation.on({
                    accept: function () { },
                    cancel: function () {
                        closeFeaturesModal();
                        modalChanged = false;
                    }
                });
                confirmation.open();
            } else {
                closeFeaturesModal();
            }
        });

        $(document).on('click', '#delete-features', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const $button = $(e.currentTarget);
            const optionId = $button.data('confirmation-slug');
            const title = $button.data('confirmation-title');
            const message = $button.data('confirmation-message');
            const accept = $button.data('confirmation-accept');
            const cancel = $button.data('confirmation-cancel');
            const deleteBtnIcon = $button.data('confirmation-delete-btn-icon');

            if (!optionId) {
                console.error('Option ID is missing for delete button');
                return;
            }

            const confirmationData = {
                title: title,
                message: message,
                accept: accept,
                cancel: cancel,
                deleteBtnIcon: deleteBtnIcon,
                slug: optionId,
            };

            let confirmation = initConfirmationPopup(e.currentTarget, confirmationData);
            confirmation.on({
                accept: function () { },
                cancel: function () {
                    deleteFeatureGroup(optionId);
                }
            });
            confirmation.open();
        });

        function deleteFeatureGroup(optionId) {
            showWrapperLoading();
            disableEditButton();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'listing_manager_delete_form',
                    nonce: nonce_get_form,
                    template: 'feature-group',
                    listing_manager_page_id: 'features',
                    option_id: decodeURIComponent(optionId)
                },
                success: function (response) {
                    if (response.success) {
                        $('.mvl-modal-features-popup, .mvl-confirmation-modal, .mvl-options-popup-container').remove();
                        $('body').removeClass('mvl-popup-overlay');
                        $('body').removeClass('mvl-popup-overlay-x2');

                        const ajaxData = Object.assign({}, getBaseAjaxData(), { template: 'edit-features' });
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: ajaxData,
                            success: function (response) {
                                if (response.success) {
                                    originalHtml = $wrapper.html();
                                    $wrapper.html(response.data.fields_html.join(''));
                                    setOriginalIndexes();

                                    const hasGroups = response.data.fields_html && response.data.fields_html.length > 0;

                                    if (hasGroups) {
                                        $editBtn
                                            .addClass('mvl-cancel-btn')
                                            .html(stm_vehicles_listing.cancel)
                                            .show();
                                    } else {
                                        $editBtn
                                            .removeClass('mvl-cancel-btn')
                                            .html(`
                                                <i class="motors-icons-mvl-pencil"></i>
                                                ${stm_vehicles_listing.edit_fields}
                                            `)
                                            .hide();
                                    }

                                    initSortable();
                                } else {
                                    console.error(response.data.message);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('AJAX Error:', error);
                            },
                            complete: function () {
                                hideWrapperLoading();
                                enableEditButton();
                            }
                        });
                    } else {
                        hideWrapperLoading();
                        enableEditButton();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    hideWrapperLoading();
                    enableEditButton();
                }
            });
        }

    });
})(jQuery);