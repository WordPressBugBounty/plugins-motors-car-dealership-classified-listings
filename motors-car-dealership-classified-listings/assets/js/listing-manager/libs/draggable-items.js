class MVL_Listing_Manager_Draggable_Items {
    constructor(options = {}) {
        let defaults = {
            classes: {
                container: 'draggable',
                item: 'draggable-item',
                dragging: 'dragging',
                dragOver: 'drag-over',
                ghost: 'ghost',
            },
            onGrap: null,
            onHover: null,
            onChange: null,
        };

        this.mousedownPosition = {
            x: 0,
            y: 0,
        };
        this.hoveredItem = false;
        this.ghostItem = false;
        this.draggedItem = false;
        this.draggedItemContainer = false;

        let events = ['mousedown', 'mousemove', 'mouseup', 'mouseleave'];

        if (options.classes) {
            for (let option in options.classes) {
                defaults.classes[option] = options.classes[option];
            }
        }

        options.classes = defaults.classes;

        for (let option in options) {
            defaults[option] = options[option];
        }

        for (let key in defaults) {
            this[key] = defaults[key];
        }

        for (let event of events) {
            document.addEventListener(event, this[event].bind(this));
        }
    }

    mouseleave(e) {
        if (e.clientX <= 0 || e.clientY <= 0 ||
            e.clientX >= window.innerWidth || e.clientY >= window.innerHeight) {
            this.reset();
        }
    }

    mousedown(e) {
        let item = this.getClosestItem(e.target);
        let container = this.getContainer(item);

        if (item && container) {
            e.preventDefault();
            this.reset();
            this.mousedownPosition = {
                x: e.clientX,
                y: e.clientY,
            };

            this.draggedItem = item;
            this.ghostItem = item.cloneNode(true);
            this.draggedItemContainer = container;

            let rect = item.getBoundingClientRect();

            this.draggedItem.classList.add('dragging');

            this.ghostItem.style.position = 'fixed';
            this.ghostItem.style.top = rect.top + 'px';
            this.ghostItem.style.left = rect.left + 'px';
            this.ghostItem.style.width = rect.width + 'px';
            this.ghostItem.style.height = rect.height + 'px';
            this.ghostItem.style.maxWidth = rect.width + 'px';
            this.ghostItem.style.maxHeight = rect.height + 'px';
            this.ghostItem.style.minWidth = rect.width + 'px';
            this.ghostItem.style.minHeight = rect.height + 'px';
            this.ghostItem.style.zIndex = '9999';
            this.ghostItem.style.paddingTop = '0';
            this.ghostItem.style.paddingBottom = '0';
            this.ghostItem.style.opacity = '0.8';
            this.ghostItem.style.pointerEvents = 'none';
            this.ghostItem.style.transition = 'none';

            document.body.appendChild(this.ghostItem);
            item.style.opacity = '0.6';
        }
    }

    mousemove(e) {
        if (this.ghostItem) {
            this.ghostItem.style.marginLeft = e.clientX - this.mousedownPosition.x + 'px';
            this.ghostItem.style.marginTop = e.clientY - this.mousedownPosition.y + 'px';

            let elementUnderCursor = this.getElementUnderCursor(e.clientX, e.clientY);
            let itemUnderCursor = this.getClosestItem(elementUnderCursor);
            let itemUnderCursorContainer = this.getContainer(itemUnderCursor);

            if (itemUnderCursor && itemUnderCursorContainer === this.draggedItemContainer) {
                if (itemUnderCursor !== this.draggedItem) {
                    if (this.hoveredItem) {
                        this.hoveredItem.classList.remove('drag-over');
                    }

                    this.hoveredItem = itemUnderCursor;
                    this.hoveredItem.classList.add('drag-over');
                }
            } else {
                if (this.hoveredItem) {
                    this.hoveredItem.classList.remove('drag-over');
                    this.hoveredItem = null;
                }
            }
        }
    }

    mouseup() {
        if (this.ghostItem) {
            if (this.hoveredItem && this.draggedItem) {
                if (this.hoveredItem !== this.draggedItem) {
                    let draggedItemIndex = 0;
                    let hoveredItemIndex = 0;
                    let items = this.draggedItem.closest('.' + this.classes.container).querySelectorAll('.' + this.classes.item);

                    for (let i in items) {
                        let item = items[i];
                        if (item === this.draggedItem) {
                            draggedItemIndex = i;
                        }
                        if (item === this.hoveredItem) {
                            hoveredItemIndex = i;
                        }
                    }

                    this.hoveredItem.classList.remove(this.classes.dragOver);
                    this.draggedItem.classList.remove(this.classes.dragging);

                    this.draggedItem.style.opacity = '1';
                    this.hoveredItem.insertAdjacentHTML('afterend', this.draggedItem.outerHTML);
                    this.draggedItem.insertAdjacentHTML('afterend', this.hoveredItem.outerHTML);

                    document.dispatchEvent(new CustomEvent(this.classes.item + '-changed', {
                        detail: {
                            item: this.draggedItem,
                            draggedItem: this.draggedItem,
                            hoveredItem: this.hoveredItem,
                            draggedItemIndex: draggedItemIndex * 1,
                            hoveredItemIndex: hoveredItemIndex * 1,
                        },
                    }));
                }

                this.hoveredItem.remove();
                this.draggedItem.remove();
            }

            this.reset();
        }
    }

    getElementUnderCursor(x, y) {
        if (this.ghostItem) {
            this.ghostItem.style.display = 'none';
        }

        let element = document.elementFromPoint(x, y);

        if (this.ghostItem) {
            this.ghostItem.style.display = '';
        }

        return element;
    }

    getClosestItem(node) {
        if (node) {
            let item = false;
            let closest = node.closest('.' + this.classes.item);

            if (node.classList.contains(this.classes.item)) {
                item = node;
            } else if (closest !== undefined) {
                item = closest;
            }

            return item;
        } else {
            return false;
        }
    }

    getContainer(node) {
        if (node) {
            let container = node.parentElement;
            return container && container.classList.contains(this.classes.container) ? container : false;
        } else {
            return false;
        }
    }

    reset() {
        if (this.draggedItem) {
            this.draggedItem.classList.remove(this.classes.dragging);
            this.draggedItem.style.opacity = '1';
            this.draggedItem = false;
        }

        if (this.hoveredItem) {
            this.hoveredItem.classList.remove(this.classes.dragOver);
            this.hoveredItem = false;
        }

        if (this.ghostItem) {
            this.ghostItem.remove();
            this.ghostItem = false;
        }

        this.draggedItemContainer = false;
    }
}
