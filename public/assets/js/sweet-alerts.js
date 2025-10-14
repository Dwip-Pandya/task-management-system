// Ensure this runs after DOM is loaded
document.addEventListener('DOMContentLoaded', function () {

    // SweetAlert2 dark theme config
    const swalDark = Swal.mixin({
        background: '#2c2c2c',
        color: '#fff',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    });

    /* -----------------------------
       SWITCH USER BUTTONS
       ----------------------------- */
    document.querySelectorAll('.swal-switch-user').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            let url = this.dataset.url; // Get the URL from data attribute
            let title = this.dataset.title || 'Switch to this user account?';
            let confirmText = this.dataset.confirm || 'Yes, switch';
            let cancelText = this.dataset.cancel || 'Cancel';

            swalDark.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirect to the switch route
                }
            });
        });
    });

    document.querySelectorAll('.swal-switch-back').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            let url = this.getAttribute('href'); // redirect URL
            let title = this.dataset.title || 'Return to your admin account?';
            let confirmText = this.dataset.confirm || 'Yes, switch back';
            let cancelText = this.dataset.cancel || 'Cancel';

            swalDark.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // redirect
                }
            });
        });
    });

    document.querySelectorAll('.swal-restore-user').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let userId = this.dataset.userid; // Use data attribute for user ID
            if (!userId) return;

            swalDark.fire({
                title: 'Restore this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create dynamic form to submit restore request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/admin/users/' + userId + '/restore'; // your restore route

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.swal-bulk-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const form = this.closest('form');
            if (!form) return;

            // Check if at least one checkbox is selected
            const checkboxes = form.querySelectorAll('input[name="user_ids[]"]:checked');
            if (checkboxes.length === 0) {
                swalDark.fire({
                    title: 'Please select at least one user!',
                    icon: 'warning'
                });
                return;
            }

            swalDark.fire({
                title: 'Are you sure you want to delete selected users?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.swal-delete-user').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const userId = this.dataset.userid; // pass user id via data attribute
            if (!userId) return;

            swalDark.fire({
                title: 'Delete this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a dynamic form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/admin/users/' + userId;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

});

/* -----------------------------
   AJAX SUCCESS ALERTS
   ----------------------------- */
function showAjaxSuccess(message) {
    Swal.fire({
        title: message,
        icon: 'success',
        background: '#2c2c2c',
        color: '#fff',
        timer: 1500,
        showConfirmButton: false
    });
}

/* -----------------------------
   FULLCALENDAR EVENT CLICK
   ----------------------------- */
function showEventInfo(title, htmlContent) {
    Swal.fire({
        title: title,
        html: htmlContent,
        icon: 'info',
        background: '#2c2c2c',
        color: '#fff'
    });
}
