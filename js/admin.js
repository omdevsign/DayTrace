document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('user-search');
    const tableBody = document.getElementById('user-table-body');

    if (searchInput && tableBody) {
        searchInput.addEventListener('keyup', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const text = row.cells[1] ? row.cells[1].innerText.toLowerCase() : '';
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});