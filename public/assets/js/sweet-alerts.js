
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
