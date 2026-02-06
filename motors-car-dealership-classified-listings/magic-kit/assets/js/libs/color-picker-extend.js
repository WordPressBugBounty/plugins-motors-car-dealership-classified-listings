class ColorPickerRepresentationInput {
    formats = ['HEXA', 'RGBA', 'HSLA'];

    constructor(colorPickerInstance) {
        this.colorPickerInstance = colorPickerInstance;
        this.colorPickerInstance.getRoot().app.querySelector('input.pcr-result').insertAdjacentHTML('beforebegin', this.getTemplate());

        this.input = this.colorPickerInstance.getRoot().app.querySelector('.pckr-color-representation');
        this.input.querySelector('.pckr-color-representation-next').addEventListener('click', this.next.bind(this));
        this.input.querySelector('.pckr-color-representation-prev').addEventListener('click', this.prev.bind(this));
        this.colorPickerInstance.on('change', this.onPickerChange.bind(this));
    }

    onPickerChange(e) {
        this.input.querySelector('.pckr-color-representation-value').textContent = this.colorPickerInstance.getColorRepresentation();
    }

    next() {
        var currentFormat = this.colorPickerInstance.getColorRepresentation();
        for (var formatIndex in this.formats) {
            formatIndex = parseInt(formatIndex);
            var format = this.formats[formatIndex];
            var nextFormat = this.formats[formatIndex + 1] === undefined ? this.formats[0] : this.formats[formatIndex + 1];
            if (format === currentFormat) {
                this.colorPickerInstance.setColorRepresentation(nextFormat);
                break;
            }
        }

        this.input.querySelector('.pckr-color-representation-value').textContent = this.colorPickerInstance.getColorRepresentation();
    }

    prev() {
        var currentFormat = this.colorPickerInstance.getColorRepresentation();
        for (var formatIndex in this.formats) {
            formatIndex = parseInt(formatIndex);
            var format = this.formats[formatIndex];
            var nextFormat = formatIndex === 0 ? this.formats[this.formats.length - 1] : this.formats[formatIndex - 1];
            if (format === currentFormat) {
                this.colorPickerInstance.setColorRepresentation(nextFormat);
                break;
            }
        }

        this.input.querySelector('.pckr-color-representation-value').textContent = this.colorPickerInstance.getColorRepresentation();
    }

    getTemplate() {
        return `
    <span class="pckr-color-representation">
        <span class="pckr-color-representation-value">${this.colorPickerInstance.getColorRepresentation()}</span>
        <span class="pckr-color-representation-prev fa fa-angle-up"></span>
        <span class="pckr-color-representation-next fa fa-angle-down"></span>
    </span >
`;
    }
}

class ColorPickerOpacityInput {
    constructor(colorPickerInstance) {
        this.colorPickerInstance = colorPickerInstance;

        this.colorPickerInstance.getRoot().app.querySelector('input.pcr-result').insertAdjacentHTML('afterend', this.getTemplate());

        this.input = this.colorPickerInstance.getRoot().app.querySelector('.pcr-opacity-input');

        this.input.addEventListener('keydown', this.validateKeypress.bind(this));
        this.input.addEventListener('input', this.onInput.bind(this));
        this.input.addEventListener('focus', this.updateCursorPosition.bind(this));
        this.input.addEventListener('mousedown', this.updateCursorPosition.bind(this));
        this.input.addEventListener('mouseup', this.updateCursorPosition.bind(this));
        this.input.addEventListener('click', this.updateCursorPosition.bind(this));
        this.colorPickerInstance.on('change', this.onPickerChange.bind(this));
    }

