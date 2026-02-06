class MagicSwitch extends MagicCheckbox {
    __construct(options) {
        super.__construct(options);
    }

    __options() {
        return {
            choices: {
                on: {
                    label: '',
                    value: true,
                },
            },
        };
    }
}


MagicAPI.add.component('switch', MagicSwitch);