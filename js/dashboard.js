document.addEventListener("DOMContentLoaded", function () {
    fetch('php/dashboard_data.php')
        .then(response => response.json())
        .then(data => {
            renderHabitChart(data.habits);
            renderSleepChart(data.sleep);
            renderJournalSummary(data.journal);
        })
        .catch(err => console.error("Error loading dashboard data:", err));
});

function renderHabitChart(habitData) {
    const habitCtx = document.getElementById('habitPieChart');
    if (!habitCtx) return;

    new Chart(habitCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Missed'],
            datasets: [{
                data: [habitData.completed, habitData.missed],
                backgroundColor: ['#6366f1', '#e2e8f0'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

function renderSleepChart(sleepData) {
    const sleepCtx = document.getElementById('sleepGraph');
    if (!sleepCtx) return;

    new Chart(sleepCtx, {
        type: 'bar',
        data: {
            labels: sleepData.labels,
            datasets: [
                {
                    label: 'Hours Slept',
                    data: sleepData.hours,
                    backgroundColor: '#6366f1',
                    borderRadius: 4
                },
                {
                    label: 'Energy (Scaled x2)',
                    data: sleepData.energy,
                    type: 'line',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: '#ffffff'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 12 }
            }
        }
    });
}

function renderJournalSummary(journalData) {
    const counterElement = document.getElementById('journal-counter');
    if (counterElement) {
        counterElement.textContent = `Total Reflections Logged: ${journalData.total}`;
    }

    const journalCtx = document.getElementById('journalGraph');
    if (!journalCtx) return;

    new Chart(journalCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Logged Entries',
                data: [journalData.total > 0 ? journalData.total : 0, journalData.total],
                borderColor: '#334155',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
}