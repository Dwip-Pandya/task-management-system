$(document).ready(function () {
    $("#taskForm").validate({
        rules: {
            title: {
                required: true,
                maxlength: 255
            },
            assigned_to: {
                required: true
            },
            project_id: {
                required: true
            },
            status_id: {
                required: true
            },
            priority_id: {
                required: true
            },
            description: {
                maxlength: 1000
            },
            due_date: {
                date: true
            }
        },
        messages: {},

        errorElement: "div",
        errorClass: "text-danger small mt-1",

        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function (error, element) {
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
});
