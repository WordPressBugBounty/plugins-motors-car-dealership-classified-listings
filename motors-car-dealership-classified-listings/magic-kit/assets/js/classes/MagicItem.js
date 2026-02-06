class MagicItem extends MagicClass {
    __construct(options) {
        this.data = Object.assign({}, this.options);
        this.data.value = (this.options && typeof this.options.value === 'object' && this.options.value !== null)
            ? JSON.parse(JSON.stringify(this.options.value))
            : this.options.value;
        this.generateVID();
        this.data.node = this.dom();
        MagicCache.cache.item(this);
    }

    type() {
        return MagicAPI.get.component(this.constructor.name);
    }

    propertyHTML(property, tag = 'span', _default = '') {
        let value = jshelper.get.object.value(this.data, property, _default);
        let positions = [
            'beforebegin',
            'afterbegin',
            'beforeend',
            'afterend',
        ];
        let isMetaProp = positions.indexOf(property.split('.')[0]) === -1 ? false : true;

        return value ? `
        ${!isMetaProp ? this.propertyHTML('beforebegin.' + property, tag) : ''}
        <${tag} data-part="${this.type()}-${property.replaceAll('.', '-')}" data-part-of="${this.vid()}">
            ${!isMetaProp ? this.propertyHTML('afterbegin.' + property, tag) : ''}
            ${value}
            ${!isMetaProp ? this.propertyHTML('beforeend.' + property, tag) : ''}
        </${tag}>
        ${!isMetaProp ? this.propertyHTML('afterend.' + property, tag) : ''}
        ` : '';
    }

    propertyDOM(property, tag = 'span', _default = '') {
        return jshelper.convert.string.to.dom(this.propertyHTML(property, tag, _default));
    }

    dom() {
        return jshelper.convert.string.to.dom(this.html());
    }

    html() {
        return '';
    }

    vid() {
        return this.data.vid;
    }

    generateVID() {
        let id = 1;

        while (MagicCache.get.item('vid' + id)) {
            id++;
        }

        this.data.vid = 'vid' + id;

        return this;
    }

    value(value = null) {
        if (value === null) {
            return this.data.value ? this.data.value : '';
        } else {
            this.data.value = value;
            return this;
        }
    }

    node(part = '') {
        if (document.querySelectorAll(this.selector(part)).length > 0) {
            return document.querySelector(this.selector(part));
        } else {
            return part === '' ? this.data.node : this.data.node.querySelector(`${this.selector(part)}`);
        }
    }

    resetValue() {
        this.value(this.options.value);
    }

    selector(part = '') {
        return part
            ? `[data-part-of="${this.vid()}"][data-part="${this.type()}-${part}"]`
            : `[data-part-of="${this.vid()}"][data-part="${this.type()}"]`;
    }

    _onchange() {
        if (this.data.onchange) {
            for (let key in this.data.onchange) {
                let method = '_onchange_' + key;
                if (typeof this[method] === 'function') {
                    this[method](this.data.onchange[key]);
                }
            }
        }
    }

    _onsave() {
        if (this.data.onsave) {
            for (let key in this.data.onsave) {
                let method = '_onchange_' + key;
                if (typeof this[method] === 'function') {
                    this[method](this.data.onsave[key]);
                }
            }
        }
    }

    _onchange_remove(data) {
        for (let key in data) {
            let method = '_onchange_remove_' + key;
            if (typeof this[method] === 'function') {
                this[method](data[key]);
            }
        }
    }

    _onchange_remove_attribute(data) {
        for (let key in data) {
            document.querySelectorAll(this.__replace_vars(data[key])).forEach(item => {
                item.removeAttribute(this.__replace_vars(key));
            });
        }
    }

    _onchange_update(data) {
        for (let key in data) {
            let method = '_onchange_update_' + key;
            if (typeof this[method] === 'function') {
                this[method](data[key]);
            }
        }
    }

    _onchange_update_iframe(data) {
        for (let iframe in data) {
            let doc = document.querySelector(iframe).contentDocument;
            for (let key in data[iframe]) {
                let method = '_onchange_update_' + key;
                if (typeof this[method] === 'function') {
                    this[method](data[iframe][key], doc);
                }
            }
        }
    }

    _onchange_update_attribute(data, doc = false) {
        if (doc === false) {
            doc = document;
        }
        for (let attr in data) {
            for (let selector in data[attr]) {
                if (doc.querySelector(this.__replace_vars(selector))) {
                    doc.querySelectorAll(this.__replace_vars(selector)).forEach(item => {
                        item.setAttribute(attr, this.__replace_vars(data[attr][selector]));
                    });
                }
            }
        }
    }

    _onchange_update_content(data, doc = false) {
        if (doc === false) {
            doc = document;
        }
        for (let selector in data) {
            let items = doc.querySelectorAll(selector);
            for (let item of items) {
                item.innerHTML = this.__replace_vars(data[selector]);
            }
        }
    }

    _onchange_update_css(data, doc = false) {
        if (doc === false) {
            doc = document;
        }
        let css_node = doc.querySelector(this.selector('css'));
        let css = [];

        if (css_node) {
            css_node.remove();
        }

        for (let selector in data) {
            let selector_css = [];
            for (let key in data[selector]) {
                selector_css.push(`${key}:${this.__replace_vars(data[selector][key])}`);
            }
            css.push(`${selector}{${selector_css.join(';')}}`);
        }

        doc.querySelector('body').insertAdjacentHTML('beforeend', `<style data-part-of="${this.vid()}" data-part="${this.type()}-css">${css.join('')}</style>`);
    }


    remove(part) {
        if (this.node(part)) {
            this.node(part).remove();
        }
    }

    __replace_vars(string) {
        return String(string).replace(/{{\s*([^}]+?)\s*}}/g, (m, path) => {
            const v = jshelper.get.object.value(this.data, path.trim());
            return v == null ? '' : String(v);
        });
    }

    __options() {
        return {
            value: false,
        };
    }

    init() {

    }

    isEmptyValue() {
        return this.data.value === '';
    }

    isChanged() {
        return this.data.value !== this.options.value;
    }

    save() {
        this.options.value = (this.data && typeof this.data.value === 'object' && this.data.value !== null)
            ? JSON.parse(JSON.stringify(this.data.value))
            : this.data.value;
    }

    reset() {
        this.value(this.options.value);
    }

    search(search) {
        let result = {
            found: false,
            items: {},
        };

        if (this.data.label && this.data.label.toLowerCase().includes(search.toLowerCase())) {
            result.found = true;
            result.items[this.data.id] = this;
        }

        return result;
    }
}