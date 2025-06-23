class MVL_Listing_Manager_Field_Image_List extends MVL_Listing_Manager_Field_File_List {
    getItemTemplate(data) {
        let input = this.getItemInputTemplate(data);

        return `
            <div class="${this.classes.item}">
                <div class="${this.classes.content}">
                    <img class="${this.classes.image}" src="${data.url}" alt="">
                    <span class="${this.classes.delete}"></span>
                </div>
                ${input}
            </div>
        `;
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
                    url: URL.createObjectURL(file),
                    file: file,
                    inputName: data.inputName,
                });
            } else {
                alert(stm_vehicles_listing_errors.same_file);
            }
        }
    }

    getDefaultOptions() {
        return {
            classes: {
                container: 'mvl-listing-manager-field-image-list',
                item: 'mvl-listing-manager-field-image-list-item',
                delete: 'mvl-listing-manager-field-image-list-item-delete',
                input: 'mvl-listing-manager-field-image-list-item-input',
                image: 'mvl-listing-manager-field-image-list-item-image',
                content: 'mvl-listing-manager-field-image-list-item-content',
            },
            field: false,
        };
    }
}
