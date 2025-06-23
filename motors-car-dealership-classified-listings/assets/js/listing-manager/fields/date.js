document.addEventListener('change', function (e) {
    let dateInput = MVL_Listing_Manager.getClosestByClass(e.target, 'mvl-listing-manager-field-date-input');
    if (dateInput) {
        let input = dateInput.previousElementSibling;

        if (input && input.classList.contains('mvl-listing-manager-field-input')) {
            if (dateInput.value) {
                let value = dateInput.value.split('-');
                input.value = `${value[2]}/${value[1]}/${value[0]}`;
            } else {
                input.value = '';
            }
        }
    }
});

document.addEventListener('keydown', function (e) {
    let input;
    if (typeof MVL_Listing_Manager !== 'undefined' && typeof MVL_Listing_Manager.getClosestByID === 'function') {
        input = MVL_Listing_Manager.getClosestByID(e.target, 'mvl-listing-manager-field-input-manufacture_date-date');
    } else {
        input = e.target.closest('#mvl-listing-manager-field-input-manufacture_date-date') || 
                document.getElementById('mvl-listing-manager-field-input-manufacture_date-date');
    }

    if (input) {
        let dateInput = input.nextElementSibling;
        if (dateInput) {

            if (e.key.length === 1) {
                e.preventDefault();

            }
        }
    }
});

