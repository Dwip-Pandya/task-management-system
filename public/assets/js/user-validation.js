$(document).ready(function () {
    $('form').each(function () {
        const $form = $(this);
        const isEditForm = $form.find('input[name="_method"]').val() === 'PUT';

        $form.validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: !isEditForm,
                    minlength: 6
                },
                role_id: {
                    required: true
                }
            },
            messages: {},
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
});
