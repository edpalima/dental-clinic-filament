<x-filament::page>
  <div style="margin-bottom: 20px;">
    <a href="{{ route('appointment-reports.pdf') }}" 
       style="display: inline-block; padding: 10px 20px; background-color: #061c34; color: white; text-align: center; text-decoration: none; border-radius: 5px;">
        Download Report
    </a>
</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th style="text-align: center;">Number of Appointments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td style="text-align: center;">{{ $appointment->date }}</td>
                    <td style="text-align: center;">{{ $appointment->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>