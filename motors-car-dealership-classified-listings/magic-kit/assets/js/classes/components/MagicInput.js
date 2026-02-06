class MagicInput extends MagicItem {
    __construct() {
        if (this.options['smart-tag']) {
            this.options['smart-tag'].type = 'smart-tag';
            this.options['smart-tag'].parent = this;
            this.options['smart-tag'] = MagicAPI.new.item(this.options['smart-tag']) || false;
        }
        super.__construct();
        jshelper
            .event('keydown', this.selector('area'), this._keydown_on_input.bind(this));
    }

    _keydown_on_input(event) {
        if (event.key === "Enter") {
            event.preventDefault();
        }
        if (this.data['smart-tag']) {
            this.data['smart-tag']._keydown_on_input(event);
        }
    }

    html() {
        return `
        <label data-part-of="${this.vid()}" data-part="input">
            ${this.propertyHTML('label', 'span')}
            <input ${jshelper.convert.object.to.attrs(this.attributes())} />
            ${this.propertyHTML('description', 'span')}
            ${this.data['smart-tag'] ? this.data['smart-tag'].html() : ''}
        </label>
        `;
    }

    value(newValue) {
        if (newValue !== undefined && newValue !== null) {
            this.data.value = newValue;
            this.node('area').value = newValue;
        }
        return this.data.value;
    }

    attributes() {
        return {
            'id': this.data.id,
            'name': this.data.name ? this.data.name : this.data.id,
            'type': this.data.type ? this.data.type : 'text',
            'value': this.value(),
            'placeholder': this.data.placeholder ? this.data.placeholder : false,
            'class': this.data.class ? this.data.class : false,
            'required': this.data.required ? this.data.required : false,
            'autocomplete': this.data.autocomplete ? this.data.autocomplete : false,
            'disabled': this.data.disabled ? this.data.disabled : false,
            'readonly': this.data.readonly ? this.data.readonly : false,
            'pattern': this.data.pattern ? this.data.pattern : false,
            'min': this.data.min ? this.data.min : false,
            'max': this.data.max ? this.data.max : false,
            'step': this.data.step ? this.data.step : false,
            'minlength': this.data.minlength ? this.data.minlength : false,
            'maxlength': this.data.maxlength ? this.data.maxlength : false,
            'size': this.data.size ? this.data.size : false,
            'multiple': this.data.multiple ? this.data.multiple : false,
            'data-part-of': this.vid(),
            'data-part': this.type() + '-area',
        }
    }

    charsBeforeCaret(count = 2) {
        return jshelper.get.chars.before.caret(this.node('area'), count);
    }

    deleteCharsBeforeCaret(count = 2) {
        jshelper.delete.chars.before.caret(this.node('area'), count);
    }

    _onchange() {
        this.data.value = this.node('area').value;
        super._onchange();
    }

    init() {
        super.init();
        this.node('area').addEventListener('input', this._onchange.bind(this));
        this.node('area').addEventListener('change', this._onchange.bind(this));
    }

}

MagicAPI.add.component('input', MagicInput);