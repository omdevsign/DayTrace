document.addEventListener("DOMContentLoaded", function () {
    const entryDate = document.getElementById('entry-date');
    if (entryDate) {
        entryDate.valueAsDate = new Date();
    }

    const journalForm = document.getElementById('journal-form');
    if (journalForm) {
        journalForm.addEventListener('submit', addJournalEntry);
    }

    const feedContainer = document.getElementById('journal-feed-container');
    if (feedContainer) {
        feedContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('btn-delete')) {
                deleteEntry(event.target);
            } else if (event.target.classList.contains('btn-edit')) {
                editEntry(event.target);
            }
        });
    }
});

function addJournalEntry(event) {
    event.preventDefault();
    const date = document.getElementById('entry-date').value;
    const highlight = document.getElementById('daily-highlight').value;
    const growth = document.getElementById('area-growth').value;
    const gratitude = document.getElementById('gratitude-statement').value;
    const tagsInput = document.getElementById('entry-tags').value;

    const container = document.getElementById('journal-feed-container');

    let tagsHTML = '';
    if (tagsInput.trim() !== '') {
        const tagsArray = tagsInput.split(',');
        tagsArray.forEach(tag => {
            if (tag.trim() !== '') {
                tagsHTML += `<span class="tag-badge">${tag.trim()}</span>`;
            }
        });
    }

    const entryHTML = `
        <div class="journal-item">
            <div class="journal-header">
                <span class="journal-date">${date}</span>
                <div class="journal-tags">${tagsHTML}</div>
            </div>
            <div class="journal-field">
                <strong>Daily Highlight</strong>
                <p>${highlight}</p>
            </div>
            <div class="journal-field">
                <strong>Area for Growth</strong>
                <p>${growth}</p>
            </div>
            <div class="journal-field">
                <strong>Gratitude Statement</strong>
                <p>${gratitude}</p>
            </div>
            <div class="journal-actions">
                <button class="action-btn btn-edit">Edit</button>
                <button class="action-btn btn-delete">Delete</button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('afterbegin', entryHTML);
    document.getElementById('journal-form').reset();
    document.getElementById('entry-date').valueAsDate = new Date();
    alert("Reflection logged dynamically! Ready for MySQL database insertion.");
}

function deleteEntry(button) {
    if (confirm("Are you sure you want to delete this reflection entry?")) {
        const item = button.closest('.journal-item');
        if (item) {
            item.remove();
        }
    }
}

function editEntry(button) {
    alert("Populating form fields for inline editing...");
}