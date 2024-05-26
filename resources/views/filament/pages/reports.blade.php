<x-filament::page>
    <h2>Appointment Reports</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Number of Appointments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->date }}</td>
                    <td>{{ $appointment->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>