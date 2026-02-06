class MagicRadio extends MagicCheckbox {
    __construct(options) {
        super.__construct(options);
    }

    attributes(choice) {
        return jshelper.recursive.merge.objects(super.attributes(choice), {
            'name': this.data.id,
            'type': 'radio',
        });
    }
}

MagicAPI.add.component('radio', MagicRadio);