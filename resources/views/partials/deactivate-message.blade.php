@if (session('is_deactivated'))
<div class="alert-deleted-message mt-3" role="alert" style="font-size: 0.95rem;">
    ⚠️ Your account has been <strong>deactivated</strong>. You are in <strong>view-only mode</strong> — all operations are disabled.
</div>
@endif