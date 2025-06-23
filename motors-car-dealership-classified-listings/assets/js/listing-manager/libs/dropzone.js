class MVL_Listing_Manager_Drop_Zone {

    constructor(options = {
        dropZoneClass: '',
        onDrop: null,
    }) {
        this.dropZoneClass = options.dropZoneClass;
        this.onDrop = options.onDrop;
        this.dropZone = false;

        var events = ['dragover', 'dragleave', 'drop'];

        for (var event of events) {
            document.body.addEventListener(event, this[event].bind(this));
        }
    }

    dragover(e) {
        this.dropZone = this.getClosestDropZone(e.target);

        if (this.dropZone) {
            e.preventDefault();
            this.dropZone.classList.add('dragover');
        }
    }

    dragleave() {
        if (this.dropZone) {
            this.dropZone.classList.remove('dragover');
            this.dropZone = false;
        }
    }

    async drop(e) {
        if (this.dropZone) {
            e.preventDefault();
            this.dropZone.classList.remove('dragover');

            if (this.onDrop) {
                await this.onDrop(e, this.dropZone);
            }
        }
    }

    getClosestDropZone(node) {
        var dropZone = false;
        var closest = node.closest('.' + this.dropZoneClass);

        if (node.classList.contains(this.dropZoneClass)) {
            dropZone = node;
        } else if (closest !== undefined) {
            dropZone = closest;
        }

        return dropZone;
    }
}