class ConfirmationPopup {
    constructor(options = {}) {
        let defaults = {
            templateID: 'mvl-confirmation-modal-template',
            cancel: false,
            accept: false,
            text: {
                title: 'Confirmation',
                message: 'Are you sure want to continue this action?',
                accept: 'Confirm',
                cancel: 'Cancel',
                deleteBtnIcon: '',
                slug: ''
            }
        }

        if (options.text) {
            for (let option in options.text) {
                defaults.text[option] = options.text[option];
            }
        }

        options.text = defaults.text;

        for (let option in options) {
            defaults[option] = options[option];
        }

        for (let key in defaults) {
            this[key] = defaults[key];
        }

        this.modal = false;
    }

    getTemplate() {
        let template = document.getElementById(this.templateID).innerHTML;
        for (let key in this.text) {
            template = template.replace(`{{${key}}}`, this.text[key]);
        }
        return template;
    }

    on(events) {
        for (let event in events) {
            this[event] = events[event];
        }
    }

    open() {
        this.close();
        this.getTemplate();
        document.body.insertAdjacentHTML('beforeend', this.getTemplate());
        this.modal = document.querySelector('.mvl-confirmation-modal');
        this.modal.classList.add('mvl-popup-confirmation-opened');
        document.body.classList.add('mvl-popup-confirmation-opened');
        this.modal.querySelector('#discard-btn').addEventListener('click', this.onCancel.bind(this));
        if (this.text.accept) {
            this.modal.querySelector('#stay-btn').addEventListener('click', this.onAccept.bind(this));
        } else {
            this.modal.querySelector('#stay-btn').remove();
        }

        const closeBtn = this.modal.querySelector('.mvl-listing-manager-close-modal');
        if (closeBtn) {
            closeBtn.addEventListener('click', this.onAccept.bind(this));
        }
    }

    close() {
        if (document.body.classList.contains('mvl-popup-confirmation-opened')) {
            document.querySelectorAll('.mvl-confirmation-modal').forEach(modal => modal.remove());
            document.body.classList.remove('mvl-popup-confirmation-opened');
        }
    }

    onAccept() {
        this.close();
        this.accept();
    }

    onCancel() {
        this.close();
        this.cancel();
    }
}

function initConfirmationPopup(button) {
    if (!button) {
        console.error('Button element is undefined or null');
        return null;
    }

    const buttonElement = button.jquery ? button[0] : button;
    
    if (!buttonElement) {
        console.error('Button element not found');
        return null;
    }

    const dataset = buttonElement.dataset || {};
    
    return new ConfirmationPopup({
        button: buttonElement,
        text: {
            title: dataset.confirmationTitle || 'Confirmation',
            message: dataset.confirmationMessage || 'Are you sure want to continue this action?',
            accept: dataset.confirmationAccept || 'Confirm',
            cancel: dataset.confirmationCancel || 'Cancel',
            deleteBtnIcon: dataset.confirmationDeleteBtnIcon || '',
            slug: dataset.confirmationSlug || ''
        }
    });
}