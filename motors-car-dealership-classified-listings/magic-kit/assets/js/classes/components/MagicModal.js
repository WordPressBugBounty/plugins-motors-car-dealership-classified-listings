class MagicModal extends MagicClass {
    __construct(options) {
        super.__construct(options);
        this.data = this.options;
    }

    show(options = {}) {
        this.close();
        this.data = jshelper.recursive.merge.objects(options, this.data);
        this.append();
    }

    close() {
        if (this.node()) {
            this.node().remove();
        }
    }

    append() {
        document.body.appendChild(this.dom());
        this.init();
    }

    dom() {
        return jshelper.convert.string.to.dom(this.html());
    }

    html() {
        return `
        <div data-part="modal" data-id="${this.data.id}">
            <div data-part="modal-body">
                <div data-part="modal-header">
                    ${this.data.title}
                    <i class="motors-icons-close-times" data-part="modal-close"></i>
                </div>
                <div data-part="modal-content">
                    ${this.data.content}
                </div>
                <div data-part="modal-actions">
                    ${Object.keys(this.data.actions).map(action => `
                        <button data-action="${action}" data-part="modal-action" class="${this.data.actions[action].class ? this.data.actions[action].class : ''}">
                            ${this.data.actions[action].text}
                        </button>
                    `).join('')}
                </div>
            </div>
        </div>
        `;
    }

    init() {
        let ths = this;
        this.node('close').addEventListener('click', this.close.bind(this));

        for (let action in this.data.actions) {
            let actionNode = this.node('actions').querySelector('[data-action="' + action + '"]');
            actionNode.addEventListener('click', function (e) {
                e.preventDefault();
                ths.data.actions[action].onclick(e);
                actionNode.insertAdjacentHTML('beforeend', '<span data-part="loader"></span>');
                if (ths.node('close')) {
                    ths.node('close').setAttribute('data-status', 'disabled');
                }

                for (let _action in ths.data.actions) {
                    if (ths.node('actions') && ths.node('actions').querySelector('[data-action="' + _action + '"]')) {
                        ths.node('actions').querySelector('[data-action="' + _action + '"]').setAttribute('data-status', 'disabled');
                    }
                }
            });
        }
    }

    node(part = '') {
        return document.querySelector(this.selector(part));
    }

    selector(part = '') {
        return part === '' ? '[data-id="' + this.data.id + '"][data-part="modal"]' : '[data-id="' + this.data.id + '"] [data-part="modal-' + part + '"]';
    }

    __options() {
        return {
            id: '',
            title: '',
            content: '',
            actions: {},
        };
    }

}