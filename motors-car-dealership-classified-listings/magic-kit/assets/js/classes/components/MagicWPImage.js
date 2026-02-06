class MagicWPImage extends MagicItem {
    wpmedia = null;
    current = null;
    initialized = false;

    __construct(options) {
        super.__construct(options);
        jshelper.event('click', this.selector('delete'), this.deleteMedia.bind(this));
        jshelper.event('click', this.selector('set'), this.openWPMedia.bind(this));
    }

    html() {
        return `
        <div data-item="${this.data.id}" data-part="wpimage" data-part-of="${this.vid()}">
            ${this.propertyHTML('label')}
            <div data-part="wpimage-inner" data-part-of="${this.vid()}">
                <div data-part="wpimage-image-wrap" data-part-of="${this.vid()}">
                    ${this.imageHTML()}
                </div>
                ${this.propertyHTML(this.data.value && this.data.value.url ? 'delete' : 'set')}
            </div>
            ${this.propertyHTML('description')}
        </div>
        `;
    }

    imageHTML() {
        return this.data.value && this.data.value.url ? `
            <img data-part="wpimage-image" data-part-of="${this.vid()}" src="${jshelper.get.object.value(this.data, 'value.url', '')}" title="${jshelper.get.object.value(this.data, 'value.title', '')}" />
            ` : ''
    }

    deleteMedia() {
        this.data.value = {
            url: '',
            title: '',
            id: '',
        };
        this.node('delete').remove();
        this.node('inner').appendChild(this.propertyDOM('set'));
        this.node('image').remove();
        if (this.data.onremove) {
            for (let key in this.data.onremove) {
                let method = '_onchange_' + key;
                if (typeof this[method] === 'function') {
                    this[method](this.data.onremove[key]);
                }
            }
        }
    }

    openWPMedia() {
        this.current = this;
        this.wpmedia.open();
    }

    mediaSelected() {
        this.data.value = this.wpmedia.state().get('selection').first().toJSON();
        this.updateImageHTML();
    }

    updateImageHTML() {
        this.remove('image');
        this.remove('set');
        this.node('image-wrap').insertAdjacentHTML('beforeend', this.imageHTML());
        this.node('inner').appendChild(this.propertyDOM('delete'));
        if (this.data.onadd) {
            for (let key in this.data.onadd) {
                let method = '_onchange_' + key;
                if (typeof this[method] === 'function') {
                    this[method](this.data.onadd[key]);
                }
            }
        }
    }

    value(newValue = false) {
        if (typeof newValue === 'object') {
            if (this.data.value.url) {
                this.deleteMedia();
            }
            this.data.value = newValue;

            if (newValue.url) {
                this.updateImageHTML();
            }
        }
        return this.data.value;
    }

    _onchange_remove(selector) {
        if (typeof selector === 'string') {
            if (document.querySelector(selector)) {
                document.querySelector(selector).remove();
            }
        } else {
            for (let iframe in selector.iframe) {
                let iframeNode = document.querySelector(iframe);
                if (iframeNode) {
                    let doc = iframeNode.contentDocument || iframeNode.contentWindow.document;
                    doc.querySelectorAll(selector.iframe[iframe]).forEach(node => node.remove());
                }
            }
        }
    }

    _onchange_insert(data, doc = false) {
        if (doc === false) {
            doc = document;
        }
        for (let selector in data) {
            if ('iframe' === selector) {
                for (let iframe in data[selector]) {
                    let iframeNode = doc.querySelector(iframe);
                    if (iframeNode) {
                        let doc = iframeNode.contentDocument || iframeNode.contentWindow.document;
                        this._onchange_insert(data[selector][iframe], doc);
                    }
                }
            } else {
                for (let position in data[selector]) {
                    doc.querySelectorAll(selector).forEach(node => node.insertAdjacentHTML(position, this.__replace_vars(data[selector][position])));
                }
            }
        }
    }

    __options() {
        return {
            delete: 'Remove',
            set: 'Upload',
        };
    }

    isChanged() {
        return this.data.value.url !== this.options.value.url;
    }

    init() {
        let ths = this;
        if (!this.inititialized) {
            this.wpmedia = wp.media({
                multiple: false,
                library: {
                    type: 'image',
                },
            });
        }
        this.wpmedia.on('close', function () {
            if (ths.current === ths) {
                requestAnimationFrame(() => {
                    ths.current = false;
                });
            }

        });
        this.wpmedia.on('select', function () {
            if (ths.current === ths) {
                ths.mediaSelected();
                requestAnimationFrame(() => {
                    ths.current = false;
                });
            }
        });
        super.init();
    }

    isEmptyValue() {
        return this.data.value.url === '';
    }
}

MagicAPI.add.component('wpimage', MagicWPImage);