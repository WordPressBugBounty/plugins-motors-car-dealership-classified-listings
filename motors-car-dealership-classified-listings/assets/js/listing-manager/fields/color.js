let mvlListingManagerColorPicker = function () {
    // Инициализируем все элементы с классом color-input
    document.querySelectorAll('.mvl-listing-manager-field-color-input').forEach(function (element) {
        let pickr = new Pickr({

            // Selector or element which will be replaced with the actual color-picker.
            // Can be a HTMLElement.
            el: element,

            // Where the pickr-app should be added as child.
            container: 'body',

            // Which theme you want to use. Can be 'classic', 'monolith' or 'nano'
            theme: 'classic',

            // Nested scrolling is currently not supported and as this would be really sophisticated to add this
            // it's easier to set this to true which will hide pickr if the user scrolls the area behind it.
            closeOnScroll: false,

            // Custom class which gets added to the pcr-app. Can be used to apply custom styles.
            appClass: 'mvl-listing-manager-field-color-popup',

            // Don't replace 'el' Element with the pickr-button, instead use 'el' as a button.
            // If true, appendToBody will also be automatically true.
            useAsButton: false,

            // Size of gap between pickr (widget) and the corresponding reference (button) in px
            padding: 8,

            // If true pickr won't be floating, and instead will append after the in el resolved element.
            // It's possible to hide it via .hide() anyway.
            inline: false,

            // If true, pickr will be repositioned automatically on page scroll or window resize.
            // Can be set to false to make custom positioning easier.
            autoReposition: true,

            // Defines the direction in which the knobs of hue and opacity can be moved.
            // 'v' => opacity- and hue-slider can both only moved vertically.
            // 'hv' => opacity-slider can be moved horizontally and hue-slider vertically.
            // Can be used to apply custom layouts
            sliders: 'h',

            // Start state. If true 'disabled' will be added to the button's classlist.
            disabled: false,

            // If true, the user won't be able to adjust any opacity.
            // Opacity will be locked at 1 and the opacity slider will be removed.
            // The HSVaColor object also doesn't contain an alpha, so the toString() methods just
            // print HSV, HSL, RGB, HEX, etc.
            lockOpacity: false,

            // Precision of output string (only effective if components.interaction.input is true)
            outputPrecision: 0,

            // Defines change/save behavior:
            // - to keep current color in place until Save is pressed, set to `true`,
            // - to apply color to button and preview (save) in sync with each change
            //   (from picker or palette), set to `false`.
            comparison: true,

            // Default color. If you're using a named color such as red, white ... set
            // a value for defaultRepresentation too as there is no button for named-colors.
            default: '#42445a',

            // Optional color swatches. When null, swatches are disabled.
            // Types are all those which can be produced by pickr e.g. hex(a), hsv(a), hsl(a), rgb(a), cmyk, and also CSS color names like 'magenta'.
            // Example: swatches: ['#F44336', '#E91E63', '#9C27B0', '#673AB7'],
            swatches: listingManager.colorpicker_swatches,

            // Default color representation of the input/output textbox.
            // Valid options are `HEX`, `RGBA`, `HSVA`, `HSLA` and `CMYK`.
            defaultRepresentation: 'HEX',

            // Option to keep the color picker always visible.
            // You can still hide / show it via 'pickr.hide()' and 'pickr.show()'.
            // The save button keeps its functionality, so still fires the onSave event when clicked.
            showAlways: false,

            // Close pickr with a keypress.
            // Default is 'Escape'. Can be the event key or code.
            // (see: https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key)
            closeWithKey: 'Escape',

            // Defines the position of the color-picker.
            // Any combinations of top, left, bottom or right with one of these optional modifiers: start, middle, end
            // Examples: top-start / right-end
            // If clipping occurs, the color picker will automatically choose its position.
            // Pickr uses https://github.com/Simonwep/nanopop as positioning-engine.
            position: 'bottom-middle',

            // Enables the ability to change numbers in an input field with the scroll-wheel.
            // To use it set the cursor on a position where a number is and scroll, use ctrl to make steps of five
            adjustableNumbers: true,

            // Show or hide specific components.
            // By default only the palette (and the save button) is visible.
            components: {

                // Defines if the palette itself should be visible.
                // Will be overwritten with true if preview, opacity or hue are true
                palette: true,

                preview: true, // Display comparison between previous state and new color
                opacity: true, // Display opacity slider
                hue: true,     // Display hue slider

                // show or hide components on the bottom interaction bar.
                interaction: {

                    // Buttons, if you disable one but use the format in default: or setColor() - set the representation-type too!
                    hex: false,  // Display 'input/output format as hex' button  (hexadecimal representation of the rgba value)
                    rgba: false, // Display 'input/output format as rgba' button (red green blue and alpha)
                    hsla: false, // Display 'input/output format as hsla' button (hue saturation lightness and alpha)
                    hsva: false, // Display 'input/output format as hsva' button (hue saturation value and alpha)
                    cmyk: false, // Display 'input/output format as cmyk' button (cyan mangenta yellow key )

                    input: true, // Display input/output textbox which shows the selected color value.
                    // the format of the input is determined by defaultRepresentation,
                    // and can be changed by the user with the buttons set by hex, rgba, hsla, etc (above).
                    cancel: false, // Display Cancel Button, resets the color to the previous state
                    clear: false, // Display Clear Button; same as cancel, but keeps the window open
                    save: false,  // Display Save Button,
                },
            },

            // Translations, these are the default values.
            i18n: {

                // Strings visible in the UI
                'ui:dialog': 'color picker dialog',
                'btn:toggle': 'toggle color picker dialog',
                'btn:swatch': 'color swatch',
                'btn:last-color': 'use previous color',
                'btn:save': 'Save',
                'btn:cancel': 'Cancel',
                'btn:clear': 'Clear',

                // Strings used for aria-labels
                'aria:btn:save': 'save and close',
                'aria:btn:cancel': 'cancel and close',
                'aria:btn:clear': 'clear and close',
                'aria:input': 'color input field',
                'aria:palette': 'color selection area',
                'aria:hue': 'hue selection slider',
                'aria:opacity': 'selection slider'
            }
        });

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

        class ListingManagerColorInput {
            constructor(colorPickerInstance) {
                this.colorPickerInstance = colorPickerInstance;
                this.inputWrapper = colorPickerInstance.getRoot().button.closest('.mvl-listing-manager-field-color-input-wrapper');
                this.input = this.inputWrapper.querySelector('.mvl-listing-manager-field-input');

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

                let eventName = 'mvl-listing-manager-field-color-input-changed';

                if (this.input.id) {
                    eventName = 'mvl-listing-manager-field-color-input-changed-' + this.input.id;
                }

                MVL_Listing_Manager.change();

                document.dispatchEvent(new CustomEvent(eventName, {
                    detail: {
                        input: this.input,
                    }
                }));
            }
        }

        pickr.on('init', function (instance) {
            new ColorPickerRepresentationInput(instance);
            new ColorPickerOpacityInput(instance);
            new ColorPickerSavedColors(instance);
            new ColorPickerAutoSubmitOnChange(instance);
            new ListingManagerColorInput(instance);
        });
    }); // Закрываем forEach
}

window.addEventListener('load', mvlListingManagerColorPicker);