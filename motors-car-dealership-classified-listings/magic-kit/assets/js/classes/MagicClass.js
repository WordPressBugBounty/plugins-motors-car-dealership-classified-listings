class MagicClass {
    constructor(options) {
        this.options = jshelper.recursive.merge.objects(this.__options(), options);
        this.__construct(options);
    }

    __construct(options) {
        return this;
    }

    __options() {
        return {};
    }
}
