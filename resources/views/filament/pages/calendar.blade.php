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

        <!-- Status Filter Buttons -->
        <div class="mb-4 flex gap-2 items-center">
            {{-- <span class="font-semibold">Filter by Status:</span> --}}
            <button class="status-filter-btn px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600"
                style="background-color: #061c34;" data-status="" data-color="#3B82F6">All</button>
            <button class="status-filter-btn px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600"
                style="background-color: #6B7280;" data-status="pending" data-color="#F59E0B">Pending</button>
            <button class="status-filter-btn px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600"
                style="background-color: #3B82F6;" data-status="confirmed" data-color="#10B981">Confirmed</button>
            <button class="status-filter-btn px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600"
                style="background-color: #F59E0B;" data-status="cancelled" data-color="#EF4444">Cancelled</button>
            <button class="status-filter-btn px-4 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600"
                style="background-color: #EF4444;" data-status="rejected" data-color="#6B7280">Rejected</button>
            <button class="status-filter-btn px-4 py-2 rounded-lg bg-gray-500 text-white hover:bg-gray-600"
                style="background-color: #10B981;" data-status="completed" data-color="#6B7280">Completed</button>
        </div>

        <div id="calendar"></div>
    </x-filament::card>

    @push('styles')
        <!-- FullCalendar CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
        <style>
            /* Active button styling */
            .status-filter-btn.active {
                outline: 2px solid #000;
                /* Add a dark outline for the active state */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                /* Optional shadow for emphasis */
            }

            /* Inactive styling for past and present dates */
            .past-date {
                background-color: #e5e7eb !important;
                /* Light gray for past dates */
                color: #6b7280 !important;
                /* Darker text for past dates */
                pointer-events: none !important;
                /* Disable clicking */
            }

            .present-date {
                background-color: #f3f4f6 !important;
                /* Slightly different gray for today */
                color: #9ca3af !important;
                /* Slightly lighter text for today */
                pointer-events: none !important;
                /* Disable clicking */
            }

            /* Specific date styling */
            .closed-date {
                background-color: #fef2f2 !important;
                /* subtle red tint (tailwind rose-50) */
                color: inherit !important;
                border: 1px dashed #fca5a5;
                /* light red border */
                position: relative;

                z-index: -10;
            }

            .closed-date::after {
                content: "Closed";
                font-size: 10px;
                color: #991b1b;
                background-color: #fcdcdc;
                /* pale red background */
                padding: 2px 4px;
                border-radius: 4px;
                position: absolute;
                bottom: 4px;
                right: 4px;
                z-index: -10;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- FullCalendar JS -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var statusButtons = document.querySelectorAll('.status-filter-btn');
                var selectedStatus = ''; // Default to all statuses
                let specificClosedDates = [];
                let repeatClosedDays = [];

                // Fetch closed days first
                fetch('{{ route('calendar.closed-days') }}')
                    .then(response => response.json())
                    .then(data => {
                        specificClosedDates = data.specificDates; // e.g. ['2025-04-15', '2025-04-16']
                        repeatClosedDays = data.repeatDays; // e.g. ['sunday', 'saturday']
                        initCalendar(); // Only initialize calendar after data is ready
                    });

                // Function to fetch events from the server
                function fetchEvents(fetchInfo, successCallback, failureCallback) {
                    fetch('{{ route('appointments.fetch') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                start: fetchInfo.startStr,
                                end: fetchInfo.endStr,
                                status: selectedStatus, // Pass the selected status
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            data.sort((a, b) => {
                                const dateA = new Date(`${a.start}T${a.time}`);
                                const dateB = new Date(`${b.start}T${b.time}`);
                                return dateA - dateB; // Ascending order
                            });

                            console.log('Sorted Events:', data);

                            // Map and style events based on status
                            const styledEvents = data.map((event) => {
                                let bgColor;
                                switch (event.status) {
                                    case 'CONFIRMED':
                                        bgColor = '#3B82F6'; // Green for CONFIRMED
                                        break;
                                    case 'PENDING':
                                        bgColor = '#6B7280'; // Gray for PENDING
                                        break;
                                    case 'CANCELLED':
                                        bgColor = '#F59E0B'; // Yellow for CANCELLED
                                        break;
                                    case 'REJECTED':
                                        bgColor = '#EF4444'; // Red for REJECTED
                                        break;
                                    case 'COMPLETED':
                                        bgColor = '#10B981'; // Green for COMPLETED
                                        break;
                                    default:
                                        bgColor = '#061c34'; // primary color
                                        break;
                                }

                                // Add time to title for better clarity
                                const time = event.time ? `${event.time} - ` : '';
                                return {
                                    ...event,
                                    title: `${time}${event.title}`,
                                    backgroundColor: bgColor, // FullCalendar property for background color
                                    borderColor: bgColor, // Ensure consistent border color
                                    textColor: '#ffffff', // Ensure text is readable
                                };
                            });

                            // Call success callback with the styled events
                            successCallback(styledEvents);
                        })
                        .catch((error) => failureCallback(error));
                }


                function initCalendar() {
                    // Initialize FullCalendar
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        selectable: true,
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay',
                        },
                        events: fetchEvents, // Events fetched via the `fetchEvents` function
                        eventClick: function(info) {
                            window.location.href =
                                '{{ route('filament.admin.resources.appointments.view', ['record' => '__appointmentId__']) }}'
                                .replace('__appointmentId__', info.event.id);
                        },
                        dateClick: function(info) {
                            const selectedDate = info.dateStr;
                            const today = new Date().toISOString().split('T')[0];

                            const isClosedSpecific = specificClosedDates.includes(selectedDate);
                            const dayName = info.date.toLocaleString('en-US', {
                                weekday: 'long'
                            }).toLowerCase();
                            const isRepeatClosed = repeatClosedDays.includes(dayName);

                            if (selectedDate <= today) {
                                alert('You cannot select a past date.');
                                return;
                            }

                            // Block closed or past dates
                            if (isClosedSpecific || isRepeatClosed) {
                                alert('This date is closed.');
                                return;
                            }

                            window.location.href =
                                '{{ route('filament.admin.resources.appointments.create') }}' +
                                '?date=' + selectedDate;
                        },
                        eventOrder: "-time_id", // Sort events by start date
                        validRange: {
                            start: '2020-01-01',
                            end: '2099-12-31',
                        },
                        datesSet: function() {
                            const today = new Date().toISOString().split('T')[0];
                            const allCells = document.querySelectorAll('.fc-daygrid-day-frame');

                            allCells.forEach(function(cell) {
                                const cellDate = cell.closest('.fc-daygrid-day')?.dataset.date;
                                if (!cellDate) return;

                                const dateObj = new Date(cellDate);
                                const dayName = dateObj.toLocaleString('en-US', {
                                    weekday: 'long'
                                }).toLowerCase();

                                if (cellDate < today) {
                                    cell.classList.add('past-date');
                                } else if (cellDate === today) {
                                    cell.classList.add('present-date');
                                }

                                if (specificClosedDates.includes(cellDate) || repeatClosedDays
                                    .includes(
                                        dayName)) {
                                    cell.classList.add('closed-date');
                                }
                            });
                        },

                        // Use 'datesRender' to apply inactive styles for past/present dates
                        datesRender: function() {
                            const today = new Date().toISOString().split('T')[0];
                            const specificDate =
                                '2025-01-24'; // Replace with your desired specific date (e.g., '2025-01-25')

                            // Loop through all cells and add classes for past, present, and specific dates
                            const allCells = document.querySelectorAll('.fc-daygrid-day-frame');
                            allCells.forEach(function(cell) {
                                const cellDate = cell.getAttribute('data-date');
                                if (cellDate < today) {
                                    cell.classList.add('past-date'); // Add class for past dates
                                } else if (cellDate === today) {
                                    cell.classList.add('present-date'); // Add class for today
                                }

                                // Set color for specific date
                                if (cellDate === specificDate) {
                                    cell.classList.add(
                                        'closed-date'); // Add class for the specific date
                                }
                            });
                        },
                    });

                    calendar.render();

                    // Handle status filter buttons
                    statusButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
                            // Remove active class from all buttons
                            statusButtons.forEach((btn) => btn.classList.remove('active'));
                            // Add active class to the clicked button
                            this.classList.add('active');
                            // Update selected status
                            selectedStatus = this.getAttribute('data-status');
                            // Refetch calendar events
                            calendar.refetchEvents();
                        });
                    });
                }
            });
        </script>
    @endpush
</x-filament-panels::page>