    validateKeypress(e) {
        if (e.key.length === 1 && !/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    }

    onInput(e) {
        var hsva = this.colorPickerInstance.getColor().toHSVA();
        var value = this.input.value.replace('%', '');

        if (value * 1 > 100) {
            value = 100;
            this.input.value = 100 + '%';
        } else {
            if (value * 1 < 0 || value === '') {
                value = 0;
                this.input.value = 0 + '%';
            } else {
                if (value.length > 1 && value[0] === '0') {
                    value = value[1];
                    this.input.value = value + '%';
                }
            }
        }

        hsva[3] = value / 100;

        this.colorPickerInstance.setHSVA(hsva[0], hsva[1], hsva[2], hsva[3]);

        if (this.input.value.indexOf('%') === -1) {
            this.input.value = hsva[3] * 100 + '%';
        }

        this.updateCursorPosition();
    }

    updateCursorPosition() {
        requestAnimationFrame(() => {
            if (this.input.selectionStart === this.input.value.length) {
                const pos = this.input.value.length - 1;

                this.input.setSelectionRange(pos, pos);
                this.input.focus();
            }
        });
    }
    onPickerChange(e) {
        this.input.value = Math.ceil(this.colorPickerInstance.getColor().a * 100) + '%';
    }

    getTemplate() {
        return `<input type="text" class="pcr-opacity-input" value="${this.colorPickerInstance.getColor().a * 100}%" />`;
    }
}

class ColorPickerSavedColors {
    constructor(colorPickerInstance) {
        this.colorPickerInstance = colorPickerInstance;
        this.colorPickerInstance.getRoot().app.querySelector('.pcr-swatches').insertAdjacentHTML('beforebegin', this.getTemplate());
    }

    getTemplate() {
        return `
    <div class="pcr-saved-colors">
        <span class="pcr-saved-colors-title">Saved Colors:</span>
    </div>
    `;
    }
}

class ColorPickerAutoSubmitOnChange {
    constructor(colorPickerInstance) {
        this.colorPickerInstance = colorPickerInstance;
        this.colorPickerInstance.on('change', this.onPickerChange.bind(this));
    }

    onPickerChange(e) {
        var representationMethod = 'to' + this.colorPickerInstance.getColorRepresentation();
        this.colorPickerInstance.setColor(this.colorPickerInstance.getColor()[representationMethod]().toString());
    }
}

class MotorsColorInput {
    constructor(colorPickerInstance) {
        this.colorPickerInstance = colorPickerInstance;
        this.inputWrapper = colorPickerInstance.getRoot().button.closest('[data-part="color"]');
        this.input = this.inputWrapper.querySelector('[data-part="color-area"]');

        this.colorPickerInstance.setColor(this.input.value);
        this.colorPickerInstance.on('change', this.updateInputValue.bind(this));
        this.colorPickerInstance.on('show', this.onPickerShow.bind(this));
        this.colorPickerInstance.on('hide', this.onPickerHide.bind(this));
        this.input.addEventListener('click', this.onClick.bind(this));
        this.input.addEventListener('focusout', this.onFocusOut.bind(this));
    }

    onFocusOut(e) {
        this.colorPickerInstance.setColor(this.input.value);
        this.updateInputValue();
    }

    onClick(e) {
        if (this.inputWrapper.classList.contains('active')) {
            this.inputWrapper.classList.remove('active');
            this.colorPickerInstance.hide();
        } else {
            this.inputWrapper.classList.add('active');
            this.colorPickerInstance.show();
        }
    }

    onPickerShow(e) {
        this.inputWrapper.classList.add('active');
    }

    onPickerHide(e) {
        this.inputWrapper.classList.remove('active');
    }

    updateInputValue(e) {
        var representation = this.colorPickerInstance.getColorRepresentation();
        var representationMethod = 'to' + representation;

        if (representation === 'RGBA' || representation === 'HSLA') {
            var color = this.colorPickerInstance.getColor()[representationMethod]();

            if (representation === 'RGBA') {
                var rgba = color.map((v, i) => i < 3 ? Math.round(v) : v);
                this.input.value = `rgba(${rgba[0]}, ${rgba[1]}, ${rgba[2]}, ${rgba[3]})`;
            } else {
                var hsla = color.map((v, i) => i < 3 ? Math.round(v) : v);
                this.input.value = `hsla(${hsla[0]}, ${hsla[1]}%, ${hsla[2]}%, ${hsla[3]})`;
            }
        } else {
            this.input.value = this.colorPickerInstance.getColor()[representationMethod]().toString();
        }

        let eventName = 'motors-color-changed';

        if (this.input.id) {
            eventName = 'motors-color-changed-' + this.input.id;
        }

        document.dispatchEvent(new CustomEvent(eventName, {
            detail: {
                input: this.input,
            }
        }));
    }
}