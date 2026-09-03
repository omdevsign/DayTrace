document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('user-search');
    const tableBody = document.getElementById('user-table-body');

    searchInput.addEventListener('keyup', () => {
        const filter = searchInput.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const text = row.cells[1].innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    tableBody.addEventListener('click', (e) => {
        const button = e.target;
        if (!button.classList.contains('btn-action')) return;

        const row = button.closest('tr');
        const statusCell = row.cells[3];

        if (button.classList.contains('btn-suspend')) {
            if (confirm('Suspend this user account?')) {
                statusCell.innerHTML = '<span class="badge badge-suspended">Suspended</span>';
                button.textContent = 'Activate';
                button.className = 'btn-action btn-activate';
            }
        } else if (button.classList.contains('btn-activate')) {
            statusCell.innerHTML = '<span class="badge badge-active">Active</span>';
            button.textContent = 'Suspend';
            button.className = 'btn-action btn-suspend';
        } else if (button.classList.contains('btn-delete')) {
            if (confirm('Permanently delete this user record?')) {
                row.remove();
            }
        }
    });
});