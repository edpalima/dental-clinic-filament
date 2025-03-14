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
        <h1>Appointment Confirmation</h1>
        <p>Dear Customer,</p>
        <p>This email confirms your upcoming appointment on <strong>{{ $data['date'] }}</strong>,
            <strong>{{ $data['dayOfWeek'] }}</strong> at <strong>{{ $data['time'] }}</strong>.
        </p>
        <p>You are scheduled for <strong>{{ $data['procedures'] ?? 'your procedure' }}</strong>.</p>
        <p>The appointment will take place at Almoro Santiago Dental Clinic.</p>
        <p>We appreciate you and look forward to providing you with excellent service.</p>
        <p>Thank you!</p>

        <div class="footer">
            Almoro Santiago Dental Clinic
        </div>
    </div>
</body>

</html>
