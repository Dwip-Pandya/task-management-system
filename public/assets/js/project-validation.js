document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');

    form.addEventListener('submit', function (e) {
        let isValid = true;

        // Clear previous errors
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        nameInput.classList.remove('is-invalid');
        descriptionInput.classList.remove('is-invalid');

        // Validate Project Name
        if (nameInput.value.trim() === '') {
            showError(nameInput, 'Project Name is required.');
            isValid = false;
        } else if (nameInput.value.length > 255) {
            showError(nameInput, 'Project Name must be less than 255 characters.');
            isValid = false;
        }

        // Validate Description (optional, max length 500)
        if (descriptionInput.value.length > 500) {
            showError(descriptionInput, 'Description must be less than 500 characters.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault(); // stop form submission
        }
    });

    function showError(input, message) {
        input.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        input.parentNode.appendChild(errorDiv);
    }
});
