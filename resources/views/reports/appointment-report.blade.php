<!DOCTYPE html>
<html>
<head>
    <title>Appointment Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Appointment Reports</h2>
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
                    <td>{{ $appointment->date }}</td>
                    <td style="text-align: center;">{{ $appointment->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
