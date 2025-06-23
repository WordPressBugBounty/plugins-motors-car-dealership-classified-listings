class MVL_Listing_Manager_Field_Image extends MVL_Listing_Manager_Field_File {
    getDefaultOptions() {
        return {
            classes: {
                container: 'mvl-listing-manager-field-image',
                dropZone: 'mvl-listing-manager-field-image-dropzone',
                uploadButton: 'mvl-listing-manager-field-image-upload-btn',
                uploadInput: 'mvl-listing-manager-field-image-upload-input',
                clearButton: 'mvl-listing-manager-field-image-list-clear-btn',
                changeButton: 'mvl-listing-manager-field-image-change-btn',
                hasFiles: 'has-files',
                limitExceeded: 'limit-exceeded',
            },
            dropZone: new MVL_Listing_Manager_Drop_Zone({
                dropZoneClass: 'mvl-listing-manager-field-image-dropzone',
                onDrop: this.ondropfiles.bind(this),
            }),
            list: new MVL_Listing_Manager_Field_Image_List({
                field: this,
            }),
            wpMediaArgs: listingManagerImageMediaArgs,
        };
    }
}