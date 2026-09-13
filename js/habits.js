document.addEventListener("DOMContentLoaded", function () {
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!confirm("Are you sure you want to permanently delete this habit?")) {
                event.preventDefault();
            }
        });
    });
});