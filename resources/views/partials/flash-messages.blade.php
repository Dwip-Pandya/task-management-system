@if(session('success'))
<div class="alert alert-success glass-alert">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger glass-alert">
    {{ session('error') }}
</div>
@endif