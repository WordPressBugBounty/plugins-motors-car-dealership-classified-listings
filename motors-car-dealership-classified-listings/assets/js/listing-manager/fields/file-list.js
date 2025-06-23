class MVL_Listing_Manager_Field_File_List {
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

        let events = ['click'];

        for (let event of events) {
            document.addEventListener(event, this[event].bind(this));
        }
    }

    click(e) {
        let deleteButton = this.getParentDelete(e.target);

        if (deleteButton) {
            this.deleteItem(this.getParentItem(deleteButton));
        }
    }

    getParentContainer(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.container);
    }

    getContainer(node) {
        return node.querySelector('.' + this.classes.container);
    }

    getParentItem(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.item);
    }

    getParentDelete(node) {
        return MVL_Listing_Manager.getClosestByClass(node, this.classes.delete);
    }

    getInput(node) {
        return node.classList.contains(this.classes.input) ? node : false;
    }

    getItemImage(item) {
        return item.querySelector('.' + this.classes.image);
    }

    renderItem(container, data) {
        container.insertAdjacentHTML('beforeend', this.getItemTemplate(data));

        let input = container.querySelector('.' + this.classes.input + '.inserted');
        let item = this.getParentItem(input);

        if (data.file) {
            this.setFileToInput(input, data.file);
        }

        input.classList.remove('inserted');

        if (container.classList.contains(this.field.draggable.classes.container)) {
            item.classList.add(this.field.draggable.classes.item);
        }

        document.dispatchEvent(new CustomEvent(this.classes.item + '-rendered', {
            detail: {
                item: item,
                field: this.field,
            },
        }));

        return item;
    }

    renderFromFiles(container, data) {
        let files = Array.from(data.files);
        let filesLimit = container.dataset.fileslimit;

        if (filesLimit > 1) {
            data.inputName = data.inputName;
        }

        for (let file of files) {
            if (!this.isFileRendered(container, file)) {
                this.renderItem(container, {
                    url: container.dataset.itemimage,
                    file: file,
                    inputName: data.inputName,
                    image: data.image,
                });
            }
        }
    }

    isFileRendered(container, file) {
        let inputs = container.querySelectorAll('.' + this.classes.input);
        let isRendered = false;

        for (let input of inputs) {
            if (input.files && input.files[0].name === file.name && input.files[0].size === file.size) {
                isRendered = true;
            }
        }

        return isRendered;
    }

    getItemTemplate(data) {
        let input = this.getItemInputTemplate(data);
        let name = data.file ? data.file.name : data.name;

        return `
            <div class="${this.classes.item}">
                <div class="${this.classes.content}">
                    <img class="${this.classes.image}" src="${data.image}" alt="">
                    <span class="${this.classes.itemName}">${name}</span>
                </div>
                ${input}
            </div>
        `;
    }

    getItemInputTemplate(data) {
        if (data.file) {
            return `<input type="file" class="${this.classes.input} inserted" name="${data.inputName}">`;
        } else {
            return `<input type="hidden" class="${this.classes.input} inserted" name="${data.inputName}" value="${data.value}">`;
        }
    }

    setFileToInput(input, file) {
        let dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        input.files = dataTransfer.files;
    }

    deleteItem(item) {
        let container = this.field.getParentContainer(item);
        document.dispatchEvent(new CustomEvent(this.classes.item + '-deleted', {
            detail: {
                item: item,
                field: this.field,
            },
        }));
        item.remove();
        this.field.updateStatus(container);
    }

    getDefaultOptions() {
        return {
            classes: {
                container: 'mvl-listing-manager-field-file-list',
                item: 'mvl-listing-manager-field-file-list-item',
                delete: 'mvl-listing-manager-field-file-list-item-delete',
                input: 'mvl-listing-manager-field-file-list-item-input',
                image: 'mvl-listing-manager-field-file-list-item-image',
                content: 'mvl-listing-manager-field-file-list-item-content',
                itemName: 'mvl-listing-manager-field-file-list-item-name',
            },
            field: false,
        };
    }
}
