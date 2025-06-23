let MvlListingManagerSearch = {
	data: {
		searchInput: null,
		searchResults: [],
		searchTimeout: null,
		clearBtn: null
	},
	init: function() {
		this.data.searchInput = document.querySelector('.mvl-lm-search-field-input');
		if (!this.data.searchInput) return;

		this.data.clearBtn = document.createElement('span');
		this.data.clearBtn.className = 'mvl-search-clear-btn';
		this.data.clearBtn.innerHTML = '&times;';
		this.data.clearBtn.style.display = 'none';
		this.data.clearBtn.addEventListener('click', () => {
			this.data.searchInput.value = '';
			this.clearResults();
			this.data.clearBtn.style.display = 'none';
			this.data.searchInput.focus();
		});
		this.data.searchInput.parentNode.style.position = 'relative';
		this.data.searchInput.parentNode.appendChild(this.data.clearBtn);

		this.data.searchInput.addEventListener('input', this.handleSearch.bind(this));
		this.data.searchInput.addEventListener('input', this.toggleClearBtn.bind(this));
		this.data.searchInput.addEventListener('keydown', this.handleKeydown.bind(this));
	},
	handleSearch: function(event) {
		const searchValue = event.target.value.toLowerCase();
		
		if (this.data.searchTimeout) {
			clearTimeout(this.data.searchTimeout);
		}

		this.data.searchTimeout = setTimeout(() => {
			this.searchFields(searchValue);
		}, 300);
	},
	searchFields: function(searchValue) {
		if (!searchValue) {
			this.clearResults();
			return;
		}

		const fields = document.querySelectorAll('.mvl-listing-manager-field');
		const pages = document.querySelectorAll('.mvl-listing-manager-content-body-page');
		this.data.searchResults = [];

		fields.forEach(field => {
			const label = field.getAttribute('data-label')?.toLowerCase();
			if (label && label.includes(searchValue)) {
				const page = field.closest('.mvl-listing-manager-content-body-page');
				const pageTitle = page?.querySelector('.mvl-listing-manager-content-body-page-title')?.textContent || 'Unknown Page';
				this.data.searchResults.push({
					field: field,
					pageTitle: pageTitle,
					type: 'field'
				});
			}
		});

		pages.forEach(page => {
			const pageTitleElement = page.querySelector('.mvl-listing-manager-content-body-page-title');
			const pageTitle = pageTitleElement?.textContent || '';
			if (pageTitle && pageTitle.toLowerCase().includes(searchValue)) {
				this.data.searchResults.push({
					field: page,
					pageTitle: pageTitle,
					type: 'page'
				});
			}
		});

		this.showResults();
	},

	highlightMatch: function(text, search) {
		if (!search) return text;
		const regex = new RegExp(`(${search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'ig');
		return text.replace(regex, '<b>$1</b>');
	},
	showResults: function() {
		this.clearResults();

		if (this.data.searchResults.length === 0) return;

		const resultsContainer = document.createElement('div');
		resultsContainer.className = 'mvl-search-results';

		this.data.searchResults.forEach(result => {
			const resultItem = document.createElement('div');
			resultItem.className = 'mvl-search-result-item';

			const leftCol = document.createElement('span');
			leftCol.className = 'mvl-search-result-label';
			let labelText = '';
			if (result.type === 'field') {
				const label = result.field.getAttribute('data-label') || '';
				leftCol.innerHTML = this.highlightMatch(label, this.data.searchInput.value);
			} else {
				const pageTitle = result.pageTitle || '';
				leftCol.innerHTML = this.highlightMatch(pageTitle, this.data.searchInput.value);
			}

			const rightCol = document.createElement('span');
			rightCol.className = 'mvl-search-result-page';
			rightCol.textContent = result.pageTitle;

			resultItem.appendChild(leftCol);
			resultItem.appendChild(rightCol);

			resultItem.addEventListener('click', () => this.navigateToField(result.field));
			resultsContainer.appendChild(resultItem);
		});

		this.data.searchInput.parentNode.appendChild(resultsContainer);
	},
	clearResults: function() {
		const existingResults = document.querySelector('.mvl-search-results');
		if (existingResults) {
			existingResults.remove();
		}
	},
	navigateToField: function(field) {
		const pageId = field.closest('.mvl-listing-manager-content-body-page')?.getAttribute('data-pageid');
		if (pageId) {
			MVL_Listing_Manager.closeCurrentPage();
			MVL_Listing_Manager.openPage(pageId);
			if (!field.classList.contains('mvl-listing-manager-content-body-page')) {
				const currentPage = document.querySelector(`.mvl-listing-manager-content-body-page[data-pageid=\"${pageId}\"]`);
				const scrollContainer = currentPage?.querySelector('.mvl-listing-manager-content-body-page-text');
				if (scrollContainer) {
					const fieldRect = field.getBoundingClientRect();
					const containerRect = scrollContainer.getBoundingClientRect();
					const scrollTop = fieldRect.top - containerRect.top + scrollContainer.scrollTop - (containerRect.height / 2) + (fieldRect.height / 2);
					scrollContainer.scrollTo({
						top: scrollTop,
						behavior: 'smooth'
					});
				}
				field.classList.add('mvl-field-highlight');
				setTimeout(() => {
					field.classList.remove('mvl-field-highlight');
				}, 2000);
			}
			this.clearResults();
			this.data.searchInput.value = '';
			if (this.data.clearBtn) this.data.clearBtn.style.display = 'none';
		}
	},
	handleKeydown: function(event) {
		if (event.key === 'Escape') {
			this.clearResults();
			this.data.searchInput.value = '';
		}
	},
	toggleClearBtn: function() {
		if (this.data.searchInput.value.length > 0) {
			this.data.clearBtn.style.display = 'block';
		} else {
			this.data.clearBtn.style.display = 'none';
		}
	}
};

document.addEventListener('DOMContentLoaded', function() {
	MvlListingManagerSearch.init();
});
