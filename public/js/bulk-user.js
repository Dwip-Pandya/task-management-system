document.getElementById('select-all').addEventListener('click', function (event) {
    const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
    checkboxes.forEach(cb => cb.checked = event.target.checked);
});
