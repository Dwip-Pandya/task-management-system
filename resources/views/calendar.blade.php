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
    <div class="calendar" id="calendar"></div>
</div>
@endsection

@push('scripts')
<!-- FullCalendar JS -->
<script src="{{ asset('assets/js/fullcalendar/index.global.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            events: '{{ route("calendar.events") }}', // AJAX source
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
@endpush