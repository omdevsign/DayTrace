document.addEventListener('DOMContentLoaded', () => {
    const logDateInput = document.getElementById('log-date');
    if (logDateInput && !logDateInput.value) {
        logDateInput.valueAsDate = new Date();
    }
});