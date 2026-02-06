MagicCache = {
    store: {
        kits: {},
        items: {},
    },
    cache: {
        kit: function (kit) {
            MagicCache.store.kits[kit.id()] = kit;
        },
        item: function (item) {
            MagicCache.store.items[item.vid()] = item;
        }
    },
    get: {
        kit: function (kitID) {
            return MagicCache.store.kits[kitID];
        },
        item: function (vid) {
            return MagicCache.store.items[vid];
        }
    },
    delete: {
        kit: function (kitID) {
            delete MagicCache.store.kits[kitID];
        },
        item: function (vid) {
            delete MagicCache.store.items[vid];
        }
    }
}

