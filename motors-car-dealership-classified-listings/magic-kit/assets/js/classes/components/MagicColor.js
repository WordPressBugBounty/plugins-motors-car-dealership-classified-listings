class MagicColor extends MagicInput {
    inititialized = false;

    html() {
        return `
        <div data-part="color" data-item="${this.data.id}" data-part-of="${this.vid()}">
            ${this.propertyHTML('label')}
            <span data-part="color-input-wrap" data-part-of="${this.vid()}">
                <span data-part="color-area" data-part-of="${this.vid()}"></span>
                <input ${jshelper.convert.object.to.attrs(this.attributes())} />
            </span>
            ${this.propertyHTML('description')}
        </div>
        `;
    }

    attributes() {
        return {
            'id': this.data.id,
            'name': this.data.name ? this.data.name : this.data.id,
            'type': 'text',
            'value': this.value(),
            'placeholder': this.data.placeholder ? this.data.placeholder : false,
            'class': this.data.class ? this.data.class : false,
            'required': this.data.required ? this.data.required : false,
            'autocomplete': 'off',
            'disabled': this.data.disabled ? this.data.disabled : false,
            'readonly': this.data.readonly ? this.data.readonly : false,
            'data-part-of': this.vid(),
            'data-part': this.type() + '-area',
        }
    }

    value(newValue) {
        if (newValue !== undefined && newValue !== null) {
            this.picker.setColor(newValue);
        }
        return super.value(newValue);
    }

    init() {
        let ths = this;
        let options = {
            el: this.node('area'),
            container: this.selector(),
            theme: 'classic',
            closeOnScroll: false,
            appClass: 'motors-color-picker',
            useAsButton: false,
            padding: 8,
            inline: false,
            autoReposition: true,
            sliders: 'h',
            disabled: false,
            lockOpacity: false,
            outputPrecision: 0,
            comparison: true,
            default: this.data.default || '#42445a',
            swatches: this.data.swatches || [],
            defaultRepresentation: 'HEX',
            showAlways: false,
            closeWithKey: 'Escape',
            position: 'bottom-middle',
            adjustableNumbers: true,
            components: {
                palette: true,
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    hex: false,
                    rgba: false,
                    hsla: false,
                    hsva: false,
                    cmyk: false,
                    input: true,
                    cancel: false,
                    clear: false,
                    save: false,
                },
            },
            i18n: {
                'ui:dialog': 'color picker dialog',
                'btn:toggle': 'toggle color picker dialog',
                'btn:swatch': 'color swatch',
                'btn:last-color': 'use previous color',
                'btn:save': 'Save',
                'btn:cancel': 'Cancel',
                'btn:clear': 'Clear',
                'aria:btn:save': 'save and close',
                'aria:btn:cancel': 'cancel and close',
                'aria:btn:clear': 'clear and close',
                'aria:input': 'color input field',
                'aria:palette': 'color selection area',
                'aria:hue': 'hue selection slider',
                'aria:opacity': 'selection slider'
            }
        }

        this.picker = new Pickr(options);

        this.picker.on('init', function (instance) {
            new ColorPickerRepresentationInput(instance);
            new ColorPickerOpacityInput(instance);
            new ColorPickerSavedColors(instance);
            new ColorPickerAutoSubmitOnChange(instance);
            new MotorsColorInput(instance);
            instance.on('change', ths._onchange.bind(ths));
        });
    }
}

MagicAPI.add.component('color', MagicColor);