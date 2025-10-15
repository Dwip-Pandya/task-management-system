$(document).ready(function () {
    $("#projectForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 255
            },
            description: {
                maxlength: 500
            }
        },
        messages: {},

        errorElement: "div",
        errorClass: "invalid-feedback",

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