document.addEventListener('change', function (e) {
    if (e.target.classList.contains('mvl-listing-manager-field-switch-input')) {
        var id = e.target.id;
        var dependencies = document.querySelectorAll(`[data-checked="${id}"]`);
        var checked = e.target.checked;

        dependencies.forEach(function (dependency) {
            if (checked) {
                dependency.classList.add('active');
            } else {
                dependency.classList.remove('active');
            }
        });
    }
});


