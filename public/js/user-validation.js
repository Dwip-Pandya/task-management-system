document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            let isValid = true;

            // Clear previous errors
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Get inputs
            const name = form.querySelector('input[name="name"]');
            const email = form.querySelector('input[name="email"]');
            const password = form.querySelector('input[name="password"]');
            const role = form.querySelector('select[name="role_id"]');

            // Validate Name
            if (name.value.trim() === '') {
                showError(name, 'Name is required.');
                isValid = false;
            } else if (name.value.length > 100) {
                showError(name, 'Name must be less than 100 characters.');
                isValid = false;
            }

            // Validate Email
            if (email.value.trim() === '') {
                showError(email, 'Email is required.');
                isValid = false;
            } else if (!validateEmail(email.value)) {
                showError(email, 'Invalid email format.');
                isValid = false;
            }

            // Validate Password (required only on create)
            if (password && password.type !== 'hidden') {
                const isEditForm = form.querySelector('input[name="_method"]')?.value === 'PUT';
                if (!isEditForm || password.value.trim() !== '') {
                    if (password.value.length < 6) {
                        showError(password, 'Password must be at least 6 characters.');
                        isValid = false;
                    }
                }
            }

            // Validate Role
            if (role.value === '') {
                showError(role, 'Please select a role.');
                isValid = false;
            }

            if (!isValid) e.preventDefault();
        });
    });

    function showError(input, message) {
        input.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        input.parentNode.appendChild(errorDiv);
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
