class MagicTextarea extends MagicInput {
    html() {
        return `
        <label data-part-of="${this.vid()}" data-part="${this.type()}" for="${this.data.id + '-' + this.vid()}">
            ${this.propertyHTML('label', 'span')}
            <textarea ${jshelper.convert.object.to.attrs(this.attributes())} >${this.value()}</textarea>
            ${this.propertyHTML('description', 'span')}
            ${this.data['smart-tag'] ? this.data['smart-tag'].html() : ''}
        </label>
        `;
    }

    attributes() {
        return {
            'id': this.data.id + '-' + this.vid(),
            'name': this.data.name ? this.data.name : this.data.id,
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
            'rows': this.data.rows ? this.data.rows : false,
            'data-part-of': this.vid(),
            'data-part': this.type() + '-area',
        }
    }
}

MagicAPI.add.component('textarea', MagicTextarea);