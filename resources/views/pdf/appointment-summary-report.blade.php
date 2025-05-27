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
            line-height: 1.5;
            background-color: #f8f8f8;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
            color: #4A90E2;
        }

        .header p {
            margin: 5px 0;
            font-size: 0.9rem;
            color: #555;
        }

        .report-details {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .report-details h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .report-details p {
            margin: 5px 0;
            font-size: 0.9rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #4A90E2;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #eef;
        }

        .footer {
            text-align: center;
            font-size: 0.8rem;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header with logo and company info -->
        <div class="header">
            <img src="{{ public_path('assets/img/logo.jpg') }}" alt="Company Logo">
            <h1>{{ $companyName }}</h1>
            <p>{{ $companyAddress }}</p>
            <p>Contact: {{ $companyContact }}</p>
        </div>

        <!-- Report Summary -->
        <div class="report-details">
            <h2>Appointment Report</h2>
            <p><strong>Start Date:</strong> {{ $startDate }}</p>
            <p><strong>End Date:</strong> {{ $endDate }}</p>
            @if(isset($patient->fullname))
                <p><strong>Patient:</strong> {{ $patient->fullname ?? 'ALL' }}</p>
            @endif
        </div>

        <!-- Appointments Table -->
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Confirmed</td>
                    <td>{{ $confirmedCount }}</td>
                </tr>
                <tr>
                    <td>Pending</td>
                    <td>{{ $pendingCount }}</td>
                </tr>
                <tr>
                    <td>Rejected</td>
                    <td>{{ $rejectedCount }}</td>
                </tr>
                <tr>
                    <td>Completed</td>
                    <td>{{ $completedCount }}</td>
                </tr>
                <tr>
                    <td>Cancelled</td>
                    <td>{{ $cancelledCount }}</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>{{ $totalCount }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ now()->format('F j, Y, g:i a') }}</p>
            <p>Prepared By: {{ $preparedBy }}</p>
        </div>
    </div>
</body>

</html>
