class MagicSmartTag extends MagicItem {
    __construct() {
        super.__construct();
        jshelper
            .event('click', this.selector('label'), this._click_by_label.bind(this));
        jshelper
            .event('mouseover', this.selector('choice'), this._mouseover_on_choice.bind(this));
        jshelper
            .event('mouseout', this.selector('choice'), this._mouseout_from_choice.bind(this));
        jshelper
            .event('click', this.selector('choice'), this._click_by_choice.bind(this));

        document
            .addEventListener('mousedown', this._mousedown_by_document.bind(this));
        document
            .addEventListener('keydown', this._keydown_by_document.bind(this));
    }

    _keydown_on_input(event) {
        if (event.key === '{') {
            this.remove('choices');
            document.body.insertAdjacentHTML('beforeend', this.choicesHTML());
            this.node('choices').style = this.belowCaretPosition();
        } else {
            if (this.data.parent.charsBeforeCaret(1) !== '{') {
                this.remove('choices');
            }
        }
    }

    _click_by_choice(event, node) {
        this.insertChoice(node.querySelector(this.selector('choice-value')).innerText);
        this.remove('choices');
    }

    _click_by_label() {
        if (this.node('choices')) {
            this.node('choices').remove();
        } else {
            document.body.insertAdjacentHTML('beforeend', this.choicesHTML());
            if (this.node('label').getBoundingClientRect().top > this.node('choices').getBoundingClientRect().height) {
                this.node('choices').style = this.aboveLabelPosition();
            } else {
                this.node('choices').style = this.belowLabelPosition();
            }
        }
    }

    _mousedown_by_document(event) {
        if (!jshelper.closest(event.target, this.selector('label')) && !jshelper.closest(event.target, this.selector('choices'))) {
            this.remove('choices');
        }
    }

    _keydown_by_document(event) {
        let activeChoice = document.querySelector(this.selector('choice') + '.active');
        let choices = document.querySelectorAll(this.selector('choice'));
        if (choices.length > 0) {
            switch (event.key) {
                case 'Escape':
                    event.preventDefault();
                    this.remove('choices');
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    if (activeChoice) {
                        jshelper.removeclass(activeChoice, 'active');
                        if (activeChoice.previousElementSibling) {
                            jshelper.addclass(activeChoice.previousElementSibling, 'active');
                            jshelper.scroll.to.element(activeChoice.previousElementSibling);
                        } else {
                            jshelper.addclass(choices[choices.length - 1], 'active');
                            jshelper.scroll.to.element(choices[choices.length - 1]);
                        }
                    } else {
                        jshelper.addclass(choices[0], 'active');
                        jshelper.scroll.to.element(choices[0]);
                    }
                    break;
                case 'ArrowDown':
                    event.preventDefault();
                    if (activeChoice) {
                        jshelper.removeclass(activeChoice, 'active');
                        if (activeChoice.nextElementSibling) {
                            jshelper.addclass(activeChoice.nextElementSibling, 'active');
                            jshelper.scroll.to.element(activeChoice.nextElementSibling);
                        } else {
                            jshelper.addclass(choices[0], 'active');
                            jshelper.scroll.to.element(choices[0]);
                        }
                    } else {
                        jshelper.addclass(choices[0], 'active');
                        jshelper.scroll.to.element(choices[0]);
                    }
                    break;
                case 'Enter':
                    event.preventDefault();
                    if (activeChoice) {
                        this.insertChoice(activeChoice.querySelector(this.selector('choice-value')).innerText);
                        document.querySelector(this.selector('choices')).remove();
                    }
                    break;
            }
        }
    }

    _mouseover_on_choice(event) {
        let activeChoice = jshelper.closest(event.target, this.selector('choice'));
        jshelper.addclass(activeChoice, 'active');
    }

    _mouseout_from_choice(event) {
        let activeChoice = jshelper.closest(event.target, this.selector('choice'));
        jshelper.removeclass(activeChoice, 'active');
    }

    insertChoice(choiceValue) {
        if (this.data.parent.editor) {
            if (this.data.parent.getCharsBeforeCaret(2) === '{{') {
                this.data.parent.deleteCharsBeforeCaret(2);
            } else if (this.data.parent.getCharsBeforeCaret(1) === '{') {
                this.data.parent.deleteCharsBeforeCaret(1);
            }
            this.data.parent.editor.insertContent(choiceValue);
        } else {
            if (this.data.parent.charsBeforeCaret(2) === '{{') {
                this.data.parent.deleteCharsBeforeCaret(2);
            } else if (this.data.parent.charsBeforeCaret(1) === '{') {
                this.data.parent.deleteCharsBeforeCaret(1);
            }
            jshelper.insert.at.caret(this.data.parent.node('area'), choiceValue);
        }
    }

    html() {
        return `
        <span data-part-of="${this.vid()}" data-part="smart-tag">
            ${this.propertyHTML('description', 'span')}
            ${this.propertyHTML('label', 'span')}
        </span>
        `;
    }

    choicesHTML() {
        return `
            <span data-part="smart-tag-choices" data-part-of="${this.vid()}">
                ${Object.keys(this.data.choices).map(choice => `
                    <span data-part="smart-tag-choice" data-part-of="${this.vid()}">
                        <span data-part="smart-tag-choice-label" data-part-of="${this.vid()}">
                            ${this.data.choices[choice]}
                        </span>
                        <span data-part="smart-tag-choice-value" data-part-of="${this.vid()}">
                            {{${choice}}}
                        </span>
                    </span>
                `).join('')}
            </span>
        `;
    }

    aboveLabelPosition() {
        let choices = this.node('choices');
        let label = this.node('label');
        let position = jshelper.absolute.offset.of.node(label);
        let css = window.getComputedStyle(this.node());
        let mt = parseFloat(css.marginTop);
        return `top: ${position.y - choices.offsetHeight - mt}px; left: ${position.x - choices.offsetWidth + label.offsetWidth}px;`;
    }

    belowLabelPosition() {
        let choices = this.node('choices');
        let label = this.node('label');
        let position = jshelper.absolute.offset.of.node(label);
        let css = window.getComputedStyle(this.node());
        let mt = parseFloat(css.marginTop);
        return `top: ${position.y + mt + label.offsetHeight}px; left: ${position.x - choices.offsetWidth + label.offsetWidth}px;`;
    }

    belowCaretPosition() {
        let input = this.data.parent.node('area');
        let position = jshelper.absolute.offset.of.caret(input);
        let area = this.data.parent.node('area');

        return `top: ${jshelper.absolute.offset.of.node(area, 'content').y + area.offsetHeight}px; left: ${position.x}px;`;
    }

    remove(part = '') {
        if (document.body.querySelector(this.selector(part))) {
            document.body.querySelector(this.selector(part)).remove();
        }

        if (this.node(part)) {
            this.node(part).remove();
        }
    }
}

MagicAPI.add.component('smart-tag', MagicSmartTag);