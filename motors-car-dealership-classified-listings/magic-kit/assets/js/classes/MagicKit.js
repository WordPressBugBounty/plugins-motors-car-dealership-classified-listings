class MagicKit extends MagicClass {
    __construct(options) {
        this.data = this.options;

        for (let itemID of Object.keys(this.data.items)) {
            this.data.items[itemID].id = itemID;
            this.data.items[itemID].kit = this;
            this.data.items[itemID] = MagicAPI.new.item(this.data.items[itemID]);
        }
    }

    id() {
        return this.data.id;
    }

    node() {
        return document.querySelector(`[data-kit="${this.id()}"]`);
    }

    render() {
        for (let itemID of Object.keys(this.data.items)) {
            this.node().appendChild(this.data.items[itemID].node());
            this.data.items[itemID].init();
        }
        return this;
    }

    values(newValues = false) {
        let values = {};

        if (typeof newValues === 'object') {
            for (let itemID of Object.keys(this.data.items)) {
                this.data.items[itemID].value(newValues[itemID]);
                this.data.items[itemID]._onchange();
            }
        } else {
            for (let itemID of Object.keys(this.data.items)) {
                values[itemID] = this.data.items[itemID].value();
            }
        }

        return values;
    }

    reset() {
        for (let itemID of Object.keys(this.data.items)) {
            this.data.items[itemID].reset();
        }
    }

    isChanged() {
        let changed = false;
        for (let itemID of Object.keys(this.data.items)) {
            if (this.data.items[itemID].isChanged()) {
                changed = true;
                break;
            }
        }
        return changed;
    }

    onchange() {
        for (let itemID of Object.keys(this.data.items)) {
            this.data.items[itemID]._onchange();
        }
        return this;
    }

    save() {
        for (let itemID of Object.keys(this.data.items)) {
            this.data.items[itemID].save();
            this.data.items[itemID]._onsave();
        }
        return this;
    }

    search(search) {
        let result = {
            found: false,
            items: {},
        };

        for (let itemID of Object.keys(this.data.items)) {
            let itemResult = this.data.items[itemID].search(search);
            if (itemResult.found) {
                result.found = true;
                result.items = {
                    ...result.items,
                    ...itemResult.items,
                };
            }
        }

        return result;
    }

    __options() {
        return {
            items: {},
            attributes: {},
            save: 'Save',
            reset: 'Reset',
            cancel: 'Cancel',
            content: {
                before: '',
                after: '',
            }
        };
    }
}
