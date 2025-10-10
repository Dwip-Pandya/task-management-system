$(document).ready(function () {

    // Assigned User
    $('.change-assigned').change(function () {
        let id = $(this).data('id');
        let assigned_to = $(this).val();
        $.ajax({
            url: '/admin/tasks/' + id + '/update-assigned',
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                assigned_to: assigned_to
            },
            success: function (data) {
                if (data.success) {
                    alert('Assigned user updated!');
                }
            }
        });
    });

    // Status
    $('.change-status').change(function () {
        let id = $(this).data('id');
        let status_id = $(this).val();
        $.ajax({
            url: '/admin/tasks/' + id + '/update-status',
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status_id: status_id
            },
            success: function (data) {
                if (data.success) {
                    alert('Status updated!');
                    location.reload(); // optional: to reflect badge/card color
                }
            }
        });
    });

    // Priority
    $('.change-priority').change(function () {
        let id = $(this).data('id');
        let priority_id = $(this).val();
        $.ajax({
            url: '/admin/tasks/' + id + '/update-priority',
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                priority_id: priority_id
            },
            success: function (data) {
                if (data.success) {
                    alert('Priority updated!');
                }
            }
        });
    });

});