let MagicAPI = {
    default: {
        component: 'input',
    },
    components: {},
    new: {
        item: function (item) {
            return new (MagicAPI.get.component(item.type))(item);
        },
    },
    add: {
        kit: function (kit) {
            MagicCache.cache.kit(new MagicKit(kit));
            return MagicAPI.get.kit(kit.id);
        },
        component: function (componentID, component) {
            MagicAPI.components[componentID] = component;
            MagicAPI.components[jshelper.get.object.class.name(component)] = componentID;
        },
    },
    get: {
        kit: function (kitID) {
            return MagicCache.get.kit(kitID);
        },
        component: function (componentID) {
            return MagicAPI.components[componentID] || MagicAPI.components[MagicAPI.default.component];
        },
        item: function (itemID) {
            return MagicCache.get.item(itemID);
        },
    },
    delete: {
        kit: function (kitID) {
            MagicCache.delete.kit(kitID);
        },
    },
    set: {
        default: {
            component: function (componentID) {
                MagicAPI.default.component = componentID;
            },
        }
    },
    render: {
        kit: function (kitID) {
            return MagicCache.get.kit(kitID).render();
        },
    },
};
