class MVL_Listing_Manager_Listing_Card {
    constructor() {
        this.image = document.querySelector('.mvl-listing-preview-card-image');
        document.addEventListener('mvl-listing-manager-field-image-list-item-rendered', this.onImageChanged.bind(this));
        document.addEventListener('mvl-listing-manager-field-image-list-item-deleted', this.onImageChanged.bind(this));
        document.addEventListener('mvl-listing-manager-field-image-list-item-dragged', this.onImageChanged.bind(this));
        document.addEventListener('mvl-listing-manager-field-image-cleared', this.onImagesCleared.bind(this));
        document.addEventListener('draggable-item-changed', this.onImageChanged.bind(this));
        document.addEventListener('input', this.input.bind(this));
        document.addEventListener('mvl-listing-manager-field-color-input-changed-badge_bg_color', this.onBadgeBgColorChanged.bind(this));
    }

    onBadgeBgColorChanged(e) {
        let input = e.detail.input;
        let color = input.value;

        document.querySelector('.mvl-listing-preview-card-badge').style.backgroundColor = color;
    }

    input(e) {
        if (e.target.id === 'title') {
            if (e.target.value) {
                document.querySelector('.mvl-listing-manager-sidebar-title').innerHTML = e.target.value;
                document.querySelector('.mvl-listing-preview-card-title-value').innerHTML = e.target.value;
            } else {
                document.querySelector('.mvl-listing-manager-sidebar-title').innerHTML = listingManager.text.untitled;
                document.querySelector('.mvl-listing-preview-card-title-value').innerHTML = listingManager.text.untitled;
            }
        }

        if (e.target.id === 'badge_text') {
            if (e.target.value) {
                document.querySelector('.mvl-listing-preview-card-badge-text').innerHTML = e.target.value;
            } else {
                document.querySelector('.mvl-listing-preview-card-badge-text').innerHTML = listingManager.text.special;
            }
        }

        if (e.target.dataset.slug === 'price') {
            if (e.target.value) {
                if (listingManager.currency_position === 'right') {
                    document.querySelector('.mvl-listing-preview-card-price-value').innerHTML = e.target.value + listingManager.currency_symbol;
                } else {
                    document.querySelector('.mvl-listing-preview-card-price-value').innerHTML = listingManager.currency_symbol + e.target.value;
                }
            } else {
                document.querySelector('.mvl-listing-preview-card-price-value').innerHTML = '-';
            }
        }

        if (e.target.dataset.slug === 'sale_price') {
            if (e.target.value) {
                document.querySelector('.mvl-listing-preview-card-sale-price-value').innerHTML = e.target.value + listingManager.currency_symbol;
            } else {
                document.querySelector('.mvl-listing-preview-card-sale-price-value').innerHTML = '-';
            }
        }
    }

    onImagesCleared(e) {
        if (e.detail.container.classList.contains('mvl-listing-manager-field-image-images')) {
            this.unsetImage();
        }
    }

    onImageChanged(e) {
        let item = e.detail.item;

        if (item.closest('.mvl-listing-manager-field-image-images')) {
            let container = item.closest('.mvl-listing-manager-field-image-images').querySelector('.mvl-listing-manager-field-image-list');

            requestAnimationFrame(() => {
                let items = container.querySelectorAll('.mvl-listing-manager-field-image-list-item');

                if (items.length > 0) {
                    let image = items[0].querySelector('.mvl-listing-manager-field-image-list-item-image');
                    this.setImage(image.src);
                } else {
                    this.unsetImage();
                }
            });
        }
    }

    setImage(url) {
        this.image.innerHTML = this.getImageTemplate(url);
    }

    unsetImage() {
        this.image.innerHTML = this.getEmptyImageTemplate();
    }

    getImageTemplate(url) {
        return `
            <img src="${url}" alt="">
        `;
    }

    getEmptyImageTemplate() {
        return `
            <div class="mvl-listing-preview-card-image-placeholder">
                <i class="motors-icons-mvl_file_select"></i>
            </div>
        `;
    }
}

