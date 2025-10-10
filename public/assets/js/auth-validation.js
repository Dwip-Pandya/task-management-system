document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form[id='registerForm'], form[id='loginForm']");

    // Function to show and clear errors
    function showError(input, message) {
        clearError(input);
        const error = document.createElement("div");
        error.className = "text-danger small mt-1 validation-error";
        error.innerText = message;
        input.classList.add("is-invalid");
        input.parentNode.appendChild(error);
    }

    function clearError(input) {
        input.classList.remove("is-invalid");
        const existing = input.parentNode.querySelector(".validation-error");
        if (existing) existing.remove();
    }

    function clearAll(form) {
        form.querySelectorAll(".validation-error").forEach(e => e.remove());
        form.querySelectorAll(".is-invalid").forEach(e => e.classList.remove("is-invalid"));
    }

    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            clearAll(form);
            let valid = true;
            const isRegister = form.id === "registerForm";

            // Common fields
            const email = form.querySelector("[name='email']");
            const password = form.querySelector("[name='password']");
            const emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

            // ---- Common Validation ----
            if (!email.value.trim()) {
                showError(email, "Email is required.");
                valid = false;
            } else if (!emailRegex.test(email.value.trim())) {
                showError(email, "Invalid email format.");
                valid = false;
            }

            if (!password.value.trim()) {
                showError(password, "Password is required.");
                valid = false;
            }

            // Extra for Register
            if (isRegister) {
                const name = form.querySelector("[name='name']");
                const confirm = form.querySelector("[name='password_confirmation']");
                const role = form.querySelector("[name='role_id']");

                if (!name.value.trim()) {
                    showError(name, "Name is required.");
                    valid = false;
                } else if (name.value.trim().length < 3) {
                    showError(name, "Name must be at least 3 characters.");
                    valid = false;
                }

                if (password.value.trim().length < 6) {
                    showError(password, "Password must be at least 6 characters.");
                    valid = false;
                }

                if (password.value.trim() !== confirm.value.trim()) {
                    showError(confirm, "Passwords do not match.");
                    valid = false;
                }

                if (!role.value.trim()) {
                    showError(role, "Please select a role.");
                    valid = false;
                }
            }

            // Stop submission if invalid
            if (!valid) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
        });
    });
});
