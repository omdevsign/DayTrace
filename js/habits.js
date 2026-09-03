document.addEventListener("DOMContentLoaded", function () {
    const habitForm = document.getElementById("add-habit-form");
    if (habitForm) {
        habitForm.addEventListener("submit", createHabit);
    }

    const container = document.getElementById("habit-list-container");
    if (container) {
        container.addEventListener("click", function (event) {
            const target = event.target;

            if (target.classList.contains("btn-complete")) {
                markCompleted(target);
            }

            if (target.classList.contains("btn-delete-habit")) {
                deleteHabit(target);
            }
        });
    }
});

function createHabit(event) {
    event.preventDefault();
    const name = document.getElementById("habit-name").value;
    const freq = document.getElementById("target-frequency").value;
    const container = document.getElementById("habit-list-container");

    const today = new Date().toISOString().split("T")[0];

    const habitHTML = `
        <div class="habit-item">
            <div class="habit-details">
                <h4>${name} <span class="badge-freq">${freq}</span></h4>
                <p>Created: ${today}</p>
            </div>
            <div class="habit-actions">
                <button class="btn-complete">Log Complete</button>
                <button class="btn-delete-habit">Delete</button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML("beforeend", habitHTML);
    document.getElementById("add-habit-form").reset();
    alert("New habit created dynamically! Ready for MySQL database connection.");
}

function markCompleted(button) {
    button.innerText = "Completed ✓";
    button.style.backgroundColor = "#64748b";
    button.disabled = true;
}

function deleteHabit(button) {
    if (confirm("Are you sure you want to remove this habit definition?")) {
        const item = button.closest(".habit-item");
        item.remove();
    }
}