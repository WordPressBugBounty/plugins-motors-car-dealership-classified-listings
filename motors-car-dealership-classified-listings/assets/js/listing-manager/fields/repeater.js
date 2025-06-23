class MVL_Listing_Manager_Repeater {
    constructor() {
        this.classes = {
            addButton: 'repeater-add-btn',
            deleteButton: 'repeater-delete-btn',
        };

        document.addEventListener('click', this.click.bind(this));
    }

    click(e) {
        let addButton = this.getParentAddButton(e.target);
        let deleteButton = this.getParentDeleteButton(e.target);

        if (addButton) {
            this.addItem(addButton);
        }

        if (deleteButton) {
            this.deleteVideo(deleteButton);
        }
    }

    deleteVideo(deleteButton) {
        let fieldsGroups = this.getFieldsGroups(deleteButton);
        let groupToDelete = this.getParentFieldsGroup(deleteButton);

        if (fieldsGroups.length > 1 && fieldsGroups[0] !== groupToDelete) {
            groupToDelete.remove();
        }

        let addButton = document.querySelector(`.${this.classes.addButton}[data-groupclass="${deleteButton.dataset.groupclass}"]`);

        if (addButton && addButton.hasAttribute('style') && fieldsGroups.length - 1 < this.getLimit(addButton)) {
            addButton.removeAttribute('style');
        }
    }

    addItem(addButton) {
        let fieldsGroups = this.getFieldsGroups(addButton);
        let template = this.getTemplate(addButton);

        if (fieldsGroups.length < this.getLimit(addButton)) {
            fieldsGroups[fieldsGroups.length - 1].insertAdjacentHTML('afterend', template);
        }
        if (fieldsGroups.length + 1 >= this.getLimit(addButton)) {
            addButton.style.display = 'none';
        }
    }

    getTemplate(button) {
        let templateClass = button.dataset.templateclass;
        return document.querySelector(`.${templateClass}`).innerHTML;
    }

    getFieldsGroups(button) {
        let groupClass = button.dataset.groupclass;
        return document.querySelectorAll(`.${groupClass}`);
    }

    getLimit(button) {
        return button.dataset.limit * 1;
    }

    getParentFieldsGroup(button) {
        return MVL_Listing_Manager.getClosestByClass(button, button.dataset.groupclass);
    }

    getParentAddButton(element) {
        return MVL_Listing_Manager.getClosestByClass(element, this.classes.addButton);
    }

    getParentDeleteButton(element) {
        return MVL_Listing_Manager.getClosestByClass(element, this.classes.deleteButton);
    }
}

new MVL_Listing_Manager_Repeater();
