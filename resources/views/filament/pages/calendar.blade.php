<x-filament-panels::page>

    <x-filament::card>
        <div class="fi-ac gap-3 flex flex-wrap items-center justify-end shrink-0 sm:mt-7">
            <a href="{{ route('filament.admin.resources.appointments.create') }}"
                style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                class="fi-btn relative grid-flow-col items-center justify-right font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-6 py-3 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action mt-4 mb-4">
                <span class="fi-btn-label">
                    Create New Appointment
                </span>
            </a>
        </div>

        <div id="calendar"></div>
    </x-filament::card>

    @push('styles')
        <!-- FullCalendar CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
        <style>
            /* Custom style to disable past dates visually */
            .fc-day-disabled {
                background-color: #f1f1f1 !important;
                color: #d3d3d3 !important;
                pointer-events: none !important;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- FullCalendar JS -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth', // Set to month view
                    selectable: true, // Allow date selection
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: function(fetchInfo, successCallback, failureCallback) {
                        fetch('{{ route('appointments.fetch') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    start: fetchInfo.startStr,
                                    end: fetchInfo.endStr
                                })
                            })
                            .then(response => response.json())
                            .then(data => successCallback(data))
                            .catch(error => failureCallback(error));
                    },
                    dateClick: function(info) {
                        // Prevent clicking past or today dates
                        var selectedDate = info.dateStr;
                        var today = new Date().toISOString().split('T')[0];

                        if (selectedDate < today) {
                            return; // Do nothing if the clicked date is in the past
                        }

                        // Redirect to the appointment creation page with the selected date as a query parameter
                        window.location.href =
                            '{{ route('filament.admin.resources.appointments.create') }}' + '?date=' +
                            selectedDate;
                    },
                    eventClick: function(info) {
                        // Redirect to the view page of the appointment with the correct ID
                        window.location.href =
                            '{{ route('filament.admin.resources.appointments.view', ['record' => '__appointmentId__']) }}'
                            .replace('__appointmentId__', info.event.id);
                    },
                    validRange: {
                        start: new Date().toISOString().split('T')[0] // Disable past dates and today
                    },
                    dayRender: function(info) {
                        var today = new Date().toISOString().split('T')[0];
                        // Disable past dates
                        if (info.date < today) {
                            info.el.classList.add('fc-day-disabled'); // Apply custom class to past dates
                        }
                    }
                });

                calendar.render();
            });
        </script>
    @endpush
</x-filament-panels::page>
