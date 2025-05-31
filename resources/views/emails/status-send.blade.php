<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            color: #4A90E2;
            font-size: 1.5rem;
        }

        p {
            margin: 10px 0;
        }

        .footer {
            font-size: 0.9rem;
            color: #777;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Appointment {{ strtoupper($data['status']) }}</h1>

        <p><strong>Appointment No:</strong> #{{ str_pad($data['id'], 5, '0', STR_PAD_LEFT) }}</p>

        <p>Dear Customer,</p>

        <p>
            This email is to inform you that your appointment on
            <strong>{{ $data['date'] }}</strong>,
            <strong>{{ $data['dayOfWeek'] }}</strong> at
            <strong>{{ $data['time'] }}</strong> has been
            <strong style="text-transform: uppercase;">{{ $data['status'] }}</strong>.
        </p>

        <p>
            You were scheduled for <strong>{{ $data['procedures'] }}</strong>.
        </p>

        <p>
            The appointment location is Almoro Santiago Dental Clinic.
        </p>

        @if (strtolower($data['status']) === 'cancelled')
        <p style="color: red;"><strong>Please contact us to reschedule your appointment.</strong></p>
        @else
        <p>We appreciate you and look forward to providing you with excellent service.</p>
        @endif

        <p>
            Please review our
            <a href="{{ url('/consent-agreement') }}" target="_blank">Privacy Policy</a> and
            <a href="{{ url('/terms-and-conditions') }}" target="_blank">Terms & Conditions</a>.
        </p>

        <p>Thank you!</p>

        <div class="footer">
            Almoro Santiago Dental Clinic
        </div>
    </div>
</body>

</html>