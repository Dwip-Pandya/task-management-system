$(document).ready(function () {

    $(".comment-form").each(function () {
        const form = $(this);

        form.validate({
            rules: {
                message: {
                    required: form.find("#blank-comment").length ? true : false,
                    maxlength: 1000,
                    noHtml: true
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
                error.insertAfter(element);
            }
        });

        $.validator.addMethod(
            "noHtml",
            function (value, element) {
                const htmlRegex = /<\/?[\w\s="/.':;#-\/]+>/gi;
                return !htmlRegex.test(value);
            },
            "no html allowed"
        );
    });

});
