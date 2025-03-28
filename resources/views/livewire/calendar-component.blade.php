<div>
    <h2 class="text-2xl font-bold mb-4 text-center">Calendario de Citas - Terapia Física</h2>

    <div id='calendar'></div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: @json($events),
                    locale: 'es',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek,listWeek'
                    },
                    
                    buttonText: {
                        today:    'Hoy',
                        month:    'Mensual',
                        week:     'Semanal',
                        day:      'Día',
                        listWeek: 'Lista Semanal'   
                    },
                    titleFormat: {
                        year: 'numeric',
                        month: 'long' 
                    },
                    views: {
                        dayGridMonth: {
                            titleFormat: { year: 'numeric', month: 'long' }
                        },
                        dayGridWeek: {
                            titleFormat: { year: 'numeric', month: 'short', day: 'numeric' }
                        },
                        listWeek: {
                            titleFormat: { year: 'numeric', month: 'long', week: 'numeric' }
                        }
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
</div>
