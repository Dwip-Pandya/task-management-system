document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form");

    forms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            let valid = true;
            const errors = {};

            // Title validation
            const title = form.querySelector("[name='title']");
            if (!title.value.trim()) {
                valid = false;
                errors.title = "Title is required.";
            } else if (title.value.length > 255) {
                valid = false;
                errors.title = "Title must be less than 255 characters.";
            }

            // assigned_to validation
            const assigned_to = form.querySelector("[name='assigned_to']");
            if (!assigned_to.value) {
                valid = false;
                errors.assigned_to = "Please select an assigned user.";
            }


            // Status validation
            const status = form.querySelector("[name='status_id']");
            if (!status.value) {
                valid = false;
                errors.status_id = "Please select a status.";
            }

            // Priority validation
            const priority = form.querySelector("[name='priority_id']");
            if (!priority.value) {
                valid = false;
                errors.priority_id = "Please select a priority.";
            }

            // Project validation
            const project = form.querySelector("[name='project_id']");
            if (!project.value) {
                valid = false;
                errors.project_id = "Please select a project.";
            }

            // Display errors
            form.querySelectorAll(".text-danger").forEach((el) => el.remove());
            for (const key in errors) {
                const field = form.querySelector(`[name='${key}']`);
                if (field) {
                    const errorEl = document.createElement("div");
                    errorEl.className = "text-danger small mt-1";
                    errorEl.innerText = errors[key];
                    field.parentNode.appendChild(errorEl);
                }
            }

            if (!valid) e.preventDefault();
        });
    });
});
