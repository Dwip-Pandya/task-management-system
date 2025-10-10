document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll(".comment-form");

    forms.forEach(form => {
        form.addEventListener("submit", function (e) {
            let valid = true;

            const messageField = form.querySelector("textarea[name='message']");
            const nextError = messageField.nextElementSibling;
            if (nextError && nextError.classList.contains("validation-error")) {
                nextError.remove();
            }

            const value = messageField.value.trim();

            // HTML tag check
            const htmlRegex = /<\/?[\w\s="/.':;#-\/]+>/gi;
            if (htmlRegex.test(value)) {
                valid = false;
                const error = document.createElement("div");
                error.className = "text-danger small mt-1 validation-error";
                error.innerText = "HTML tags are not allowed.";
                messageField.parentNode.appendChild(error);
            }

            if (!valid) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });
    });
});
