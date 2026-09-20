document.addEventListener("DOMContentLoaded", function () {
    const entryDate = document.getElementById('entry-date');
    if (entryDate) {
        entryDate.valueAsDate = new Date();
    }

    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            const confirmed = confirm("Are you sure you want to delete this reflection? This action cannot be undone.");
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});