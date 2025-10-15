$(document).ready(function () {

    // Common for both forms
    function setValidation(formSelector, rules, messages) {
        $(formSelector).validate({
            rules: rules,
            messages: messages,
            errorElement: "div",
            errorClass: "text-danger small mt-1 validation-error",
            highlight: function (element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    }

    // ---- LOGIN FORM ----
    setValidation("#loginForm", {
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
        }
    }, {
        email: {
            required: "Email is required.",
            email: "Invalid email format."
        },
        password: {
            required: "Password is required.",
        }
    });

    // REGISTER FORM 
    setValidation("#registerForm", {
        name: {
            required: true,
            minlength: 3
        },
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
            minlength: 6
        },
        password_confirmation: {
            required: true,
            equalTo: "[name='password']"
        },
        role_id: {
            required: true
        }
    }, {
        name: {
            required: "Name is required.",
            minlength: "Name must be at least 3 characters."
        },
        email: {
            required: "Email is required.",
            email: "Invalid email format."
        },
        password: {
            required: "Password is required.",
            minlength: "Password must be at least 6 characters."
        },
        password_confirmation: {
            required: "Confirm Password is required.",
            equalTo: "Passwords do not match."
        },
        role_id: {
            required: "Please select a role."
        }
    });
});
