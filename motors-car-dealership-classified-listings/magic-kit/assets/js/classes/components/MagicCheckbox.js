class MagicCheckbox extends MagicItem {
    __construct(options) {
        if (this.options.value) {
            if (typeof this.options.value === 'string') {
                this.options.value = this.options.value.split(',');
            }
        } else {
            this.options.value = [];
        }

        for (let choice of Object.keys(this.options.choices)) {
            if (this.options.choices[choice] instanceof Object === false) {
                this.options.choices[choice] = {
                    label: this.options.choices[choice],
                    value: choice,
                };
            }
        }
        this.data = this.options;
        super.__construct(options);
    }

    html() {
        return `
        <div data-part="${this.type()}" data-part-of="${this.vid()}">
            ${this.propertyHTML('label')}
            <div data-part="${this.type()}-choices" data-part-of="${this.vid()}">
                ${Object.keys(this.data.choices).map(choice => `
                    <span data-part="${this.type()}-choice" data-part-of="${this.vid()}">
                    <input ${jshelper.convert.object.to.attrs(this.attributes(choice))} />
                        ${this.data.choices[choice].image ? `
                            <label for="${this.data.id}-${this.vid()}-${choice}-choice" data-part="${this.type()}-choice-image-wrap" data-part-of="${this.vid()}">
                                <img data-part="${this.type()}-choice-image" data-part-of="${this.vid()}" src="${this.data.choices[choice].image}" alt="${this.data.choices[choice].label}" />
                            </label>
                        ` : ''}
                        <label for="${this.data.id}-${this.vid()}-${choice}-choice" data-part="${this.type()}-choice-label">
                            <span data-part="${this.type()}-choice-text" data-part-of="${this.vid()}">${this.data.choices[choice].label}</span>
                        </label>
                    </span>
                `).join('')}
            </div>
            ${this.propertyHTML('description')}
        </div>
        `;
    }

    attributes(choice) {
        return {
            'id': this.data.id + '-' + this.vid() + '-' + choice + '-choice',
            'name': this.data.id + '[' + choice + ']',
            'type': 'checkbox',
            'value': choice,
            'class': this.data.class ? this.data.class : false,
            'required': this.data.required ? this.data.required : false,
            'disabled': this.data.disabled ? this.data.disabled : false,
            'readonly': this.data.readonly ? this.data.readonly : false,
            'data-part-of': this.vid(),
            'data-part': this.type() + '-area',
            'checked': this.value().includes(choice) ? 'checked' : false
        };
    }

    value(newValue) {
        if (newValue !== undefined && newValue !== null) {
            this.data.value = newValue;
            let choices = document.querySelectorAll(this.selector('area'));
            choices.forEach(choice => {
                if (typeof newValue === 'string') {
                    newValue = [newValue];
                }
                if (choice.value === newValue.join(',')) {
                    choice.checked = true;
                } else {
                    choice.checked = false;
                }
            });
        } else {
            let value = typeof this.data.value !== 'array' && typeof this.data.value !== 'object' ? [this.data.value] : this.data.value;
            return value;
        }
    }

    init() {
        super.init();
        jshelper.event('change', this.selector('area'), this._onchange.bind(this));
    }

    isChanged() {
        return this.data.value.join(',') !== this.options.value.join(',');
    }

    _onchange() {
        let checked = this.node().querySelectorAll(this.selector('area') + ':checked');
        this.data.value = [];

        checked.forEach(input => {
            this.data.value.push(input.value);
        });

        super._onchange();
    }

    __replace_vars(string) {
        return String(string).replace(/{{\s*([^}]+?)\s*}}/g, (m, path) => {
            if (path.trim() === 'value') {
                path = path.trim() + '.0';
            }
            const v = jshelper.get.object.value(this.data, path);
            return v == null ? '' : String(v);
        });
    }
}


MagicAPI.add.component('checkbox', MagicCheckbox);