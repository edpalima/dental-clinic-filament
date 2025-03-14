<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
            line-height: 1.4;
            font-size: 0.8rem;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 80px;
            margin-bottom: 5px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #4A90E2;
        }

        .header p {
            margin: 2px 0;
            font-size: 0.9rem;
        }

        .report-details {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.8rem;
        }

        .report-details p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #4A90E2;
            color: #fff;
            font-size: 0.9rem;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .footer {
            text-align: center;
            font-size: 0.7rem;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        {{-- <img src="{{ url('assets/img/logo.png') }}" alt="Company Logo"> --}}
        <img src="{{ public_path('assets/img/logo.png') }}" alt="Company Logo">
        <h1>{{ $companyName }}</h1>
        <p>{{ $companyAddress }}</p>
        <p>Contact: {{ $companyContact }}</p>
    </div>

    <div class="report-details">
        <h2>Appointment Report</h2>
        <p><strong>Start Date:</strong> {{ $startDate }}</p>
        <p><strong>End Date:</strong> {{ $endDate }}</p>
        @if (isset($patient->fullname))
            <p><strong>Patient:</strong> {{ $patient->fullname ?? 'ALL' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Status</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->id }}</td>
                    <td>{{ $appointment->patient ? $appointment->patient->fullname : 'N/A' }}</td>
                    <td>{{ $appointment->doctor ? $appointment->doctor->fullname : 'N/A' }}</td>
                    <td>{{ $appointment->status }}</td>
                    <td>{{ $appointment->date }}</td>
                    <td>{{ $appointment->time->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F j, Y, g:i a') }}</p>
        <p>Prepared By: {{ $preparedBy }}</p>
    </div>

</body>

</html>