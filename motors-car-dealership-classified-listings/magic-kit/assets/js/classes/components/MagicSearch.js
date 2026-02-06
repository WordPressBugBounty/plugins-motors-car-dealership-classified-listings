class MagicSearch extends MagicClass {
    __construct(options) {
        super.__construct(options);
        this.data = this.options;
        this.debounceTimeout = false;
        this.focusTimeout = false;
        jshelper.event('click', '[data-part="search-result"]', this._click_on_result.bind(this));
        document.querySelector(this.data.input.selector).addEventListener('keyup', this._keyup_on_input.bind(this));
        document.querySelector(this.data.input.selector).addEventListener('click', this._keyup_on_input.bind(this));
        document.addEventListener('click', this._click_outside_results.bind(this));
    }

    _click_outside_results(event) {
        if (!jshelper.closest(event.target, '[data-part="search-results"]') && !jshelper.closest(event.target, this.data.input.selector)) {
            if (document.querySelector('[data-part="search-results"]')) {
                document.querySelector('[data-part="search-results"]').remove();
            }
        }
    }

    _keyup_on_input(event) {
        let ths = this;

        if (event.target.value.length > 0) {
            this._debounce(() => {
                ths.data.results = {};
                for (let kit of ths.data.kits) {
                    let result = kit.search(event.target.value.toLowerCase());
                    if (result.found) {
                        ths.data.results = {
                            ...ths.data.results,
                            ...result.items,
                        };
                    }
                }
                if (ths.prevResults !== ths.data.results) {
                    ths.prevResults = ths.data.results;
                    document.querySelector(ths.data.input.selector).insertAdjacentHTML('afterend', ths.resultsHTML(event.target.value.toLowerCase()));
                }
            }, 200);
        }

        if (document.querySelector('[data-part="search-results"]')) {
            document.querySelector('[data-part="search-results"]').remove();
        }
    }

    _click_on_result(event, node) {
        event.preventDefault();
        let item = MagicAPI.get.item(node.dataset.partOf);
        item.node().setAttribute('data-effect', 'focus');
        this.focusTimeout = setTimeout(() => {
            item.node().removeAttribute('data-effect');
        }, 2000);
        document.querySelector('[data-part="search-results"]').remove();
        if (this.data.click && this.data.click.on && this.data.click.on.result) {
            this.data.click.on.result(item);
        }
    }

    resultsHTML(search) {
        return `
            <div data-part="search-results">
                ${Object.keys(this.data.results).map(result => `
                    <div data-part="search-result" data-part-of="${this.data.results[result].vid()}">
                        <span>${this.consilencesLabel(this.data.results[result], search)}</span> <span>${this.data.results[result].data.kit.data.name}</span>
                    </div>
                `).join('')}
            </div>
        `;
    }

    _debounce(callback, delay) {
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(() => callback(), delay);
    }

    consilencesLabel(item, search) {
        let match = {
            index: item.data.label.toLowerCase().indexOf(search),
        }

        match = {
            ...match,
            begin: item.data.label.slice(0, match.index),
            str: item.data.label.slice(match.index, match.index + search.length),
            end: item.data.label.slice(match.index + search.length),
        }

        return match.begin + `<b>${match.str}</b>` + match.end;
    }
}