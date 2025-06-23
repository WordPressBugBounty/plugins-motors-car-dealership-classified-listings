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

        if (headerActionButton) {
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
        let publishButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-primary-btn, .mvl-primary-btn.mvl-listing-manager-content-header-action-btn');
        for (let button of publishButtons) {
            if (button.classList.contains('disabled')) {
                button.classList.remove('disabled');
            }
        }
    },

    hidePublishButtons: function () {
        let publishButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-primary-btn, .mvl-primary-btn.mvl-listing-manager-content-header-action-btn');
        for (let button of publishButtons) {
            if (!button.classList.contains('disabled')) {
                button.classList.add('disabled');
            }
        }
    },

    showDraftButtons: function () {
        let draftButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-thirdary-btn');
        for (let button of draftButtons) {
            if (button.classList.contains('disabled')) {
                button.classList.remove('disabled');
            }
        }
    },

    hideDraftButtons: function () {
        let draftButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-thirdary-btn');
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
        let publishButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-primary-btn, .mvl-primary-btn.mvl-listing-manager-content-header-action-btn');
        let draftButtons = this.nodes.form.querySelectorAll('.mvl-listing-preview-card-actions .mvl-thirdary-btn');
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
            url += '?' + params.join('&');
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
