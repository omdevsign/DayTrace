document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById('log-date');
    if (dateInput) {
        dateInput.valueAsDate = new Date();
    }

    const logForm = document.getElementById('daily-log-form');
    if (logForm) {
        logForm.addEventListener('submit', function (event) {
            event.preventDefault();
            alert("Daily log client-side validation passed! Ready for PHP script handler.");
        });
    }

    const historyTableBody = document.getElementById('history-table-body');
    if (historyTableBody) {
        historyTableBody.addEventListener('click', function (event) {
            const target = event.target;
            
            if (target.classList.contains('btn-delete')) {
                if (confirm("Are you sure you want to delete this historical record?")) {
                    const row = target.closest('tr');
                    row.remove();
                }
            }
            
            if (target.classList.contains('btn-edit')) {
                alert("Populating form for inline editing...");
            }
        });
    }
});