class MagicMessage extends MagicClass {
    __construct(options) {
        super.__construct(options);
        this.data = this.options;
    }

    show(options = {}) {
        if (this.data.timeout) {
            clearTimeout(this.data.timeout);
            this.data.timeout = false;
        }
        this.close();
        this.data = jshelper.recursive.merge.objects(options, this.data);
        this.append();
        this.data.timeout = setTimeout(() => {
            this.close();
        }, this.data.time);
    }

    html() {
        return `
        <div data-part="message" data-type="${this.data.type}" data-id="${this.data.id}">
            ${this.data.message}
            <i class="motors-icons-close-times" data-part="message-close"></i>
        </div>
        `;
    }

    dom() {
        return jshelper.convert.string.to.dom(this.html());
    }

    append() {
        document.body.appendChild(this.dom());
        this.init();
    }

    close() {
        if (this.data.timeout) {
            clearTimeout(this.data.timeout);
            this.data.timeout = false;
        }
        if (this.node()) {
            this.node().remove();
        }
    }

    init() {
        this.node('close').addEventListener('click', this.close.bind(this));
    }

    node(part = '') {
        return document.querySelector(this.selector(part));
    }

    selector(part = '') {
        return part === '' ? '[data-id="' + this.data.id + '"][data-part="message"]' : '[data-id="' + this.data.id + '"] [data-part="message-' + part + '"]';
    }

    __options() {
        return {
            id: '',
            type: 'success',
            message: '',
            time: 3000,
        };
    }
}