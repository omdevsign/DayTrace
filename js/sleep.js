document.addEventListener('DOMContentLoaded', () => {
    const logDateInput = document.getElementById('log-date');
    if (logDateInput) {
        logDateInput.valueAsDate = new Date();
    }

    const goalForm = document.getElementById('goal-form');
    if (goalForm) {
        goalForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const target = document.getElementById('target-hours').value;
            alert("Baseline target goal updated to " + target + " hours!");
        });
    }

    const sleepLogForm = document.getElementById('sleep-log-form');
    if (sleepLogForm) {
        sleepLogForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const logDate = document.getElementById('log-date').value;
            const hours = parseFloat(document.getElementById('hours-slept').value);
            const energy = document.getElementById('energy-rating').value;
            const target = parseFloat(document.getElementById('target-hours').value);

            const tbody = document.getElementById('sleep-table-body');
            
            let statusBadge = hours >= target 
                ? `<span class="status-badge badge-good">Target Met</span>`
                : `<span class="status-badge badge-low">Sleep Deficit</span>`;

            const newRowHTML = `
                <tr>
                    <td>${logDate}</td>
                    <td>${hours.toFixed(1)} hrs</td>
                    <td>${statusBadge}</td>
                    <td>${energy} / 5</td>
                    <td>
                        <button class="action-btn btn-edit">Edit</button>
                        <button class="action-btn btn-delete">Delete</button>
                    </td>
                </tr>
            `;

            tbody.insertAdjacentHTML('afterbegin', newRowHTML);
            sleepLogForm.reset();
            logDateInput.valueAsDate = new Date();
            alert("Sleep log recorded dynamically!");
        });
    }

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-delete')) {
            if (confirm("Are you sure you want to delete this sleep log record?")) {
                const row = e.target.closest('tr');
                if (row) row.remove();
            }
        }

        if (e.target.classList.contains('btn-edit')) {
            alert("Populating form fields for inline editing...");
        }
    });
});