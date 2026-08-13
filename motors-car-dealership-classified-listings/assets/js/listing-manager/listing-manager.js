let MVL_Listing_Manager = {
    classes: {
        menuItem: 'mvl-listing-manager-sidebar-menu-item',
        page: 'mvl-listing-manager-content-body-page',
        activeMenuItem: 'active',
        activePage: 'active',
        nextPage: 'mvl-listing-manager-content-body-page-action-next',
        prevPage: 'mvl-listing-manager-content-body-page-action-prev',
        form: 'mvl-listing-manager-content',
    },
    nodes: {
        overlay: document.querySelector('.mvl-listing-manager-overlay'),
        saveProgress: document.querySelector('.mvl-listing-manager-save-progress'),
        saveProgressText: document.querySelector('.mvl-listing-manager-save-progress-text'),
        form: document.querySelector('form.mvl-listing-manager-content'),
    },
    nodes: {
        overlay: document.querySelector('.mvl-listing-manager-overlay'),
        saveProgress: document.querySelector('.mvl-listing-manager-save-progress'),
        saveProgressText: document.querySelector('.mvl-listing-manager-save-progress-text'),
        form: document.querySelector('form.mvl-listing-manager-content'),
    },
    wpmedia: false,
    draggable: false,
    pages: {},
    saveProgressTimeout: false,
    post_status: '',
    formChanged: false,
    initialFormData: {},

    isWpMediaAvailable: function () {
        try {
            return typeof wp !== 'undefined' &&
                typeof wp.media === 'function' &&
                typeof wp.media.prototype === 'object';
        } catch (e) {
            return false;
        }
    },

    getClosestByClass: function (node, className) {
        var item = false;
        var closest = node.closest('.' + className);

        if (node.classList.contains(className)) {
            item = node;
        } else if (closest !== undefined) {
            item = closest;
        }

        return item;
    },

    click: function (e) {
        let menuItem = this.getParentMenuItem(e.target);
        let nextPageButton = this.getParentNextPageButton(e.target);
        let prevPageButton = this.getParentPrevPageButton(e.target);
        let headerActionButton = this.getClosestByClass(e.target, 'mvl-listing-manager-content-header-action-btn');
        let previewCardActionButton = this.getClosestByClass(e.target, 'mvl-listing-preview-card-action');
        let rentalPriceField = e.target.closest && e.target.closest('[data-mvl-rental-price-field]');
        let rentalPriceToggle = e.target.closest && e.target.closest('[data-mvl-rental-price-toggle]');
        let rentalPriceOption = e.target.closest && e.target.closest('[data-mvl-rental-price-option]');
        let rentalPriceCustom = e.target.closest && e.target.closest('[data-mvl-rental-price-custom]');
        let rentalLocationAddOpen = e.target.closest && e.target.closest('[data-mvl-rental-location-add-open]');
        let rentalLocationAddClose = e.target.closest && e.target.closest('[data-mvl-rental-location-add-close]');
        let rentalLocationAddSubmit = e.target.closest && e.target.closest('[data-mvl-rental-location-add-submit]');

        if (rentalLocationAddOpen) {
            e.preventDefault();
            this.openRentalLocationModal();
            return;
        }

        if (rentalLocationAddClose) {
            e.preventDefault();
            this.closeRentalLocationModal();
            return;
        }

        if (rentalLocationAddSubmit) {
            e.preventDefault();
            this.createRentalLocation();
            return;
        }

        if (!rentalPriceField) {
            this.closeRentalPriceDropdowns();
        }

        if (rentalPriceToggle) {
            e.preventDefault();
            this.toggleRentalPriceDropdown(rentalPriceField);
        }

        if (rentalPriceOption) {
            e.preventDefault();
            this.selectRentalPriceClass(rentalPriceField, rentalPriceOption);
        }

        if (rentalPriceCustom) {
            e.preventDefault();
            this.selectRentalCustomPrice(rentalPriceField);
        }

        if (menuItem) {
            e.preventDefault();
            let pageID = menuItem.dataset.pageid;

            if (pageID) {
                if (pageID !== this.getCurrentPageID()) {
                    this.closeCurrentPage();
                    this.openPage(pageID);
                }
            }
        }

        if (nextPageButton) {
            e.preventDefault();
            this.openNextPage();
        }

        if (prevPageButton) {
            e.preventDefault();
            this.openPrevPage();
        }

        if (headerActionButton && headerActionButton.dataset && headerActionButton.dataset.status) {
            this.post_status = headerActionButton.dataset.status;
        } else if (previewCardActionButton) {
            this.post_status = previewCardActionButton.dataset.status;
        } else {
            this.post_status = listingManager.post_status;
        }
    },

    openPage: function (pageID) {
        let menuItem = document.querySelector('.' + this.classes.menuItem + '.' + pageID);
        let pageItem = document.querySelector('.' + this.classes.page + '.' + pageID);
        let nextPageButton = document.querySelector('.' + this.classes.nextPage);
        let prevPageButton = document.querySelector('.' + this.classes.prevPage);

        if (menuItem) {
            menuItem.classList.add(this.classes.activeMenuItem);
        }

        if (pageItem) {
            pageItem.classList.add(this.classes.activePage);
        }

        if (nextPageButton) {
            if (pageItem === this.getLastPage()) {
                if (!nextPageButton.classList.contains('disabled')) {
                    nextPageButton.classList.add('disabled');
                }
            } else {
                if (nextPageButton.classList.contains('disabled')) {
                    nextPageButton.classList.remove('disabled');
                }
            }
        }

        if (prevPageButton) {
            if (pageItem === this.getFirstPage()) {
                if (!prevPageButton.classList.contains('disabled')) {
                    prevPageButton.classList.add('disabled');
                }
            } else {
                if (prevPageButton.classList.contains('disabled')) {
                    prevPageButton.classList.remove('disabled');
                }
            }
        }

        this.updateUrl(this.nodes.form.querySelector('input[name="listing_id"]').value, this.getCurrentPageID());
    },

    closeCurrentPage: function () {
        let menuItem = document.querySelector('.' + this.classes.menuItem + '.active');
        let pageItem = document.querySelector('.' + this.classes.page + '.active');

        if (menuItem) {
            menuItem.classList.remove('active');
        }

        if (pageItem) {
            pageItem.classList.remove('active');
        }
    },

    getCurrentPage: function () {
        return document.querySelector('.' + this.classes.page + '.active');
    },

    getCurrentPageID: function () {
        let menuItem = document.querySelector('.' + this.classes.menuItem + '.active');
        let pageID = false;

        if (menuItem) {
            pageID = menuItem.dataset.pageid;
        }

        return pageID;
    },

    getParentMenuItem: function (node) {
        return this.getClosestByClass(node, this.classes.menuItem);
    },

    init: function () {
        let events = ['click'];

        for (let event of events) {
            document.addEventListener(event, this[event].bind(this));
        }
        if (this.isWpMediaAvailable()) {
            this.wpmedia = wp.media(listingManagerImageMediaArgs);
            this.wpmedia.open();
            this.wpmedia.close();
        }

        this.draggable = new MVL_Listing_Manager_Draggable_Items();

        this.imageField = new MVL_Listing_Manager_Field_Image({
            draggable: this.draggable
        });

        this.fileField = new MVL_Listing_Manager_Field_File({
            draggable: this.draggable
        });

        new MVL_Listing_Manager_Listing_Card();

        this.initialFormData = new FormData(this.nodes.form);

        this.nodes.form.addEventListener('submit', this.submit.bind(this));
        this.nodes.form.addEventListener('change', this.change.bind(this));
        this.nodes.form.addEventListener('input', this.change.bind(this));
        this.nodes.form.addEventListener('change', this.updateRentalLocationAddress.bind(this));
        this.nodes.form.addEventListener('input', this.handleRentalPriceInput.bind(this));

        let rentalLocationModal = this.getRentalLocationModal();
        if (rentalLocationModal) {
            rentalLocationModal.addEventListener('input', function (event) {
                event.stopPropagation();
            });
            rentalLocationModal.addEventListener('change', function (event) {
                event.stopPropagation();
            });
            rentalLocationModal.addEventListener('change', this.toggleRentalLocationHours.bind(this));
        }

        document.addEventListener('mvl-listing-manager-field-image-list-item-rendered', this.change.bind(this));
        document.addEventListener('mvl-listing-manager-field-image-list-item-deleted', this.change.bind(this));
        document.addEventListener('mvl-listing-manager-field-image-list-item-dragged', this.change.bind(this));
        document.addEventListener('mvl-listing-manager-field-image-cleared', this.change.bind(this));
        document.addEventListener('draggable-item-changed', this.change.bind(this));
        document.addEventListener('mvl-listing-manager-field-file-list-item-rendered', this.change.bind(this));
        document.addEventListener('mvl-listing-manager-field-file-list-item-deleted', this.change.bind(this));
        document.addEventListener('mvl-listing-manager-field-file-list-item-dragged', this.change.bind(this));
        document.addEventListener('mvl-listing-manager-field-file-cleared', this.change.bind(this));
        this.initFormChangeTracking();
        this.post_status = listingManager.post_status;
    },

    getRentalLocationModal: function () {
        return document.querySelector('[data-mvl-rental-location-add-modal]');
    },

    openRentalLocationModal: function () {
        let modal = this.getRentalLocationModal();
        let firstInput;

        if (!modal) {
            return;
        }

        modal.hidden = false;
        modal.classList.add('is-open');
        this.clearRentalLocationModalError();

        firstInput = modal.querySelector('[data-mvl-rental-location-add-field="name"]');
        if (firstInput) {
            firstInput.focus();
        }
    },

    closeRentalLocationModal: function () {
        let modal = this.getRentalLocationModal();

        if (!modal) {
            return;
        }

        modal.hidden = true;
        modal.classList.remove('is-open', 'is-loading');
        this.clearRentalLocationModalError();
    },

    clearRentalLocationModalError: function () {
        let modal = this.getRentalLocationModal();
        let error = modal ? modal.querySelector('[data-mvl-rental-location-add-error]') : null;

        if (error) {
            error.hidden = true;
            error.textContent = '';
        }
    },

    setRentalLocationModalError: function (message) {
        let modal = this.getRentalLocationModal();
        let error = modal ? modal.querySelector('[data-mvl-rental-location-add-error]') : null;

        if (error) {
            error.hidden = false;
            error.textContent = message;
        }
    },

    getRentalLocationModalData: function () {
        let modal = this.getRentalLocationModal();
        let data = {};

        if (!modal) {
            return data;
        }

        modal.querySelectorAll('[data-mvl-rental-location-add-field]').forEach(function (field) {
            data[field.dataset.mvlRentalLocationAddField] = field.value ? field.value.trim() : '';
        });

        data.hours = this.getRentalLocationModalHours(modal);

        return data;
    },

    getRentalLocationModalHours: function (modal) {
        let hours = {};

        modal.querySelectorAll('[data-mvl-rental-location-add-hours-day]').forEach(function (row) {
            let day = row.dataset.mvlRentalLocationAddHoursDay;
            let open = row.querySelector('[data-mvl-rental-location-add-hours-open]');
            let from = row.querySelector('[data-mvl-rental-location-add-hours-from]');
            let to = row.querySelector('[data-mvl-rental-location-add-hours-to]');

            if (!day) {
                return;
            }

            hours[day] = {
                open: !!(open && open.checked),
                from: from && from.value ? from.value : '08:00',
                to: to && to.value ? to.value : '20:00'
            };
        });

        return hours;
    },

    resetRentalLocationModal: function () {
        let modal = this.getRentalLocationModal();
        let defaultHours = {
            mon: { open: true, from: '08:00', to: '20:00' },
            tue: { open: true, from: '08:00', to: '20:00' },
            wed: { open: true, from: '08:00', to: '20:00' },
            thu: { open: true, from: '08:00', to: '20:00' },
            fri: { open: true, from: '08:00', to: '20:00' },
            sat: { open: true, from: '09:00', to: '18:00' },
            sun: { open: false, from: '09:00', to: '18:00' }
        };

        if (!modal) {
            return;
        }

        modal.querySelectorAll('[data-mvl-rental-location-add-field]').forEach(function (field) {
            field.value = field.tagName === 'SELECT' ? 'both' : '';
        });

        modal.querySelectorAll('[data-mvl-rental-location-add-hours-day]').forEach(function (row) {
            let day = row.dataset.mvlRentalLocationAddHoursDay;
            let defaults = defaultHours[day] || { open: true, from: '08:00', to: '20:00' };
            let open = row.querySelector('[data-mvl-rental-location-add-hours-open]');
            let from = row.querySelector('[data-mvl-rental-location-add-hours-from]');
            let to = row.querySelector('[data-mvl-rental-location-add-hours-to]');

            if (open) {
                open.checked = defaults.open;
            }

            if (from) {
                from.value = defaults.from;
                from.disabled = !defaults.open;
            }

            if (to) {
                to.value = defaults.to;
                to.disabled = !defaults.open;
            }
        });
    },

    toggleRentalLocationHours: function (event) {
        let toggle = event.target.matches && event.target.matches('[data-mvl-rental-location-add-hours-open]');
        let row;
        let isOpen;

        if (!toggle) {
            return;
        }

        row = event.target.closest('[data-mvl-rental-location-add-hours-day]');
        isOpen = event.target.checked;

        if (!row) {
            return;
        }

        row.querySelectorAll('[data-mvl-rental-location-add-hours-from], [data-mvl-rental-location-add-hours-to]').forEach(function (input) {
            input.disabled = !isOpen;
        });
    },

    createRentalLocation: function () {
        let modal = this.getRentalLocationModal();
        let submit = modal ? modal.querySelector('[data-mvl-rental-location-add-submit]') : null;
        let data = this.getRentalLocationModalData();
        let restUrl = listingManager && listingManager.rental_locations_rest_url ? listingManager.rental_locations_rest_url : '';
        let restNonce = listingManager && listingManager.rest_nonce ? listingManager.rest_nonce : '';

        if (!modal || !submit || !restUrl) {
            return;
        }

        this.clearRentalLocationModalError();

        if (!data.name) {
            this.setRentalLocationModalError('Location name is required.');
            return;
        }

        modal.classList.add('is-loading');
        submit.disabled = true;

        fetch(restUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': restNonce
            },
            body: JSON.stringify({
                name: data.name,
                address: data.address || '',
                phone: data.phone || '',
                email: data.email || '',
                type: data.type || 'both',
                hours: data.hours || {},
                is_active: 1
            })
        }).then(function (response) {
            return response.json().then(function (body) {
                if (!response.ok) {
                    throw body;
                }

                return body;
            });
        }).then(function (location) {
            MVL_Listing_Manager.addRentalLocationToSelect(location);
            MVL_Listing_Manager.resetRentalLocationModal();
            MVL_Listing_Manager.closeRentalLocationModal();
        }).catch(function (error) {
            let message = error && error.message ? error.message : 'Location could not be added.';
            MVL_Listing_Manager.setRentalLocationModalError(message);
        }).finally(function () {
            modal.classList.remove('is-loading');
            submit.disabled = false;
        });
    },

    addRentalLocationToSelect: function (location) {
        let select = this.nodes.form.querySelector('[data-mvl-rental-location-select]');
        let option;
        let label;
        let address;

        if (!select || !location || !location.id) {
            return;
        }

        address = location.address || '';
        label = address ? location.name + ' - ' + address : location.name;
        option = new Option(label, location.id, true, true);
        option.dataset.address = label;

        select.appendChild(option);

        if (window.jQuery && jQuery.fn && typeof jQuery.fn.select2 === 'function') {
            jQuery(select).val(location.id).trigger('change').trigger('change.select2');
        } else {
            select.value = location.id;
            select.dispatchEvent(new Event('change', { bubbles: true }));
        }

        this.updateRentalLocationAddress({ target: select });
        this.change();
    },

    updateRentalLocationAddress: function (event) {
        let select = event.target.matches && event.target.matches('[data-mvl-rental-location-select]');

        if (!select) {
            return;
        }

		let addressField = this.nodes.form.querySelector('[data-mvl-rental-location-address]') || this.nodes.form.querySelector('#stm_car_location');
		let selectedOption = event.target.options[event.target.selectedIndex];

		if (addressField && selectedOption) {
			addressField.value = selectedOption.dataset.address || '';
			addressField.dispatchEvent(new Event('input', { bubbles: true }));
		}

        this.change();
    },

    handleRentalPriceInput: function (event) {
        let field = event.target.closest && event.target.closest('[data-mvl-rental-price-field]');

        if (!field) {
            return;
        }

        if (event.target.matches('[data-mvl-rental-price-search]')) {
            this.filterRentalPriceOptions(field, event.target.value);
            return;
        }

        if (event.target.matches('[data-mvl-rental-price-custom-rate]') || event.target.matches('[data-mvl-rental-price-custom-deposit]')) {
            this.syncRentalCustomPrice(field);
        }
    },

    toggleRentalPriceDropdown: function (field) {
        if (!field) {
            return;
        }

        let dropdown = field.querySelector('[data-mvl-rental-price-dropdown]');
        let toggle = field.querySelector('[data-mvl-rental-price-toggle]');
        let isOpen = field.classList.contains('is-open');

        this.closeRentalPriceDropdowns();

        if (!isOpen && dropdown) {
            field.classList.add('is-open');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'true');
            }

            requestAnimationFrame(function () {
                let search = field.querySelector('[data-mvl-rental-price-search]');
                if (search) {
                    search.focus();
                }
            });
        }
    },

    closeRentalPriceDropdowns: function () {
        document.querySelectorAll('[data-mvl-rental-price-field].is-open').forEach(function (field) {
            field.classList.remove('is-open');
            let toggle = field.querySelector('[data-mvl-rental-price-toggle]');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    },

    selectRentalPriceClass: function (field, option) {
        if (!field || !option) {
            return;
        }

        this.setRentalHiddenValue(field, '[data-mvl-rental-price-mode]', 'class');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-class]', option.dataset.id || '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-rate]', option.dataset.daily || '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-deposit]', option.dataset.deposit || '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-pay-later]', option.dataset.deposit || '');

        field.querySelectorAll('[data-mvl-rental-price-option], [data-mvl-rental-price-custom]').forEach(function (item) {
            item.classList.remove('is-selected');
        });
        option.classList.add('is-selected');

        field.querySelector('[data-mvl-rental-price-custom-fields]').classList.remove('is-open');
        this.updateRentalPriceTrigger(field, option.dataset.abbr || '', option.dataset.name || '', this.formatRentalPrice(option.dataset.daily, field) + ' / day - Pay later ' + this.formatRentalPrice(option.dataset.deposit, field));
        this.updateRentalTariffPreview(option.dataset.daily, field);
        this.closeRentalPriceDropdowns();
        this.dispatchNativeEvent(field.querySelector('[data-mvl-rental-price-rate]'), 'change');
        this.change();
    },

    selectRentalCustomPrice: function (field) {
        if (!field) {
            return;
        }

        let customFields = field.querySelector('[data-mvl-rental-price-custom-fields]');
        let customRate = field.querySelector('[data-mvl-rental-price-custom-rate]');
        let customDeposit = field.querySelector('[data-mvl-rental-price-custom-deposit]');

        this.setRentalHiddenValue(field, '[data-mvl-rental-price-mode]', 'custom');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-class]', '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-rate]', customRate ? customRate.value : '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-deposit]', customDeposit ? customDeposit.value : '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-pay-later]', customDeposit ? customDeposit.value : '');

        field.querySelectorAll('[data-mvl-rental-price-option], [data-mvl-rental-price-custom]').forEach(function (item) {
            item.classList.remove('is-selected');
        });
        field.querySelector('[data-mvl-rental-price-custom]').classList.add('is-selected');

        if (customFields) {
            customFields.classList.add('is-open');
        }

        this.updateRentalPriceTrigger(field, '$', 'Custom price', customRate && customRate.value ? this.formatRentalPrice(customRate.value, field) + ' / day' : 'Set your own rate');
        this.updateRentalTariffPreview(customRate ? customRate.value : '', field);
        this.closeRentalPriceDropdowns();
        this.dispatchNativeEvent(field.querySelector('[data-mvl-rental-price-rate]'), 'change');
        this.change();
    },

    syncRentalCustomPrice: function (field) {
        let customRate = field.querySelector('[data-mvl-rental-price-custom-rate]');
        let customDeposit = field.querySelector('[data-mvl-rental-price-custom-deposit]');
        let rate = customRate ? customRate.value : '';

        this.setRentalHiddenValue(field, '[data-mvl-rental-price-mode]', 'custom');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-class]', '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-rate]', rate);
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-deposit]', customDeposit ? customDeposit.value : '');
        this.setRentalHiddenValue(field, '[data-mvl-rental-price-pay-later]', customDeposit ? customDeposit.value : '');
        this.updateRentalPriceTrigger(field, '$', 'Custom price', rate ? this.formatRentalPrice(rate, field) + ' / day' : 'Set your own rate');
        this.updateRentalTariffPreview(rate, field);
        this.change();
    },

    filterRentalPriceOptions: function (field, query) {
        let value = (query || '').toLowerCase();

        field.querySelectorAll('[data-mvl-rental-price-option], [data-mvl-rental-price-custom]').forEach(function (option) {
            let search = option.getAttribute('data-search') || '';
            option.hidden = value && search.indexOf(value) === -1;
        });
    },

    setRentalHiddenValue: function (field, selector, value) {
        let input = field.querySelector(selector);
        if (input) {
            input.value = value;
        }
    },

    updateRentalPriceTrigger: function (field, abbr, name, sub) {
        let abbrNode = field.querySelector('[data-mvl-rental-price-trigger-abbr]');
        let nameNode = field.querySelector('[data-mvl-rental-price-trigger-name]');
        let subNode = field.querySelector('[data-mvl-rental-price-trigger-sub]');

        if (abbrNode) {
            abbrNode.textContent = abbr || '-';
        }

        if (nameNode) {
            nameNode.textContent = name || 'Select a class';
        }

        if (subNode) {
            subNode.textContent = sub || '';
        }
    },

    updateRentalTariffPreview: function (rate, field) {
        let value = document.querySelector('[data-mvl-rental-tariff-value]');
        let unit = document.querySelector('[data-mvl-rental-tariff-unit]');

        if (value) {
            value.textContent = rate ? this.formatRentalPrice(rate, field) : '-';
        }

        if (unit) {
            unit.textContent = rate ? '/ day' : '';
        }
    },

    formatRentalPrice: function (value, field) {
        let amount = parseFloat(value);
        let symbol = field ? field.getAttribute('data-currency-symbol') || '$' : '$';
        let position = field ? field.getAttribute('data-currency-position') || 'left' : 'left';

        if (isNaN(amount)) {
            return symbol + '0';
        }

        amount = Math.round(amount).toString();

        return position === 'right' ? amount + symbol : symbol + amount;
    },

    dispatchNativeEvent: function (element, eventName) {
        if (!element) {
            return;
        }

        let event;

        if (typeof Event === 'function') {
            event = new Event(eventName, { bubbles: true });
        } else {
            event = document.createEvent('HTMLEvents');
            event.initEvent(eventName, true, false);
        }

        element.dispatchEvent(event);
    },

    change: function () {
        this.formChanged = true;

        this.showPublishButtons();
        this.showDraftButtons();
        if (this.post_status) {
            this.showTrashButtons();
        }
        if (this.post_status !== 'publish') {
            this.hidePreviewButtons();
        }
    },

    getPreviewButtons: function () {
        return this.nodes.form.querySelectorAll('.mvl-listing-preview-button');
    },

    showPreviewButtons: function (url) {
        let previewButtons = this.getPreviewButtons();

        for (let button of previewButtons) {
            if (button.classList.contains('disabled')) {
                button.href = url;
                button.classList.remove('disabled');
            }
        }
    },

    hidePreviewButtons: function () {
        let previewButtons = this.getPreviewButtons();
        for (let button of previewButtons) {
            if (!button.classList.contains('disabled')) {
                button.classList.add('disabled');
            }
        }
    },

    showTrashButtons: function () {
        let trashButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-delete-btn');
        for (let button of trashButtons) {
            if (button.classList.contains('disabled')) {
                button.classList.remove('disabled');
            }
        }
    },

    hideTrashButtons: function () {
        let trashButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-delete-btn');
        for (let button of trashButtons) {
            if (!button.classList.contains('disabled')) {
                button.classList.add('disabled');
            }
        }
    },

    showPublishButtons: function () {
        let publishButtons = this.nodes.form.querySelectorAll('[data-status="publish"]');
        for (let button of publishButtons) {
            if (button.classList.contains('disabled')) {
                button.classList.remove('disabled');
            }
        }
    },

    hidePublishButtons: function () {
        let publishButtons = this.nodes.form.querySelectorAll('[data-status="publish"]');
        for (let button of publishButtons) {
            if (!button.classList.contains('disabled')) {
                button.classList.add('disabled');
            }
        }
    },

    showDraftButtons: function () {
        let draftButtons = this.nodes.form.querySelectorAll('[data-status="draft"]');
        for (let button of draftButtons) {
            if (button.classList.contains('disabled')) {
                button.classList.remove('disabled');
            }
        }
    },

    hideDraftButtons: function () {
        let draftButtons = this.nodes.form.querySelectorAll('[data-status="draft"]');
        for (let button of draftButtons) {
            if (!button.classList.contains('disabled')) {
                button.classList.add('disabled');
            }
        }
    },

    isHasChanged: function () {
        return this.formChanged;
    },

    submit: async function (e) {
        e.preventDefault();
        requestAnimationFrame(() => {
            if (this.nodes.form.querySelector('input#title').value === '') {
                this.nodes.form.querySelector('input#title').value = listingManager.text.untitled;
            }

            if (this.post_status) {
                if (this.post_status === 'trash') {
                    let trashButton = this.nodes.form.querySelector('.mvl-listing-preview-card-action.mvl-delete-btn');
                    let confirmation = initConfirmationPopup(trashButton);
                    let ths = this;
                    confirmation.on({
                        cancel: function () {
                            ths.showPreloader();
                            ths.sendFormData({
                                post_status: ths.post_status
                            });
                            confirmation.close();
                        }
                    });

                    confirmation.open();
                } else {
                    this.showPreloader();
                    this.sendFormData({
                        post_status: this.post_status
                    });
                }
            } else {
                alert('Post status is required');
            }
        });
    },

    showPreloader: function () {
        this.nodes.overlay.setAttribute('data-show', 'true');
        this.nodes.saveProgress.setAttribute('data-show', 'true');
        this.nodes.saveProgress.setAttribute('data-status', 'progress');
        this.nodes.saveProgressText.textContent = listingManager.text.save_in_progress;
    },

    hidePreloader: function () {
        this.nodes.overlay.setAttribute('data-show', 'false');
        this.nodes.saveProgress.setAttribute('data-status', 'saved');
        this.nodes.saveProgressText.textContent = listingManager.text.save_success;

        if (this.saveProgressTimeout) {
            clearTimeout(this.saveProgressTimeout);
        }
        this.saveProgressTimeout = setTimeout(() => {
            this.nodes.saveProgress.setAttribute('data-show', 'false');
        }, 3000);
    },

    sendFormData: function (args = {}) {
        let formData = new FormData(this.nodes.form);

        if (args.post_id) {
            formData.append('listing_id', args.post_id);
        }

        if (args.progress_page) {
            formData.append('progress_page', args.progress_page);
        }

        if (args.post_status) {
            formData.append('post_status', args.post_status);
        }

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(res => {
            let data = res.data;
            if (data.post_id) {
                this.nodes.form.querySelector('input[name="listing_id"]').value = data.post_id;
            }

            if (res.success) {
                if (data.uploaded_item) {
                    if (data.uploaded_item.input_name) {
                        let input = document.querySelector('input[type="file"][name="' + data.uploaded_item.input_name + '"]');
                        if (input) {
                            input.file = null;
                            input.type = 'hidden';
                            input.value = data.uploaded_item.id;
                        }
                    }
                    if (data.callback) {
                        this[data.callback.name](data.callback.args);
                    }
                } else {
                    this.successSaved(data);
                }
            }

            this.hidePreloader();
        });
    },

    successSaved: function (data) {
        listingManager.post_status = data.post_status;
        this.post_status = data.post_status;

        let deleteButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-delete-btn');
        let publishButtons = this.nodes.form.querySelectorAll('[data-status="publish"]');
        let draftButtons = this.nodes.form.querySelectorAll('[data-status="draft"]');
        let backLink = document.querySelector('.mvl-listing-manager-sidebar-back-link');
        let previewButtons = this.getPreviewButtons();
        let previewLink = data.preview_url;

        backLink.href = data.back_link.replace('amp;', '');

        switch (data.post_status) {
            case 'trash':
                for (let button of publishButtons) {
                    button.textContent = listingManager.text.publish_button;
                }
                for (let button of draftButtons) {
                    button.textContent = listingManager.text.draft_button;
                }
                for (let button of previewButtons) {
                    button.innerHTML = '<i class="motors-icons-mvl-eye"></i>' + listingManager.text.preview;
                    button.href = previewLink;
                }
                for (let button of deleteButtons) {
                    button.innerHTML = '<i class="motors-icons-mvl-trash"></i>' + listingManager.text.trashed_button;
                    if (!button.classList.contains('disabled')) {
                        button.classList.add('disabled');
                    }
                }
                this.hideTrashButtons();
                this.showPublishButtons();
                this.showDraftButtons();
                this.hidePreviewButtons();
                break;
            case 'publish':
                for (let button of publishButtons) {
                    button.textContent = listingManager.text.published_button;
                }
                for (let button of draftButtons) {
                    button.innerHTML = listingManager.text.draft_button;
                }
                for (let button of deleteButtons) {
                    button.innerHTML = '<i class="motors-icons-mvl-trash"></i>' + listingManager.text.trash_button;
                }
                for (let button of previewButtons) {
                    button.innerHTML = '<i class="motors-icons-mvl-eye"></i>' + listingManager.text.view;
                    button.href = previewLink;
                }
                this.hidePublishButtons();
                this.showDraftButtons();
                this.showTrashButtons();
                this.showPreviewButtons(data.preview_url);
                break;
            case 'draft':
                for (let button of publishButtons) {
                    button.textContent = listingManager.text.publish_button;
                }
                for (let button of draftButtons) {
                    button.innerHTML = listingManager.text.drafted_button;
                }
                for (let button of deleteButtons) {
                    button.innerHTML = '<i class="motors-icons-mvl-trash"></i>' + listingManager.text.trash_button;
                }
                for (let button of previewButtons) {
                    button.innerHTML = '<i class="motors-icons-mvl-eye"></i>' + listingManager.text.preview;
                    button.href = previewLink;
                }
                this.hideDraftButtons();
                this.showPublishButtons();
                this.showTrashButtons();
                this.showPreviewButtons(data.preview_url);
                break;
        }

        this.updateUrl(data.post_id, this.getCurrentPageID());
    },

    updateUrl: function (post_id, page) {
        let url = listingManager.url;
        let params = [];

        if (post_id * 1) {
            params.push('id=' + post_id);
        }
        if (page) {
            params.push('page=' + page);
        }
        if (params.length) {
            url += '&' + params.join('&');
        }

        window.history.pushState({}, '', url);

        this.initFormChangeTracking();
    },

    initFormChangeTracking: function () {
        const form = this.nodes.form;
        if (!form) return;
        form.addEventListener('change', (e) => {
            if (!this.getClosestByClass(e.target, 'mvl-lm-search-field-input')) {
                this.formChanged = true;
            }
        });

        form.addEventListener('input', (e) => {
            if (!this.getClosestByClass(e.target, 'mvl-lm-search-field-input')) {
                this.formChanged = true;
            }
        });

        form.addEventListener('submit', (e) => {
            this.formChanged = false;
        });

        window.addEventListener('beforeunload', (e) => {
            if (this.formChanged) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });
    },

    openPrevPage() {
        let prevPage = this.getPrevPage();

        if (prevPage) {
            this.closeCurrentPage();
            this.openPage(prevPage.dataset.pageid);
        }
    },

    openNextPage() {
        let nextPage = this.getNextPage();

        if (nextPage) {
            this.closeCurrentPage();
            this.openPage(nextPage.dataset.pageid);
        }
    },

    getPrevPage: function () {
        return this.getCurrentPage().previousElementSibling;
    },

    getNextPage: function () {
        return this.getCurrentPage().nextElementSibling;
    },

    getParentNextPageButton: function (node) {
        return this.getClosestByClass(node, this.classes.nextPage);
    },

    getParentPrevPageButton: function (node) {
        return this.getClosestByClass(node, this.classes.prevPage);
    },

    getLastPage: function () {
        let pages = document.querySelectorAll('.' + this.classes.page);
        return pages[pages.length - 1];
    },

    getFirstPage: function () {
        let pages = document.querySelectorAll('.' + this.classes.page);
        return pages[0];
    },

    capitalizeFirstLetter: function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
}

MVL_Listing_Manager.init();
