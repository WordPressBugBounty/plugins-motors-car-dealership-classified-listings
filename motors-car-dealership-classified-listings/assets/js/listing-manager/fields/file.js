class MVL_Listing_Manager_Field_File {
    constructor(options = {}) {
        let defaults = this.getDefaultOptions();

        if (options.classes) {
            for (let option in options.classes) {
                defaults.classes[option] = options.classes[option];
            }
        }

        options.classes = defaults.classes;

        for (let option in options) {
            defaults[option] = options[option];
        }

        for (let key in defaults) {
            this[key] = defaults[key];
        }

        let events = ['click', 'change', 'cancel'];

        for (let event of events) {
            document.body.addEventListener(event, this[event].bind(this));
        }

        if (MVL_Listing_Manager.isWpMediaAvailable()) {
            MVL_Listing_Manager.wpmedia.on('select', this.mediaSelected.bind(this));
            MVL_Listing_Manager.wpmedia.on('close', this.mediaClosed.bind(this));
        }
    }

    cancel(e) {
        let container = this.getParentContainer(e.target);

        if (container) {
            let uploadInput = this.getUploadInput(container);

            if (uploadInput && container.classList.contains('change-process')) {
                container.classList.remove('change-process');
            }
        }
    }

    click(e) {
        let clearButton = this.getParentClearButton(e.target);
        let uploadButton = this.getParentUploadButton(e.target);
        let changeButton = this.getParentChangeButton(e.target);


        if (clearButton) {
            let container = this.getParentContainer(clearButton);
            this.clear(container);
        }

        if (uploadButton) {
            this.openUpload(uploadButton);
        }

        if (changeButton) {
            this.openUpload(changeButton);
            let container = this.getParentContainer(changeButton);
            container.classList.add('change-process');
        }
    }

    change(e) {
        let container = this.getParentContainer(e.target);

        if (container) {
            let uploadInput = this.getUploadInput(container);

            if (uploadInput) {
                if (container.classList.contains('change-process')) {
                    this.clear(container);
                    container.classList.remove('change-process');
                }

                this.uploadFiles(uploadInput.files, container);
                uploadInput.files = new DataTransfer().files;
            }
        }

    }

    async ondropfiles(e, dropZone) {
        let container = this.getParentContainer(dropZone);

        document.dispatchEvent(new CustomEvent(this.classes.container + '-dropfiles', {
            detail: {
                dropZone: dropZone,
                field: this,
            },
        }));

        this.uploadFiles(e.dataTransfer.files, container);
    }

    openUpload(button) {
        let container = this.getParentContainer(button);
        if (MVL_Listing_Manager.isWpMediaAvailable()) {
            this.openWpMedia(container);
        } else {
            this.getUploadInput(container).click();
        }
    }

    openWpMedia(container) {
        let wpmedia = MVL_Listing_Manager.wpmedia;

        this.updateWpMediaSettings(wpmedia, container);

        wpmedia.open();
        this.currentContainer = container;
    }


    updateWpMediaSettings(wpmedia, container) {
        let state = wpmedia.state();
        let isMultiple = this.isMultiple(container);
        let selection = state.get('selection');
        let view = state.get('view');
        let library = state.get('library');

        library.props.set('type', this.wpMediaArgs.library.type);

        state.set('multiple', isMultiple);

        if (selection) {
            selection.multiple = isMultiple;
            selection.reset();
        }

        if (view) {
            view.refresh();
        }
    }

    mediaSelected() {
        if (this.currentContainer) {
            let itemImage = this.currentContainer.dataset.itemimage;
            let state = MVL_Listing_Manager.wpmedia.state();
            let selection = state.get('selection');
            let images = selection.toJSON().map(attachment => ({
                id: attachment.id,
                value: attachment.id,
                url: attachment.sizes.large ? attachment.sizes.large.url : attachment.url,
                inputName: this.currentContainer.dataset.inputname,
                name: attachment.filename,
                image: itemImage,
            }));

            if (!this.isMultiple(this.currentContainer)) {
                this.clear(this.currentContainer);
            }

            for (let image of images) {
                if (!this.isWpMediaItemRendered(this.currentContainer, image)) {
                    this.list.renderItem(this.list.getContainer(this.currentContainer), image);
                } else {
                    alert(stm_vehicles_listing_errors.same_file);
                }
            }

            this.updateStatus(this.currentContainer);
            this.currentContainer = false;
        }
    }

    mediaClosed() {
        requestAnimationFrame(() => {
            this.currentContainer = false;
        });
    }

    isWpMediaItemRendered(container, item) {
        let inputs = container.querySelectorAll('.' + this.list.classes.input);
        let isRendered = false;

        for (let input of inputs) {
            if (input.value * 1 === item.id * 1) {
                isRendered = true;
                break;
            }
        }

        return isRendered;
    }

    uploadFiles(files, container) {
        let validationResult = (new MVL_Listing_Manager_File_Validator(files, this.getValidationParams(container))).validateAll();
        let listContainer = this.list.getContainer(container);

        if (validationResult.errors.length === 0) {
            this.list.renderFromFiles(listContainer, {
                files: validationResult.files,
                inputName: container.dataset.inputname,
                image: container.dataset.itemimage,
            });

            this.updateStatus(container);
        }
    }

    updateStatus(container) {
        let items = container.querySelectorAll('.' + this.list.classes.item);

        if (items.length > 0) {
            if (!container.classList.contains(this.classes.hasFiles)) {
                container.classList.add(this.classes.hasFiles);
            }
        } else {
            if (container.classList.contains(this.classes.hasFiles)) {
                container.classList.remove(this.classes.hasFiles);
            }
        }

        if (items.length >= container.dataset.fileslimit * 1) {
            container.classList.add(this.classes.limitExceeded);
        } else {
            container.classList.remove(this.classes.limitExceeded);
        }
    }

    getParentContainer(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.container);
    }

    getParentDropZone(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.dropZone);
    }

    getParentUploadButton(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.uploadButton);
    }

    getParentClearButton(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.clearButton);
    }

    getParentChangeButton(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.changeButton);
    }

    getParentWpMediaSelectButton(node) {
        return MVL_Listing_Manager.getClosestByClass(node, 'media-button-select');
    }

    getDropZone(node) {
        return node.querySelector('.' + this.classes.dropZone);
    }

    getUploadInput(node) {
        return node.querySelector('.' + this.classes.uploadInput);
    }

    getValidationParams(container) {
        return {
            maxSize: container.dataset.filesize * 1,
            maxFiles: container.dataset.fileslimit * 1,
            allowedType: this.getSupportType(container),
            errorCallback: this.showError.bind(this),
            maxSizeMB: container.dataset.filesizemb * 1,
        };
    }

    clear(container) {
        let listContainer = this.list.getContainer(container);
        listContainer.innerHTML = '';
        this.updateStatus(container);

        document.dispatchEvent(new CustomEvent(this.classes.container + '-cleared', {
            detail: {
                field: this,
                container: container,
            },
        }));
    }


    isMultiple(container) {
        let data = this.getValidationParams(container);
        let isMultiple = false;

        if (data.maxFiles > 1) {
            isMultiple = true;
        }

        return isMultiple;
    }

    getSupportType(container) {
        return MVL_Listing_Manager_File_Validator.types[container.dataset.allowedtype];
    }

    getDefaultOptions() {
        return {
            accept: '',
            classes: {
                container: 'mvl-listing-manager-field-file',
                dropZone: 'mvl-listing-manager-field-file-dropzone',
                uploadButton: 'mvl-listing-manager-field-file-upload-btn',
                uploadInput: 'mvl-listing-manager-field-file-upload-input',
                clearButton: 'mvl-listing-manager-field-file-list-clear-btn',
                changeButton: 'mvl-listing-manager-field-file-change-btn',
                hasFiles: 'has-files',
                limitExceeded: 'limit-exceeded',
            },
            dropZone: new MVL_Listing_Manager_Drop_Zone({
                dropZoneClass: 'mvl-listing-manager-field-file-dropzone',
                onDrop: this.ondropfiles.bind(this),
            }),
            list: new MVL_Listing_Manager_Field_File_List({
                field: this,
            }),
            wpMediaArgs: listingManagerFileMediaArgs,
        };
    }

    showError(errors) {
        let confirmationPopup = new ConfirmationPopup({
            text: {
                title: listingManager.text.error_popup_title,
                message: errors.join('<br>'),
                accept: false,
                cancel: listingManager.text.error_popup_cancel,
                slug: 'error-popup',
            },
        });

        confirmationPopup.open();
    }
}