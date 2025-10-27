@extends('layouts.main')

@section('title', 'Task Calendar')

@push('styles')
<!-- FullCalendar CSS -->

<!-- Page-Specific CSS -->
<link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="main-content p-4 w-100">
    <h2 class="mb-3">Task Calendar</h2>

    @if($permissions['can_view'])
    <div class="calendar" id="calendar"></div>
    @else
    <div class="alert alert-danger text-center mt-4">
        You do not have permission to view the calendar.
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/fullcalendar/index.global.min.js') }}"></script>
@if($permissions['can_view'])
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            events: '{{ route("calendar.events") }}',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            eventClick: function(info) {
                showEventInfo(info.event.title,
                    `<p><strong>Due:</strong> ${info.event.startStr}</p>
                     <p><strong>Status:</strong> ${info.event.extendedProps.status}</p>`
                );
            }
        });
        calendar.render();
    });
</script>
@endif
@endpush