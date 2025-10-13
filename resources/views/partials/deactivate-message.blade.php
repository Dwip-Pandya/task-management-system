@if (session('is_deactivated'))
<div class="alert alert-danger text-center m-0 py-2" role="alert" style="font-size: 0.95rem;">
    ⚠️ Your account has been <strong>deactivated</strong>. You are in <strong>view-only mode</strong> — all operations are disabled.
</div>
@endif