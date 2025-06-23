class MVL_Listing_Manager_File_Validator {

    constructor(files, options) {
        let defaults = {
            maxSize: 10 * 1024 * 1024,
            allowedType: false,
            maxFiles: 10,
            maxSizeError: stm_vehicles_listing_errors.large_file_size,
            allowedTypeError: stm_vehicles_listing_errors.not_allowed_file_type,
            maxFilesError: stm_vehicles_listing_errors.files_limit,
            noAllowedTypeError: stm_vehicles_listing_errors.no_allowed_type,
            types: MVL_Listing_Manager_File_Validator.types,
            errorCallback: false,
            maxSizeMB: 10,
        };

        for (var option in options) {
            defaults[option] = options[option];
        }

        for (var option in defaults) {
            this[option] = defaults[option];
        }

        this.files = files;
    }

    validate(file) {
        var errors = [];

        if (this.allowedType) {
            if (!this.allowedType.includes(file.type)) {
                errors.push(this.allowedTypeError);
            }
        } else {
            errors.push(this.noAllowedTypeError);
        }

        if (file.size > this.maxSize) {
            errors.push(this.maxSizeError + ' ' + this.maxSizeMB + 'MB');
        }

        return errors;
    }

    validateAll() {
        var errors = [];
        var validFiles = new DataTransfer();
        var totalSize = 0;

        for (var fileKey in this.files) {
            var file = this.files[fileKey];

            if (file.type) {
                var fileErrors = this.validate(file);

                if (fileErrors.length === 0) {
                    if (validFiles.items.length < this.maxFiles * 1) {
                        validFiles.items.add(file);
                        totalSize += file.size;
                    } else {
                        if (!errors.includes(this.maxFilesError + this.maxFiles)) {
                            errors.push(this.maxFilesError + this.maxFiles);
                        }
                    }
                } else {
                    for (var error of fileErrors) {
                        errors.push(error);
                    }
                }
            }
        }

        if (errors.length > 0) {
            if (this.errorCallback) {
                this.errorCallback(errors);
            } else {
                alert(errors.join('\n'));
            }
        }

        return {
            files: validFiles.files,
            errors: errors,
        };
    }
}

MVL_Listing_Manager_File_Validator.types = {
    image: ['image/png', 'image/jpeg', 'image/jpg'],
    pdf: ['application/pdf'],
    audio: ['audio/mpeg', 'audio/mp3', 'audio/mpga', 'audio/m4a', 'audio/ogg', 'audio/wav', 'audio/webm'],
    video: ['video/mp4', 'video/webm', 'video/ogg'],
    document: ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'],
};
