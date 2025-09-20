<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Calendar</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/partials.css') }}" rel="stylesheet">
    <link href="{{ asset('css/calendar.css') }}" rel="stylesheet">
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">

    <!-- Header -->
    @include('partials.header', ['user' => $user])

    <div class="d-flex flex-grow-1">
        <!-- Sidebar -->
        @include('partials.sidebar', ['user' => $user])

        <!-- Main Content -->
        <div class="main-content">
            <div id="calendar"></div>
        </div>

    </div>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <!-- Your Original Script (unchanged) -->
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
                    alert('Task: ' + info.event.title + '\nDue: ' + info.event.start.toISOString().split('T')[0]);
                }
            });
            calendar.render();
        });
    </script>
</body>

</html>