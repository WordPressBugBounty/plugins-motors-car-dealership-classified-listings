class MagicSelect extends MagicItem {
    __construct(options) {
        super.__construct(options);
    }

    html() {
        return `
        <label data-part-of="${this.vid()}" data-part="select">
            ${this.propertyHTML('label', 'span')}
            <span data-part="select-zone">
                <select ${jshelper.convert.object.to.attrs(this.attributes())} />
                ${Object.keys(this.choices()).map(choice => `
                        <option ${this.value().slug === choice ? 'selected' : ''} value="${choice}">${this.choices()[choice].label}</option>
                    `).join('')}
                </select>
            </span>
            ${this.propertyHTML('description', 'span')}
        </label>
        `;
    }

    attributes() {
        return {
            'id': this.data.id,
            'name': this.data.name ? this.data.name : this.data.id,
            'class': this.data.class ? this.data.class : false,
            'required': this.data.required ? this.data.required : false,
            'disabled': this.data.disabled ? this.data.disabled : false,
            'readonly': this.data.readonly ? this.data.readonly : false,
            'data-part-of': this.vid(),
            'data-part': this.type() + '-area',
        }
    }

    _onchange() {
        this.choices()[this.node('area').value].slug = this.node('area').value;
        this.data.value = this.choices()[this.node('area').value];
        super._onchange();
    }

    value(newValue) {
        if (newValue === undefined || newValue === null) {
            let value = this.data.value;
            if (typeof value === 'string') {
                if (this.choices()[value]) {
                    this.choices()[value].slug = value;
                    value = this.choices()[value];
                } else {
                    let firstKey = Object.keys(this.choices())[0];
                    this.choices()[firstKey].slug = firstKey;
                    value = this.choices()[firstKey];
                }
            }
            return value;
        } else {
            if (typeof newValue === 'string') {
                this.choices()[newValue].slug = newValue;
                newValue = this.choices()[newValue];
            } else {
                this.data.value = newValue;
            }
            this.node('area').value = newValue.slug;
            this._onchange();
        }
    }

    init() {
        this.node('area').addEventListener('change', this._onchange.bind(this));
    }

    isChanged() {
        let oldValue = this.options.value;

        if (typeof oldValue !== 'string') {
            oldValue = oldValue.slug;
        }
        let newValue = this.value();
        if (typeof newValue !== 'string') {
            newValue = newValue.slug;
        }
        return oldValue !== newValue;
    }

    choices() {
        let choices = {};

        for (let choice in this.data.choices) {
            choices[choice] = {
                label: typeof this.data.choices[choice] === 'string' ? this.data.choices[choice] : this.data.choices[choice].label,
                slug: choice,
            };
        }
        return choices;
    }
}
MagicAPI.add.component('select', MagicSelect);